<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\ProfileCv2;

class CvController extends Controller
{
    public function analyzeCv(Request $request)
    {
        \Log::info('analyzeCv called with request:', $request->all());

        $request->validate([
            'cv_id' => 'required|exists:profile_cvs,id',
        ]);

        \Log::info('Validation successful for cv_id: ' . $request->cv_id);

        $cv = ProfileCv2::findOrFail($request->cv_id);

        \Log::info('cv->cv_file from database: ' . $cv->cv_file);

        $cvFilePath = public_path('cvs/' . $cv->cv_file);
        $txtFilePath = public_path('cvs/' . pathinfo($cv->cv_file, PATHINFO_FILENAME) . '.txt');

        \Log::info('CV file path: ' . $cvFilePath);
        \Log::info('TXT file path: ' . $txtFilePath);

        \Log::info('file_exists($cvFilePath): ' . (file_exists($cvFilePath) ? 'true' : 'false'));
        \Log::info('pathinfo($cvFilePath, PATHINFO_EXTENSION): ' . pathinfo($cvFilePath, PATHINFO_EXTENSION));

        try {
            if (file_exists($cvFilePath) && pathinfo($cvFilePath, PATHINFO_EXTENSION) == 'pdf') {
                \Log::info('Attempting to convert PDF to TXT...');
                exec('pdftotext "' . $cvFilePath . '" "' . $txtFilePath . '"');
                \Log::info('PDF converted to TXT successfully.');

                if (file_exists($txtFilePath)) {
                    \Log::info('Attempting to read TXT file...');
                    $cvContent = file_get_contents($txtFilePath);
                    \Log::info('TXT file read successfully.');
                } else {
                    return response()->json(['error' => 'Gagal membuat file TXT.'], 500);
                }
            } else {
                return response()->json(['error' => 'File tidak didukung atau format tidak valid.'], 400);
            }
        } catch (\Exception $pdfException) {
            \Log::error('Error processing PDF/TXT: ' . $pdfException->getMessage(), ['trace' => $pdfException->getTraceAsString()]);
            return response()->json(['error' => 'Error processing PDF file.'], 500);
        }

        $analysisPrompts = [
           
            "Identify 5 points key weakness of the candidate:\n\n",
            "write simple and short suggestion for improvement:\n\n",
          
        ];
        
        $results = [];
        
        try {
            $client = new Client();
            $apiKey = env('GEMINI_API_KEY');
        
            foreach ($analysisPrompts as $key => $prompt) {
                $fullPrompt = $prompt . "\n\n" . $cvContent;
        
                \Log::info('Sending prompt to Gemini API: ' . $fullPrompt);
        
                try {
                    $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                        'json' => [
                            'contents' => [
                                [
                                    'parts' => [
                                        ['text' => $fullPrompt],
                                    ],
                                ],
                            ],
                        ],
                        'timeout' => 10,
                        'connect_timeout' => 10,
                    ]);
        
                    \Log::info('Gemini API connection successful.');
                    \Log::info('Gemini API response status code: ' . $response->getStatusCode());
        
                    $rawResponse = $response->getBody()->getContents();
                    \Log::info('Raw API Response: ' . $rawResponse);
        
                    // Check if the response is valid JSON
                    if (json_decode($rawResponse) === null && json_last_error() !== JSON_ERROR_NONE) {
                        \Log::error('Invalid JSON response from Gemini API: ' . $rawResponse);
                        return response()->json(['status' => 'error', 'message' => 'Invalid JSON response from Gemini API.'], 500);
                    }
        
                    $responseBody = json_decode($rawResponse, true);
        
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        \Log::error('JSON decode error: ' . json_last_error_msg());
                        return response()->json(['status' => 'error', 'message' => 'JSON decode error: ' . json_last_error_msg()], 500);
                    }
        
                    $responseText = $responseBody['candidates'][0]['content']['parts'][0]['text'] ?? 'No content returned';
                    
                    // Format the response text with proper spacing
                    $formattedResponse = implode("\n\n", array_filter(array_map('trim', explode("\n", $responseText))));
                    $results[$key] = $formattedResponse;
        
                    \Log::info('Analysis result for ' . $key . ': ' . $formattedResponse);
        
                } catch (\GuzzleHttp\Exception\RequestException $guzzleException) {
                    \Log::error('Guzzle HTTP Request Exception: ' . $guzzleException->getMessage(), ['request' => $guzzleException->getRequest(), 'response' => $guzzleException->getResponse()]);
                    return response()->json(['status' => 'error', 'message' => 'Error connecting to Gemini API.'], 500);
                } catch (\Exception $e) {
                    \Log::error('General Exception during Gemini API call: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                    return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
                }
            }
        
            return response()->json(['results' => $results]);
        
        } catch (\Exception $e) {
            \Log::error('Error with Gemini API: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
        }
    }
}
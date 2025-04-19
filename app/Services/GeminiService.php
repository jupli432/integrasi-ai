<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
    }

    public function analyzeCandidate(array $candidateData, string $query): array
    {
        try {
            $prompt = $this->buildStrictPrompt($candidateData, $query);

            $response = Http::timeout(30)->post("{$this->apiUrl}?key={$this->apiKey}", [
                'contents' => [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error: ' . $response->body());
                return $this->defaultAnalysis();
            }

            return $this->parseResponse($response->json());

        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());
            return $this->defaultAnalysis();
        }
    }

    public function analyzeAndSortCandidates(array $candidates, string $query): array
    {
        $results = [];

        foreach ($candidates as $candidate) {
            try {
                $candidateData = [
                    'cv' => $this->truncateText($candidate['cv'] ?? ''), 
                    'skills' => $candidate['skills'] ?? [],
                    'educations' => $candidate['educations'] ?? [],
                    'experiences' => $candidate['experiences'] ?? [],
                ];

                $analysis = $this->analyzeCandidate($candidateData, $query);

                // Validasi ketat
                $score = $this->normalizeScore($analysis['score'] ?? 0);
                $strengths = $this->validateStrengths($analysis['strengths'] ?? []);
                $analysisText = $this->validateAnalysis($analysis['analysis'] ?? '');

                if ($score <= 0 || empty($strengths) || empty($analysisText)) {
                    Log::debug('Candidate skipped', [
                        'id' => $candidate['id'] ?? null,
                        'reason' => match(true) {
                            $score <= 0 => 'Invalid score',
                            empty($strengths) => 'No valid strengths',
                            default => 'Invalid analysis'
                        }
                    ]);
                    continue;
                }

                $results[] = [
                    'id' => $candidate['id'] ?? null,
                    'image' => $candidate['image'] ?? null,
                    'name' => $candidate['name'] ?? 'Unknown Candidate',
                    'score' => $score,
                    'strengths' => $strengths,
                    'analysis' => $analysisText,
                ];

            } catch (\Exception $e) {
                Log::error('Error processing candidate', [
                    'error' => $e->getMessage(),
                    'candidate' => $candidate['id'] ?? null
                ]);
                continue;
            }
        }

        // Filter akhir
        $results = array_filter($results, fn($item) => 
            $item['score'] > 0 &&
            !empty($item['strengths']) &&
            !empty($item['analysis'])
        );

        // Urutkan
        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        return $results;
    }

    private function buildStrictPrompt(array $data, string $query): string
    {
        $cvSummary = $this->truncateText($data['cv'], 1000);
        $skills = implode(', ', $data['skills']);
        $education = $this->formatEducations($data['educations']);
        $experience = $this->formatExperiences($data['experiences']);

        return <<<PROMPT
**Job Analysis Request**
Position: {$query}
Required Skills: {$this->extractKeywords($query)}

**Candidate Profile**
CV Summary: {$cvSummary}
Skills: {$skills}
Education History:
{$education}
Work Experience:
{$experience}

**Analysis Requirements**
1. Berikan score 0% jika tidak relevan
2. Strengths harus spesifik ke posisi
3. Hindari generic statements

**Response Format**
Match Score: [1-100]%
Key Strengths:
- Specific strength 1
- Specific strength 2
Summary: [50-word analysis]
PROMPT;
    }

    private function extractKeywords(string $query): string
    {
        preg_match_all('/\b[\w+]+\b/', $query, $matches);
        return implode(', ', array_unique($matches[0]));
    }

    private function truncateText(string $text, int $length = 1000): string
    {
        return substr($text, 0, $length) ?: '';
    }

    private function normalizeScore($score): int
    {
        $score = (int)$score;
        return min(100, max(0, $score));
    }

    private function validateStrengths(array $strengths): array
    {
        $invalid = [
            'No strengths identified', 'Data not available', 
            'N/A', '', ' ', 'Generic skills'
        ];

        return array_values(array_filter(
            array_map('trim', $strengths),
            fn($s) => !in_array($s, $invalid) && strlen($s) > 5
        ));
    }

    private function validateAnalysis(string $analysis): string
    {
        $analysis = trim($analysis);
        return preg_match('/\b(unavailable|not provided|n\/a)\b/i', $analysis) 
            ? '' 
            : $analysis;
    }

    private function parseResponse(array $response): array
    {
        $text = $response['candidates'][0]['content']['parts'][0]['text'] ?? '';

        return [
            'score' => $this->extractStrictScore($text),
            'strengths' => $this->extractQualityStrengths($text),
            'analysis' => $this->extractMeaningfulSummary($text)
        ];
    }

    private function extractStrictScore(string $text): int
    {
        if (preg_match('/Match Score:\s*(\d+)%/i', $text, $matches)) {
            $score = (int)$matches[1];
            return ($score > 0 && $score <= 100) ? $score : 0;
        }
        return 0;
    }

    private function extractQualityStrengths(string $text): array
    {
        if (preg_match('/Key Strengths:\s*(.+?)(?=\nSummary:|$)/is', $text, $matches)) {
            return $this->validateStrengths(explode("\n- ", trim($matches[1])));
        }
        return [];
    }

    private function extractMeaningfulSummary(string $text): string
    {
        if (preg_match('/Summary:\s*(.+)/is', $text, $matches)) {
            return $this->validateAnalysis(trim($matches[1]));
        }
        return '';
    }

    private function formatEducations(array $educations): string
    {
        return collect($educations)->map(fn($e) => 
            "- {$this->truncateText($e['degree'])} at " .
            "{$this->truncateText($e['institution'])} " .
            "({$e['year']})"
        )->implode("\n");
    }

    private function formatExperiences(array $experiences): string
    {
        return collect($experiences)->map(fn($e) => 
            "- {$this->truncateText($e['position'])} at " .
            "{$this->truncateText($e['company'])} " .
            "({$e['duration']})"
        )->implode("\n");
    }

    private function defaultAnalysis(): array
    {
        return [
            'score' => 0,
            'strengths' => [],
            'analysis' => 'Analysis unavailable'
        ];
    }
}
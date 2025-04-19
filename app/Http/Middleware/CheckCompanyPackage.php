<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckCompanyPackage
{
    public function handle(Request $request, Closure $next)
    {
        $company = auth('company')->user();

        // Menambahkan log untuk memeriksa nilai yang diterima
        \Log::info('Checking company package:', [
            'cvs_package_id' => $company->cvs_package_id,
            'cvs_package_end_date' => $company->cvs_package_end_date,
            'availed_cvs_quota' => $company->availed_cvs_quota,
            'cvs_quota' => $company->cvs_quota
        ]);

        // Cek apakah perusahaan memiliki paket aktif
        if (!$company->cvs_package_id) {
            \Log::info('No CV package assigned to the company.');
            return redirect()->route('company.packages')
                ->with('error', __('Silakan upgrade paket Anda terlebih dahulu'));
        }

        // Cek apakah paket sudah expired
        if (!$company->cvs_package_end_date || Carbon::parse($company->cvs_package_end_date)->isPast()) {
            \Log::info('CV package expired or no end date set.');
            return redirect()->route('company.packages')
                ->with('error', __('Paket Anda sudah expired. Silakan upgrade paket Anda.'));
        }

        // Cek jika kuota habis
        if ($company->availed_cvs_quota >= $company->cvs_quota) {
            \Log::info('CV package quota exhausted.');
            return redirect()->route('company.packages')
                ->with('error', __('Kuota CV Anda habis. Silakan upgrade paket Anda.'));
        }

        return $next($request);
    }

    // Middleware CheckCompanyPackage.php
private function errorResponse($request, $message)
{
    if ($request->wantsJson()) {
        return response()->json([ // <-- HANYA INI YANG DI RETURN
            'success' => false,
            'message' => $message,
            'redirect' => route('company.packages')
        ], 403);
    }
    
    return redirect()->route('company.packages')->with('error', $message);
}
}

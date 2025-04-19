1.penambahan file di :
. folder controller :
- CvController.php

2. Penambahan file di dalam folder models:
Company.php
JobMo.php
JobSkill.php
MajorSubject.php
ProfileCv2.php
ProfileEducation.php
ProfileExperience.php
ProfileSkill.php
User.php

3. menambahkan baris coding berikut didalam file :routes ->front-routes -> site_user.php :

use App\Http\Controllers\CvController;

Route::get('aianalyze', 'UserController@aianalyze')->name('aianalyze');
Route::post('/analyze-cv', [CvController::class, 'analyzeCv'])->name('cv.analyze');

4. menambahkan baris coding berikut didalam file : routes ->front-routes -> company.php :
Route::group(['middleware' => ['company', 'company.package.active']], function() {
    Route::get('/resume-search', [CompanyController::class, 'resume_search_packages'])->name('company.resume.search');
    Route::post('/ai-search', [CompanyController::class, 'aiSearch'])->name('ai.search');
});

5. Menambahkan coding file didalam folder resources -> views -> user :
analyze.blade.php

6. menambahkan coding didalam file (views-> includes : user_dashboard_menu.blade

7. menambahkan fungsi search analyze di dalam folder (resources-> views -> company_resume_search_packages.blade.php

8. menambahkan coding didalam folder views -> includes :
user_dashboard_menu.blade.php
 <li class="{{ Request::url() == route('aianalyze') ? 'active' : '' }}"><a href="{{ route('aianalyze') }}"><i class="fas fa-spell-check" aria-hidden="true"></i> {{__('Resume Analyze')}}</a>
        </li>
9. Penambahan coding didalan folder controllers -> UserController.php :
public function aianalyze()
{
    $user = User::findOrFail(Auth::user()->id);
    return view('user.analyze')->with('user', $user);
}

10. Penambahan folder App -> Services berikut 2 filenya : CvAnalysisService.php dan GeminiService.php

11. Penambahan variable : 'company.package.active' => \App\Http\Middleware\CheckCompanyPackage::class, di dalam file kernel.php

12. Penambahan file : CheckCompanyPackage.php didalam folder http -> middleware
13. penambahan file: ai_results.blade di folder : resources -> view ->company -> partials

<?php

namespace App\Http\Controllers;

use App\Seo;
use App\Job;
use App\Company;
use App\User;
use App\FunctionalArea;
use App\Video;
use App\Testimonial;
use App\SiteSetting;
use App\Slider;
use App\Blog;
use Illuminate\Http\Request;
use App\Traits\CompanyTrait;
use App\Traits\FunctionalAreaTrait;
use App\Traits\CityTrait;
use App\Traits\JobTrait;
use App\Traits\Active;
use App\Helpers\DataArrayHelper;
use Illuminate\Support\Facades\App ;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class IndexController extends Controller
{

    use CompanyTrait;
    use FunctionalAreaTrait;
    use CityTrait;
    use JobTrait;
    use Active;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\View
     */
    public function index()
    {

        $topCompanyIds = $this->getCompanyIdsAndNumJobs(16);
        $topFunctionalAreaIds = $this->getFunctionalAreaIdsAndNumJobs(8);
        $topIndustryIds = $this->getIndustryIdsFromCompanies(12);
        $topCityIds = $this->getCityIdsAndNumJobs(8);
        $featuredJobs = Job::active()->withCount('appliedUsers')->featured()->notExpire()->limit(12)->orderBy('id', 'desc')->get();

        $companyPostedJobs = null;
        if (Auth::guard('company')->check()) {
            try {
                $companyId = Auth::guard('company')->user()->id;
                $companyPostedJobs = Job::active()->where('company_id', $companyId)->notExpire()->limit(12)->orderBy('id', 'desc')->get();
            } catch (\Throwable $th) {
            }
        }

        $latestJobs = Job::active()->withCount('appliedUsers')->notExpire()->orderBy('id', 'desc')->limit(18)->get();
        $blogs = Blog::orderBy('id', 'desc')->where('lang', 'like', App::getLocale())->limit(3)->get();
        $video = Video::getVideo();
        $testimonials = Testimonial::langTestimonials();

        $functionalAreas = DataArrayHelper::langFunctionalAreasArray();
        $countries = DataArrayHelper::langCountriesArray();
        $sliders = Slider::langSliders();

        $jobsCount = Job::active()->notExpire()->count();
        $seekerCount = User::active()->count();
        $companyCount = Company::active()->count();



        // return response()->json($featuredJobs);
        $seo = SEO::where('seo.page_title', 'like', 'front_index_page')->first();
        return view('welcome')
            ->with('topCompanyIds', $topCompanyIds)
            ->with('topFunctionalAreaIds', $topFunctionalAreaIds)
            ->with('topCityIds', $topCityIds)
            ->with('topIndustryIds', $topIndustryIds)
            ->with('featuredJobs', $featuredJobs)
            ->with('latestJobs', $latestJobs)
            ->with('blogs', $blogs)
            ->with('functionalAreas', $functionalAreas)
            ->with('countries', $countries)
            ->with('sliders', $sliders)
            ->with('video', $video)
            ->with('testimonials', $testimonials)
            ->with('jobsCount', $jobsCount)
            ->with('seekerCount', $seekerCount)
            ->with('companyCount', $companyCount)
            ->with('companyPostedJobs', $companyPostedJobs)
            ->with('seo', $seo);
    }

    public function allCategories(Request $request)

    {
        $functionalAreas = FunctionalArea::where('lang', 'en')->get();
        return view('job.categories', compact('functionalAreas'));
    }

    public function setLocale(Request $request)
    {
        $locale = $request->input('locale');
        $return_url = $request->input('return_url');
        $is_rtl = $request->input('is_rtl');
        $localeDir = ((bool) $is_rtl) ? 'rtl' : 'ltr';

        session(['locale' => $locale]);
        session(['localeDir' => $localeDir]);

        return Redirect::to($return_url);
    }

    public function checkTime()

    {
        $siteSetting = SiteSetting::findOrFail(1272);
        $t1 = strtotime(date('Y-m-d h:i:s'));
        $t2 = strtotime($siteSetting->check_time);
        $diff = $t1 - $t2;
        $hours = $diff / (60 * 60);
        if ($hours >= 1) {
            $siteSetting->check_time = date('Y-m-d h:i:s');
            $siteSetting->update();
            Artisan::call('schedule:run');
            echo 'done';
        } else {
            echo 'not done';
        }
    }
}

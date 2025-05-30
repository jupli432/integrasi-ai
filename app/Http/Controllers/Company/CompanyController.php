<?php


namespace App\Http\Controllers\Company;

use Mail;
use Hash;
use File;
use ImgUploader;
use Auth;
use Validator;
use DB;
use Input;
use Redirect;
use App\Subscription;
use Newsletter;
// use App\User;
use App\Company;
use App\CompanyMessage;
use App\ApplicantMessage;
use App\Country;
use App\CountryDetail;
use App\JobApplyRejected;
use App\State;
use App\City;
use App\Unlocked_users;
use App\Industry;
use App\FavouriteCompany;
use App\Package;
use App\FavouriteApplicant;
use App\OwnershipType;
use App\JobApply;
use Carbon\Carbon;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use App\Mail\CompanyContactMail;
use App\Mail\ApplicantContactMail;
use App\Mail\JobSeekerRejectedMailable;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Front\CompanyFrontFormRequest;
use App\Http\Controllers\Controller;
use App\Traits\CompanyTrait;
use App\Traits\Cron;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
// Tambahkan imports baru
use App\Services\GeminiService;
use Illuminate\Support\Facades\Log;
use App\Models\User;


class CompanyController extends Controller

{

    use CompanyTrait;
    use Cron;
    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('company', ['except' => ['companyDetail', 'sendContactForm','company.package.active']]);

        $this->runCheckPackageValidity();

    }

    public function index()

    {

        return view('company_home');

    }

    public function company_listing()

    {

        $data['companies']=Company::paginate(20);
        return view('company.listing')->with($data);

    }

    public function companyProfile()

    {
        $countries = DataArrayHelper::defaultCountriesArray();
        $industries = DataArrayHelper::defaultIndustriesArray();
        $ownershipTypes = DataArrayHelper::defaultOwnershipTypesArray();
        $company = Company::findOrFail(Auth::guard('company')->user()->id);

        return view('company.edit_profile')

                        ->with('company', $company)

                        ->with('countries', $countries)

                        ->with('industries', $industries)

                        ->with('ownershipTypes', $ownershipTypes);

    }



    public function updateCompanyProfile(CompanyFrontFormRequest $request)

    {

        $company = Company::findOrFail(Auth::guard('company')->user()->id);

        /*         * **************************************** */

        if ($request->hasFile('logo')) {

            $is_deleted = $this->deleteCompanyLogo($company->id);

            $image = $request->file('logo');

            $fileName = ImgUploader::UploadImage('company_logos', $image, $request->input('name'), 300, 300, false);

            $company->logo = $fileName;

        }

        /*         * ************************************** */

        $company->name = $request->input('name');

        $company->email = $request->input('email');

        if (!empty($request->input('password'))) {

            $company->password = Hash::make($request->input('password'));

        }

        $company->ceo = $request->input('ceo');
        $company->industry_id = $request->input('industry_id');
        $company->ownership_type_id = $request->input('ownership_type_id');
        $company->description = $request->input('description');
        $company->location = $request->input('location');
        $company->map = $request->input('map');
        $company->no_of_offices = $request->input('no_of_offices');
        $website = $request->input('website');
        $company->website = (false === strpos($website, 'http')) ? 'http://' . $website : $website;
        $company->no_of_employees = $request->input('no_of_employees');
        $company->established_in = $request->input('established_in');
        $company->fax = $request->input('fax');
        $company->phone = $request->input('phone');
        $company->facebook = $request->input('facebook');
        $company->twitter = $request->input('twitter');
        $company->linkedin = $request->input('linkedin');
        $company->google_plus = $request->input('google_plus');
        $company->pinterest = $request->input('pinterest');
        $company->country_id = $request->input('country_id');
        $company->state_id = $request->input('state_id');
        $company->city_id = $request->input('city_id');
		$company->is_subscribed = $request->input('is_subscribed', 0);
		
        $company->slug = Str::slug($company->name, '-') . '-' . $company->id;
        $company->update();

		
        flash(__('Company has been updated'))->success();

        return \Redirect::route('company.profile');

    }


    public function addToFavouriteApplicant(Request $request, $application_id, $user_id, $job_id, $company_id)

    {

        $data['user_id'] = $user_id;
        $data['job_id'] = $job_id;
        $data['company_id'] = $company_id;

        $data_save = FavouriteApplicant::create($data);
        flash(__('Job seeker has been added in favorites list'))->success();
        return \Redirect::route('applicant.profile', $application_id);

    }



    public function removeFromFavouriteApplicant(Request $request, $application_id, $user_id, $job_id, $company_id)

    {

        $data['user_id'] = $user_id;
        $data['job_id'] = $job_id;
        $data['company_id'] = $company_id;

        FavouriteApplicant::where('user_id', $user_id)

                ->where('job_id', '=', $job_id)

                ->where('company_id', '=', $company_id)

                ->delete();



        flash(__('Job seeker has been removed from favorites list'))->success();

        return \Redirect::route('applicant.profile', $application_id);

    } 


    public function hireFromFavouriteApplicant(Request $request, $application_id, $user_id, $job_id, $company_id)

    {

        $data['user_id'] = $user_id;
        $data['job_id'] = $job_id;
        $data['company_id'] = $company_id;
        $fev = FavouriteApplicant::where('user_id', $user_id)

                ->where('job_id', '=', $job_id)

                ->where('company_id', '=', $company_id)

                ->first();

        $fev->status = 'hired';
        $fev->update();        

        flash(__('Job seeker has been Hired from favorites list'))->success();

        return \Redirect::route('applicant.profile', $application_id);

    }

    public function removehireFromFavouriteApplicant(Request $request, $application_id, $user_id, $job_id, $company_id)

    {

        $data['user_id'] = $user_id;
        $data['job_id'] = $job_id;
        $data['company_id'] = $company_id;
        $fev = FavouriteApplicant::where('user_id', $user_id)
                ->where('job_id', '=', $job_id)

                ->where('company_id', '=', $company_id)

                ->first();

        $fev->status = null;
        $fev->update();        

        flash(__('Job seeker has been removed from hired list'))->success();

        return \Redirect::route('applicant.profile', $application_id);

    }

    public function companyDetail(Request $request, $company_slug)

    {

        $company = Company::where('slug', 'like', $company_slug)->firstOrFail();

        /*         * ************************************************** */

        $seo = $this->getCompanySEO($company);

        /*         * ************************************************** */

        return view('company.detail')

                        ->with('company', $company)
                        ->with('seo', $seo);

    }



    public function sendContactForm(Request $request)

    {

        $msgresponse = Array();
        $rules = array(

            'from_name' => 'required|max:100|between:4,70',

            'from_email' => 'required|email|max:100',

            'subject' => 'required|max:200',

            'message' => 'required',

            'to_id' => 'required',

            'g-recaptcha-response' => 'required|captcha',

        );

        $rules_messages = array(

            'from_name.required' => __('Name is required'),

            'from_email.required' => __('E-mail address is required'),

            'from_email.email' => __('Valid e-mail address is required'),

            'subject.required' => __('Subject is required'),

            'message.required' => __('Message is required'),

            'to_id.required' => __('Recieving Company details missing'),

            'g-recaptcha-response.required' => __('Please verify that you are not a robot'),

            'g-recaptcha-response.captcha' => __('Captcha error! try again'),

        );

        $validation = Validator::make($request->all(), $rules, $rules_messages);

        if ($validation->fails()) {

            $msgresponse = $validation->messages()->toJson();

            echo $msgresponse;

            exit;

        } else {

            $receiver_company = Company::findOrFail($request->input('to_id'));
            $data['company_id'] = $request->input('company_id');
            $data['company_name'] = $request->input('company_name');
            $data['from_id'] = $request->input('from_id');
            $data['to_id'] = $request->input('to_id');
            $data['from_name'] = $request->input('from_name');
            $data['from_email'] = $request->input('from_email');
            $data['from_phone'] = $request->input('from_phone');
            $data['subject'] = $request->input('subject');
            $data['message_txt'] = $request->input('message');
            $data['to_email'] = $receiver_company->email;
            $data['to_name'] = $receiver_company->name;
            $msg_save = CompanyMessage::create($data);
            $when = Carbon::now()->addMinutes(5);
            Mail::send(new CompanyContactMail($data));

            $msgresponse = ['success' => 'success', 'message' => __('Message sent successfully')];

            echo json_encode($msgresponse);

            exit;

        }

    }



    public function sendApplicantContactForm(Request $request)

    {

        $msgresponse = Array();

        $rules = array(

            'from_name' => 'required|max:100|between:4,70',

            'from_email' => 'required|email|max:100',

            'subject' => 'required|max:200',

            'message' => 'required',

            'to_id' => 'required',

        );

        $rules_messages = array(

            'from_name.required' => __('Name is required'),

            'from_email.required' => __('E-mail address is required'),

            'from_email.email' => __('Valid e-mail address is required'),

            'subject.required' => __('Subject is required'),

            'message.required' => __('Message is required'),

            'to_id.required' => __('Recieving applicant details missing'),

            'g-recaptcha-response.required' => __('Please verify that you are not a robot'),

            'g-recaptcha-response.captcha' => __('Captcha error! try again'),

        );

        $validation = Validator::make($request->all(), $rules, $rules_messages);

        if ($validation->fails()) {

            $msgresponse = $validation->messages()->toJson();

            echo $msgresponse;

            exit;

        } else {

            $receiver_user = User::findOrFail($request->input('to_id'));

            $data['user_id'] = $request->input('user_id');

            $data['user_name'] = $request->input('user_name');

            $data['from_id'] = $request->input('from_id');

            $data['to_id'] = $request->input('to_id');

            $data['from_name'] = $request->input('from_name');

            $data['from_email'] = $request->input('from_email');

            $data['from_phone'] = $request->input('from_phone');

            $data['subject'] = $request->input('subject');

            $data['message_txt'] = $request->input('message');

            $data['to_email'] = $receiver_user->email;

            $data['to_name'] = $receiver_user->getName();

            $msg_save = ApplicantMessage::create($data);

            $when = Carbon::now()->addMinutes(5);

            Mail::send(new ApplicantContactMail($data));

            $msgresponse = ['success' => 'success', 'message' => __('Message sent successfully')];

            echo json_encode($msgresponse);

            exit;

        }

    }



    public function postedJobs(Request $request)

    {

        $jobs = Auth::guard('company')->user()->jobs()->paginate(10);

        return view('job.company_posted_jobs')

                        ->with('jobs', $jobs);

    }



    public function listAppliedUsers(Request $request, $job_id)

    {

        $job_applications = JobApply::where('job_id', '=', $job_id)->get();



        return view('job.job_applications')

                        ->with('job_applications', $job_applications);

    }



    public function listHiredUsers(Request $request, $job_id)

    {

        $company_id = Auth::guard('company')->user()->id;

        $user_ids = FavouriteApplicant::where('job_id', '=', $job_id)->where('company_id', '=', $company_id)->where('status','hired')->pluck('user_id')->toArray();

        $job_applications = JobApply::where('job_id', '=', $job_id)->whereIn('user_id', $user_ids)->get();

        return view('job.hired_applications')

                        ->with('job_applications', $job_applications);

    }


    public function listRejectedUsers(Request $request, $job_id)

    {

        $job_applications = JobApplyRejected::where('job_id', '=', $job_id)->get();

        return view('job.job_rejected_users')

                        ->with('job_applications', $job_applications);

    }


    public function listFavouriteAppliedUsers(Request $request, $job_id)

    {

        $company_id = Auth::guard('company')->user()->id;

        $user_ids = FavouriteApplicant::where('job_id', '=', $job_id)->where('company_id', '=', $company_id)->where('status',null)->pluck('user_id')->toArray();

        $job_applications = JobApply::where('job_id', '=', $job_id)->whereIn('user_id', $user_ids)->get();

        return view('job.job_applications')

                        ->with('job_applications', $job_applications);

    }



    public function applicantProfile($application_id)

    {


        $job_application = JobApply::findOrFail($application_id);

        $user = $job_application->getUser();

        $job = $job_application->getJob();

        $company = $job->getCompany();

        $profileCv = $job_application->getProfileCv();

        /*         * ********************************************** */

        $num_profile_views = $user->num_profile_views + 1;

        $user->num_profile_views = $num_profile_views;

        $user->update();

        $is_applicant = 'yes';

        /*         * ********************************************** */

        return view('user.applicant_profile')

                        ->with('job_application', $job_application)

                        ->with('user', $user)

                        ->with('job', $job)

                        ->with('company', $company)

                        ->with('profileCv', $profileCv)

                        ->with('page_title', 'Applicant Profile')

                        ->with('form_title', 'Contact Applicant')

                        ->with('is_applicant', $is_applicant);

    }

    public function rejectApplicantProfile($application_id)

    {



        $job_application = JobApply::findOrFail($application_id);

        $rej = new JobApplyRejected();

        $rej->apply_id = $job_application->id;

        $rej->user_id = $job_application->user_id;

        $rej->job_id = $job_application->job_id;

        $rej->cv_id = $job_application->cv_id;

        $rej->current_salary = $job_application->current_salary;

        $rej->expected_salary = $job_application->expected_salary;

        $rej->salary_currency = $job_application->salary_currency;

        $rej->save();

        $job = $rej->getJob();

        $job_application->delete();

        Mail::send(new JobSeekerRejectedMailable($job,$rej));

        flash(__('Job seeker has been rejected successfully'))->success();

        return \Redirect::route('rejected-users',$job->id);

    }



    public function userProfile($id)

    {

        $user = User::findOrFail($id);

        $profileCv = $user->getDefaultCv();

        /*         * ********************************************** */

        $num_profile_views = $user->num_profile_views + 1;

        $user->num_profile_views = $num_profile_views;

        $user->update();

        /*         * ********************************************** */

        return view('user.applicant_profile')

                        ->with('user', $user)

                        ->with('profileCv', $profileCv)

                        ->with('page_title', 'Job Seeker Profile')

                        ->with('form_title', 'Contact Job Seeker');

    }



    public function companyFollowers()

    {

        $company = Company::findOrFail(Auth::guard('company')->user()->id);

        $userIdsArray = $company->getFollowerIdsArray();

        $users = User::whereIn('id', $userIdsArray)->get();



        return view('company.follower_users')

                        ->with('users', $users)

                        ->with('company', $company);

    }



    public function companyMessages()

    {

        $company = Company::findOrFail(Auth::guard('company')->user()->id);

        $messages = CompanyMessage::where('company_id', '=', $company->id)

                ->orderBy('is_read', 'asc')

                ->orderBy('created_at', 'desc')

                ->get();



        return view('company.company_messages')

                        ->with('company', $company)

                        ->with('messages', $messages);

    }



    public function companyMessageDetail($message_id)

    {

        $company = Company::findOrFail(Auth::guard('company')->user()->id);

        $message = CompanyMessage::findOrFail($message_id);

        $message->update(['is_read' => 1]);



        return view('company.company_message_detail')

                        ->with('company', $company)

                        ->with('message', $message);

    }


    public function resume_search_packages()
{
    $company = Auth::guard('company')->user();
    
    // Middleware sudah menangani validasi, langsung ambil data
    $data = [
        'postedJobs' => $company->jobs()->paginate(10),
        'packages' => Package::where('package_for', 'cv_search')->get(),
        'success_package' => Package::find($company->cvs_package_id)
    ];

    return view('company_resume_search_packages', $data);
}

    public function unlocked_users()



    {

        $data = array();

        $unlocked_users = Unlocked_users::where('company_id', Auth::guard('company')->user()->id)->first();

        if (null !== ($unlocked_users)) {

            $data['users'] = User::whereIn('id', explode(',', $unlocked_users->unlocked_users_ids))->get();

        }

        //dd($data['users']);



        return view('company.unlocked_users')->with($data);

    }



    public function unlock($user_id)

    {

        $cvsSearch = Auth::guard('company')->user();
        //dd($cvsSearch);
        if($cvsSearch->cvs_package_id && $cvsSearch->cvs_package_end_date>= date('Y-m-d') && ($cvsSearch->cvs_quota-$cvsSearch->availed_cvs_quota)>0){


            if (null !== ($cvsSearch)) {

            if ($cvsSearch->availed_cvs_ids != '') {



                $newString = $this->addtoString($cvsSearch->availed_cvs_ids, $user_id);

            } else {

                $newString = $user_id;

            }



            $cvsSearch->availed_cvs_ids  = $newString;

            $cvsSearch->availed_cvs_quota += 1;

            $cvsSearch->update();



            $unlock = Unlocked_users::where('company_id', Auth::guard('company')->user()->id)->first();

            if (null !== ($unlock)) {

                $unlock->unlocked_users_ids  = $newString;

                $unlock->update();

            } else {

                $unlock = new Unlocked_users();



                $unlock->company_id  = Auth::guard('company')->user()->id;

                $unlock->unlocked_users_ids  = $newString;

                $unlock->save();

            }

            return redirect()->back();

        } else {

            return redirect('/company-packages');

        }


            
        }else{

            flash(__('Your Package has been expired!'))->error();

            return redirect('/company-packages');
            
        }

        

    }

    function addtoString($str, $item)

    {

        $parts = explode(',', $str);

        $parts[] = $item;

        return implode(',', $parts);

    }


    public function aiSearch(Request $request)
{
    try {
        $company = Auth::guard('company')->user();
        
        // Validasi paket perusahaan
        if (!$this->checkCompanyPackage()) {
            return response()->json([
                'success' => false,
                'message' => 'Paket Anda tidak valid atau kuota telah habis'
            ], 403);
        }

        // Ambil data kandidat dengan relasi yang diperlukan
        $candidates = User::with([
            'profileCv',
            'profileCv.skills.jobSkill',
            'profileCv.educations',
            'profileCv.experiences'
        ])->whereHas('profileCv')->get();

        $gemini = new GeminiService();
        $results = [];

        foreach ($candidates as $candidate) {
            // Format data untuk Gemini AI
            $candidateData = [
                'cv' => optional($candidate->profileCv)->cv_content ?? 'Tidak ada CV',
                'skills' => $candidate->profileCv->skills->pluck('jobSkill.job_skill')->toArray() ?? [],
                'educations' => $candidate->profileCv->educations->map(function($edu) {
                    return [
                        'degree' => $edu->degree_title,
                        'institution' => $edu->institution,
                        'year' => $edu->date_completion ? Carbon::parse($edu->date_completion)->format('Y') : 'Sekarang'
                    ];
                })->toArray(),
                'experiences' => $candidate->profileCv->experiences->map(function($exp) {
                    return [
                        'position' => $exp->title,
                        'company' => $exp->company,
                        'duration' => $this->calculateDuration($exp->date_start, $exp->date_end)
                    ];
                })->toArray()
            ];

            // Lakukan analisis dengan Gemini
            $analysis = $gemini->analyzeCandidate($candidateData, $request->ai_query);

            // Simpan hasil analisis
            $results[] = [
                'id' => $candidate->id,
                'name' => $candidate->name,
                'score' => $analysis['score'],
                'strengths' => $analysis['strengths'],
                'summary' => Str::limit($analysis['analysis'], 250)
            ];
        }

        // Update kuota perusahaan
        $company->availed_cvs_quota += count($results);
        $company->save();

        // return response()->json([
        //     'success' => true,
        //     'html' => view('company.partials.ai_results', compact('results'))->render()
        // ]);
        return view('company.partials.ai_results', compact('results'));

    } catch (\Exception $e) {
        Log::error('AI Search Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan dalam proses pencarian: ' . $e->getMessage()
        ], 500);
    }
}


    private function calculateDuration($start, $end)
    {
        $startDate = Carbon::parse($start);
        $endDate = $end ? Carbon::parse($end) : now();
        
        return $startDate->diffForHumans($endDate, true);
    }

private function checkCompanyPackage()
{
    $company = Auth::guard('company')->user();
    return $company->cvs_package_id && 
           $company->cvs_package_end_date >= now() && 
           $company->availed_cvs_quota < $company->cvs_quota;
}


private function extractStrengths(string $text): string
{
    // Contoh ekstraksi strengths dari teks
    preg_match('/Strengths:\s*(.+?)(\n|$)/i', $text, $matches);
    return $matches[1] ?? 'Analytical skills, Teamwork';
}

}


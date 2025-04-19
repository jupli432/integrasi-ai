<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\CompanyMessage;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class CompanyMessagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $post_input = 'post_input';
    public function __construct()
    {
        $this->middleware('company');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    function submitnew_message_seeker(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
        ], [
            'message.required' => 'Message is required.',
        ]);
        $message = new CompanyMessage();
        $message->company_id = Auth::guard('company')->user()->id;
        $message->message = $request->message;
        $message->seeker_id = $request->seeker_id;
        $message->type = 'reply';
        $message->save();

        $company = Company::where('id', Auth::guard('company')->user()->id)->first();
        $user = User::where('id', $request->seeker_id)->first();
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['company_name'] = $company->name;

        /*Mail::send(new MessageSendCompanyMail($data));*/
        if ($message->save() == true) {
            $arr = array('msg' => 'Your message have successfully been posted. ', 'status' => true);
        }
        return Response()->json($arr);
    }
    function submit_message(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
        ], [
            'message.required' => 'Message is required.',
        ]);
        $message = new CompanyMessage();
        $message->company_id = Auth::guard('company')->user()->id;
        $message->message = $request->message;
        $message->seeker_id = $request->seeker_id;
        $message->type = 'reply';
        $message->save();

        $company = Company::where('id', Auth::guard('company')->user()->id)->first();
        $user = User::where('id', $request->seeker_id)->first();
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['company_name'] = $company->name;

        // Mail::send(new MessageSendCompanyMail($data));
        if ($message->save() == true) {
            $seeker_id = $request->seeker_id;
            $company_id = Auth::guard('company')->user()->id;
            $messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();
            $seeker = User::where('id', $seeker_id)->first();
            $company = Company::where('id', $company_id)->first();
            $search = view("company.appendonly-messages", compact('messages', 'seeker', 'company'))->render();
            return $search;
        }
    }
    public function all_messages()
    {
        $messages = CompanyMessage::where('company_id', Auth::guard('company')->user()->id)->get();
        $ids = array();
        foreach ($messages as $key => $value) {
            $ids[] = $value->seeker_id;
        }

        $seekers = User::whereIn('id', $ids)
            ->with(['companyMessages' => function ($query) {
                $q =   $query
                    ->orderBy('created_at', 'desc')
                    ->where('company_id', Auth::guard('company')->user()->id)
                    ->limit(1);

                Log::info('' . $q->toSql());

                return $q;
            }])
            ->get();

        $data['seekers'] = $seekers->map(function (?User $seeker) {
            if ($seeker == null) return null;

            $seeker->lastUserMessage = $seeker?->lastUserMessages();
            return $seeker;
        });

        // return response()->json($data);
        return view('company.all-messages')->with($data);
    }
    public function append_messages(Request $request)
    {
        $seeker_id = $request->get('seeker_id');
        $company_id = Auth::guard('company')->user()->id;
        $messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();
        $seeker = User::where('id', $seeker_id)->first();
        $company = Company::where('id', $company_id)->first();
        $search = view("company.append-messages", compact('messages', 'seeker', 'company'))->render();
        return $search;
    }
    public function appendonly_messages(Request $request)
    {
        $seeker_id = $request->get('seeker_id');
        $company_id = Auth::guard('company')->user()->id;
        $messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();
        $seeker = User::where('id', $seeker_id)->first();
        $company = Company::where('id', $company_id)->first();
        $search = view("company.appendonly-messages", compact('messages', 'seeker', 'company'))->render();
        $data = array();
        $data['html_data'] = $search;
        $data['seeker_id'] = $seeker_id;
        return Response::json($data);
    }

    public function change_message_status(Request $request)
    {
        $company_id = Auth::guard('company')->user()->id;
        $seeker_id = $request->get('sender_id');
        $messages = CompanyMessage::where('company_id', $company_id)->where('seeker_id', $seeker_id)->get();
        if ($messages) {
            foreach ($messages as $key => $value) {
                $message = CompanyMessage::findOrFail($value->id);
                $message->status = 'viewed';
                $message->update();
            }
        }
        echo 'done';
    }
}

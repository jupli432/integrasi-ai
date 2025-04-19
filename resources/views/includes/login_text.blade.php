<div class="userloginbox">
    <div class="usrintxt">
        <div class="titleTop">
            <h3>{{ __('Looking for Your Dream Job?') }} </h3>
            <h4>{{ __('Find the Perfect Opportunity for You') }}</h4>
        </div>
        <p>Take the next step in your career by exploring job opportunities that match your skills and interests.
            Connect with top employers, showcase your talents, and land your ideal job. Start building your CV and apply
            with confidence to get hired faster.</p>

        @if (!Auth::user() && !Auth::guard('company')->user())
            <div class="viewallbtn"><a href="{{ route('register') }}"> {{ __('Get Started Today') }} </a></div>
        @else
            <div class="viewallbtn"><a href="{{ url('my-profile') }}">{{ __('Build Your CV') }} </a></div>
        @endif
    </div>
</div>

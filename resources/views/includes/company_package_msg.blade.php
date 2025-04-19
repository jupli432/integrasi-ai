<div class="instoretxt custom-card">
    <div class="credit">
        <div class="instore-title">
            {{ __('Your Package is') }}
        </div>

        <div class="instore-body">
            {{ $package->package_title }} - {{ $siteSetting->default_currency_code }}{{ $package->package_price }}
        </div>

    </div>


    <div class="credit">

        <div class="instore-title">
            {{ __('Package Duration') }}
        </div>
        @php
            $packageStartDate = \Carbon\Carbon::parse(Auth::guard('company')->user()->package_start_date);
            $packageDuration = Auth::guard('company')->user()->package_duration; // Assuming this field holds the duration
            $packageEndDate = $packageStartDate->copy()->addDays($packageDuration);
        @endphp
        <div class="instore-body">
            {{ $packageStartDate->format('jS F Y') }} - {{ $packageEndDate->format('jS F Y') }}
        </div>

    </div>

    <div class="credit">
        <div class="instore-title">
            {{ __('Availed quota') }} :
        </div>
        <div class="instore-body">
            {{ Auth::guard('company')->user()->availed_jobs_quota }}/{{ Auth::guard('company')->user()->jobs_quota }}
        </div>
    </div>

</div>

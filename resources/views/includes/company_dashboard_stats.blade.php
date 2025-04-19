<div class="card-information-company-dashboard-container">
    <div class="card-information-company-dashboard-item-yellow">
        {{ Auth::guard('company')->user()->countOpenJobs() }}
        <div class="card-information-company-dashboard-item-label">
            {{ __('Open Jobs') }}
        </div>
    </div>
    <div class="card-information-company-dashboard-item-green">
        {{ Auth::guard('company')->user()->countFollowers() }}
        <div class="card-information-company-dashboard-item-label">
            {{ __('Followers') }}
        </div>
    </div>
    <div class="card-information-company-dashboard-item-blue">
        {{ Auth::guard('company')->user()->countCompanyMessages() }}
        <div class="card-information-company-dashboard-item-label">
            {{ __('Messages') }}
        </div>
    </div>
</div>


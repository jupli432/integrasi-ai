<a href="{{ route('company.detail', $company->slug) }}">
    <div class="company-card" title="{{ $company->name }}">
        <div class="company-card-container">
            <div class="company-card-header">
                <div class="company-card-header-top">
                    <img src="{{ url('/') . '/company_logos/' . $company->logo }}" alt="Company logo"
                        class="img-fluid company-logo"
                        onerror="this.onerror=null;this.src='{{ url('/') }}/admin_assets/no-image.png';">

                    <div class="job-count">
                            {{ $company->countNumJobs('company_id', $company->id) }} {{ __('Jobs') }}
                    </div>
                </div>
                <div class="company-name">
                    {{ $company->name }}
                </div>
                <div class="company-location">
                    {{ $company->getCity('city') }}
                </div>
            </div>
            <div class="company-status">
                <i class="fa-regular fa-clock"></i>
                <span>
                    @if ($company->isOnline())
                        {{ _('Online') }}
                    @else
                        Last active {{ $company->lastOnlineDisplay() }}
                    @endif
                </span>
            </div>
        </div>
    </div>
</a>

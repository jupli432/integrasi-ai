<div class="section">
    <div class="container-fluid">

        <!-- title start -->
        <div class="titleTop">
            <h3>{{ __('Latest Jobs') }}</h3>
        </div>
        <!-- title end -->
        <div class="mt-lg-4 mt-2">
            <ul class="jobslist row row-cols-1  row-cols-md-2 g-2 g-lg-4 ">
                @if (isset($latestJobs) && count($latestJobs))
                    @foreach ($latestJobs as $latestJob)
                        <?php $company = $latestJob->getCompany(); ?>
                        @if (null !== $company)
                            <div class="col {{ $loop->index >= 4 ? 'd-none d-lg-block' : '' }}">
                                <div class="job-posted-container">
                                    <img class="job-posted-logo" src="{{ url('/') . '/company_logos/' . $company->logo }}"
                                        alt="Company logo" class="img-fluid"
                                        onerror="this.onerror=null;this.src='{{ url('/') }}/admin_assets/no-image.png';">
                                    <div class="job-posted-details">
                                        <div class="job-posted-header">
                                            <div class="job-posted-title">
                                                <a href="{{ route('job.detail', [$latestJob->slug]) }}"
                                                    title="{{ $latestJob->title }}">{{ $latestJob->title }}</a>

                                            </div>

                                            <div class="job-posted-meta" data-toggle="tooltip" data-placement="top"
                                                title="{{ $latestJob->created_at->format('M d, Y') }}">
                                                {{ $latestJob->created_at->diffForHumans() }}
                                            </div>

                                            {{-- <div class="job-posted-type-label">
                                            <div class="job-posted-candidate-count">
                                                {{ $latestJob->applied_users_count ?? 0 }} Candidates</div>
                                        </div> --}}
                                        </div>
                                        <div class="job-posted-info">
                                            <a href="{{ route('company.detail', $company->slug) }}"
                                                title="{{ $company->name }}" class="job-posted-meta">
                                                {{ $company->name }}
                                            </a>
                                            <div class="job-posted-separator"></div>
                                            <div class="job-posted-meta">
                                                {{ $latestJob->getCity('city') }}
                                            </div>
                                        </div>
                                        <div class="full-time">
                                            <label class="fulltime" title="{{ $latestJob->getJobType('job_type') }}">
                                                {{ $latestJob->getJobType('job_type') }}
                                            </label>
                                        </div>
                                        {{-- <div class="job-posted-meta">
                                        {{ $latestJob->created_at->diffForHumans() }}
                                    </div> --}}
                                    </div>
                                </div>
                            </div>
                            {{-- </li> --}}
                            <!--Job end-->
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>


        <!--view button-->
        <div class="viewallbtn  mt-lg-4 mt-2">
            <a href="{{ route('job.list') }}">
                {{ __('View All Latest Jobs') }}
            </a>
        </div>
        <!--view button end-->
    </div>
</div>

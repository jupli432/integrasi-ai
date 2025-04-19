@isset($companyPostedJobs)

    <div class="section">
        <div class="container-fluid">
            <!-- title start -->
            <div class="titleTop">
                <h3>{{ __('Jobs Posted') }}</span></h3>
            </div>
            <!-- title end -->
            <div class="mt-lg-4 mt-2">
                <ul class="jobslist row row-cols-1  row-cols-md-2 g-2 g-lg-4 ">
                    @if (isset($companyPostedJobs) && count($companyPostedJobs))
                        @foreach ($companyPostedJobs as $job)
                            <?php $company = $job->getCompany(); ?>
                            @if (null !== $company)
                                <div class="col mb-4 {{ $loop->index >= 6 ? 'd-none d-lg-block' : '' }}">
                                    <div class="job-posted-container">
                                        <img class="job-posted-logo" src="{{ url('/') . '/company_logos/' . $company->logo }}"
                                            alt="Company logo" class="img-fluid"
                                            onerror="this.onerror=null;this.src='{{ url('/') }}/admin_assets/no-image.png';">
                                        <div class="job-posted-details">
                                            <div class="job-posted-header">
                                                <div class="job-posted-title">
                                                    <a href="{{ route('job.detail', [$job->slug]) }}"
                                                        title="{{ $job->title }}">{{ $job->title }}</a>

                                                </div>

                                                {{-- <div class="job-posted-meta" data-toggle="tooltip" data-placement="top"
                                                title="{{ $job->created_at->format('M d, Y') }}">
                                                {{ $job->created_at->diffForHumans() }}
                                            </div> --}}

                                                <div class="job-posted-type-label">
                                                    <div class="job-posted-candidate-count">
                                                        {{ $job->applied_users_count ?? 0 }} Candidates</div>
                                                </div>
                                            </div>
                                            <div class="job-posted-info">
                                                <a href="{{ route('company.detail', $company->slug) }}"
                                                    title="{{ $company->name }}" class="job-posted-meta">
                                                    {{ $company->name }}
                                                </a>
                                                <div class="job-posted-separator"></div>
                                                <div class="job-posted-meta">
                                                    {{ $job->getCity('city') }}
                                                </div>
                                            </div>
                                            <div class="full-time">
                                                <label class="fulltime" title="{{ $job->getJobType('job_type') }}">
                                                    {{ $job->getJobType('job_type') }}
                                                </label>
                                            </div>
                                            <div class="job-posted-meta">
                                                {{ $job->created_at->diffForHumans() }}
                                            </div>
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
                <a href="{{ route('job.list', ['company_ids[]' => Auth::guard('company')->user()->id]) }}">
                    {{ __('View All Jobs') }}
                </a>
            </div>
            <!--view button end-->
        </div>
    </div>

@endisset

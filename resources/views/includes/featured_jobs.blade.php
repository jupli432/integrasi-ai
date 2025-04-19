<div class="section featuredjobwrap">
    <div class="container-fluid">
        <!-- title start -->
        <div class="titleTop">
            <h3>{{ __('Featured Jobs') }}</h3>
        </div>
        <!-- title end -->

        <!--Featured Job start-->
        <div class="mt-lg-4 mt-2">
            <div class="row row-cols-2  row-cols-md-3 row-cols-lg-4 g-2 g-lg-4 ">
                @if (isset($featuredJobs) && count($featuredJobs))
                    @foreach ($featuredJobs as $featuredJob)
                        <?php $company = $featuredJob->getCompany(); ?>
                        @if (null !== $company)
                            <div class="col {{ $loop->index >= 6 ? 'd-none d-lg-block' : '' }}">
                                <div class="job h-100">
                                    <div class="featured-job-frame-parent">
                                        <div class="featured-job-frame-group">
                                            <div class="featured-job-title-parent">
                                                <a class="featured-job-title"
                                                    href="{{ route('job.detail', [$featuredJob->slug]) }}"
                                                    title="{{ $featuredJob->title }}">{{ $featuredJob->title }}</a>
                                                <div class="full-time">
                                                    <label class="fulltime"
                                                        title="{{ $featuredJob->getJobType('job_type') }}">{{ $featuredJob->getJobType('job_type') }}</label>
                                                </div>
                                            </div>
                                            <div class="featured-job-company-info">
                                                <div class="company-logo-parent">
                                                    <div class="company-logo-icon">
                                                        {{ $company->printCompanyImage() }}</div>

                                                    <div class="featured-job-company-detail">
                                                        <a class="featured-job-company-name"
                                                            href="{{ route('company.detail', $company->slug) }}"
                                                            title="{{ $company->name }}">{{ $company->name }}</a>
                                                        <div class="featured-job-company-location">
                                                            {{ $featuredJob->getCity('city') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="iconuitime-circle-parent">
                                            <div class="featured-job-company-name">
                                                {{-- <i class="fa-regular fa-clock"></i> --}}
                                                &nbsp;
                                                {{ $featuredJob->created_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <!--Featured Job end-->








        <!--button start-->
        <div class="viewallbtn  mt-lg-4 mt-2">
            <a href="{{ route('job.list', ['is_featured' => 1]) }}">
                {{ __('View All Featured Jobs') }}
            </a>
        </div>
        <!--button end-->
    </div>
</div>

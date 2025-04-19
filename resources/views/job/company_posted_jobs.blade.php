@extends('layouts.app')
@section('content')
    <!-- Header start -->
    @include('includes.header')
    <!-- Header end -->
    <!-- Inner Page Title start -->
    @include('includes.inner_page_title', ['page_title' => __('Company Posted Jobs')])
    <!-- Inner Page Title end -->
    <div class="listpgWraper">
        <div class="container-fluid">
            <div class="row">
                @include('includes.company_dashboard_menu')

                <div class="col-lg-9">
                    <div class=" custom-card">



                        <div class="h3">{{ __('Jobs Posted') }}</div>
                        <div class="mt-4">
                            <!-- job start -->
                            @if (isset($jobs) && count($jobs))
                                @foreach ($jobs as $job)
                                    @php
                                        $company = $job->getCompany();
                                        $appliedUsersCount = $job->appliedUsers->count();
                                    @endphp
                                    @if (null !== $company)
                                        <div class="job-posted-wrapper">
                                            <div class="job-posted-image">
                                                {{ $company->printCompanyImage(48, 48) }}
                                            </div>
                                            <div class="job-posted-content">
                                                <a class="h3" href="{{ route('job.detail', [$job->slug]) }}"
                                                    title="{{ $job->title }}">
                                                    {{ $job->title }}
                                                </a>
                                                <div class="text-muted">
                                                    <a class="text-muted"
                                                        href="{{ route('company.detail', $company->slug) }}"
                                                        title="{{ $company->name }}">{{ $company->name }}
                                                    </a>
                                                    <i class="fa-solid fa-circle text-muted mx-2"
                                                        style="font-size: 8px"></i>
                                                    {{ $job->getCity('city') }}
                                                </div>
                                                <div class="mt-1">
                                                    <label class="fulltime" title="{{ $job->getJobShift('job_shift') }}">
                                                        {{ $job->getJobShift('job_shift') }}
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <a class="custom-label-container mt"
                                                    href="{{ route('list.applied.users', [$job->id]) }}">
                                                    <div class="custom-label text-center no-wrap">
                                                        {{ $appliedUsersCount ?? 0 }} {{ _('Candidates') }}
                                                    </div>
                                                </a>

                                                <div class="mt-2 p-0 d-flex justify-content-center">
                                                    <a class="btn btn-sm btn-outline-dark flex-grow-1 "
                                                        href="{{ route('edit.front.job', [$job->id]) }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <div class="ms-2"></div>
                                                    <a class="btn btn-sm btn-outline-danger flex-grow-1 "
                                                        href="javascript:;" onclick="deleteJob({{ $job->id }});">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>


                                        {{-- <li id="job_li_{{ $job->id }} d-none">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="jobimg"></div>
                                                    <div class="jobinfo">

                                                        <div class="companyName">
                                                        </div>
                                                        <div class="location">
                                                            <label class="fulltime"
                                                                title="{{ $job->getJobShift('job_shift') }}">{{ $job->getJobShift('job_shift') }}</label>
                                                            - <span>{{ $job->getCity('city') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>


                                                <div class="col-md-5">
                                                    <div class="d-flex justify-content-end">
                                                        <a class="btn btn-primary me-2"
                                                            href="{{ route('list.applied.users', [$job->id]) }}">{{ __('List Candidates') }}
                                                            @if ($appliedUsersCount > 0)
                                                                <span
                                                                    class="badge bg-white text-dark">{{ $appliedUsersCount }}</span>
                                                            @else
                                                                <span class="badge bg-white text-dark">0</span>
                                                            @endif

                                                        </a>

                                                        <a class="btn btn-warning me-2"
                                                            href="{{ route('edit.front.job', [$job->id]) }}"><i
                                                                class="fas fa-edit"></i></a>
                                                        <a class="btn btn-danger me-2" href="javascript:;"
                                                            onclick="deleteJob({{ $job->id }});"><i
                                                                class="fas fa-trash"></i></a>

                                                    </div>



                                                </div>
                                            </div>
                                        </li> --}}
                                        <!-- job end -->
                                    @endif
                                @endforeach
                            @endif
                        </div>


                        <!-- Pagination Start -->

                        <div class="pagiWrap">
                            <div class="showreslt">

                                {{ __('Showing Jobs') }} : {{ $jobs->firstItem() }} - {{ $jobs->lastItem() }}
                                {{ __('Total') }}
                                {{ $jobs->total() }}
                            </div>
                            @if (isset($jobs) && count($jobs))
                                {{ $jobs->appends(request()->query())->links() }}
                            @endif

                        </div>


                        <!-- Pagination end -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('includes.footer')
@endsection
@push('scripts')
    <script type="text/javascript">
        function deleteJob(id) {
            var msg = 'Are you sure?';
            if (confirm(msg)) {
                $.post("{{ route('delete.front.job') }}", {
                        id: id,
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    })
                    .done(function(response) {
                        if (response == 'ok') {
                            $('#job_li_' + id).remove();
                        } else {
                            alert('Request Failed!');
                        }
                    });
            }
        }
    </script>
@endpush

@extends('layouts.app')
@section('content')
    <!-- Header start -->
    @include('includes.header')
    <!-- Header end -->


    @include('flash::message')
    <form action="{{ route('job.seeker.list') }}" method="get">
        <!-- Page Title start -->
        <div class="pageSearch">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="searchform">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="search" value="{{ Request::get('search', '') }}"
                                        class="form-control" placeholder="{{ __('Enter Skills or job seeker details') }}" />
                                </div>
                                <div class="col-md-5"> {!! Form::select(
                                    'functional_area_id[]',
                                    ['' => __('Select Functional Area')] + $functionalAreas,
                                    Request::get('functional_area_id', null),
                                    ['class' => 'form-control', 'id' => 'functional_area_id'],
                                ) !!} </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn"><i class="fa fa-search"
                                            aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>


                        @if (Auth::guard('company')->check())
                            <a href="{{ route('post.job') }}" class="btn btn-yellow mt-3"><i class="fa fa-file-text"
                                    aria-hidden="true"></i> {{ __('Post Job') }}</a>
                        @else
                            <a href="{{ url('my-profile#cvs') }}" class="btn btn-yellow mt-3"><i class="fa fa-file-text"
                                    aria-hidden="true"></i> {{ __('Upload Your Resume') }}</a>
                        @endif


                    </div>
                </div>
            </div>
        </div>
        <!-- Page Title end -->
    </form>



    <div class="listpgWraper ">
        <div class="container-fluid">

            <form action="{{ route('job.seeker.list') }}" method="get">
                <!-- Search Result and sidebar start -->
                <div class="row"> @include('includes.job_seeker_list_side_bar')
                    <div class="col-lg-6">
                        <!-- Search List -->
                        <ul class="searchList">
                            <!-- job start -->
                            @if (isset($jobSeekers) && count($jobSeekers))
                                @foreach ($jobSeekers as $jobSeeker)
                                    <li>
                                        <div class="d-inline-flex col-12">
                                            <div class="">{{ $jobSeeker->printUserImage(100, 100) }}</div>
                                            <div class="mx-1 flex-grow-1">
                                                <h3 class="mb-0">
                                                    <a href="{{ route('user.profile', $jobSeeker->id) }}">
                                                        {{ $jobSeeker->getName() }}
                                                    </a>
                                                </h3>
                                                <div class="small text-muted">
                                                    {{-- @if ($jobSeeker->isOnline())
                                                            <i class="fa-solid fa-circle text-success"
                                                                style="font-size: 8pt"></i> Online
                                                        @else
                                                            <span class="ms-auto text-muted">
                                                                Online {{ $jobSeeker->lastOnlineDisplay() }}
                                                            </span>
                                                        @endif --}}
                                                    @if ($jobSeeker->getFunctionalArea()?->functional_area)
                                                        {{-- <i class="fa-solid fa-circle fa-2xs" style="font-size: 6px"></i> --}}
                                                        {{ $jobSeeker->getFunctionalArea()->functional_area }}
                                                    @endif
                                                    @if ($jobSeeker->getJobExperience()?->job_experience)
                                                        <i class="fa-solid fa-circle fa-2xs mx-1"
                                                            style="font-size: 6px"></i>
                                                        {{ $jobSeeker->getJobExperience()?->job_experience }}
                                                    @endif


                                                </div>
                                                <div class="location"> {{ $jobSeeker->getLocation() }}</div>
                                            </div>
                                            <div class="">
                                                <div class="listbtn">
                                                    <a href="{{ route('user.profile', $jobSeeker->id) }}">
                                                        {{ __('View Profile') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            {{ \Illuminate\Support\Str::limit($jobSeeker->getProfileSummary('summary'), 150, '...') }}
                                        </p>
                                    </li>
                                    <!-- job end -->
                                @endforeach
                            @endif
                        </ul>

                        <!-- Pagination Start -->
                        <div class="pagiWrap">
                            <div class="showreslt">
                                {{ __('Showing Pages') }} : {{ $jobSeekers->firstItem() }} -
                                {{ $jobSeekers->lastItem() }} {{ __('Total') }} {{ $jobSeekers->total() }}
                            </div>
                            @if (isset($jobSeekers) && count($jobSeekers))
                                {{ $jobSeekers->appends(request()->query())->links() }}
                            @endif
                            
                        </div>
                        <!-- Pagination end -->
                        <div class=""><br />{!! $siteSetting->listing_page_horizontal_ad !!}</div>

                    </div>
                    <div class="col-lg-3">
                        <!-- Sponsord By -->
                        <div class="sidebar">
                            <h4 class="widget-title">{{ __('Sponsord By') }}</h4>
                            <div class="gad">{!! $siteSetting->listing_page_vertical_ad !!}</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('includes.footer')
@endsection
@push('styles')
    <style type="text/css">
        .searchList li .jobimg {
            min-height: 80px;
        }

        .hide_vm_ul {
            height: 100px;
            overflow: hidden;
        }

        .hide_vm {
            display: none !important;
        }

        .view_more {
            cursor: pointer;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function($) {
            $("form").submit(function() {
                $(this).find(":input").filter(function() {
                    return !this.value;
                }).attr("disabled", "disabled");
                return true;
            });
            $("form").find(":input").prop("disabled", false);

            $(".view_more_ul").each(function() {
                if ($(this).height() > 100) {
                    $(this).addClass('hide_vm_ul');
                    $(this).next().removeClass('hide_vm');
                }
            });
            $('.view_more').on('click', function(e) {
                e.preventDefault();
                $(this).prev().removeClass('hide_vm_ul');
                $(this).addClass('hide_vm');
            });

        });
    </script>
    @include('includes.country_state_city_js')
@endpush

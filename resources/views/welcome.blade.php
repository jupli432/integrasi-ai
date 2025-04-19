@extends('layouts.app')

@section('content')
    <!-- Header start -->
    @include('includes.header')
    <!-- Header end -->
    <!-- Search start -->
    @include('includes.search')
    <!-- Search End -->
    <!-- Featured Companies start -->
    @include('includes.top_employers')
    <!-- Featured Companies ends -->

    <div class="largebanner shadow3">
        <div class="adin">
            {!! $siteSetting->index_page_below_top_employes_ad !!}
        </div>
        <div class="clearfix"></div>
    </div>

    <!-- Popular Searches start -->
    @include('includes.popular_searches')
    <!-- Popular Searches ends -->

    @if (Auth::guard('company')->check())
        <!-- posted Jobs start -->
        @include('includes.posted_jobs')
        <!-- posted Jobs end -->
    @else
        <!-- Featured Jobs start -->
        @include('includes.featured_jobs')
        <!-- Featured Jobs ends -->
    @endif

    <!-- industries start -->
    @include('includes.industries')
    <!-- industries ends -->
    @if (Auth::guard('company')->check())
        <!-- How it Works start -->
        @include('includes.how_it_works_company')
        <!-- How it Works Ends -->
    @else
        <!-- How it Works start -->
        @include('includes.how_it_works')
        <!-- How it Works Ends -->
    @endif


    <div class="infodatawrap">
        @if (Auth::guard('web')->check())
            @include('includes.login_text')
        @elseif (Auth::guard('company')->check())
            @include('includes.employer_login_text')
        @else
            @include('includes.login_text')
            @include('includes.employer_login_text')
        @endif



    </div>

    <!-- Latest Jobs start -->
    @include('includes.latest_jobs')
    <!-- Latest Jobs ends -->
    <!-- Testimonials start -->
    @include('includes.testimonials')
    <!-- Testimonials End -->
    <!-- Top Cities start -->
    @include('includes.top_cities')
    <!-- Top Cities End -->
    <!-- Video start -->
    @include('includes.video')
    <!-- Video end -->
    <!-- Testimonials start -->
    @include('includes.home_blogs')
    <!-- Testimonials End -->
    <!-- Subscribe start -->
    @include('includes.subscribe')
    <!-- Subscribe End -->
    @include('includes.footer')
@endsection
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

        });
    </script>
    @include('includes.country_state_city_js')
@endpush

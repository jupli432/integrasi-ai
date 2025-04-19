@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/ruangdev/cdn@idn/css/sweetalert2/sweetalert2.min.css" integrity="sha512-Xxs33QtURTKyRJi+DQ7EKwWzxpDlLSqjC7VYwbdWW9zdhrewgsHoim8DclqjqMlsMeiqgAi51+zuamxdEP2v1Q==" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/ruangdev/cdn@idn/starterkit/dashboard/css/toastify.css" integrity="sha512-tA+z1mt8+hiZE9CgG95WPtakY4JPkTaYgIcM1Wyq/VCdKDttHhnJoIDRC9/eWo8mbK2MmIDcDeUBfIfI1J8nWA==" crossorigin="anonymous">
@endpush
@extends('layouts.app')
@section('content')
<!-- Header start -->
@include('includes.header')
<!-- Header end -->
<!-- Inner Page Title start -->
@include('includes.inner_page_title', ['page_title'=>__('My Profile')])
<!-- Inner Page Title end -->
<div class="listpgWraper">
    <div class="container-fluid">
        <div class="row">
            @include('includes.user_dashboard_menu')

            <div class="col-md-9 col-sm-8">

                <div class="userccount">
                    <div class="formpanel mt0"> @include('flash::message')
                        <!-- Personal Information -->
                        @include('user.inc.profile')
                    </div>
                </div>

                <div class="userccount">
                    <div class="formpanel mt0">
                        @include('user.inc.summary')
                    </div>
                </div>

                <div class="editprofilebox">
                    <div class="formpanel mt-5">
                        <!-- Personal Information -->
                        {{-- @include('user.forms.cv.cvs') --}}
                        @include('user.forms.new.cv.index')
                        @include('user.forms.project.projects')
                        @include('user.forms.experience.experience')
                        @include('user.forms.education.education')
                        @include('user.forms.skill.skills')
                        @include('user.forms.language.languages')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
@endsection
@push('styles')
<style type="text/css">
    .userccount p {
        text-align: left !important;
    }

</style>
@endpush
@push('scripts')
@include('includes.immediate_available_btn')

<script>
    $(document).on('click', '.btn-close', function() {
        $('.modal').css('display', 'none');
        $('.modal-backdrop').remove();
        $('.modal').removeAttr('style');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        $('body').removeAttr('style');
    });

</script>

<script src="https://cdn.jsdelivr.net/gh/ruangdev/cdn@idn/starterkit/datatables/js/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/ruangdev/cdn@idn/js/sweetalert2/sweetalert2.js" integrity="sha512-tWKcNRzXNTybB8ca0NSEyHlUl1mXPL/2xFjiUkUBGmJTRnAstcmyXtmv81vEennKVkH/FDDIH5l2+Jo0p1FObg==" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/ruangdev/cdn@idn/starterkit/dashboard/js/toastify.js" integrity="sha512-ZHzbWDQKpcZxIT9l5KhcnwQTidZFzwK/c7gpUUsFvGjEsxPusdUCyFxjjpc7e/Wj7vLhfMujNx7COwOmzbn+2w==" crossorigin="anonymous"></script>

@include('includes.employee_edit_profile_sweetalert')
@if(Session::has('message'))
    @include('includes.message_toast.notif')
@endif
@endpush

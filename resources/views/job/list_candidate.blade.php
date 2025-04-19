@push('customCss')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=collapse_content,expand_content" />
@endpush
@extends('layouts.app')
@section('content')
    @include('includes.header')
    @include('includes.inner_page_title', ['page_title' => __('List Candidate')])
    <div class="listpgWraper">
        <div class="container-fluid">
            <div class="row">
                @include('includes.company_dashboard_menu')
                <div id="content-card" class="col-lg-9"> 
                    <div class="myads">
                        <div class=" custom-card">

                            <div class=" d-inline-flex align-items-center">

                                <span class="btn expand-collapse-button material-symbols-outlined me-2">
                                    expand_content
                                </span>
                                <div class="h3 m-0">{{ __('Candidates') }}</div>
                            </div>
                            <div class="mt-4"></div>
                            <form action="{{ route('company.list.candidate') }}" method="GET">
                                <div class="mb-2">

                                    <form id="filterForm">
                                        <div class="row">
                                            <label for="jobName" class="col-auto col-form-label">Select Job:</label>
                                            <div class="col-auto">
                                                <select name="jobName" id="jobName" class="form-control">
                                                    <option value=""> -- All JOBS -- </option>
                                                    @foreach ($JobNames as $jobName)
                                                        <option value="{{ $jobName }}">{{ $jobName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>

                            <div class="">

                                <table class="table table-bordered" id="tableListCandidate">
                                    <thead class="">
                                        <tr class="">
                                            <th class="dt-left">{{ __('Name & Domicile') }}</th>
                                            <th class="dt-left">{{ __('Position Applied') }}</th>
                                            <th class="dt-left">{{ __('Status') }}</th>
                                            <th class="dt-right">{{ __('Salary expectation') }}</th>
                                            <th class="dt-center">{{ __('Social') }}</th>
                                            <th class="dt-left">{{ __('Email') }}</th>
                                            <th class="dt-left">{{ __('Experience') }}</th>
                                            <th class="dt-left">{{ __('Last Position') }}</th>
                                            <th class="dt-left">{{ __('Education') }}</th>
                                            <th class="dt-left">{{ __('Aplication Date') }}</th>
                                            <th class="dt-center">{{ __('Gender') }}</th>
                                            <th class="dt-center">{{ __('Last Active') }}</th>
                                            <th class="dt-center">{{ __('CV') }}</th>
                                            <th class="dt-center">{{ __('Message') }}</th>
                                        </tr>

                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('includes.footer')
@endsection
@push('scripts')
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    @include('job.tabel.list_candidate')

    <script>
        $('.expand-collapse-button').on('click', function() {
            const button_content = $('.expand-collapse-button').html()
            console.log(button_content);


            switch (button_content.trim()) {
                case "expand_content":
                    $('.expand-collapse-button').html('collapse_content')
                    $("#dashboard-menu").addClass("d-none");
                    $("#content-card").removeClass("col-lg-9");
                    $("#content-card").addClass("col-lg-12");
                    break;
                case "collapse_content":
                    $('.expand-collapse-button').html('expand_content')
                    $("#dashboard-menu").removeClass("d-none");
                    $("#content-card").addClass("col-lg-9");
                    $("#content-card").removeClass("col-lg-12");
                    break;

                default:
                    break;
            }
        })
    </script>
@endpush

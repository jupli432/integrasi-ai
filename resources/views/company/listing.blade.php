@extends('layouts.app')
@section('content')
    <!-- Header start -->
    @include('includes.header')
    <!-- Header end -->

    <div class="pageSearch">
        <div class="container-fluid px-5">
            <div class="row">
                <div class="col-lg-7">
                    <h3 class="mb-1">{{ __('Browse Companies') }}.</h3>
                    <h5 class="mb-3">{{ __('Get hired in most high rated companies') }}.</h5>

                    <section id="joblisting-header" role="search" class="searchform">

                        <form id="top-search" method="GET" action="{{ route('company.listing') }}">
                            <div class="input-group">
                                <input type="text" name="search" value="{{ Request::get('search', '') }}"
                                    class="form-control" placeholder="{{ __('keywords e.g. "Google"') }}" />
                                <button type="submit" id="submit-form-top" class="btn"><i class="fa fa-search"
                                        aria-hidden="true"></i> {{ __('Search Company') }}</button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <div class="listpgWraper">
        <div class="container-fluid">
            <ul class="row row-cols-2  row-cols-md-3 row-cols-lg-4 ">
                @if ($companies->isEmpty())
                    <p>No active and verified companies found.</p>
                @else
                    @foreach ($companies as $company)
                        <div class="col mb-4">
                            <div class=" h-100">
                                @component('components.company-card', ['company' => $company])
                                @endcomponent
                            </div>
                        </div>
                    @endforeach
                @endif

            </ul>
            <!-- <div class="pagiWrap"> -->
                <div class="showreslt">
                    {{ __('Showing Companies') }} : {{ $companies->firstItem() }} - {{ $companies->lastItem() }}
                    {{ __('Total') }} {{ $companies->total() }}
                </div>
                <!-- @if (isset($companies) && count($companies))
                    {{ $companies->appends(request()->query())->links() }}
                @endif -->
               

            <!-- </div> -->
            <div class="d-flex justify-content-center">
                @if (isset($companies) && count($companies))
                        {{ $companies->appends(request()->query())->links() }}
                @endif
            </div>

        </div>
    </div>

    @include('includes.footer')
@endsection
@push('styles')
    <style type="text/css">
        .formrow iframe {
            height: 78px;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '#send_company_message', function() {
                var postData = $('#send-company-message-form').serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('contact.company.message.send') }}",
                    data: postData,
                    //dataType: 'json',
                    success: function(data) {
                        response = JSON.parse(data);
                        var res = response.success;
                        if (res == 'success') {
                            var errorString = '<div role="alert" class="alert alert-success">' +
                                response.message + '</div>';
                            $('#alert_messages').html(errorString);
                            $('#send-company-message-form').hide('slow');
                            $(document).scrollTo('.alert', 2000);
                        } else {
                            var errorString =
                                '<div class="alert alert-danger" role="alert"><ul>';
                            response = JSON.parse(data);
                            $.each(response, function(index, value) {
                                errorString += '<li>' + value + '</li>';
                            });
                            errorString += '</ul></div>';
                            $('#alert_messages').html(errorString);
                            $(document).scrollTo('.alert', 2000);
                        }
                    },
                });
            });
        });
    </script>
@endpush

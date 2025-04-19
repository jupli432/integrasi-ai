@extends('layouts.app')
@push('css')
    <style>
        .contact-links a {
            white-space: nowrap;
            /* Prevents wrapping */
            overflow: hidden;
            /* Hides overflow */
            text-overflow: ellipsis;
            /* Adds "..." if truncated */
            display: inline-block;
            /* Ensures it respects width */
            max-width: 100%;
            /* Ensures it doesn't exceed the container */
        }
    </style>
@endpush
@section('content')
    <!-- Header start -->
    @include('includes.header')
    <!-- Header end -->
    <!-- Inner Page Title start -->
    @include('includes.inner_page_title', ['page_title' => __('Contact Us')])
    <!-- Inner Page Title end -->
    <div class="inner-page">
        <!-- About -->
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <h2>{{ __('GET IN TOUCH') }}</h2>
                    <p class="h6 mt-2 mb-5">Contact us if you need more information</p>
                    <form method="post" action="{{ route('contact.us') }}" name="contactform" id="contactform">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-12 formrow  mb-3">
                                {!! Form::text('full_name', null, [
                                    'id' => 'full_name',
                                    'class' => 'form-control ' . ($errors->has('full_name') ? ' is-invalid' : ''),
                                    'placeholder' => __('Full Name'),
                                    'required' => 'required',
                                    'autofocus' => 'autofocus',
                                ]) !!}
                                @if ($errors->has('full_name'))
                                    <span class="help-block invalid-feedback">
                                        <strong>{{ $errors->first('full_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-12 formrow mb-3">
                                {!! Form::text('email', null, [
                                    'id' => 'email',
                                    'class' => 'form-control ' . ($errors->has('email') ? ' is-invalid' : ''),
                                    'placeholder' => __('Email'),
                                    'required' => 'required',
                                ]) !!}
                                @if ($errors->has('email'))
                                    <span class="help-block invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-12 formrow mb-3">
                                {!! Form::text('phone', null, [
                                    'id' => 'phone',
                                    'class' => 'form-control ' . ($errors->has('phone') ? ' is-invalid' : ''),
                                    'placeholder' => __('Phone'),
                                ]) !!}
                                @if ($errors->has('phone'))
                                    <span class="help-block invalid-feedback">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-12 formrow mb-3">
                                {!! Form::text('subject', null, [
                                    'id' => 'subject',
                                    'class' => 'form-control ' . ($errors->has('subject') ? ' is-invalid' : ''),
                                    'placeholder' => __('Subject'),
                                    'required' => 'required',
                                ]) !!}
                                @if ($errors->has('subject'))
                                    <span class="help-block invalid-feedback">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-12 formrow mb-3">
                                {!! Form::textarea('message_txt', null, [
                                    'id' => 'message_txt',
                                    'class' => 'form-control ' . ($errors->has('message_txt') ? ' is-invalid' : ''),
                                    'placeholder' => __('Message'),
                                    'required' => 'required',
                                ]) !!}
                                @if ($errors->has('message_txt'))
                                    <span class="help-block invalid-feedback">
                                        <strong>{{ $errors->first('message_txt') }}</strong> </span>
                                @endif
                            </div>
                            <div
                                class="col-12 formrow {{ $errors->has('g-recaptcha-response') ? ' is-invalid' : '' }} mb-3 d-grid">
                                {!! app('captcha')->display() !!}
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block invalid-feedback">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong> </span>
                                @endif
                            </div>
                            <div class="col-12 d-grid">
                                <button title="" class="btn btn-primary" type="submit"
                                    id="submit">{{ __('Submit Now') }}</button>
                            </div>
                        </div>
                    </form>
                    <div class="row contact-links">
                        <div class="col-lg-4">
                            <div class=" p-2 d-flex gap-3">
                                <i class="fa fa-home"></i>
                                <div>
                                    <strong>{{ __('Address') }}</strong>
                                    <p class="text-break">
                                        {{ $siteSetting->site_street_address }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Contact Info -->
                        <div class="col-lg-4">
                            <div class=" p-2 d-flex gap-3">
                                <i class="fa fa-envelope"></i>
                                <div>
                                    <strong>{{ __('Email Address') }}</strong>
                                    <p class="text-break">
                                        <a href="mailto:{{ $siteSetting->mail_to_address }}">
                                            {{ $siteSetting->mail_to_address }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Contact Info -->
                        <div class="col-lg-4">
                            <div class=" p-2 d-flex gap-3">
                                <i class="fa fa-phone"></i>
                                <div>
                                    <strong>{{ __('Phone') }}</strong>
                                    <p class="text-break">
                                        <a href="tel:{{ $siteSetting->site_phone_primary }}">
                                            {{ $siteSetting->site_phone_primary }}
                                        </a>
                                    </p>
                                    @if ($siteSetting->site_phone_secondary)
                                        <p class="text-break">
                                            <a href="tel:{{ $siteSetting->site_phone_secondary }}">
                                                {{ $siteSetting->site_phone_secondary }}
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Contact Info -->
                    </div>
                </div>
                <div class=" col-12 col-lg-6 d-flex">
                    <iframe
                        src="https://maps.google.it/maps?q={{ urlencode(strip_tags($siteSetting->site_google_map)) }}&output=embed"
                        width="100%" height="100%" allowfullscreen></iframe>
                </div>
            </div>
        </div>

        {{-- <div class="container d-none">
            <div class="contact-wrap">
                <div class="title"> <span>{{ __('We Are Here For Your Help') }}</span>
                    <h2>{{ __('GET IN TOUCH FAST') }}</h2>
                    <p>
                        {{ __('Vestibulum at magna tellus. Vivamus sagittis nunc aliquet. Vivamin orci aliquam') }}
                        <br>
                        {{ __('eros vel saphicula. Donec eget ultricies ipsmconsequat') }}
                    </p>
                </div>
                <!-- Contact Info -->
                <div class="contact-now">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="contact"> <span><i class="fa fa-home"></i></span>
                                <div class="information"> <strong>{{ __('Address') }}:</strong>
                                    <p>{{ $siteSetting->site_street_address }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Contact Info -->
                        <div class="col-lg-4">
                            <div class="contact"> <span><i class="fa fa-envelope"></i></span>
                                <div class="information"> <strong>{{ __('Email Address') }}:</strong>
                                    <p><a
                                            href="mailto:{{ $siteSetting->mail_to_address }}">{{ $siteSetting->mail_to_address }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Contact Info -->
                        <div class="col-lg-4">
                            <div class="contact"> <span><i class="fa fa-phone"></i></span>
                                <div class="information"> <strong>{{ __('Phone') }}:</strong>
                                    <p><a
                                            href="tel:{{ $siteSetting->site_phone_primary }}">{{ $siteSetting->site_phone_primary }}</a>
                                    </p>
                                    <p><a
                                            href="tel:{{ $siteSetting->site_phone_secondary }}">{{ $siteSetting->site_phone_secondary }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Contact Info -->
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <!-- Google Map -->
                            <div class="googlemap">
                                <iframe
                                    src="https://maps.google.it/maps?q={{ urlencode(strip_tags($siteSetting->site_google_map)) }}&output=embed"
                                    allowfullscreen></iframe>
                            </div>
                        </div>
                        <!-- Contact form -->
                        <div class="col-lg-8">
                            <div class="contact-form">
                                <div id="message"></div>
                                <form method="post" action="{{ route('contact.us') }}" name="contactform"
                                    id="contactform">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-6{{ $errors->has('full_name') ? ' has-error' : '' }}">
                                            {!! Form::text('full_name', null, [
                                                'id' => 'full_name',
                                                'placeholder' => __('Full Name'),
                                                'required' => 'required',
                                                'autofocus' => 'autofocus',
                                            ]) !!}
                                            @if ($errors->has('full_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('full_name') }}</strong> </span>
                                            @endif
                                        </div>
                                        <div class="col-md-6{{ $errors->has('email') ? ' has-error' : '' }}">
                                            {!! Form::text('email', null, ['id' => 'email', 'placeholder' => __('Email'), 'required' => 'required']) !!}
                                            @if ($errors->has('email'))
                                                <span class="help-block"> <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-6{{ $errors->has('phone') ? ' has-error' : '' }}">
                                            {!! Form::text('phone', null, ['id' => 'phone', 'placeholder' => __('Phone')]) !!}
                                            @if ($errors->has('phone'))
                                                <span class="help-block"> <strong>{{ $errors->first('phone') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-6{{ $errors->has('subject') ? ' has-error' : '' }}">
                                            {!! Form::text('subject', null, ['id' => 'subject', 'placeholder' => __('Subject'), 'required' => 'required']) !!}
                                            @if ($errors->has('subject'))
                                                <span class="help-block"> <strong>{{ $errors->first('subject') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-12{{ $errors->has('message_txt') ? ' has-error' : '' }}">
                                            {!! Form::textarea('message_txt', null, [
                                                'id' => 'message_txt',
                                                'placeholder' => __('Message'),
                                                'required' => 'required',
                                            ]) !!}
                                            @if ($errors->has('message_txt'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('message_txt') }}</strong> </span>
                                            @endif
                                        </div>
                                        <div
                                            class="col-md-12{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                            {!! app('captcha')->display() !!}
                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong> </span>
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            <button title="" class="button" type="submit"
                                                id="submit">{{ __('Submit Now') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    @include('includes.footer')
@endsection

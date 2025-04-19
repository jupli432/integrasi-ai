<div class="footerWrap">
    <div class="container container-lg-fluid">
        <div class="footer-new-footer">
            <div class="fulllogo-transparent-nobuffer-parent-new-footer">
                <img src="{{ asset('/') }}sitesetting_images/thumb/{{ $siteSetting->site_logo }}"
                    alt="{{ $siteSetting->site_name }}"
                    class="fulllogo-transparent-nobuffer-icon-new-footer d-none d-md-block" />
                <div class="row">
                    <div class="col-6 col-md-3">
                        <b class="prestalo-new-footer">Quick Links</b>
                        <div class="inicio-quines-somos-new-footer"><a
                                href="{{ route('index') }}">{{ __('Home') }}</a></div>
                        <div class="inicio-quines-somos-new-footer"><a
                                href="{{ route('contact.us') }}">{{ __('Contact Us') }}</a></div>
                        <div class="inicio-quines-somos-new-footer"><a
                                href="{{ route('post.job') }}">{{ __('Post a Job') }}</a></div>
                        <div class="inicio-quines-somos-new-footer"><a
                                href="{{ route('faq') }}">{{ __('FAQs') }}</a></div>
                    </div>
                    <div class="col-6 col-md-3 d-none d-md-block">
                        <b class="prestalo-new-footer">Jobs by Funcional Area</b>
                        @php
                            $functionalAreas = App\FunctionalArea::getUsingFunctionalAreas(10);
                        @endphp
                        @foreach ($functionalAreas as $functionalArea)
                            <div class="inicio-quines-somos-new-footer"><a
                                    href="{{ route('job.list', ['functional_area_id[]' => $functionalArea->functional_area_id]) }}">{{ $functionalArea->functional_area }}</a>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-6 col-md-3 d-none d-md-block">
                        <b class="prestalo-new-footer">Jobs by Industry</b>
                        @php
                            $industries = App\Industry::getUsingIndustries(10);
                        @endphp
                        @foreach ($industries as $industry)
                            <div class="inicio-quines-somos-new-footer"><a
                                    href="{{ route('job.list', ['industry_id[]' => $industry->industry_id]) }}">{{ $industry->industry }}</a>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-6 col-md-3">
                        <b class="prestalo-new-footer">Contact Us</b>
                        <div class="iconuilocation-parent-new-footer">
                            <img class="iconuimail-new-footer" alt=""
                                src="{{ asset('/footer/icon/ui/location.png') }}" />
                            <div class="inicio-quines-somos22-new-footer">
                                {{ $siteSetting->site_street_address }}
                            </div>
                        </div>
                        <div class="iconuilocation-parent-new-footer">
                            <img class="iconuimail-new-footer" alt=""
                                src="{{ asset('/footer/icon/ui/mail.png') }}" />
                            <a href="mailto:{{ $siteSetting->mail_to_address }}"
                                class="inicio-quines-somos22-new-footer">
                                {{ $siteSetting->mail_to_address }}
                            </a>
                        </div>
                        <div class="iconuilocation-parent-new-footer">
                            <img class="iconuimail-new-footer" alt=""
                                src="{{ asset('/footer/icon/ui/calling.png') }}" />
                            <a href="tel:{{ $siteSetting->site_phone_primary }}"
                                class="inicio-quines-somos22-new-footer">
                                {{ $siteSetting->site_phone_primary }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="rectangle-new-footer"></div>
                <div
                    class="d-flex flex-row justify-content-between justify-content-md-center justify-content-between align-items-center w-100">
                    <b class="prestalo-new-footer d-md-none">Follow Us</b>
                    <div class="social-icons-yt-parent-new-footer">
                        <img class="social-icons-yt-new-footer" alt="Youtube"
                            src="{{ asset('/footer/sosmed/youtube.png') }}" />
                        <img class="social-icons-yt-new-footer" alt="linkend"
                            src="{{ asset('/footer/sosmed/linkend.png') }}" />
                        <img class="social-icons-yt-new-footer" alt="instagram"
                            src="{{ asset('/footer/sosmed/ig.png') }}" />
                        <img class="social-icons-yt-new-footer" alt="tweeter"
                            src="{{ asset('/footer/sosmed/x.png') }}" />
                        <img class="social-icons-yt-new-footer" alt="facebook"
                            src="{{ asset('/footer/sosmed/fb.png') }}" />
                    </div>
                </div>

                <div class="inicio-quines-somos-parent-new-footer">
                    <div class="inicio-quines-somos25-new-footer">
                        Copyright @ 2025 PT Bali Project Indonesia
                    </div>
                    <div class="master-card-removebg-preview-parent-new-footer">
                        <img class="master-card-removebg-preview-icon-new-footer" alt=""
                            src="{{ asset('/footer/payment/master_card.png') }}" />
                        <img class="master-card-removebg-preview-icon-new-footer" alt=""
                            src="{{ asset('/footer/payment/visa.png') }}" />
                        <img class="master-card-removebg-preview-icon-new-footer" alt=""
                            src="{{ asset('/footer/payment/paypal.png') }}" />
                        <img class="master-card-removebg-preview-icon-new-footer" alt=""
                            src="{{ asset('/footer/payment/stripe.png') }}" />
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

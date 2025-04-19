<div class="paypackages custom-card">

    <h3>{{ __('Our Packages') }}</h3>
    <div class="row">
        @foreach ($packages as $package)
            <div class="col-md-4  col-sm-12">
                <div class="plan-card bg-secondary p-4 h-100">
                    {{-- <li class="plan-name">{{ $package->package_title }}</li>
                    <li>
                        <div class="main-plan">
                            <div class="plan-price1-1">$</div>
                            <div class="plan-price1-2">{{ $package->package_price }}</div>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li class="plan-pages">{{ __('Can apply on jobs') }} : {{ $package->package_num_listings }}</li>
                    <li class="plan-pages">{{ __('Package Duration') }} : {{ $package->package_num_days }} Days</li> --}}

                    <div class="plan-card-content mb-5">
                        <div class="plan-name h4 text-center">{{ $package->package_title }}</div>

                        <hr>


                        <div class="main-plan">

                            <div class="plan-price1-1">{{ $siteSetting->default_currency_code }}</div>

                            <div class="plan-price1-2">{{ $package->package_price }}</div>
                        </div>
                        <ul>
                            <li> <i class="fa-regular fa-circle-check me-2"></i>
                                {{ __('Can apply on jobs') }}
                                {{ $package->package_num_listings }}</li>
                            <li> <i class="fa-regular fa-circle-check me-2"></i>
                                {{ __('Package Duration') }}
                                {{ $package->package_num_days }} {{ __('Days') }}</li>
                        </ul>
                    </div>

                    <div class="div">
                        @if ($package->package_price > 0)
                            @if ((bool) $siteSetting->is_paypal_active)
                                <a class="order paypal" href="{{ route('order.package', $package->id) }}">
                                    <i class="fab fa-cc-paypal" aria-hidden="true"></i>
                                    {{ __('pay with paypal') }}
                                </a>
                            @endif
                            @if ((bool) $siteSetting->is_stripe_active)
                                <a class="order" href="{{ route('stripe.order.form', [$package->id, 'new']) }}"><i
                                        class="fab fa-cc-stripe" aria-hidden="true">
                                    </i>
                                    {{ __('pay with stripe') }}
                                </a>
                            @endif
                            @if ((bool) $siteSetting->is_payu_active)
                                <a class="order payu"
                                    href="{{ route('payu.order.package', ['package_id=' . $package->id, 'type=new']) }}">
                                    {{ __('pay with PayU') }}
                                </a>
                            @endif
                        @else
                            <a class="order" href="{{ route('order.free.package', $package->id) }}">
                                {{ __('Subscribe Free Package') }}
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach
    </div>

</div>

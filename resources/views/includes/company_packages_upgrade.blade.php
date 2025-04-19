@if ($packages->count())

    <div class="paypackages custom-card">

        <h3>{{ __('Upgrade Package') }}</h3>

        <div class="row d-flex align-items-stretch justify-content-center">
            @foreach ($packages as $package)
                <div class="col-md-4  col-sm-12">

                    <div class="plan-card bg-secondary p-4 h-100">

                        <div class="plan-card-content mb-5">
                            <div class="plan-name h4 text-center">{{ $package->package_title }}</div>

                            <hr>


                            <div class="main-plan">

                                <div class="plan-price1-1">{{ $siteSetting->default_currency_code }}</div>

                                <div class="plan-price1-2">{{ $package->package_price }}</div>
                            </div>
                            <ul>
                                <li> <i class="fa-regular fa-circle-check me-2"></i>
                                    {{ __('Post job') }}
                                    {{ $package->package_num_listings }}</li>
                                <li> <i class="fa-regular fa-circle-check me-2"></i>
                                    {{ __('Package Duration') }}
                                    {{ $package->package_num_days }} {{ __('Days') }}</li>
                            </ul>
                        </div>





                        <a class="order " href="javascript:void(0)" data-bs-toggle="modal"
                            data-bs-target="#buypack{{ $package->id }}" class="reqbtn">{{ __('Buy Now') }}</a>

                    </div>


                    <div class="modal fade" id="buypack{{ $package->id }}" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    </button>
                                    <div class="invitereval">
                                        <h3>Please Choose Your Payment Method to Pay</h3>

                                        <div class="totalpay">{{ __('Total Amount to pay') }}:
                                            <strong>{{ $package->package_price }}</strong>
                                        </div>

                                        <ul class="btn2s">
                                            @if ((bool) $siteSetting->is_paypal_active)
                                                <div class="order paypal"><a
                                                        href="{{ route('order.upgrade.package', $package->id) }}"><i
                                                            class="fab fa-cc-paypal" aria-hidden="true"></i>
                                                        {{ __('Paypal') }}</a></div>
                                            @endif
                                            @if ((bool) $siteSetting->is_stripe_active)
                                                <div class="order"><a
                                                        href="{{ route('stripe.order.form', [$package->id, 'upgrade']) }}"><i
                                                            class="fab fa-cc-stripe" aria-hidden="true"></i>
                                                        {{ __('Stripe') }}</a></div>
                                            @endif
                                            @if ((bool) $siteSetting->is_payu_active)
                                                <div class="order payu"><a
                                                        href="{{ route('payu.order.package', ['package_id=' . $package->id, 'type=upgrade']) }}">{{ __('PayU') }}</a>
                                                </div>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            @endforeach
        </div>



    </div>

@endif

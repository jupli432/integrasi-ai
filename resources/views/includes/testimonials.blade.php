<div class="section testimonialwrap overflow-hidden">
    <div class="container">
        <!-- title start -->
        <div class="titleTop">
            <div class="subtitle">{{ __('Testimonials') }}</div>
            <h3>{{ __('Success Stories') }}</h3>
        </div>
        <!-- title end -->

        <ul class="testimonialsList owl-carousel">
            @if (isset($testimonials) && count($testimonials))
                @foreach ($testimonials as $testimonial)
                    <li class="item item-child" data-number="{{ $testimonial->id }}">
                        <div class="clientname">{{ $testimonial->testimonial_by }}</div>
                        <div class="clientinfo">{{ $testimonial->company }}</div>
                        <div class="ratinguser">
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                        </div>
                        <p>"{{ $testimonial->testimonial }}"</p>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>
@push('scripts')
    <script>
        /* ==== Testimonials Slider ==== */
        $(".testimonialsList").owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: true
                },
                768: {
                    items: 1,
                    nav: true
                },
                1170: {
                    items: 3,
                    nav: true,
                    loop: true
                }
            }
        });
    </script>
@endpush

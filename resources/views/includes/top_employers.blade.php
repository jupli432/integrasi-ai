@push('customCss')
@endpush
<div class="section overflow-hidden">



    <div class="container-fluid">
        <!-- title start -->
        <div class="titleTop d-flex justify-content-between align-items-center">
            <h3 class="m-0">{{ __('Featured Companies') }}</h3>
            <a href="{{ url('/companies') }}">{{ __('View all companies') }}</a>
        </div>
        <!-- title end -->
        <div class="mt-lg-4 mt-2">

            <div class="employerList  owl-carousel owl-theme" data-group-item="1">


                <!--employer-->
                @if (isset($topCompanyIds) && count($topCompanyIds))
                    @foreach ($topCompanyIds as $company_id_num_jobs)
                        <?php
            $company = App\Company::where('id', '=', $company_id_num_jobs->company_id)->active()->first();
            if (null !== $company) {
                ?>
                        <div class="item-child" data-number="{{ $company->id }}">
                            @component('components.company-card', ['company' => $company])
                            @endcomponent
                        </div>
                        <?php
            }
            ?>
                    @endforeach
                @endif
            </div>
        </div>

    </div>



</div>
@push('scripts')
    <script>
        $(document).ready(function($) {

            $(".employerList").owlCarousel({
                items: 2,
                margin: 12,
                autoHeight: false,
                loop: true,
                nav: false,
                center: false,
                dots: false,
                responsive: {
                    0: {
                        items: 2
                    },
                    768: {
                        items: 2
                    },
                    1024: {
                        items: 3
                    },
                    1366: {
                        items: 4,
                        margin: 24,
                    }
                },
            });
        })
    </script>
@endpush

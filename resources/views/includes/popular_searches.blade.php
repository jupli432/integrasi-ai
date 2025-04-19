<div class="section ">
    <div class="container-fluid">
        <div class="topsearchwrap">

            <div class="titleTop">
                <h3>{{ __('Explore by Category') }}</h3>
            </div>

            <div class="srchint mt-lg-4 mt-2">
                <ul class="row row-cols-2  row-cols-md-3 row-cols-lg-4 g-2 g-lg-4 ">
                    @if (isset($topFunctionalAreaIds) && count($topFunctionalAreaIds))
                        @foreach ($topFunctionalAreaIds as $functional_area_id_num_jobs)
                            <?php
                            $functionalArea = App\FunctionalArea::where('functional_area_id', '=', $functional_area_id_num_jobs->functional_area_id)->lang()->active()->first();
                            ?>
                            @if (null !== $functionalArea)
                                <div class="col  {{ $loop->index >= 6 ? 'd-none d-lg-block' : '' }}">
                                    @component('components.functional-area-card', [
                                        'functionalArea' => $functionalArea,
                                        'functional_area_id_num_jobs' => $functional_area_id_num_jobs,
                                    ])
                                    @endcomponent
                                </div>
                            @endif
                        @endforeach
                    @endif
                </ul>
                <!--Categories end-->
            </div>

            <div class="viewallbtn  mt-lg-4 mt-2">
                <a href="{{ url('/all-categories') }}">
                    {{ __('View All Categories') }}
                </a>
            </div>


        </div>
    </div>
</div>

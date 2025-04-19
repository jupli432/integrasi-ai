<div class="section citieswrap">
    <div class="container-fluid">
        <!-- title start -->
        <div class="titleTop">
            <h3>{{ __('Jobs by Cities') }}</h3>
        </div>
        <!-- title end -->
        <div class="mt-lg-4 mt-2">
            <div class="">
                <ul class="row row-cols-2  row-cols-md-3 row-cols-lg-4 g-2 g-lg-4  citiessrchlist">
                    @if (isset($topCityIds) && count($topCityIds))
                        @foreach ($topCityIds as $city_id_num_jobs)
                            <?php
                            $city = App\City::getCityById($city_id_num_jobs->city_id);
                            ?> @if (null !== $city && $city->upload_image)
                                <div class="col {{ $loop->index >= 2 ? 'd-none d-md-block' : '' }} ">
                                    <li class="h-100">

                                        @if (isset($city) && null !== $city->upload_image)
                                            <div class="cityimg">
                                                {{ ImgUploader::print_image("city_images/$city->upload_image") }}</div>
                                        @endif
                                        <div class="cityinfobox ">
                                            <h4><a href="{{ route('job.list', ['city_id[]' => $city->city_id]) }}"
                                                    title="{{ $city->city }}">{{ $city->city }}</a></h4>
                                            <span>({{ $city_id_num_jobs->num_jobs }}) {{ __('Open Jobs') }}</span>
                                        </div>
                                    </li>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
            <!--Cities end-->
        </div>
    </div>
</div>

<a href="{{ route('job.list', ['functional_area_id[]' => $functionalArea->functional_area_id]) }}">
    <div class="functional-area-card">
        <div>
            @if ($functionalArea->image && file_exists(public_path('uploads/functional_area/' . $functionalArea->image)))
                <img class="image-container" src="{{ asset('uploads/functional_area/' . $functionalArea->image) }}"
                    alt="">
            @else
                <img class="image-container" src="{{ asset('images/no-image.png') }}" alt="Dummy Image">
            @endif
            <div class="title">
                {{ $functionalArea->functional_area }}
            </div>
        </div>
        <div class="info">
            <div class="job-info">
                <div>
                    {{ $functional_area_id_num_jobs->num_jobs }} jobs available
                </div>
                <i class="fa-solid fa-arrow-right"></i>
            </div>
        </div>
    </div>
</a>

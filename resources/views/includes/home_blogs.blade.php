<div class="section homeblogposts">
    <div class="container">
        <!-- title start -->
        <div class="titleTop">
            <div>{{ __('Here You Can See') }}</div>
            <h3>{{ __('Latest Blogs') }}</span></h3>
        </div>
        <!-- title end -->

        <ul class="blogList row row-cols-1 row-cols-lg-4  g-4 ">
            @if (null !== $blogs)
                @foreach ($blogs as $blog)
                    <?php
                    $cate_ids = explode(',', $blog->cate_id);
                    $data = DB::table('blog_categories')->whereIn('id', $cate_ids)->get();
                    $cate_array = [];
                    foreach ($data as $cat) {
                        $cate_array[] = "<a href='" . url('/blog/category/') . '/' . $cat->slug . "'>$cat->heading</a>";
                    }
                    ?>
                    <li class="col">
                        <a class="bloginner" href="{{ route('blog-detail', $blog->slug) }}">
                            <div class="postimg">
                                @if (null !== $blog->image && $blog->image != '')
                                    <img src="{{ asset('uploads/blogs/' . $blog->image) }}" alt="{{ $blog->heading }}">
                                @else
                                    <img src="{{ asset('images/blog/1.jpg') }}" alt="{{ $blog->heading }}">
                                @endif
                            </div>
                            <div class="post-date">
                                <div class="text-muted text-sm">
                                    {{$blog->created_at->format('M d, Y')}}
                                </div>
                            </div>

                            <div class="post-header">
                                <h4>{{ $blog->heading }}</h4>
                            </div>
                            <p>{!! \Illuminate\Support\Str::limit($blog->content, $limit = 150, $end = '...') !!}</p>
                            <div class="postmeta">
                                Category : {!! implode(', ', $cate_array) !!}
                            </div>

                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
        <!--view button-->
        <div class="viewallbtn"><a href="{{ route('blogs') }}">{{ __('View All Blog Posts') }}</a></div>
        <!--view button end-->
    </div>
</div>

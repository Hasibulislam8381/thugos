@php
    // $best_selling_products = Cache::remember('best_selling_products', 86400, function () {
    //     return filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))
    //         ->limit(20)
    //         ->get();
    // });
    $blogs = \App\Models\Blog::where('status', 1)->orderBy('created_at', 'desc')->limit(3)->get();
@endphp


<section class="home_page_sec_pad_25">

    <div class="container">
        <div class="d-flex mb-3 align-items-baseline">
            <h3 class="h5 fw-700 mb-0">
                <span class="pb-3 d-inline-block">{{ translate('Latest Blog') }}</span>
            </h3>
            <h6 class="border-bottom border-width-2 ml-auto mr-0">
                <a href="{{ route('blog') }}" class="view_all_btn">{{ translate('View All') }} <i
                        class="fa-solid fa-caret-right"></i></a>
            </h6>
        </div>
        <div class="row mt-2">
            @foreach ($blogs as $blog)
                <div class="col-lg-4 col-md-6 home_blog_m_bot_res">
                    <a href="{{ url('blog') . '/' . $blog->slug }}" class="text-reset d-block">
                        <div class="banner_img_main_div">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($blog->thumb) }}" alt="{{ $blog->title }}"
                                class="img-fluid lazyload banner_img">
                        </div>
                    </a>
                    <div class="blog_bot_sec">
                        <div class="opacity-100 text-white">
                            {{ \Carbon\Carbon::parse($blog->created_at)->format('d F Y') }}
                        </div>

                        <h2 class="fs-18 fw-600 mb-1 mt-2">
                            <a href="{{ url('blog') . '/' . $blog->slug }}" class="text-white">
                                {{ $blog->title }}
                            </a>
                        </h2>

                        <div class="position-relative">
                            <a href="{{ url('blog') . '/' . $blog->slug }}"
                                class="fs-18 fw-600 readmore blog_read_more">
                                {{ translate('Read More') }} <i class="fa-solid fa-caret-right"></i>
                            </a>
                        </div>
                    </div>


                </div>
            @endforeach
        </div>
        {{-- <div class="d-flex mt-3">
            <a href="{{ route('blog') }}"
                class="m-auto fw-600 fs-16 mr-0 btn btn-secondary btn-sm shadow-md mt-4 view_all_btn"
                style="border: none">{{ translate('View All Blogs') }}</a>
        </div> --}}
    </div>
</section>

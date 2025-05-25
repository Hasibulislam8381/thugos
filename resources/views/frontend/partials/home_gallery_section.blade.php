@php
    // $best_selling_products = Cache::remember('best_selling_products', 86400, function () {
    //     return filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))
    //         ->limit(20)
    //         ->get();
    // });
    $blogs = \App\Models\Blog::where('status', 1)->orderBy('created_at', 'desc')->limit(3)->get();
    $slider_images = json_decode(get_setting('home_gallery_images'), true);

@endphp


<section class="home_page_sec_pad_25 mb-4">

    <div class="container">
        <div class="d-flex align-items-baseline">
            <h3 class="h5 fw-700 mb-0">
                <span class="pb-3 d-inline-block">{{ translate('Our Gallery') }}</span>
            </h3>
        </div>

        <div class="silk_center">
            @foreach ($slider_images as $key => $value)
                <div class="slide-item">
                    <a href="{{ json_decode(get_setting('home_gallery_links'), true)[$key] }}"
                        class="text-reset d-block home_gallery_m_bot_res">
                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                            data-src="{{ uploaded_asset($slider_images[$key]) }}" alt=""
                            class="img-fluid lazyload banner_img">
                    </a>
                </div>
            @endforeach
        </div>


    </div>
    <style>
        .silk_center .slick-slide {
            transition: transform 0.5s ease;
        }

        .silk_center .slick-center {
            transform: scale(1.5);
            /* Increase scale for zoom effect */
            z-index: 1;
            /* Ensure zoomed image is on top */
        }

        .home_gallery_m_bot_res {
            margin-top: 50px;
            margin-bottom: 50px;
        }
    </style>
    <script>
        $('.silk_center').slick({
            centerMode: true,
            centerPadding: '60px',
            slidesToShow: 5,
            arrows: false,
            autoplay: true,
            autoplaySpeed: 3000,

            responsive: [{
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 1
                    }
                }
            ]
        });
        $('.silk_center').on('afterChange', function(event, slick, currentSlide) {
            $('.slick-slide').removeClass('slick-center');
            $('.slick-slide[data-slick-index="' + currentSlide + '"]').addClass('slick-center');
        });
    </script>
</section>

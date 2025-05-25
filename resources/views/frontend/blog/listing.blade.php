@extends('frontend.layouts.app')

@section('content')

<section class="pt-4 mb-4">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-6 text-center text-lg-left">
                <h1 class="fw-600 h4">{{ translate('Blog')}}</h1>
            </div>
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                    <li class="breadcrumb-item opacity-50">
                        <a class="text-reset" href="{{ route('home') }}">
                            {{ translate('Home')}}
                        </a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        <a class="text-reset" href="{{ route('blog') }}">
                            "{{ translate('Blog') }}"
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="pb-4">
    <div class="container">
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

                        <a href="{{ url('blog') . '/' . $blog->slug }}" class="fs-18 fw-600 readmore blog_read_more">
                            {{ translate('Read More') }} <i class="fa-solid fa-caret-right"></i>
                        </a>
                    </div>


                </div>
            @endforeach
        </div>
        <div class="aiz-pagination aiz-pagination-center mt-4">
            {{ $blogs->links() }}
        </div>
    </div>
</section>
@endsection

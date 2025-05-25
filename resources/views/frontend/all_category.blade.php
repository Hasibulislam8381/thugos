@extends('frontend.layouts.app')

@section('content')
    <section class="pt-4 mb-4">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <h1 class="fw-600 h4">{{ translate('All Categories') }}</h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                        <li class="breadcrumb-item opacity-50">
                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            <a class="text-reset"
                                href="{{ route('categories.all') }}">"{{ translate('All Categories') }}"</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                @foreach ($categories as $key => $category)
                    @if ($category != null)
                        <div class="col-lg-3 col-sm-6 col-6">
                            <a href="{{ route('products.category', $category->slug) }}"
                                class="bg-white cat_border d-block text-reset  p-2 mb-2">
                                <div class="align-items-center no-gutters p-2">
                                    <div class="text-center overflow-hidden">
                                        <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ uploaded_asset($category->icon) }}"
                                            alt="{{ $category->getTranslation('name') }}"
                                            class="img-fluid img lazyload hov-shadow-md rounded cat_img"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </div>
                                    <div class="mt-2">
                                        <div class="text-truncat-2 pl-3 fs-14 fw-600 text-center">
                                            {{ $category->getTranslation('name') }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="aiz-pagination aiz-pagination-center mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </section>
@endsection

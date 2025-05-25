@extends('frontend.layouts.app')

@section('content')
    <section class="pt-4 mb-4">
        <div class="container text-center">
            <div class="row mobile_row">
                <div class="col-lg-6 text-center text-lg-left">
                    <h1 class="fw-600 h4">{{ translate('All Reviews') }}</h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                        <li class="breadcrumb-item opacity-50">
                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            <a class="text-reset" href="{{ route('reviews') }}">"{{ translate('All reviews') }}"</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-4">
        <div class="container">
            <div class="row gutters-10 mobile_row">
                @php
                    $home_customer_review = json_decode(get_setting('home_customer_review'));
                @endphp
                @foreach ($home_customer_review as $review_photo)
                    <div class="col-md-3 col-sm-6 col-6">
                        <div class="carousel-box">

                            <div class="align-items-center no-gutters p-2">
                                <div class="text-center overflow-hidden rounded">
                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($review_photo) }}" alt=""
                                        class="img-fluid img lazyload hov-shadow-md "
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </div>

                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
            {{-- <div class="aiz-pagination aiz-pagination-center mt-4">
                {{ $home_customer_review->links() }}
            </div> --}}
        </div>
    </section>
@endsection

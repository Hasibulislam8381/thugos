@extends('frontend.layouts.app')

@section('content')
    <section class="pt-4 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <div class="all_product_title"><a href="{{ route('home') }}">Home / </a><a
                            href="">{{ translate('Best Selling') }}</a></div>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-4">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="grid-container">
                    @foreach ($best_sellings as $key => $product)
                        <div class="grid-item">
                            @include('frontend.partials.product_box_1', ['product' => $product])
                        </div>
                    @endforeach
    
                </div>
            </div>
            <div class="aiz-pagination aiz-pagination-center mt-4">
                {{ $best_sellings->links() }}
            </div>

        </div>
    </section>
@endsection

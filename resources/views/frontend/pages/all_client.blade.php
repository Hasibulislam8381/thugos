@extends('frontend.layouts.app')

@section('content')
    @php
        $orders = \App\Models\Order::select('user_id', \DB::raw('COUNT(*) as total_orders'))
            ->groupBy('user_id')
            ->orderByDesc('total_orders')
            ->get();
    @endphp
    <section class="pt-4 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <h1 class="fw-600 h4">{{ translate('All Client') }}</h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                        <li class="breadcrumb-item opacity-50">
                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            <a class="text-reset" href="{{ route('all_client') }}">"{{ translate('All client') }}"</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-4">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="row">
                    @foreach ($orders as $key => $order)
                        @php
                            $user = \App\Models\User::where('id', $order->user_id)->first();
                            $position = $key + 1;
                            $positionSuffix = '';
                            switch ($position) {
                                case 1:
                                    $positionSuffix = 'st';
                                    break;
                                case 2:
                                    $positionSuffix = 'nd';
                                    break;
                                case 3:
                                    $positionSuffix = 'rd';
                                    break;
                                default:
                                    $positionSuffix = 'th';
                                    break;
                            }
                        @endphp
                        <div class="col-md-3">
                            <div class="card top_client_img all_client_background">
                                <img src="{{ static_asset('assets/img/top_client/Ellipse 4 (1).jpg') }}" alt="">
                                <div class="client_info">
                                    <div class="client_name">{{ @$user->name }}</div>
                                    <div class="client_rank"><i class="fa-solid fa-crown"
                                            style="padding-right: 5px;color: #b9a120"></i>{{ $position . $positionSuffix }}
                                        Position</div>
                                </div>


                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

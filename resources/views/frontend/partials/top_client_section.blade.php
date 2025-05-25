@php

    $orders = \App\Models\Order::select('user_id', 'todays_date', 'created_at', \DB::raw('COUNT(*) as total_orders'))
        ->groupBy('user_id', 'todays_date', 'created_at')
        ->orderByDesc('total_orders')
        ->limit(20)
        ->get();

@endphp


<section class="home_page_sec_pad_25 mb-4">
    <div class="container">
        <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
            <div class="d-flex align-items-baseline set_pb_30">
                <h3 class="h5 fw-700">
                    <span class="d-inline-block top_client_padding">{{ translate('Our Top Client') }}</span>
                </h3>
                <h6 class="border-bottom border-width-2 ml-auto mr-0">
                    <a href="{{ route('all_client') }}" class="view_all_btn">{{ translate('View All') }} <i
                            class="fa-solid fa-caret-right"></i></a>
                </h6>
            </div>

            <div class="container">
                <div class="owl-carousel owl-theme">
                    @foreach ($orders as $key => $order)
                        @php
                            $createdAt = \Carbon\Carbon::parse($order->created_at)->toDateString();
                            $rankReset = \Carbon\Carbon::parse($order->todays_date)->toDateString();

                            $user = \App\Models\User::where('id', $order->user_id)->first();
                            if ($rankReset > $createdAt) {
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
                            }

                        @endphp
                        @if ($rankReset > $createdAt)
                            <div class="item">
                                <div class="card top_client_img">
                                    <img src="{{ uploaded_asset(@$user->avatar_original) }}" alt=""
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    <div class="client_info">
                                        <div class="client_name">{{ @$user->name ?? 'No Name' }}</div>
                                        <div class="client_rank"><i class="fa-solid fa-crown"
                                                style="padding-right: 5px;color: #b9a120"></i>{{ $position . $positionSuffix }}
                                            Position</div>
                                    </div>


                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>

    </div>

    </div>
</section>
<script>
    $(document).ready(function() {
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 10,
            responsive: {
                0: {
                    items: 2
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            },
            autoplay: false, // Add autoplay option
            autoplayTimeout: 3000, // Set autoplay timeout in milliseconds
            autoplayHoverPause: true // Pause autoplay on mouse hover
        });
    });
</script>

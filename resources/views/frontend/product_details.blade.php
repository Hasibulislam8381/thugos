@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:price:currency" content="{{ \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection

@section('content')

<section class="mb-4 pt-3">
    <div class="container">
      <div class="row mobile_row">
        <div class="col-xl-6 col-lg-6">
            <div class="bg-white shadow-sm rounded p-3">
          <div class="sticky-top z-3 row gutters-10 grid_display"> @php $photos = explode(',', $detailedProduct->photos); @endphp <div class="col order-2 order-md-2">
              <div class="aiz-carousel product-gallery " data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='true'> @foreach ($photos as $key => $photo) <div class="carousel-box img-zoom rounded ">
                  <img  class="img-fluid lazyload m-auto" src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($photo) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </div> @endforeach @foreach ($detailedProduct->stocks as $key => $stock) @if ($stock->image != null) <div  class="carousel-box img-zoom rounded d-flex">
                  <img  class="img-fluid lazyload m-auto" src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($stock->image) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </div> @endif @endforeach </div>
            </div>
            <div class="col-12 col-md-auto w-md-80px w-527 order-1 order-md-12 mt-3 mt-md-0">
                <div class="aiz-carousel product-gallery-thumb" data-items='5' data-nav-for='.product-gallery' data-vertical='false' data-vertical-sm='false' data-focus-select='true' data-arrows='true'>
                   @foreach ($photos as $key => $photo) 
                   <div class="carousel-box c-pointer m-1 rounded">
                      <img class="lazyload mw-100 size-50px mx-auto border" src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($photo) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                   </div>
                   @endforeach
                    @foreach ($detailedProduct->stocks as $key => $stock) 
                    @if ($stock->image != null) 
                   <div class="carousel-box c-pointer m-1 rounded" data-variation="{{ $stock->variant }}">
                      <img class="lazyload mw-100 size-50px mx-auto border" src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($stock->image) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                   </div>
                   @endif
                    @endforeach 
                </div>
             </div>
          </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="bg-white shadow-sm rounded p-3 min_width_698">
            <div  class="text-left pl_30">
              <h1 class="mb-0 fs-20 fw-600">
                {{ $detailedProduct->getTranslation('name') }}
              </h1>
              <div class="col-lg-12">
                <div class="row align-items-center justify-content-between ">
                  <div class="d-flex"> @if (home_price($detailedProduct) != home_discounted_price($detailedProduct)) <div class="row  no-gutters my-2 ml-2">
                      <div class="col-sm-10">
                        <div class="fs-20">
                          <strong class="fs-20 fw-700 text-primary">
                            {{ home_discounted_price($detailedProduct) }}
                          </strong>
                        </div>
                      </div>
                    </div>
                    <div class="row  no-gutters my-2 pl-2 pt__6">
                      <div class="col-sm-10">
                        <div class="fs-14 opacity-60">
                          <del>
                            {{ home_price($detailedProduct) }}
                          </del>
                        </div>
                      </div>
                    </div> @if (discount_in_percentage($detailedProduct) > 0) <span class="badge-custom offer_text">{{ discount_in_percentage($detailedProduct) }}% {{ translate('OFF') }}</span> @endif @else <div class="row no-gutters mt-3">
                      <div class="col-sm-10">
                        <div style="white-space: nowrap" class="fs-20">
                          <strong class="fs-20 fw-700 text-primary">
                            {{ home_discounted_price($detailedProduct) }}
                          </strong>
                        </div>
                      </div>
                    </div> @endif
                  </div>
                  <div>
                    <div class="row mobile_row no-gutters my-2 pr_15">
                      <div class="col-12"> @php $total = 0; $total += $detailedProduct->reviews->count(); @endphp <span class="rating">
                          {{ renderStarRating($detailedProduct->rating) }}
                        </span>
                        <span class="ml-1 opacity-50">({{ $total }})</span>
                      </div> @if ($detailedProduct->est_shipping_days) <div class="col-auto ml">
                        <small class="mr-2 opacity-50">{{ translate('Estimate Shipping Time') }}: </small>{{ $detailedProduct->est_shipping_days }} {{ translate('Days') }}
                      </div> @endif
                    </div>
                  </div>
                </div>
              </div> @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0) <div class="row mobile_row no-gutters mt-4">
                <div class="col-sm-2">
                  <div class="opacity-50 my-2">{{ translate('Club Point') }}:</div>
                </div>
                <div class="col-sm-10">
                  <div class="d-inline-block rounded px-2 bg-soft-primary border-soft-primary border">
                    <span class="strong-700">{{ $detailedProduct->earn_point }}</span>
                  </div>
                </div>
              </div> @endif
              
             @if ($detailedProduct->stocks->first()->sku) 
             
             <div class="row no-gutters pt-2 mobile_row mobile_pl_16">
                <div class="col-lg-2 col-sm-4 col-4 mr-2">
                  <div class="my-2 product_quantity_text">{{ translate('Product Code') }}:</div>
                </div>
                <div class="col-lg-8 col-sm-3 col-3">
                    <div class="p_code">
                        {{ $detailedProduct->stocks->first()->sku }}
                    </div>
                    
                </div>
             </div>
          
               
             @endif
             <!--@if ($key == 0) checked @endif-->
              <form id="option-choice-form"> @csrf <input type="hidden" name="id" value="{{ $detailedProduct->id }}">
              @if (is_array(json_decode($detailedProduct->colors)) && count(json_decode($detailedProduct->colors)) > 0)
    <div class="row mobile_row no-gutters align-items-center my-2">
        <!-- Color Label -->
        <div class="col-md-3 col-sm-3 col-3">
            <div class="select_color">{{ translate('Select Color') }}:</div>
        </div>

        <!-- Color Options -->
        @php
            // Group by color and take the first image for each distinct color
            $firstImages = $detailedProduct->stocks->groupBy(function($stock) {
                return explode('-', $stock->variant)[0];  // Extract color part
            })->map(function($group) {
                return $group->first()->image;  // Get the first stock image for each color
            })->values();  // Reset the keys to numeric
        @endphp

        <div class="col-md-9 col-sm-9 col-9">
            <div class="aiz-radio-inline d-flex flex-wrap align-items-center">
                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                    @php
                        $colorName = \App\Models\Color::where('code', $color)->first()->name;
                    @endphp
                    <label class="aiz-megabox pl-0 mr-2" data-toggle="tooltip" data-title="{{ $colorName }}">
                        <input type="radio" name="color" value="{{ $colorName }}" @if ($key == 0) checked @endif>
                        <span class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center p-1 mb-0">
                            @if (isset($firstImages[$key]) && $firstImages[$key])
                                <span class="d-inline-block rounded">
                                    <img src="{{ uploaded_asset($firstImages[$key]) }}" width="40" height="60" alt="">
                                </span>
                            @endif
                        </span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Size Options (if available) -->
    @if ($detailedProduct->choice_options != null)
        @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)
            <div class="row no-gutters align-items-center my-2">
                <!-- Size Label -->
                <div class="col-md-3 col-sm-3 col-3">
                    <div class="select_color">
                        Select {{ \App\Models\Attribute::find($choice->attribute_id)->getTranslation('name') }}:
                    </div>
                </div>

                <!-- Size Options -->
                <div class="col-md-9 col-sm-9 col-9">
                    <div class="aiz-radio-inline d-flex flex-wrap align-items-center">
                        @foreach ($choice->values as $i => $value)
                            <label class="aiz-megabox pl-0 mr-2 mb-0">
                                <input type="radio" name="attribute_id_{{ $choice->attribute_id }}" value="{{ $value }}" @if ($i == 0) checked @endif>
                                <span class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center py-2 px-3">
                                    {{ $value }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endif

               
                <hr> 
                <div class="row mobile_row no-gutters">
                  {{-- <div class="col-sm-12 col-12"> {!! $detailedProduct->short_description !!} </div> --}}
                </div>
                <!-- Quantity + Add to cart -->
                <div class="row mobile_row no-gutters mobile_pl_16 pt-2">
                  <div class="col-lg-2 col-sm-3 col-3 mr-2">
                    <div class="my-2 product_quantity_text">{{ translate('Quantity') }}:</div>
                  </div>
                  <div class="col-md-3 col-sm-4 col-4">
                    <div class="product-quantity d-flex align-items-center">
                      <div class="row mobile_row no-gutters align-items-center aiz-plus-minus mr-3" style="width: 130px;">
                        <button class="btn col-auto btn-icon  btn-sm minus_btn btn___padding product_plus_minus" type="button" data-type="minus" data-field="quantity" disabled="">
                          <i class="las la-minus"></i>
                        </button>
                        <input type="number" name="quantity" class="col border-0 text-center flex-grow-1 fs-16 input-number num_input product_num_input" placeholder="1" value="{{ $detailedProduct->min_qty }}" min="{{ $detailedProduct->min_qty }}" max="10">
                        <button class="btn  col-auto btn-icon btn-sm plus_btn btn___padding product_plus_minus" type="button" data-type="plus" data-field="quantity">
                          <i class="las la-plus"></i>
                        </button>
                      </div> @php $qty = 0; foreach ($detailedProduct->stocks as $key => $stock) { $qty += $stock->qty; } @endphp
                    </div>
                  </div>
                </div> @if ($qty >= 1) <div class="row mobile_row no-gutters mobile_pl_16">
                  <div class="col-sm-3 col-3">
                    <div class="my-2">{{ translate('In Stock') }}:</div>
                  </div>
                  <div class="col-sm-9 col-9">
                    <div class="avialable-amount my-2"> @if ($detailedProduct->stock_visibility_state == 'quantity') ({{ translate('available') }}
                      <span id="available-quantity">{{ $qty }}</span> ) @elseif($detailedProduct->stock_visibility_state == 'text' && $qty >= 1) ( <span id="available-quantity">{{ translate('In Stock') }}</span>) @endif
                    </div>
                  </div>
                </div> @endif <div class="row  no-gutters pb-2 d-none mobile_row mobile_pl_16" id="chosen_price_div">
                  <div class="col-lg-2 col-sm-3 col-3">
                    <div class="my-2">{{ translate('Total Price') }}:</div>
                  </div>
                  <div class="col-sm-3 col-3">
                    <div class="product-price">
                      <strong id="chosen_price" class="h4 fw-600 text-primary"></strong>
                      <span class="discount_text_css">
                        <span class="discount_text" id="discount_text"></span>
                        <span class="offer_discount fw-600" id="offer_discount"></span>
                      </span>
                    </div>
                  </div>
              
                </div>
              </form>
              <div class="product_details_btn">
                <div class="">
                  <div class=""> @if ($detailedProduct->external_link != null) <a type="button" class="btn btn-primary mr-2 add-to-cart fw-600 second_button " href="{{ $detailedProduct->external_link }}">
                      <i class="fa-solid fa-cart-shopping"></i>
                      <span class="d-md-inline-block text-uppercase"> {{ translate('Add to cart') }}</span>
                    </a> @else <button type="button" class="btn btn-primary mr-2 add-to-cart fw-600 second_button " onclick="addToCart()">
                      <i class="fa-solid fa-cart-shopping"></i>
                      <span class="d-md-inline-block text-uppercase"> {{ translate('Add to cart') }}</span>
                    </button> @endif <button type="button" class="btn btn-secondary out-of-stock fw-600 d-none" disabled>
                      <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock') }}
                    </button>
                  </div>
                </div>
                  <div class="">
                  @if ($detailedProduct->external_link != null)
                      <a type="button" class="btn btn-primary buy-now fw-600 second_button text-uppercase add_to_cart_btn_product zoom-effect" href="{{ $detailedProduct->external_link }}">
                          <i class="fa-solid fa-cart-shopping"></i>
                          <span>{{ translate('Buy Now') }}</span>
                      </a>
                  @else
                      <button type="button" style="border: 1px solid #000c50" class="btn btn-primary buy-now fw-600 text-uppercase add_to_cart_btn_product buy_n zoom-effect" onclick="buyNow()">
                          <i class="fa-solid fa-cart-shopping"></i>
                          <span>{{ translate('Buy Now') }}</span>
                      </button>
                  @endif
              </div>
                
                 
              </div>
              <div class="product_details_btn mt-3">
                <a href="tel:+88{{ get_setting('contact_phone') }}" class="btn btn-primary buy-now fw-600 width_100" style="border: 1px solid #0a608b;background-color:#0a608b">
                    <i class="fa fa-phone"></i>
                    <span>  কল করতে ক্লিক করুন : {{ get_setting('contact_phone') }}</span>
                </a>
            </div>
            
            
            <div class="product_details_btn mt-3">
              <a href="https://wa.me/{{ get_setting('contact_phone') }}" class="btn btn-primary buy-now fw-600 width_100" style="border: 1px solid #185a54;background-color:#185a54">
                  <i class="fab fa-whatsapp"></i>
                  <span>Whatsapp Message : {{ get_setting('contact_phone') }}</span>
              </a>
          </div>
          

              <div class="delivery_charge_table mt-3">      
                {!! get_setting('delevery_charge_info') !!}
                            </div>
              <div class="d-table width-100 mt-3">
                <div class="d-table-cell">
                  <!-- Add to wishlist button --> @if (Auth::check() &&
                          addon_is_activated('affiliate_system') &&
                          (\App\Models\AffiliateOption::where('type', 'product_sharing')->first()->status ||
                              \App\Models\AffiliateOption::where('type', 'category_wise_affiliate')->first()->status) &&
                          Auth::user()->affiliate_user != null &&
                          Auth::user()->affiliate_user->status) @php if(Auth::check()){ if(Auth::user()->referral_code == null){ Auth::user()->referral_code = substr(Auth::user()->id.Str::random(10), 0, 10); Auth::user()->save(); } $referral_code = Auth::user()->referral_code; $referral_code_url = URL::to('/product').'/'.$detailedProduct->slug."?product_referral_code=$referral_code"; } @endphp <div>
                    <button type=button id="ref-cpurl-btn" class="btn btn-sm btn-secondary" data-attrcpy="{{ translate('Copied') }}" onclick="CopyToClipboard(this)" data-url="{{ $referral_code_url }}">{{ translate('Copy the Promote Link') }}</button>
                  </div> @endif
                </div>
              </div>
              <div class="row no-gutters">
                <div class="col-md-1 col-sm-2 col-2">
                  <div class="opacity-50 my-2 pt-1">{{ translate('Share') }}:</div>
                </div>
                <div class="col-md-11 col-sm-10 col-10">
                  <div class="aiz-share"></div>
                </div>
              </div>
              {{-- <div class="row no-gutters">
																	<div class="col-sm-12">
																		<div class="sms"> প্রডাক্ট সম্পর্কে বিস্তারিত তথ্য জানতে যোগাযোগ করুন এই নাম্বারে 
																			<a href="tel:{{ get_setting('contact_phone') }}" class="fw-600">{{ get_setting('contact_phone') }}</a>
            </div>
          </div>
        </div> --}}
      </div>
    </div>
    </div>
    </div>
    </div>
  </section>

    <section class="mb-4">
        <div class="container">
            <div class="row mobile_row gutters-10">
                
                <div class="col-xl-12 order-0 order-xl-1">

                    <div class="bg-white mb-3 shadow-sm rounded set_mt_22">
                        <ul class="accordion-list">
                            <li>
                              <h3>Description <i class="fas fa-plus"></i></h3>

                              <div class="answer">
                           {!! $detailedProduct->getTranslation('description') !!} 
                              </div>
                            </li>
                          
                        <li>
                          <h3>Video <i class="fas fa-plus"></i></h3>
                          <div class="answer">
                          <div class="video-container">
                              @if ($detailedProduct->video_link)
                                  @if (strpos($detailedProduct->video_link, 'youtube.com/embed') !== false)
                                      <!-- YouTube Embed -->
                                      <iframe width="100%" height="315" src="{{ $detailedProduct->video_link }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                  @elseif (strpos($detailedProduct->video_link, 'facebook.com/plugins/video.php') !== false)
                                  <!-- Facebook Embed -->
                                  {{-- {{ dd($detailedProduct->video_link) }} --}}
                                  <iframe src="{{ $detailedProduct->video_link }}" width="100%" height="315" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                                  @endif
                              @else
                                  <p>No video available.</p>
                              @endif
                          </div>
                        </div>
                        </li>
                            <li>
                              <h3>Ingredient <i class="fas fa-plus"></i></h3>
                              <div class="answer">{!! $detailedProduct->getTranslation('ingredients') !!}</div>
                            </li>
                            <li>
                              <h3>Faq <i class="fas fa-plus"></i></h3>
                              <div class="answer">{!! $detailedProduct->getTranslation('faq') !!}</div>
                            </li>
                            <li>
                              <h3>Reviews <i class="fas fa-plus"></i></h3>
                              <div class="answer">
                                <div class="p-4 mobile-p-0">
                                    <ul class="list-group list-group-flush"> @foreach ($detailedProduct->reviews as $key => $review) @if ($review->user != null) <li class="media list-group-item d-flex">
                                        <span class="avatar avatar-md mr-3">
                                            {{-- {{ dd(uploaded_asset($review->user->avatar_original)) }} --}}
                                          <img class="lazyload" src="{{ asset($review->user->avatar_original) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" @if ($review->user->avatar_original != null) data-src="{{ uploaded_asset($review->user->avatar_original) }}" @else data-src="{{ static_asset('assets/img/placeholder.jpg') }}" @endif > </span>
                                        <div class="media-body text-left">
                                          <div class="d-flex justify-content-between">
                                            <h3 class="fs-15 fw-600 mb-0">{{ $review->user->name }}</h3>
                                            <span class="rating rating-sm d-flex"> @for ($i = 0; $i < $review->rating; $i++) <i class="las la-star active"></i> @endfor @for ($i = 0; $i < 5 - $review->rating; $i++) <i class="las la-star"></i> @endfor </span>
                                          </div>
                                          <div class="opacity-60 mb-2">{{ date('d-m-Y', strtotime($review->created_at)) }}</div>
                                          <p class="comment-text">
                                            {{ $review->comment }}
                                          </p>
                                        </div>
                                      </li> @endif @endforeach </ul> @if (count($detailedProduct->reviews) <= 0) <div class="text-center fs-18 opacity-70">
                                      {{ translate('There have been no reviews for this product yet.') }}
                                  </div> @endif @if (Auth::check()) @php $commentable = false; @endphp @foreach ($detailedProduct->orderDetails as $key => $orderDetail) @if (
                                      $orderDetail->order != null &&
                                          $orderDetail->order->user_id == Auth::user()->id &&
                                          $orderDetail->delivery_status == 'delivered' &&
                                          \App\Models\Review::where('user_id', Auth::user()->id)->where('product_id', $detailedProduct->id)->first() == null) @php $commentable = true; @endphp @endif @endforeach @if ($commentable) <div class="pt-4">
                                    <div class="border-bottom mb-4">
                                      <h3 class="fs-17 fw-600">
                                        {{ translate('Write a review') }}
                                      </h3>
                                    </div>
                                    <form class="form-default" role="form" action="{{ route('reviews.store') }}" method="POST"> @csrf <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="" class="text-uppercase c-gray-light">{{ translate('Your name') }}</label>
                                            <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control" disabled required>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="" class="text-uppercase c-gray-light">{{ translate('Email') }}</label>
                                            <input type="text" name="email" value="{{ Auth::user()->email }}" class="form-control" required disabled>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label class="opacity-60">{{ translate('Rating') }}</label>
                                        <div class="rating rating-input ">
                                          <label>
                                            <input type="radio" name="rating" value="1" required>
                                            <i class="las la-star"></i>
                                          </label>
                                          <label>
                                            <input type="radio" name="rating" value="2">
                                            <i class="las la-star"></i>
                                          </label>
                                          <label>
                                            <input type="radio" name="rating" value="3">
                                            <i class="las la-star"></i>
                                          </label>
                                          <label>
                                            <input type="radio" name="rating" value="4">
                                            <i class="las la-star"></i>
                                          </label>
                                          <label>
                                            <input type="radio" name="rating" value="5">
                                            <i class="las la-star"></i>
                                          </label>
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label class="opacity-60">{{ translate('Comment') }}</label>
                                        <textarea class="form-control" rows="4" name="comment" placeholder="{{ translate('Your review') }}" required></textarea>
                                      </div>
                                      <div class="text-right">
                                        <button type="submit" class="btn btn-primary mt-3">
                                          {{ translate('Submit review') }}
                                        </button>
                                      </div>
                                    </form>
                                  </div> @endif @endif
                                </div>
                              </div>
                            </li>
                        
                    
                          </ul>
                        
                  
                    </div>
                    
                    <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                        <div class="d-flex mb-3 align-items-baseline justify-content-center">
                            <h3 class="h5 fw-700 mb-0">
                                <span
                                    class="pb-3 d-inline-block">{{ translate('You May Also Like') }}</span>
                            </h3>
                            {{-- <h6 class="border-bottom border-width-2 ml-auto mr-0">
                                <a href="javascript:void(0)" class="view_all_btn">{{ translate('View All') }} <i class="fa-solid fa-caret-right"></i></a>
                            </h6> --}}
                        </div>
                        @php
                            $related_product = filter_products(\App\Models\Product::where('category_id', $detailedProduct->category_id)->where('id', '!=', $detailedProduct->id))->limit(12)->get();
                        @endphp
                             <div class="row">
                                
                                @foreach ($related_product as $key => $product)
                                    <div class="col-md-3 col-sm-6 col-6">
                                        @include('frontend.partials.product_box_1', [
                                            'product' => $product,
                                        ])
                                    </div>
                                @endforeach
                            </div>
                    </div>

          
                </div>
            </div>
        </div>
    </section>

@endsection

@section('modal')
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3" name="title" value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="8" name="message" required placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600" data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary fw-600">{{ translate('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">{{ translate('Login') }}</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="p-3">
                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                @if (addon_is_activated('otp_system'))
                                    <input type="text" class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone') }}" name="email" id="email">
                                @else
                                    <input type="email" class="form-control h-auto form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email">
                                @endif
                                @if (addon_is_activated('otp_system'))
                                    <span class="opacity-60">{{ translate('Use country code before number') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control h-auto form-control-lg" placeholder="{{ translate('Password') }}">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span class=opacity-60>{{ translate('Remember Me') }}</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{ route('password.request') }}" class="text-reset opacity-60 fs-14">{{ translate('Forgot password?') }}</a>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button type="submit" class="btn btn-primary btn-block fw-600">{{ translate('Login') }}</button>
                            </div>
                        </form>

                        <div class="text-center mb-3">
                            <p class="text-muted mb-0">{{ translate('Dont have an account?') }}</p>
                            <a href="{{ route('user.registration') }}">{{ translate('Register Now') }}</a>
                        </div>
                        @if (get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1)
                            <div class="separator mb-3">
                                <span class="bg-white px-3 opacity-60">{{ translate('Or Login With') }}</span>
                            </div>
                            <ul class="list-inline social colored text-center mb-5">
                                @if (get_setting('facebook_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook">
                                            <i class="lab la-facebook-f"></i>
                                        </a>
                                    </li>
                                @endif
                                @if (get_setting('google_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                            <i class="lab la-google"></i>
                                        </a>
                                    </li>
                                @endif
                                @if (get_setting('twitter_login') == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="twitter">
                                            <i class="lab la-twitter"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    
   <style>
@keyframes zoomInOut {
    0% {
        transform: scale(1);  /* Normal size */
    }
    50% {
        transform: scale(1.1);  /* Zoom in */
    }
    100% {
        transform: scale(1);  /* Back to normal */
    }
}

.zoom-effect {
    animation: zoomInOut 2s infinite ease-in-out; /* 2s duration for one cycle */
}


    </style>  
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
    	});

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
           
        }
        function show_chat_modal(){
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show'); @endif
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                                                                                                                                }

                                                                                                                                                                                                                                                                                                                                                        </script>
                                                                                                                                                                                                                                                                                                                                                        <script>
                                                                                                                                                                                                                                                                                                                                                            $(document).ready(function() {
                                                                                                                                                                                                                                                                                                                                                                $('.accordion-list > li > .answer').hide();

                                                                                                                                                                                                                                                                                                                                                                var $descriptionSection = $('.accordion-list > li:first-child');
                                                                                                                                                                                                                                                                                                                                                                $descriptionSection.addClass('active').find('.answer').slideDown();
                                                                                                                                                                                                                                                                                                                                                                $descriptionSection.find('h3 > svg').removeClass('fas fa-plus').addClass('fas fa-minus');


                                                                                                                                                                                                                                                                                                                                                                $('.accordion-list > li > h3').click(function() {
                                                                                                                                                                                                                                                                                                                                                                    var $parent = $(this).parent(); // The parent <li> element

                                                                                                                                                                                                                                                                                                                                                                    var $answer = $parent.find('.answer');
                                                                                                                                                                                                                                                                                                                                                                    var $icon = $(this).find('svg');

                                                                                                                                                                                                                                                                                                                                                                    if ($parent.hasClass("active")) {

                                                                                                                                                                                                                                                                                                                                                                        $parent.removeClass("active").find(".answer").slideUp();
                                                                                                                                                                                                                                                                                                                                                                        $icon.removeClass("fas fa-minus").addClass("fas fa-plus");
                                                                                                                                                                                                                                                                                                                                                                    } else {

                                                                                                                                                                                                                                                                                                                                                                        $(".accordion-list > li.active .answer").slideUp();
                                                                                                                                                                                                                                                                                                                                                                        $(".accordion-list > li.active").removeClass("active");
                                                                                                                                                                                                                                                                                                                                                                        $parent.addClass("active").find(".answer").slideDown();
                                                                                                                                                                                                                                                                                                                                                                        $icon.removeClass("fas fa-plus").addClass("fas fa-minus");
                                                                                                                                                                                                                                                                                                                                                                    }

                                                                                                                                                                                                                                                                                                                                                                    $('.accordion-list > li').each(function() {
                                                                                                                                                                                                                                                                                                                                                                        var $icon = $(this).find('h3 svg');
                                                                                                                                                                                                                                                                                                                                                                        if (!$(this).hasClass("active")) {
                                                                                                                                                                                                                                                                                                                                                                            $icon.removeClass("fas fa-minus").addClass("fas fa-plus");
                                                                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                                                                    });

                                                                                                                                                                                                                                                                                                                                                                    return false;
                                                                                                                                                                                                                                                                                                                                                                });
                                                                                                                                                                                                                                                                                                                                                            });
                                                                                                                                                                                                                                                                                                                                                        </script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
@endsection

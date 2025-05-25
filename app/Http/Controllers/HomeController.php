<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Mail;
use Cache;
use Cookie;
use App\Models\Shop;
use App\Models\User;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\PickupPoint;
use App\Models\SmsTemplate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RecentlyViewed;
use Illuminate\Support\Carbon;
use App\Models\AffiliateConfig;
use App\Models\BusinessSetting;
use App\Models\CustomerPackage;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\PasswordReset;
use App\Mail\SecondEmailVerifyMailManager;


class HomeController extends Controller
{
    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $featured_categories = Cache::rememberForever('featured_categories', function () {
            return Category::where('featured', 1)->get();
        });

        $todays_deal_products = Cache::rememberForever('todays_deal_products', function () {
            return filter_products(Product::where('published', 1)->where('todays_deal', '1'))->get();
        });

        return view('frontend.index', compact('featured_categories', 'todays_deal_products'));
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('frontend.user_login');
    }

    public function registration(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        if ($request->has('referral_code') && addon_is_activated('affiliate_system')) {
            try {
                $affiliate_validation_time = AffiliateConfig::where('type', 'validation_time')->first();
                $cookie_minute = 30 * 24;
                if ($affiliate_validation_time) {
                    $cookie_minute = $affiliate_validation_time->value * 60;
                }

                Cookie::queue('referral_code', $request->referral_code, $cookie_minute);
                $referred_by_user = User::where('referral_code', $request->product_referral_code)->first();

                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
            } catch (\Exception $e) {
            }
        }
        return view('frontend.user_registration');
    }

    public function cart_login(Request $request)
    {

        $user = null;
        if ($request->get('phone') != null) {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('phone', "+{$request['country_code']}{$request['phone']}")->first();
        } elseif ($request->get('email') != null) {
            $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->first();
        }

        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                if ($request->has('remember')) {
                    auth()->login($user, true);
                } else {
                    auth()->login($user, false);
                }
            } else {
                flash(translate('Invalid email or password!'))->warning();
            }
        } else {
            flash(translate('Invalid email or password!'))->warning();
        }
        return redirect(route('checkout.shipping_info'));
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if (Auth::user()->user_type == 'seller') {
            return view('frontend.user.seller.dashboard');
        } elseif (Auth::user()->user_type == 'customer') {
            return view('frontend.user.customer.dashboard');
        } elseif (Auth::user()->user_type == 'delivery_boy') {
            return view('delivery_boys.frontend.dashboard');
        } else {
            abort(404);
        }
    }

    public function profile(Request $request)
    {
        if (Auth::user()->user_type == 'delivery_boy') {
            return view('delivery_boys.frontend.profile');
        } else {
            return view('frontend.user.profile');
        }
    }

    public function userProfileUpdate(Request $request)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }

        $user->avatar_original = $request->photo;

        $seller = $user->seller;

        if ($seller) {
            $seller->cash_on_delivery_status = $request->cash_on_delivery_status;
            $seller->bank_payment_status = $request->bank_payment_status;
            $seller->bank_name = $request->bank_name;
            $seller->bank_acc_name = $request->bank_acc_name;
            $seller->bank_acc_no = $request->bank_acc_no;
            $seller->bank_routing_no = $request->bank_routing_no;

            $seller->save();
        }

        $user->save();

        flash(translate('Your Profile has been updated successfully!'))->success();
        return back();
    }

    public function flash_deal_details($slug)
    {
        $flash_deal = FlashDeal::where('slug', $slug)->first();
        if ($flash_deal != null)
            return view('frontend.flash_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }

    public function load_featured_section()
    {
        return view('frontend.partials.featured_products_section');
    }

    public function load_top_client_section()
    {
        return view('frontend.partials.top_client_section');
    }
    public function load_best_selling_section()
    {
        return view('frontend.partials.best_selling_section');
    }

    public function load_auction_products_section()
    {
        if (!addon_is_activated('auction')) {
            return;
        }
        return view('auction.frontend.auction_products_section');
    }
    public function load_home_todays_deal()
    {
        return view('frontend.partials.home_todays_deal');
    }


    public function load_home_categories_section()
    {
        return view('frontend.partials.home_categories_section');
    }

    public function load_best_sellers_section()
    {
        return view('frontend.partials.best_sellers_section');
    }
    public function load_recently_viewed_section()
    {
        return view('frontend.partials.recently_viewed_section');
    }
    public function load_home_blog_section()
    {
        return view('frontend.partials.home_blog_section');
    }
    public function load_new_selling_section()
    {
        return view('frontend.partials.new_selling');
    }
    public function load_home_home_gallery_section()
    {
        return view('frontend.partials.home_gallery_section');
    }

    public function trackOrder(Request $request)
    {

        if ($request->has('order_code')) {
            $order = Order::where('code', $request->order_code)->first();
            if ($order != null) {
                return view('frontend.track_order', compact('order'));
            }
        }
        if ($request->has('order_code')) {
            $order_traking = Order::where('tracking_code', $request->order_code)->first();
            if ($order_traking != null) {
                return redirect()->to('https://merchant.pathao.com/tracking?consignment_id=' . $request->order_code);
            }
        }
        return view('frontend.track_order');
    }

    public function product(Request $request, $slug)
    {
        $detailedProduct  = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop')->where('auction_product', 0)->where('slug', $slug)->where('approved', 1)->first();

        if ($detailedProduct != null && $detailedProduct->published) {
            if ($request->has('product_referral_code') && addon_is_activated('affiliate_system')) {

                $affiliate_validation_time = AffiliateConfig::where('type', 'validation_time')->first();
                $cookie_minute = 30 * 24;
                if ($affiliate_validation_time) {
                    $cookie_minute = $affiliate_validation_time->value * 60;
                }
                Cookie::queue('product_referral_code', $request->product_referral_code, $cookie_minute);
                Cookie::queue('referred_product_id', $detailedProduct->id, $cookie_minute);

                $referred_by_user = User::where('referral_code', $request->product_referral_code)->first();

                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliateStats($referred_by_user->id, 1, 0, 0, 0);
            }
            $json = @file_get_contents('http://ip-api.com/json/');
            $data = json_decode($json, JSON_PRETTY_PRINT);

            $ip_address = $_SERVER['REMOTE_ADDR'];
            $existingVisitor = RecentlyViewed::where('product_id', $detailedProduct->id)
                ->where('ip_address', $ip_address)->first();

            $temp_user = Session::has('temp_user') ? session('temp_user') : 'visitor-' . date('Ymd') . Str::random(20) . date('His') . $_SERVER['REMOTE_ADDR'];

            // Check if $temp_user is not in the session, then put it into the session
            if (!Session::has('temp_user')) {
                Session::put('temp_user', $temp_user);
            }

            // Check if a user is logged in
            if (Auth::check()) {
                $temp_user = auth()->user()->id;
            }

            if ($existingVisitor) {
                // If a visitor record exists for this product_id and IP address, update the visitor_id
                $existingVisitor->visitor_id = $temp_user;
                $existingVisitor->visitor += 1;
                $existingVisitor->save();
            } else {
                // If no visitor record exists, create a new one
                $visitor = new RecentlyViewed();

                // Use $temp_user as the visitor_id, which may be either the session value or user ID
                $visitor->visitor_id = $temp_user;

                $visitor->data = $json;
                $visitor->ip_address = $ip_address;
                $visitor->product_id = $detailedProduct->id;
                $visitor->visitor = 1;
                $visitor->save();
            }

            if ($detailedProduct->digital == 1) {
                return view('frontend.digital_product_details', compact('detailedProduct'));
            } else {
                return view('frontend.product_details', compact('detailedProduct'));
            }
        }
        abort(404);
    }

    public function shop($slug)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if ($shop != null) {
            $seller = Seller::where('user_id', $shop->user_id)->first();
            if ($seller->verification_status != 0) {
                return view('frontend.seller_shop', compact('shop'));
            } else {
                return view('frontend.seller_shop_without_verification', compact('shop', 'seller'));
            }
        }
        abort(404);
    }

    public function filter_shop($slug, $type)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if ($shop != null && $type != null) {
            return view('frontend.seller_shop', compact('shop', 'type'));
        }
        abort(404);
    }

    public function all_categories(Request $request)
    {
        //        $categories = Category::where('level', 0)->orderBy('name', 'asc')->get();
        $categories = Category::where('level', 0)->orderBy('order_level', 'desc')->paginate(16);
        return view('frontend.all_category', compact('categories'));
    }
    public function all_reviews(Request $request)
    {

        //        $categories = Category::where('level', 0)->orderBy('name', 'asc')->get();

        return view('frontend.all_reviews');
    }
    public function all_brands(Request $request)
    {
        $brands = Brand::paginate(18);
        return view('frontend.all_brand', compact('brands'));
    }

    public function show_product_upload_form(Request $request)
    {
        $seller = Auth::user()->seller;
        if (addon_is_activated('seller_subscription')) {
            if ($seller->seller_package && $seller->seller_package->product_upload_limit > $seller->user->products()->count()) {
                $categories = Category::where('parent_id', 0)
                    ->where('digital', 0)
                    ->with('childrenCategories')
                    ->get();
                return view('frontend.user.seller.product_upload', compact('categories'));
            } else {
                flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                return back();
            }
        }
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('frontend.user.seller.product_upload', compact('categories'));
    }

    public function show_product_edit_form(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('frontend.user.seller.product_edit', compact('product', 'categories', 'tags', 'lang'));
    }

    public function seller_product_list(Request $request)
    {
        $search = null;
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 0)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%' . $search . '%');
        }
        $products = $products->paginate(10);
        return view('frontend.user.seller.products', compact('products', 'search'));
    }

    public function home_settings(Request $request)
    {
        return view('home_settings.index');
    }

    public function top_10_settings(Request $request)
    {
        foreach (Category::all() as $key => $category) {
            if (is_array($request->top_categories) && in_array($category->id, $request->top_categories)) {
                $category->top = 1;
                $category->save();
            } else {
                $category->top = 0;
                $category->save();
            }
        }

        foreach (Brand::all() as $key => $brand) {
            if (is_array($request->top_brands) && in_array($brand->id, $request->top_brands)) {
                $brand->top = 1;
                $brand->save();
            } else {
                $brand->top = 0;
                $brand->save();
            }
        }

        flash(translate('Top 10 categories and brands have been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $offer_quantity = json_decode($product->offer_quantity);


        $str = '';
        $quantity = 0;
        $tax = 0;
        $max_limit = 0;

        if ($request->has('color')) {
            $str = $request['color'];
        }

        if (json_decode($product->choice_options) != null) {
            foreach (json_decode($product->choice_options) as $key => $choice) {
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                } else {
                    $str .= str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                }
            }
        }

        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;

        if ($product->wholesale_product) {
            $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }

        $quantity = $product_stock->qty;
        $max_limit = $product_stock->qty;

        if ($quantity >= 1 && $product->min_qty <= $quantity) {
            $in_stock = 1;
        } else {
            $in_stock = 0;
        }

        //Product Stock Visibility
        if ($product->stock_visibility_state == 'text') {
            if ($quantity >= 1 && $product->min_qty < $quantity) {
                $quantity = translate('In Stock');
            } else {
                $quantity = translate('Out Of Stock');
            }
        }

        //discount calculation
        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        // taxes
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        $price += $tax;
        $total_price = $price * $request->quantity;
        // $offer_discount = 0;
        // if ($offer_quantity != null) {
        //     foreach ($offer_quantity as $o_quantity) {
        //         if ($o_quantity->quantity == $request->quantity) {
        //             $offer_discount = $o_quantity->discount;
        //             $total_price = $total_price - $o_quantity->discount;
        //         }
        //     }
        // }
        // Session::put('offer_discount',$offer_discount);

        return array(
            'price' => single_price($total_price),
            'quantity' => $quantity,
            'digital' => $product->digital,
            'variation' => $str,
            'max_limit' => $max_limit,
            'in_stock' => $in_stock,
        );
    }

    public function sellerpolicy()
    {
        return view("frontend.policies.sellerpolicy");
    }

    public function returnpolicy()
    {
        return view("frontend.policies.returnpolicy");
    }

    public function supportpolicy()
    {
        return view("frontend.policies.supportpolicy");
    }

    public function terms()
    {
        return view("frontend.policies.terms");
    }

    public function privacypolicy()
    {
        return view("frontend.policies.privacypolicy");
    }
    public function about_us()
    {
        return view("frontend.pages.about_us");
    }

    public function get_pick_up_points(Request $request)
    {
        $pick_up_points = PickupPoint::all();
        return view('frontend.partials.pick_up_points', compact('pick_up_points'));
    }

    public function get_category_items(Request $request)
    {
        $category = Category::findOrFail($request->id);
        return view('frontend.partials.category_elements', compact('category'));
    }

    public function premium_package_index()
    {
        $customer_packages = CustomerPackage::all();
        return view('frontend.user.customer_packages_lists', compact('customer_packages'));
    }

    public function seller_digital_product_list(Request $request)
    {
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.user.seller.digitalproducts.products', compact('products'));
    }
    public function show_digital_product_upload_form(Request $request)
    {
        $seller = Auth::user()->seller;
        if (addon_is_activated('seller_subscription')) {
            if ($seller->seller_package && $seller->seller_package->product_upload_limit > $seller->user->products()->count()) {
                $categories = Category::where('digital', 1)->get();
                return view('frontend.user.seller.digitalproducts.product_upload', compact('categories'));
            } else {
                flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                return back();
            }
        }
        $categories = Category::where('digital', 1)->get();
        return view('frontend.user.seller.digitalproducts.product_upload', compact('categories'));
    }

    public function show_digital_product_edit_form(Request $request, $id)
    {
        $categories = Category::where('digital', 1)->get();
        $lang = $request->lang;
        $product = Product::find($id);
        return view('frontend.user.seller.digitalproducts.product_edit', compact('categories', 'product', 'lang'));
    }

    // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;
        if (isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = 'Email already exists!';
            return json_encode($response);
        }

        $response = $this->send_email_change_verification_mail($request, $email);
        return json_encode($response);
    }


    // Form request
    public function update_email(Request $request)
    {
        $email = $request->email;
        if (isUnique($email)) {
            $this->send_email_change_verification_mail($request, $email);
            flash(translate('A verification mail has been sent to the mail you provided us with.'))->success();
            return back();
        }

        flash(translate('Email already exists!'))->warning();
        return back();
    }

    public function send_email_change_verification_mail($request, $email)
    {
        $response['status'] = 0;
        $response['message'] = 'Unknown';

        $verification_code = Str::random(32);

        $array['subject'] = 'Email Verification';
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = 'Verify your account';
        $array['link'] = route('email_change.callback') . '?new_email_verificiation_code=' . $verification_code . '&email=' . $email;
        $array['sender'] = Auth::user()->name;
        $array['details'] = "Email Second";

        $user = Auth::user();
        $user->new_email_verificiation_code = $verification_code;
        $user->save();

        try {
            Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));

            $response['status'] = 1;
            $response['message'] = translate("Your verification mail has been Sent to your email.");
        } catch (\Exception $e) {
            // return $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function email_change_callback(Request $request)
    {
        if ($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param =  $request->input('new_email_verificiation_code');
            $user = User::where('new_email_verificiation_code', $verification_code_of_url_param)->first();

            if ($user != null) {

                $user->email = $request->input('email');
                $user->new_email_verificiation_code = null;
                $user->save();

                auth()->login($user, true);

                flash(translate('Email Changed successfully'))->success();
                return redirect()->route('dashboard');
            }
        }

        flash(translate('Email was not verified. Please resend your mail!'))->error();
        return redirect()->route('dashboard');
    }

    public function reset_password_with_code(Request $request)
    {
        if (($user = User::where('email', $request->email)->where('verification_code', $request->code)->first()) != null) {
            if ($request->password == $request->password_confirmation) {
                $user->password = Hash::make($request->password);
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();
                event(new PasswordReset($user));
                auth()->login($user, true);

                flash(translate('Password updated successfully'))->success();

                if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home');
            } else {
                flash("Password and confirm password didn't match")->warning();
                return redirect()->route('password.request');
            }
        } else {
            flash("Verification code mismatch")->error();
            return redirect()->route('password.request');
        }
    }


    public function all_flash_deals()
    {
        $today = strtotime(date('Y-m-d H:i:s'));

        $data['all_flash_deals'] = FlashDeal::where('status', 1)
            ->where('start_date', "<=", $today)
            ->where('end_date', ">", $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return view("frontend.flash_deal.all_flash_deal_list", $data);
    }

    public function all_seller(Request $request)
    {
        $shops = Shop::whereIn('user_id', verified_sellers_id())
            ->paginate(15);

        return view('frontend.shop_listing', compact('shops'));
    }

    public function all_coupons(Request $request)
    {
        $coupons = Coupon::where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')))->paginate(15);
        return view('frontend.coupons', compact('coupons'));
    }

    public function inhouse_products(Request $request)
    {
        $products = filter_products(Product::where('added_by', 'admin'))->with('taxes')->paginate(12)->appends(request()->query());
        return view('frontend.inhouse_products', compact('products'));
    }


    public function best_selling()
    {
        $best_sellings = Product::where('published', 1)
            ->orderBy('num_of_sale', 'desc')
            ->paginate(21);
        return view('frontend.pages.best_selling_product', compact('best_sellings'));
    }
    public function todays_deal()
    {
        $todays_deal_products = Product::where('published', 1)->where('todays_deal', 1)->paginate(12);
        return view('frontend.pages.todays_deal_product', compact('todays_deal_products'));
    }
    public function new_arrival()
    {
        $new_arraival = Product::where('published', 1)->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        return view('frontend.pages.new_arrival_product', compact('new_arraival'));
    }
    public function recently_viewed()
    {


        $ip_address = $_SERVER['REMOTE_ADDR'];
        $recently_viewed_products = RecentlyViewed::with('viewedProduct')
            ->whereHas('viewedProduct')
            ->where('ip_address', $ip_address)
            ->orderBy('visitor', 'desc')->limit(20)->get();

        return view('frontend.pages.recently_viewed_product', compact('recently_viewed_products'));
    }
    public function all_client()
    {
        return view('frontend.pages.all_client');
    }

    public function updateSession(Request $request)
    {

        $variableName = $request->input('variableName');
        $value = $request->input('value');
        session([$variableName => $value]);
        return response()->json(['success' => true]);
    }
    public function store_otp(Request $request)
    {
       
    // Retrieve the user by phone number
    $user = User::where('phone', $request->phone_num)->first();

    if ($user) {
    
        Auth::login($user);
         return response()->json(['redirect_url' => route('checkout.shipping_info') ]);
    } else {
      
        // Phone number does not exist, create a new user and set email_verified_at to now
        $user = User::create([
            'phone' => $request->phone_num,
            'email_verified_at' => now()
        ]);

        // Log in the newly created user
        Auth::login($user);
         return response()->json(['redirect_url' => route('checkout.shipping_info') ]);
    }

  
    }

    public function forgot_store_otp(Request $request)
    {
        $phoneNum = $request->input('phone_num');

        // For resend, if phone number is not provided in the request, retrieve it from the session
        if ($phoneNum === null) {
            $phoneNum = $request->session()->get('phone_num');
        }


        // Store phone number in session
        $request->session()->put('phone_num', $phoneNum);

        // Return a view or response indicating the phone number is stored
        return response()->json(['phone_num_stored' => true, 'phone_num' => $phoneNum]);
    }
    public function check_pass($userId)
    {

        $user = User::where('id', $userId)->first();
        return view("auth.check_pass", compact('user'));
    }

    public function match_pass(Request $request)
    {

        // Retrieve user_id from the request
        $userId = $request->user_id;

        // Retrieve the user based on the user_id
        $user = User::find($userId);

        // Check if user exists
        if (!$user) {

            return response()->json(['error' => 'User not found'], 404);
        }

        // Retrieve the password from the request
        $password = $request->input('password');

        // Retrieve the stored password from the user model
        $storedPassword = $user->password;

        // Check if the provided password matches the stored password
        $isMatched = Hash::check($password, $storedPassword);

        if ($isMatched) {
            auth()->login($user);
            flash(translate('Login successfull!'))->success();
            return redirect()->route('home'); // Redirect to home route upon successful password match
        } else {
            flash(translate('Invalid  password!'))->warning();
            return back();
        }
    }
    // public function match_pass_cart(Request $request)
    // {


    //     $userId = $request->user_id;


    //     $user = User::where('id', $userId)->first();



    //     $password = $request->input('password');


    //     $storedPassword = $user->password;

    //     $isMatched = Hash::check($password, $storedPassword);
    //     if ($isMatched) {
    //         auth()->login($user);
    //         flash(translate('Login Successfull!'))->success();
    //         return redirect()->to('/checkout');
    //     } else {
    //         flash(translate('Invalid  password!'))->warning();
    //         return back();
    //     }
    // }
    public function loginWithOtp(Request $request)
    {

        $otp = $request->input('otp');
        $phoneNum = $request->session()->get('phone_num');
        $sessionOtp = $request->session()->get('otp');
        // Check if OTP has expired
        if ($request->session()->has('otp_expires_at') && Carbon::now()->greaterThanOrEqualTo($request->session()->get('otp_expires_at'))) {
            $request->session()->forget('otp');
            $request->session()->forget('otp_expires_at');
            $sessionOtp = ''; // Set OTP to empty
        }


        if ($otp == $sessionOtp) {
            $user = User::where('phone', $phoneNum)->first();

            if (!$user) {
                $user = User::create([
                    // 'phone' => '+'.$request['country_code'].$phoneNum,
                    'phone' => $phoneNum,
                    'email_verified_at' => now(),
                    'verification_code' => $sessionOtp
                ]);
            }
            auth()->login($user);

            return response()->json(['user' => $user]);
        } else {
            return response()->json(['error' => 'Invalid OTP'], 422);
        }
    }
    public function loginWithOtpRegis(Request $request)
    {

        $otp = $request->input('otp');
        $phoneNum = $request->session()->get('phone_num');
        $sessionOtp = $request->session()->get('otp');
        // Check if OTP has expired
        if ($request->session()->has('otp_expires_at') && Carbon::now()->greaterThanOrEqualTo($request->session()->get('otp_expires_at'))) {
            $request->session()->forget('otp');
            $request->session()->forget('otp_expires_at');
            $sessionOtp = ''; // Set OTP to empty
        }


        if ($otp == $sessionOtp) {
            $user = User::where('phone', $phoneNum)->first();

            if (!$user) {
                $user = User::create([
                    // 'phone' => '+'.$request['country_code'].$phoneNum,
                    'phone' => $phoneNum,
                    'email_verified_at' => now(),
                    'verification_code' => $sessionOtp
                ]);
            }



            // auth()->login($user);

            return response()->json(['user' => $user]);
        } else {
            return response()->json(['error' => 'Invalid OTP'], 422);
        }
    }

    public function register_page()
    {
        return view('frontend.pages.register_page');
    }

    public function user_login()
    {
        return view('auth.user-login');
    }
    public function check_otp()
    {
        return view('frontend.pages.check_otp');
    }
    public function forgot_user_login()
    {
        return view('auth.forgot-user-login');
    }
    public function forgot_check_otp()
    {
        return view('frontend.pages.forgot_check_otp');
    }
    public function confirm_password()
    {

        return view('frontend.pages.confirm_password');
    }

    public function update_password(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'password' => 'required|min:8|confirmed', // Ensure password matches password_confirmation
        ], [
            'password.confirmed' => translate('The password confirmation does not match.'),
        ]);

        // Retrieve phone number from session
        $phoneNumber = session('phone_num');

        // Find user by phone number
        $user = User::where('phone', $phoneNumber)->first();

        // Check if user exists
        if ($user) {
            // Update user's password
            $user->password = Hash::make($request->password); // Hash the password
            $user->save();

            // Password updated successfully
            // You can redirect the user or return a response indicating success
            auth()->login($user);
            flash(translate('Your password has been updated successfully!'))->success();
            return redirect()->route('home');
        } else {
            // User not found
            // Handle the case where the user is not found
            flash(translate('Password does not match'))->error();
            return redirect()->back();
        }
    }

    public function user_address(Request $request)
    {
        $address_id = $request->address_id;
        Session::put('addressData', $address_id);

        return response()->json(['success' => true]);
    }
    public function contact_us()
    {
        return view('frontend.pages.contact_us');
    }

    //    for sen email

}

<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Product;
use App\Models\Category;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use App\Utility\PayfastUtility;
use App\Utility\PayhereUtility;
use App\Utility\NotificationUtility;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaytmController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\InstamojoController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PublicSslCommerzPaymentController;

class CheckoutController extends Controller
{

    public function __construct()
    {
        //
    }

    //check the selected payment gateway and redirect to that controller accordingly
    public function checkout(Request $request)
    {
        // dd($request);

        if ($request->address_id) {
            $address = Address::findOrFail($request->address_id);

            $address->name       = $request->name;
            $address->email       = $request->email;
            $address->address       = $request->address;
            $address->city_id       = $request->city_id;
            $address->zone_id      = $request->zone_id;
            $address->area_id    = $request->area_id;
            $address->longitude     = $request->longitude;
            $address->latitude      = $request->latitude;
            $address->postal_code   = $request->postal_code;
            $address->phone         = $request->phone;

            $address->save();
        } else {
            $address = new Address;
            if ($request->has('customer_id')) {
                $address->user_id   = $request->customer_id;
            } else {
                $address->user_id   = Auth::user()->id;
            }
            $address->name       = $request->name;
            $address->email       = $request->email;
            $address->address       = $request->address;
            $address->city_id       = $request->city_id;
            $address->zone_id      = $request->zone_id;
            $address->area_id    = $request->area_id;
            $address->longitude     = $request->longitude;
            $address->latitude      = $request->latitude;
            $address->postal_code   = $request->postal_code;
            $address->phone         = $request->phone;
            $address->save();
        }


        if ($request->payment_method) {
            (new OrderController)->store($request);

            $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));

            foreach ($combined_order->orders as $order) {
                $order->payment_method = $request->payment_method;
                $order->bkash_number = $request->bkash_number;
                $order->bkash_transaction_id = $request->bkash_transaction_id;
                $order->nagad_number = $request->nagad_number;
                $order->nagad_transaction_id = $request->nagad_transaction_id;
                $order->rocket_number = $request->rocket_number;
                $order->rocket_transaction_id = $request->rocket_transaction_id;
                $order->payment_type = $request->payment_method;
                $order->save();
            }


            flash(translate("Your order has been placed successfully"))->success();
            return redirect()->route('order_confirmed');
        }


        if ($request->payment_option != null && $request->payment_method === null) {
            (new OrderController)->store($request);

            $request->session()->put('payment_type', 'cart_payment');

            $data['combined_order_id'] = $request->session()->get('combined_order_id');
            $request->session()->put('payment_data', $data);

            if ($request->session()->get('combined_order_id') != null) {
                if ($request->payment_option == 'paypal') {
                    $paypal = new PaypalController;
                    return $paypal->getCheckout();
                } elseif ($request->payment_option == 'stripe') {
                    $stripe = new StripePaymentController;
                    return $stripe->stripe();
                } elseif ($request->payment_option == 'sslcommerz') {
                    $sslcommerz = new PublicSslCommerzPaymentController;
                    return $sslcommerz->index($request);
                } elseif ($request->payment_option == 'instamojo') {
                    $instamojo = new InstamojoController;
                    return $instamojo->pay($request);
                } elseif ($request->payment_option == 'razorpay') {
                    $razorpay = new RazorpayController;
                    return $razorpay->payWithRazorpay($request);
                } elseif ($request->payment_option == 'payku') {
                    return (new PaykuController)->create($request);
                } elseif ($request->payment_option == 'voguepay') {
                    $voguePay = new VoguePayController;
                    return $voguePay->customer_showForm();
                } elseif ($request->payment_option == 'ngenius') {
                    $ngenius = new NgeniusController();
                    return $ngenius->pay();
                } elseif ($request->payment_option == 'iyzico') {
                    $iyzico = new IyzicoController();
                    return $iyzico->pay();
                } elseif ($request->payment_option == 'nagad') {
                    $nagad = new NagadController;
                    return $nagad->getSession();
                } elseif ($request->payment_option == 'bkash') {
                    $bkash = new BkashController;
                    return $bkash->pay();
                } elseif ($request->payment_option == 'aamarpay') {
                    $aamarpay = new AamarpayController;
                    return $aamarpay->index();
                } elseif ($request->payment_option == 'flutterwave') {
                    $flutterwave = new FlutterwaveController();
                    return $flutterwave->pay();
                } elseif ($request->payment_option == 'mpesa') {
                    $mpesa = new MpesaController();
                    return $mpesa->pay();
                } elseif ($request->payment_option == 'paystack') {
                    if (addon_is_activated('otp_system') && !Auth::user()->email) {
                        flash(translate('Your email should be verified before order'))->warning();
                        return redirect()->route('cart')->send();
                    }
                    $paystack = new PaystackController;
                    return $paystack->redirectToGateway($request);
                } elseif ($request->payment_option == 'payhere') {
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));


                    $combined_order_id = $combined_order->id;
                    $amount = $combined_order->grand_total;
                    $first_name = json_decode($combined_order->shipping_address)->name;
                    $last_name = 'X';
                    $phone = json_decode($combined_order->shipping_address)->phone;
                    $email = json_decode($combined_order->shipping_address)->email;
                    $address = json_decode($combined_order->shipping_address)->address;
                    $city = json_decode($combined_order->shipping_address)->city;

                    return PayhereUtility::create_checkout_form($combined_order_id, $amount, $first_name, $last_name, $phone, $email, $address, $city);
                } elseif ($request->payment_option == 'payfast') {
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));

                    $combined_order_id = $combined_order->id;
                    $amount = $combined_order->grand_total;

                    return PayfastUtility::create_checkout_form($combined_order_id, $amount);
                } elseif ($request->payment_option == 'paytm') {
                    if (Auth::user()->phone == null) {
                        flash('Please add phone number to your profile')->warning();
                        return redirect()->route('profile');
                    }

                    $paytm = new PaytmController;
                    return $paytm->index();
                } else if ($request->payment_option == 'authorizenet') {
                    $authorize_net = new AuthorizeNetController();
                    return $authorize_net->pay();
                } elseif ($request->payment_option == 'cash_on_delivery') {
                    flash(translate("Your order has been placed successfully"))->success();
                    return redirect()->route('order_confirmed');
                } elseif ($request->payment_option == 'wallet') {
                    $user = Auth::user();
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                    if ($user->balance >= $combined_order->grand_total) {
                        $user->balance -= $combined_order->grand_total;
                        $user->save();
                        return $this->checkout_done($request->session()->get('combined_order_id'), null);
                    }
                } else {
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                    foreach ($combined_order->orders as $order) {
                        $order->manual_payment = 1;
                        $order->save();
                    }
                    flash(translate('Your order has been placed successfully. Please submit payment information from purchase history'))->success();
                    return redirect()->to('/order-confirmed');
                }
            }
        } else {
            flash(translate('Select Payment Option.'))->warning();
            return back();
        }
    }

    //redirects to this method after a successfull checkout
    public function checkout_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::findOrFail($combined_order_id);

        foreach ($combined_order->orders as $key => $order) {
            $order = Order::findOrFail($order->id);
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();

            calculateCommissionAffilationClubPoint($order);
        }

        Session::put('combined_order_id', $combined_order_id);
        return redirect()->route('order_confirmed');
    }

    public function get_shipping_info(Request $request)
    {
        // $carts = Cart::where('user_id', Auth::user()->id)->get();
        // if ($carts && count($carts) > 0) {
        //     $categories = Category::all();
        //     return view('frontend.shipping_info', compact('categories', 'carts'));
        // }

        $categories = Category::all();
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            if ($request->session()->get('temp_user_id')) {
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                    ->update(
                        [
                            'user_id' => $user_id,
                            'temp_user_id' => null
                        ]
                    );

                Session::forget('temp_user_id');
            }
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            // $carts = Cart::where('temp_user_id', $temp_user_id)->get();
            $carts = ($temp_user_id != null) ? Cart::where('temp_user_id', $temp_user_id)->get() : [];
        }
        // order info



        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();



        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();


        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;
        if ($carts && count($carts) > 0) {
            foreach ($carts as $key => $cartItem) {
                $product = Product::find($cartItem['product_id']);
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $subtotal += $cartItem['price'] * $cartItem['quantity'];

                if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                    $cartItem['shipping_type'] = 'pickup_point';
                    $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                } else {
                    $cartItem['shipping_type'] = 'home_delivery';
                }
                $cartItem['shipping_cost'] = 0;
                if ($cartItem['shipping_type'] == 'home_delivery') {
                    $cartItem['shipping_cost'] = getShippingCost($carts, $key);
                }

                if (isset($cartItem['shipping_cost']) && is_array(json_decode($cartItem['shipping_cost'], true))) {

                    foreach (json_decode($cartItem['shipping_cost'], true) as $shipping_region => $val) {
                        if ($shipping_info['city'] == $shipping_region) {
                            $cartItem['shipping_cost'] = (float)($val);
                            break;
                        } else {
                            $cartItem['shipping_cost'] = 0;
                        }
                    }
                } else {
                    if (
                        !$cartItem['shipping_cost'] ||
                        $cartItem['shipping_cost'] == null ||
                        $cartItem['shipping_cost'] == 'null'
                    ) {

                        $cartItem['shipping_cost'] = 0;
                    }
                }

                $shipping += $cartItem['shipping_cost'];
                $cartItem->save();
            }
            $total = $subtotal + $tax + $shipping;


            return view('frontend.shipping_info', compact('carts', 'shipping_info', 'total'));
        }
        flash(translate('Your cart is empty'))->success();
        return back();
    }

    public function store_shipping_info(Request $request)
    {
        if ($request->address_id == null) {
            flash(translate("Please add shipping address"))->warning();
            return back();
        }

        $carts = Cart::where('user_id', Auth::user()->id)->get();

        foreach ($carts as $key => $cartItem) {
            $cartItem->address_id = $request->address_id;
            $cartItem->save();
        }
        return view('frontend.payment_select', compact('total'));
    }

    public function store_delivery_info(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();

        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;

        if ($carts && count($carts) > 0) {
            foreach ($carts as $key => $cartItem) {
                $product = Product::find($cartItem['product_id']);
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $subtotal += $cartItem['price'] * $cartItem['quantity'];

                if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                    $cartItem['shipping_type'] = 'pickup_point';
                    $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                } else {
                    $cartItem['shipping_type'] = 'home_delivery';
                }
                $cartItem['shipping_cost'] = 0;
                if ($cartItem['shipping_type'] == 'home_delivery') {
                    $cartItem['shipping_cost'] = getShippingCost($carts, $key);
                }

                if (isset($cartItem['shipping_cost']) && is_array(json_decode($cartItem['shipping_cost'], true))) {

                    foreach (json_decode($cartItem['shipping_cost'], true) as $shipping_region => $val) {
                        if ($shipping_info['city'] == $shipping_region) {
                            $cartItem['shipping_cost'] = (float)($val);
                            break;
                        } else {
                            $cartItem['shipping_cost'] = 0;
                        }
                    }
                } else {
                    if (
                        !$cartItem['shipping_cost'] ||
                        $cartItem['shipping_cost'] == null ||
                        $cartItem['shipping_cost'] == 'null'
                    ) {

                        $cartItem['shipping_cost'] = 0;
                    }
                }

                $shipping += $cartItem['shipping_cost'];
                $cartItem->save();
            }
            $total = $subtotal + $tax + $shipping;
            return view('frontend.payment_select', compact('carts', 'shipping_info', 'total'));
        } else {
            flash(translate('Your Cart was empty'))->warning();
            return redirect()->to('/');
        }
    }

    public function apply_coupon_code(Request $request)
    {
        $coupon = Coupon::where('code', $request->data)->first();
        $response_message = array();
        $coupon_discount = 0;

        if ($coupon != null) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);

                    $carts = Cart::where('user_id', Auth::user()->id)
                        ->where('owner_id', $coupon->user_id)
                        ->get();

                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $key => $cartItem) {
                            $subtotal += $cartItem['price'] * $cartItem['quantity'];
                            $tax += $cartItem['tax'] * $cartItem['quantity'];
                            $shipping += $cartItem['shipping_cost'];
                        }
                        $sum = $subtotal + $tax + $shipping;

                        if ($sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                        }
                    } elseif ($coupon->type == 'product_base') {
                        $coupon_discount = 0;
                        foreach ($carts as $key => $cartItem) {
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['product_id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += ($cartItem['price'] * $coupon->discount / 100) * $cartItem['quantity'];
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                    }
                                }
                            }
                        }
                    }

                    $imran = Cart::where('user_id', Auth::user()->id)
                        ->where('owner_id', $coupon->user_id)
                        ->update([
                            'discount' => (count($carts) > 0) ? ($coupon_discount / count($carts)) : 0,
                            'coupon_code' => $request->data,
                            'coupon_applied' => 1
                        ]);

                    $response_message['response'] = 'success';
                    $response_message['message'] = translate('Coupon has been applied');
                    Session::put('coupon', $coupon->discount);
                } else {
                    $response_message['response'] = 'warning';
                    $response_message['message'] = translate('You already used this coupon!');
                }
            } else {
                $response_message['response'] = 'warning';
                $response_message['message'] = translate('Coupon expired!');
            }
        } else {
            $response_message['response'] = 'danger';
            $response_message['message'] = translate('Invalid coupon!');
        }

        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        

        $returnHTML = view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'))->render();
        return response()->json(array('response_message' => $response_message, 'html' => $returnHTML));
    }

    public function remove_coupon_code(Request $request)
    {
        Cart::where('user_id', Auth::user()->id)
            ->update(
                [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0
                ]
            );

        $coupon = Coupon::where('code', $request->code)->first();
        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        return view('frontend.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'));
    }

    public function apply_club_point(Request $request)
    {
        if (addon_is_activated('club_point')) {

            $point = $request->point;

            if (Auth::user()->point_balance >= $point) {
                $request->session()->put('club_point', $point);
                flash(translate('Point has been redeemed'))->success();
            } else {
                flash(translate('Invalid point!'))->warning();
            }
        }
        return back();
    }

    public function remove_club_point(Request $request)
    {
        $request->session()->forget('club_point');
        return back();
    }

    public function order_confirmed()
    {
        $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));

        Cart::where('user_id', $combined_order->user_id)
            ->delete();

        //Session::forget('club_point');
        //Session::forget('combined_order_id');
        Session::forget('coupon');
        // Session::forget('delivery_charge');

        foreach ($combined_order->orders as $order) {
            NotificationUtility::sendOrderPlacedNotification($order);
        }

        return view('frontend.order_confirmed', compact('combined_order'));
    }
    public function advance_payment(Request $request)
    {
        if ($request->payment_option != null) {

            if ($request->payment_option == 'sslcommerz') {
                $sslcommerz = new PublicSslCommerzPaymentController;

                return $sslcommerz->advance_payment($request);
            }
        } else {
            flash(translate('Select Payment Option.'))->warning();
            return back();
        }
    }
}

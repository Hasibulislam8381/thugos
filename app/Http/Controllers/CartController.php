<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Auth;
use Session;
use Cookie;

class CartController extends Controller
{
    public function index(Request $request)
    {
        //dd($cart->all());
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

        return view('frontend.view_cart', compact('categories', 'carts'));
    }

    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.partials.addToCart', compact('product'));
    }
    public function showNotifyModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.partials.show_notify', compact('product'));
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

   public function addToCart(Request $request)
{
    
    $product = Product::find($request->id);
    $carts = array();
    $data = array();

    if (auth()->check()) {
        $user_id = auth()->id();
        $data['user_id'] = $user_id;
        $carts = Cart::where('user_id', $user_id)->get();
    } else {
        $temp_user_id = $request->session()->get('temp_user_id') ?? bin2hex(random_bytes(10));
        $request->session()->put('temp_user_id', $temp_user_id);
        $data['temp_user_id'] = $temp_user_id;
        $carts = Cart::where('temp_user_id', $temp_user_id)->get();
    }

    $data['product_id'] = $product->id;
    $data['owner_id'] = $product->user_id;
    $data['type'] = $request->type ?? null;

    $str = '';
    $tax = 0;

    if ($product->auction_product == 0) {
        if ($product->digital != 1 && $request->quantity < $product->min_qty) {
            return [
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.partials.minQtyNotSqatisfied', ['min_qty' => $product->min_qty])->render(),
                'nav_cart_view' => view('frontend.partials.cart')->render(),
            ];
        }

        if ($request->has('color')) {
            $str = $request['color'];
        }

        if ($product->digital != 1 && $product->choice_options) {
            foreach (json_decode($product->choice_options) as $choice) {
                $value = str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                $str .= ($str ? '-' : '') . $value;
            }
        }

        $data['variation'] = $str;

        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;

        if ($product->wholesale_product) {
            $wholesalePrice = $product_stock->wholesalePrices
                ->where('min_qty', '<=', $request->quantity)
                ->where('max_qty', '>=', $request->quantity)
                ->first();
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }

        if ($product_stock->qty < $request->quantity) {
            return [
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.partials.outOfStockCart')->render(),
                'nav_cart_view' => view('frontend.partials.cart')->render(),
            ];
        }

        // Discounts
        $discount_applicable = !$product->discount_start_date ||
            (strtotime(now()) >= $product->discount_start_date && strtotime(now()) <= $product->discount_end_date);

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        // Taxes
        foreach ($product->taxes as $product_tax) {
            $tax += ($product_tax->tax_type == 'percent')
                ? ($price * $product_tax->tax) / 100
                : $product_tax->tax;
        }

        $data['quantity'] = $request->quantity ?? 1;
        $data['price'] = $price;
        $data['tax'] = $tax;
        $data['shipping_cost'] = 0;
        $data['product_referral_code'] = Cookie::get('referred_product_id') == $product->id ? Cookie::get('product_referral_code') : null;
        $data['cash_on_delivery'] = $product->cash_on_delivery;
        $data['digital'] = $product->digital;

        $foundInCart = false;

        foreach ($carts as $cartItem) {
            $cartProduct = Product::find($cartItem['product_id']);
            if ($cartProduct->auction_product) {
                return [
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.partials.auctionProductAlredayAddedCart')->render(),
                    'nav_cart_view' => view('frontend.partials.cart')->render(),
                ];
            }

            if (
                $cartItem['product_id'] == $request->id &&
                $cartItem['variation'] == $str &&
                $cartItem['type'] == $request->type
            ) {
                if ($product_stock->qty < $cartItem['quantity'] + $request->quantity) {
                    return [
                        'status' => 0,
                        'cart_count' => count($carts),
                        'modal_view' => view('frontend.partials.outOfStockCart')->render(),
                        'nav_cart_view' => view('frontend.partials.cart')->render(),
                    ];
                }

                $cartItem['quantity'] += $request->quantity;

                if ($product->wholesale_product) {
                    $wholesalePrice = $product_stock->wholesalePrices
                        ->where('min_qty', '<=', $cartItem['quantity'])
                        ->where('max_qty', '>=', $cartItem['quantity'])
                        ->first();
                    if ($wholesalePrice) {
                        $cartItem['price'] = $wholesalePrice->price;
                    }
                }

                $cartItem->save();
                $foundInCart = true;
                break;
            }
        }

        if (!$foundInCart) {
            Cart::create($data);
        }

        $carts = auth()->check()
            ? Cart::where('user_id', auth()->id())->get()
            : Cart::where('temp_user_id', $request->session()->get('temp_user_id'))->get();

        return [
            'status' => 1,
            'cart_count' => count($carts),
            'modal_view' => view('frontend.partials.addedToCart', compact('product', 'data'))->render(),
            'nav_cart_view' => view('frontend.partials.cart')->render(),
        ];
    }

    // For auction products
    $price = $product->bids->max('amount');

    foreach ($product->taxes as $product_tax) {
        $tax += ($product_tax->tax_type == 'percent')
            ? ($price * $product_tax->tax) / 100
            : $product_tax->tax;
    }

    $data['quantity'] = 1;
    $data['price'] = $price;
    $data['tax'] = $tax;
    $data['shipping_cost'] = 0;
    $data['product_referral_code'] = null;
    $data['cash_on_delivery'] = $product->cash_on_delivery;
    $data['digital'] = $product->digital;
    $data['type'] = $request->type ?? null;

    if ($carts->isEmpty()) {
        Cart::create($data);
    }

    $carts = auth()->check()
        ? Cart::where('user_id', auth()->id())->get()
        : Cart::where('temp_user_id', $request->session()->get('temp_user_id'))->get();

    return [
        'status' => 1,
        'cart_count' => count($carts),
        'modal_view' => view('frontend.partials.addedToCart', compact('product', 'data'))->render(),
        'nav_cart_view' => view('frontend.partials.cart')->render(),
    ];
}

    public function addtocart_temp(Request $request)
{
    $product = Product::find($request->product_id);
    $carts = array();
    $data = array();

    if (auth()->user() != null) {
        $user_id = Auth::user()->id;
        $data['user_id'] = $user_id;
        $carts = Cart::where('user_id', $user_id)->get();
    } else {
        if ($request->session()->get('temp_user_id')) {
            $temp_user_id = $request->session()->get('temp_user_id');
        } else {
            $temp_user_id = bin2hex(random_bytes(10));
            $request->session()->put('temp_user_id', $temp_user_id);
        }
        $data['temp_user_id'] = $temp_user_id;
        $carts = Cart::where('temp_user_id', $temp_user_id)->get();
    }

    $data['product_id'] = $product->id;
    $data['name'] = $product->name;
    $data['thumbnail'] = $product->thumbnail_img;
    $data['owner_id'] = $product->user_id;
    $data['type'] = $request->type ?? null;  // Add type here

    $str = '';
    $tax = 0;
    if ($product->auction_product == 0) {
        if ($product->digital != 1 && $request->quantity < $product->min_qty) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.partials.minQtyNotSatisfied', ['min_qty' => $product->min_qty])->render(),
                'nav_cart_view' => view('frontend.partials.cart')->render(),
            );
        }

        if ($request->has('color')) {
            $str = $request['color'];
        }

        if ($product->digital != 1) {
            // If you want to add choice options string, uncomment below
            // foreach (json_decode($product->choice_options) as $key => $choice) {
            //     $str .= ($str ? '-' : '') . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
            // }
        }

        $data['variation'] = $str;

        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;

        if ($product->wholesale_product) {
            $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }

        $quantity = $product_stock->qty;

        if ($quantity < $request['quantity']) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.partials.outOfStockCart')->render(),
                'nav_cart_view' => view('frontend.partials.cart')->render(),
            );
        }

        // Discount calculation here...

        // Tax calculation here...

        $data['quantity'] = $request['quantity'] ?? 1;
        $data['price'] = $price;
        $data['tax'] = $tax;
        $data['shipping_cost'] = 0;
        $data['product_referral_code'] = null;
        $data['cash_on_delivery'] = $product->cash_on_delivery;
        $data['digital'] = $product->digital;

        $foundInCart = false;

        foreach ($carts as $key => $cartItem) {
            $productItem = Product::find($cartItem['product_id']);
            if ($productItem->auction_product == 1) {
                return array(
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.partials.auctionProductAlredayAddedCart')->render(),
                    'nav_cart_view' => view('frontend.partials.cart')->render(),
                );
            }

            // **Add type check here as well**
            if (
                $cartItem['product_id'] == $request->product_id &&
                $cartItem['variation'] == $str &&
                $cartItem['type'] == $request->type
            ) {
                $product_stock = $productItem->stocks->where('variant', $str)->first();
                $quantity = $product_stock->qty;

                if ($quantity < ($cartItem['quantity'] + $request['quantity'])) {
                    return array(
                        'status' => 0,
                        'cart_count' => count($carts),
                        'modal_view' => view('frontend.partials.outOfStockCart')->render(),
                        'nav_cart_view' => view('frontend.partials.cart')->render(),
                    );
                }

                $foundInCart = true;
                $cartItem['quantity'] += $request['quantity'];

                if ($product->wholesale_product) {
                    $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $cartItem['quantity'])->where('max_qty', '>=', $cartItem['quantity'])->first();
                    if ($wholesalePrice) {
                        $cartItem['price'] = $wholesalePrice->price;
                    }
                }

                $cartItem->save();
                break;
            }
        }

        if (!$foundInCart) {
            Cart::create($data);
        }

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'status' => 1,
            'cart_count' => count($carts),
            'modal_view' => view('frontend.partials.addedToCart', compact('product', 'data'))->render(),
            'nav_cart_view' => view('frontend.partials.cart')->render(),
        );
    } else {
        // Auction product handling here...

        // similar changes to add type if needed
    }
}


    //removes from Cart
    public function removeFromCart(Request $request)
    {
        Cart::destroy($request->id);
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.partials.cart')->render(),
        );
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::findOrFail($request->id);

        if ($cartItem['id'] == $request->id) {
            $product = Product::find($cartItem['product_id']);
            $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
            $quantity = $product_stock->qty;
            $price = $product_stock->price;

            if ($quantity >= $request->quantity) {
                if ($request->quantity >= $product->min_qty) {
                    $cartItem['quantity'] = $request->quantity;
                }
            }

            if ($product->wholesale_product) {
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                if ($wholesalePrice) {
                    $price = $wholesalePrice->price;
                }
            }

            $cartItem->save();
        }

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.partials.cart')->render(),
        );
    }
}
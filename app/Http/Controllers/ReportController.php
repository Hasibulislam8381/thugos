<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\Search;
use App\Models\Seller;
use App\Models\Wallet;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\CommissionHistory;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function stock_report(Request $request)
    {
        $sort_by =null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')){
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.stock_report', compact('products','sort_by'));
    }

    public function in_house_sale_report(Request $request)
    {
        $orders = Order::withCount('orderDetails');

        $sort_search = null;
        $delivery_status = null;

        // $orders = Order::orderBy('id', 'desc');
        // if ($request->has('search')) {
        if ($request->search != null) {
            $sort_search = $request->search;
            // Assuming 'code' is another column you want to search
            // $orders = $orders->where('code', 'like', '%' . $sort_search . '%')
            //     ->orWhereJsonContains('shipping_address', ['name' => $sort_search])
            //     ->orWhereJsonContains('shipping_address', ['phone' => $sort_search]);
            $orders = $orders->where('code',  $sort_search)
                ->orWhereJsonContains('shipping_address', ['name' => $sort_search])
                ->orWhereJsonContains('shipping_address', ['phone' => $sort_search]);
        }

        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }

        $date = $request->date;
        if (request()->ajax()) {
            $orders = Order::withCount('orderDetails')->orderBy('id','desc');
            $date = $request->date;
            $sort_search = $request->search;
            if ($date != null) {

                $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
            }

            if ($sort_search != null) {
                $orders->where('code',  $sort_search)
                    ->orWhereJsonContains('shipping_address', ['name' => $sort_search])
                    ->orWhereJsonContains('shipping_address', ['phone' => $sort_search]);
            }
            $sum = 0;
            return DataTables::of($orders)

                ->addColumn('grand_total_formatted', function ($order) {
                    return single_price(floatval($order->grand_total));
                })



                ->addColumn('amount_formatted', function ($order) {
                    if ($order->advance_payment == 'paid') {
                        return single_price(floatval($order->grand_total) - floatval(get_setting('advance_payment')));
                    } elseif ($order->payment_status != 'paid') {
                        return single_price(floatval($order->grand_total));
                    } else {
                        return '0';
                    }
                })
                ->addColumn('name_formatted', function ($order) {
                    $shipping_address = json_decode($order->shipping_address, true);
                    return  $shipping_address['name'] ?? null;
                })
                ->addColumn('phone_formatted', function ($order) {
                    $shipping_address = json_decode($order->shipping_address, true);
                    return  $shipping_address['phone'] ?? null;
                })
                ->addColumn('address_formatted', function ($order) {
                    $shipping_address = json_decode($order->shipping_address, true);
                    $city_name = getCityName($shipping_address['city_id']);
                    $zone_name = getZoneName($shipping_address['zone_id'],$shipping_address['city_id']);
                    $area_name = getAreaName($shipping_address['area_id'],$shipping_address['zone_id']);
                    
                    $customer_address = "$city_name, $zone_name, $area_name";

                    return  $customer_address ?? null;
                })
                ->addColumn('order_details_count', function ($order) {
                    return $order->order_details_count;
                })
                ->editColumn('footer_total', function ($order) {
                    return '<span class="footer_grand_total" data-orig-value="' . single_price(floatval($order->grand_total)) . '">' . single_price(floatval($order->grand_total)) . '</span>';
                })


                ->make(true);
        }
        return view('backend.reports.in_house_sale_report', compact('orders', 'sort_search', 'delivery_status', 'date'));
        // return view('backend.reports.in_house_sale_report', compact('products','sort_by'));
    }

    public function seller_sale_report(Request $request)
    {
        $sort_by =null;
        $sellers = Seller::orderBy('created_at', 'desc');
        if ($request->has('verification_status')){
            $sort_by = $request->verification_status;
            $sellers = $sellers->where('verification_status', $sort_by);
        }
        $sellers = $sellers->paginate(10);
        return view('backend.reports.seller_sale_report', compact('sellers','sort_by'));
    }

    public function wish_report(Request $request)
    {
        $sort_by =null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')){
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(10);
        return view('backend.reports.wish_report', compact('products','sort_by'));
    }

    public function user_search_report(Request $request){
        $searches = Search::orderBy('count', 'desc')->paginate(10);
        return view('backend.reports.user_search_report', compact('searches'));
    }
    
    public function commission_history(Request $request) {
        $seller_id = null;
        $date_range = null;
        
        if(Auth::user()->user_type == 'seller') {
            $seller_id = Auth::user()->id;
        } if($request->seller_id) {
            $seller_id = $request->seller_id;
        }
        
        $commission_history = CommissionHistory::orderBy('created_at', 'desc');
        
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $commission_history = $commission_history->where('created_at', '>=', $date_range1[0]);
            $commission_history = $commission_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($seller_id){
            
            $commission_history = $commission_history->where('seller_id', '=', $seller_id);
        }
        
        $commission_history = $commission_history->paginate(10);
        if(Auth::user()->user_type == 'seller') {
            return view('frontend.user.seller.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
        }
        return view('backend.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
    }
    
    public function wallet_transaction_history(Request $request) {
        $user_id = null;
        $date_range = null;
        
        if($request->user_id) {
            $user_id = $request->user_id;
        }
        
        $users_with_wallet = User::whereIn('id', function($query) {
            $query->select('user_id')->from(with(new Wallet)->getTable());
        })->get();

        $wallet_history = Wallet::orderBy('created_at', 'desc');
        
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $wallet_history = $wallet_history->where('created_at', '>=', $date_range1[0]);
            $wallet_history = $wallet_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($user_id){
            $wallet_history = $wallet_history->where('user_id', '=', $user_id);
        }
        
        $wallets = $wallet_history->paginate(10);

        return view('backend.reports.wallet_history_report', compact('wallets', 'users_with_wallet', 'user_id', 'date_range'));
    }
}

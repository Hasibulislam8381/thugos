<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Search;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\FlashDeal;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Utility\CategoryUtility;
use App\Models\AttributeCategory;

class SearchController extends Controller
{
    public function index(Request $request, $category_id = null, $brand_id = null)

    {
        // dd($brand_id);
        $query = $request->keyword;
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;
        $attributes = Attribute::all();
        $selected_attribute_values = array();
        $colors = Color::all();
        $selected_color = null;
        $brand_info = null;
        $category_id_get = null;
        $category_id_himel = [];

        $conditions = ['published' => 1];

        if ($brand_id != null) {

            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }


        if ($seller_id != null) {
            $conditions = array_merge($conditions, ['user_id' => Seller::findOrFail($seller_id)->user->id]);
        }


        $products = Product::where($conditions);



        if ($category_id != null) {
            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;

            $products->whereIn('category_id', $category_ids);


            $attribute_ids = AttributeCategory::whereIn('category_id', $category_ids)->pluck('attribute_id')->toArray();
            $attributes = Attribute::whereIn('id', $attribute_ids)->get();
        } else {
        }
        if ($request->category) {
            if ($request->category === "all_cat") {

                return redirect()->route('search');
            } else {
                $category_id_get = (Category::where('slug', $request->category)->first() != null) ? Category::where('slug', $request->category)->first()->id : null;


                $category_id_himel = CategoryUtility::children_ids($category_id_get);

                $category_id_himel[] = $category_id_get;

                //$products->whereIn('category_id', $category_id_himel);

                $products = Product::whereIn('category_id', $category_id_himel);
                // dd($products->get());
            }
        }
        if ($request->brand) {

            $brand_info = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;

            if ($category_id_himel != []) {
                $products = Product::where('brand_id', $brand_info)->whereIn('category_id', $category_id_himel);
            } else {
                $products = Product::where('brand_id', $brand_info);
            }
        }

        if ($min_price != null && $max_price != null) {
            $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }

        if ($query != null) {
            $searchController = new SearchController;
            $searchController->store($request);

            $products->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')->orWhere('tags', 'like', '%' . $word . '%')->orWhereHas('product_translations', function ($q) use ($word) {
                        $q->where('name', 'like', '%' . $word . '%');
                    });
                }
            });
        }

        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products->orderBy('unit_price', 'desc');
                break;
            default:
                $products->orderBy('id', 'desc');
                break;
        }

        if ($request->has('color')) {
            $str = '"' . $request->color . '"';
            $products->where('colors', 'like', '%' . $str . '%');
            $selected_color = $request->color;
        }

        if ($request->has('selected_attribute_values')) {
            $selected_attribute_values = $request->selected_attribute_values;
            foreach ($selected_attribute_values as $key => $value) {
                $str = '"' . $value . '"';
                $products->where('choice_options', 'like', '%' . $str . '%');
            }
        }
        if ($request->stock) {

            if ($request->stock === 'in_stock') {

                $product_stock_ids = ProductStock::where('qty', '>', 0)
                    ->pluck('product_id')
                    ->toArray();
                // dd($category_id_himel === []);
                if ($brand_info != null) {
                    $products = Product::whereIn('id', $product_stock_ids)->where('brand_id', $brand_info);
                }
                if ($category_id_himel != []) {
                    $products = Product::whereIn('id', $product_stock_ids)->where('category_id', $category_id_himel);
                }
                if ($brand_info != null && $category_id_himel != []) {
                    $products = Product::whereIn('id', $product_stock_ids)->where('brand_id', $brand_info)->where('category_id', $category_id_himel);
                } else {
                    $products = Product::whereIn('id', $product_stock_ids);
                }
            } else {
                $product_stock_ids = ProductStock::where('qty', '=', 0)
                    ->pluck('product_id')
                    ->toArray();
                if ($brand_info != null) {
                    $products = Product::whereIn('id', $product_stock_ids)->where('brand_id', $brand_info);
                }
                if ($category_id_himel != []) {
                    $products = Product::whereIn('id', $product_stock_ids)->where('category_id', $category_id_himel);
                }
                if ($brand_info != null && $category_id_himel != []) {
                    $products = Product::whereIn('id', $product_stock_ids)->where('brand_id', $brand_info)->where('category_id', $category_id_himel);
                } else {
                    $products = Product::whereIn('id', $product_stock_ids);
                }
            }
        }

        $products = filter_products($products)->with('taxes')->paginate(12)->appends(request()->query());
        // dd($products);

        return view('frontend.product_listing', compact('products', 'query', 'category_id', 'category_id_get', 'brand_id', 'brand_info', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attribute_values', 'colors', 'selected_color'));
    }

    public function listing(Request $request)
    {
        return $this->index($request);
    }

    public function listingByCategory(Request $request, $category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
            return $this->index($request, $category->id);
        }
        abort(404);
    }

    public function listingByBrand(Request $request, $brand_slug)
    {

        $brand = Brand::where('slug', $brand_slug)->first();
        if ($brand != null) {
            return $this->index($request, null, $brand->id);
        }
        abort(404);
    }

    //Suggestional Search
    public function ajax_search(Request $request)
    {
        $keywords = array();
        $query = $request->search;
        $products = Product::where('published', 1)->where('tags', 'like', '%' . $query . '%')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',', $product->tags) as $key => $tag) {
                if (stripos($tag, $query) !== false) {
                    if (sizeof($keywords) > 5) {
                        break;
                    } else {
                        if (!in_array(strtolower($tag), $keywords)) {
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        $products = filter_products(Product::query());

        $products = $products->where('published', 1)
            ->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')->orWhere('tags', 'like', '%' . $word . '%')->orWhereHas('product_translations', function ($q) use ($word) {
                        $q->where('name', 'like', '%' . $word . '%');
                    });
                }
            })
            ->get();

        $categories = Category::where('name', 'like', '%' . $query . '%')->get()->take(3);

        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name', 'like', '%' . $query . '%')->get()->take(3);

        if (sizeof($keywords) > 0 || sizeof($categories) > 0 || sizeof($products) > 0 || sizeof($shops) > 0) {
            return view('frontend.partials.search_content', compact('products', 'categories', 'keywords', 'shops'));
        }
        return '0';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $search = Search::where('query', $request->keyword)->first();
        if ($search != null) {
            $search->count = $search->count + 1;
            $search->save();
        } else {
            $search = new Search;
            $search->query = $request->keyword;
            $search->save();
        }
    }
}

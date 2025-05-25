<?php

namespace App\Models;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        $product = new Product();
        if(isset($_GET['categories'])){
            $categories = $_GET['categories'];
            if($categories!=""){
               $product = $product->where('category_id',$categories);
            }
        }
        if(isset($_GET['seller_id'])){
            $seller_id = $_GET['seller_id'];
            if($seller_id!=""){
                $product = $product->where('user_id',$seller_id);
            }
        }
        
        if(isset($_GET['start_date'])){
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
            if($start_date!=""){
                $product = $product->whereBetween('created_at', [$start_date, $end_date]);
            }
        }
        
        
        return $product->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'added_by',
            'user_id',
            'category_id',
            'brand_id',
            'video_provider',
            'video_link',
            'description',
            'unit_price',
            'discount',
            'purchase_price',
            'unit',
            'current_stock',
            'product_image',
            'slug',
            'meta_title',
            'meta_description',
        ];
    }

    /**
    * @var Product $product
    */
    public function map($product): array
    {
        $qty = 0;
        foreach ($product->stocks as $key => $stock) {
            $qty += $stock->qty;
        }
        return [
            $product->id,
            $product->name,
            $product->added_by,
            $product->user_id,
            $product->category_id,
            $product->brand_id,
            $product->video_provider,
            $product->video_link,
            $product->description,
            $product->unit_price,
            $product->discount,
            $product->purchase_price,
            $product->unit,
           // $product->current_stock,
            $qty,
            uploaded_asset($product->thumbnail_img),
            $product->slug,
        ];
    }
}

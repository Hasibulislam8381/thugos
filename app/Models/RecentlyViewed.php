<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecentlyViewed extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

     /**
     * Define a custom relationship to retrieve products that are recently viewed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function viewedProduct()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}

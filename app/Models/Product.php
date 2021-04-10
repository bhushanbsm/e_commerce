<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'products';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'category_id',
        'sub_category_id',
        'name',
        'cost',
        'discount',
        'color_id',
        'status',
    ];

     protected $casts = [
        'status' => 'int',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class)->where('parent_id',null)->where('status',1);
    }

    public function sub_category()
    {
        return $this->belongsTo(\App\Models\Category::class,'sub_category_id')->where('parent_id', "!=" , null)->where('status',1);
    }

    public function product_color()
    {
        return $this->belongsTo(\App\Models\ProductColor::class,'color_id')->where('status',1);
    }

    public function product_images()
    {
        return $this->hasMany(\App\Models\ProductImage::class);
    }

    public function product_image()
    {
        return $this->hasOne(\App\Models\ProductImage::class);
    }
}

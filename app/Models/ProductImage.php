<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'product_images';
    public $timestamps = true;

    protected $fillable = [
        'path',
        'product_id',
        'status',
    ];

     protected $casts = [
        'status' => 'int',
        'product_id' => 'int',
    ];
}

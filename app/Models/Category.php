<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    use NodeTrait;

    protected $table = 'categories';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'slug',
        'parent_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
    ];

     protected $casts = [
        'parent_id' => 'int',
        'status' => 'int',
    ];
}

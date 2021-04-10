<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resume extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'resumes';
    public $timestamps = true;

    protected $fillable = [
        'country_id',
        'state_id',
        'city_id',
        'first_name',
        'last_name',
        'about',
        'username',
        'path',
        'email',
        'address_line',
        'zipcode',
    ];

     protected $casts = [
        'id' => 'int',
    ];

}

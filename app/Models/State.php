<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'states';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'country_id',
    ];

     protected $casts = [
        'id' => 'int',
        'country_id' => 'int',
    ];

    public function cities()
    {
        return $this->hasMany(\App\Models\City::class);
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class);
    }
}

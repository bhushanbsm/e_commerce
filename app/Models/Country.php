<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'countries';
    public $timestamps = true;

    protected $fillable = [
        'name'
    ];

     protected $casts = [
        'id' => 'int',
    ];

    public function states()
    {
        return $this->hasMany(\App\Models\State::class);
    }
}

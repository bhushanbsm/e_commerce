<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cities';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'state_id',
    ];

     protected $casts = [
        'id' => 'int',
        'state_id' => 'int',
    ];

    public function states()
    {
        return $this->belongsTo(\App\Models\State::class);
    }
}

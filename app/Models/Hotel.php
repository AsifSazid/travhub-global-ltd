<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($city) {
            if (empty($city->uuid)) {
                $city->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function City()
    {
        return $this->belongsTo(City::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

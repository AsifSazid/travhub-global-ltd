<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    // Created By Relation
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Updated By Relation
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}

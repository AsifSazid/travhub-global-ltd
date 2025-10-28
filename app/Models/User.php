<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // -------------------
    // Activity Category Relations
    // -------------------
    public function createdActCategories()
    {
        return $this->hasMany(ActivityCategory::class, 'created_by', 'id');
    }

    public function updatedActCategories()
    {
        return $this->hasMany(ActivityCategory::class, 'updated_by', 'id');
    }

    // -------------------
    // Countries Relations
    // -------------------
    public function createdCountries()
    {
        return $this->hasMany(Country::class, 'created_by', 'id');
    }

    public function updatedCountries()
    {
        return $this->hasMany(Country::class, 'updated_by', 'id');
    }

    // -------------------
    // Cities Relations
    // -------------------
    public function createdCities()
    {
        return $this->hasMany(City::class, 'created_by', 'id');
    }

    public function updatedCities()
    {
        return $this->hasMany(City::class, 'updated_by', 'id');
    }

    // -------------------
    // Hotels Relations
    // -------------------
    public function createdHotels()
    {
        return $this->hasMany(Hotel::class, 'created_by', 'id');
    }

    public function updatedHotels()
    {
        return $this->hasMany(Hotel::class, 'updated_by', 'id');
    }

    // -------------------
    // Current Rates Relations
    // -------------------
    public function createdCurrentRates()
    {
        return $this->hasMany(CurrentRate::class, 'created_by', 'id');
    }

    public function updatedCurrentRates()
    {
        return $this->hasMany(CurrentRate::class, 'updated_by', 'id');
    }

    // -------------------
    // Currencies Relations
    // -------------------
    public function createdCurrencies()
    {
        return $this->hasMany(Currency::class, 'created_by', 'id');
    }

    public function updatedCurrencies()
    {
        return $this->hasMany(Currency::class, 'updated_by', 'id');
    }

    // -------------------
    // Navigations Relations
    // -------------------
    public function createdNavigations()
    {
        return $this->hasMany(Navigation::class, 'created_by', 'id');
    }

    public function updatedNavigations()
    {
        return $this->hasMany(Navigation::class, 'updated_by', 'id');
    }

    // -------------------
    // Inclusions Relations
    // -------------------
    public function createdActivities()
    {
        return $this->hasMany(Activity::class, 'created_by', 'id');
    }

    public function updatedActivities()
    {
        return $this->hasMany(Activity::class, 'updated_by', 'id');
    }

    // -------------------
    // Inclusions Relations
    // -------------------
    public function createdInclusions()
    {
        return $this->hasMany(Inclusion::class, 'created_by', 'id');
    }

    public function updatedInclusions()
    {
        return $this->hasMany(Inclusion::class, 'updated_by', 'id');
    }
}

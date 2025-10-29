<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'logo',
        'website',
        'email',
        'phone',
        'address',
        'postcode',
        'latitude',
        'longitude',
        'is_active',
        'booking_system_on',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'booking_system_on' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function enquiryMessages(): HasMany
    {
        return $this->hasMany(EnquiryMessage::class);
    }

    /**
     * Check if this company uses online booking system
     */
    public function usesOnlineBooking(): bool
    {
        return $this->booking_system_on === true;
    }

    /**
     * Get the users who manage this company
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get only the owners of this company
     */
    public function owners()
    {
        return $this->users()->wherePivot('role', 'owner');
    }

    /**
     * Get owners and managers of this company
     */
    public function managers()
    {
        return $this->users()->whereIn('company_user.role', ['owner', 'manager']);
    }
}

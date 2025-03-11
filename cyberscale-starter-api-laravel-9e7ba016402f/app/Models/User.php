<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'admin' or 'attendee'
        'profile_image',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'creator_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function bookedEvents()
    {
        return $this->belongsToMany(Event::class, 'bookings')
            ->withPivot('ticket_id', 'quantity', 'total_price', 'qr_code', 'status')
            ->withTimestamps();
    }
}

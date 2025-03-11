<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'address',
        'capacity',
        'status', 
        'category', 
        'creator_id',
        'featured_image',
        'is_featured',
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_featured' => 'boolean',
        'category' => 'string', 
    ];
     /**
     * Get the creator of the event.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the tickets for the event.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
 /**
     * Get the bookings for the event.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the attendees for the event.
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'bookings')
            ->withPivot('ticket_id', 'quantity', 'total_price', 'qr_code', 'status')
            ->withTimestamps();
    }

}

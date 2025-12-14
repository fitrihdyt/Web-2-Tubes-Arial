<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'price',
        'capacity',
        'stock',
        'description',
        'thumbnail',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}

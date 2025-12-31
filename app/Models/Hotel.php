<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Room;
use App\Models\Facility;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'city',
        'star',
        'latitude',
        'longitude',
        'thumbnail',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function facilities()
    {
         return $this->belongsToMany(Facility::class)
            ->withPivot('custom_name')
            ->withTimestamps();
    
    }
}

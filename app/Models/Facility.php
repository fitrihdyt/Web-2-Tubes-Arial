<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facility extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name'];

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class)
            ->withPivot('custom_name');
    }
}

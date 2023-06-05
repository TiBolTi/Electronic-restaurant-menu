<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topping extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function food()
    {
        return $this->belongsToMany(Food::class, 'food_toppings')->withPivot('quantity');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}

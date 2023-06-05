<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsToMany(Category::class, 'category_food');
    }

    public function topping()
    {
        return $this->belongsToMany(Topping::class, 'food_toppings')->withPivot('quantity');
    }

    public function order()
    {
        return $this->belongsToMany(Order::class, 'food_orders')->withPivot('quantity_complete', 'quantity', 'price', 'is_completed');;
    }

}

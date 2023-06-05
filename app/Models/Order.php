<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function food()
    {
        return $this->belongsToMany(Food::class, 'food_orders')->withPivot('quantity_complete', 'quantity', 'price', 'is_completed');;
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

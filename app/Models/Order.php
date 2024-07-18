<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function products()
{
    return $this->belongsToMany(Product::class, 'order_items')->withPivot('quantity');
}
public function user()
{
    return $this->belongsTo(User::class);
}
}

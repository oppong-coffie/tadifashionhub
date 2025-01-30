<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
    use HasFactory;
    protected $table='carts';
    protected $fillable=[
        'designer_id',
        'customer_id',
        'product_image',
        'product_price',
        'product_name',
        'quantity',
    ];
}

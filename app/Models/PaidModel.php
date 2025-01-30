<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidModel extends Model
{
    use HasFactory;
    protected $table='paid';
    protected $fillable=[
        'designer_id',
        'customer_id',
        'customer_name',
        'product_name',
        'product_image',
        'product_price',
    ];
}

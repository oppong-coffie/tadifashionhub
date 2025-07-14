<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class servedModel extends Model
{
    use HasFactory;
    protected $table ='served'; //table name

    protected $fillable=[
        'designer_id',
        'customer_id',
        'customer_name',
        'product_name',
        'product_image',
        'product_price',
    ];
}

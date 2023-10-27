<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Order extends Model
{
    use HasFactory, AsSource;
    protected $table = "order";
    protected $fillable = [
        'product_id',
        'presentation_id',
        'quantity',
        'subtotal',
        'status',
        'from',
        'to',
        'from_type',
        'to_type',
    ];
}

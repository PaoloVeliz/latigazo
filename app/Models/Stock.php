<?php

namespace App\Models;

use Orchid\Metrics\Chartable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Stock extends Model
{
    use HasFactory, AsSource, Chartable;
    protected $table = "stock";
    protected $fillable = [
        'product_id',
        'user_id',
        'presentation_id',
        'quantity',
        'limit',
    ];
}

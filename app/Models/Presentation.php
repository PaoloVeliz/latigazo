<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Presentation extends Model
{
    use HasFactory, AsSource;
    protected $table = "presentation";
    protected $fillable = [
        'name',
        'units',
    ];
}

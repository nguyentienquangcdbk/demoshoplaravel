<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productimg extends Model
{
    use HasFactory;
    protected $table = 'productimgs';
    protected $fillable = ['name', 'path', 'productId'];
}

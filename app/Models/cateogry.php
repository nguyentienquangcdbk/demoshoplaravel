<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cateogry extends Model
{
    use HasFactory;
    protected $table = 'cateogries';
    protected $fillable = ['name', 'slug'];
}

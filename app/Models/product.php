<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'avatarOne', 'avatarTwo', 'price', 'categoryName'];

    public function productimg()
    {
        return $this->hasMany(productimg::class, 'productId');
    }

    public function propertyproduct()
    {
        return $this->hasMany(propertiesproduct::class, 'productId');
    }
}

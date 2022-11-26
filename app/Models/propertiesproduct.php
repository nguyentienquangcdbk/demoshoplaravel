<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class propertiesproduct extends Model
{
    use HasFactory;

    protected $fillable = ['productId', 'key', 'value'];
    public function products()
    {
        return $this->belongsTo(product::class, 'productId');
    }
}

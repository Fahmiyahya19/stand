<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $guarded = [];
    protected $attributes = [
        'deleted' => 0
    ];
    use HasFactory;

    public function scopeActive($query){
        $query->where('deleted', 0);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function category(){
        $this->belongsTo(Categories::class);
    }

    public function images(){
        $this->hasMany(Image::class);
    }
}

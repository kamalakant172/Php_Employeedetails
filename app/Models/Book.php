<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    public function ratings(){
        return $this->hasMany(Rating::class);
        
        // return $this->hasOne(Rating::class);
    }
}

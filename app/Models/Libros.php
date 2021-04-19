<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libros extends Model
{
    use HasFactory;
    protected $fillable = [
        'isbn',
        'title',
        'cover_large'
    ];


    public function autores(){
        return $this->hasMany(Autores::class,'book_id');
    }


}

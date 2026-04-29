<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zakaznik extends Model
{
    use HasFactory;

    protected $table = 'zakaznici'; // Názov tabuľky v DB

    protected $fillable =
    [
        'meno', 'ico', 'dic', 'mesto', 'ulica', 'psc', 'typ', 'siet',
        'kontakt_meno', 'telefon', 'email', 'poznamka', 
        'default_discount_base', 'default_discount_obj'
    ];
}
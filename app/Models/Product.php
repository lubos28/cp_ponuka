<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Toto povolí Laravelu zapísať dáta do týchto stĺpcov
    protected $fillable = [
        'id_vyrobok', 
        'nazov', 
        'Rozmer', 
        'rozmer_balenie', 
        'merj', 
        'balenie_typ', 
        'Popis1', 
        'popis2_skrateny', 
        'kratky_popis', 
        'technicke_info', 
        'cele_balenie', 
        'mn_cele_balenie', 
        'balenie_ks_karton', 
        'hmotnost_objem', 
        'nazov_strany', 
        'cislo_strany', 
        'cena_mj', 
        'cena_mj_ks'
    ];
}
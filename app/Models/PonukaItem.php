<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PonukaItem extends Model
{
    // Povieme Laravelu, že tabuľka sa volá 'offer_items'
    protected $table = 'offer_items';

    protected $fillable = [
        'offer_id', 
        'product_name', 
        'product_data', 
        'quantity', 
        'price_mj', 
        'z_zaklad', 
        'z_objekt', 
        'row_total'
    ];

    // Prevod JSONu na pole
    protected $casts = [
        'product_data' => 'array',
    ];

    public function ponuka()
    {
        return $this->belongsTo(Ponuka::class, 'offer_id');
    }
}
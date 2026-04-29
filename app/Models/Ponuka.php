<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ponuka extends Model
{
    // Povieme Laravelu, že tabuľka sa volá 'offers'
    protected $table = 'offers';

    protected $fillable = [
        'customer_name', 
        'title',
        'customer_data', 
        'discount_base', 
        'discount_vol', 
        'total_sum', 
        'status'
    ];

    // Prevod JSONu na pole
    protected $casts = [
        'customer_data' => 'array',
    ];

    // Vzťah k položkám
    public function polozky()
    {
        return $this->hasMany(PonukaItem::class, 'offer_id');
    }
}
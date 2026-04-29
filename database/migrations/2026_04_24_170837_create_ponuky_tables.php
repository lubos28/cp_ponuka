<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabuľka hlavičiek ponúk
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name'); 
            // Tu uložíme VŠETKO z tabuľky 'zakaznici' (Snapshot)
            $table->json('customer_data')->nullable(); 
            $table->decimal('discount_base', 5, 2)->default(0);
            $table->decimal('discount_vol', 5, 2)->default(0);
            $table->decimal('total_sum', 15, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // Tabuľka riadkov ponúk
        Schema::create('offer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained('offers')->onDelete('cascade');
            $table->string('product_name');
            // Tu uložíme VŠETKO z tabuľky 'products' (Snapshot)
            $table->json('product_data')->nullable(); 
            $table->decimal('quantity', 15, 2);
            $table->decimal('price_mj', 15, 2);
            $table->decimal('z_zaklad', 5, 2);
            $table->decimal('z_objekt', 5, 2);
            $table->decimal('row_total', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offer_items');
        Schema::dropIfExists('offers');
    }
};
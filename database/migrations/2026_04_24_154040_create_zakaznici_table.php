<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zakaznici', function (Blueprint $table) {
            $table->id();
            
            // Základné údaje
            $table->string('meno');                    // Názov firmy alebo meno osoby
            $table->string('ico', 20)->nullable();     // IČO
            $table->string('dic', 20)->nullable();     // DIČ
            $table->string('ic_dph', 30)->nullable();  // IČ DPH
            
            // Adresa a kontakt
            $table->string('ulica')->nullable();
            $table->string('mesto')->nullable();
            $table->string('psc', 10)->nullable();
            $table->string('email')->nullable();
            $table->string('telefon')->nullable();
            
            // Predvolené nastavenia pre cenové ponuky
            $table->decimal('default_discount_base', 5, 2)->default(0); // Predvolená základná zľava (%)
            $table->decimal('default_discount_obj', 5, 2)->default(0);  // Predvolená objemová zľava (%)
            
            // Poznámka ku klientovi
            $table->text('poznamka')->nullable();
            
            $table->timestamps(); // vytvorí created_at a updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakaznici');
    }
};
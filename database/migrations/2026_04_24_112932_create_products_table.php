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
    // Ak tabuľka náhodou už existuje, tak ju tento skript nespustí a nespôsobí chybu
    if (!Schema::hasTable('products')) {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('id_vyrobok')->nullable();
            $table->string('nazov')->index();
            $table->string('Rozmer')->nullable();
            $table->string('rozmer_balenie')->nullable();
            $table->string('merj')->nullable();
            $table->string('balenie_typ')->nullable();
            $table->text('Popis1')->nullable();
            $table->text('popis2_skrateny')->nullable();
            $table->text('kratky_popis')->nullable();
            $table->text('technicke_info')->nullable();
            $table->string('cele_balenie')->nullable();
            $table->string('mn_cele_balenie')->nullable();
            $table->string('balenie_ks_karton')->nullable();
            $table->string('hmotnost_objem')->nullable();
            $table->string('nazov_strany')->nullable();
            $table->integer('cislo_strany')->nullable();
            $table->decimal('cena_mj', 12, 4)->default(0);
            $table->decimal('cena_mj_ks', 12, 4)->default(0);
            $table->timestamps();
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

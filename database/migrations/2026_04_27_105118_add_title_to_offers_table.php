<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Spustenie migrácie (pridanie stĺpca)
     */
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            // Pridáme stĺpec 'title' (názov ponuky), ktorý môže byť prázdny (nullable)
            // Umiestnime ho za stĺpec 'customer_name'
            $table->string('title')->after('customer_name')->nullable();
        });
    }

    /**
     * Vrátenie zmien (ak by sme migráciu vracali späť)
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('zakaznici', function (Blueprint $table) {
        $table->string('typ')->nullable()->after('mesto');
        $table->string('siet')->nullable()->after('typ');
        $table->string('kontakt_meno')->nullable()->after('email');
        // Ak už stĺpce existujú, stačí im nastaviť default(0)
        $table->decimal('default_discount_base', 5, 2)->default(0)->change();
        $table->decimal('default_discount_obj', 5, 2)->default(0)->change();
        });     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zakaznici', function (Blueprint $table) {
            //
        });
    }
};

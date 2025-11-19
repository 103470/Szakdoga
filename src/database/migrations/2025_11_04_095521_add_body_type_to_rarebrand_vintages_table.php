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
        Schema::table('rarebrand_vintages', function (Blueprint $table) {
            $table->enum('body_type', [
                'Sedan',
                'Coupé',
                'Kombi',
                'Kabrió',
                'Ferdehátú',
            ])->default('Sedan')->change()->after('frame');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rarebrand_vintages', function (Blueprint $table) {
            $table->string('body_type')->nullable()->change();
        });
    }
};

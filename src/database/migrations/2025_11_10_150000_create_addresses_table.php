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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('country', 100);
            $table->string('zip', 20);
            $table->string('city', 100);
            $table->string('street_name', 100);
            $table->string('street_type', 100)->nullable();
            $table->string('house_number', 20);
            $table->string('building', 20)->nullable();
            $table->string('floor', 20)->nullable();
            $table->string('door', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};

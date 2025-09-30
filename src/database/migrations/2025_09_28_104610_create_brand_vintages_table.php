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
        Schema::create('brand_vintages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('brand_types')->onDelete('cascade');

            $table->string('name');  
            $table->string('slug')->unique();
            $table->string('frame')->nullable();

            $table->unsignedSmallInteger('year_from');
            $table->unsignedTinyInteger('month_from')->default(1);
            $table->unsignedSmallInteger('year_to');
            $table->unsignedTinyInteger('month_to')->default(12);

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
        Schema::dropIfExists('brand_vintages');
    }
};

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
        Schema::create('brand_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('brand_types')->onDelete('cascade');
            $table->unsignedBigInteger('unique_code')->unique()->nullable();

            $table->string('name');
            $table->string('slug')->unique()->nullable();          
            $table->string('ccm')->nullable();
            $table->string('kw_hp')->nullable();  
            $table->string('engine_type')->nullable();
            $table->foreignId('fuel_type_id')->nullable()->constrained('fuel_types')->onDelete('set null');

            $table->unsignedSmallInteger('year_from');
            $table->unsignedTinyInteger('month_from')->default(1);
            $table->unsignedSmallInteger('year_to')->nullable();
            $table->unsignedTinyInteger('month_to')->nullable()->default(12);
            $table->string('frame')->nullable();
            $table->foreign('frame')->references('frame')->on('brand_vintages')->onDelete('set null');

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
        Schema::dropIfExists('brand_models');
    }
};

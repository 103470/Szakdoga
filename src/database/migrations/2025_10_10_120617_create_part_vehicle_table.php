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
        Schema::create('part_vehicle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('oem_number_id');
            $table->unsignedBigInteger('unique_code'); 
            $table->enum('model_source', ['brand', 'rarebrand'])->default('brand');
            $table->timestamps();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('oem_number_id')
                ->references('id')
                ->on('oem_numbers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_vehicle');
    }
};

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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->unsignedBigInteger('product_category_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('article_number')->nullable(); 
            $table->string('manufacturer')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 10)->default('HUF');
            $table->integer('stock')->default(0);
            $table->string('image')->nullable(); 
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('subcategory_id')
                ->references('subcategory_id')
                ->on('subcategories')
                ->onDelete('set null');

            $table->foreign('product_category_id')
                ->references('product_category_id')
                ->on('product_categories')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

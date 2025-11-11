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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('shipping_name');
            $table->string('shipping_email');
            $table->string('shipping_phone_prefix', 10)->nullable();
            $table->string('shipping_phone');
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_zip');
            $table->string('shipping_country');
            $table->string('billing_name');
            $table->string('billing_email');
            $table->string('billing_phone_prefix', 10)->nullable();
            $table->string('billing_phone');
            $table->string('billing_address');
            $table->string('billing_city');
            $table->string('billing_zip');
            $table->string('billing_country');
            $table->string('delivery_option');
            $table->string('payment_option');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

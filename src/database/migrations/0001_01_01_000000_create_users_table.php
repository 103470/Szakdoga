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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Alap adatok
            $table->string('lastname');
            $table->string('firstname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Admin jelölő
            $table->boolean('is_admin')->default(false);

            // Fiókadattípus
            $table->string('account_type');

            // Telefonszám
            $table->string('phone_country_code');
            $table->string('phone_number');

            // Billing cím
            $table->string('billing_country');
            $table->string('billing_zip');
            $table->string('billing_city');
            $table->string('billing_street_name');
            $table->string('billing_street_type');
            $table->string('billing_house_number');
            $table->string('billing_building')->nullable();
            $table->string('billing_floor')->nullable();
            $table->string('billing_door')->nullable();

            // Shipping cím
            $table->string('shipping_country');
            $table->string('shipping_zip');
            $table->string('shipping_city');
            $table->string('shipping_street_name');
            $table->string('shipping_street_type');
            $table->string('shipping_house_number');
            $table->string('shipping_building')->nullable();
            $table->string('shipping_floor')->nullable();
            $table->string('shipping_door')->nullable();

            // Social auth mezők
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('provider_token')->nullable();
            $table->string('avatar')->nullable();

            // Laravel extra mezők
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
        });
    }
};

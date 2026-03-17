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
    Schema::table('stores', function (Blueprint $table) {

        $table->string('store_code')->unique();

        $table->string('alt_phone')->nullable();
        $table->string('alt_email')->nullable();

        $table->string('address_line1')->nullable();
        $table->string('address_line2')->nullable();
        $table->string('city')->nullable();
        $table->string('state')->nullable();
        $table->string('pincode')->nullable();
        $table->string('landmark')->nullable();

        $table->string('contact_name')->unique()->nullable();
        $table->string('contact_phone')->unique()->nullable();
        $table->string('contact_email')->unique()->nullable();
        $table->string('contact_whatsapp')->nullable();
        $table->text('contact_address')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            //
        });
    }
};

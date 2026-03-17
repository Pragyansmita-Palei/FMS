<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_associates', function (Blueprint $table) {
            $table->id();
            $table->string('sales_id')->unique();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('alternate_phone')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('pin');
            $table->string('landmark')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_associates');
    }
};

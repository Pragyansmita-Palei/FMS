<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_associates', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
            $table->string('address_line1')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('pin')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sales_associates', function (Blueprint $table) {
            $table->string('phone')->nullable(false)->change();
            $table->string('address_line1')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('state')->nullable(false)->change();
            $table->string('pin')->nullable(false)->change();
        });
    }
};
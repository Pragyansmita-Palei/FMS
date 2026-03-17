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
    Schema::table('received_payments', function (Blueprint $table) {
        $table->string('transaction_number')->nullable()->after('payment_mode');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('received_payments', function (Blueprint $table) {
            //
        });
    }
};

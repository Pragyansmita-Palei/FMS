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
    Schema::table('quotation_items', function (Blueprint $table) {
        $table->decimal('length', 10, 2)->nullable()->after('product_name');
        $table->decimal('breadth', 10, 2)->nullable()->after('length');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            //
        });
    }
};

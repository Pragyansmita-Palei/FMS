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
Schema::table('products', function (Blueprint $table) {
$table->string('item_code')->nullable();
$table->decimal('discount',8,2)->default(0);
$table->decimal('total_price',10,2)->default(0);
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};

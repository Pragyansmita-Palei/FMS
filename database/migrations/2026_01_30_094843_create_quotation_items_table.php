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
       Schema::create('quotation_items', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('project_id')->nullable();

    $table->string('area_name');
    $table->string('reference_name');

    $table->string('product_name');

    $table->decimal('width',8,2)->nullable();
    $table->decimal('height',8,2)->nullable();
    $table->string('unit')->nullable();

    $table->integer('qty');
    $table->decimal('rate',10,2);
    $table->decimal('discount',5,2);
    $table->decimal('sale_rate',10,2);
    $table->decimal('total',12,2);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};

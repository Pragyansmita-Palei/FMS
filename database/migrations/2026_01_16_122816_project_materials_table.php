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
     Schema::create('project_materials', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('project_id');
    $table->string('product_group');
    $table->string('company');
    $table->string('catalogue');
    $table->string('design_no')->nullable();
    $table->decimal('mrp', 10, 2)->nullable();
    $table->decimal('discount', 5, 2)->nullable();
    $table->decimal('sale_rate', 10, 2)->nullable();
    $table->timestamps();

    $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

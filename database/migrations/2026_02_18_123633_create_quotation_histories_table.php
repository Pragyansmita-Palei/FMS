<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_quotation_histories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quotation_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('quotation_item_id')->nullable();
            $table->integer('version')->default(1);
            $table->string('area_name');
            $table->string('reference_name')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('breadth', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->string('unit')->default('CM');
            $table->decimal('qty', 10, 2)->default(1);
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('discount', 5, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('sale_rate', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['project_id', 'version']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotation_histories');
    }
};
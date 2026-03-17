<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_measurements', function (Blueprint $table) {

            $table->id();

            // Project reference
            $table->unsignedBigInteger('project_id');

            // Measurement details
            $table->string('product_group')->nullable();
            $table->string('reference');                 // REQUIRED
            $table->string('measurement');               // REQUIRED (unit)
            $table->decimal('width', 10, 2);             // REQUIRED
            $table->decimal('height', 10, 2);            // REQUIRED
            $table->integer('quantity')->default(1);

            $table->timestamps();

            // Foreign key
            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_measurements');
    }
};

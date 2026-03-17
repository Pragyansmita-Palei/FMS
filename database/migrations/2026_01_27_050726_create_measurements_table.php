<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();

            // Relation to areas table
            $table->unsignedBigInteger('area_id');

            // Measurement fields
            $table->string('reference')->nullable();
            $table->enum('unit', ['CM', 'INCH', 'FT'])->default('CM');

            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();

            $table->integer('qty')->default(1);
            $table->string('remark')->nullable();

            $table->timestamps();

            // Foreign key
            $table->foreign('area_id')
                  ->references('id')
                  ->on('areas')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};

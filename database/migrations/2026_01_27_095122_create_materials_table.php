<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();

            // Area / Room info
            $table->string('area')->nullable();          // e.g. All, Masterbed Room
            $table->string('item')->nullable();          // e.g. Window, Door

            // Material details
            $table->string('product')->nullable();
            $table->string('brand')->nullable();
            $table->string('catalogue')->nullable();
            $table->string('design_no')->nullable();

            // Pricing
            $table->decimal('mrp', 10, 2)->default(0);

            // Quantity / size (optional but useful)
            $table->integer('quantity')->default(1);
            $table->string('size')->nullable(); // e.g. 123cm x 12cm

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};

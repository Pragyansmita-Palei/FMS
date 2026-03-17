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
    Schema::create('quotation_versions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->constrained()->cascadeOnDelete();
        $table->unsignedInteger('version'); // 1, 2, 3...
        $table->decimal('total_amount', 10, 2)->default(0);
        $table->enum('status', ['pending', 'rejected', 'approved'])->default('pending');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::dropIfExists('quotation_versions');
}

};

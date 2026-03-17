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
       Schema::create('projects', function (Blueprint $table) {

    $table->id();

    $table->foreignId('customer_id')->constrained()->cascadeOnDelete();

    $table->string('project_code')->unique(); // FMS-P-1
    $table->string('project_name');

    $table->text('address')->nullable();

    $table->foreignId('sales_associate_id')->nullable();
    $table->foreignId('tailor_id')->nullable();

    $table->string('status')->default('draft');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

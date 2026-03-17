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
        Schema::create('received_payments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('project_id');
    $table->decimal('amount',10,2);
    $table->string('payment_mode');
    $table->date('payment_date');
    $table->text('remarks')->nullable();
    $table->unsignedBigInteger('created_by');
    $table->timestamps();

    $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('received_payments');
    }
};

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
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();

        $table->unsignedBigInteger('project_id')->nullable();
        $table->unsignedBigInteger('tailor_id')->nullable();
        $table->unsignedBigInteger('sales_associate_id')->nullable();

        $table->date('due_date')->nullable();
        $table->string('priority')->default('Low');
        $table->string('status')->default('To Do');

        $table->timestamps();

        $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
        $table->foreign('tailor_id')->references('id')->on('tailors')->onDelete('set null');
        $table->foreign('sales_associate_id')->references('id')->on('sales_associates')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

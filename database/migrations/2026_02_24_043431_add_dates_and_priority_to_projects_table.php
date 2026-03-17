<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->date('project_start_date')
              ->nullable()
              ->after('project_deadline');

        $table->date('estimated_end_date')
              ->nullable()
              ->after('project_start_date');

        $table->string('priority', 20)
              ->nullable()
              ->after('estimated_end_date');
    });
}

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'project_start_date',
                'estimated_end_date',
                'priority',
            ]);
        });
    }
};
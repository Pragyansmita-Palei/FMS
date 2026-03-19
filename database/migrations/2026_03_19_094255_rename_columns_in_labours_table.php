<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('labours', function (Blueprint $table) {
            $table->renameColumn('labour_name', 'name');
            $table->renameColumn('phone_number', 'phone');
        });
    }

    public function down(): void
    {
        Schema::table('labours', function (Blueprint $table) {
            $table->renameColumn('name', 'labour_name');
            $table->renameColumn('phone', 'phone_number');
        });
    }
};

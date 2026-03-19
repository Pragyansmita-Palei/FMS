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
    Schema::table('interiors', function (Blueprint $table) {
        $table->renameColumn('firm_name', 'name');
    });
}

public function down()
{
    Schema::table('interiors', function (Blueprint $table) {
        $table->renameColumn('name', 'firm_name');
    });
}
};

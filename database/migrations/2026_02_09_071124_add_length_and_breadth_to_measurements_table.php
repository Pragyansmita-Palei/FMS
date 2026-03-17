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
    Schema::table('measurements', function (Blueprint $table) {

        $table->decimal('length', 10, 2)
              ->nullable()
              ->after('unit');

        $table->decimal('breadth', 10, 2)
              ->nullable()
              ->after('length');
    });
}

public function down()
{
    Schema::table('measurements', function (Blueprint $table) {

        $table->dropColumn(['length', 'breadth']);
    });
}

};

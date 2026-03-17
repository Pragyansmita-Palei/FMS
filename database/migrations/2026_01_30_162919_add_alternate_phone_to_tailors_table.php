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
    Schema::table('tailors', function (Blueprint $table) {
        $table->string('alternate_phone', 20)->nullable()->after('phone');
    });
}

public function down()
{
    Schema::table('tailors', function (Blueprint $table) {
        $table->dropColumn('alternate_phone');
    });
}

};

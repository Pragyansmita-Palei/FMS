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
    Schema::table('project_materials', function (Blueprint $table) {
        $table->string('store')->nullable()->after('project_id');
        $table->string('item_name')->nullable()->after('store');
    });
}

    /**
     * Reverse the migrations.
     */

public function down()
{
    Schema::table('project_materials', function (Blueprint $table) {
        $table->dropColumn(['store', 'item_name']);
    });
}
};

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
    Schema::table('stores', function (Blueprint $table) {

        $table->string('branch_name')->after('email');
        $table->string('branch_code')->nullable()->after('branch_name');

        $table->string('branch_contact_name')->after('branch_code');
        $table->string('branch_contact_phone')->after('branch_contact_name');
        $table->string('branch_contact_email')->nullable()->after('branch_contact_phone');

    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            //
        });
    }
};

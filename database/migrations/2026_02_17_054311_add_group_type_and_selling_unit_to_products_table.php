<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->unsignedBigInteger('group_type_id')
                  ->nullable()
                  ->after('store_id');

            $table->unsignedBigInteger('selling_unit_id')
                  ->nullable()
                  ->after('group_type_id');

        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropColumn([
                'group_type_id',
                'selling_unit_id'
            ]);

        });
    }
};

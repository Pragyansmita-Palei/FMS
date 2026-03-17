<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE `products`
            ADD UNIQUE `products_unique_store_group_unit_name`
            (
                `store_id`,
                `group_type`(50),
                `selling_unit`(50),
                `name`(100)
            )
        ");
    }

    public function down()
    {
        Schema::table('products', function ($table) {
            $table->dropUnique('products_unique_store_group_unit_name');
        });
    }
};

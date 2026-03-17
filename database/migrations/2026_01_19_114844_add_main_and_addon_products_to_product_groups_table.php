<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('product_groups', function (Blueprint $table) {
            $table->string('main_product')->nullable()->after('name');
            $table->json('addon_products')->nullable()->after('main_product');
        });
    }

    public function down()
    {
        Schema::table('product_groups', function (Blueprint $table) {
            $table->dropColumn(['main_product', 'addon_products']);
        });
    }
};

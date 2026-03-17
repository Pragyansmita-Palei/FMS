<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {

            $table->unsignedBigInteger('product_id')->nullable()->after('measurement_id');
            $table->unsignedBigInteger('brand_id')->nullable()->after('product_id');
            $table->unsignedBigInteger('catalogue_id')->nullable()->after('brand_id');

            // Foreign Keys (Recommended)
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('catalogue_id')->references('id')->on('catalogues')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {

            $table->dropForeign(['product_id']);
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['catalogue_id']);

            $table->dropColumn([
                'product_id',
                'brand_id',
                'catalogue_id'
            ]);
        });
    }
};

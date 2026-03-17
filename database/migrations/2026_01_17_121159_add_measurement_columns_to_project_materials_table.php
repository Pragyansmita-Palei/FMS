<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('project_materials', function (Blueprint $table) {
            $table->string('reference')->nullable()->after('id');
            $table->string('measurement')->nullable()->after('reference');
            $table->decimal('width', 10, 2)->nullable()->after('measurement');
            $table->decimal('height', 10, 2)->nullable()->after('width');
            $table->integer('qty')->default(1)->after('height');
        });
    }

    public function down(): void
    {
        Schema::table('project_materials', function (Blueprint $table) {
            $table->dropColumn([
                'reference',
                'measurement',
                'width',
                'height',
                'qty'
            ]);
        });
    }
};

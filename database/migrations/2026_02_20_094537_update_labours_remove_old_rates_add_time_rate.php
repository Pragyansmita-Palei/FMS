<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('labours', function (Blueprint $table) {

            // remove old columns
            $table->dropColumn([
                'sq_feet_rate',
                'running_feet_rate',
                'per_piece_rate',
                'per_roll_rate',
            ]);

            // add new columns
            $table->enum('rate_type', ['day', 'hour'])->nullable();
            $table->decimal('price', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('labours', function (Blueprint $table) {

            $table->decimal('sq_feet_rate', 10, 2)->nullable();
            $table->decimal('running_feet_rate', 10, 2)->nullable();
            $table->decimal('per_piece_rate', 10, 2)->nullable();
            $table->decimal('per_roll_rate', 10, 2)->nullable();

            $table->dropColumn(['rate_type', 'price']);
        });
    }
};
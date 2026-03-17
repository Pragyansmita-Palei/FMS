// database/migrations/xxxx_xx_xx_create_labours_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('labours', function (Blueprint $table) {
            $table->id();

            $table->string('labour_name');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->text('address');

            $table->decimal('sq_feet_rate',10,2)->nullable();
            $table->decimal('running_feet_rate',10,2)->nullable();
            $table->decimal('per_piece_rate',10,2)->nullable();
            $table->decimal('per_roll_rate',10,2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labours');
    }
};
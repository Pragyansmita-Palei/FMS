<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTermsAndConditionsToQuotationsTable extends Migration
{
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->text('terms_and_conditions')->nullable()->after('grand_total');
            $table->decimal('sub_total', 12, 2)->default(0)->after('grand_total');
            $table->decimal('total_tax', 12, 2)->default(0)->after('sub_total');
            $table->decimal('total_discount', 12, 2)->default(0)->after('total_tax');
        });
    }

    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['terms_and_conditions', 'sub_total', 'total_tax', 'total_discount']);
        });
    }
}

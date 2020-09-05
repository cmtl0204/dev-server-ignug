<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-ignug')->create('school_periods', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('name', 500)->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->date('ordinary_start_date');
            $table->date('ordinary_end_date');
            $table->date('extraordinary_start_date');
            $table->date('extraordinary_end_date');
            $table->date('especial_start_date');
            $table->date('especial_end_date');
            $table->foreignId('state_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql-ignug')->dropIfExists('school_periods');
    }
}

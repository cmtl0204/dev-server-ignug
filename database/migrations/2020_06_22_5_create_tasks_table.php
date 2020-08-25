<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-attendance')->create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained();
            $table->string('description', 300)->nullable();
            $table->integer('percentage_advance')->default(0);
            $table->string('observations', 500)->nullable();
            $table->foreignId('type_id')->constrained('catalogues');
            $table->foreignId('state_id')->constrained('ignug.states');
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
        Schema::connection('pgsql-attendance')->dropIfExists('tasks');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-job-board')->create('locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_code_id')->nullable();
            $table->foreign('parent_code_id')->references('id')->on('catalogues');
            $table->string('code', 100);
            $table->string('name', 500);
            $table->string('type', 200);
            $table->string('principal_street', 200)->nullable();
            $table->string('secondary_street', 200)->nullable();
            $table->string('number', 100)->nullable();
            $table->string('post_code', 100)->nullable();
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
        Schema::connection('pgsql-job-board')->dropIfExists('locations');
    }

}

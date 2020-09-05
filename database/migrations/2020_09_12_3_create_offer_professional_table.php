<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferProfessionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-job-board')->create('offer_professional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->constrained();
            $table->foreignId('offer_id')->constrained();
            $table->foreignId('status_id')->nullable()->constrained('catalogues');
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
        Schema::connection('pgsql-job-board')->dropIfExists('offer_professional');
    }
}

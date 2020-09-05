<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql-web')->create('image_pages', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('name');
            $table->string('description');
            $table->foreignId('type_id')->constrained('catalogues');
            $table->string('order');
            $table->string('state');
            $table->foreignId('page_id')->constrained('pages');
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
        Schema::connection('pgsql-web')->dropIfExists('image_pages');
    }
}
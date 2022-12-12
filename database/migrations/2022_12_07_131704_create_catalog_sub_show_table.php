<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogSubShowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_sub_show', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('catalog_id');
            $table->unsignedInteger('catalog_sub_show_id');
            $table->foreign('catalog_id')
                ->references('id')->on('catalogs')
                ->onDelete('cascade');
            $table->foreign('catalog_sub_show_id')
                ->references('id')->on('catalog_sub_show');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_sub_show');
    }
}

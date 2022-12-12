<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductsKlassArmaturiColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('klass-armaturi', 255)->nullable()->after('klass-armirovaniya');
            $table->string('prutkibuhta', 255)->nullable()->after('klass-armaturi');
            $table->string('tip', 255)->nullable()->after('tip-profilya');
            $table->string('naznachenie', 255)->nullable()->after('condition');
            $table->string('factor_m2', 255)->nullable()->after('factor');
            $table->string('factor_m2_weight', 255)->nullable()->after('factor_m2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['klass-armaturi', 'prutkibuhta', 'tip', 'naznachenie', 'factor_m2', 'factor_m2_weight']);
        });
    }
}

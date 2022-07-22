<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->date('date');
            $table->unsignedInteger('group_id')->index();
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('type')->index();
            $table->unsignedTinyInteger('initial')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics');
    }
};

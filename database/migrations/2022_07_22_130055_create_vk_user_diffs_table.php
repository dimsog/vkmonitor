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
        Schema::create('vk_user_diffs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('subscribed');

            $table->index(['date', 'group_id']);
            $table->index(['date', 'group_id', 'user_id', 'subscribed']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vk_user_diffs');
    }
};

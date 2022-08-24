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
        Schema::create('vk_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('last_access')->default(0)->index();
            $table->unsignedInteger('client_id');
            $table->string('access_token');
            $table->unsignedTinyInteger('active')->default(1)->index();
            $table->string('error_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vk_tokens');
    }
};

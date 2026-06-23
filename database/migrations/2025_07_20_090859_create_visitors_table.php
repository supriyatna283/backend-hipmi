<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->string('device')->nullable();
            $table->string('referer')->nullable();
            $table->string('url');
            $table->string('session_id');
            $table->timestamp('visited_at');
            $table->timestamps();
            
            $table->index(['ip_address', 'visited_at']);
            $table->index('session_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitors');
    }
};

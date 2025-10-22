<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->string('status')->default('active'); // active, ended
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_sessions');
    }
};
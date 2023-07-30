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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('class_id')->index()->nullable();
            $table->string('language')->index();
            $table->boolean('complete')->default(true);
            $table->unsignedInteger('total_questions');
            $table->unsignedInteger('correct_answered');
            $table->json('questions_answered');
            $table->timestamp('start_time');
            $table->timestamp('stop_time');
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
        Schema::dropIfExists('results');
    }
};

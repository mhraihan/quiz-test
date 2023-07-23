<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('topic_id')->index()->nullable()->constrained()->onDelete('set null');
            $table->text('title');
            $table->longText('details');
            $table->binary('explain')->nullable();
            $table->longText('options');
            $table->text('title_two');
            $table->longText('details_two');
            $table->binary('explain_two')->nullable();
            $table->longText('options_two');
            $table->string('image')->nullable();
            $table->enum('correct_answer', ['a', 'b', 'c', 'd']);
            $table->boolean('is_active')->nullable()->default(true);
            $table->softDeletes();
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
        Schema::dropIfExists('questions');
    }
};

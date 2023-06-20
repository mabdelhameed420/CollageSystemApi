<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('comment_replies');
        Schema::create('comment_replies', function (Blueprint $table) {
            $table->id();
            $table->text('comment_text');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('student_affairs_id');
            $table->unsignedBigInteger('lecturer_id');
            $table->dateTime('timestamp');
            $table->unsignedBigInteger('comment_id');
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('student_affairs_id')->references('id')->on('student_affairs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('lecturer_id')->references('id')->on('lecturers')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_replies');
    }
};

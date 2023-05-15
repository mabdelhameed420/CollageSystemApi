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
        Schema::dropIfExists('chats');
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_sender_id')->nullable();
            $table->unsignedBigInteger('student_affairs_sender_id')->nullable();
            $table->unsignedBigInteger('lecturer_sender_id')->nullable();
            $table->unsignedBigInteger('student_reciver_id')->nullable();
            $table->unsignedBigInteger('student_affairs_reciver_id')->nullable();
            $table->unsignedBigInteger('lecturer_reciver_id')->nullable();
            $table->timestamps();
            $table->foreign('student_sender_id')->references('id')->on('students');
            $table->foreign('student_affairs_sender_id')->references('id')->on('student_affairs');
            $table->foreign('lecturer_sender_id')->references('id')->on('lecturers');
            $table->foreign('student_reciver_id')->references('id')->on('students');
            $table->foreign('student_affairs_reciver_id')->references('id')->on('student_affairs');
            $table->foreign('lecturer_reciver_id')->references('id')->on('lecturers');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};

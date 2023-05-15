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
        Schema::dropIfExists('lecturers');
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('national_id');
            $table->string('email');
            $table->string('image')->nullable();
            $table->string('phone_no')->unique();
            $table->string('password');
            $table->string('fcm_token')->nullable();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('course_id');
            $table->foreign('department_id')->references('id')
                ->on('departments')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('course_id')->references('id')
                ->on('courses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};

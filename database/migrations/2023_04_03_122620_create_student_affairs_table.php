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
        Schema::dropIfExists('student_affairs');
        Schema::create('student_affairs', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('national_id');
            $table->string('email');
            $table->string('fcm_token')->nullable();
            $table->string('phone_no')->unique();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('admin_id');
            $table->string('responsible_level');
            $table->string('date_added');
            $table->string('password');
            $table->foreign('admin_id')->references('id')->on('admins')
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
        Schema::dropIfExists('student_affairs');
    }
};

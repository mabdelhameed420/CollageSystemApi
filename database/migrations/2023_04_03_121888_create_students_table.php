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
        Schema::dropIfExists('students');
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('national_id');
            $table->string('fcm_token')->nullable();
            $table->string('email');
            $table->string('phone_no')->unique();
            $table->string('image')->nullable();
            $table->string('password');
            $table->string('level');
            $table->string('state');
            $table->unsignedBigInteger('department_id');
            $table->string('department_code');
            $table->timestamps();
            $table->foreign('department_id')->references('id')
                ->on('departments')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

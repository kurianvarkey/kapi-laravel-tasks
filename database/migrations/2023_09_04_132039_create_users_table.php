<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_title_id');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('full_name')->virtualAs("CONCAT(first_name, ' ', last_name)"); //computed virtual column
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->date('dob')->nullable();
            $table->integer('age')->virtualAs('TIMESTAMPDIFF(year, dob, CURDATE())'); //computed virtual column
            $table->string('api_key', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('active')->default(1);
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

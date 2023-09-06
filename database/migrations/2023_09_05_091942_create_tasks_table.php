<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('ref', 100);
            $table->foreignId('user_id')->index('task_user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title', 250);
            $table->string('description', 1000);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('duration')->virtualAs('TIMESTAMPDIFF(minute, start_date, end_date)'); //computed virtual column;
            $table->boolean('is_completed')->default(0);
            $table->dateTimeTz('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletesTz();
        });

        // to recreate the sequence fields
        DB::statement('CREATE SEQUENCE IF NOT EXISTS seq_task_ref;');
        DB::statement('ALTER SEQUENCE seq_task_ref MINVALUE 1 RESTART 1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

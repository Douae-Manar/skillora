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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id('id_job');
            $table->string('title');
            $table->string('company')->nullable();
            $table->text('description');
            $table->unsignedBigInteger('id_city')->nullable();
            $table->unsignedBigInteger('id_domain');
            $table->foreign('id_city')
                  ->references('id_city')
                  ->on('cities')
                  ->onDelete('cascade');
            $table->foreign('id_domain')
                  ->references('id_domain')
                  ->on('domains')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};

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
        Schema::create('job_skill', function (Blueprint $table) {
            $table->unsignedBigInteger('id_job');
            $table->unsignedBigInteger('id_skill');
            $table->foreign('id_job')
                  ->references('id_job')
                  ->on('job_offers')
                  ->onDelete('cascade');
            $table->foreign('id_skill')
                  ->references('id_skill')
                  ->on('skills')
                  ->onDelete('cascade');
            $table->primary(['id_job', 'id_skill']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_skill');
    }
};

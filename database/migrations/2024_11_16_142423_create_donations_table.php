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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fundraiser_id')->nullable()->index();
            $table->string('title')->nulable();
            $table->text('images')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->nulable();
            $table->date('start_date')->nulable();
            $table->date('end_date')->nulable();
            $table->bigInteger('total')->nullable();
            $table->bigInteger('target')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};

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
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('category')->comment('disaster_emergency|reconstruction')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('address')->nullable();
            $table->text('ability')->nullable();
            $table->string('health_status')->nullable();
            $table->string('status')->nullable();
            $table->string('ktp_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
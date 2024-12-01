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
        Schema::create('expeditions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('district_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('origin_address')->nullable();
            $table->string('telp')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('weight')->nullable();
            $table->date('delivered_at')->nullable();
            $table->date('arrived_at')->nullable();
            $table->string('receipt_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expeditions');
    }
};

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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('disaster_id')->nullable()->index();
            $table->unsignedBigInteger('disaster_station_id')->nullable()->index();
            $table->unsignedBigInteger('branch_office_id')->nullable()->index();
            $table->unsignedBigInteger('delivery_by')->nullable()->index();
            $table->date('date')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('status')->nullable();
            $table->date('delivered_at')->nullable();
            $table->date('arrived_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};

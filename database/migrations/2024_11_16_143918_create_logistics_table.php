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
        Schema::create('logistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('disaster_id')->nullable()->index();
            $table->unsignedBigInteger('expedition_id')->nullable()->index();
            $table->unsignedBigInteger('delivery_id')->nullable()->index();
            $table->unsignedBigInteger('branch_office_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('good_id')->nullable()->index();
            $table->text('image')->nullable();
            $table->string('status')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistics');
    }
};

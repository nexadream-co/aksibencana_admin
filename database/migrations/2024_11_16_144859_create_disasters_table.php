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
        Schema::create('disasters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('disaster_category_id')->nullable()->index();
            $table->unsignedBigInteger('district_id')->nullable()->index();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('type')->nullable();
            $table->date('date')->nullable();
            $table->text('images')->nullable();
            $table->string('status')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disasters');
    }
};

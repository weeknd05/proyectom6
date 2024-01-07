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
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('comarca_id');
            $table->unsignedBigInteger('provincia_id');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->foreign('comarca_id')->references('id')->on('comarcas');
            $table->foreign('provincia_id')->references('id')->on('provincias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
};

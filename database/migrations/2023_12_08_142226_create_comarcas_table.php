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
        Schema::create('comarcas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('provincia_id'); // Clau forana per a la taula de provincias
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('provincia_id')->references('id')->on('provincias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comarcas');
    }
};

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
        Schema::create('billets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_voyage')->constrained('voyages')->cascadeOnDelete();
            $table->foreignId('id_commande')->constrained('commandes')->cascadeOnDelete();
            $table->unsignedInteger('qte')->default(1);
            $table->string('nom_voyageur')->nullable();
            $table->string('passport_voyageur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billets');
    }
};

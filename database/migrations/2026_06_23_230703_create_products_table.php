<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price'); // Prix en FCFA
            $table->string('unit'); // kg ou pièce
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('status')->default('En stock');
            $table->string('vendor_name')->default('Ferme de la Plaine');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
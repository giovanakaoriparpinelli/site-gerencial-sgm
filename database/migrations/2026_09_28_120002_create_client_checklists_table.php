<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('etapa');
            $table->json('dados')->nullable();
            $table->timestamps();
            $table->unique(['client_id', 'etapa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_checklists');
    }
};

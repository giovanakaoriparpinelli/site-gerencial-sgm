<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('empresa');
            $table->string('segmento')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('instagram')->nullable();
            $table->string('etapa_funil')->default('prospeccao');
            $table->date('data_ultimo_contato')->nullable();
            $table->string('proxima_acao')->nullable();
            $table->date('data_proxima_acao')->nullable();
            $table->decimal('valor_potencial', 10, 2)->nullable();
            $table->text('principal_necessidade')->nullable();
            $table->text('objecao')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};

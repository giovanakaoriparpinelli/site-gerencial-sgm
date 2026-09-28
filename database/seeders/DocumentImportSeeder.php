<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

/**
 * Importa o acervo histórico de atas e agendas (empacotado em
 * database/seeders/data/documentos.json, exportado do ambiente local em
 * 28/09/2026) para dentro do banco do SGM Gerencial — inclusive em produção,
 * onde as pastas atas/ e agenda/ do repositório principal não existem.
 * Documentos novos, gerados depois do lançamento, entram pela própria tela
 * de upload do sistema, não por este seeder.
 */
class DocumentImportSeeder extends Seeder
{
    public function run(): void
    {
        $arquivo = base_path('database/seeders/data/documentos.json');
        if (! file_exists($arquivo)) {
            return;
        }

        $documentos = json_decode(file_get_contents($arquivo), true) ?: [];

        foreach ($documentos as $doc) {
            Document::updateOrCreate(
                ['tipo' => $doc['tipo'], 'data' => $doc['data'], 'titulo' => $doc['titulo']],
                ['conteudo' => $doc['conteudo']]
            );
        }
    }
}

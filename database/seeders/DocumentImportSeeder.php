<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

/**
 * Importa as atas e agendas já existentes em atas/ e agenda/ (na raiz do
 * projeto, fora deste app Laravel) para dentro do banco do SGM Gerencial,
 * para que fiquem visíveis na aba "Atas e Agendas" desde o primeiro deploy.
 */
class DocumentImportSeeder extends Seeder
{
    public function run(): void
    {
        $this->importarPasta(base_path('../atas'), 'ata');
        $this->importarPasta(base_path('../agenda'), 'agenda');
    }

    private function importarPasta(string $pasta, string $tipo): void
    {
        $pasta = realpath($pasta);
        if (! $pasta) {
            return;
        }

        foreach (glob($pasta.'/*.md') as $arquivo) {
            $nome = basename($arquivo, '.md');
            if (stripos($nome, '_TEMPLATE') !== false) {
                continue;
            }

            if (! preg_match('/^(\d{4}-\d{2}-\d{2})/', $nome, $m)) {
                continue;
            }
            $data = $m[1];

            $conteudo = file_get_contents($arquivo);
            $titulo = $nome;
            if (preg_match('/^#\s+(.+)$/m', $conteudo, $tm)) {
                $titulo = trim($tm[1]);
            }

            Document::updateOrCreate(
                ['tipo' => $tipo, 'data' => $data, 'titulo' => $titulo],
                ['conteudo' => $conteudo]
            );
        }
    }
}

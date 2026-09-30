<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['tipo', 'data', 'titulo', 'conteudo', 'created_by'])]
class Document extends Model
{
    /** Item de checklist: "- [ ] texto", aceitando também "*"/"+" e indentação. */
    private const PADRAO_CHECKLIST = '/^\s*[-*+]\s+\[( |x|X)\]\s+(.*)$/m';

    protected function casts(): array
    {
        return [
            'data' => 'date',
        ];
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Itens de checklist ("- [ ] texto" / "- [x] texto") encontrados no conteúdo
     * em Markdown deste documento — usado para trazer as pendências da agenda
     * do dia para o dashboard.
     */
    public function itensChecklist(): array
    {
        preg_match_all(self::PADRAO_CHECKLIST, $this->conteudo, $m);

        $itens = [];
        foreach ($m[1] as $i => $marca) {
            $itens[] = [
                'indice' => $i,
                'concluido' => strtolower($marca) === 'x',
                'texto' => $m[2][$i],
            ];
        }

        return $itens;
    }

    public function alternarItemChecklist(int $indice): void
    {
        $contador = -1;
        $novoConteudo = preg_replace_callback(self::PADRAO_CHECKLIST, function ($m) use ($indice, &$contador) {
            $contador++;
            if ($contador !== $indice) {
                return $m[0];
            }
            $novaMarca = strtolower($m[1]) === 'x' ? ' ' : 'x';

            return preg_replace('/\[( |x|X)\]/', '['.$novaMarca.']', $m[0], 1);
        }, $this->conteudo);

        $this->update(['conteudo' => $novoConteudo]);
    }
}

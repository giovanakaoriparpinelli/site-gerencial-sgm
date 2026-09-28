<?php

namespace App\Models;

use App\Support\Funil;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'empresa', 'segmento', 'whatsapp', 'instagram', 'etapa_funil',
    'data_ultimo_contato', 'proxima_acao', 'data_proxima_acao', 'valor_potencial',
    'principal_necessidade', 'objecao', 'observacoes', 'responsavel_id',
])]
class Client extends Model
{
    protected function casts(): array
    {
        return [
            'data_ultimo_contato' => 'date',
            'data_proxima_acao' => 'date',
            'valor_potencial' => 'decimal:2',
        ];
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(ClientChecklist::class);
    }

    /**
     * Exportação em Markdown com todas as informações de cadastro e de
     * checklist por etapa já preenchidas para este cliente — campos vazios
     * são omitidos.
     */
    public function paraMarkdown(): string
    {
        $linhas = ["# {$this->empresa}", ''];

        $cadastro = [
            'Segmento' => $this->segmento,
            'WhatsApp' => $this->whatsapp,
            'Instagram' => $this->instagram,
            'Etapa do funil' => Funil::etapas()[$this->etapa_funil]['label'] ?? $this->etapa_funil,
            'Responsável' => $this->responsavel?->name,
            'Data do último contato' => $this->data_ultimo_contato?->format('d/m/Y'),
            'Próxima ação' => $this->proxima_acao,
            'Data da próxima ação' => $this->data_proxima_acao?->format('d/m/Y'),
            'Valor potencial' => $this->valor_potencial !== null ? 'R$ '.number_format((float) $this->valor_potencial, 2, ',', '.') : null,
            'Principal necessidade / oportunidade' => $this->principal_necessidade,
            'Objeção' => $this->objecao,
            'Observações' => $this->observacoes,
        ];

        $linhas[] = '## Dados do cliente';
        $linhas[] = '';
        foreach ($cadastro as $rotulo => $valor) {
            if ($valor === null || $valor === '') {
                continue;
            }
            $linhas[] = "**{$rotulo}:** {$valor}";
            $linhas[] = '';
        }

        $checklistsPorEtapa = $this->checklists->keyBy('etapa');

        foreach (Funil::etapas() as $chave => $etapa) {
            $dados = $checklistsPorEtapa->get($chave)?->dados ?? [];
            $preenchidos = array_filter($dados, fn ($v) => trim((string) $v) !== '');
            if (empty($preenchidos)) {
                continue;
            }

            $linhas[] = "## {$etapa['label']}";
            $linhas[] = '';
            foreach ($preenchidos as $campo => $valor) {
                $rotulo = $etapa['campos'][$campo] ?? $campo;
                $linhas[] = "**{$rotulo}:** {$valor}";
                $linhas[] = '';
            }
        }

        return implode("\n", $linhas);
    }
}

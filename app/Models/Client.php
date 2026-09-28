<?php

namespace App\Models;

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
}

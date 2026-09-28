<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['titulo', 'descricao', 'data_prevista', 'concluida', 'concluida_em', 'created_by', 'assigned_to'])]
class Task extends Model
{
    protected function casts(): array
    {
        return [
            'data_prevista' => 'date',
            'concluida' => 'boolean',
            'concluida_em' => 'datetime',
        ];
    }

    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}

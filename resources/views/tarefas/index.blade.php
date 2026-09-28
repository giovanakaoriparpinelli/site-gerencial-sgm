@extends('layouts.app')

@section('title', 'Tarefas')

@section('content')
  <div class="card" style="margin-bottom: 24px;">
    <h2 style="margin: 0 0 14px; font-size: 1rem;">Nova tarefa</h2>
    <form method="POST" action="{{ route('tarefas.store') }}" style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 12px; align-items: end;">
      @csrf
      <div>
        <label class="field-label">Título</label>
        <input type="text" name="titulo" value="{{ old('titulo') }}" required class="field">
      </div>
      <div>
        <label class="field-label">Data prevista</label>
        <input type="date" name="data_prevista" value="{{ old('data_prevista') }}" class="field">
      </div>
      <div>
        <label class="field-label">Responsável</label>
        <select name="assigned_to" class="field">
          <option value="">—</option>
          @foreach ($usuarios as $u)
            <option value="{{ $u->id }}" @selected(old('assigned_to') == $u->id)>{{ $u->name }}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="btn-primary" style="white-space: nowrap;">Adicionar</button>
      <div style="grid-column: 1 / -1;">
        <label class="field-label">Descrição (opcional)</label>
        <textarea name="descricao" rows="2" class="field">{{ old('descricao') }}</textarea>
      </div>
    </form>
  </div>

  <div class="card" style="margin-bottom: 24px;">
    <form method="GET" action="{{ route('tarefas.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: end;">
      <div style="flex: 1; min-width: 220px;">
        <label class="field-label">Buscar</label>
        <input type="text" name="busca" value="{{ $busca }}" placeholder="Título ou descrição..." class="field">
      </div>
      <div>
        <label class="field-label">Status</label>
        <select name="status" class="field" onchange="this.form.submit()">
          <option value="pendentes" @selected($status === 'pendentes')>Pendentes</option>
          <option value="concluidas" @selected($status === 'concluidas')>Concluídas</option>
          <option value="todas" @selected($status === 'todas')>Todas</option>
        </select>
      </div>
      <button type="submit" class="btn-ghost">Filtrar</button>
    </form>
  </div>

  <div class="card">
    <h2 style="margin: 0 0 14px; font-size: 1rem;">Tarefas ({{ $tarefas->count() }})</h2>

    @forelse ($tarefas as $tarefa)
      <div style="padding: 12px 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--border);' : '' }}">
        <div style="display: flex; align-items: center; gap: 12px;">
          <form method="POST" action="{{ route('tarefas.concluir', $tarefa) }}">
            @csrf
            @method('PATCH')
            <button type="submit" title="{{ $tarefa->concluida ? 'Marcar como pendente' : 'Marcar como concluída' }}"
                    style="width: 20px; height: 20px; border-radius: 6px; border: 1px solid var(--border); background: {{ $tarefa->concluida ? 'var(--gradient)' : 'transparent' }}; cursor: pointer;">
            </button>
          </form>

          <div style="flex: 1;">
            <p style="margin: 0; {{ $tarefa->concluida ? 'text-decoration: line-through; color: var(--ink-faint);' : '' }}">{{ $tarefa->titulo }}</p>
            @if ($tarefa->descricao)
              <p style="margin: 2px 0 0; font-size: 0.82rem; color: var(--ink-faint);">{{ $tarefa->descricao }}</p>
            @endif
            @if ($tarefa->responsavel)
              <p style="margin: 2px 0 0; font-size: 0.78rem; color: var(--ink-faint);">Responsável: {{ $tarefa->responsavel->name }}</p>
            @endif
          </div>

          @if ($tarefa->data_prevista)
            <span style="font-size: 0.85rem; color: {{ !$tarefa->concluida && $tarefa->data_prevista->isPast() && !$tarefa->data_prevista->isToday() ? 'var(--danger)' : 'var(--ink-soft)' }};">
              {{ $tarefa->data_prevista->format('d/m/Y') }}
            </span>
          @endif

          <details style="position: relative;">
            <summary class="icon-btn" style="list-style: none; cursor: pointer;" title="Editar">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m16.5 3.5 4 4L8 20H4v-4Z"/></svg>
            </summary>
            <div style="position: absolute; right: 0; z-index: 10; margin-top: 8px; width: 320px; background: #241247; border: 1px solid var(--border); border-radius: 12px; padding: 16px;">
              <form method="POST" action="{{ route('tarefas.update', $tarefa) }}">
                @csrf
                @method('PUT')
                <label class="field-label">Título</label>
                <input type="text" name="titulo" value="{{ $tarefa->titulo }}" required class="field" style="margin-bottom: 10px;">
                <label class="field-label">Descrição</label>
                <textarea name="descricao" rows="2" class="field" style="margin-bottom: 10px;">{{ $tarefa->descricao }}</textarea>
                <label class="field-label">Data prevista</label>
                <input type="date" name="data_prevista" value="{{ $tarefa->data_prevista?->format('Y-m-d') }}" class="field" style="margin-bottom: 10px;">
                <label class="field-label">Responsável</label>
                <select name="assigned_to" class="field" style="margin-bottom: 14px;">
                  <option value="">—</option>
                  @foreach ($usuarios as $u)
                    <option value="{{ $u->id }}" @selected($tarefa->assigned_to == $u->id)>{{ $u->name }}</option>
                  @endforeach
                </select>
                <button type="submit" class="btn-primary" style="width: 100%;">Salvar</button>
              </form>
            </div>
          </details>

          <form method="POST" action="{{ route('tarefas.destroy', $tarefa) }}" onsubmit="return confirm('Remover esta tarefa?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="icon-btn" title="Excluir">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M9 7V4h6v3m-8 0 1 13h8l1-13"/></svg>
            </button>
          </form>
        </div>
      </div>
    @empty
      <p style="color: var(--ink-faint); margin: 0; font-size: 0.9rem;">Nenhuma tarefa encontrada.</p>
    @endforelse
  </div>
@endsection

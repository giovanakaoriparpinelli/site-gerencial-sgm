@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <div class="card" style="margin-bottom: 24px;">
    <p style="margin: 0; color: var(--ink-soft); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Hoje</p>
    <h2 style="margin: 4px 0 0; font-size: 1.4rem; font-family: 'Fraunces', Georgia, serif;">
      {{ $hoje->translatedFormat('l, d \d\e F \d\e Y') }}
    </h2>
  </div>

  <div class="card">
    <h2 style="margin: 0 0 14px; font-size: 1rem;">Tarefas pendentes de hoje</h2>

    @forelse ($tarefas as $tarefa)
      <div style="display: flex; align-items: center; gap: 12px; padding: 12px 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--border);' : '' }}">
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
        </div>

        @if ($tarefa->data_prevista)
          <span style="font-size: 0.85rem; color: {{ !$tarefa->concluida && $tarefa->data_prevista->isPast() && !$tarefa->data_prevista->isToday() ? 'var(--danger)' : 'var(--ink-soft)' }};">
            {{ $tarefa->data_prevista->format('d/m/Y') }}
          </span>
        @endif
      </div>
    @empty
      <p style="color: var(--ink-faint); margin: 0; font-size: 0.9rem;">Nenhuma tarefa pendente para hoje.</p>
    @endforelse
  </div>

  <p style="margin: 18px 0 0; font-size: 0.85rem;">
    <a href="{{ route('tarefas.index') }}" style="color: var(--accent);">Ver todas as tarefas &rarr;</a>
  </p>

  <div class="card" style="margin-top: 24px;">
    <h2 style="margin: 0 0 4px; font-size: 1rem;">Pendências da agenda de hoje</h2>

    @if ($agendaHoje)
      <p style="margin: 0 0 14px; font-size: 0.82rem; color: var(--ink-faint);">
        <a href="{{ route('documentos.show', $agendaHoje) }}" style="color: var(--accent);">{{ $agendaHoje->titulo }}</a>
      </p>

      @forelse ($pendenciasAgenda as $item)
        <div style="display: flex; align-items: center; gap: 12px; padding: 10px 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--border);' : '' }}">
          <form method="POST" action="{{ route('documentos.checklist', [$agendaHoje, $item['indice']]) }}">
            @csrf
            @method('PATCH')
            <button type="submit" title="{{ $item['concluido'] ? 'Marcar como pendente' : 'Marcar como concluído' }}"
                    style="width: 20px; height: 20px; border-radius: 6px; border: 1px solid var(--border); background: {{ $item['concluido'] ? 'var(--gradient)' : 'transparent' }}; cursor: pointer; flex-shrink: 0;">
            </button>
          </form>
          <p style="margin: 0; flex: 1; {{ $item['concluido'] ? 'text-decoration: line-through; color: var(--ink-faint);' : '' }}">{{ $item['texto'] }}</p>
          @if (! $item['concluido'])
            <form method="POST" action="{{ route('tarefas.store') }}">
              @csrf
              <input type="hidden" name="titulo" value="{{ $item['texto'] }}">
              <input type="hidden" name="data_prevista" value="{{ $hoje->format('Y-m-d') }}">
              <button type="submit" class="icon-btn" title="Criar tarefa a partir desta pendência">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
              </button>
            </form>
          @endif
        </div>
      @empty
        <p style="color: var(--ink-faint); margin: 0; font-size: 0.9rem;">A agenda de hoje não tem itens de checklist.</p>
      @endforelse
    @else
      <p style="color: var(--ink-faint); margin: 0; font-size: 0.9rem;">Nenhuma agenda cadastrada para hoje ainda. Suba a agenda do dia em Atas e Agendas.</p>
    @endif
  </div>
@endsection

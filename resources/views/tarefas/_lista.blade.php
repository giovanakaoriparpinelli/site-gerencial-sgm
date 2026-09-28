@forelse ($tarefas as $tarefa)
  <div style="display: flex; align-items: center; gap: 12px; padding: 12px 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--border);' : '' }}">
    <form method="POST" action="{{ route('tarefas.concluir', $tarefa) }}">
      @csrf
      @method('PATCH')
      <button type="submit" title="{{ $tarefa->concluida ? 'Marcar como pendente' : 'Marcar como concluída' }}"
              style="width: 20px; height: 20px; border-radius: 6px; border: 1px solid var(--border); background: {{ $tarefa->concluida ? 'var(--gradient)' : 'transparent' }}; cursor: pointer; flex-shrink: 0;">
      </button>
    </form>

    <div style="flex: 1; min-width: 0;">
      <p style="margin: 0; {{ $tarefa->concluida ? 'text-decoration: line-through; color: var(--ink-faint);' : '' }}">{{ $tarefa->titulo }}</p>
      @if ($tarefa->descricao)
        <p style="margin: 2px 0 0; font-size: 0.82rem; color: var(--ink-faint);">{{ $tarefa->descricao }}</p>
      @endif
    </div>

    @if ($tarefa->responsavel)
      <span style="font-size: 0.82rem; color: var(--ink-soft); white-space: nowrap;">{{ $tarefa->responsavel->name }}</span>
    @endif

    @if ($tarefa->data_prevista)
      <span style="font-size: 0.85rem; white-space: nowrap; color: {{ !$tarefa->concluida && $tarefa->data_prevista->isPast() && !$tarefa->data_prevista->isToday() ? 'var(--danger)' : 'var(--ink-soft)' }};">
        {{ $tarefa->data_prevista->format('d/m/Y') }}
      </span>
    @endif

    <button type="button" class="icon-btn abrir-tarefa" data-id="{{ $tarefa->id }}" title="Editar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m16.5 3.5 4 4L8 20H4v-4Z"/></svg>
    </button>

    <form method="POST" action="{{ route('tarefas.destroy', $tarefa) }}" onsubmit="return confirm('Remover esta tarefa?');">
      @csrf
      @method('DELETE')
      <button type="submit" class="icon-btn" title="Excluir">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M9 7V4h6v3m-8 0 1 13h8l1-13"/></svg>
      </button>
    </form>
  </div>
@empty
  <p style="color: var(--ink-faint); margin: 0; font-size: 0.9rem;">Nenhuma tarefa encontrada.</p>
@endforelse

<script type="application/json" id="tarefas-data">
  {!! json_encode($tarefas->keyBy('id')->map(function ($t) {
      return [
          'id' => $t->id,
          'titulo' => $t->titulo,
          'descricao' => $t->descricao,
          'data_prevista' => $t->data_prevista?->format('Y-m-d'),
          'assigned_to' => $t->assigned_to,
      ];
  })) !!}
</script>

<div class="modal-backdrop" id="modal-tarefa">
  <div class="modal">
    <span class="modal-close" data-close="modal-tarefa">&times;</span>
    <h2 style="margin: 0 0 16px; font-size: 1.1rem; font-family: 'Fraunces', Georgia, serif;">Editar tarefa</h2>
    <form method="POST" id="form-tarefa">
      @csrf
      @method('PUT')
      <label class="field-label">Título</label>
      <input type="text" name="titulo" id="t_titulo" required class="field" style="margin-bottom: 12px;">
      <label class="field-label">Descrição</label>
      <textarea name="descricao" id="t_descricao" rows="2" class="field" style="margin-bottom: 12px;"></textarea>
      <label class="field-label">Data prevista</label>
      <input type="date" name="data_prevista" id="t_data_prevista" class="field" style="margin-bottom: 12px;">
      <label class="field-label">Responsável</label>
      <select name="assigned_to" id="t_assigned_to" class="field" style="margin-bottom: 18px;">
        <option value="">—</option>
        @foreach ($usuarios as $u)
          <option value="{{ $u->id }}">{{ $u->name }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn-primary" style="width: 100%;">Salvar</button>
    </form>
  </div>
</div>

<script>
  (function () {
    var tarefasData = JSON.parse(document.getElementById('tarefas-data').textContent || '{}');

    function abrirModal(id) { document.getElementById(id).classList.add('open'); }
    function fecharModal(id) { document.getElementById(id).classList.remove('open'); }

    document.querySelectorAll('[data-close="modal-tarefa"]').forEach(function (el) {
      el.addEventListener('click', function () { fecharModal('modal-tarefa'); });
    });
    document.getElementById('modal-tarefa').addEventListener('click', function (e) {
      if (e.target === this) fecharModal('modal-tarefa');
    });

    document.querySelectorAll('.abrir-tarefa').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var t = tarefasData[btn.getAttribute('data-id')];
        if (!t) return;
        document.getElementById('t_titulo').value = t.titulo || '';
        document.getElementById('t_descricao').value = t.descricao || '';
        document.getElementById('t_data_prevista').value = t.data_prevista || '';
        document.getElementById('t_assigned_to').value = t.assigned_to || '';
        document.getElementById('form-tarefa').action = '/tarefas/' + t.id;
        abrirModal('modal-tarefa');
      });
    });
  })();
</script>

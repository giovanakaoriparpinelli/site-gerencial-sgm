@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
  <div class="card" style="margin-bottom: 24px;">
    <details>
      <summary style="cursor: pointer; font-size: 1rem; font-weight: 700; list-style: none;">+ Novo cliente</summary>
      <form method="POST" action="{{ route('clientes.store') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-top: 16px; align-items: end;">
        @csrf
        <div>
          <label class="field-label">Empresa *</label>
          <input type="text" name="empresa" required class="field">
        </div>
        <div>
          <label class="field-label">Segmento</label>
          <input type="text" name="segmento" class="field">
        </div>
        <div>
          <label class="field-label">WhatsApp</label>
          <input type="text" name="whatsapp" class="field">
        </div>
        <div>
          <label class="field-label">Instagram</label>
          <input type="text" name="instagram" class="field">
        </div>
        <div>
          <label class="field-label">Etapa</label>
          <select name="etapa_funil" class="field">
            @foreach ($etapas as $chave => $etapa)
              <option value="{{ $chave }}">{{ $etapa['label'] }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="field-label">Responsável</label>
          <select name="responsavel_id" class="field">
            <option value="">—</option>
            @foreach ($usuarios as $u)
              <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn-primary">Cadastrar</button>
      </form>
    </details>
  </div>

  <div class="card" style="margin-bottom: 24px;">
    <form method="GET" action="{{ route('clientes.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: end;">
      <div style="flex: 1; min-width: 220px;">
        <label class="field-label">Buscar</label>
        <input type="text" name="busca" value="{{ $busca }}" placeholder="Empresa, segmento ou WhatsApp..." class="field">
      </div>
      <div>
        <label class="field-label">Etapa</label>
        <select name="etapa" class="field" onchange="this.form.submit()">
          <option value="">Todas</option>
          @foreach ($etapas as $chave => $etapa)
            <option value="{{ $chave }}" @selected($etapaFiltro === $chave)>{{ $etapa['label'] }}</option>
          @endforeach
        </select>
      </div>
      <input type="hidden" name="visualizacao" value="{{ $visualizacao }}">
      <button type="submit" class="btn-ghost">Filtrar</button>

      <div style="display: flex; gap: 6px; margin-left: auto;">
        <a href="{{ route('clientes.index', array_merge(request()->except('page'), ['visualizacao' => 'grade'])) }}"
           class="icon-btn" style="{{ $visualizacao === 'grade' ? 'border-color: var(--accent); color: var(--ink);' : '' }}" title="Visualizar em grade">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="8" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/><rect x="13" y="13" width="8" height="8" rx="1.5"/></svg>
        </a>
        <a href="{{ route('clientes.index', array_merge(request()->except('page'), ['visualizacao' => 'lista'])) }}"
           class="icon-btn" style="{{ $visualizacao === 'lista' ? 'border-color: var(--accent); color: var(--ink);' : '' }}" title="Visualizar em lista">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </a>
      </div>
    </form>
  </div>

  @if ($visualizacao === 'lista')
    <div class="card" style="padding: 0; overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
        <thead>
          <tr style="text-align: left;">
            <th style="padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">Empresa</th>
            <th style="padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">Segmento</th>
            <th style="padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">WhatsApp</th>
            <th style="padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">Etapa</th>
            <th style="padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">Último contato</th>
            <th style="padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">Próxima ação</th>
            <th style="padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--ink-soft);"></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($clientes as $cliente)
            <tr>
              <td style="padding: 10px 14px; border-bottom: 1px solid var(--border);">
                <button type="button" class="abrir-cliente" data-id="{{ $cliente->id }}"
                        style="background: none; border: none; padding: 0; cursor: pointer; color: var(--ink); font-weight: 700;">
                  {{ $cliente->empresa }}
                </button>
              </td>
              <td style="padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">{{ $cliente->segmento ?: '—' }}</td>
              <td style="padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">{{ $cliente->whatsapp ?: '—' }}</td>
              <td style="padding: 10px 14px; border-bottom: 1px solid var(--border);">
                <span style="display: inline-block; padding: 3px 9px; border-radius: 999px; background: var(--surface-2); border: 1px solid var(--border); font-size: 0.72rem; color: var(--ink-soft);">
                  {{ $etapas[$cliente->etapa_funil]['label'] ?? $cliente->etapa_funil }}
                </span>
              </td>
              <td style="padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--ink-faint);">{{ $cliente->data_ultimo_contato?->format('d/m/Y') ?? '—' }}</td>
              <td style="padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--ink-faint);">{{ $cliente->data_proxima_acao?->format('d/m/Y') ?? '—' }}</td>
              <td style="padding: 10px 14px; border-bottom: 1px solid var(--border);">
                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                  <button type="button" class="icon-btn abrir-cliente" data-id="{{ $cliente->id }}" title="Editar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m16.5 3.5 4 4L8 20H4v-4Z"/></svg>
                  </button>
                  <button type="button" class="icon-btn abrir-checklist" data-id="{{ $cliente->id }}" title="Checklist">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11.5 11 13.5l4-4.5"/><rect x="3" y="3" width="18" height="18" rx="3"/></svg>
                  </button>
                  <a href="{{ route('clientes.exportar', $cliente) }}" class="icon-btn" title="Exportar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v11m0 0 3.5-3.5M12 15l-3.5-3.5"/><path d="M5 17v2a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-2"/></svg>
                  </a>
                  <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" onsubmit="return confirm('Remover este cliente?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="icon-btn" title="Excluir">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M9 7V4h6v3m-8 0 1 13h8l1-13"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" style="padding: 14px; color: var(--ink-faint);">Nenhum cliente encontrado.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  @else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px;">
      @forelse ($clientes as $cliente)
        <div class="card">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
            <button type="button" class="abrir-cliente" data-id="{{ $cliente->id }}"
                    style="background: none; border: none; padding: 0; text-align: left; cursor: pointer; color: var(--ink); font-weight: 700; font-size: 1rem; font-family: 'Fraunces', Georgia, serif;">
              {{ $cliente->empresa }}
            </button>
            <div style="display: flex; gap: 6px; flex-shrink: 0;">
              <button type="button" class="icon-btn abrir-cliente" data-id="{{ $cliente->id }}" title="Editar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m16.5 3.5 4 4L8 20H4v-4Z"/></svg>
              </button>
              <button type="button" class="icon-btn abrir-checklist" data-id="{{ $cliente->id }}" title="Checklist">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11.5 11 13.5l4-4.5"/><rect x="3" y="3" width="18" height="18" rx="3"/></svg>
              </button>
              <a href="{{ route('clientes.exportar', $cliente) }}" class="icon-btn" title="Exportar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v11m0 0 3.5-3.5M12 15l-3.5-3.5"/><path d="M5 17v2a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-2"/></svg>
              </a>
              <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" onsubmit="return confirm('Remover este cliente?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="icon-btn" title="Excluir">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M9 7V4h6v3m-8 0 1 13h8l1-13"/></svg>
                </button>
              </form>
            </div>
          </div>

          <p style="margin: 8px 0 0; font-size: 0.85rem; color: var(--ink-soft);">{{ $cliente->segmento ?: '—' }}</p>
          <p style="margin: 4px 0 0; font-size: 0.85rem; color: var(--ink-soft);">{{ $cliente->whatsapp ?: '—' }}</p>

          <span style="display: inline-block; margin-top: 10px; padding: 4px 10px; border-radius: 999px; background: var(--surface-2); border: 1px solid var(--border); font-size: 0.75rem; color: var(--ink-soft);">
            {{ $etapas[$cliente->etapa_funil]['label'] ?? $cliente->etapa_funil }}
          </span>

          <div style="display: flex; justify-content: space-between; margin-top: 14px; font-size: 0.78rem; color: var(--ink-faint);">
            <span>Último contato: {{ $cliente->data_ultimo_contato?->format('d/m/Y') ?? '—' }}</span>
            <span>Próxima ação: {{ $cliente->data_proxima_acao?->format('d/m/Y') ?? '—' }}</span>
          </div>
        </div>
      @empty
        <p style="color: var(--ink-faint); margin: 0; font-size: 0.9rem;">Nenhum cliente encontrado.</p>
      @endforelse
    </div>
  @endif

  {{-- Dados de todos os clientes (para preencher os modais via JS, sem round-trip ao servidor) --}}
  <script type="application/json" id="clientes-data">
    {!! json_encode($clientes->keyBy('id')->map(function ($c) {
        return [
            'id' => $c->id,
            'empresa' => $c->empresa,
            'segmento' => $c->segmento,
            'whatsapp' => $c->whatsapp,
            'instagram' => $c->instagram,
            'etapa_funil' => $c->etapa_funil,
            'data_ultimo_contato' => $c->data_ultimo_contato?->format('Y-m-d'),
            'proxima_acao' => $c->proxima_acao,
            'data_proxima_acao' => $c->data_proxima_acao?->format('Y-m-d'),
            'valor_potencial' => $c->valor_potencial,
            'principal_necessidade' => $c->principal_necessidade,
            'objecao' => $c->objecao,
            'observacoes' => $c->observacoes,
            'responsavel_id' => $c->responsavel_id,
            'checklists' => $c->checklists->keyBy('etapa')->map(fn ($chk) => $chk->dados),
        ];
    })) !!}
  </script>

  {{-- Modal: detalhes / edicao do cliente --}}
  <div class="modal-backdrop" id="modal-cliente">
    <div class="modal">
      <span class="modal-close" data-close="modal-cliente">&times;</span>
      <h2 style="margin: 0 0 16px; font-size: 1.1rem; font-family: 'Fraunces', Georgia, serif;">Dados do cliente</h2>
      <form method="POST" id="form-cliente" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
        @csrf
        @method('PUT')
        <div>
          <label class="field-label">Empresa *</label>
          <input type="text" name="empresa" id="c_empresa" required class="field">
        </div>
        <div>
          <label class="field-label">Segmento</label>
          <input type="text" name="segmento" id="c_segmento" class="field">
        </div>
        <div>
          <label class="field-label">WhatsApp</label>
          <input type="text" name="whatsapp" id="c_whatsapp" class="field">
        </div>
        <div>
          <label class="field-label">Instagram</label>
          <input type="text" name="instagram" id="c_instagram" class="field">
        </div>
        <div>
          <label class="field-label">Etapa do funil</label>
          <select name="etapa_funil" id="c_etapa_funil" class="field">
            @foreach ($etapas as $chave => $etapa)
              <option value="{{ $chave }}">{{ $etapa['label'] }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="field-label">Responsável</label>
          <select name="responsavel_id" id="c_responsavel_id" class="field">
            <option value="">—</option>
            @foreach ($usuarios as $u)
              <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="field-label">Data do último contato</label>
          <input type="date" name="data_ultimo_contato" id="c_data_ultimo_contato" class="field">
        </div>
        <div>
          <label class="field-label">Próxima ação</label>
          <input type="text" name="proxima_acao" id="c_proxima_acao" class="field">
        </div>
        <div>
          <label class="field-label">Data da próxima ação</label>
          <input type="date" name="data_proxima_acao" id="c_data_proxima_acao" class="field">
        </div>
        <div>
          <label class="field-label">Valor potencial (R$)</label>
          <input type="number" step="0.01" name="valor_potencial" id="c_valor_potencial" class="field">
        </div>
        <div style="grid-column: 1 / -1;">
          <label class="field-label">Principal necessidade / oportunidade</label>
          <textarea name="principal_necessidade" id="c_principal_necessidade" rows="2" class="field"></textarea>
        </div>
        <div style="grid-column: 1 / -1;">
          <label class="field-label">Objeção</label>
          <textarea name="objecao" id="c_objecao" rows="2" class="field"></textarea>
        </div>
        <div style="grid-column: 1 / -1;">
          <label class="field-label">Observações</label>
          <textarea name="observacoes" id="c_observacoes" rows="2" class="field"></textarea>
        </div>
        <div style="grid-column: 1 / -1;">
          <button type="submit" class="btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Modal: checklist por etapa --}}
  <div class="modal-backdrop" id="modal-checklist">
    <div class="modal modal-wide">
      <span class="modal-close" data-close="modal-checklist">&times;</span>
      <h2 style="margin: 0 0 4px; font-size: 1.1rem; font-family: 'Fraunces', Georgia, serif;">Checklist do cliente</h2>
      <p id="chk-empresa" style="margin: 0 0 4px; color: var(--ink-soft); font-size: 0.85rem;"></p>

      <div class="tabs">
        @foreach ($etapas as $chave => $etapa)
          <button type="button" class="tab-btn chk-tab-btn" data-etapa="{{ $chave }}">{{ $etapa['label'] }}</button>
        @endforeach
      </div>

      @foreach ($etapas as $chave => $etapa)
        <div class="tab-pane chk-tab-pane" data-etapa="{{ $chave }}">
          <form method="POST" class="chk-form" data-etapa="{{ $chave }}">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">
              @foreach ($etapa['campos'] as $campo => $rotulo)
                <div>
                  <label class="field-label">{{ $rotulo }}</label>
                  <textarea name="{{ $campo }}" rows="1" class="field chk-field" data-campo="{{ $campo }}"></textarea>
                </div>
              @endforeach
            </div>
            <div style="margin-top: 16px;">
              <button type="submit" class="btn-primary">Salvar etapa</button>
            </div>
          </form>
        </div>
      @endforeach
    </div>
  </div>

  <script>
    (function () {
      var clientesData = JSON.parse(document.getElementById('clientes-data').textContent || '{}');
      var abrirCliente = {{ (int) $abrirCliente }};
      var abrirChecklist = @json($abrirChecklist);

      function abrirModal(id) { document.getElementById(id).classList.add('open'); }
      function fecharModal(id) { document.getElementById(id).classList.remove('open'); }

      document.querySelectorAll('[data-close]').forEach(function (el) {
        el.addEventListener('click', function () { fecharModal(el.getAttribute('data-close')); });
      });
      document.querySelectorAll('.modal-backdrop').forEach(function (el) {
        el.addEventListener('click', function (e) { if (e.target === el) fecharModal(el.id); });
      });

      function preencherCliente(cliente) {
        document.getElementById('c_empresa').value = cliente.empresa || '';
        document.getElementById('c_segmento').value = cliente.segmento || '';
        document.getElementById('c_whatsapp').value = cliente.whatsapp || '';
        document.getElementById('c_instagram').value = cliente.instagram || '';
        document.getElementById('c_etapa_funil').value = cliente.etapa_funil || '';
        document.getElementById('c_responsavel_id').value = cliente.responsavel_id || '';
        document.getElementById('c_data_ultimo_contato').value = cliente.data_ultimo_contato || '';
        document.getElementById('c_proxima_acao').value = cliente.proxima_acao || '';
        document.getElementById('c_data_proxima_acao').value = cliente.data_proxima_acao || '';
        document.getElementById('c_valor_potencial').value = cliente.valor_potencial || '';
        document.getElementById('c_principal_necessidade').value = cliente.principal_necessidade || '';
        document.getElementById('c_objecao').value = cliente.objecao || '';
        document.getElementById('c_observacoes').value = cliente.observacoes || '';
        document.getElementById('form-cliente').action = '/clientes/' + cliente.id;
      }

      function abrirClientePorId(id) {
        var cliente = clientesData[id];
        if (!cliente) return;
        preencherCliente(cliente);
        abrirModal('modal-cliente');
      }

      document.querySelectorAll('.abrir-cliente').forEach(function (btn) {
        btn.addEventListener('click', function () { abrirClientePorId(btn.getAttribute('data-id')); });
      });

      var checklistAtualId = null;

      function selecionarAba(etapa) {
        document.querySelectorAll('.chk-tab-btn').forEach(function (b) {
          b.classList.toggle('active', b.getAttribute('data-etapa') === etapa);
        });
        document.querySelectorAll('.chk-tab-pane').forEach(function (p) {
          p.classList.toggle('active', p.getAttribute('data-etapa') === etapa);
        });
      }

      function abrirChecklistPorId(id, etapaInicial) {
        var cliente = clientesData[id];
        if (!cliente) return;
        checklistAtualId = id;
        document.getElementById('chk-empresa').textContent = cliente.empresa;

        document.querySelectorAll('.chk-form').forEach(function (form) {
          var etapa = form.getAttribute('data-etapa');
          form.action = '/clientes/' + id + '/checklist/' + etapa;
          var dados = (cliente.checklists && cliente.checklists[etapa]) || {};
          form.querySelectorAll('.chk-field').forEach(function (field) {
            var campo = field.getAttribute('data-campo');
            field.value = dados[campo] || '';
          });
        });

        selecionarAba(etapaInicial || 'prospeccao');
        abrirModal('modal-checklist');
      }

      document.querySelectorAll('.abrir-checklist').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var id = btn.getAttribute('data-id');
          var cliente = clientesData[id];
          abrirChecklistPorId(id, cliente ? cliente.etapa_funil : null);
        });
      });

      document.querySelectorAll('.chk-tab-btn').forEach(function (btn) {
        btn.addEventListener('click', function () { selecionarAba(btn.getAttribute('data-etapa')); });
      });

      if (abrirCliente && abrirChecklist) {
        abrirChecklistPorId(abrirCliente, abrirChecklist);
      } else if (abrirCliente) {
        abrirClientePorId(abrirCliente);
      }
    })();
  </script>
@endsection

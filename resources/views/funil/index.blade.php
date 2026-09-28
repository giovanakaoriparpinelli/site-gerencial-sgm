@extends('layouts.app')

@section('title', 'Funil de Vendas')

@section('content')
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card">
      <p style="margin: 0; font-size: 0.8rem; color: var(--ink-soft);">Clientes no funil</p>
      <p style="margin: 6px 0 0; font-size: 1.8rem; font-weight: 700;">{{ $totalClientes }}</p>
    </div>
    <div class="card">
      <p style="margin: 0; font-size: 0.8rem; color: var(--ink-soft);">Valor potencial em aberto</p>
      <p style="margin: 6px 0 0; font-size: 1.8rem; font-weight: 700;">R$ {{ number_format($valorPotencialAberto ?? 0, 2, ',', '.') }}</p>
    </div>
  </div>

  <div class="card">
    <h2 style="margin: 0 0 14px; font-size: 1rem;">SGM Empresarial — Visão do funil</h2>
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
        <thead>
          <tr style="text-align: left;">
            <th style="padding: 10px 12px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">Etapa</th>
            <th style="padding: 10px 12px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">Objetivo</th>
            <th style="padding: 10px 12px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">Próxima ação típica</th>
            <th style="padding: 10px 12px; border-bottom: 1px solid var(--border); color: var(--ink-soft); text-align: right;">Quantidade</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($etapas as $chave => $etapa)
            <tr>
              <td style="padding: 10px 12px; border-bottom: 1px solid var(--border); font-weight: 600;">{{ $etapa['label'] }}</td>
              <td style="padding: 10px 12px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">{{ $etapa['objetivo'] }}</td>
              <td style="padding: 10px 12px; border-bottom: 1px solid var(--border); color: var(--ink-soft);">{{ $etapa['proxima_acao'] }}</td>
              <td style="padding: 10px 12px; border-bottom: 1px solid var(--border); text-align: right;">
                <a href="{{ route('clientes.index', ['etapa' => $chave]) }}" style="color: var(--accent); font-weight: 700;">
                  {{ $contagem[$chave] ?? 0 }}
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

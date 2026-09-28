@extends('layouts.app')

@section('title', 'Atas e Agendas')

@section('content')
  <div class="card" style="margin-bottom: 24px;">
    <details>
      <summary style="cursor: pointer; font-size: 1rem; font-weight: 700; list-style: none;">+ Adicionar ata ou agenda</summary>
      <form method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data" style="margin-top: 16px;">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 12px;">
          <div>
            <label class="field-label">Tipo</label>
            <select name="tipo" class="field" required>
              <option value="ata">Ata de sessão</option>
              <option value="agenda">Agenda diária</option>
            </select>
          </div>
          <div>
            <label class="field-label">Data</label>
            <input type="date" name="data" class="field" required value="{{ old('data', now()->format('Y-m-d')) }}">
          </div>
          <div>
            <label class="field-label">Título</label>
            <input type="text" name="titulo" class="field" required value="{{ old('titulo') }}">
          </div>
        </div>
        <label class="field-label">Conteúdo (Markdown) — cole aqui ou envie um arquivo .md abaixo</label>
        <textarea name="conteudo" rows="6" class="field" style="margin-bottom: 12px;">{{ old('conteudo') }}</textarea>
        <label class="field-label">Ou enviar arquivo .md</label>
        <input type="file" name="arquivo" accept=".md,.markdown,.txt" class="field" style="margin-bottom: 14px;">
        <button type="submit" class="btn-primary">Salvar documento</button>
      </form>
    </details>
  </div>

  <div class="card" style="margin-bottom: 24px;">
    <form method="GET" action="{{ route('documentos.index') }}" style="display: flex; gap: 12px; align-items: end; flex-wrap: wrap;">
      <div>
        <label class="field-label">Mês</label>
        <select name="mes" class="field" onchange="this.form.submit()">
          @forelse ($meses as $m)
            <option value="{{ $m }}" @selected($m === $mesAtual)>
              {{ \Illuminate\Support\Carbon::createFromFormat('Y-m', $m)->translatedFormat('F \d\e Y') }}
            </option>
          @empty
            <option value="{{ $mesAtual }}">{{ \Illuminate\Support\Carbon::createFromFormat('Y-m', $mesAtual)->translatedFormat('F \d\e Y') }}</option>
          @endforelse
        </select>
      </div>
    </form>
  </div>

  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
    <div class="card">
      <h2 style="margin: 0 0 14px; font-size: 1rem;">Agendas diárias</h2>
      @forelse ($agendas as $doc)
        <a href="{{ route('documentos.show', $doc) }}" style="display: block; padding: 10px 0; border-bottom: 1px solid var(--border); color: var(--ink);">
          <span style="font-weight: 600;">{{ $doc->titulo }}</span>
          <span style="display: block; font-size: 0.78rem; color: var(--ink-faint);">{{ $doc->data->format('d/m/Y') }}</span>
        </a>
      @empty
        <p style="color: var(--ink-faint); margin: 0; font-size: 0.9rem;">Nenhuma agenda cadastrada ainda.</p>
      @endforelse
    </div>

    <div class="card">
      <h2 style="margin: 0 0 14px; font-size: 1rem;">Atas de sessão</h2>
      @forelse ($atas as $doc)
        <a href="{{ route('documentos.show', $doc) }}" style="display: block; padding: 10px 0; border-bottom: 1px solid var(--border); color: var(--ink);">
          <span style="font-weight: 600;">{{ $doc->titulo }}</span>
          <span style="display: block; font-size: 0.78rem; color: var(--ink-faint);">{{ $doc->data->format('d/m/Y') }}</span>
        </a>
      @empty
        <p style="color: var(--ink-faint); margin: 0; font-size: 0.9rem;">Nenhuma ata cadastrada ainda.</p>
      @endforelse
    </div>
  </div>
@endsection

@extends('layouts.app')

@section('title', $documento->titulo)

@section('head')
<style>
  .doc h1 { font-size: 1.7rem; font-weight: 800; letter-spacing: -0.01em; margin: 0 0 0.3em; }
  .doc h2 { font-size: 1.25rem; font-weight: 800; margin: 1.6em 0 0.5em; padding-top: 0.6em; border-top: 1px solid var(--border); }
  .doc h2:first-of-type { border-top: none; padding-top: 0; }
  .doc h3 { font-size: 1.02rem; font-weight: 700; margin: 1.4em 0 0.4em; }
  .doc p { color: var(--ink-soft); margin: 0 0 1em; }
  .doc strong { color: var(--ink); }
  .doc a { color: var(--accent); }
  .doc code { background: var(--surface-2); border: 1px solid var(--border); padding: 1px 6px; font-size: 0.86em; font-family: ui-monospace, Menlo, monospace; border-radius: 4px; }
  .doc pre.code { background: #14051f; color: #fff; padding: 16px 18px; overflow-x: auto; font-size: 0.82rem; line-height: 1.55; border-radius: 10px; }
  .doc pre.code code { background: none; border: none; padding: 0; color: inherit; }
  .doc blockquote { border-left: 3px solid var(--accent); margin: 0 0 1.4em; padding: 4px 0 4px 16px; color: var(--ink-soft); font-weight: 600; }
  .doc hr { border: none; border-top: 1px solid var(--border); margin: 2.2em 0; }
  .doc ul, .doc ol { padding-left: 1.3em; margin: 0 0 1.1em; color: var(--ink-soft); }
  .doc li { margin-bottom: 6px; }
  .doc li.task { list-style: none; margin-left: -1.3em; display: flex; gap: 10px; align-items: flex-start; }
  .doc li.task .box { width: 15px; height: 15px; border: 1.5px solid var(--ink-soft); border-radius: 4px; flex-shrink: 0; margin-top: 3px; }
  .doc li.task.done .box { background: var(--gradient); border-color: transparent; }
  .doc li.task.done { color: var(--ink-faint); text-decoration: line-through; }
  .doc .table-wrap { overflow-x: auto; margin: 0 0 1.4em; }
  .doc table { border-collapse: collapse; width: 100%; font-size: 0.87rem; }
  .doc th, .doc td { border: 1px solid var(--border); padding: 9px 12px; text-align: left; vertical-align: top; }
  .doc th { background: var(--surface-2); font-weight: 700; color: var(--ink); }
  .doc pre.mermaid { background: var(--surface-2); border: 1px solid var(--border); padding: 20px; margin: 0 0 1.4em; text-align: center; border-radius: 10px; }
</style>
@endsection

@section('content')
  <a href="{{ route('documentos.index') }}" style="display: inline-block; margin-bottom: 18px; font-size: 0.84rem; color: var(--ink-faint);">&larr; Voltar a atas e agendas</a>

  <div class="card doc">
    <p style="text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.72rem; color: var(--ink-faint); margin: 0 0 8px;">
      {{ $documento->tipo === 'ata' ? 'Ata de sessão' : 'Agenda diária' }} — {{ $documento->data->format('d/m/Y') }}
    </p>
    {!! \App\Support\Markdown::toHtml($documento->conteudo) !!}
  </div>

  <div style="display: flex; gap: 10px; margin-top: 18px; flex-wrap: wrap;">
    <a href="{{ route('documentos.edit', $documento) }}" class="btn-ghost" style="display: inline-block;">Editar documento</a>
    <form method="POST" action="{{ route('documentos.destroy', $documento) }}" onsubmit="return confirm('Remover este documento? Esta ação não pode ser desfeita.');">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn-ghost">Excluir documento</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
  <script>
    if (window.mermaid) {
      mermaid.initialize({
        startOnLoad: true,
        theme: "base",
        themeVariables: {
          primaryColor: "#a855f7",
          primaryTextColor: "#ffffff",
          primaryBorderColor: "#a855f7",
          lineColor: "#cbd0dc",
          secondaryColor: "#391a6b",
          tertiaryColor: "#1c0a3a",
          fontFamily: "Manrope, sans-serif"
        }
      });
    }
  </script>
@endsection

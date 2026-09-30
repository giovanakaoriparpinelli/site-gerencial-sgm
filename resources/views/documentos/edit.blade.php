@extends('layouts.app')

@section('title', 'Editar documento')

@section('content')
  <a href="{{ route('documentos.show', $documento) }}" style="display: inline-block; margin-bottom: 18px; font-size: 0.84rem; color: var(--ink-faint);">&larr; Voltar ao documento</a>

  <div class="card">
    <h2 style="margin: 0 0 16px; font-size: 1rem;">Editar documento</h2>
    <form method="POST" action="{{ route('documentos.update', $documento) }}">
      @csrf
      @method('PUT')
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 12px;">
        <div>
          <label class="field-label">Tipo</label>
          <select name="tipo" class="field" required>
            <option value="ata" @selected(old('tipo', $documento->tipo) === 'ata')>Ata de sessão</option>
            <option value="agenda" @selected(old('tipo', $documento->tipo) === 'agenda')>Agenda diária</option>
          </select>
        </div>
        <div>
          <label class="field-label">Data</label>
          <input type="date" name="data" class="field" required value="{{ old('data', $documento->data->format('Y-m-d')) }}">
        </div>
        <div>
          <label class="field-label">Título</label>
          <input type="text" name="titulo" class="field" required value="{{ old('titulo', $documento->titulo) }}">
        </div>
      </div>
      <label class="field-label">Conteúdo (Markdown)</label>
      <textarea name="conteudo" rows="22" class="field" style="margin-bottom: 14px; font-family: ui-monospace, Menlo, monospace;" required>{{ old('conteudo', $documento->conteudo) }}</textarea>
      <button type="submit" class="btn-primary">Salvar alterações</button>
    </form>
  </div>
@endsection

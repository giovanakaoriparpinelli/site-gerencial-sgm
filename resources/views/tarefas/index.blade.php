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

    @include('tarefas._lista', ['tarefas' => $tarefas, 'usuarios' => $usuarios])
  </div>
@endsection

@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
  <div class="card" style="margin-bottom: 24px;">
    <details>
      <summary style="cursor: pointer; font-size: 1rem; font-weight: 700; list-style: none;">+ Novo funcionário</summary>
      <form method="POST" action="{{ route('usuarios.store') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-top: 16px; align-items: end;">
        @csrf
        <div>
          <label class="field-label">Nome *</label>
          <input type="text" name="name" required class="field">
        </div>
        <div>
          <label class="field-label">E-mail *</label>
          <input type="email" name="email" required class="field">
        </div>
        <button type="submit" class="btn-primary">Cadastrar</button>
      </form>
      <p style="margin: 10px 0 0; font-size: 0.78rem; color: var(--ink-faint);">Uma senha temporária é gerada automaticamente e mostrada uma única vez após o cadastro — repasse ao funcionário e peça para trocar em Perfil no primeiro login.</p>
    </details>
  </div>

  <div class="card">
    <h2 style="margin: 0 0 14px; font-size: 1rem;">Funcionários ({{ $usuarios->count() }})</h2>

    @forelse ($usuarios as $usuario)
      <div style="display: flex; align-items: center; gap: 12px; padding: 12px 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--border);' : '' }}">
        <div style="flex: 1;">
          <p style="margin: 0; font-weight: 600;">{{ $usuario->name }}</p>
          <p style="margin: 2px 0 0; font-size: 0.82rem; color: var(--ink-faint);">{{ $usuario->email }}</p>
        </div>

        <details style="position: relative;">
          <summary class="icon-btn" style="list-style: none; cursor: pointer;" title="Editar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m16.5 3.5 4 4L8 20H4v-4Z"/></svg>
          </summary>
          <div style="position: absolute; right: 0; z-index: 10; margin-top: 8px; width: 300px; background: #241247; border: 1px solid var(--border); border-radius: 12px; padding: 16px;">
            <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
              @csrf
              @method('PUT')
              <label class="field-label">Nome</label>
              <input type="text" name="name" value="{{ $usuario->name }}" required class="field" style="margin-bottom: 10px;">
              <label class="field-label">E-mail</label>
              <input type="email" name="email" value="{{ $usuario->email }}" required class="field" style="margin-bottom: 14px;">
              <button type="submit" class="btn-primary" style="width: 100%;">Salvar</button>
            </form>
          </div>
        </details>

        @if ($usuario->id !== auth()->id())
          <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" onsubmit="return confirm('Remover {{ $usuario->name }}? As tarefas criadas por essa pessoa também serão removidas.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="icon-btn" title="Excluir">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M9 7V4h6v3m-8 0 1 13h8l1-13"/></svg>
            </button>
          </form>
        @endif
      </div>
    @empty
      <p style="color: var(--ink-faint); margin: 0; font-size: 0.9rem;">Nenhum funcionário cadastrado.</p>
    @endforelse
  </div>
@endsection

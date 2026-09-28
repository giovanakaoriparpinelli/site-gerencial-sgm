<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $busca = trim((string) $request->get('busca', ''));
        $status = $request->get('status', 'pendentes');

        $query = Task::query()->with(['criador', 'responsavel']);

        if ($busca !== '') {
            $query->where(function ($q) use ($busca) {
                $q->where('titulo', 'like', "%{$busca}%")
                    ->orWhere('descricao', 'like', "%{$busca}%");
            });
        }

        if ($status === 'pendentes') {
            $query->where('concluida', false);
        } elseif ($status === 'concluidas') {
            $query->where('concluida', true);
        }

        $tarefas = $query->orderBy('concluida')->orderBy('data_prevista')->get();

        return view('tarefas.index', [
            'tarefas' => $tarefas,
            'busca' => $busca,
            'status' => $status,
            'usuarios' => User::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'data_prevista' => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $data['created_by'] = $request->user()->id;

        Task::create($data);

        return back()->with('status', 'Tarefa adicionada.');
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'data_prevista' => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $task->update($data);

        return back()->with('status', 'Tarefa atualizada.');
    }

    public function concluir(Task $task): RedirectResponse
    {
        $task->update([
            'concluida' => ! $task->concluida,
            'concluida_em' => ! $task->concluida ? now() : null,
        ]);

        return back();
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return back()->with('status', 'Tarefa removida.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $tarefas = Task::with(['criador', 'responsavel'])
            ->where('concluida', false)
            ->whereDate('data_prevista', '<=', today())
            ->orderBy('data_prevista')
            ->get();

        // Todas as agendas de hoje (pode haver mais de uma). Sem nenhuma, cai na
        // agenda mais recente já cadastrada, sinalizada como "anterior" na tela.
        $agendas = Document::where('tipo', 'agenda')->whereDate('data', today())->orderBy('id')->get();
        $agendaAnterior = false;

        if ($agendas->isEmpty()) {
            $ultima = Document::where('tipo', 'agenda')->whereDate('data', '<', today())->orderByDesc('data')->orderByDesc('id')->first();
            $agendas = $ultima ? collect([$ultima]) : collect();
            $agendaAnterior = $ultima !== null;
        }

        return view('dashboard', [
            'tarefas' => $tarefas,
            'usuarios' => User::orderBy('name')->get(),
            'hoje' => today(),
            'agendas' => $agendas,
            'agendaAnterior' => $agendaAnterior,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $tarefas = Task::where(function ($q) {
                $q->where('concluida', false)->whereDate('data_prevista', '<=', today());
            })
            ->orWhere(function ($q) {
                $q->where('concluida', true)->whereDate('concluida_em', today());
            })
            ->orderBy('concluida')
            ->orderBy('data_prevista')
            ->get();

        $agendaHoje = Document::where('tipo', 'agenda')->whereDate('data', today())->first();

        return view('dashboard', [
            'tarefas' => $tarefas,
            'hoje' => today(),
            'agendaHoje' => $agendaHoje,
            'pendenciasAgenda' => $agendaHoje ? $agendaHoje->itensChecklist() : [],
        ]);
    }
}

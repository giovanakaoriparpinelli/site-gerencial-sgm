<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Support\Funil;
use Illuminate\View\View;

class FunilController extends Controller
{
    public function index(): View
    {
        $contagem = Client::query()
            ->selectRaw('etapa_funil, count(*) as total')
            ->groupBy('etapa_funil')
            ->pluck('total', 'etapa_funil');

        $valorPotencialAberto = Client::query()
            ->whereNotIn('etapa_funil', ['onboarding', 'posvenda', 'recusa'])
            ->sum('valor_potencial');

        return view('funil.index', [
            'etapas' => Funil::etapas(),
            'contagem' => $contagem,
            'totalClientes' => Client::count(),
            'valorPotencialAberto' => $valorPotencialAberto,
        ]);
    }
}

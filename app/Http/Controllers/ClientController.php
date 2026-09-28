<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientChecklist;
use App\Models\User;
use App\Support\Funil;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $busca = trim((string) $request->get('busca', ''));
        $etapa = $request->get('etapa', '');

        $query = Client::query()->with(['responsavel', 'checklists']);

        if ($busca !== '') {
            $query->where(function ($q) use ($busca) {
                $q->where('empresa', 'like', "%{$busca}%")
                    ->orWhere('segmento', 'like', "%{$busca}%")
                    ->orWhere('whatsapp', 'like', "%{$busca}%");
            });
        }

        if ($etapa !== '' && array_key_exists($etapa, Funil::etapas())) {
            $query->where('etapa_funil', $etapa);
        }

        $clientes = $query->orderByDesc('updated_at')->get();

        return view('clientes.index', [
            'clientes' => $clientes,
            'busca' => $busca,
            'etapaFiltro' => $etapa,
            'etapas' => Funil::etapas(),
            'usuarios' => User::orderBy('name')->get(),
            'abrirCliente' => (int) $request->get('cliente', 0),
            'abrirChecklist' => (string) $request->get('checklist', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validarCliente($request);

        Client::create($data);

        return back()->with('status', 'Cliente cadastrado.');
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $data = $this->validarCliente($request);

        $client->update($data);

        return back()->with('status', 'Cliente atualizado.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return back()->with('status', 'Cliente removido.');
    }

    public function salvarChecklist(Request $request, Client $client, string $etapa): RedirectResponse
    {
        abort_unless(array_key_exists($etapa, Funil::etapas()), 404);

        $campos = array_keys(Funil::campos($etapa));
        $dados = [];
        foreach ($campos as $campo) {
            $dados[$campo] = $request->input($campo, '');
        }

        ClientChecklist::updateOrCreate(
            ['client_id' => $client->id, 'etapa' => $etapa],
            ['dados' => $dados]
        );

        return redirect()->route('clientes.index', ['cliente' => $client->id, 'checklist' => $etapa])
            ->with('status', 'Checklist salvo.');
    }

    private function validarCliente(Request $request): array
    {
        $data = $request->validate([
            'empresa' => ['required', 'string', 'max:255'],
            'segmento' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'etapa_funil' => ['required', 'string', 'in:'.implode(',', Funil::chaves())],
            'data_ultimo_contato' => ['nullable', 'date'],
            'proxima_acao' => ['nullable', 'string', 'max:255'],
            'data_proxima_acao' => ['nullable', 'date'],
            'valor_potencial' => ['nullable', 'numeric'],
            'principal_necessidade' => ['nullable', 'string'],
            'objecao' => ['nullable', 'string'],
            'observacoes' => ['nullable', 'string'],
            'responsavel_id' => ['nullable', 'exists:users,id'],
        ]);

        return $data;
    }
}

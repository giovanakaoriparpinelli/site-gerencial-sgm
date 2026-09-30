<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $meses = Document::query()
            ->orderByDesc('data')
            ->pluck('data')
            ->map(fn ($data) => $data->format('Y-m'))
            ->unique()
            ->values();

        $mes = $request->get('mes', $meses->first() ?? now()->format('Y-m'));
        [$ano, $mesNumero] = array_pad(explode('-', $mes), 2, null);

        return view('documentos.index', [
            'atas' => Document::where('tipo', 'ata')->whereYear('data', $ano)->whereMonth('data', $mesNumero)->orderByDesc('data')->orderByDesc('id')->get(),
            'agendas' => Document::where('tipo', 'agenda')->whereYear('data', $ano)->whereMonth('data', $mesNumero)->orderByDesc('data')->orderByDesc('id')->get(),
            'meses' => $meses,
            'mesAtual' => $mes,
        ]);
    }

    public function show(Document $documento): View
    {
        return view('documentos.show', ['documento' => $documento]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tipo' => ['required', 'in:ata,agenda'],
            'data' => ['required', 'date'],
            'titulo' => ['required', 'string', 'max:255'],
            'conteudo' => ['nullable', 'string'],
            'arquivo' => ['nullable', 'file', 'max:10240'],
        ]);

        $conteudo = $data['conteudo'] ?? '';

        if ($request->hasFile('arquivo')) {
            $conteudo = file_get_contents($request->file('arquivo')->getRealPath());
        }

        if (trim($conteudo) === '') {
            return back()->withErrors(['conteudo' => 'Cole o conteúdo em Markdown ou envie um arquivo .md.'])->withInput();
        }

        $documento = Document::create([
            'tipo' => $data['tipo'],
            'data' => $data['data'],
            'titulo' => $data['titulo'],
            'conteudo' => $conteudo,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('documentos.show', $documento)->with('status', 'Documento adicionado.');
    }

    public function edit(Document $documento): View
    {
        return view('documentos.edit', ['documento' => $documento]);
    }

    public function update(Request $request, Document $documento): RedirectResponse
    {
        $data = $request->validate([
            'tipo' => ['required', 'in:ata,agenda'],
            'data' => ['required', 'date'],
            'titulo' => ['required', 'string', 'max:255'],
            'conteudo' => ['required', 'string'],
        ]);

        $documento->update($data);

        return redirect()->route('documentos.show', $documento)->with('status', 'Documento atualizado.');
    }

    public function alternarChecklist(Document $documento, int $indice): RedirectResponse
    {
        $documento->alternarItemChecklist($indice);

        return back();
    }

    public function destroy(Document $documento): RedirectResponse
    {
        $documento->delete();

        return redirect()->route('documentos.index')->with('status', 'Documento removido.');
    }
}

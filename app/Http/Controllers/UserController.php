<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('gerencial.usuarios', [
            'usuarios' => User::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
        ]);

        $senhaTemporaria = Str::password(10, symbols: false);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($senhaTemporaria),
        ]);

        return back()->with('status', "Funcionário cadastrado. Senha temporária: {$senhaTemporaria} — repasse e peça para trocar em Perfil no primeiro login.");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($data);

        return back()->with('status', 'Funcionário atualizado.');
    }

    public function resetarSenha(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['usuario' => 'Para trocar a sua própria senha, use Perfil.']);
        }

        $senhaTemporaria = Str::password(10, symbols: false);

        $user->forceFill([
            'password' => Hash::make($senhaTemporaria),
            'remember_token' => null,
        ])->save();

        return back()->with('status', "Senha de {$user->name} redefinida. Senha temporária: {$senhaTemporaria} — repasse e peça para trocar em Perfil no primeiro login.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['usuario' => 'Você não pode remover sua própria conta.']);
        }

        $user->delete();

        return back()->with('status', 'Funcionário removido.');
    }
}

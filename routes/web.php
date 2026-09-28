<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FunilController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/perfil', [ProfileController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('perfil.update');
    Route::put('/perfil/senha', [ProfileController::class, 'updatePassword'])->name('perfil.senha');

    Route::get('/tarefas', [TaskController::class, 'index'])->name('tarefas.index');
    Route::post('/tarefas', [TaskController::class, 'store'])->name('tarefas.store');
    Route::put('/tarefas/{task}', [TaskController::class, 'update'])->name('tarefas.update');
    Route::patch('/tarefas/{task}/concluir', [TaskController::class, 'concluir'])->name('tarefas.concluir');
    Route::delete('/tarefas/{task}', [TaskController::class, 'destroy'])->name('tarefas.destroy');

    Route::get('/clientes', [ClientController::class, 'index'])->name('clientes.index');
    Route::post('/clientes', [ClientController::class, 'store'])->name('clientes.store');
    Route::put('/clientes/{client}', [ClientController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{client}', [ClientController::class, 'destroy'])->name('clientes.destroy');
    Route::put('/clientes/{client}/checklist/{etapa}', [ClientController::class, 'salvarChecklist'])->name('clientes.checklist');
    Route::get('/clientes/{client}/exportar', [ClientController::class, 'exportar'])->name('clientes.exportar');

    Route::get('/funil', [FunilController::class, 'index'])->name('funil.index');

    Route::get('/gerencial/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::post('/gerencial/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::put('/gerencial/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/gerencial/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');

    Route::get('/documentos', [DocumentController::class, 'index'])->name('documentos.index');
    Route::post('/documentos', [DocumentController::class, 'store'])->name('documentos.store');
    Route::get('/documentos/{documento}', [DocumentController::class, 'show'])->name('documentos.show');
    Route::patch('/documentos/{documento}/checklist/{indice}', [DocumentController::class, 'alternarChecklist'])->name('documentos.checklist');
    Route::delete('/documentos/{documento}', [DocumentController::class, 'destroy'])->name('documentos.destroy');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;

// 1. Rota inicial abrindo direto na tela de login
Route::get('/', function () {
    return view('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/fazerLogin', 'App\Http\Controllers\UsuarioController@fazerLogin');
Route::get('/logout', 'App\Http\Controllers\UsuarioController@fazerLogOut');

// Cadastro público de usuário
Route::get('/inserir-usuario', function () {
    return view('insertUsuario');
});
Route::post('/criar-usuario', 'App\Http\Controllers\UsuarioController@insert');


// 2. Rotas Protegidas (Exigem login para acessar)
Route::middleware([Authenticate::class])->group(function () {

    // Tela inicial após logar (Tarefas)
    Route::get('/home', 'App\Http\Controllers\TarefaController@index');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('paineladministrativo/dashboard');
    });

    // Rotas de Usuário
    Route::get('/usuario', 'App\Http\Controllers\UsuarioController@index');
    Route::get('/enviar-usuario', 'App\Http\Controllers\UsuarioController@create');
    Route::put('/desativar-usuario/{id}', 'App\Http\Controllers\UsuarioController@desativar');
    Route::put('/ativar-usuario/{id}', 'App\Http\Controllers\UsuarioController@ativar');
    Route::put('/editar-usuario/{id}', 'App\Http\Controllers\UsuarioController@editar');
    Route::delete('/excluir-usuario/{id}', 'App\Http\Controllers\UsuarioController@excluir');

    // Rotas das Tarefas
    Route::get('/inserir-tarefa', 'App\Http\Controllers\TarefaController@tarefasSelect');
    Route::get('/enviar-tarefa', 'App\Http\Controllers\TarefaController@create');
    Route::post('/criar-tarefa', 'App\Http\Controllers\TarefaController@insert');
    Route::put('/concluir-tarefa/{id}', 'App\Http\Controllers\TarefaController@concluir');
    Route::put('/editar-tarefa/{id}', 'App\Http\Controllers\TarefaController@editar');
    Route::put('/desfazer-tarefa/{id}', 'App\Http\Controllers\TarefaController@desfazer');
    Route::delete('/excluir-tarefa/{id}', 'App\Http\Controllers\TarefaController@excluir');

    // Rotas dos Projetos
    Route::get('/projeto', 'App\Http\Controllers\ProjetoController@index');
    Route::get('/enviar-projeto','App\Http\Controllers\ProjetoController@create');
    Route::post('/criar-projeto','App\Http\Controllers\ProjetoController@insert');
    Route::get('/inserir-projeto','App\Http\Controllers\ProjetoController@projeto_select');
    Route::put('/editar-projeto/{id}','App\Http\Controllers\ProjetoController@editar');
    Route::delete('/excluir-projeto/{id}','App\Http\Controllers\ProjetoController@excluir');

});
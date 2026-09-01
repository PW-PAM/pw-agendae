<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Tarefa;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarefaController extends Controller
{
    public function index(Request $request) {
        // Base da consulta com Left Join para não perder tarefas sem projeto/usuário
        $query = Tarefa::leftJoin('projeto', 'tarefa.projeto_id', '=', 'projeto.id')
            ->leftJoin('usuario', 'projeto.usuario_id', '=', 'usuario.id')
            ->select('tarefa.*', 'projeto.nome as projeto_nome', 'usuario.nome as usuario_nome');

        // 1. Filtro por Status (Pendentes / Concluídas)
        if ($request->filled('txfiltro')) {
            if ($request->txfiltro == 'pendentes') {
                $query->where('tarefa.status', '=', 'Pendente');
            } elseif ($request->txfiltro == 'concluidas') {
                $query->where('tarefa.status', '=', 'Concluída');
            }
        }

        // 2. Filtro por Intervalo de Data (Prazo / data_fim)
        if ($request->filled('data_inicio')) {
            $query->whereDate('tarefa.data_fim', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('tarefa.data_fim', '<=', $request->data_fim);
        }

        // 3. Filtro por Projeto / Categoria
        if ($request->filled('projeto_busca')) {
            $projetoBusca = $request->projeto_busca;
            $query->where('projeto.nome', 'like', "%{$projetoBusca}%");
        }

        // Executa a busca com os filtros aplicados
        $tarefas = $query->get();

        // Contadores para os Cards do Topo
        $tarefasTotais = Tarefa::count();
        $tarefasConcluidas = Tarefa::where('status', '=', 'Concluída')->count();
        $tarefasPendentes = Tarefa::where('status', '=', 'Pendente')->count();

        return view('home', compact('tarefas', 'tarefasTotais', 'tarefasPendentes', 'tarefasConcluidas'));
    }

    public function tarefasSelect(){
        $projeto = Projeto::all();
        return view('insertTarefa', compact('projeto'));
    }

    public function insert(Request $request){
        $tarefa = new Tarefa();
        $tarefa->titulo = $request->txNome;
        $tarefa->descricao = $request->txDesc;
        $tarefa->status = "Pendente";
        $tarefa->data_inicio = date('Y-m-d H:i:s');
        $tarefa->data_fim = $request->txData;
        $tarefa->projeto_id = $request->txProjeto;
        $tarefa->save();

        $projeto = Projeto::findOrFail($request->txProjeto);
        $contarTarefas = Tarefa::where('projeto_id', '=', $projeto->id)->count();
        $projeto->quantiaTarefas = $contarTarefas;
        $projeto->save();

        return redirect('/');
    }

    public function concluir(Request $request, string $id){
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->status = "Concluída";
        $tarefa->save();
        return redirect('/');
    }

    public function desfazer(Request $request, string $id){
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->status = "Pendente";
        $tarefa->save();
        return redirect('/');
    }

    public function editar(Request $request, string $id){
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->titulo = $request->txNome;
        $tarefa->descricao = $request->txDesc;
        $tarefa->data_fim = $request->txData;
        $tarefa->save();

        return redirect('/');
    }

    public function excluir(Request $request, string $id){
        $tarefa = Tarefa::findOrFail($id);
        $projeto = Projeto::findOrFail($tarefa->projeto_id);

        Tarefa::where('id', '=', $id)->delete();

        $contarTarefas = Tarefa::where('projeto_id', '=', $projeto->id)->count();
        $projeto->quantiaTarefas = $contarTarefas;
        $projeto->save();

        return redirect('/');
    }

    // API
    public function indexApi(){
        return Tarefa::leftJoin('projeto','tarefa.projeto_id','=','projeto.id')
            ->select('tarefa.*', 'projeto.nome as projeto_nome')
            ->get();
    }

    public function countsApi(){
        $tarefaCounts = Tarefa::count();
        $tarefasConcluidas = Tarefa::where('status', '=', 'Concluída')->count();
        $tarefasPendentes = Tarefa::where('status', '=', 'Pendente')->count();

        return response()->json([
            'tarefas_totais' => $tarefaCounts,
            'tarefas_concluidas' => $tarefasConcluidas,
            'tarefas_pendentes' => $tarefasPendentes
        ]);
    }

    public function insertAPI(Request $request){
        $tarefa = new Tarefa();
        $tarefa->titulo = $request->titulo;
        $tarefa->descricao = $request->descricao;
        $tarefa->status = "Pendente";
        $tarefa->data_inicio = date('Y-m-d H:i:s');
        $tarefa->data_fim = $request->dataFinal;
        $tarefa->projeto_id = $request->projeto_id;
        $tarefa->save();

        $projeto = Projeto::findOrFail($request->projeto_id);
        $contarTarefas = Tarefa::where('projeto_id', '=', $projeto->id)->count();
        $projeto->quantiaTarefas = $contarTarefas;
        $projeto->save();

        return response()->json($tarefa, 201);
    }

    public function atualizarAPI(Request $request, string $id){
        $validarDados = $request->validate([
            'titulo' => 'sometimes|min:3',
            'descricao' => 'sometimes|max:200',
            'status' => 'sometimes|in:Pendente,Concluída',
            'data_fim' => 'sometimes',
            'projeto_id' => 'sometimes|exists:projeto,id'
        ]);

        $tarefa = Tarefa::findOrFail($id);
        $tarefa->update($validarDados);

        return response()->json($tarefa, 200);
    }

    public function excluirAPI(Request $request, string $id){
        $tarefa = Tarefa::findOrFail($id);
        $projeto = Projeto::findOrFail($tarefa->projeto_id);

        Tarefa::where('id', '=', $id)->delete();

        $contarTarefas = Tarefa::where('projeto_id', '=', $projeto->id)->count();
        $projeto->quantiaTarefas = $contarTarefas;
        $projeto->save();
        
        return response()->json([
            'message' => "Tarefa excluída",
            'code' => 200
        ]);
    }
//ativade04/08
public function showAPI($id)
{
    $tarefa = Tarefa::find($id);

    if (!$tarefa) {
        return response()->json(['message' => 'Tarefa não encontrada'], 404);
    }

    return response()->json($tarefa, 200);
}
}
<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Models\Projeto;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Tarefas por status (Pizza)
        $tarefasPorStatus = Tarefa::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // 2. Tarefas por planejamento (Coluna)
        $tarefasPorProjeto = Tarefa::join('projeto', 'tarefa.projeto_id', '=', 'projeto.id')
            ->select('projeto.nome as projeto_nome', DB::raw('count(*) as total'))
            ->groupBy('projeto.nome')
            ->orderByDesc('total')
            ->get();

        // 3. Planejamentos por usuário (Barra)
        $projetosPorUsuario = Projeto::join('usuario', 'projeto.usuario_id', '=', 'usuario.id')
            ->select('usuario.nome as usuario_nome', DB::raw('count(*) as total'))
            ->groupBy('usuario.nome')
            ->orderByDesc('total')
            ->get();

        // 4. Últimas tarefas cadastradas (Tabela)
        $ultimasTarefas = Tarefa::leftJoin('projeto', 'tarefa.projeto_id', '=', 'projeto.id')
            ->select('tarefa.titulo', 'tarefa.status', 'tarefa.data_fim', 'projeto.nome as projeto_nome')
            ->orderByDesc('tarefa.created_at')
            ->limit(8)
            ->get();

        return view('paineladministrativo.dashboard', compact(
            'tarefasPorStatus',
            'tarefasPorProjeto',
            'projetosPorUsuario',
            'ultimasTarefas'
        ));
    }
}
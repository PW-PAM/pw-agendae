<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Models\Projeto;
use Carbon\Carbon;
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
        $projetosPorUsuario = Projeto::join('users', 'projeto.usuario_id', '=', 'users.id')
            ->select('users.name as usuario_nome', DB::raw('count(*) as total'))
            ->groupBy('users.name')
            ->orderByDesc('total')
            ->get();

        // 4. Tarefas por mês de prazo, empilhadas por status (Áreas empilhadas)
        $linhas = Tarefa::select(
                DB::raw("DATE_FORMAT(data_fim, '%Y-%m') as mes"),
                'status',
                DB::raw('count(*) as total')
            )
            ->whereNotNull('data_fim')
            ->groupBy('mes', 'status')
            ->orderBy('mes')
            ->get();

        $meses = $linhas->pluck('mes')->unique()->values();

        $tarefasPorMes = [
            'meses' => $meses
                ->map(fn($m) => Carbon::createFromFormat('Y-m', $m)->format('m/Y'))
                ->all(),
            'pendentes' => $meses
                ->map(fn($m) => (int) $linhas->where('mes', $m)->where('status', 'Pendente')->sum('total'))
                ->all(),
            'concluidas' => $meses
                ->map(fn($m) => (int) $linhas->where('mes', $m)->where('status', 'Concluída')->sum('total'))
                ->all(),
        ];

        return view('paineladministrativo.dashboard', compact(
            'tarefasPorStatus',
            'tarefasPorProjeto',
            'projetosPorUsuario',
            'tarefasPorMes'
        ));
    }
}
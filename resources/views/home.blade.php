<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgendaE</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Seu CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
</head>
<body>
    
    <div class="agenda-layout">
        <aside class="agenda-sidebar">
            <div class="brand-box">
                <img src="{{ asset('img/Simbolo.png') }}" alt="Logo AgendaE">
                <div class="brand-text">
                    <h1>AgendaE</h1>
                    <p>Organização do dia a dia</p>
                </div>
            </div>
            <nav class="agenda-nav">
                <a href="/home" class="active">Tarefas</a>
                <a href="/usuario">Usuários</a>
                <a href="/projeto">Planejamentos</a>
            </nav>
        </aside>

        <main class="agenda-main">
            <!-- HERO HERO SECTION -->
            <section class="hero-box">
                <div>
                    <h2>Listagem da agendaE</h2>
                    <p>Acompanhe suas tarefas cadastradas e organize sua rotina.</p>
                </div>
                <div class="top-actions">
                    <a href="/inserir-tarefa" class="btn-primary">+ Nova tarefa</a>
                </div>
            </section>

            <!-- CARDS DE RESUMO -->
            <section class="agenda-cards">
                <div class="agenda-card">
                    <span>Total de tarefas</span>
                    <strong>{{ $tarefasTotais }}</strong>
                </div>
                <div class="agenda-card">
                    <span>Tarefas pendentes</span>
                    <strong>{{ $tarefasPendentes }}</strong>
                </div>
                <div class="agenda-card">
                    <span>Tarefas concluídas</span>
                    <strong>{{ $tarefasConcluidas }}</strong>
                </div>
            </section>

            <!-- PAINEL DE CONSULTA E TABELA -->
            <section class="agenda-panel">
                
                <!-- BARRA DE PESQUISA E FILTROS -->
                <div class="panel-top" style="padding: 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <form method="GET" action="/" class="row g-3 align-items-end w-100 m-0">
                        
                        <!-- Filtro Por Status -->
                        <div class="col-md-2">
                            <label class="form-label font-weight-bold" style="font-size: 0.8rem; color: #475569;">Status</label>
                            <select name="txfiltro" class="form-control">
                                <option value="" {{ request('txfiltro') == '' ? 'selected' : '' }}>Todos os Status</option>
                                <option value="pendentes" {{ request('txfiltro') == 'pendentes' ? 'selected' : '' }}>Pendentes</option>
                                <option value="concluidas" {{ request('txfiltro') == 'concluidas' ? 'selected' : '' }}>Concluídas</option>
                            </select>
                        </div>

                        <!-- Data Início -->
                        <div class="col-md-2">
                            <label class="form-label font-weight-bold" style="font-size: 0.8rem; color: #475569;">De (Data):</label>
                            <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" class="form-control">
                        </div>

                        <!-- Data Fim -->
                        <div class="col-md-2">
                            <label class="form-label font-weight-bold" style="font-size: 0.8rem; color: #475569;">Até (Data):</label>
                            <input type="date" name="data_fim" value="{{ request('data_fim') }}" class="form-control">
                        </div>

                        <!-- Categoria / Projeto -->
                        <div class="col-md-3">
                            <label class="form-label font-weight-bold" style="font-size: 0.8rem; color: #475569;">Projeto</label>
                            <input type="text" name="projeto_busca" value="{{ request('projeto_busca') }}" placeholder="Ex: Matemática, Biologia..." class="form-control">
                        </div>

                        <!-- Botões de Ação -->
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn-primary style-btn-search" style="flex: 1; padding: 10px;">
                                Consultar
                            </button>
                            <a href="/" class="btn-secondary" style="padding: 10px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                                Limpar
                            </a>
                        </div>
                    </form>
                </div>

                <!-- TABELA DE TAREFAS -->
                <div class="table-wrapper">
                    <table class="agenda-table">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Projeto</th>
                                <th>Usuário</th>
                                <th>Status</th>
                                <th>Prazo</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tarefas as $t)
                                <tr>
                                    <td>{{ $t->titulo }}</td>
                                    <td>{{ $t->projeto_nome }}</td>
                                    <td>{{ $t->usuario_nome }}</td>
                                    <td>
                                        <span class="status-badge {{ strtolower($t->status) == 'pendente' ? 'status-pendente' : 'status-concluida' }}">
                                            {{ $t->status }}
                                        </span>
                                    </td>
                                    <td>{{ $t->data_fim ? \Carbon\Carbon::parse($t->data_fim)->format('d/m/Y') : '' }}</td>
                                    
                                    @if(strtolower($t->status) == 'pendente')
                                    <td>
                                        <form method="POST" action="/concluir-tarefa/{{ $t->id }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="submit" value="Concluir" class="btn-primary-concluir">
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button" class="btn-primary-editar" data-bs-toggle="modal" data-bs-target="#modalEditar{{ $t->id }}">
                                            Editar
                                        </button>
                                    </td>
                                    @else
                                    <td>
                                        <form method="POST" action="/desfazer-tarefa/{{ $t->id }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="submit" value="Desfazer" class="btn-primary-desativar">
                                        </form>
                                    </td>
                                    <td>
                                        <input type="button" value="Editar" class="btn-primary-desabilitado" disabled>
                                    </td>
                                    @endif

                                    <td>
                                        <button type="button" class="btn-primary-excluir" data-bs-toggle="modal" data-bs-target="#modalexcluir{{ $t->id }}">
                                            Excluir
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" style="text-align: center; padding: 40px;">Nenhuma tarefa encontrada com esses filtros.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- MODAIS DE EDIÇÃO E EXCLUSÃO -->
    @foreach($tarefas as $t)
        <div class="modal fade" id="modalEditar{{ $t->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Tarefa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/editar-tarefa/{{ $t->id }}" method="POST" class="agenda-form">
                            @csrf
                            @method('PUT')
                            <div class="form-field mb-3">
                                <label class="form-label">Título:</label>
                                <input type="text" name="txNome" value="{{ $t->titulo }}" required class="form-control">
                            </div>
                            <div class="form-field mb-3">
                                <label class="form-label">Descrição:</label>
                                <textarea name="txDesc" required class="form-control">{{ $t->descricao }}</textarea>
                            </div>
                            <div class="form-field mb-3">
                                <label class="form-label">Prazo:</label>
                                <input type="date" name="txData" required value="{{ $t->data_fim ? \Carbon\Carbon::parse($t->data_fim)->format('Y-m-d') : '' }}" class="form-control">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn-primary">Salvar tarefa</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalexcluir{{ $t->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar Exclusão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">Você quer mesmo excluir essa tarefa?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form method="POST" action="/excluir-tarefa/{{ $t->id }}">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="Excluir" class="btn-primary-excluir">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planejamentos | AgendaE</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
                <a href="/home">Tarefas</a>
                <a href="/usuario">Usuários</a>
                <a href="/projeto" class="active">Planejamentos</a>
            </nav>
        </aside>

        <main class="agenda-main">
            <section class="hero-box">
                <div>
                    <h2>Planejamentos</h2>
                    <p>Visualize os Planejamentos criados no sistema.</p>
                </div>
                <div class="top-actions">
                    <a href="/inserir-projeto" class="btn-primary">Novo Planejamento</a>
                </div>
            </section>

            <section class="agenda-cards">
                <div class="agenda-card">
                    <span>Total de Planejamentos</span>
                    <strong>{{$projetoCount}}</strong>
                </div>
            </section>

            <section class="agenda-panel">
                <div class="panel-top">
                    <div>
                        <h3>Lista de Planejamentos</h3>
                        <p>Gerencie os Planejamentos existentes.</p>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table class="agenda-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Quantidade de Tarefas</th>
                                <th>Nome do Usuário</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projeto as $p)
                                <tr>
                                    <td>{{ $p->nome }}</td>
                                    <td>{{ $p->descricao }}</td>
                                    <td>
                                        <span class="status-badge {{ $p->quantiaTarefas == 0 ? 'status-pendente' : 'status-concluida' }}">
                                            {{ $p->quantiaTarefas }}
                                        </span>
                                    </td>
                                    <td>{{ $p->usuario_nome }}</td>
                                    <td>
                                        <button type="button" class="btn-primary-editar"
                                        data-toggle="modal"
                                        data-target="#modalEditar{{$p->id}}">
                                            Editar
                                        </button>
                                    </td>
                                    <td>
                                        @if($p->quantiaTarefas==0)
                                            <button type="button" class="btn-primary-excluir"
                                            data-toggle='modal'
                                            data-target="#modalexcluir{{$p->id}}">
                                                Excluir
                                            </button>
                                        @else
                                            <input type="button" value="Excluir" class="btn-primary-desabilitado" disabled>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal Editar -->
                                <div class="modal fade" id="modalEditar{{$p->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Projeto</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/editar-projeto/{{$p->id}}" method="post" class="agenda-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-field">
                                                        <label class="col-form-label">Titulo:</label>
                                                        <input type="text" id="txNome" name="txNome" placeholder="Digite o título do projeto" value="{{$p->nome}}" required class="form-control">
                                                    </div>
                                                    <div class="form-field">
                                                        <label class="col-form-label">Descrição:</label>
                                                        <textarea id="txDesc" name="txDesc" placeholder="Digite a descrição do projeto" required class="form-control">{{$p->descricao}}</textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn-primary">Salvar projeto</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Excluir -->
                                <div class="modal fade" id="modalexcluir{{$p->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmar Exclusão</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">Você quer mesmo excluir esse projeto?</div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn-secondary" data-dismiss="modal">Fechar</button>
                                                <form method="POST" action="/excluir-projeto/{{$p->id}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="submit" value="Excluir" class="btn-primary-excluir">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr><td colspan="6">Nenhum projeto criado ainda.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
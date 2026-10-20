<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Tarefa | AgendaE</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
            <section class="agenda-panel form-page">
                <div class="panel-top">
                    <div>
                        <h3>Nova tarefa</h3>
                        <p>Cadastre uma nova tarefa na agenda.</p>
                    </div>
                </div>
                <form action="/criar-tarefa" method="POST" class="agenda-form">
                    @csrf

                    @if ($errors->any())
                        <div class="form-errors">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-grid">
                        <div class="form-field full-width">
                            <label for="txNome">Título <span class="required">*</span></label>
                            <input type="text" id="txNome" name="txNome" placeholder="Digite o título da tarefa" value="{{ old('txNome') }}">
                        </div>
                        <div class="form-field full-width">
                            <label for="txDesc">Descrição <span class="required">*</span></label>
                            <textarea id="txDesc" name="txDesc" placeholder="Digite a descrição da tarefa">{{ old('txDesc') }}</textarea>
                        </div>
                        <div class="form-field">
                            <label for="txProjeto">Planejamentos <span class="required">*</span></label>
                            <select id="txProjeto" name="txProjeto">
                                <option value="">Selecionar Planejamento</option>
                                @foreach($projeto as $p)
                                    <option value="{{ $p->id }}" {{ old('txProjeto') == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="txData">Prazo <span class="required">*</span></label>
                            <input type="date" id="txData" name="txData" value="{{ old('txData') }}">
                        </div>
                    </div>
                    <div class="form-actions">
                        <a href="/home" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Salvar tarefa</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
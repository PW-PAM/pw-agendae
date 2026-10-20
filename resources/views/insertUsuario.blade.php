<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Usuário | AgendaE</title>
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
                <a href="/home">Tarefas</a>
                <a href="/usuario" class="active">Usuários</a>
                <a href="/projeto">Planejamentos</a>
            </nav>
        </aside>

        <main class="agenda-main">
            <section class="agenda-panel form-page">
                <div class="panel-top">
                    <div>
                        <h3>Novo usuário</h3>
                        <p>Cadastre um novo usuário na agenda.</p>
                    </div>
                </div>
                <form action="/criar-usuario" method="POST" class="agenda-form">
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
                            <label for="txNome">Nome <span class="required">*</span></label>
                            <input type="text" id="txNome" name="txNome" placeholder="Digite o nome do usuário" value="{{ old('txNome') }}">
                        </div>
                        <div class="form-field">
                            <label for="txEmail">E-mail <span class="required">*</span></label>
                            <input type="email" id="txEmail" name="txEmail" placeholder="Digite o e-mail" value="{{ old('txEmail') }}">
                        </div>
                        <div class="form-field">
                            <label for="txSenha">Senha <span class="required">*</span></label>
                            <input type="password" id="txSenha" name="txSenha" placeholder="Crie uma senha">
                        </div>
                    </div>
                    <div class="form-actions">
                        <a href="/usuario" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Cadastrar usuário</button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
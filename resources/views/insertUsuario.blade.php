<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Usuário | AgendaE</title>
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
                    <div class="form-grid">
                        <div class="form-field full-width">
                            <label for="txNome">Nome</label>
                            <input type="text" id="txNome" name="txNome" placeholder="Digite o nome do usuário" required>
                        </div>
                        <div class="form-field">
                            <label for="txEmail">E-mail</label>
                            <input type="email" id="txEmail" name="txEmail" placeholder="Digite o e-mail" required>
                        </div>
                        <div class="form-field">
                            <label for="txSenha">Senha</label>
                            <input type="password" id="txSenha" name="txSenha" placeholder="Crie uma senha" required>
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
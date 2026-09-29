<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="auth-page">
    <section class="auth-card">
        <div class="auth-card-header">
            <img src="{{ asset('img/Simbolo.png') }}" alt="AgendaE" class="auth-logo">
        </div>

        <div class="agenda-form">
            <form action="/fazerLogin" method="post">
                @csrf
                <div class="form-field">
                    <label for="">E-mail</label>
                    <input type="text" name="email">
                </div>
            
                <div class="form-field">
                    <label for="">Senha</label>
                    <input type="password" name="password">
                </div>
            
                <div class="form-actions">
                    <input type="submit" value="Entrar" class="btn-primary">
                </div>
            
                <div class="auth-footer">
                    <p>
                        Não tem uma conta?
                        <a href="/inserir-usuario"> Clique aqui para criar uma. </a>
                    </p>
                </div>
            </form>
        </div>
    </section>
    
</body>
</html>
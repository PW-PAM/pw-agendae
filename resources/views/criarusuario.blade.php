<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Usuário</title>
</head>
<body>

    <section>
        <div>
            <h1>Usuário</h1>
        </div>
    
        <div>
            <form action="/criar-usuario" method="post">
            @csrf
                <div>
                    <label for="">Nome</label>
                    <input type="text" name="txNome">
                </div>
            
                <div>
                    <label for="">E-mail</label>
                    <input type="text" name="txEmail">
                </div>
            
                <div>
                    <label for="">Senha</label>
                    <input type="password" name="txSenha">
                </div>
            
                <div>
                    <input type="submit" value="Criar">
                </div>
            </form>
        </div>
    </section>
  
</body>
</html>
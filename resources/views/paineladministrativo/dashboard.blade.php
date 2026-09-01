<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="dashboard-page">

    <section class="dashboard-header">
        <div class="dashboard-brand">
            <img src="{{ asset('img/Simbolo.png') }}" alt="AgendaE">
            <h1> Dashboard </h1>
        </div>
    </section>

    <section class="dashboard-actions">
        <a href="/logout" class="btn-secondary">Sair</a>
    </section>
    
</body>
</html>
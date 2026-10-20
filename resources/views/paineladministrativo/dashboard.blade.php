<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | AgendaE</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.0/dist/echarts.min.js"></script>
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
                <a href="/projeto">Planejamentos</a>
                <a href="/dashboard" class="active">Dashboard</a>
            </nav>
        </aside>

        <main class="agenda-main">
            <section class="hero-box">
                <div>
                    <h2>Dashboard</h2>
                    <p>Visão geral das tarefas, planejamentos e usuários cadastrados.</p>
                </div>
            </section>

            <section class="dashboard-charts-grid">
                <div class="agenda-panel dashboard-chart-card">
                    <div class="panel-top">
                        <div>
                            <h3>Tarefas por status</h3>
                            <p>Distribuição entre pendentes e concluídas.</p>
                        </div>
                    </div>
                    <div id="chart_status" class="dashboard-chart"></div>
                </div>

                <div class="agenda-panel dashboard-chart-card">
                    <div class="panel-top">
                        <div>
                            <h3>Tarefas por planejamento</h3>
                            <p>Quantidade de tarefas em cada planejamento.</p>
                        </div>
                    </div>
                    <div id="chart_projeto" class="dashboard-chart"></div>
                </div>

                <div class="agenda-panel dashboard-chart-card">
                    <div class="panel-top">
                        <div>
                            <h3>Planejamentos por usuário</h3>
                            <p>Quantos planejamentos cada usuário criou.</p>
                        </div>
                    </div>
                    <div id="chart_usuario" class="dashboard-chart"></div>
                </div>

                <div class="agenda-panel dashboard-chart-card">
                    <div class="panel-top">
                        <div>
                            <h3>Tarefas por mês de prazo</h3>
                            <p>Evolução das tarefas pendentes e concluídas ao longo dos meses.</p>
                        </div>
                    </div>
                    <div id="chart_tarefas_mes" class="dashboard-chart"></div>
                </div>
            </section>
        </main>
    </div>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawStatusChart);
        google.charts.setOnLoadCallback(drawProjetoChart);
        google.charts.setOnLoadCallback(drawUsuarioChart);

        function drawStatusChart() {
            var data = google.visualization.arrayToDataTable([
                ['Status', 'Quantidade'],
                @foreach($tarefasPorStatus as $s)
                    ['{{ $s->status }}', {{ $s->total }}],
                @endforeach
            ]);

            var options = {
                title: 'Tarefas por status',
                is3D: true,
                colors: ['#3686B7', '#10A19A']
            };

            var chart = new google.visualization.PieChart(document.getElementById('chart_status'));
            chart.draw(data, options);
        }

        function drawProjetoChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Planejamento');
            data.addColumn('number', 'Tarefas');
            data.addRows([
                @foreach($tarefasPorProjeto as $p)
                    ['{{ addslashes($p->projeto_nome) }}', {{ $p->total }}],
                @endforeach
            ]);

            var options = {
                legend: { position: 'none' },
                colors: ['#10A19A']
            };

            var chart = new google.charts.Bar(document.getElementById('chart_projeto'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        function drawUsuarioChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Usuário');
            data.addColumn('number', 'Planejamentos');
            data.addRows([
                @foreach($projetosPorUsuario as $u)
                    ['{{ addslashes($u->usuario_nome) }}', {{ $u->total }}],
                @endforeach
            ]);

            var options = {
                chart: { title: 'Planejamentos por usuário' },
                bars: 'horizontal',
                colors: ['#3686B7']
            };

            var chart = new google.charts.Bar(document.getElementById('chart_usuario'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        // ===== ECharts: Tarefas por mês de prazo (áreas empilhadas) =====
        var chartMes = echarts.init(document.getElementById('chart_tarefas_mes'));

        chartMes.setOption({
            title: { text: 'Tarefas por mês de prazo', textStyle: { fontSize: 14 } },
            color: ['#3686B7', '#10A19A'],
            tooltip: {
                trigger: 'axis',
                axisPointer: { type: 'cross', label: { backgroundColor: '#6a7985' } }
            },
            legend: { data: ['Pendentes', 'Concluídas'], right: 60 },
            toolbox: { feature: { saveAsImage: {} } },
            grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
            xAxis: [{
                type: 'category',
                boundaryGap: false,
                data: @json($tarefasPorMes['meses'])
            }],
            yAxis: [{ type: 'value', minInterval: 1 }],
            series: [
                {
                    name: 'Pendentes',
                    type: 'line',
                    stack: 'Total',
                    areaStyle: {},
                    emphasis: { focus: 'series' },
                    data: @json($tarefasPorMes['pendentes'])
                },
                {
                    name: 'Concluídas',
                    type: 'line',
                    stack: 'Total',
                    areaStyle: {},
                    emphasis: { focus: 'series' },
                    label: { show: true, position: 'top' },
                    data: @json($tarefasPorMes['concluidas'])
                }
            ]
        });

        window.addEventListener('resize', function () { chartMes.resize(); });
    </script>
</body>
</html>
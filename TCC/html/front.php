<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sensoriamento</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
    <style>
        body {
            background-color: #E0E0E0;
        }

        .content {
            margin-top: 30px;
        }

        nav {
            background-color: #008000;
        }

        .statistic {
            font-size: 5em;
        }

        .card {
            min-height: 150px;
            padding: 10px;
        }

        .activator {
            cursor: pointer;
        }

        @media screen and (max-width: 600px) {
            .statistic {
                font-size: 3em;
            }
        }

        /* Reduzindo o tamanho do canvas */
        #temperatureChart,
        #humidityChart,
        #soilHumidityChart {
            max-height: 200px;
        }

        .led-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .led-on {
            background-color: #27ae60;
            color: white;
        }

        .led-off {
            background-color: #e74c3c;
            color: white;
        }
    </style>

</head>

<body>
    <div class="navbar-fixed">
        <nav class="nav-extended">
            <div class="nav-wrapper">
                <a href="#" class="brand-logo center">Detalhes de Temperatura e Umidade</a>
            </div>
            <div class="nav-content center">
                <h6>Sensoriamento em Tempo Real</h6>
            </div>
        </nav>
    </div>
    <div class="container content">
        <br />
        <div class="row">
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-image center">
                        <span id="currentTemp" class="statistic">-</span>
                    </div>
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4">
                            Temperatura
                            <i class="material-icons right">more_vert</i>
                        </span>
                        Clique para ver histórico
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">
                            Histórico
                            <i class="material-icons right">close</i>
                        </span>
                        <canvas id="temperatureChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-image center">
                        <span id="currentUmid" class="statistic">-</span>
                    </div>
                    <div class="card-content activator">
                        <span class="card-title grey-text text-darken-4">
                            Umidade
                            <i class="material-icons right">more_vert</i>
                        </span>
                        Clique para ver histórico
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">
                            Histórico
                            <i class="material-icons right">close</i>
                        </span>
                        <canvas id="humidityChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-image center">
                        <span id="currentSoloUmid" class="statistic">-</span>
                    </div>
                    <div class="card-content activator">
                        <span class="card-title grey-text text-darken-4">
                            Umidade do solo
                            <i class="material-icons right">more_vert</i>
                        </span>
                        Clique para ver histórico
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">
                            Histórico
                            <i class="material-icons right">close</i>
                        </span>
                        <canvas id="soilHumidityChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card" style="padding-bottom: 20px;">
                    <div class="card-image center" style="padding-top: 20px;">
                        <div id="ledStatus" class="led-icon" style="transform: translateY(10px);"></div>
                    </div>
                    <div class="card-content">
                        <span class="card-title grey-text text-darken-4">
                            Lâmpada Led
                        </span>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4">
                            Cálculo da média
                            <i class="material-icons right">more_vert</i>
                        </span>
                        Clique para ver histórico
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4">

                            <i class="material-icons right">close</i>
                        </span>
                        <div class="content">
                            <p>Média da Temperatura: <span id="media_temperatura">-</span> °C</p>
                            <p>Média da Umidade do ar: <span id="media_umidade">-</span> %</p>
                            <p>Média da Umidade do solo: <span id="media_umidade_solo">-</span> %</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/live/3.0/firebase.js"></script>
    <script src="app.js"></script>
    <script>
        // Função para carregar os dados de temperatura, umidade e estado do LED
        function carregarDados() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var dados = JSON.parse(this.responseText);
                    document.getElementById("currentTemp").innerText = dados.temperatura + " °C";
                    document.getElementById("currentUmid").innerText = dados.umidade + " %";
                    document.getElementById("currentSoloUmid").innerText = dados.umidade_solo + " %";


                    // Atualizar estado do LED
                    atualizarLed(dados.led);

                    // Atualizar gráficos
                    atualizarGraficos(dados.temperatura, dados.umidade, dados.umidade_solo);


                }
            };
            xhttp.open("GET", "get_dados.php", true); // Arquivo PHP para obter os dados
            xhttp.send();
        }

        // Função para atualizar a média e a variância a cada 24 horas
        function atualizarMediaVariancia() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var dados = JSON.parse(this.responseText);

                    // Atualizar valores de média e variância
                    document.getElementById("media_temperatura").innerText = parseFloat(dados.media_temperatura).toFixed(2);
                    document.getElementById("media_umidade").innerText = parseFloat(dados.media_umidade).toFixed(2);
                    document.getElementById("media_umidade_solo").innerText = parseFloat(dados.media_umidade_solo).toFixed(2);
                    document.getElementById("variancia_temperatura").innerText = parseFloat(dados.variancia_temperatura).toFixed(2);
                    document.getElementById("variancia_umidade").innerText = parseFloat(dados.variancia_umidade).toFixed(2);
                    document.getElementById("variancia_umidade_solo").innerText = parseFloat(dados.variancia_umidade_solo).toFixed(2);
                }
            };
            xhttp.open("GET", "get_dados.php", true); // Arquivo PHP para obter os dados de média e variância
            xhttp.send();
        }


        // Função para atualizar o estado do LED
        function atualizarLed(led) {
            var ledStatus = document.getElementById("ledStatus");
            if (led === '1') {
                ledStatus.className = "led-icon led-on material-icons";
                ledStatus.innerHTML = "lightbulb";
            } else {
                ledStatus.className = "led-icon led-off material-icons";
                ledStatus.innerHTML = "lightbulb_outline";
            }
        }

        // Inicializar os gráficos
        var temperatureCtx = document.getElementById('temperatureChart').getContext('2d');
        var temperaturaChart = new Chart(temperatureCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Temperatura (°C)',
                    data: [],
                    fill: false,
                    backgroundColor: 'rgba(231, 76, 60, 0.2)',
                    borderColor: 'rgba(231, 76, 60, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var humidityCtx = document.getElementById('humidityChart').getContext('2d');
        var umidadeChart = new Chart(humidityCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Umidade (%)',
                    data: [],
                    fill: false,
                    backgroundColor: 'rgba(52, 152, 219, 0.2)',
                    borderColor: 'rgba(52, 152, 219, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var soilHumidityCtx = document.getElementById('soilHumidityChart').getContext('2d');
        var soloUmidadeChart = new Chart(soilHumidityCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Umidade do Solo (%)',
                    data: [],
                    fill: false,
                    backgroundColor: 'rgba(46, 204, 113, 0.2)',
                    borderColor: 'rgba(46, 204, 113, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        // Função para atualizar os gráficos com novos dados
        function atualizarGraficos(temperatura, umidade, umidadeSolo) {
            // Adicionar novos dados aos gráficos
            temperaturaChart.data.labels.push(new Date().toLocaleTimeString());
            temperaturaChart.data.datasets[0].data.push(temperatura);
            umidadeChart.data.labels.push(new Date().toLocaleTimeString());
            umidadeChart.data.datasets[0].data.push(umidade);
            soloUmidadeChart.data.labels.push(new Date().toLocaleTimeString());
            soloUmidadeChart.data.datasets[0].data.push(umidadeSolo);

            // Limitar o número de pontos no gráfico para manter a janela deslizante
            var maxDataPoints = 10;
            if (temperaturaChart.data.labels.length > maxDataPoints) {
                temperaturaChart.data.labels.shift();
                temperaturaChart.data.datasets[0].data.shift();
            }
            if (umidadeChart.data.labels.length > maxDataPoints) {
                umidadeChart.data.labels.shift();
                umidadeChart.data.datasets[0].data.shift();
            }
            if (soloUmidadeChart.data.labels.length > maxDataPoints) {
                soloUmidadeChart.data.labels.shift();
                soloUmidadeChart.data.datasets[0].data.shift();
            }

            // Atualizar os gráficos
            temperaturaChart.update();
            umidadeChart.update();
            soloUmidadeChart.update();
        }



        // Carregar os dados inicialmente e atualizar a cada segundo
        carregarDados();
        setInterval(carregarDados, 5000); // Atualizar a cada 5 segundos

        // Chamar a função para atualizar a média e a variância inicialmente
        atualizarMediaVariancia();
        // Atualização da média e da variância a cada 24 horas
        setInterval(atualizarMediaVariancia, 24 * 60 * 60 * 1000); // Atualizar a cada 24 horas
    </script>
</body>

</html>
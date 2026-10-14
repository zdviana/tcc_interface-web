<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'db_teste';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Função para calcular a média e a variância das últimas 24 horas
function calcularMediaVariancia($conn, $field)
{
    $sql = "SELECT AVG($field) as media, VARIANCE($field) as variancia FROM tb_teste WHERE TIMESTAMPDIFF(HOUR, data_hora, NOW()) <= 24";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return array('media' => null, 'variancia' => null);
    }
}

$sql = "SELECT * FROM tb_teste ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $dados_atuais = array(
        'temperatura' => $row['temperatura'],
        'umidade' => $row['umidade'],
        'umidade_solo' => $row['umidade_solo'],
        'led' => $row['led']
    );

    // Calcular média e variância para temperatura, umidade e umidade do solo
    $media_variancia_temperatura = calcularMediaVariancia($conn, 'temperatura');
    $media_variancia_umidade = calcularMediaVariancia($conn, 'umidade');
    $media_variancia_umidade_solo = calcularMediaVariancia($conn, 'umidade_solo');

    $dados_historicos = array(
        'media_temperatura' => isset($media_variancia_temperatura['media']) ? $media_variancia_temperatura['media'] : null,
        'variancia_temperatura' => isset($media_variancia_temperatura['variancia']) ? $media_variancia_temperatura['variancia'] : null,
        'media_umidade' => isset($media_variancia_umidade['media']) ? $media_variancia_umidade['media'] : null,
        'variancia_umidade' => isset($media_variancia_umidade['variancia']) ? $media_variancia_umidade['variancia'] : null,
        'media_umidade_solo' => isset($media_variancia_umidade_solo['media']) ? $media_variancia_umidade_solo['media'] : null,
        'variancia_umidade_solo' => isset($media_variancia_umidade_solo['variancia']) ? $media_variancia_umidade_solo['variancia'] : null
    );

    $dados = array_merge($dados_atuais, $dados_historicos);

    echo json_encode($dados);
} else {
    echo json_encode(array('error' => 'Nenhum dado encontrado'));
}

$conn->close();
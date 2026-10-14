<?php

$dbname = 'db_teste';
$dbuser = 'root';  
$dbpass = ''; 
$dbhost = 'localhost'; 

$connect = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$connect) {
    echo "Error: " . mysqli_connect_error();
    exit();
}

$temperatura = isset($_GET["temperatura"]) ? $_GET["temperatura"] : '';
$umidade = isset($_GET["umidade"]) ? $_GET["umidade"] : '';
$umidade_solo = isset($_GET["umidade_solo"]) ? $_GET["umidade_solo"] : '';
$led = isset($_GET["led"]) ? $_GET["led"] : '';

$query = "INSERT INTO tb_teste (temperatura, umidade, umidade_solo, led) VALUES ('$temperatura', '$umidade', '$umidade_solo', '$led')";
$result = mysqli_query($connect, $query);

if ($result) {
    echo "Inserido com Sucesso!";
} else {
    echo "Erro ao inserir dados: " . mysqli_error($connect);
}

?>

<?php
include 'dadosServidorBD.php';

header('access-control-allow-origin: *');
header('Access-Control-Allow-Headers: *');

// Dados recebidos do Arduino
$reservatorio = 1; // substitua pelo valor recebido do Arduino
$nivel = $_POST['nivel']; // substitua pela variável recebida do Arduino
$dataHoraMillis = $_POST['dataHora']; // substitua pela variável recebida do Arduino

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão foi estabelecida com sucesso
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Prepara a instrução SQL
$sql = "INSERT INTO leituraReservatorio (reservatorio, nivel, dataHora) VALUES (?, ?, ?)";

// Prepara a declaração SQL
$stmt = $conn->prepare($sql);

// Vincula os parâmetros à declaração SQL
$stmt->bind_param("iss", $reservatorio, $nivel, $dataHora);

// Executa a instrução SQL
if ($stmt->execute() === TRUE) {
    echo "Dados adicionados ao banco de dados com sucesso.";
} else {
    echo "Erro ao adicionar dados ao banco de dados: " . $conn->error;
}

// Fecha a declaração e a conexão com o banco de dados
$stmt->close();
$conn->close();
?>

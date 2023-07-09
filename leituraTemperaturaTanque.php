<?php

include 'dadosServidorBD.php';

header('access-control-allow-origin: *');
header('Access-Control-Allow-Headers: *');

// Obtém os dados enviados via POST
$data = json_decode(file_get_contents("php://input"), true);

// Verifica se os campos obrigatórios estão presentes
if (!isset($data['reservatorio']) || !isset($data['local_medicao']) || !isset($data['dataHora']) || !isset($data['temperatura'])) {
    $response = array('message' => 'Campos obrigatórios ausentes ltq');
    echo json_encode($response);
    exit;
}

// Cria uma conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $database);

// Verifica se ocorreu algum erro na conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Prepara a consulta SQL
$sql = "INSERT INTO leituraTemperatura (reservatorio, local_medicao, dataHora, temperatura) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Verifica se a preparação da consulta falhou
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conn->error);
}
$reservatorio = intval($data['reservatorio']);
$local_medicao = intval($data['local_medicao']);

// Vincula os parâmetros à consulta preparada
$stmt->bind_param("iiss", $reservatorio, $local_medicao, $data['dataHora'], $data['temperatura']);

// Executa a consulta
if ($stmt->execute()) {
    $response = array('message' => 'Dados inseridos com sucesso na tabela leituraTemperatura');
} else {
    $teste=$data['reservatorio'];
    $teste2=$data['local_medicao'];
    $teste3=$data['dataHora'];
    $teste4=$data['temperatura'];
    print("$teste\n");print("$teste2\n");print("$teste3\n");print("$teste4\n");
    $response = array('message' => 'Erro ao inserir dados: ' . $stmt->error);
}


// Retorna a resposta como JSON
header('Content-Type: application/json');
echo json_encode($response);


// Fecha a conexão com o banco de dados
$stmt->close();
$conn->close();

<?php

include 'dadosServidorBD.php';

header('access-control-allow-origin: *');
header('Access-Control-Allow-Headers: *');

// Obtém os dados enviados via POST
$data = json_decode(file_get_contents("php://input"), true);

// Verifica se os campos obrigatórios estão presentes
if (!isset($data['gerador']) || !isset($data['geracao']) || !isset($data['dataHora'])) {
    $response = array('message' => 'Campos obrigatorios ausentes');
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
$sql = "INSERT INTO leituraGeracaoEnergiaEletrica (gerador, geracao, dataHora) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

// Verifica se a preparação da consulta falhou
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conn->error);
}

// Vincula os parâmetros à consulta preparada
$stmt->bind_param("iss", $data['gerador'], $data['geracao'], $data['dataHora']);

// Executa a consulta
if ($stmt->execute()) {
    $response = array('message' => 'Dados inseridos com sucesso na tabela leituraGeracaoEnergiaEletrica');
} else {
    $response = array('message' => 'Erro ao inserir dados: ' . $stmt->error);
}


// Retorna a resposta como JSON
header('Content-Type: application/json');
echo json_encode($response);


// Fecha a conexão com o banco de dados
$stmt->close();
$conn->close();

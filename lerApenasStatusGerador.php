<?php
// Inclua as configurações do banco de dados
include 'dadosServidorBD.php';

header('access-control-allow-origin: *');
header('Access-Control-Allow-Headers: *');

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Storing the received JSON in $json.
$json = file_get_contents('php://input');

// Decode the received JSON and store into $obj
$obj = json_decode($json, true);

// Getting data from $obj.
$idGerador = $obj["gerador"]; // Mudamos de "id" para "gerador" para ficar compatível com o código do Arduino

$sql = "SELECT geracao FROM leituraGeracaoEnergiaEletrica WHERE gerador = $idGerador ORDER BY dataHora DESC LIMIT 1";
$result = $conn->query($sql);

$response = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response["status"] = $row["geracao"];
    }
} else {
    $response["status"] = "Não encontrado";
}

$conn->close();
mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($response);
?>

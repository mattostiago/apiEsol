<?php
include 'dadosServidorBD.php';

header('access-control-allow-origin: *');
header('Access-Control-Allow-Headers: *');

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT nivel, dataHora FROM leituraReservatorio ORDER BY dataHora DESC LIMIT 100";
$result = $conn->query($sql);

$response = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($response, $row);
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>

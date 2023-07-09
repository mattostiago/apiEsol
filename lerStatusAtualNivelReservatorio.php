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
    // Storing the received JSON in $json.
	$json = file_get_contents('php://input');
    // Decode the received JSON and store into $obj
	$obj = json_decode($json,true);
	// Getting data from $obj.
	$idReservatorio = $obj["id"];

    $sql = "SELECT reservatorio.id, reservatorio.nome, leituraReservatorio.nivel, leituraReservatorio.dataHora
    FROM reservatorio
    INNER JOIN leituraReservatorio
    ON leituraReservatorio.reservatorio=reservatorio.id
    WHERE reservatorio.id = $idReservatorio && leituraReservatorio.dataHora=(SELECT MAX(LR.dataHora)
                                                               FROM leituraReservatorio LR
                                                               WHERE LR.reservatorio=$idReservatorio)";
    $result = $conn->query($sql);
    $response = array();

    if($result->num_rows> 0){
        while($row = $result->fetch_assoc()){
            array_push($response, $row);
        }
    }
    $conn->close();
    mysqli_close($conn);
    header('Content-Type: application/json');
    echo json_encode($response);
  //  $linhasatualizadas = $conexao->exec($consulta);
  //      echo "Total de linhas atualizadas: $linhasatualizadas";
  //      mysqli_close($conn);
?>
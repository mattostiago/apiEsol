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

    $sql = "SELECT reservatorio.nome, reservatorio.tipo, reservatorio.capacidade, leituraTemperatura.temperatura, localMedicao.local, leituraTemperatura.dataHora
    FROM reservatorio
    INNER JOIN leituraTemperatura
    ON leituraTemperatura.reservatorio=reservatorio.id
    INNER JOIN localMedicao
    ON localMedicao.id = leituraTemperatura.local_medicao
    WHERE reservatorio.id = $idReservatorio  && leituraTemperatura.dataHora=(SELECT MAX(LT.dataHora)
                                                               FROM leituraTemperatura LT
                                                               WHERE LT.reservatorio=$idReservatorio 
                                                               )";
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
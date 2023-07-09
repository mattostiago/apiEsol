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
    // else{
    //    echo "Connected successfully";
    // }
    $sql = "SELECT reservatorio.id, reservatorio.nome, leituraReservatorio.nivel, leituraReservatorio.dataHora
    FROM reservatorio
    INNER JOIN leituraReservatorio
    ON leituraReservatorio.reservatorio=reservatorio.id
    WHERE reservatorio.id = 1 && leituraReservatorio.dataHora=(SELECT MAX(LR.dataHora)
                                                               FROM leituraReservatorio LR
                                                               WHERE LR.reservatorio=1)";
    $result = $conn->query($sql);
    $response = array();
    if($result->num_rows> 0){
        while($row = $result->fetch_assoc()){
            array_push($response, $row);
        }
    }
    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response);
  //  $linhasatualizadas = $conexao->exec($consulta);
  //      echo "Total de linhas atualizadas: $linhasatualizadas";
  //      mysqli_close($conn);
?>
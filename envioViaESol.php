<?php

    //servidor_bd2
    $servername = "localhost";
    $database = "u611806352_esol";
    $username = "u611806352_esol";
    $password = "ESol123*&54";

    
    //Outros nomes
    $HostName = $servername;
    $DatabaseName = $database;
    $HostUser = $username;
    $HostPass = $password; 



// Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
// If you change this value, the ESP32 sketch needs to match
//$api_key_value = "tPmAT5Ab3j7F9";

$reservatorio= $nivel = $dataHora = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $reservatorio = test_input($_POST["reservatorio"]);
        $nivel = test_input($_POST["nivel"]);
        $dataHora = test_input($_POST["dataHora"]);
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        $sql= "INSERT INTO leituraReservatorio (reservatorio, nivel, dataHora) VALUES (NULL, '" . $reservatorio . "', '" . $nivel . "', '" . $dataHora . "')";


        //$sql = "INSERT INTO testeMCU (codTeste, dado, dataEhora) VALUES (NULL, '" . $dado . "', '" . $dataEhora . "')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Novo dado salvo em leituraReservatorio";
        } 
        else {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
        mysqli_close($conn);
    

}
else {
    echo "Nenhum dado enviado via POST HTTP.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
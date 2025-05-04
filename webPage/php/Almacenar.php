<?php

$servername = "Localhost";
$username = "root";
$password = ""; 
$dbname = "rick_morty_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}                

// Obtener datos de la API 

$apiUrl = "https://rickandmortyapi.com/api/character";  
$response = file_get_contents($apiUrl);
$data = json_decode($response, true); // Convertir JSON a array

foreach ($data["results"] as $character) {
    $name = $conn->real_escape_string($character["name"]);
    $species = $conn->real_escape_string$character(["species"]);
    $status = $conn->real_escape_string$character(["status"]);
    $image = $conn->real_escape_string$character(["image"]);
    $gender = $conn->real_escape_string$character(["gender"]);
    $type = $conn->real_escape_string$character(["type"]);

    
    // Insertar datos en la base de datos
    $sql = "INSERT INTO characters (name, species, status, image, gender, type, id_location, id_origin) 
    VALUES ('$name', '$species', '$status', '$image', '$gender', '$type', 0, 0)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Personaje Insertado<br>";
    } else {
        echo "Error al insertar el personaje: " . $sql . "<br>" . $conn->error; "<br>";
    }
}

$conn->close();
?>
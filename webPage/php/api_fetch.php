<?php
// URL de la API
$apiUrl = "https://rickandmortyapi.com/api/character";

// Obtener datos de la API
$response = file_get_contents($apiUrl);
$data = json_decode($response, true); // Convertir JSON a array

// Mostrar los datos
foreach ($data["results"] as $character) {
    echo "Nombre: " . $character["name"] . "<br>";
    echo "Especie: " . $character["species"] . "<br>";
    echo "Estado: " . $character["status"] . "<br>";
    echo "------------------------------<br>";
}
?>

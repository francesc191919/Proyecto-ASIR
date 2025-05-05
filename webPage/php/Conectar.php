<?php

function conectar($bd)
{
    try
    {
        $idCone = mysqli_connect("localhost", "root", "", $bd);
        if (!$idCone) {
            throw new Exception("Error de conexión: " . mysqli_connect_error());
        }
        return $idCone;
    }
    catch (Exception $e)
    {
        die("<p class='error'>Error inesperado: " . $e->getMessage() . "</p>\n");
    }
}

function cambiarBD($conexion, $bd)
{
    mysqli_select_db($conexion, $bd);
}

// Obtener todos los personajes
function obtenerPersonajes($conexion)
{
    $sql = "SELECT * FROM characters";
    $resultado = mysqli_query($conexion, $sql);
    $personajes = [];

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $personajes[] = $fila;
    }

    return $personajes;
}

// Obtener todos los episodios
function obtenerEpisodios($conexion)
{
    $sql = "SELECT * FROM episodes";
    $resultado = mysqli_query($conexion, $sql);
    $episodios = [];

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $episodios[] = $fila;
    }

    return $episodios;
}

// Obtener todas las ubicaciones
function obtenerUbicaciones($conexion)
{
    $sql = "SELECT * FROM locations";
    $resultado = mysqli_query($conexion, $sql);
    $ubicaciones = [];

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $ubicaciones[] = $fila;
    }

    return $ubicaciones;
}

// Conectar a la base de datos
$conexion = conectar("rick_morty_db");

// Obtener datos y mostrarlos
$personajes = obtenerPersonajes($conexion);
$episodios = obtenerEpisodios($conexion);
$ubicaciones = obtenerUbicaciones($conexion);

echo "<h2>Personajes:</h2>";
echo "<pre>" . print_r($personajes, true) . "</pre>";

echo "<h2>Episodios:</h2>";
echo "<pre>" . print_r($episodios, true) . "</pre>";

echo "<h2>Ubicaciones:</h2>";
echo "<pre>" . print_r($ubicaciones, true) . "</pre>";

// Cerrar conexión
mysqli_close($conexion);

?>

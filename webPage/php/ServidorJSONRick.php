<?php

header("Content-Type: application/json");

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
        die(json_encode(["error" => $e->getMessage()]));
    }
}

// Obtener parámetros de la URL
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;

$conexion = conectar("rick_morty_db");

// Función para obtener datos según el tipo solicitado
function obtenerDatos($conexion, $tabla)
{
    $sql = "SELECT * FROM $tabla";
    $resultado = mysqli_query($conexion, $sql);
    $datos = [];

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $datos[] = $fila;
    }

    return $datos;
}

// Comprobar qué datos se solicitan
if ($tipo === "personajes") {
    echo json_encode(obtenerDatos($conexion, "characters"));
} elseif ($tipo === "episodios") {
    echo json_encode(obtenerDatos($conexion, "episodes"));
} elseif ($tipo === "ubicaciones") {
    echo json_encode(obtenerDatos($conexion, "locations"));
} else {
    echo json_encode(["error" => "Tipo no válido. Usa ?tipo=personajes, ?tipo=episodios o ?tipo=ubicaciones"]);
}

// Cerrar conexión
mysqli_close($conexion);

?>

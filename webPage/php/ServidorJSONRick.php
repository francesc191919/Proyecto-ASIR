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

// Función para obtener datos de un personaje específico
function obtenerDatosPersonaje($conexion, $id)
{
    $sql = "
        SELECT 
            c.id,
            c.name,
            c.gender,
            c.species,
            c.status,
            c.type,
            c.image,

            loc.name AS current_location,
            loc.type AS current_location_type,
            loc.dimension AS current_location_dimension,

            orig.name AS origin_location,
            orig.type AS origin_location_type,
            orig.dimension AS origin_location_dimension,

            e.id AS episode_id,
            e.name AS episode_name,
            e.episode,
            e.air_date

        FROM characters c
        LEFT JOIN locations loc ON c.id_location = loc.id
        LEFT JOIN locations orig ON c.id_origin = orig.id
        LEFT JOIN character_in_episode ce ON c.id = ce.id_character
        LEFT JOIN episodes e ON ce.id_episode = e.id
        WHERE c.id = " . intval($id);

    $resultado = mysqli_query($conexion, $sql);

    $personaje = null;
    $episodios = [];

    while ($fila = mysqli_fetch_assoc($resultado)) {
        // Solo una vez guardamos los datos del personaje
        if ($personaje === null) {
            $personaje = [
                'id' => $fila['id'],
                'name' => $fila['name'],
                'gender' => $fila['gender'],
                'species' => $fila['species'],
                'status' => $fila['status'],
                'type' => $fila['type'],
                'image' => $fila['image'],
                'current_location' => [
                    'name' => $fila['current_location'],
                    'type' => $fila['current_location_type'],
                    'dimension' => $fila['current_location_dimension']
                ],
                'origin_location' => [
                    'name' => $fila['origin_location'],
                    'type' => $fila['origin_location_type'],
                    'dimension' => $fila['origin_location_dimension']
                ],
                'episodes' => [] // Aquí se llenarán después
            ];
        }

        // Si hay episodio asociado, lo añadimos
        if ($fila['episode_id'] !== null) {
            $personaje['episodes'][] = [
                'id' => $fila['episode_id'],
                'name' => $fila['episode_name'],
                'episode' => $fila['episode'],
                'air_date' => $fila['air_date']
            ];
        }
    }

    return $personaje;
}

function filtrarPersonajes($conexion, $idEpisodio = null, $nombre = null)
{
    $sql = "
        SELECT DISTINCT c.*
        FROM characters c
        LEFT JOIN character_in_episode ce ON c.id = ce.id_character
        WHERE 1=1
    ";

    if (!empty($idEpisodio)) {
        $sql .= " AND ce.id_episode = " . intval($idEpisodio);
    }

    if (!empty($nombre)) {
        // Escapar el texto manualmente por seguridad
        $nombreEscapado = mysqli_real_escape_string($conexion, $nombre);
        $sql .= " AND c.name LIKE '%$nombreEscapado%'";
    }

    $resultado = mysqli_query($conexion, $sql);
    $personajes = [];

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $personajes[] = $fila;
    }

    return $personajes;
}

// Comprobar qué datos se solicitan
if ($tipo === "personajes") {
    $idEpisodio = isset($_GET['episodio']) ? $_GET['episodio'] : null;
    $nombre = isset($_GET['nombre']) ? $_GET['nombre'] : null;
    echo json_encode(filtrarPersonajes($conexion, $idEpisodio, $nombre));
} elseif ($tipo === "episodios") {
    echo json_encode(obtenerDatos($conexion, "episodes"));
} elseif ($tipo === "detallePersonaje") {
    $id = isset($_GET['id']) ? $_GET['id'] : null;	
    echo json_encode(obtenerDatosPersonaje($conexion, $id));
} else {
    echo json_encode(["error" => "Tipo no válido. Usa ?tipo=personajes, ?tipo=episodios o ?tipo=detallePersonaje"]);
}

// Cerrar conexión
mysqli_close($conexion);

?>

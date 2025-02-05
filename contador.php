<?php
// Configuración de la base de datos
$host = 'localhost';
$user = 'contador_user';
$password = 'Eisenholz2024';
$dbname = 'contador_db';

try {
    // Conexión a la base de datos
    $conn = new mysqli($host, $user, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("No se pudo conectar a la base de datos.");
    }

    // Página actual
    $pagina = 'index.html';

    // Verificar si la página ya existe en la base de datos
    $sql = "SELECT * FROM visitas WHERE pagina = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $pagina);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si existe, actualizar el contador
        $sql = "UPDATE visitas SET visitas = visitas + 1, ultima_visita = NOW() WHERE pagina = ?";
    } else {
        // Si no existe, insertar un nuevo registro
        $sql = "INSERT INTO visitas (pagina, visitas) VALUES (?, 1)";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $pagina);
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

    // Obtener el número de visitas actualizado
    $sql = "SELECT visitas FROM visitas WHERE pagina = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $pagina);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Devolver el número de visitas
    echo $row['visitas'];
} catch (Exception $e) {
    // Si ocurre un error, devolver "error"
    echo "error";
} finally {
    // Cerrar la conexión si está abierta
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>

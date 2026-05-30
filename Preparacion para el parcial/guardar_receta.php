<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Método no permitido";
    exit;
}

$titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
$idtiporeceta = isset($_POST['idtiporeceta']) ? intval($_POST['idtiporeceta']) : 0;
$preparacion = isset($_POST['preparacion']) ? $_POST['preparacion'] : '';

$nuevo = "";
if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
    $nombre_original = $_FILES['fotografia']['name'];
    $nombre_temporal = $_FILES['fotografia']['tmp_name'];
    // Obtener la extensión original del archivo para no guardarlo como .tmp
    $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
    $nuevo = uniqid() . '.' . $extension;
    
    // Crear el directorio images si no existe
    if (!is_dir("images")) {
        mkdir("images", 0777, true);
    }
    
    copy($nombre_temporal, "images/" . $nuevo);
}

include('conexion.php');

$sql = "INSERT INTO recetas (fotografia, titulo, idtiporeceta, preparacion) VALUES (?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param(
    "ssis",
    $nuevo,
    $titulo,
    $idtiporeceta,
    $preparacion
);

if ($stmt->execute()) {
    echo "registro exitoso";
} else {
    echo "Error al guardar el registro: " . $stmt->error;
}

$stmt->close();
$con->close();
?>

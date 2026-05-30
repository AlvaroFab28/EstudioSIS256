<?php
include "conexion.php";

$sql = "SELECT id, tiporeceta FROM tiporeceta";
$consulta = mysqli_query($con, $sql);

$arreglo = array();
while ($row = mysqli_fetch_assoc($consulta)) {
    $arreglo[] = $row;
}

$con->close();
echo json_encode($arreglo);
?>

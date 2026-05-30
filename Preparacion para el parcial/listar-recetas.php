<?php
include "conexion.php";

// 1. Obtener el valor del filtro de tipo
$tipo_filter = isset($_GET['tipo']) ? $_GET['tipo'] : null;

// 2. Construir la cláusula WHERE
$where = "";
if ($tipo_filter !== null && $tipo_filter !== '') {
    $where = "WHERE tiporeceta.tiporeceta = '" . mysqli_real_escape_string($con, $tipo_filter) . "'";
}

// 3. Consultar las recetas
$sql = "SELECT recetas.id, fotografia, titulo, tiporeceta.tiporeceta AS tipo, preparacion 
        FROM recetas 
        LEFT JOIN tiporeceta ON recetas.idtiporeceta = tiporeceta.id 
        $where";
$consulta = mysqli_query($con, $sql);

// 4. Consultar los tipos de receta (solo si es la carga inicial para renderizar el select)
if ($tipo_filter === null) {
    $sql2 = "SELECT id, tiporeceta FROM tiporeceta";
    $consulta2 = mysqli_query($con, $sql2);
}
?>

<?php if ($tipo_filter === null) { ?>
<!-- Esta parte superior (el select) solo se envía en la primera carga -->
<div style="margin-bottom: 15px;">
    <label for="tipo" style="font-weight: bold; margin-right: 10px;">Filtrar por tipo de receta:</label>
    <select name="tipo" id="tipo" onchange="cargarContenido2('listar-recetas.php?tipo='+this.value)" style="padding: 6px; border-radius: 4px; border: 1px solid #ccc; width: 200px;">
        <option value="">Todos</option>
        <?php while ($row_tipo = mysqli_fetch_array($consulta2)) { ?>
            <option value="<?php echo htmlspecialchars($row_tipo['tiporeceta']); ?>">
                <?php echo htmlspecialchars($row_tipo['tiporeceta']); ?>
            </option>
        <?php } ?>
    </select>
</div>

<div class="tabla">
<?php } ?>

    <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd; font-size: 14px;">
        <thead>
            <tr style="background-color: #1a73e8; color: white;">
                <th style="border: 1px solid #ddd; padding: 10px; text-align: center; width: 50px;">ID</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: center; width: 100px;">Fotografía</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Título</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left; width: 120px;">Tipo</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Preparación (Abreviada)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (mysqli_num_rows($consulta) > 0) {
                while ($receta = mysqli_fetch_array($consulta)) { 
            ?> 
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center; font-weight: bold;"><?php echo $receta['id']; ?></td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                        <img width="80" height="80" src="images/<?php echo htmlspecialchars($receta['fotografia']); ?>" style="object-fit: cover; border-radius: 4px; border: 1px solid #eee;" alt="">
                    </td>
                    <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;"><?php echo htmlspecialchars($receta['titulo']); ?></td>
                    <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($receta['tipo']); ?></td>
                    <td style="border: 1px solid #ddd; padding: 8px;" title="<?php echo htmlspecialchars($receta['preparacion']); ?>">
                        <?php 
                        $prep = $receta['preparacion'];
                        if (strlen($prep) > 50) {
                            echo htmlspecialchars(substr($prep, 0, 50)) . "...";
                        } else {
                            echo htmlspecialchars($prep);
                        }
                        ?>
                    </td>
                </tr>
            <?php 
                }
            } else {
            ?>
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center; color: #777;">No hay recetas disponibles para este tipo.</td>
                </tr>
            <?php 
            }
            $con->close();
            ?>
        </tbody>
    </table>

<?php if ($tipo_filter === null) { ?>
</div>
<?php } ?>

<?php
include "conexion.php";

$sql = "SELECT recetas.id, fotografia, titulo, tiporeceta.tiporeceta AS tipo 
        FROM recetas 
        LEFT JOIN tiporeceta ON recetas.idtiporeceta = tiporeceta.id";
$result = mysqli_query($con, $sql);

if (!$result) {
    echo "Error en la consulta: " . mysqli_error($con);
    exit;
}
?>

<h3>Galería de Recetas</h3>
<p style="font-size: 13px; color: #666; margin-bottom: 15px;">Haga clic en cualquier imagen para ver los detalles.</p>

<div class="galeria-grid">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="galeria-item">
            <img src="images/<?php echo htmlspecialchars($row['fotografia']); ?>" 
                 width="80" 
                 height="80" 
                 onclick="mostrarDetalle(<?php echo $row['id']; ?>)" 
                 style="cursor: pointer; object-fit: cover; border-radius: 4px; border: 1px solid #ccc; transition: opacity 0.2s;" 
                 onmouseover="this.style.opacity=0.8" 
                 onmouseout="this.style.opacity=1"
                 alt="<?php echo htmlspecialchars($row['titulo']); ?>">
            <div style="font-size: 11px; max-width: 80px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; color: #333; margin-top: 3px;" title="<?php echo htmlspecialchars($row['titulo']); ?>">
                <?php echo htmlspecialchars($row['titulo']); ?>
            </div>
        </div>
    <?php } ?>
</div>

<?php
$con->close();
?>

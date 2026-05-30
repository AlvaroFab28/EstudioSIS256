<?php
if (!isset($_GET['id']) || $_GET['id'] == '') {
    echo "ID no especificado.";
    exit;
}

$id = intval($_GET['id']);
include "conexion.php";

$sql = "SELECT recetas.id, fotografia, titulo, tiporeceta.tiporeceta AS tipo, preparacion 
        FROM recetas 
        LEFT JOIN tiporeceta ON recetas.idtiporeceta = tiporeceta.id 
        WHERE recetas.id = $id";
$result = mysqli_query($con, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Receta no encontrada.";
    $con->close();
    exit;
}

$receta = mysqli_fetch_assoc($result);

// Listado de ingredientes posibles para seleccionar 5 ficticios en PHP
$posibles_ingredientes = [
    "Harina de trigo", "Azúcar granulada", "Huevos frescos", "Leche entera", "Mantequilla con sal",
    "Sal marina", "Pimienta negra molida", "Aceite de oliva extra virgen", "Dientes de ajo", "Cebolla picada",
    "Tomates frescos", "Queso Parmesano rallado", "Orégano seco", "Pimentón dulce", "Comino en polvo",
    "Perejil picado", "Cilantro fresco", "Jugo de limón", "Pechuga de pollo", "Carne picada de res",
    "Crema para batir", "Esencia de vainilla", "Polvo de hornear", "Levadura seca", "Agua tibia"
];

// Mezclar de manera aleatoria y tomar 5
shuffle($posibles_ingredientes);
$ingredientes_ficticios = array_slice($posibles_ingredientes, 0, 5);

$con->close();
?>

<div class="modal" id="modal-detalle" style="visibility: visible;">
    <div class="modal-content" id="vista" style="border: 2px solid #1a73e8;">
        <h2 style="margin-top: 0; color: #1a73e8; border-bottom: 2px solid #1a73e8; padding-bottom: 5px;">
            <?php echo htmlspecialchars($receta['titulo']); ?>
        </h2>
        
        <p style="font-size: 14px; margin: 8px 0;">
            <strong>Tipo de Receta:</strong> 
            <span style="background: #e1f5fe; color: #0288d1; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">
                <?php echo htmlspecialchars($receta['tipo']); ?>
            </span>
        </p>

        <div style="text-align: center; margin: 15px 0;">
            <img src="images/<?php echo htmlspecialchars($receta['fotografia']); ?>" 
                 width="300" 
                 height="300" 
                 style="object-fit: cover; border-radius: 8px; border: 1px solid #ccc; box-shadow: 0 2px 5px rgba(0,0,0,0.1);" 
                 alt="<?php echo htmlspecialchars($receta['titulo']); ?>">
        </div>

        <div style="margin-top: 15px;">
            <strong style="font-size: 15px; color: #333; display: block; margin-bottom: 5px;">Preparación:</strong>
            <p style="font-size: 14px; color: #555; line-height: 1.4; background: #fafafa; padding: 10px; border-radius: 4px; border-left: 3px solid #ccc; margin: 0 0 15px 0;">
                <?php echo nl2br(htmlspecialchars($receta['preparacion'])); ?>
            </p>
        </div>

        <div style="margin-top: 15px;">
            <strong style="font-size: 15px; color: #333; display: block; margin-bottom: 5px;">Ingredientes Requeridos (Ficticios):</strong>
            <ul style="font-size: 14px; color: #555; padding-left: 20px; margin-top: 5px;">
                <?php foreach ($ingredientes_ficticios as $ingrediente) { ?>
                    <li style="margin-bottom: 4px;"><?php echo htmlspecialchars($ingrediente); ?></li>
                <?php } ?>
            </ul>
        </div>

        <div style="text-align: right; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px;">
            <button onclick="cerrarDetalle()" class="btnMenu" style="width: auto; padding: 8px 20px; margin-bottom: 0;">Cerrar Modal</button>
        </div>
    </div>
</div>

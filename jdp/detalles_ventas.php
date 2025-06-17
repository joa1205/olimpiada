<?php
include 'conexion.php';
session_start();

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
  header('Location: login.php');
  exit();
}

// Procesar cambios de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'], $_POST['id_detalle'])) {
  $id_detalle = intval($_POST['id_detalle']);
  $accion = $_POST['accion'];

  if (in_array($accion, ['aceptado', 'rechazado'])) {
    $conexion->query("UPDATE detalle_compra SET estado = '$accion' WHERE id = $id_detalle");
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle de Ventas</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>🧾 Detalle de todas las compras registradas</h2>

<table>
  <tr>
    <th>ID Compra</th>
    <th>Usuario</th>
    <th>Tipo</th>
    <th>Cantidad</th>
    <th>Precio Unitario</th>
    <th>Total</th>
    <th>Fecha</th>
    <th>Estado</th>
  </tr>

<?php
$sql = "SELECT 
          c.id AS id_compra,
          u.usuario AS nombre_usuario,
          dc.id_producto,
          dc.tipo_producto,
          dc.cantidad,
          dc.precio_unitario,
          dc.id AS id_detalle,
          dc.estado,
          c.fecha AS fecha_compra
        FROM compras c
        JOIN usuarios u ON c.id_usuario = u.id
        JOIN detalle_compra dc ON c.id = dc.id_compra
        ORDER BY c.fecha DESC";

$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
  while($row = $resultado->fetch_assoc()) {
    $id_prod = intval($row['id_producto']);
    $tipo = $row['tipo_producto'];

    $res_prod = $conexion->query("SELECT * FROM productos WHERE id = $id_prod");
    if ($res_prod && $producto = $res_prod->fetch_assoc()) {
      if (!is_null($producto['id_viaje'])) $tipo = 'pasaje';
      elseif (!is_null($producto['id_alojamiento'])) $tipo = 'alojamiento';
      elseif (!is_null($producto['id_paquetes'])) $tipo = 'paquete';
      elseif (!is_null($producto['id_autos'])) $tipo = 'auto';
      else $tipo = 'producto';
    }

    $total = $row['cantidad'] * $row['precio_unitario'];
    $estado = $row['estado'];
    $id_detalle = $row['id_detalle'];

    echo "<tr>
            <td>{$row['id_compra']}</td>
            <td>{$row['nombre_usuario']}</td>
            <td>$tipo</td>
            <td>{$row['cantidad']}</td>
            <td>\${$row['precio_unitario']}</td>
            <td>\$$total</td>
            <td>{$row['fecha_compra']}</td>
            <td>";

    if ($_SESSION['rol'] === 'admin') {
        // Mostrar estado actual con color
$color = 'gris';
if ($estado === 'aceptado') $color = 'verde';
elseif ($estado === 'rechazado') $color = 'rojo';

echo "<span class='estado-$color'>" . ucfirst($estado) . "</span><br>";

// Siempre mostrar botón de modificar
echo "<button class='modificar-btn' onclick='mostrarOpciones($id_detalle)'>Modificar</button>
      <div id='opciones-$id_detalle' class='opciones-estado'>
        <form method='POST' style='display:inline;'>
          <input type='hidden' name='id_detalle' value='$id_detalle'>
          <input type='hidden' name='accion' value='aceptado'>
          <button class='btn-verde' type='submit'>Aceptar</button>
        </form>
        <form method='POST' style='display:inline;'>
          <input type='hidden' name='id_detalle' value='$id_detalle'>
          <input type='hidden' name='accion' value='rechazado'>
          <button class='btn-rojo' type='submit'>Rechazar</button>
        </form>
      </div>";

    } else {
      // Solo texto para usuarios comunes
      echo ucfirst($estado);
    }

    echo "</td></tr>";
  }
} else {
  echo "<tr><td colspan='8'>No hay compras registradas aún.</td></tr>";
}
$conexion->close();
?>
</table>

<script>
  function mostrarOpciones(id) {
    document.getElementById('opciones-' + id).style.display = 'inline-block';
  }
</script>

<style>
  table {
    width: 95%;
    margin: 30px auto;
    border-collapse: collapse;
  }
  th, td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: center;
  }
  th {
    background-color: #eee;
  }
  h2 {
    text-align: center;
    margin-top: 20px;
  }

  .modificar-btn {
    background-color: #888;
    color: white;
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    border-radius: 4px;
  }

  .btn-verde {
    background-color: #4CAF50;
    color: white;
    padding: 5px 10px;
    margin-right: 5px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .btn-rojo {
    background-color: #f44336;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .estado-verde {
    color: #4CAF50;
    font-weight: bold;
  }

  .estado-rojo {
    color: #f44336;
    font-weight: bold;
  }

  .opciones-estado {
    display: none;
    margin-top: 5px;
  }
</style>

</body>
</html>

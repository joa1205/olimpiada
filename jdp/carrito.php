<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['carrito'])) {
  $_SESSION['carrito'] = [];
}

// Eliminar producto del carrito si se solicita
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
  $idEliminar = intval($_POST['eliminar_id']);
  unset($_SESSION['carrito'][$idEliminar]);
  header("Location: carrito.php");
  exit;
}

// Obtener productos del carrito
$productos = [];
$total = 0;

if (!empty($_SESSION['carrito'])) {
  $ids = array_keys($_SESSION['carrito']);
  $ids_sql = implode(',', array_map('intval', $ids));
  $query = "SELECT * FROM pasaje WHERE id IN ($ids_sql)";
  $resultado = mysqli_query($conexion, $query);

  while ($row = mysqli_fetch_assoc($resultado)) {
    $row['cantidad'] = $_SESSION['carrito'][$row['id']];
    $row['subtotal'] = $row['PRECIO'] * $row['cantidad'];
    $total += $row['subtotal'];
    $productos[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carrito de Compras</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<h1><i class="fas fa-shopping-cart"></i> Carrito de Compras (<?php echo array_sum($_SESSION['carrito']); ?>)</h1>

<div class="carrito-container">
  <?php if (empty($productos)): ?>
    <div class="carrito-vacio">
      <p>No hay productos en el carrito.</p>
      <a href="vuelos.php">← Seguir comprando</a>
    </div>
  <?php else: ?>
    <?php foreach ($productos as $producto): ?>
      <div class="producto">
        <img src="<?php echo $producto['imagen']; ?>" alt="Imagen del producto">
        <div class="producto-info">
          <h3><?php echo $producto['lugar_de_llegada']; ?></h3>
          <p>Desde: <?php echo $producto['lugar_de_salida']; ?> en <?php echo $producto['metodo_de_transporte']; ?></p>
          <p class="cantidad">Cantidad: <?php echo $producto['cantidad']; ?></p>
          <p class="subtotal">Subtotal: $<?php echo number_format($producto['subtotal'], 2); ?></p>
        </div>
        <form class="eliminar-form" method="post">
          <input type="hidden" name="eliminar_id" value="<?php echo $producto['id']; ?>">
          <button type="submit">Eliminar</button>
        </form>
      </div>
    <?php endforeach; ?>

    <div class="total">Total a pagar: $<?php echo number_format($total, 2); ?></div>
    <a class="volver" href="vuelos.php">← Seguir comprando</a>
  <?php endif; ?>
</div>

</body>
</html>


  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f0f0;
      padding: 20px;
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 28px;
      color: #222;
    }

    .carrito-container {
      max-width: 900px;
      margin: auto;
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .producto {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      border-bottom: 1px solid #eee;
      padding-bottom: 15px;
    }

    .producto img {
      width: 120px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 20px;
    }

    .producto-info {
      flex-grow: 1;
    }

    .producto-info h3 {
      margin: 0 0 8px 0;
      font-size: 18px;
      color: #333;
    }

    .producto-info p {
      margin: 3px 0;
      color: #555;
    }

    .cantidad {
      font-weight: bold;
      margin-top: 8px;
    }

    .subtotal {
      font-size: 16px;
      font-weight: bold;
      color: #007BFF;
      margin-top: 5px;
    }

    .eliminar-form {
      margin-left: 20px;
    }

    .eliminar-form button {
      background-color: #dc3545;
      border: none;
      color: white;
      padding: 6px 12px;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .eliminar-form button:hover {
      background-color: #a71d2a;
    }

    .total {
      text-align: right;
      font-size: 22px;
      font-weight: bold;
      color: #333;
      margin-top: 30px;
    }

    .volver {
      display: block;
      text-align: center;
      margin-top: 40px;
      font-size: 16px;
      text-decoration: none;
      color: #3f0071;
    }

    .carrito-vacio {
      text-align: center;
      font-size: 18px;
      color: #666;
    }

    .carrito-vacio a {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #3f0071;
      font-weight: bold;
    }

    .carrito-vacio a:hover {
      text-decoration: underline;
    }
  </style>
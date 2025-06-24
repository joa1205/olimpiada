<?php
include 'conexion.php';  // Incluye archivo para conexión a la base de datos
session_start();         // Inicia la sesión para manejar variables de sesión

// Inicializa el carrito en sesión si no existe (para usuarios no logueados)
if (!isset($_SESSION['carrito'])) {
  $_SESSION['carrito'] = [];
}

// Obtener el ID del usuario si está logueado
$id_usuario = null;
if (isset($_SESSION['usuario'])) {
  $usuario = $_SESSION['usuario'];
  // Consulta para obtener el ID del usuario a partir del nombre de usuario en sesión
  $res = mysqli_query($conexion, "SELECT id FROM usuarios WHERE usuario = '$usuario'");
  if ($fila = mysqli_fetch_assoc($res)) {
    $id_usuario = $fila['id'];
  }
}

// Proceso para agregar un auto al carrito cuando se recibe un POST con id_autos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_autos'])) {
  $id_autos = intval($_POST['id_autos']); // ID del vuelo recibido

  if ($id_usuario) {
    // Si usuario logueado, buscar carrito activo
    $carrito_query = mysqli_query($conexion, "SELECT id FROM carrito WHERE id_usuario = $id_usuario AND estado = 'activo'");
    if ($fila = mysqli_fetch_assoc($carrito_query)) {
      $id_carrito = $fila['id'];  // carrito encontrado
    } else {
      // Si no hay carrito activo, crear uno nuevo
      mysqli_query($conexion, "INSERT INTO carrito (id_usuario, estado) VALUES ($id_usuario, 'activo')");
      $id_carrito = mysqli_insert_id($conexion);  // obtener ID del carrito nuevo
    }

    // Obtener el precio del vuelo desde la tabla productos
    $precio_query = mysqli_query($conexion, "SELECT precio FROM productos WHERE id_autos = $id_autos");
    $precio_unitario = 0;
    if ($precio_row = mysqli_fetch_assoc($precio_query)) {
      $precio_unitario = floatval($precio_row['precio']);
    }

    // Verificar si el autos ya está en el detalle del carrito
    $detalle = mysqli_query($conexion, "SELECT * FROM detalle_carrito WHERE id_carrito = $id_carrito AND id_producto = $id_autos AND tipo_producto = 'auto' ");
    if (mysqli_num_rows($detalle) > 0) {
      // Si existe, aumentar la cantidad en 1
      mysqli_query($conexion, "UPDATE detalle_carrito SET cantidad = cantidad + 1 WHERE id_carrito = $id_carrito AND id_producto = $id_autos AND tipo_producto = 'auto' ");
    } else {
      // Si no existe, insertar nuevo registro en detalle carrito
      mysqli_query($conexion, "INSERT INTO detalle_carrito (id_carrito, id_producto, tipo_producto, cantidad, precio_unitario) VALUES ($id_carrito, $id_autos, 'auto', 1, $precio_unitario)");
    }
  } else {
    // Si no está logueado, agregar o incrementar el vuelo en carrito de sesión
    if (isset($_SESSION['carrito'][$id_autos])) {
      $_SESSION['carrito'][$id_autos]++;
    } else {
      $_SESSION['carrito'][$id_autos] = 1;
    }
  }
}

// Calcular la cantidad total de productos en el carrito para mostrar contador
$contador_carrito = 0;
if ($id_usuario) {
  // Sumar cantidades en detalle_carrito para el carrito activo del usuario
  $q = "SELECT SUM(cantidad) AS total FROM detalle_carrito dc 
        INNER JOIN carrito c ON c.id = dc.id_carrito
        WHERE c.id_usuario = $id_usuario AND c.estado = 'activo'";
  $res = mysqli_query($conexion, $q);
  if ($fila = mysqli_fetch_assoc($res)) {
    $contador_carrito = $fila['total'] ?? 0;
  }
} else {
  // Para usuarios no logueados, sumar cantidades del carrito en sesión
  $contador_carrito = array_sum($_SESSION['carrito']);
}

// Eliminar auto (admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_auto_id'])) {
  if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    $idEliminar = intval($_POST['eliminar_auto_id']);
    mysqli_query($conexion, "DELETE FROM productos WHERE id = $idEliminar");
    $sqlEliminar = "DELETE FROM autos WHERE id = $idEliminar";
    mysqli_query($conexion, $sqlEliminar);
  }
}

$sql = "SELECT * FROM autos";
$listaautos = mysqli_query($conexion, $sql);
$listaDatos = mysqli_fetch_all($listaautos, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Autos</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav>
    <div class="navbar">
      <div class="navbar-left">
        <img class="navbar-logo img" src="Logo3.png" alt="Logo" style="height: 70px;">
        <?php if (isset($_SESSION['usuario'])): ?>
          <span class="nav-item user-info">
            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usuario']); ?>
          </span>
        <?php endif; ?>
      </div>

      <div class="nav-links">
        <a href="index.php" class="nav-item"><i class="fas fa-house"></i>Inicio</a>
        <a href="vuelos.php" class="nav-item"><i class="fas fa-plane"></i>Vuelos</a>
        <a href="alojamientos.php" class="nav-item"><i class="fas fa-hotel"></i>Alojamientos</a>
        <a href="paquetes.php" class="nav-item"><i class="fas fa-suitcase-rolling"></i>Paquetes</a>
        <a href="autos.php" class="nav-item active"><i class="fas fa-car"></i>Autos</a>
        <?php
  if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] === 'admin') {
      echo '<a href="detalles_ventas.php" class="nav-item"><i class="fas fa-chart-line"></i> Ventas</a>';
    } elseif ($_SESSION['rol'] === 'cliente') {
      echo '<a href="mis_compras.php" class="nav-item"><i class="fas fa-receipt"></i> Mis Compras</a>';
    }
  }
?>
      </div>

      <div class="navbar-right">
        <?php if (isset($_SESSION['usuario'])): ?>
          <a href="cerrar_sesion.php" class="nav-item"><i class="fas fa-right-from-bracket"></i>Cerrar sesión</a>
        <?php else: ?>
          <a href="inicio_sesion.php" class="nav-item"><i class="fas fa-right-to-bracket"></i>Iniciar sesión</a>
          <a href="crear_cuenta.php" class="nav-item"><i class="fas fa-user-plus"></i>Registrarse</a>
        <?php endif; ?>
                <!-- Contador carrito -->
        <a href="carrito.php" class="nav-item cart"><i
        class="fas fa-shopping-cart"></i>Carrito(<?php echo $contador_carrito; ?>)</a>
      </div>
    </div>
  </nav>

  <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
    <div style="text-align: center; margin: 20px;">
      <a href="formulario_agregar_auto.php" class="btn-agregar-vuelo">Agregar nuevo vehiculo</a>
    </div>
  <?php endif; ?>

  <div class="cards-container">
    <?php foreach ($listaDatos as $autos) { ?>
      <div class="card">
        <div class="card-img">
          <img src="<?php echo $autos['imagen']; ?>" alt="">
        </div>
        <div class="card-content">
          <p class="package-label">Vehiculo</p>
          <h2 class="nombre"><?php echo $autos['nombre']; ?></h2>
          <div class="capacidad"><?php echo $autos['capacidad']; ?></div>
          <div class="rating">
            <span class="score"><?php echo $autos['calificacion']; ?>/5</span>
            <span class="stars">
              <?php
              for ($i = 0; $i < intval($autos['estrellas']); $i++) echo "★";
              for ($i = intval($autos['estrellas']); $i < 5; $i++) echo "☆";
              ?>
            </span>
          </div>
          <div class="price-section">
            <p class="price">$<?php echo $autos['precio']; ?></p>
            <form method="post">
              <input type="hidden" name="id_autos" value="<?php echo $autos['id']; ?>">
              <input type="submit" class="boton-carrito" value="Añadir al carrito" name="añadir">
            </form>
          </div>
          <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <form action="modificar_auto.php" method="get">
              <input type="hidden" name="id_autos" value="<?php echo $autos['id']; ?>">
              <input type="submit" value="Modificar vehiculo✏️" class="btn-modificar">
            </form>
            <form method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este vehiculo?');">
              <input type="hidden" name="eliminar_auto_id" value="<?php echo $autos['id']; ?>">
              <input type="submit" value="Eliminar auto 🗑️" class="btn-eliminar">
            </form>
          <?php endif; ?>
        </div>
      </div>
    <?php } ?>
  </div>
</body>
</html>


<!-- Estilos -->
<style>
  
</style>

<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['carrito'])) {
  $_SESSION['carrito'] = [];
}

$id_usuario = null;
if (isset($_SESSION['usuario'])) {
  $usuario = $_SESSION['usuario'];
  $res = mysqli_query($conexion, "SELECT id FROM usuarios WHERE usuario = '$usuario'");
  if ($fila = mysqli_fetch_assoc($res)) {
    $id_usuario = $fila['id'];
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_alojamiento'])) {
  $id_alojamiento = intval($_POST['id_alojamiento']);

  if ($id_usuario) {
    $carrito_query = mysqli_query($conexion, "SELECT id FROM carrito WHERE id_usuario = $id_usuario AND estado = 'activo'");
    if ($fila = mysqli_fetch_assoc($carrito_query)) {
      $id_carrito = $fila['id'];
    } else {
      mysqli_query($conexion, "INSERT INTO carrito (id_usuario, estado) VALUES ($id_usuario, 'activo')");
      $id_carrito = mysqli_insert_id($conexion);
    }

    $precio_query = mysqli_query($conexion, "SELECT precio FROM productos WHERE id_alojamiento = $id_alojamiento");
    $precio_unitario = 0;
    if ($precio_row = mysqli_fetch_assoc($precio_query)) {
      $precio_unitario = floatval($precio_row['precio']);
    }

    $detalle = mysqli_query($conexion, "SELECT * FROM detalle_carrito WHERE id_carrito = $id_carrito AND id_producto = $id_alojamiento AND tipo_producto = 'alojamiento'");
    if (mysqli_num_rows($detalle) > 0) {
      mysqli_query($conexion, "UPDATE detalle_carrito SET cantidad = cantidad + 1 WHERE id_carrito = $id_carrito AND id_producto = $id_alojamiento AND tipo_producto = 'alojamiento'");
    } else {
      mysqli_query($conexion, "INSERT INTO detalle_carrito (id_carrito, id_producto, tipo_producto, cantidad, precio_unitario) VALUES ($id_carrito, $id_alojamiento, 'alojamiento', 1, $precio_unitario)");
    }
  } else {
    if (isset($_SESSION['carrito'][$id_alojamiento])) {
      $_SESSION['carrito'][$id_alojamiento]++;
    } else {
      $_SESSION['carrito'][$id_alojamiento] = 1;
    }
  }
}

$contador_carrito = 0;
if ($id_usuario) {
  $q = "SELECT SUM(cantidad) AS total FROM detalle_carrito dc 
        INNER JOIN carrito c ON c.id = dc.id_carrito
        WHERE c.id_usuario = $id_usuario AND c.estado = 'activo'";
  $res = mysqli_query($conexion, $q);
  if ($fila = mysqli_fetch_assoc($res)) {
    $contador_carrito = $fila['total'] ?? 0;
  }
} else {
  $contador_carrito = array_sum($_SESSION['carrito']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_alojamiento_id'])) {
  if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    $idEliminar = intval($_POST['eliminar_alojamiento_id']);
    mysqli_query($conexion, "DELETE FROM alojamiento WHERE id = $idEliminar");
  }
}

$sql = "SELECT * FROM alojamiento";
$lista = mysqli_query($conexion, $sql);
$listaDatos = mysqli_fetch_all($lista, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Alojamientos</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <nav>
    <div class="navbar">
      <div class="navbar-left">
        <?php if (isset($_SESSION['usuario'])): ?>
          <span class="nav-item user-info"><i class="fas fa-user"></i> <?php echo $_SESSION['usuario']; ?></span>
        <?php endif; ?>
      </div>
      <div class="nav-links">
        <a href="index.php" class="nav-item"><i class="fas fa-house"></i>Inicio</a>
        <a href="vuelos.php" class="nav-item"><i class="fas fa-plane"></i>Vuelos</a>
        <a href="alojamientos.php" class="nav-item active"><i class="fas fa-hotel"></i>Alojamientos</a>
        <a href="paquetes.php" class="nav-item"><i class="fas fa-suitcase-rolling"></i>Paquetes</a>
        <a href="autos.php" class="nav-item"><i class="fas fa-car"></i>Autos</a>
      </div>
      <div class="navbar-right">
        <?php if (isset($_SESSION['usuario'])): ?>
          <a href="cerrar_sesion.php" class="nav-item"><i class="fas fa-right-from-bracket"></i>Cerrar sesión</a>
        <?php else: ?>
          <a href="inicio_sesion.php" class="nav-item"><i class="fas fa-right-to-bracket"></i>Iniciar sesión</a>
          <a href="crear_cuenta.php" class="nav-item"><i class="fas fa-user-plus"></i>Registrarse</a>
        <?php endif; ?>
        <a href="carrito.php" class="nav-item cart"><i
            class="fas fa-shopping-cart"></i>Carrito(<?php echo $contador_carrito; ?>)</a>
      </div>
    </div>
  </nav>

  <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
    <div style="text-align: center; margin: 20px;">
      <a href="formulario_agregar_alojamiento.php" class="btn-agregar-vuelo">Agregar nuevo alojamiento 🏨</a>
    </div>
  <?php endif; ?>

  <div class="cards-container">
    <?php foreach ($listaDatos as $alojamiento): ?>
      <div class="card">
        <div class="card-img">
          <img src="<?php echo $alojamiento['imagen']; ?>" alt="">
        </div>
        <div class="card-content">
          <p class="package-label">ALOJAMIENTO</p>
          <h2 class="destination"><?php echo $alojamiento['nombre']; ?></h2>
          <div class="duration"><?php echo $alojamiento['duracion']; ?></div>
          <div class="rating">
            <span class="score"><?php echo $alojamiento['calificacion']; ?>/5</span>
            <span class="stars">
              <?php
              for ($i = 0; $i < intval($alojamiento['estrellas']); $i++)
                echo "★";
              for ($i = intval($alojamiento['estrellas']); $i < 5; $i++)
                echo "☆";
              ?>
            </span>
          </div>
          <a href="<?php echo $alojamientos['mapalink']; ?>" class="ubicacion" target="_blank">
            Ver en Google Maps 📍
          </a>
          <p class="departure">Dirección: <?php echo $alojamiento['direccion']; ?> - Capacidad:
            <?php echo $alojamiento['capacidad']; ?></p>
          <div class="price-section">
            <p class="price">$<?php echo $alojamiento['precio']; ?></p>

            <form method="post">
              <input type="hidden" name="id_alojamiento" value="<?php echo $alojamiento['id']; ?>">
              <input type="submit" class="boton-carrito" value="Añadir al carrito">
            </form>

            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
              <!-- Botón de modificar solo para admin -->
              <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <form action="modificacion_alojamiento.php" method="get" style="margin-top: 5px;">
                  <input type="hidden" name="id_autos" value="<?php echo $autos['id']; ?>">
                  <input type="submit" value="Modificar alojamiento✏️" class="btn-modificar">
                </form>
              <?php endif; ?>
              <form method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este alojamiento?');">
                <input type="hidden" name="eliminar_alojamiento_id" value="<?php echo $alojamiento['id']; ?>">
                <input type="submit" value="Eliminar alojamiento 🗑️" class="btn-eliminar">
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</body>

</html>
<?php
include 'conexion.php';
session_start();
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
$sqlVuelos = "SELECT id, nombre, PRECIO, imagen, fecha_ida as fecha_salida, 'vuelo' AS tipo FROM pasaje ORDER BY RAND() LIMIT 2";
$sqlAlojamientos = "SELECT id, nombre, precio, imagen, fecha_ingreso as fecha_salida, 'alojamiento' AS tipo FROM alojamiento ORDER BY RAND() LIMIT 2";
$sqlPaquetes = "SELECT id, nombre, precio, imagen, fecha_ida as fecha_salida, 'paquete' AS tipo FROM paquetes ORDER BY RAND() LIMIT 2";

$res1 = mysqli_query($conexion, $sqlVuelos);
$res2 = mysqli_query($conexion, $sqlAlojamientos);
$res3 = mysqli_query($conexion, $sqlPaquetes);

$datos = array_merge(
  mysqli_fetch_all($res1, MYSQLI_ASSOC),
  mysqli_fetch_all($res2, MYSQLI_ASSOC),
  mysqli_fetch_all($res3, MYSQLI_ASSOC)
);

shuffle($datos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Navbar y Carrusel</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>

    /* Fondo del body con logo */
  body::before {
    content: "";
    background-image: url('fondo-logo.png');
    background-size: cover;
    background-position: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    filter: blur(10px) brightness(1.1);
    z-index: -1;
    opacity: 0.25;
  }

  body {
    background-color: #ffffff;
    position: relative;
    z-index: 0;
    font-family: Arial, sans-serif;
  }

  /* NAVBAR MODERNA */
  .navbar {
    background-color: #f5f8ff;
    padding: 15px 30px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
  }

  .nav-links .nav-item {
    margin: 0 10px;
    color: #004b91;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s, color 0.3s;
    padding: 6px 12px;
    border-radius: 8px;
  }

  .nav-links .nav-item:hover {
    background-color: #dbeafe;
    color: #002b5c;
  }

  .navbar-right .nav-item {
    margin-left: -14px;
    
    font-weight: bold;
  }

  .navbar-right .cart {
    background-color: #fef3c7;
    border-radius: 8px;
    padding: 5px 10px;
    color: #8a6d00;
  }

  .navbar-logo img {
    height: 60px;
    object-fit: contain;
  }

  .card {
    padding: 10px;
    border-radius: 10px;
  }

  .card-img-top {
    height: 250px;
    object-fit: cover;
    width: 100%;
    display: block;
    border-radius: 8px;
    margin: 0;
  }
  </style>
</head>
<body>

<?php if (isset($_GET['registro']) && $_GET['registro'] === 'exitoso'): ?>
  <div class="alert alert-success text-center">✅ Registrado exitosamente</div>
<?php endif; ?>
<?php if (isset($_GET['pago']) && $_GET['pago'] === 'exitoso'): ?>
  <div class="alert alert-success text-center">✅ Pago procesado correctamente. ¡Gracias por tu compra!</div>
<?php endif; ?>

<nav>
  <div class="navbar d-flex justify-content-between align-items-center px-3 py-2 shadow-sm" style="background-color: white;">
    <!-- Usuario y logo -->
    <div class="navbar-left d-flex align-items-center">
      <img class="navbar-logo img" src="Logo3.png" alt="Logo" style="height: 70px;">
      <?php if (isset($_SESSION['usuario'])): ?>
        <span class="nav-item user-info ms-2">
          <i class="fas fa-user"></i>
          <?php echo htmlspecialchars($_SESSION['usuario']); ?>
        </span>
      <?php endif; ?>
    </div>

    <!-- Links -->
    <div class="nav-links d-flex gap-3">
      <a href="index.php" class="nav-item"><i class="fas fa-house"></i> Inicio</a>
      <a href="vuelos.php" class="nav-item"><i class="fas fa-plane"></i> Vuelos</a>
      <a href="alojamientos.php" class="nav-item"><i class="fas fa-hotel"></i> Alojamientos</a>
      <a href="paquetes.php" class="nav-item"><i class="fas fa-suitcase-rolling"></i> Paquetes</a>
      <a href="autos.php" class="nav-item"><i class="fas fa-car"></i> Autos</a>
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

    <!-- Acciones -->
    <div class="navbar-right d-flex gap-3 align-items-center">
  <?php if (isset($_SESSION['usuario'])): ?>
    <a href="cerrar_sesion.php" class="nav-item"><i class="fas fa-right-from-bracket"></i> Cerrar sesión</a>
  <?php else: ?>
    <div class="d-flex gap-3 border-start ps-3">
      <a href="inicio_sesion.php" class="nav-item"><i class="fas fa-right-to-bracket"></i> Iniciar sesión</a>
      <a href="crear_cuenta.php" class="nav-item"><i class="fas fa-user-plus"></i> Registrarse</a>
    </div>
  <?php endif; ?>
  <a href="carrito.php" class="nav-item cart"><i
            class="fas fa-shopping-cart"></i>Carrito(<?php echo $contador_carrito; ?>)</a>
    
</div>
</nav>

<!-- Carrusel -->
<div class="container my-5">
  <h2 class="mb-4 text-center">Explora nuestras opciones destacadas</h2>
  <div id="carouselMixto" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php
      $grupos = array_chunk($datos, 3);
      foreach ($grupos as $i => $grupo):
      ?>
      <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
        <div class="row">
          <?php foreach ($grupo as $item): 
            $pagina = [
              'vuelo' => 'vuelos.php',
              'alojamiento' => 'alojamientos.php',
              'paquete' => 'paquetes.php'
            ][$item['tipo']];
            $precio = $item['tipo'] === 'vuelo' ? $item['PRECIO'] : $item['precio'];
          ?>
          <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
              <img src="<?= htmlspecialchars($item['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['nombre']) ?>">
              <div class="card-body d-flex flex-column">
                <h6 class="text-muted text-uppercase mb-1"><?= ucfirst($item['tipo']) ?></h6>
                <h5 class="card-title"><?= htmlspecialchars($item['nombre']) ?></h5>
                <p class="card-text">Salida: <strong><?= date('d/m/Y', strtotime($item['fecha_salida'])) ?></strong></p>
                <h6 class="text-primary mb-3">$<?= number_format($precio, 2) ?></h6>
                <a href="<?= $pagina ?>?id=<?= $item['id'] ?>" class="btn btn-primary mt-auto">Ver más</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Controles -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselMixto" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselMixto" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</div>

</body>
</html>

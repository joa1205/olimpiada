<?php
include 'conexion.php';
session_start();

// Traer 2 random de cada tabla
$sqlVuelos = "SELECT id, nombre, PRECIO, imagen, fecha_ida as fecha_salida, 'vuelo' AS tipo FROM pasaje ORDER BY RAND() LIMIT 2";
$sqlAlojamientos = "SELECT id, nombre, precio, imagen, fecha_ingreso as fecha_salida, 'alojamiento' AS tipo FROM alojamiento ORDER BY RAND() LIMIT 2";
$sqlPaquetes = "SELECT id, nombre, precio, imagen, fecha_ida as fecha_salida, 'paquete' AS tipo FROM paquetes ORDER BY RAND() LIMIT 2";

// Ejecutar y unir
$res1 = mysqli_query($conexion, $sqlVuelos);
$res2 = mysqli_query($conexion, $sqlAlojamientos);
$res3 = mysqli_query($conexion, $sqlPaquetes);

$datos = array_merge(
  mysqli_fetch_all($res1, MYSQLI_ASSOC),
  mysqli_fetch_all($res2, MYSQLI_ASSOC),
  mysqli_fetch_all($res3, MYSQLI_ASSOC)
);

// Mezclar
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
</head>
<body>
  <nav>
    <div class="navbar">
      <!-- Usuario -->
      <div class="navbar-left">
        <?php if (isset($_SESSION['usuario'])): ?>
          <span class="nav-item user-info">
            <i class="fas fa-user"></i>
            <?php echo htmlspecialchars($_SESSION['usuario']); ?>
          </span>
        <?php endif; ?>
      </div>

      <!-- Links -->
      <div class="nav-links">
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

  <!-- Links centrados -->
  <div class="nav-links">
    <a href="index.php" class="nav-item">
      <i class="fas fa-house"></i>
      Inicio
    </a>
    <a href="vuelos.php" class="nav-item active">
      <i class="fas fa-plane"></i>
      Vuelos
    </a>
    <a href="alojamientos.php" class="nav-item">
      <i class="fas fa-hotel"></i>
      Alojamientos
    </a>
    <a href="paquetes.php" class="nav-item">
      <i class="fas fa-suitcase-rolling"></i>
      Paquetes
    </a>
    <a href="autos.php" class="nav-item">
      <i class="fas fa-car"></i>
      Autos
    </a>
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

  <!-- Acciones a la derecha -->
  <div class="navbar-right">
    <?php if (isset($_SESSION['usuario'])): ?>
      <a href="cerrar_sesion.php" class="nav-item">
        <i class="fas fa-right-from-bracket"></i>
        Cerrar sesión
      </a>
    <?php else: ?>
      <a href="inicio_sesion.php" class="nav-item">
        <i class="fas fa-right-to-bracket"></i>
        Iniciar sesión
      </a>
      <a href="crear_cuenta.php" class="nav-item">
        <i class="fas fa-user-plus"></i>
        Registrarse
      </a>
    <?php endif; ?>
    <a href="#" class="nav-item cart">
      <i class="fas fa-shopping-cart"></i>
      Carrito(0)
    </a>
  </div>
</div>

  </nav>

  <!-- Carrusel de opciones -->
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
                <img src="img/<?= htmlspecialchars($item['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['nombre']) ?>">
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

  <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exitoso'): ?>
    <div class="alert alert-success text-center">✅ Registrado exitosamente</div>
  <?php endif; ?>
</body>
</html>

<style>

</style>
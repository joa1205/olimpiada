<?php
include 'conexion.php';
session_start();

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
  $_SESSION['carrito'] = [];
}

// Añadir al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_vuelo'])) {
  $idProducto = intval($_POST['id_vuelo']);
  if (isset($_SESSION['carrito'][$idProducto])) {
    $_SESSION['carrito'][$idProducto]++;
  } else {
    $_SESSION['carrito'][$idProducto] = 1;
  }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Vuelos</title>

  <link rel="stylesheet" href="/olimpiada/style.css">


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <nav>
    <div class="navbar">
      <div class="navbar-left">
        <?php if (isset($_SESSION['usuario'])): ?>
          <span class="nav-item user-info">
            <i class="fas fa-user"></i>
            <?php echo htmlspecialchars($_SESSION['usuario']); ?>
          </span>
        <?php endif; ?>
      </div>

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
      </div>

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
        <a href="carrito.php" class="nav-item cart">
          <i class="fas fa-shopping-cart"></i>
          Carrito(<?php echo array_sum($_SESSION['carrito']); ?>)
        </a>
      </div>
    </div>
  </nav>

  <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
    <div style="text-align: center; margin: 20px;">
      <a href="formulario_agregar_vuelo.php" class="btn-agregar-vuelo">Agregar nuevo vuelo ✈️</a>
    </div>
  <?php endif; ?>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_vuelo_id'])) {
    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
      $idEliminar = intval($_POST['eliminar_vuelo_id']);
      $sqlEliminar = "DELETE FROM pasaje WHERE id = $idEliminar";
      mysqli_query($conexion, $sqlEliminar);
    }
  }

  $sql = "SELECT * FROM pasaje";
  $listavuelos = mysqli_query($conexion, $sql);
  $listaDatos = mysqli_fetch_all($listavuelos, MYSQLI_ASSOC);
  ?>

  <div class="cards-container">
    <?php foreach ($listaDatos as $vuelos): ?>
      <div class="card">
        <div class="card-img">
          <img src="<?php echo $vuelos['imagen']; ?>" alt="">
        </div>
        <div class="card-content">
          <p class="package-label">VUELO</p>
          <h2 class="destination"><?php echo $vuelos['lugar_de_llegada']; ?></h2>
          <div class="duration"><?php echo $vuelos['duracion']; ?></div>
          <div class="rating">
            <span class="score"><?php echo $vuelos['calificacion']; ?>/5</span>
            <span class="stars">
              <?php
              for ($i = 0; $i < intval($vuelos['estrellas']); $i++) {
                echo "★";
              }
              for ($i = intval($vuelos['estrellas']); $i < 5; $i++) {
                echo "☆";
              }
              ?>
            </span>
          </div>
          <p class="departure">Saliendo desde <?php echo $vuelos['lugar_de_salida']; ?> en <?php echo $vuelos['metodo_de_transporte']; ?></p>
          <div class="price-section">
            <p class="price">$<?php echo $vuelos['PRECIO']; ?></p>
            <form method="post">
              <input type="hidden" name="id_vuelo" value="<?php echo $vuelos['id']; ?>">
              <input type="submit" class="boton-carrito" value="Añadir al carrito">
            </form>

            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
              <form method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este vuelo?');">
                <input type="hidden" name="eliminar_vuelo_id" value="<?php echo $vuelos['id']; ?>">
                <input type="submit" value="Eliminar vuelo 🗑️" class="btn-eliminar">
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</body>
</html>
<?php
include 'conexion.php';
session_start(); // MUY importante para acceder a $_SESSION


?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Navbar</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
</head>
<body>
    <nav>
    <nav>
  <div class="navbar">
  <!-- Usuario a la izquierda -->
<div class="navbar-left">
  <?php if (isset($_SESSION['usuario'])): ?>
    <span class="nav-item user-info">
      <i class="fas fa-user"></i>
      <?php echo htmlspecialchars($_SESSION['usuario']); ?>
    </span>
  <?php endif; ?>
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
?>

  <?php
  
  $sql = "SELECT * FROM alojamiento";
  $listaalojamientos = mysqli_query($conexion, $sql);
  $listaDatos = mysqli_fetch_all($listaalojamientos, MYSQLI_ASSOC);
  ?>

  <div class="cards-container">
    <?php foreach ($listaDatos as $alojamientos) { ?>
  <div class="card">
    <div class="card-img">
      <img src="<?php echo $alojamientos['imagen']; ?>" alt="">
    </div>
    <div class="card-content">
      <p class="package-label">alojamiento</p>
      <h2 class="direccion"><?php echo $alojamientos['direccion']; ?></h2>
      <div class="fechain"><?php echo $alojamientos['fecha de entrada']; ?></div>
      <div class="rating">
        <span class="score"><?php echo $alojamientos['calificacion']; ?>/5</span>
        <span class="stars">
          <?php
          for ($i = 0; $i < intval($alojamientos['estrellas']); $i++) {
            echo "★";
          }
          for ($i = intval($alojamientos['estrellas']); $i < 5; $i++) {
            echo "☆";
          }
          ?>
        </span>
      </div>
      <p class="departure">Saliendo desde <?php echo $alojamientos['lugar_de_salida']; ?> en <?php echo $alojamientos['metodo_de_transporte']; ?></p>
      <div class="price-section">
        <p class="price"><?php echo $alojamientos['PRECIO']; ?></p>
        <form method="post">
          <input type="hidden" name="id_vuelo" value="<?php echo $alojamientos['id']; ?>">
          <input type="submit" value="Añadir al carrito" name="añadir">
        </form>

        <!-- Botón solo para admin -->
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
          <form method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este vuelo?');">
            <input type="hidden" name="eliminar_vuelo_id" value="<?php echo $alojamientos['id']; ?>">
            <input type="submit" value="Eliminar vuelo 🗑️" class="btn-eliminar">
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php } ?>

  </div>

</body>
</html>

<style>
    .navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #f8f8f8;
  padding: 10px 30px;
  border-bottom: 2px solid #ddd;
  position: relative;
}

.navbar-left,
.navbar-right {
  display: flex;
  align-items: center;
}

.nav-links {
  display: flex;
  align-items: center;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
}

.nav-item {
  text-align: center;
  margin: 0 10px;
  color: #555;
  text-decoration: none;
  font-size: 14px;
  transition: color 0.3s ease;
}

.nav-item i {
  font-size: 20px;
  display: block;
  margin-bottom: 5px;
}

.nav-item:hover {
  color: #3f0071;
}

.user-info {
  font-weight: bold;
  font-size: 18px;
  color: #3f0071;
}

.cart {
  position: relative;
}
  </style>
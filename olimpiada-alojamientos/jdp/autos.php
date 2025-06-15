<?php
include 'conexion.php';
session_start(); // Asegúrate de iniciar la sesión
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
      <a href="formulario_agregar_auto.php" class="btn-agregar-auto">Agregar nuevo vehiculo</a>
    </div>
  <?php endif; ?>
    <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_auto_id'])) {
  if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    $idEliminar = intval($_POST['eliminar_auto_id']);
    $sqlEliminar = "DELETE FROM autos WHERE id = $idEliminar";
    mysqli_query($conexion, $sqlEliminar);
  }
}
?>

  <?php
  
  $sql = "SELECT * FROM autos";
  $listaautos = mysqli_query($conexion, $sql);
  $listaDatos = mysqli_fetch_all($listaautos, MYSQLI_ASSOC);
  ?>

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
          for ($i = 0; $i < intval($autos['estrellas']); $i++) {
            echo "★";
          }
          for ($i = intval($autos['estrellas']); $i < 5; $i++) {
            echo "☆";
          }
          ?>
        </span>
      </div>
      <div class="price-section">
        <p class="price"><?php echo $autos['precio']; ?></p>
        <form method="post">
          <input type="hidden" name="id_autos" value="<?php echo $autos['id']; ?>">
          <input type="submit" value="Añadir al carrito" name="añadir">
      </div>
    </div>   
        </form>
          <!-- Botón de modificar solo para admin -->
          <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
          <form action="modificar_auto.php" method="get" style="margin-top: 5px;">
          <input type="hidden" name="id_autos" value="<?php echo $autos['id']; ?>">
        <input type="submit" value="Modificar vehiculo✏️" class="btn-modificar">
       </form>
        <?php endif; ?>

        <!-- Botón solo para admin -->
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
          <form method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este vehiculo?');">
            <input type="hidden" name="eliminar_auto_id" value="<?php echo $autos['id']; ?>">
            <input type="submit" value="Eliminar auto 🗑️" class="btn-eliminar">
          </form>
        <?php endif; ?>
    
  </div>
    <?php } ?>

</div>

</body>

</html>

<style>
  .btn-eliminar {
  margin-top: 10px;
  padding: 6px 12px;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.3s ease;
}

.btn-eliminar:hover {
  background-color: #b02a37;
}

  .btn-agregar-auto {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: background-color 0.3s ease;
  }

  .btn-agregar-auto:hover {
    background-color: #0056b3;
  }

  .cards-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin: 40px auto;
    max-width: 1200px;
  }

  .card {
    width: 320px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', sans-serif;
    background-color: white;
  }

  .card-img img {
    width: 100%;
    height: 180px;
    object-fit: cover;
  }

  .duration {
    bottom: 8px;
    left: 8px;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 6px;
    display: inline-block;
  }

  .card-content {
    padding: 16px;
  }

  .package-label {
    font-size: 12px;
    color: #777;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .destination {
    font-size: 18px;
    margin: 4px 0;
    color: #333;
  }

  .rating {
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 8px 0;
  }

  .score {
    background-color: #2ecc71;
    color: white;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 14px;
  }

  .stars {
    color: #f39c12;
    font-size: 14px;
  }

  .departure {
    font-size: 14px;
    color: #444;
    margin: 2px 0;
  }

  .price-section {
    margin: 12px 0;
  }

  .price {
    font-size: 20px;
    font-weight: bold;
    color: #222;
  }

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
.btn-modificar {
  margin-top: 6px;
  padding: 6px 12px;
  background-color: #ffc107;
  color: #333;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.3s ease;
}

.btn-modificar:hover {
  background-color: #e0a800;
}

</style>

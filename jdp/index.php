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
</body>
<?php if (isset($_GET['registro']) && $_GET['registro'] === 'exitoso'): ?>
  <div class="mensaje-exito">
    ✅ Registrado exitosamente
  </div>
<?php endif; ?>
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

  .mensaje-exito {
    background-color: #d4edda;
    color: #155724;
    border: 2px solid #c3e6cb;
    padding: 15px 20px;
    margin: 20px auto;
    width: fit-content;
    border-radius: 8px;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
  }


</style>

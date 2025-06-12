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
        <a href="" class="nav-item cart">
            <i class="fas fa-shopping-cart"></i>
            Carrito(0)
        </a>
        </div>
    </nav>
  

</body>
</html>

<style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .navbar {
      display: flex;
      justify-content: center;
      background-color: #f8f8f8;
      padding: 10px 0;
      border-bottom: 2px solid #ddd;
    }

    .nav-item {
      text-align: center;
      margin: 0 20px;
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
    .cart {
      position: absolute;
      right: 30px;
      top: 3%;
      transform: translateY(-50%);
    }
  </style>
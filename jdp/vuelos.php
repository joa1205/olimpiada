<?php
include 'conexion.php';
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
        Carrito(<php $contador ?>)
      </a>
    </div>
  </nav>
  <?php
  $sql = "SELECT * FROM pasaje";
  $listavuelos = mysqli_query($conexion, $sql);

  // Convertimos el resultado en un array asociativo
  $listaDatos = mysqli_fetch_all($listavuelos, MYSQLI_ASSOC);
  ?>
  <?php foreach ($listaDatos as $vuelos) { ?>
    <div class="card">
      <div class="card-img">
        <img src="<?php echo $vuelos['imagen']; ?>" alt="">
        <div class="duration">13 DÍAS / 12 NOCHES</div>
      </div>
      <div class="card-content">
        <p class="package-label">PAQUETE</p>
        <h2 class="destination"><?php echo $vuelos['lugar_de_llegada']; ?></h2>
        <div class="rating">
          <span class="score"><?php echo $vuelos['paquete']; ?></span>
          <span class="stars">★★★</span>
        </div>
        <p class="departure">Saliendo desde <?php echo $vuelos['lugar_de_salida']; ?> en
          <?php echo $vuelos['metodo_de_transporte']; ?>
        </p>
        <div class="price-section">
          <p class="price"><?php echo $vuelos['PRECIO']; ?></p>
          <form action="" method="post">
            <input type="button" value="Añadir al carrito" name=añadir>
          </form>

        </div>
      </div>
    </div>

  <?php } ?>







</body>

</html>

<style>
  .card {
    width: 320px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', sans-serif;
    background-color: white;
  }

  .card-img {
    background-size: cover;
    background-position: center;
    height: 180px;
    position: relative;
  }

  .duration {
    position: absolute;
    bottom: 8px;
    left: 8px;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 6px;
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

  .departure,
  .details {
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

  .note {
    font-size: 12px;
    color: #666;
  }

  .footer {
    border-top: 1px solid #eee;
    margin-top: 12px;
    padding-top: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
  }

  .points {
    color: #333;
  }

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
    color: rgb(0, 20, 202);
  }

  .cart {
    position: absolute;
    right: 30px;
    top: 3%;
    transform: translateY(-50%);
  }

  .card-img {
    position: relative;
    overflow: hidden;
  }

  .card-img img {
    width: 100%;
    height: auto;
    display: block;
    object-fit: cover;
    max-height: 180px;
    /* Altura máxima */
    border-bottom: 1px solid #ddd;
  }
</style>
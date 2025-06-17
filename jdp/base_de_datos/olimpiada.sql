-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-06-2025 a las 23:34:40
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `olimpiada`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alojamiento`
--

CREATE TABLE `alojamiento` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `mapalink` varchar(255) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `capacidad` enum('individual','2 personas','4 personas') DEFAULT NULL,
  `seguro` int(10) DEFAULT NULL,
  `precio` decimal(10,0) DEFAULT NULL,
  `duracion` varchar(30) NOT NULL,
  `calificacion` int(5) NOT NULL,
  `estrellas` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `alojamiento`
--

INSERT INTO `alojamiento` (`id`, `nombre`, `imagen`, `direccion`, `fecha_ingreso`, `fecha_salida`, `capacidad`, `seguro`, `precio`, `duracion`, `calificacion`, `estrellas`) VALUES
(1, 'las 3 leches', 'https://la100.cienradios.com.ar/resizer/v2/LWXNF4KGEZA3BOFP7NZTPJ6TWU.jpg?auth=5c51dafa95045f443b706596accb4e8b0c7813f444477e521be6a4419b96fcb3&width=800&height=400', 'Chubut 1285', '2025-06-14', '2050-06-14', 'individual', 0, 1, '25 años/ 12noches', 5, 5);

-- --------------------------------------------------------

-- ------------------------------------------------------------
-- Estructura de tabla para la tabla `autos`
-- ------------------------------------------------------------
CREATE TABLE `autos` (
  `id` INT(10) NOT NULL,
  `nombre` VARCHAR(100) DEFAULT NULL,
  `imagen` VARCHAR(255) DEFAULT NULL,
  `capacidad` ENUM('2 personas','4 personas','+5 personas') DEFAULT NULL,
  `fecha_deposito` DATE DEFAULT NULL,
  `fecha_devolucion` DATE DEFAULT NULL,
  `calificacion` FLOAT DEFAULT NULL,
  `estrellas` INT(11) DEFAULT NULL,
  `precio` DECIMAL(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ------------------------------------------------------------
-- Estructura de tabla para la tabla `carrito`
-- ------------------------------------------------------------
CREATE TABLE `carrito` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` INT(11) DEFAULT NULL,
  `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `estado` ENUM('activo','comprado','cancelado') DEFAULT 'activo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id`, `id_usuario`, `fecha_creacion`, `estado`) VALUES
(1, 6, '2025-06-14 16:52:51', 'activo'),
(2, 4, '2025-06-15 18:09:15', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `dni` int(8) NOT NULL,
  `nombre_y_apellido` varchar(40) NOT NULL,
  `fecha_de_nacimiento` date NOT NULL,
  `sexo` enum('Masculino','Femenino') NOT NULL,
  `gmail` varchar(50) NOT NULL,
  `numero_telefonico` int(20) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `provincia` varchar(30) NOT NULL,
  `localidad` varchar(70) NOT NULL,
  `domicilio` varchar(50) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`dni`, `nombre_y_apellido`, `fecha_de_nacimiento`, `sexo`, `gmail`, `numero_telefonico`, `pais`, `provincia`, `localidad`, `domicilio`, `id_usuario`) VALUES
(1111111, '111', '2000-05-12', 'Masculino', 'mateomoyano@gmail.com', 2147483647, 'Argentina', 'Cordoba', 'Montevideo', 'alcachofas333', 4),
(47825536, 'mateo moyano', '2000-05-12', 'Masculino', 'mateomoyano@gmail.com', 2147483647, 'Argentina', 'Cordoba', 'Montevideo', 'alcachofas333', 3),
(47825537, 'mateo moyano', '2007-12-12', 'Masculino', 'mateomoyano@gmail.com', 2147483647, 'Argentina', 'Cordoba', 'Montevideo', 'alcachofas333', 0),
(47825539, 'Pepito Hernandez', '1212-12-12', 'Masculino', 'pepito@gmail.com', 2147483647, 'Argentina', 'cordoba', 'Santa Fe', 'San luis 25', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_carrito`
--

CREATE TABLE `detalle_carrito` (
  `id` int(11) NOT NULL,
  `id_carrito` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `tipo_producto` enum('vuelo','alojamiento','paquete','auto') NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_carrito`
--

INSERT INTO `detalle_carrito` (`id`, `id_carrito`, `id_producto`, `tipo_producto`, `cantidad`, `precio_unitario`) VALUES
(10, 1, 8, 'vuelo', 4, 300000.00),
(11, 2, 8, 'vuelo', 2, 300000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquete`
--

CREATE TABLE `paquetes` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `lugar_salida` varchar(100) DEFAULT NULL,
  `lugar_de_llegada` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_ida` date DEFAULT NULL,
  `fecha_vuelta` date DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `paquete` enum('individual','grupal','familiar') DEFAULT NULL,
  `calificacion` float DEFAULT NULL,
  `estrellas` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pasaje`
--

CREATE TABLE `pasaje` (
  `id` int(10) NOT NULL,
  `lugar_de_salida` varchar(100) DEFAULT NULL,
  `lugar_de_llegada` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_ida` date DEFAULT NULL,
  `fecha_vuelta` date DEFAULT NULL,
  `metodo_de_transporte` enum('avion','colectivo') DEFAULT NULL,
  `paquete` enum('individual','grupo','familia') DEFAULT NULL,
  `PRECIO` decimal(10,2) DEFAULT NULL,
  `duracion` varchar(255) DEFAULT NULL,
  `calificacion` float DEFAULT NULL,
  `estrellas` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `pasaje`
--

INSERT INTO `pasaje` (`id`, `lugar_de_salida`, `lugar_de_llegada`, `imagen`, `fecha_ida`, `fecha_vuelta`, `metodo_de_transporte`, `paquete`, `PRECIO`, `duracion`, `calificacion`, `estrellas`) VALUES
(8, 'chile', 'Madrid', 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4nr3c2vPWvAbyQ-NXg4YA96c0ObhBtOSJVOkN9zTzsBnw8AMVdTt4c0iLgJJ-Zc4e1FB-HxfDLoXKn4QZE2sgIL1-XIKyRy1dmvqHNl9VzHPg1OKckumF6DR56wlrxxd4WcycY7eKQ=w540-h312-n-k-no', '1212-12-12', '1212-12-12', 'avion', 'individual', 300000.00, '1 día/ 0 noches', 3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(10) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `id_viaje` int(10) DEFAULT NULL,
  `id_alojamiento` int(10) DEFAULT NULL,
  `id_paquetes` int(10) DEFAULT NULL,
  `id_autos` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `id_viaje`, `id_alojamiento`, `id_paquetes`, `id_autos`) VALUES
(4, 'oclahoma', 450000.00, 7, NULL, NULL, NULL),
(3, 'oclahoma', 300000.00, 6, NULL, NULL, NULL),
(5, 'chile', 300000.00, 8, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('admin','cliente') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `contraseña`, `rol`) VALUES
(1, 'moyanito', '$2y$10$Ufz2oa0Uae2TwA8ZvSAxx.t', 'cliente'),
(2, 'putita', '$2y$10$ZvKPkRosyYMWiWZlTfgEm.8', 'cliente'),
(3, 'putita333', '$2y$10$DWy8SHH5bd7sHWlqQhrM2ui', 'cliente'),
(4, '111', '$2y$10$4fJC0h.Gy7h7NomTmaypN.oMQ6jvXxv7JLg/ZBXDpKcPWK618rDUG', 'admin'),
(5, 'pirulito', '$2y$10$xvw287ghIONHl88ux1hreeFbnRfANDALwpazsLttykbNK1NRJr8dq', 'cliente'),
(6, 'pirulito123', '$2y$10$jw5l8bhCk9XfWcIuQFi.cuOBgMFCxbf3JvN9ZCz6upkE5KpFxi8SG', 'cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(10) NOT NULL,
  `id_cliente` int(10) NOT NULL,
  `nombre_producto` varchar(100) DEFAULT NULL,
  `fecha_venta` date DEFAULT NULL,
  `precio_total` int(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alojamiento`
--
ALTER TABLE `alojamiento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `autos`
--
ALTER TABLE `autos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`dni`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_carrito` (`id_carrito`);

--
-- Indices de la tabla `paquete`
--
ALTER TABLE `paquete`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pasaje`
--
ALTER TABLE `pasaje`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_viaje` (`id_viaje`),
  ADD KEY `id_alojamiento` (`id_alojamiento`),
  ADD KEY `id_paquetes` (`id_paquetes`),
  ADD KEY `id_autos` (`id_autos`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alojamiento`
--
ALTER TABLE `alojamiento`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `autos`
--
ALTER TABLE `autos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `paquete`
--
ALTER TABLE `paquete`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pasaje`
--
ALTER TABLE `pasaje`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  ADD CONSTRAINT `detalle_carrito_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

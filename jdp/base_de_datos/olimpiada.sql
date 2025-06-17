-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-06-2025 a las 20:58:40
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

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
  `direccion` varchar(50) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `capacidad` enum('individual','2 personas','4 personas') DEFAULT NULL,
  `seguro` int(10) DEFAULT NULL,
  `precio` decimal(10,0) DEFAULT NULL,
  `duracion` varchar(30) NOT NULL,
  `calificacion` int(5) NOT NULL,
  `estrellas` int(5) NOT NULL,
  `mapalink` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `alojamiento`
--

INSERT INTO `alojamiento` (`id`, `nombre`, `imagen`, `direccion`, `fecha_ingreso`, `fecha_salida`, `capacidad`, `seguro`, `precio`, `duracion`, `calificacion`, `estrellas`, `mapalink`) VALUES
(1, 'las 5 leches', 'https://la100.cienradios.com.ar/resizer/v2/LWXNF4KGEZA3BOFP7NZTPJ6TWU.jpg?auth=5c51dafa95045f443b706596accb4e8b0c7813f444477e521be6a4419b96fcb3&width=800&height=400', 'Chubut 1285', '2025-06-14', '2050-06-14', 'individual', 0, 1, '25 años/ 12noches', 5, 5, 'no'),
(17, 'porno 2', 'xd seo', 'calle siempreviva 1826', '2025-06-18', '2025-06-25', '2 personas', 30000, 300000, '13 dias/ 12 noches', 5, 5, 'colombia');

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
(1, 6, '2025-06-14 16:52:51', 'comprado'),
(2, 4, '2025-06-15 18:09:15', 'comprado'),
(3, 7, '2025-06-15 19:14:12', 'comprado'),
(4, 4, '2025-06-15 19:37:30', 'comprado'),
(5, 4, '2025-06-15 19:38:43', 'comprado'),
(6, 4, '2025-06-15 19:39:40', 'comprado'),
(7, 4, '2025-06-15 19:40:17', 'comprado'),
(8, 4, '2025-06-15 19:54:52', 'comprado'),
(9, 8, '2025-06-15 20:17:49', 'comprado'),
(10, 8, '2025-06-15 20:21:55', 'comprado'),
(11, 7, '2025-06-16 15:29:31', 'comprado'),
(12, 7, '2025-06-16 15:49:06', 'comprado'),
(13, 4, '2025-06-16 15:51:15', 'comprado'),
(14, 7, '2025-06-16 15:51:53', 'comprado'),
(15, 4, '2025-06-17 09:45:36', 'comprado'),
(16, 7, '2025-06-17 12:10:57', 'comprado'),
(17, 7, '2025-06-17 12:12:38', 'comprado'),
(18, 4, '2025-06-17 12:13:55', 'activo'),
(19, 7, '2025-06-17 12:20:13', 'comprado');

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
(47825539, 'Pepito Hernandez', '1212-12-12', 'Masculino', 'pepito@gmail.com', 2147483647, 'Argentina', 'cordoba', 'Santa Fe', 'San luis 25', 6),
(49323669, 'agustin hartemann', '2009-02-11', 'Masculino', 'fvpijnepifjvrnewpifuvner@gmail.com', 89, 'Peru', 'Santa Fe', 'frontera', 'asdada', 8),
(2147483647, 'Elias M', '2025-07-05', 'Masculino', 'qadadasd@asdasdas', 2147483647, 'Peru', 'Santa Fe', 'frontera', 'asdada', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `id_usuario`, `fecha`, `total`) VALUES
(12, 4, '2025-06-16 15:51:18', 600.00),
(13, 7, '2025-06-16 15:51:55', 600.00),
(14, 4, '2025-06-17 09:45:40', 1200.00),
(15, 6, '2025-06-17 11:24:35', 1200600.00),
(16, 7, '2025-06-17 12:10:59', 0.00),
(17, 7, '2025-06-17 12:12:50', 1200.00),
(18, 7, '2025-06-17 12:20:18', 300000.00);

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
(17, 2, 9, 'vuelo', 1, 100.00),
(18, 4, 9, 'vuelo', 4, 100.00),
(19, 4, 10, 'vuelo', 3, 100000.00),
(20, 5, 9, 'vuelo', 1, 100.00),
(21, 6, 9, 'vuelo', 1, 100.00),
(23, 7, 9, 'vuelo', 2, 100.00),
(24, 8, 9, 'vuelo', 3, 100.00),
(25, 9, 9, 'vuelo', 1, 100.00),
(26, 10, 9, 'vuelo', 8, 100.00),
(27, 3, 9, 'vuelo', 1, 100.00),
(28, 11, 11, 'vuelo', 1, 600.00),
(29, 12, 12, 'vuelo', 1, 1.85),
(30, 13, 13, 'vuelo', 1, 600.00),
(31, 14, 13, 'vuelo', 1, 600.00),
(32, 15, 13, 'vuelo', 2, 600.00),
(33, 1, 13, 'vuelo', 1, 600.00),
(34, 16, 1, 'alojamiento', 2, 0.00),
(35, 17, 13, 'vuelo', 2, 600.00),
(36, 18, 13, 'vuelo', 3, 600.00),
(37, 19, 15, 'vuelo', 1, 300000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
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
  `nombre` varchar(100) DEFAULT NULL,
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

INSERT INTO `pasaje` (`id`, `nombre`, `lugar_de_salida`, `lugar_de_llegada`, `imagen`, `fecha_ida`, `fecha_vuelta`, `metodo_de_transporte`, `paquete`, `PRECIO`, `duracion`, `calificacion`, `estrellas`) VALUES
(13, 'Viaje a Yemen', 'sao paoblo Brasil', 'yemen', 'https://fotos.perfil.com/2016/04/01/trim/1280/720/presa.jpg', '2025-06-04', '2025-06-21', 'avion', 'familia', 600.00, '13 dias / 11 noches', 4, 4),
(15, NULL, 'Buenos Aires', 'Madrid', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRp1Ibqi5VFmJWaLoxHpesa23Kvy3RzlqfULw&s', '2025-06-19', '2025-06-23', 'avion', 'familia', 300000.00, '4 días/ 3 noches', 2, 2),
(16, NULL, 'Antartida', 'Peru', 'https://encrypted-tbn0.gstatic.com/licensed-image?q=tbn:ANd9GcSGOvYRx1GIm21vEw2vIwxA0Dz9zvSiMHkPribzTjR3hPgEwLaHAE-e1W1Pb6vBQn2KmJUBlEwm9xi69YyhR5VPK_RI7kSSqIqHLlg2pw', '2025-06-29', '2025-06-30', 'avion', '', 7000000.00, '1 dia/ 0 noches', 4, 4);

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
(10, 'Viaje a Yemen', 600.00, 13, NULL, NULL, NULL),
(11, 'papua neva guiena', 1000000.00, NULL, NULL, NULL, NULL),
(12, 'auto', 4000.00, NULL, NULL, NULL, NULL),
(13, 'auto', 1000000.00, NULL, NULL, NULL, NULL),
(14, 'auto', 1000000.00, NULL, NULL, NULL, 14),
(15, 'Buenos Aires', 300000.00, 15, NULL, NULL, NULL),
(16, 'Antartida', 7000000.00, 16, NULL, NULL, NULL),
(17, 'porno 2', 300000.00, NULL, 17, NULL, NULL),
(18, 'viaje a colombia', 202020.00, NULL, NULL, NULL, NULL);

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
(6, 'pirulito123', '$2y$10$jw5l8bhCk9XfWcIuQFi.cuOBgMFCxbf3JvN9ZCz6upkE5KpFxi8SG', 'cliente'),
(7, '222', '$2y$10$whBOJMNxEMkarSAldkL86eUTqF/aELtuE8P.WeM0gHKZKpJ3EpiZm', 'cliente'),
(8, 'Aguh', '$2y$10$/XUTsey62o.ZNBgnvf02BeFvaQ3Es2xOL1FXcFgB8txZ4iENuOxjy', 'cliente');

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
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_carrito` (`id_carrito`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_compra` (`id_compra`);

--
-- Indices de la tabla `paquetes`
--
ALTER TABLE `paquetes`
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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alojamiento`
--
ALTER TABLE `alojamiento`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `autos`
--
ALTER TABLE `autos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pasaje`
--
ALTER TABLE `pasaje`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `detalle_carrito`
--
ALTER TABLE `detalle_carrito`
  ADD CONSTRAINT `detalle_carrito_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id`);

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

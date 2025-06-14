-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2025 a las 23:45:14
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
  `id` int(10) NOT NULL ,
  `nombre` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `mapalink` varchar(255) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `capacidad` enum('individual','2 personas','4 personas') DEFAULT NULL,
  `seguro` int(10) DEFAULT NULL,
  `precio` decimal(10,0) DEFAULT NULL,
  `duracion` varchar(255) DEFAULT NULL,
  `calificacion` float DEFAULT NULL,
  `estrellas` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autos`
--

CREATE TABLE `autos` (
  `id` int(10) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `capacidad` enum('2 personas','4 personas','+5 personas') DEFAULT NULL,
  `fecha_deposito` date DEFAULT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `calificacion` float DEFAULT NULL,
  `estrellas` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `dni` int(10) NOT NULL,
  `nombre_y_apellido` varchar(40) DEFAULT NULL,
  `usuario` varchar(35) DEFAULT NULL,
  `contraseña` varchar(100) DEFAULT NULL,
  `fecha_de_nacimiento` date DEFAULT NULL,
  `sexo` enum('Masculino','Femenino') DEFAULT NULL,
  `gmail` varchar(50) DEFAULT NULL,
  `numero_telefonico` int(20) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `provincia` varchar(30) DEFAULT NULL,
  `localidad` varchar(70) DEFAULT NULL,
  `domicilio` varchar(50) DEFAULT NULL,
  `rol` enum('admin','usuario') DEFAULT 'usuario'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`dni`, `nombre_y_apellido`, `usuario`, `contraseña`, `fecha_de_nacimiento`, `sexo`, `gmail`, `numero_telefonico`, `pais`, `provincia`, `localidad`, `domicilio`, `rol`) VALUES
(47583374, 'MAXIMO Hanzo', 'MaximitoUwu', '$2y$10$PJcAC8DiOXKqRbPOjG4b8ew1CR1P.WuYHzoKHPpNXUdMdZMCyhseq', '2006-11-01', 'Femenino', 'sdaaaaaaadsa@asddas', 561452, 'Brasilero', 'Sao pablo', 'mai', 'mi casa', 'usuario'),
(47586125, 'Mateo Moyano', 'MATUTUBER', '$2y$10$bjh8ZbvhvdXbqctmOlO5H.lqv7PEwDLy9qGNntxHmGWgaOo01s9Va', '2006-11-22', 'Masculino', 'memoyano@escuelasproa.edu.ar', 561452, 'Argentina', 'Santa Fe', 'queseyo', '2222sssd', 'usuario'),
(2147483647, 'mueeeeeeeeeeee', '111', '$2y$10$eY0tWtA3HfSwxGwiYMICHumFo8CMmaOmvauZSy2HBcSo2MO9GpKLe', '2025-06-10', 'Femenino', 'sdasaddsadas@asd', 2147483647, 'Argentina', 'Cordoba', 'queseyo', '2222sssd', 'admin'),
(222222222, '222', '222', '$2y$10$5DdsGof.d1BEXoaeYIdvl.UOnD9eSqpjx6nuQpv3NCxP2UHZcyMfG', '2025-06-11', 'Masculino', 'sdsdadas@addasda', 0, 'sddsadsa', 'sdadsadas', 'sddsadsa', 'sdasdas', 'usuario'),
(0, 'asdasdasdas', '333', '$2y$10$dKxl2updTS4sIonyWESXl..M8JT.ANXsCG.A826aA0bJrkhwj60xK', '2025-06-03', 'Masculino', 'asdasdas@adas', 12312312, 'dsadasd', 'dasdsa', 'asdasdas', 'dsasdasd', 'usuario'),
(12313123, 'asdasdasdassadasdasda', '555', '$2y$10$Lldak3w6.Qd9Iw47p3/l4uk0Pzif3zdV.k9USGhhR5MnXWx1O3BXC', '2025-06-06', 'Masculino', 'memoyano@escuelasproa.edu.ar', 561452, 'Argentina', 'Sao pablo', 'sddsadsa', 'dsasdasd', 'usuario'),
(1212121212, 'asdasdasdassadasdasda', '666', '$2y$10$r0EzOz0kR.jk1zk/H6Kr0.AIYm94hhwQ7kwYuUtUQdP/MVsYodk.a', '2025-06-13', 'Masculino', 'memoyano@escuelasproa.edu.ar', 2147483647, 'Argentina', 'Sao pablo', 'queseyo', '2222sssd', 'usuario'),
(11111111, 'asdasdasdassadasdasda', '777', '$2y$10$oQs4rkpizl1ucfndJUd42.Co9DrR0cY/N/hQj3QYzlVFirprQTcue', '2025-06-13', 'Masculino', 'memoyano@escuelasproa.edu.ar', 561452, 'Argentina', 'Sao pablo', 'queseyo', 'dsasdasd', 'usuario'),
(32323232, 'mueeeeeeeeeeee', '888', '$2y$10$ScaE1BriKRi1S0e18YSnfeO9.TCVXOh9HPkMdKnpvM3L0mKIWiF/u', '2025-06-05', 'Masculino', 'sdaaaaaaadsa@asddas', 2147483647, 'Argentina', 'Sao pablo', 'queseyo', 'dsasdasd', 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquete`
--

CREATE TABLE `paquete` (
  `id` int(10) NOT NULL,
  `lugar_de_salida` varchar(100) DEFAULT NULL,
  `lugar_de_llegada` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_ida` date DEFAULT NULL,
  `fecha_vuelta` date DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_salida` date DEFAULT NULL,
  `paquete` enum('individual','grupal','familiar') DEFAULT NULL
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
(0, 'sao paoblo Brasil', 'lol', 'https://e00-elmundo.uecdn.es/assets/multimedia/imagenes/2015/07/14/14368666394868_997x0.jpg', '2025-06-04', '2025-06-14', 'avion', 'familia', 600.00, '2dias/1noches', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(10) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `viaje` enum('internacional','nacional') DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `paquete` enum('individual','grupal','familiar') DEFAULT NULL,
  `id_viaje` int(10) DEFAULT NULL,
  `id_alojamiento` int(10) DEFAULT NULL,
  `id_paquetes` int(10) DEFAULT NULL,
  `id_autos` int(10) DEFAULT NULL,
  `fecha_de_salida` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`dni`);

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
  ADD KEY `id_autos` (`id_autos`),
  ADD KEY `fecha_de_salida` (`fecha_de_salida`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

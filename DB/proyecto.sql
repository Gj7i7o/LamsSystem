-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-10-2025 a las 02:24:46
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
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `descrip` text NOT NULL,
  `estado` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`, `descrip`, `estado`) VALUES
(2, 'Gomas', 'Desde decoraciones para el interior del auto', 'Inactivo'),
(6, 'Limpieza', 'Productos de limpieza', 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrada`
--

CREATE TABLE `entrada` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` varchar(10) NOT NULL,
  `idproveedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrada`
--

INSERT INTO `entrada` (`id`, `fecha`, `hora`, `idproveedor`) VALUES
(1, '2024-12-05', '12:34pm', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradaproducto`
--

CREATE TABLE `entradaproducto` (
  `id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` float NOT NULL,
  `idproducto` int(11) NOT NULL,
  `identrada` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entradaproducto`
--

INSERT INTO `entradaproducto` (`id`, `cantidad`, `precio`, `idproducto`, `identrada`) VALUES
(1, 13, 4, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `estado` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id`, `nombre`, `estado`) VALUES
(1, 'Sonax', 'Inactivo'),
(2, 'ford', 'activo'),
(3, 'Chevrolet ', 'Inactivo'),
(4, 'Ferrari', 'Inactivo'),
(5, 'Bugatti', 'activo'),
(6, 'Nissan', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `codigo` varchar(250) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `precio` float NOT NULL,
  `cantidad` int(15) NOT NULL,
  `idcategoria` int(11) NOT NULL,
  `idmarca` int(11) NOT NULL,
  `estado` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `codigo`, `nombre`, `precio`, `cantidad`, `idcategoria`, `idmarca`, `estado`) VALUES
(1, '990-00-123', 'Pur', 29, 0, 2, 3, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `rif` varchar(40) NOT NULL,
  `direccion` text NOT NULL,
  `estado` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id`, `nombre`, `apellido`, `rif`, `direccion`, `estado`) VALUES
(1, 'Carlos', 'Sánchez', 'V-123456789', 'Sector el Viñedo Piso N-1 PB-1 Sector El Viñedo. Valencia Carabobo Zona postal 2001', 'activo'),
(2, 'Miguel', 'Rojas', 'J-311592210', 'Industria Municipal Norte 91-100 Valencia Carabobo Zona Postal 2003', 'activo'),
(3, 'Juan', 'Farias', 'J-407540351', 'Calle Acosta Casa Nro 96 Sector Mercado', 'activo'),
(4, 'Donald', 'Tyson', 'V-123456743', 'Industria Municipal Norte 91-100 Valencia Carabobo Zona Postal 2005', 'Inactivo'),
(6, 'Miguel', 'Farias', 'V-123456789', 'Industria Municipal Norte 91-100 Valencia Carabobo Zona Postal 2003', 'activo'),
(8, 'Carlos', 'Sánchez', 'V-123456789', 'Sector el Viñedo Piso N-1 PB-1 Sector El Viñedo. Valencia Carabobo Zona postal 2001', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida`
--

CREATE TABLE `salida` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` varchar(10) NOT NULL,
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salida`
--

INSERT INTO `salida` (`id`, `fecha`, `hora`, `idusuario`) VALUES
(1, '2024-11-23', '12:21pm', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidaproducto`
--

CREATE TABLE `salidaproducto` (
  `id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` float NOT NULL,
  `idproducto` int(11) NOT NULL,
  `idsalida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salidaproducto`
--

INSERT INTO `salidaproducto` (`id`, `cantidad`, `precio`, `idproducto`, `idsalida`) VALUES
(1, 3, 4, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `correo` varchar(30) DEFAULT NULL,
  `telef` varchar(20) DEFAULT NULL,
  `clave` varchar(100) NOT NULL,
  `rango` varchar(20) NOT NULL,
  `estado` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `usuario`, `nombre`, `apellido`, `correo`, `telef`, `clave`, `rango`, `estado`) VALUES
(1, 'RuiChan', 'Luis', 'Sánchez', 'sanluiscar@gmail.com', '0412-0392977', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'administrador', 'activo'),
(2, 'MayChan', 'Ana', 'Rodríguez', 'anitasalina@gmail.com', '0412-3322110', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'empleado', 'activo'),
(14, 'Necrozma', 'Sebastián', 'Russián', 'sebastian@gmail.com', '0412-1234567', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'empleado', 'activo'),
(15, 'Moisu', 'Moisés', 'Córdova', 'moisuc@gmail.com', '0416-1234578', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'empleado', 'Inactivo'),
(17, 'Fat32', 'Angel', 'Brazón', 'angelbrazon@gmail.com', '0416-1234576', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'empleado', 'activo'),
(18, 'Admin', 'Luis', 'Sánchez', 'lsanchezbalan@gmail.com', '0416-1234578', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'empleado', 'activo'),
(19, 'Rexlord', 'Roy', 'Winchester', 'lsanchezbalan@gmail.com', '0416-1234523', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'empleado', 'activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entrada`
--
ALTER TABLE `entrada`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_proveedor` (`idproveedor`);

--
-- Indices de la tabla `entradaproducto`
--
ALTER TABLE `entradaproducto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`idproducto`),
  ADD KEY `id_entrada` (`identrada`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_producto_categoria` (`idcategoria`),
  ADD KEY `FK_producto_marca` (`idmarca`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `salida`
--
ALTER TABLE `salida`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`idusuario`);

--
-- Indices de la tabla `salidaproducto`
--
ALTER TABLE `salidaproducto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_salida_id_salida` (`idsalida`),
  ADD KEY `FK_salida_id_producto` (`idproducto`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `entrada`
--
ALTER TABLE `entrada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `entradaproducto`
--
ALTER TABLE `entradaproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `salida`
--
ALTER TABLE `salida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `salidaproducto`
--
ALTER TABLE `salidaproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `entrada`
--
ALTER TABLE `entrada`
  ADD CONSTRAINT `entrada_ibfk_1` FOREIGN KEY (`idproveedor`) REFERENCES `proveedor` (`id`);

--
-- Filtros para la tabla `entradaproducto`
--
ALTER TABLE `entradaproducto`
  ADD CONSTRAINT `entradaproducto_ibfk_1` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`id`),
  ADD CONSTRAINT `entradaproducto_ibfk_2` FOREIGN KEY (`identrada`) REFERENCES `entrada` (`id`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `FK_producto_categoria` FOREIGN KEY (`idcategoria`) REFERENCES `categoria` (`id`),
  ADD CONSTRAINT `FK_producto_marca` FOREIGN KEY (`idmarca`) REFERENCES `marca` (`id`);

--
-- Filtros para la tabla `salida`
--
ALTER TABLE `salida`
  ADD CONSTRAINT `salida_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `salidaproducto`
--
ALTER TABLE `salidaproducto`
  ADD CONSTRAINT `FK_salida_id_producto` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`id`),
  ADD CONSTRAINT `FK_salida_id_salida` FOREIGN KEY (`idsalida`) REFERENCES `salida` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

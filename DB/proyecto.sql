-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-01-2026 a las 19:40:48
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
(2, 'Gomas', 'Desde decoraciones para el interior del auto', 'activo'),
(6, 'Limpieza', 'Productos de limpieza', 'activo'),
(7, 'Camioneta', 'Repuestos para camionetas', 'activo'),
(8, 'Gomas', 'Desde decoraciones para el interior del auto', 'activo'),
(9, 'Motos', 'Motocicletas y similares', 'activo'),
(10, 'Aviones', 'Lo que necesitan aviones', 'activo'),
(12, 'especiales 1', 'Motocicletas y similares', 'activo'),
(13, 'Limpieza2', 'hjjjj', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrada`
--

CREATE TABLE `entrada` (
  `id` int(11) NOT NULL,
  `fecha` varchar(20) NOT NULL,
  `hora` varchar(20) NOT NULL,
  `idproveedor` int(11) NOT NULL,
  `total` float NOT NULL,
  `cod_docum` varchar(20) NOT NULL,
  `tipo_pago` varchar(15) NOT NULL DEFAULT 'contado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrada`
--

INSERT INTO `entrada` (`id`, `fecha`, `hora`, `idproveedor`, `total`, `cod_docum`, `tipo_pago`) VALUES
(5, '13/1/2026', '5:43:20 p. m.', 2, 36, 'Za-3333', 'contado'),
(7, '13/1/2026', '6:00:41 p. m.', 2, 60, 'Za-1234', 'contado'),
(8, '13/1/2026', '6:03:22 p. m.', 2, 0.03, 'Za-1232', 'contado'),
(9, '17/1/2026', '10:50:46 a. m.', 4, 86, 'Za-1233', 'contado'),
(10, '17/1/2026', '10:53:45 a. m.', 2, 160, 'Za-1235', 'contado'),
(11, '18/1/2026', '10:05:30 p. m.', 2, 160, '990-00-123', 'contado'),
(12, '18/1/2026', '10:18:02 p. m.', 2, 160, 'Za-2222', 'contado'),
(13, '21/1/2026', '9:41:13 a. m.', 2, 140, '3456', 'contado'),
(14, '21/1/2026', '9:49:39 a. m.', 2, 619, '00001', 'contado'),
(15, '21/1/2026', '9:52:48 a. m.', 2, 30, '0002', 'contado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradaproducto`
--

CREATE TABLE `entradaproducto` (
  `id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` float NOT NULL,
  `idproducto` int(11) NOT NULL,
  `identrada` int(11) NOT NULL,
  `iva` float NOT NULL,
  `sub_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entradaproducto`
--

INSERT INTO `entradaproducto` (`id`, `cantidad`, `precio`, `idproducto`, `identrada`, `iva`, `sub_total`) VALUES
(2, 12, 3, 1, 5, 30, 3),
(6, 3, 20, 9, 7, 30, 60),
(7, 3, 0.01, 1, 8, 30, 0.03),
(8, 4, 14, 1, 9, 30, 56),
(9, 6, 5, 12, 9, 30, 30),
(10, 4, 40, 11, 10, 30, 160),
(11, 4, 40, 1, 11, 30, 160),
(12, 4, 40, 1, 12, 30, 160),
(13, 20, 7, 9, 13, 30, 140),
(14, 20, 4, 9, 14, 30, 80),
(15, 23, 23, 10, 14, 30, 529),
(16, 10, 1, 11, 14, 30, 10),
(17, 10, 1, 1, 15, 30, 10),
(18, 20, 1, 1, 15, 30, 20);

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
(1, 'Sonax', 'inactivo'),
(2, 'Chevrolet', 'activo'),
(3, 'Chevrolet ', 'activo'),
(4, 'Ferrari', 'activo'),
(5, 'Bugatti', 'activo'),
(6, 'Nissan', 'activo'),
(7, 'Shelby', 'activo'),
(8, 'BMW', 'activo'),
(10, '2', 'activo'),
(11, 'Chevrolet1', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id` int(20) NOT NULL,
  `ci` varchar(20) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `telef` varchar(30) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id`, `ci`, `nombre`, `apellido`, `telef`, `correo`, `idusuario`) VALUES
(1, '31464415', 'Luis', 'Sánchez', '0412-0392977', 'lsanchezbalan@gmail.com', 1),
(2, '30484421', 'May', 'Waguri', '0412-0392921', 'maywari@gmail.com', 2);

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
(1, '990-00-123', 'Pur', 29, -9959, 7, 3, 'activo'),
(9, '111-11-222', 'Bujya', 17, 24, 7, 2, 'activo'),
(10, '990-00-123	', 'Moto', 20, 27, 6, 6, 'activo'),
(11, '111-11-223', 'Sierra', 12, 14, 6, 4, 'activo'),
(12, '111-11-234', 'Tuercas de compresor ', 5, 6, 7, 2, 'activo');

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
(1, 'Carlos', 'gomez', 'V-123456789', 'Sector el Viñedo Piso N-1 PB-1 Sector El Viñedo. Valencia Carabobo Zona postal 6158', 'inactivo'),
(2, 'Miguel', 'Rojas', 'J-407540351', 'Industria Municipal Norte 91-100 Valencia Carabobo Zona Postal 2003', 'activo'),
(3, 'Juan', 'Farias', 'J-407540351', 'Calle Acosta Casa Nro 96 Sector Mercado', 'activo'),
(4, 'Donald', 'Tyson', 'V-123456743', 'Industria Municipal Norte 91-100 Valencia Carabobo Zona Postal 2005', 'activo'),
(6, 'Miguel', 'Farias', 'V-123456789', 'Industria Municipal Norte 91-100 Valencia Carabobo Zona Postal 2003', 'activo'),
(8, 'Antonio', 'Martinez', 'V-987654321', 'Calle principal sector el Muco', 'inactivo'),
(9, 'angel', 'brazon', 'V-123456789', 'calle independencia numero 4', 'inactivo'),
(10, 'Luis', 'Hernandez', 'V-123456789', 'Sector el Viñedo Piso N-1 PB-1 Sector El Viñedo. Valencia Carabobo Zona postal 6158', 'activo'),
(12, 'Luis', 'Hernandez', 'V-123456787', 'Sector el Viñedo Piso N-1 PB-1 Sector El Viñedo. Valencia Carabobo Zona postal 6158', 'activo'),
(13, 'aleida', 'figueroa', 'J-13111111', 'guayacan ', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida`
--

CREATE TABLE `salida` (
  `id` int(11) NOT NULL,
  `fecha` varchar(20) NOT NULL,
  `hora` varchar(20) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `total` float NOT NULL,
  `cod_docum` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salida`
--

INSERT INTO `salida` (`id`, `fecha`, `hora`, `idusuario`, `total`, `cod_docum`) VALUES
(3, '16/1/2026', '8:33:56 p. m.', 1, 255, 'Za-1232'),
(4, '16/1/2026', '8:36:46 p. m.', 1, 24.2, '2sa5lL*2'),
(5, '21/1/2026', '9:57:31 a. m.', 1, 340040, '0001');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidaproducto`
--

CREATE TABLE `salidaproducto` (
  `id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` float NOT NULL,
  `idproducto` int(11) NOT NULL,
  `idsalida` int(11) NOT NULL,
  `sub_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salidaproducto`
--

INSERT INTO `salidaproducto` (`id`, `cantidad`, `precio`, `idproducto`, `idsalida`, `sub_total`) VALUES
(3, 15, 17, 1, 3, 255),
(4, 2, 12.1, 9, 4, 24.2),
(5, 10000, 34, 1, 5, 340000),
(6, 20, 2, 9, 5, 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `rango` varchar(20) NOT NULL,
  `estado` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `usuario`, `clave`, `rango`, `estado`) VALUES
(1, 'RuiChan', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'administrador', 'activo'),
(2, 'MayChan', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'empleado', 'activo');

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
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idusuario` (`idusuario`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `entrada`
--
ALTER TABLE `entrada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `entradaproducto`
--
ALTER TABLE `entradaproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `salida`
--
ALTER TABLE `salida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `salidaproducto`
--
ALTER TABLE `salidaproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`id`);

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

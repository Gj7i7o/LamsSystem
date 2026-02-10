-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-02-2026 a las 23:59:25
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
  `estado` varchar(15) NOT NULL,
  `creadoEl` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nombre`, `descrip`, `estado`, `creadoEl`) VALUES
(2, 'Gomas', 'Desde decoraciones para el interior del auto', 'activo', '2026-01-30 03:48:00'),
(6, 'Limpieza', 'Productos de limpieza', 'activo', '2026-01-30 03:48:00'),
(7, 'Camioneta', 'Repuestos para camionetas', 'activo', '2026-01-30 03:48:00'),
(8, 'Gomas', 'Desde decoraciones para el interior del auto', 'activo', '2026-01-30 03:48:00'),
(9, 'Motos', 'Motocicletas y similares', 'activo', '2026-01-30 03:48:00'),
(10, 'Aviones', 'Lo que necesitan aviones', 'activo', '2026-01-30 03:48:00'),
(12, 'especiales 1', 'Motocicletas y similares', 'activo', '2026-01-30 03:48:00'),
(13, 'Limpieza2', 'hjjjj', 'activo', '2026-01-30 03:48:00'),
(14, 'test', 'ITBMS', 'activo', '2026-01-30 03:48:00'),
(15, 'test 2', 'ITBMS 2', 'activo', '2026-01-30 03:48:00'),
(16, 'test 3', 'ITBMS 3', 'activo', '2026-01-30 03:48:00'),
(17, 'Categoria test', 'categoria test', 'inactivo', '2026-01-30 03:48:00'),
(18, 'test 4', 'ITBMS', 'activo', '2026-01-30 03:48:00'),
(19, 'categoria 3', '', 'activo', '2026-02-04 07:59:56');

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
  `tipo_pago` varchar(15) NOT NULL DEFAULT 'contado',
  `idusuario` int(11) DEFAULT NULL,
  `creadoEl` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrada`
--

INSERT INTO `entrada` (`id`, `fecha`, `hora`, `idproveedor`, `total`, `cod_docum`, `tipo_pago`, `idusuario`, `creadoEl`, `estado`) VALUES
(5, '13/1/2026', '5:43:20 p. m.', 2, 36, 'Za-3333', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(7, '13/1/2026', '6:00:41 p. m.', 2, 60, 'Za-1234', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(8, '13/1/2026', '6:03:22 p. m.', 2, 0.03, 'Za-1232', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(9, '17/1/2026', '10:50:46 a. m.', 4, 86, 'Za-1233', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(10, '17/1/2026', '10:53:45 a. m.', 2, 160, 'Za-1235', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(11, '18/1/2026', '10:05:30 p. m.', 2, 160, '990-00-123', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(12, '18/1/2026', '10:18:02 p. m.', 2, 160, 'Za-2222', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(13, '21/1/2026', '9:41:13 a. m.', 2, 140, '3456', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(14, '21/1/2026', '9:49:39 a. m.', 2, 619, '00001', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(15, '21/1/2026', '9:52:48 a. m.', 2, 30, '0002', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(16, '23/1/2026', '23:47:12', 2, 100, '11111111', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(17, '24/1/2026', '18:20:51', 2, 1, '332211', 'credito', NULL, '2026-01-30 03:48:00', 'activo'),
(18, '24/1/2026', '19:49:29', 2, 10, '20260124001', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(19, '24/1/2026', '19:58:59', 2, 80, '20260124002', 'contado', NULL, '2026-01-30 03:48:00', 'activo'),
(20, '30/1/2026', '11:26:07 a. m.', 2, 400, '00-00-222', 'contado', 1, '2026-01-30 11:26:07', 'activo'),
(21, '30/1/2026', '10:10:59 p. m.', 14, 423, '00-00-227', 'credito', 1, '2026-01-30 22:10:59', 'activo'),
(22, '31/1/2026', '10:04:31 p. m.', 14, 80, '11-11-111', 'contado', 1, '2026-01-31 22:04:31', 'activo'),
(23, '31/1/2026', '10:06:59 p. m.', 14, 300, '11-11-112', 'credito', 1, '2026-01-31 22:06:59', 'activo'),
(24, '2/2/2026', '9:20:43 p. m.', 6, 300, '00-11-115', 'contado', 1, '2026-02-02 21:20:43', 'activo'),
(25, '3/2/2026', '9:26:22 p. m.', 2, 40, 'za-222', 'contado', 1, '2026-02-03 21:26:22', 'activo'),
(26, '3/2/2026', '9:32:02 p. m.', 2, 80, 'Za-212', 'contado', 1, '2026-02-03 21:32:02', 'activo'),
(27, '3/2/2026', '9:35:19 p. m.', 12, 140, 'Za-221', 'credito', 1, '2026-02-03 21:35:19', 'activo'),
(28, '4/2/2026', '8:07:31 a. m.', 2, 120, '7765564543', 'credito', 1, '2026-02-04 08:07:31', 'activo'),
(29, '4/2/2026', '9:56:59 a. m.', 14, 60, '2sa5lL*4', 'contado', 1, '2026-02-04 09:56:59', 'activo'),
(30, '4/2/2026', '11:11:38 p. m.', 3, 40, '2-zalll', 'contado', 1, '2026-02-04 23:11:38', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradaproducto`
--

CREATE TABLE `entradaproducto` (
  `id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` float NOT NULL,
  `precioVenta` float NOT NULL DEFAULT 0,
  `idproducto` int(11) NOT NULL,
  `identrada` int(11) NOT NULL,
  `iva` float NOT NULL,
  `sub_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entradaproducto`
--

INSERT INTO `entradaproducto` (`id`, `cantidad`, `precio`, `precioVenta`, `idproducto`, `identrada`, `iva`, `sub_total`) VALUES
(2, 12, 3, 0, 1, 5, 30, 3),
(6, 3, 20, 0, 9, 7, 30, 60),
(7, 3, 0.01, 0, 1, 8, 30, 0.03),
(8, 4, 14, 0, 1, 9, 30, 56),
(9, 6, 5, 0, 12, 9, 30, 30),
(10, 4, 40, 0, 11, 10, 30, 160),
(11, 4, 40, 0, 1, 11, 30, 160),
(12, 4, 40, 0, 1, 12, 30, 160),
(13, 20, 7, 0, 9, 13, 30, 140),
(14, 20, 4, 0, 9, 14, 30, 80),
(15, 23, 23, 0, 10, 14, 30, 529),
(16, 10, 1, 0, 11, 14, 30, 10),
(17, 10, 1, 0, 1, 15, 30, 10),
(18, 20, 1, 0, 1, 15, 30, 20),
(19, 1, 100, 0, 13, 16, 30, 100),
(20, 1, 1, 0, 1, 17, 30, 1),
(21, 1, 10, 0, 13, 18, 30, 10),
(24, 2, 20, 0, 13, 19, 30, 40),
(25, 2, 20, 0, 13, 19, 30, 40),
(26, 20, 20, 0, 11, 20, 30, 400),
(27, 18, 20, 22, 11, 21, 30, 360),
(28, 3, 21, 22, 11, 21, 30, 63),
(29, 4, 20, 22, 11, 22, 30, 80),
(30, 2, 50, 50, 11, 23, 30, 100),
(31, 4, 50, 50, 11, 23, 30, 200),
(32, 10, 30, 30, 9, 24, 30, 300),
(33, 2, 20, 20, 1, 25, 30, 40),
(34, 2, 20, 30, 1, 26, 30, 40),
(35, 2, 20, 30, 11, 26, 30, 40),
(36, 2, 25, 40, 1, 27, 30, 50),
(37, 3, 30, 60, 11, 27, 30, 90),
(38, 20, 6, 10, 9, 28, 30, 120),
(39, 3, 20, 20, 1, 29, 30, 60),
(40, 2, 20, 30, 1, 30, 30, 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_usuario`
--

CREATE TABLE `historial_usuario` (
  `id` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `modulo` varchar(50) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` varchar(20) NOT NULL,
  `hora` varchar(20) NOT NULL,
  `creadoEl` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_usuario`
--

INSERT INTO `historial_usuario` (`id`, `idusuario`, `modulo`, `accion`, `descripcion`, `fecha`, `hora`, `creadoEl`) VALUES
(1, 1, 'Productos', 'modificar', 'Modificó producto ID: 13 - HappyBug', '26/1/2026', '1:10:06 am', '2026-01-30 03:48:00'),
(2, 1, 'Marcas', 'registrar', 'Registró marca: Marca HappyBug', '26/1/2026', '1:10:41 am', '2026-01-30 03:48:00'),
(3, 1, 'Productos', 'registrar', 'Registró producto: test (Código: producto 1110)', '26/1/2026', '1:27:56 am', '2026-01-30 03:48:00'),
(4, 1, 'Proveedores', 'desactivar', 'Desactivó proveedor ID: 13', '29/01/2026', '11:49:49 pm', '2026-01-29 23:49:49'),
(5, 1, 'Categorías', 'desactivar', 'Desactivó categoría ID: 17', '30/01/2026', '11:13:54 am', '2026-01-30 11:13:54'),
(6, 1, 'Productos', 'desactivar', 'Desactivó producto ID: 12', '30/01/2026', '11:18:15 am', '2026-01-30 11:18:15'),
(7, 1, 'Productos', 'registrar', 'Registró producto: Frenos (Código: 01-02-004)', '30/01/2026', '11:19:10 am', '2026-01-30 11:19:10'),
(8, 1, 'Entradas', 'registrar', 'Registró entrada #00-00-222 - Total: $400.00', '30/01/2026', '11:26:07 am', '2026-01-30 11:26:07'),
(9, 1, 'Salidas', 'registrar', 'Registró salida #990-00-15 - Total: $80.00', '30/01/2026', '11:52:51 am', '2026-01-30 11:52:51'),
(10, 1, 'Productos', 'modificar', 'Modificó producto ID: 14 - test', '30/01/2026', '11:55:15 am', '2026-01-30 11:55:15'),
(11, 1, 'Productos', 'registrar', 'Registró producto: test (Código: producto 1111)', '30/01/2026', '11:58:42 am', '2026-01-30 11:58:42'),
(12, 1, 'Salidas', 'registrar', 'Registró salida #990-00-16 - Total: $40.00', '30/01/2026', '12:09:43 pm', '2026-01-30 12:09:43'),
(13, 1, 'Salidas', 'registrar', 'Registró salida #990-00-125 - Total: $420.00', '30/01/2026', '12:11:58 pm', '2026-01-30 12:11:58'),
(14, 1, 'Salidas', 'registrar', 'Registró salida #111-11-226 - Total: $520.00', '30/01/2026', '10:08:21 pm', '2026-01-30 22:08:21'),
(15, 1, 'Entradas', 'registrar', 'Registró entrada #00-00-227 - Total: $423.00', '30/01/2026', '10:10:59 pm', '2026-01-30 22:10:59'),
(16, 1, 'Entradas', 'registrar', 'Registró entrada #11-11-111 - Total: $80.00', '31/01/2026', '10:04:31 pm', '2026-01-31 22:04:31'),
(17, 1, 'Entradas', 'registrar', 'Registró entrada #11-11-112 - Total: $300.00', '31/01/2026', '10:06:59 pm', '2026-01-31 22:06:59'),
(18, 1, 'Entradas', 'registrar', 'Registró entrada #00-11-115 - Total: $300.00', '02/02/2026', '09:20:43 pm', '2026-02-02 21:20:43'),
(19, 1, 'Entradas', 'registrar', 'Registró entrada #za-222 - Total: $40.00', '03/02/2026', '09:26:22 pm', '2026-02-03 21:26:22'),
(20, 1, 'Entradas', 'registrar', 'Registró entrada #Za-212 - Total: $80.00', '03/02/2026', '09:32:02 pm', '2026-02-03 21:32:02'),
(21, 1, 'Entradas', 'registrar', 'Registró entrada #Za-221 - Total: $140.00', '03/02/2026', '09:35:19 pm', '2026-02-03 21:35:19'),
(22, 1, 'Usuarios', 'modificar', 'Modificó usuario ID: 24 - gabriel', '03/02/2026', '10:29:49 pm', '2026-02-03 22:29:49'),
(23, 1, 'Usuarios', 'modificar', 'Modificó usuario ID: 24 - gabriel', '03/02/2026', '10:30:01 pm', '2026-02-03 22:30:01'),
(24, 1, 'Usuarios', 'modificar', 'Modificó usuario ID: 24 - gabriel', '03/02/2026', '10:30:13 pm', '2026-02-03 22:30:13'),
(25, 1, 'Usuarios', 'modificar', 'Modificó usuario ID: 24 - gabriel', '03/02/2026', '10:30:20 pm', '2026-02-03 22:30:20'),
(26, 1, 'Usuarios', 'registrar', 'Registró usuario: PolarKurosaki', '03/02/2026', '11:02:39 pm', '2026-02-03 23:02:39'),
(27, 25, 'Usuarios', 'modificar', 'Modificó usuario ID: 2 - MayChan', '03/02/2026', '11:04:49 pm', '2026-02-03 23:04:49'),
(28, 1, 'Productos', 'registrar', 'Registró producto: relex (Código: 0303055)', '04/02/2026', '07:55:37 am', '2026-02-04 07:55:37'),
(29, 1, 'Productos', 'desactivar', 'Desactivó producto ID: 17', '04/02/2026', '07:55:51 am', '2026-02-04 07:55:51'),
(30, 1, 'Productos', 'activar', 'Activó producto ID: 17', '04/02/2026', '07:57:09 am', '2026-02-04 07:57:09'),
(31, 1, 'Categorías', 'registrar', 'Registró categoría: categoria 3', '04/02/2026', '07:59:56 am', '2026-02-04 07:59:56'),
(32, 1, 'Proveedores', 'desactivar', 'Desactivó proveedor ID: 14', '04/02/2026', '08:01:31 am', '2026-02-04 08:01:31'),
(33, 1, 'Proveedores', 'activar', 'Activó proveedor ID: 14', '04/02/2026', '08:01:42 am', '2026-02-04 08:01:42'),
(34, 1, 'Entradas', 'registrar', 'Registró entrada #7765564543 - Total: $120.00', '04/02/2026', '08:07:31 am', '2026-02-04 08:07:31'),
(35, 1, 'Usuarios', 'desactivar', 'Desactivó usuario ID: 2', '04/02/2026', '08:09:09 am', '2026-02-04 08:09:09'),
(36, 1, 'Entradas', 'registrar', 'Registró entrada #2sa5lL*4 - Total: $60.00', '04/02/2026', '09:56:59 am', '2026-02-04 09:56:59'),
(37, 1, 'Salidas', 'registrar', 'Registró salida #za-22221 - Total: $400.00', '04/02/2026', '09:01:20 pm', '2026-02-04 21:01:20'),
(38, 1, 'Salidas', 'registrar', 'Registró salida #za-22222 - Total: $270.00', '04/02/2026', '09:02:22 pm', '2026-02-04 21:02:22'),
(39, 1, 'Salidas', 'registrar', 'Registró salida #za-2225 - Total: $240.00', '04/02/2026', '09:19:32 pm', '2026-02-04 21:19:32'),
(40, 1, 'Usuarios', 'activar', 'Activó usuario ID: 2', '04/02/2026', '09:32:03 pm', '2026-02-04 21:32:03'),
(41, 1, 'Usuarios', 'desactivar', 'Desactivó usuario ID: 25', '04/02/2026', '09:42:05 pm', '2026-02-04 21:42:05'),
(42, 1, 'Usuarios', 'activar', 'Activó usuario ID: 25', '04/02/2026', '09:43:00 pm', '2026-02-04 21:43:00'),
(43, 1, 'Productos', 'desactivar', 'Desactivó producto ID: 14', '04/02/2026', '09:44:39 pm', '2026-02-04 21:44:39'),
(44, 1, 'Marcas', 'desactivar', 'Desactivó marca ID: 8', '04/02/2026', '09:44:51 pm', '2026-02-04 21:44:51'),
(45, 1, 'Entradas', 'registrar', 'Registró entrada #2-zalll - Total: $40.00', '04/02/2026', '11:11:38 pm', '2026-02-04 23:11:38'),
(46, 1, 'Usuarios', 'modificar', 'Modificó usuario ID: 1 - RuiChan', '06/02/2026', '10:58:12 am', '2026-02-06 10:58:12'),
(47, 1, 'Usuarios', 'modificar', 'Modificó usuario ID: 1 - RuiChan', '06/02/2026', '11:02:35 am', '2026-02-06 11:02:35'),
(48, 1, 'Usuarios', 'modificar', 'Modificó usuario ID: 1 - RuiChan', '06/02/2026', '11:05:46 am', '2026-02-06 11:05:46'),
(49, 1, 'Usuarios', 'modificar', 'Modificó usuario ID: 1 - RuiChan', '06/02/2026', '11:06:18 am', '2026-02-06 11:06:18'),
(50, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '06/02/2026', '11:10:42 am', '2026-02-06 11:10:42'),
(51, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '06/02/2026', '11:11:30 am', '2026-02-06 11:11:30'),
(52, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - gabriel', '06/02/2026', '11:13:18 am', '2026-02-06 11:13:18'),
(53, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30484421 - MayChan', '06/02/2026', '11:24:58 am', '2026-02-06 11:24:58'),
(54, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30484421 - MayChan', '06/02/2026', '11:25:32 am', '2026-02-06 11:25:32'),
(55, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30484421 - MayChan', '06/02/2026', '11:25:39 am', '2026-02-06 11:25:39'),
(56, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 12 - Luis', '07/02/2026', '09:32:52 am', '2026-02-07 09:32:52'),
(57, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 14 - GJ', '07/02/2026', '09:33:08 am', '2026-02-07 09:33:08'),
(58, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 24839795 - gabriel', '07/02/2026', '09:33:35 am', '2026-02-07 09:33:35'),
(59, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '09:34:04 am', '2026-02-07 09:34:04'),
(60, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '09:34:23 am', '2026-02-07 09:34:23'),
(61, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '09:35:03 am', '2026-02-07 09:35:03'),
(62, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '09:35:30 am', '2026-02-07 09:35:30'),
(63, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '09:48:36 am', '2026-02-07 09:48:36'),
(64, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '09:49:11 am', '2026-02-07 09:49:11'),
(65, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '09:49:47 am', '2026-02-07 09:49:47'),
(66, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '09:50:54 am', '2026-02-07 09:50:54'),
(67, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '09:51:16 am', '2026-02-07 09:51:16'),
(68, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '09:51:22 am', '2026-02-07 09:51:22'),
(69, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '09:52:09 am', '2026-02-07 09:52:09'),
(70, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 24839795 - Polar', '07/02/2026', '09:52:20 am', '2026-02-07 09:52:20'),
(71, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 24839795 - Polar', '07/02/2026', '09:56:15 am', '2026-02-07 09:56:15'),
(72, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '10:04:20 am', '2026-02-07 10:04:20'),
(73, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '10:04:31 am', '2026-02-07 10:04:31'),
(74, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '10:04:35 am', '2026-02-07 10:04:35'),
(75, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - Polar', '07/02/2026', '10:05:56 am', '2026-02-07 10:05:56'),
(76, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31839795 - gabriel', '07/02/2026', '10:06:08 am', '2026-02-07 10:06:08'),
(77, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 24839795 - gabriel', '07/02/2026', '10:07:27 am', '2026-02-07 10:07:27'),
(78, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 24839795 - Polar', '07/02/2026', '10:07:37 am', '2026-02-07 10:07:37'),
(79, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 24839795 - Polar', '07/02/2026', '10:08:38 am', '2026-02-07 10:08:38'),
(80, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 24839795 - Polar', '07/02/2026', '10:09:51 am', '2026-02-07 10:09:51'),
(81, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - gabriel', '07/02/2026', '10:10:06 am', '2026-02-07 10:10:06'),
(82, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - MayChan', '07/02/2026', '10:10:39 am', '2026-02-07 10:10:39'),
(83, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30464415 - MayChan', '07/02/2026', '10:10:50 am', '2026-02-07 10:10:50'),
(84, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30464415 - MayChan', '07/02/2026', '10:11:05 am', '2026-02-07 10:11:05'),
(85, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 20464415 - gabriel', '07/02/2026', '10:11:34 am', '2026-02-07 10:11:34'),
(86, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '10:11:39 am', '2026-02-07 10:11:39'),
(87, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 14 - GJ', '07/02/2026', '10:13:34 am', '2026-02-07 10:13:34'),
(88, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '10:16:06 am', '2026-02-07 10:16:06'),
(89, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '10:17:41 am', '2026-02-07 10:17:41'),
(90, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30464415 - MayChan', '07/02/2026', '10:17:46 am', '2026-02-07 10:17:46'),
(91, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30464415 - MayChan', '07/02/2026', '10:18:30 am', '2026-02-07 10:18:30'),
(92, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31413414 - Polar', '07/02/2026', '10:18:53 am', '2026-02-07 10:18:53'),
(93, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 20333929 - gabriel', '07/02/2026', '10:19:10 am', '2026-02-07 10:19:10'),
(94, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30464415 - MayChan', '07/02/2026', '10:19:22 am', '2026-02-07 10:19:22'),
(95, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '10:23:12 am', '2026-02-07 10:23:12'),
(96, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30464415 - MayChan', '07/02/2026', '10:23:16 am', '2026-02-07 10:23:16'),
(97, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30464415 - gabriel', '07/02/2026', '10:23:24 am', '2026-02-07 10:23:24'),
(98, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 32464415 - gabriel', '07/02/2026', '10:23:43 am', '2026-02-07 10:23:43'),
(99, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '10:29:36 am', '2026-02-07 10:29:36'),
(100, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30464415 - MayChan', '07/02/2026', '10:29:40 am', '2026-02-07 10:29:40'),
(101, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '10:43:46 am', '2026-02-07 10:43:46'),
(102, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30464415 - MayChan', '07/02/2026', '10:43:51 am', '2026-02-07 10:43:51'),
(103, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30464415 - gabriel', '07/02/2026', '10:44:01 am', '2026-02-07 10:44:01'),
(104, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '11:01:12 am', '2026-02-07 11:01:12'),
(105, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '11:06:25 am', '2026-02-07 11:06:25'),
(106, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '11:36:18 am', '2026-02-07 11:36:18'),
(107, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '11:36:24 am', '2026-02-07 11:36:24'),
(108, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '11:38:00 am', '2026-02-07 11:38:00'),
(109, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '11:38:18 am', '2026-02-07 11:38:18'),
(110, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '11:38:22 am', '2026-02-07 11:38:22'),
(111, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '11:39:51 am', '2026-02-07 11:39:51'),
(112, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31464415 - RuiChan', '07/02/2026', '11:39:56 am', '2026-02-07 11:39:56'),
(113, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31413414 - Polar', '07/02/2026', '11:40:08 am', '2026-02-07 11:40:08'),
(114, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 31413414 - Polar', '07/02/2026', '11:40:17 am', '2026-02-07 11:40:17'),
(115, 1, 'Usuarios', 'registrar', 'Registró usuario: RuiChan', '07/02/2026', '11:57:39 am', '2026-02-07 11:57:39'),
(116, 1, 'Usuarios', 'registrar', 'Registró usuario: RuiChan', '07/02/2026', '12:03:15 pm', '2026-02-07 12:03:15'),
(117, 1, 'Usuarios', 'registrar', 'Registró usuario: RuiCHan', '07/02/2026', '12:16:56 pm', '2026-02-07 12:16:56'),
(118, 1, 'Usuarios', 'registrar', 'Registró usuario: RuiChan', '07/02/2026', '12:18:05 pm', '2026-02-07 12:18:05'),
(119, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30332879 - Carlos', '07/02/2026', '12:19:07 pm', '2026-02-07 12:19:07'),
(120, 1, 'Entradas', 'desactivar', 'Desactivó entrada ID: 30', '08/02/2026', '02:54:07 pm', '2026-02-08 14:54:07'),
(121, 1, 'Entrada', 'activar', 'Activó entrada ID: 30', '08/02/2026', '03:07:05 pm', '2026-02-08 15:07:05'),
(122, 1, 'Entradas', 'desactivar', 'Desactivó entrada ID: 30', '08/02/2026', '03:07:13 pm', '2026-02-08 15:07:13'),
(123, 1, 'Entrada', 'activar', 'Activó entrada ID: 30', '08/02/2026', '03:07:20 pm', '2026-02-08 15:07:20'),
(124, 1, 'Entradas', 'desactivar', 'Desactivó entrada ID: 30', '08/02/2026', '03:14:12 pm', '2026-02-08 15:14:12'),
(125, 1, 'Entradas', 'desactivar', 'Desactivó entrada ID: 29', '08/02/2026', '03:21:42 pm', '2026-02-08 15:21:42'),
(126, 1, 'Proveedores', 'desactivar', 'Desactivó proveedor ID: 12', '08/02/2026', '03:24:21 pm', '2026-02-08 15:24:21'),
(127, 1, 'Proveedores', 'activar', 'Activó proveedor ID: 12', '08/02/2026', '03:24:28 pm', '2026-02-08 15:24:28'),
(128, 1, 'Proveedores', 'activar', 'Activó proveedor ID: 9', '08/02/2026', '03:24:38 pm', '2026-02-08 15:24:38'),
(129, 1, 'Usuarios', 'desactivar', 'Desactivó usuario ID: 24', '08/02/2026', '03:25:47 pm', '2026-02-08 15:25:47'),
(130, 1, 'Usuarios', 'activar', 'Activó usuario ID: 24', '08/02/2026', '03:28:16 pm', '2026-02-08 15:28:16'),
(131, 1, 'Entradas', 'activar', 'Activó entrada ID: 30', '08/02/2026', '08:54:41 pm', '2026-02-08 20:54:41'),
(132, 1, 'Entradas', 'desactivar', 'Desactivó entrada ID: 30', '08/02/2026', '09:36:06 pm', '2026-02-08 21:36:06'),
(133, 1, 'Entradas', 'activar', 'Activó entrada ID: 30', '08/02/2026', '09:40:24 pm', '2026-02-08 21:40:24'),
(134, 1, 'Salidas', 'desactivar', 'Desactivó salida ID: 12', '08/02/2026', '10:31:47 pm', '2026-02-08 22:31:47'),
(135, 1, 'Salidas', 'activar', 'Activó salida ID: 12', '08/02/2026', '10:32:08 pm', '2026-02-08 22:32:08'),
(136, 2, 'Proveedores', 'modificar', 'Modificó proveedor ID: 10 - Luis', '09/02/2026', '08:51:51 am', '2026-02-09 08:51:51'),
(137, 2, 'Proveedores', 'modificar', 'Modificó proveedor ID: 9 - angel', '09/02/2026', '08:52:00 am', '2026-02-09 08:52:00'),
(138, 2, 'Productos', 'modificar', 'Modificó producto ID: 17 - relex', '09/02/2026', '08:56:04 am', '2026-02-09 08:56:04'),
(139, 1, 'Proveedores', 'activar', 'Activó proveedor ID: 1', '09/02/2026', '09:09:08 am', '2026-02-09 09:09:08'),
(140, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 1 - Carlos', '09/02/2026', '09:09:20 am', '2026-02-09 09:09:20'),
(141, 1, 'Proveedores', 'activar', 'Activó proveedor ID: 8', '09/02/2026', '09:09:51 am', '2026-02-09 09:09:51'),
(142, 1, 'Proveedores', 'activar', 'Activó proveedor ID: 13', '09/02/2026', '09:10:00 am', '2026-02-09 09:10:00'),
(143, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 14 - GJ', '09/02/2026', '09:10:13 am', '2026-02-09 09:10:13'),
(144, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 13 - aleida', '09/02/2026', '09:10:21 am', '2026-02-09 09:10:21'),
(145, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 12 - Luis', '09/02/2026', '09:10:30 am', '2026-02-09 09:10:30'),
(146, 1, 'Usuarios', 'modificar', 'Modificó usuario Ci: 30464415 - MayChan', '09/02/2026', '09:14:52 am', '2026-02-09 09:14:52'),
(147, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 10 - Luis', '09/02/2026', '09:15:45 am', '2026-02-09 09:15:45'),
(148, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 12 - Luis', '09/02/2026', '09:16:18 am', '2026-02-09 09:16:18'),
(149, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 12 - Luis', '09/02/2026', '09:16:26 am', '2026-02-09 09:16:26'),
(150, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 10 - Luis', '09/02/2026', '09:17:52 am', '2026-02-09 09:17:52'),
(151, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 10 - Luis', '09/02/2026', '09:17:58 am', '2026-02-09 09:17:58'),
(152, 1, 'Usuarios', 'registrar', 'Registró usuario: Necrozma', '09/02/2026', '09:21:18 am', '2026-02-09 09:21:18'),
(153, 1, 'Entradas', 'activar', 'Activó entrada ID: 29', '09/02/2026', '09:22:33 am', '2026-02-09 09:22:33'),
(154, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 14 - GJ', '09/02/2026', '09:29:54 am', '2026-02-09 09:29:54'),
(155, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 13 - aleida', '09/02/2026', '09:30:17 am', '2026-02-09 09:30:17'),
(156, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 12 - Luis', '09/02/2026', '09:30:40 am', '2026-02-09 09:30:40'),
(157, 1, 'Proveedores', 'modificar', 'Modificó proveedor ID: 12 - Luis', '09/02/2026', '09:30:52 am', '2026-02-09 09:30:52'),
(158, 1, 'Productos', 'registrar', 'Registró producto: Embrague (Código: 00-00-32)', '09/02/2026', '09:41:50 am', '2026-02-09 09:41:50'),
(159, 1, 'Entradas', 'desactivar', 'Desactivó entrada ID: 30', '10/02/2026', '08:27:40 am', '2026-02-10 08:27:40'),
(160, 1, 'Entradas', 'activar', 'Activó entrada ID: 30', '10/02/2026', '08:27:54 am', '2026-02-10 08:27:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `estado` varchar(15) NOT NULL,
  `creadoEl` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id`, `nombre`, `estado`, `creadoEl`) VALUES
(1, 'Sonax', 'inactivo', '2026-01-30 03:48:00'),
(2, 'Chevrolet', 'activo', '2026-01-30 03:48:00'),
(3, 'Chevrolet ', 'activo', '2026-01-30 03:48:00'),
(4, 'Ferrari', 'activo', '2026-01-30 03:48:00'),
(5, 'Bugatti', 'activo', '2026-01-30 03:48:00'),
(6, 'Nissan', 'activo', '2026-01-30 03:48:00'),
(7, 'Shelby', 'activo', '2026-01-30 03:48:00'),
(8, 'BMW', 'inactivo', '2026-01-30 03:48:00'),
(10, '2', 'activo', '2026-01-30 03:48:00'),
(11, 'Chevrolet1', 'activo', '2026-01-30 03:48:00'),
(12, 'HappyBugMarca', 'activo', '2026-01-30 03:48:00'),
(13, 'Marca HappyBug', 'activo', '2026-01-30 03:48:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id` int(20) NOT NULL,
  `ci` varchar(20) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `telef` varchar(20) DEFAULT NULL,
  `correo` varchar(20) DEFAULT NULL,
  `idusuario` int(11) NOT NULL,
  `creadoEl` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id`, `ci`, `nombre`, `apellido`, `telef`, `correo`, `idusuario`, `creadoEl`) VALUES
(1, '31464415', 'Luis', 'Sánchez', '', '', 1, '2026-01-30 03:48:01'),
(2, '30464415', 'May', 'Waguri', '', '', 2, '2026-01-30 03:48:01'),
(4, '30464415', 'Gabriel Jose', 'Salazar Guerra', '', '', 24, '2026-01-30 03:48:01'),
(5, '31413414', 'Soni', 'Rodríguez', '', '', 25, '2026-02-03 23:02:39'),
(6, '313329289', 'Sebastián', 'Russián', '', '', 28, '2026-02-09 09:21:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `codigo` varchar(250) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `precioVenta` float NOT NULL,
  `precioCosto` float NOT NULL DEFAULT 0,
  `cantidad` int(15) NOT NULL,
  `cantidadMinima` int(11) NOT NULL DEFAULT 1,
  `idcategoria` int(11) NOT NULL,
  `idmarca` int(11) NOT NULL,
  `estado` varchar(15) NOT NULL,
  `creadoEl` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `codigo`, `nombre`, `precioVenta`, `precioCosto`, `cantidad`, `cantidadMinima`, `idcategoria`, `idmarca`, `estado`, `creadoEl`) VALUES
(1, '990-00-123', 'Pur', 30, 20, 5, 1, 7, 3, 'activo', '2026-01-30 03:48:01'),
(9, '111-11-222', 'Bujya', 10, 6, 31, 1, 7, 2, 'activo', '2026-01-30 03:48:01'),
(10, '990-00-123	', 'Moto', 20, 0, 24, 1, 6, 6, 'activo', '2026-01-30 03:48:01'),
(11, '111-11-223', 'Sierra', 60, 30, 31, 1, 6, 4, 'activo', '2026-01-30 03:48:01'),
(12, '111-11-234', 'Tuercas de compresor ', 5, 0, 6, 1, 7, 2, 'inactivo', '2026-01-30 03:48:01'),
(13, '11111111', 'HappyBug', 21, 0, 4, 1, 14, 6, 'activo', '2026-01-30 03:48:01'),
(14, 'producto 1110', 'test', 10, 0, 0, 1, 2, 8, 'inactivo', '2026-01-29 03:48:01'),
(15, '01-02-004', 'Frenos', 20, 0, 0, 2, 9, 5, 'activo', '2026-01-30 11:19:10'),
(16, 'producto 1111', 'test', 20, 0, 0, 1, 7, 4, 'activo', '2026-01-30 11:58:42'),
(17, '0303055', 'relex', 10, 25, 20, 5, 8, 2, 'activo', '2026-02-04 07:55:37'),
(18, '00-00-32', 'Embrague', 12, 0, 0, 1, 7, 6, 'activo', '2026-02-09 09:41:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rif` varchar(40) NOT NULL,
  `direccion` text NOT NULL,
  `telefono` varchar(12) DEFAULT NULL,
  `persona_contacto` varchar(30) DEFAULT NULL,
  `estado` varchar(15) NOT NULL,
  `creadoEl` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id`, `nombre`, `rif`, `direccion`, `telefono`, `persona_contacto`, `estado`, `creadoEl`) VALUES
(1, 'Carlos', 'V-123456726', 'Sector el Viñedo Piso N-1 PB-1 Sector El Viñedo. Valencia Carabobo Zona postal 6158', '', '', 'activo', '2026-01-30 03:48:01'),
(2, 'Miguel', 'J-407540351', 'Industria Municipal Norte 91-100 Valencia Carabobo Zona Postal 2003', NULL, NULL, 'activo', '2026-01-30 03:48:01'),
(3, 'Juan', 'J-407540351', 'Calle Acosta Casa Nro 96 Sector Mercado', NULL, NULL, 'activo', '2026-01-30 03:48:01'),
(4, 'Donald', 'V-123456743', 'Industria Municipal Norte 91-100 Valencia Carabobo Zona Postal 2005', NULL, NULL, 'activo', '2026-01-30 03:48:01'),
(6, 'Miguel', 'V-123456789', 'Industria Municipal Norte 91-100 Valencia Carabobo Zona Postal 2003', NULL, NULL, 'activo', '2026-01-30 03:48:01'),
(8, 'Antonio', 'V-987654321', 'Calle principal sector el Muco', NULL, NULL, 'activo', '2026-01-30 03:48:01'),
(9, 'angel', 'V-123456782', 'calle independencia numero 4', '', '', 'activo', '2026-01-30 03:48:01'),
(10, 'Luis', 'V-123456785', 'Sector el Viñedo Piso N-1 PB-1 Sector El Viñedo. Valencia Carabobo Zona postal 6158', '', '', 'activo', '2026-01-30 03:48:01'),
(12, 'Luis', 'V-123456787', 'Sector el Viñedo Piso N-1 PB-1 Sector El Viñedo. Valencia Carabobo Zona postal 6158', '', '', 'activo', '2026-01-30 03:48:01'),
(13, 'aleida', 'J-131111111', 'guayacan ', '0416-0000000', '', 'activo', '2026-01-30 03:48:01'),
(14, 'GJ', 'J-248397952', 'El Pilar', '0412-0392977', 'Gabriel Salazar', 'activo', '2026-01-30 03:48:01');

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
  `cod_docum` varchar(30) NOT NULL,
  `tipo_despacho` varchar(20) NOT NULL DEFAULT 'venta',
  `creadoEl` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salida`
--

INSERT INTO `salida` (`id`, `fecha`, `hora`, `idusuario`, `total`, `cod_docum`, `tipo_despacho`, `creadoEl`, `estado`) VALUES
(3, '16/1/2026', '8:33:56 p. m.', 1, 255, 'Za-1232', 'venta', '2026-01-29 03:48:01', 'activo'),
(4, '16/1/2026', '8:36:46 p. m.', 1, 24.2, '2sa5lL*2', 'venta', '2026-01-30 03:48:01', 'activo'),
(5, '21/1/2026', '9:57:31 a. m.', 1, 340040, '0001', 'venta', '2026-01-30 03:48:01', 'activo'),
(6, '24/1/2026', '19:51:38', 1, 20, 'V20260124001', 'venta', '2026-01-30 03:48:01', 'activo'),
(7, '24/1/2026', '21:20:52', 1, 20, '20260124003', 'danado', '2026-01-30 03:48:01', 'activo'),
(8, '30/1/2026', '11:52:50 a. m.', 1, 80, '990-00-15', 'venta', '2026-01-30 11:52:51', 'activo'),
(9, '30/1/2026', '12:09:43 p. m.', 1, 40, '990-00-16', 'venta', '2026-01-30 12:09:43', 'activo'),
(10, '30/1/2026', '12:11:58 p. m.', 1, 420, '990-00-125', 'venta', '2026-01-30 12:11:58', 'activo'),
(11, '30/1/2026', '10:08:21 p. m.', 1, 520, '111-11-226', 'venta', '2026-01-30 22:08:21', 'activo'),
(12, '4/2/2026', '9:01:20 p. m.', 1, 400, 'za-22221', 'venta', '2026-02-04 21:01:20', 'activo'),
(13, '4/2/2026', '9:02:22 p. m.', 1, 270, 'za-22222', 'venta', '2026-02-04 21:02:22', 'activo'),
(14, '4/2/2026', '9:19:32 p. m.', 1, 240, 'za-2225', 'venta', '2026-02-04 21:19:32', 'activo');

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
(6, 20, 2, 9, 5, 40),
(7, 1, 20, 13, 6, 20),
(8, 1, 20, 13, 7, 20),
(9, 2, 40, 11, 8, 80),
(10, 1, 40, 11, 9, 40),
(11, 3, 40, 11, 10, 120),
(12, 10, 30, 11, 10, 300),
(13, 1, 40, 11, 11, 40),
(14, 16, 30, 11, 11, 480),
(15, 20, 20, 9, 12, 400),
(16, 3, 30, 9, 13, 90),
(17, 3, 60, 11, 13, 180),
(18, 3, 60, 11, 14, 180),
(19, 3, 20, 10, 14, 60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `rango` varchar(20) NOT NULL,
  `estado` varchar(15) NOT NULL,
  `creadoEl` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `usuario`, `clave`, `rango`, `estado`, `creadoEl`) VALUES
(1, 'RuiChan', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'administrador', 'activo', '2026-01-30 03:48:01'),
(2, 'MayChan', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'administrador', 'activo', '2026-01-30 03:48:01'),
(24, 'gabriel', 'fd355260e13e4e65ad0277580c5c93c3a7e0094d886eb18c0ff77c784fbd6846', 'administrador', 'activo', '2026-01-30 03:48:01'),
(25, 'Polar', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'empleado', 'activo', '2026-02-03 23:02:39'),
(28, 'Necrozma', 'e31597878c5cc1a0d9270fcf811e0c3bbc73fe855907b1e849762792f2b2df7b', 'empleado', 'activo', '2026-02-09 09:21:18');

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
  ADD KEY `id_proveedor` (`idproveedor`),
  ADD KEY `id_usuario_entrada` (`idusuario`);

--
-- Indices de la tabla `entradaproducto`
--
ALTER TABLE `entradaproducto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`idproducto`),
  ADD KEY `id_entrada` (`identrada`);

--
-- Indices de la tabla `historial_usuario`
--
ALTER TABLE `historial_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_historial_usuario` (`idusuario`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `entrada`
--
ALTER TABLE `entrada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `entradaproducto`
--
ALTER TABLE `entradaproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `historial_usuario`
--
ALTER TABLE `historial_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `salida`
--
ALTER TABLE `salida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `salidaproducto`
--
ALTER TABLE `salidaproducto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `entrada`
--
ALTER TABLE `entrada`
  ADD CONSTRAINT `entrada_ibfk_1` FOREIGN KEY (`idproveedor`) REFERENCES `proveedor` (`id`),
  ADD CONSTRAINT `entrada_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `entradaproducto`
--
ALTER TABLE `entradaproducto`
  ADD CONSTRAINT `entradaproducto_ibfk_1` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`id`),
  ADD CONSTRAINT `entradaproducto_ibfk_2` FOREIGN KEY (`identrada`) REFERENCES `entrada` (`id`);

--
-- Filtros para la tabla `historial_usuario`
--
ALTER TABLE `historial_usuario`
  ADD CONSTRAINT `FK_historial_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`id`);

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

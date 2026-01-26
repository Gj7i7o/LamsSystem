-- Tabla historial_usuario: Almacena el registro de acciones de los usuarios
CREATE TABLE `historial_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) NOT NULL,
  `modulo` varchar(50) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` varchar(20) NOT NULL,
  `hora` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_historial_usuario` (`idusuario`),
  CONSTRAINT `FK_historial_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

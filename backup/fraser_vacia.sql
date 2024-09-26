-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-09-2024 a las 18:14:57
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
-- Base de datos: `fraser_vacia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colores`
--

CREATE TABLE `colores` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID del color',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del color'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `colores`
--

INSERT INTO `colores` (`id`, `nombre`) VALUES
(1, 'BLANCO'),
(2, 'ROJO'),
(3, 'AZUL'),
(4, 'AMARILLO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL,
  `vehiculo_id` int(11) DEFAULT NULL,
  `tipo_id` int(11) UNSIGNED DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `observacion` varchar(255) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `recordatorio_enviado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID de la marca',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de la marca'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`) VALUES
(1, 'VOLVO'),
(2, 'RENAULT'),
(3, 'SALTO'),
(4, 'FIAT');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelos`
--

CREATE TABLE `modelos` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID de la modelo',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de la modelo',
  `marca_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL COMMENT 'Identificador único del permiso',
  `rol_id` int(11) NOT NULL COMMENT 'Identificador del rol asociado',
  `modulo` varchar(100) NOT NULL COMMENT 'Módulo al que se aplica el permiso',
  `permiso` int(1) NOT NULL COMMENT 'Permiso (1=permitido, 0=no permitido)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `rol_id`, `modulo`, `permiso`) VALUES
(1, 1, 'documentos', 1),
(2, 1, 'vehiculos', 1),
(3, 1, 'usuarios', 1),
(4, 2, 'documentos', 1),
(5, 2, 'vehiculos', 1),
(6, 2, 'usuarios', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL COMMENT 'Identificador único del rol',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del rol'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Operador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos`
--

CREATE TABLE `tipos` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'Identificador único del tipo',
  `nombre` varchar(20) NOT NULL COMMENT 'Nombre del tipo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos`
--

INSERT INTO `tipos` (`id`, `nombre`) VALUES
(1, 'RTO'),
(2, 'SEGURO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tramites_documentos`
--

CREATE TABLE `tramites_documentos` (
  `id` int(11) NOT NULL,
  `documento_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_tramite` datetime NOT NULL,
  `observacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL COMMENT 'Identificador único del usuario',
  `dni` int(8) NOT NULL COMMENT 'Documento Nacional de Identidad del usuario',
  `apellido` varchar(255) NOT NULL COMMENT 'Apellido del usuario',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del usuario',
  `usuario` varchar(100) NOT NULL COMMENT 'Nombre de usuario para inicio de sesión',
  `clave` varchar(100) NOT NULL COMMENT 'Clave de acceso del usuario',
  `rol_id` int(11) NOT NULL COMMENT 'Identificador del rol del usuario',
  `estado` tinyint(1) DEFAULT NULL COMMENT 'Estado del usuario (1=activo, 0=inactivo)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `dni`, `apellido`, `nombre`, `usuario`, `clave`, `rol_id`, `estado`) VALUES
(1, 26629619, 'TITO', 'ALEJANDRO', 'ATITO', '$2y$10$0pfBpIDcTUIWPDJI/EgucOEAwZXfR1ddZ3xPng1/6dHS1kltQc54a', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL,
  `patente` varchar(10) NOT NULL,
  `fecha_alta` date NOT NULL,
  `marca_id` int(11) UNSIGNED NOT NULL,
  `color_id` int(11) UNSIGNED NOT NULL,
  `motor` varchar(50) NOT NULL,
  `modelo_id` int(11) UNSIGNED NOT NULL,
  `anio` int(11) NOT NULL,
  `corroceria` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `colores`
--
ALTER TABLE `colores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `camion_id` (`vehiculo_id`),
  ADD KEY `fk_documento1` (`tipo_id`),
  ADD KEY `fk_documento3` (`usuario_id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modelos`
--
ALTER TABLE `modelos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_permiso1` (`rol_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos`
--
ALTER TABLE `tipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tramites_documentos`
--
ALTER TABLE `tramites_documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tramite_documento1` (`documento_id`),
  ADD KEY `fk_tramite_documento` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario1` (`rol_id`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vehiculo1` (`marca_id`),
  ADD KEY `fk_vehiculo2` (`modelo_id`),
  ADD KEY `fk_vehiculo3` (`color_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `colores`
--
ALTER TABLE `colores`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID del color', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de la marca', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `modelos`
--
ALTER TABLE `modelos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de la modelo', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del permiso', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del rol', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipos`
--
ALTER TABLE `tipos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del tipo', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tramites_documentos`
--
ALTER TABLE `tramites_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del usuario', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `fk_documento1` FOREIGN KEY (`tipo_id`) REFERENCES `tipos` (`id`),
  ADD CONSTRAINT `fk_documento2` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`),
  ADD CONSTRAINT `fk_documento3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `fk_permiso1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `tramites_documentos`
--
ALTER TABLE `tramites_documentos`
  ADD CONSTRAINT `fk_tramite_documento` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_tramite_documento1` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `fk_vehiculo1` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`),
  ADD CONSTRAINT `fk_vehiculo2` FOREIGN KEY (`modelo_id`) REFERENCES `modelos` (`id`),
  ADD CONSTRAINT `fk_vehiculo3` FOREIGN KEY (`color_id`) REFERENCES `colores` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

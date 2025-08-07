-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-02-2025 a las 16:34:45
-- Versión del servidor: 10.1.29-MariaDB
-- Versión de PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `osaerp_perutools`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cji_importaciondetalle`
--

CREATE TABLE `cji_importaciondetalle` (
  `IMPORDEP_Codigo` int(11) NOT NULL,
  `IMPOR_Codigo` int(11) NOT NULL,
  `PROD_Codigo` int(11) DEFAULT NULL,
  `IMPORDEC_GenInd` char(1) DEFAULT NULL,
  `UNDMED_Codigo` int(11) DEFAULT NULL,
  `IMPORDEC_Cantidad` double DEFAULT NULL,
  `IMPORDEC_Pu` double DEFAULT NULL,
  `IMPORDEC_Subtotal` double DEFAULT NULL,
  `IMPORDEC_Descuento` double DEFAULT NULL,
  `IMPORDEC_Igv` double DEFAULT NULL,
  `IMPORDEC_Total` double DEFAULT NULL,
  `IMPORDEC_Pu_ConIgv` double DEFAULT NULL,
  `IMPORDEC_Subtotal_ConIgv` double DEFAULT NULL,
  `IMPORDEC_Descuento_ConIgv` double DEFAULT NULL,
  `IMPORDEC_Igv100` int(11) DEFAULT NULL,
  `IMPORDEC_Descuento100` int(11) DEFAULT NULL,
  `IMPORDEC_Costo` double DEFAULT NULL,
  `IMPORDEC_Costo_uni_liquidado` double DEFAULT NULL,
  `IMPORDEC_Descripcion` varchar(250) DEFAULT NULL,
  `IMPORDEC_Observacion` varchar(250) DEFAULT NULL,
  `IMPORDEC_FechaRegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IMPORDEC_FechaModificacion` datetime DEFAULT NULL,
  `IMPORDEC_FlagEstado` char(1) DEFAULT '1',
  `ALMAP_Codigo` int(11) DEFAULT NULL,
  `GUIAREMP_Codigo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cji_importaciondetalle`
--
ALTER TABLE `cji_importaciondetalle`
  ADD PRIMARY KEY (`IMPORDEP_Codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cji_importaciondetalle`
--
ALTER TABLE `cji_importaciondetalle`
  MODIFY `IMPORDEP_Codigo` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

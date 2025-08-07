-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-02-2025 a las 16:35:28
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
-- Estructura de tabla para la tabla `cji_importacionservicios`
--

CREATE TABLE `cji_importacionservicios` (
  `IMPSER_Codigo` int(11) NOT NULL,
  `IMPOR_Codigo` int(11) NOT NULL,
  `PROD_Codigo` int(11) DEFAULT NULL,
  `IMPSER_Cantidad` double DEFAULT NULL,
  `IMPSER_Pu` double DEFAULT NULL,
  `IMPSER_PuIGV` double DEFAULT NULL,
  `IMPSER_Igv100` double DEFAULT NULL,
  `IMPSER_Subtotal` double DEFAULT NULL,
  `IMPSER_Igv` double DEFAULT NULL,
  `IMPSER_Total` double DEFAULT NULL,
  `IMPSER_Descripcion` varchar(250) DEFAULT NULL,
  `IMPSER_FlagEstado` char(1) DEFAULT '1',
  `IMPSER_FechaRegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IMPSER_FechaModificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cji_importacionservicios`
--
ALTER TABLE `cji_importacionservicios`
  ADD PRIMARY KEY (`IMPSER_Codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cji_importacionservicios`
--
ALTER TABLE `cji_importacionservicios`
  MODIFY `IMPSER_Codigo` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-02-2025 a las 16:32:42
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
-- Estructura de tabla para la tabla `cji_importacion`
--

CREATE TABLE `cji_importacion` (
  `IMPOR_Codigo` int(11) NOT NULL,
  `IMPOR_TipoOperacion` char(1) DEFAULT NULL,
  `IMPOR_TipoDocumento` char(1) DEFAULT NULL,
  `DUA_Codigo` varchar(20) NOT NULL,
  `PRESUP_Codigo` int(11) DEFAULT NULL,
  `OCOMP_Codigo` int(11) DEFAULT NULL,
  `COMPP_Codigo` int(11) DEFAULT NULL,
  `IMPOR_Serie` char(10) DEFAULT NULL,
  `IMPOR_Numero` int(11) DEFAULT NULL,
  `IMPOR_Nombre` varchar(250) NOT NULL,
  `CLIP_Codigo` int(11) DEFAULT NULL,
  `PROVP_Codigo` int(11) DEFAULT NULL,
  `IMPOR_NombreAuxiliar` varchar(25) DEFAULT NULL,
  `USUA_Codigo` int(11) DEFAULT NULL,
  `MONED_Codigo` int(11) DEFAULT NULL,
  `FORPAP_Codigo` int(11) DEFAULT NULL,
  `IMPOR_cif` double(10,2) DEFAULT NULL,
  `IMPOR_advalorem` double(10,2) DEFAULT NULL,
  `IMPOR_total` double(10,2) DEFAULT NULL,
  `IMPOR_subtotal_conigv` double(10,2) DEFAULT NULL,
  `IMPOR_descuento_conigv` double(10,2) DEFAULT NULL,
  `IMPOR_advalorem100` double(10,2) DEFAULT NULL,
  `IMPOR_igv100` int(11) DEFAULT NULL,
  `IMPOR_descuento100` int(11) DEFAULT NULL,
  `GUIAREMP_Codigo` int(11) DEFAULT NULL,
  `IMPOR_GuiaRemCodigo` varchar(11) DEFAULT NULL,
  `IMPOR_DocuRefeCodigo` varchar(11) DEFAULT NULL,
  `IMPOR_Observacion` text,
  `IMPOR_ModoImpresion` char(1) DEFAULT NULL,
  `IMPOR_Liquidada` bit(1) NOT NULL DEFAULT b'0',
  `IMPOR_Fecha` date DEFAULT NULL,
  `IMPOR_Vendedor` int(11) DEFAULT NULL,
  `IMPOR_TDC` double(10,3) DEFAULT NULL,
  `IMPOR_TDC_opcional` double(10,3) DEFAULT NULL,
  `IMPOR_FlagMueveStock` char(1) DEFAULT NULL,
  `GUIASAP_Codigo` int(11) DEFAULT NULL,
  `GUIAINP_Codigo` int(11) DEFAULT NULL,
  `USUA_anula` int(11) DEFAULT NULL,
  `IMPOR_FechaRegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IMPOR_FechaModificacion` datetime DEFAULT NULL,
  `IMPOR_FlagEstado` char(1) DEFAULT '1',
  `IMPOR_Hora` time DEFAULT NULL,
  `ALMAP_Codigo` int(11) DEFAULT NULL,
  `IMPOR_Codigo_Canje` int(11) DEFAULT NULL,
  `IMPOR_NumeroAutomatico` int(11) DEFAULT NULL,
  `PROYP_Codigo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cji_importacion`
--
ALTER TABLE `cji_importacion`
  ADD PRIMARY KEY (`IMPOR_Codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cji_importacion`
--
ALTER TABLE `cji_importacion`
  MODIFY `IMPOR_Codigo` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

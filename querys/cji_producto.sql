-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-02-2025 a las 16:50:36
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
-- Estructura de tabla para la tabla `cji_producto`
--

CREATE TABLE `cji_producto` (
  `PROD_Codigo` int(11) NOT NULL,
  `PROD_FlagBienServicio` char(1) NOT NULL DEFAULT 'B' COMMENT 'B: Bien, S: Servicio',
  `AFECT_Codigo` int(11) NOT NULL,
  `FAMI_Codigo` int(11) DEFAULT NULL,
  `TIPPROD_Codigo` int(11) DEFAULT NULL,
  `MARCP_Codigo` int(11) DEFAULT NULL,
  `LINP_Codigo` int(11) DEFAULT NULL,
  `FABRIP_Codigo` int(11) DEFAULT NULL,
  `PROD_PadreCodigo` int(11) DEFAULT NULL,
  `PROD_Nombre` varchar(300) DEFAULT NULL,
  `PROD_NombreCorto` varchar(300) DEFAULT NULL,
  `PROD_DescripcionBreve` varchar(200) DEFAULT NULL,
  `PROD_EspecificacionPDF` varchar(100) DEFAULT NULL,
  `PROD_Comentario` text,
  `PROD_Stock` double DEFAULT '0',
  `PROD_StockMinimo` double NOT NULL DEFAULT '0',
  `PROD_StockMaximo` double NOT NULL DEFAULT '0',
  `PROD_CodigoInterno` varchar(100) DEFAULT NULL,
  `PROD_CodigoUsuario` varchar(50) DEFAULT NULL,
  `PROD_Imagen` varchar(100) DEFAULT NULL,
  `PROD_CostoPromedio` double DEFAULT '0',
  `PROD_UltimoCosto` double DEFAULT '0',
  `PROD_Modelo` varchar(150) DEFAULT NULL,
  `PROD_Presentacion` varchar(150) DEFAULT NULL,
  `PROD_GenericoIndividual` char(1) DEFAULT NULL COMMENT 'G: producto de tipo genérico  (no va a tener número de serie), I: producto de tipo individual (va a tener número de serie)',
  `PROD_FechaUltimaCompra` datetime DEFAULT NULL,
  `PROD_FechaRegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `PROD_FechaModificacion` datetime DEFAULT NULL,
  `PROD_FlagActivo` char(1) DEFAULT '1',
  `PROD_FlagEstado` char(1) DEFAULT '1',
  `PROP_Codigo` int(11) DEFAULT NULL,
  `PROD_CodigoOriginal` varchar(50) DEFAULT NULL,
  `PROD_PartidaArancelaria` char(13) DEFAULT NULL,
  `PROD_Peso` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cji_producto`
--
ALTER TABLE `cji_producto`
  ADD PRIMARY KEY (`PROD_Codigo`),
  ADD KEY `FK_cji_producto_cji_familia` (`FAMI_Codigo`),
  ADD KEY `FK_cji_producto_cji_tipoproducto` (`TIPPROD_Codigo`),
  ADD KEY `MARCP_Codigo` (`MARCP_Codigo`),
  ADD KEY `LINP_Codigo` (`LINP_Codigo`),
  ADD KEY `FABRIP_Codigo` (`FABRIP_Codigo`),
  ADD KEY `PROD_PadreCodigo` (`PROD_PadreCodigo`),
  ADD KEY `PROD_PartidaArancelaria` (`PROD_PartidaArancelaria`);
ALTER TABLE `cji_producto` ADD FULLTEXT KEY `PROD_CodigoUsuario` (`PROD_Nombre`,`PROD_CodigoUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cji_producto`
--
ALTER TABLE `cji_producto`
  MODIFY `PROD_Codigo` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

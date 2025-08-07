-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-02-2025 a las 16:44:38
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
-- Estructura de tabla para la tabla `cji_comprobante`
--

CREATE TABLE `cji_comprobante` (
  `CPP_Codigo` int(11) NOT NULL,
  `CPC_TipoOperacion` char(1) NOT NULL DEFAULT 'V' COMMENT 'V: venta, C: compra',
  `CPC_TipoDocumento` char(1) NOT NULL DEFAULT 'F' COMMENT 'F: factura, B: boleta, N: nunguno de los dos',
  `PRESUP_Codigo` int(11) DEFAULT NULL,
  `OCOMP_Codigo` int(11) DEFAULT NULL,
  `CPP_Compracliente` varchar(20) DEFAULT NULL,
  `COMPP_Codigo` int(11) NOT NULL,
  `DUA_Codigo` varchar(20) DEFAULT NULL,
  `INV_FlagEstado` char(1) DEFAULT NULL,
  `PAIS_Codigo` int(11) DEFAULT NULL,
  `CPC_Serie` char(6) NOT NULL,
  `CPC_Numero` varchar(20) NOT NULL,
  `CLIP_Codigo` int(11) DEFAULT NULL,
  `PROVP_Codigo` int(11) DEFAULT NULL,
  `CPC_NombreAuxiliar` varchar(250) DEFAULT NULL,
  `USUA_Codigo` int(11) NOT NULL,
  `MONED_Codigo` int(11) NOT NULL DEFAULT '1',
  `FORPAP_Codigo` int(11) DEFAULT NULL,
  `FORPAP_Monto` float DEFAULT NULL,
  `CPC_subtotal` double(10,2) DEFAULT NULL,
  `CPC_descuento` double(10,2) DEFAULT NULL,
  `CPC_igv` double(10,2) DEFAULT NULL,
  `CPC_total` double(10,2) NOT NULL DEFAULT '0.00',
  `CPC_subtotal_conigv` double(10,2) DEFAULT NULL COMMENT 'Para que pueda ser usado como una boleta',
  `CPC_descuento_conigv` double(10,2) DEFAULT NULL COMMENT 'Para que pueda ser usado como una boleta',
  `CPC_igv100` int(11) NOT NULL DEFAULT '0',
  `CPC_descuento100` float NOT NULL DEFAULT '0',
  `GUIAREMP_Codigo` int(11) DEFAULT NULL,
  `CPC_GuiaRemCodigo` varchar(50) DEFAULT NULL,
  `CPC_DocuRefeCodigo` varchar(50) DEFAULT NULL,
  `CPC_Observacion` text,
  `CPC_ModoImpresion` char(1) NOT NULL DEFAULT '1',
  `CPC_Fecha` date NOT NULL,
  `CPC_FechaVencimiento` date DEFAULT NULL,
  `CPC_Vendedor` int(11) DEFAULT NULL,
  `CPC_TDC` double(10,3) DEFAULT NULL,
  `CPC_TDC_opcional` double(10,3) DEFAULT NULL,
  `CPC_FlagMueveStock` char(1) NOT NULL DEFAULT '0',
  `GUIASAP_Codigo` int(11) DEFAULT NULL,
  `GUIAINP_Codigo` int(11) DEFAULT NULL,
  `USUA_anula` int(11) DEFAULT NULL,
  `CPC_FechaRegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `CPC_FechaModificacion` datetime DEFAULT NULL,
  `CPC_FlagEstado` char(1) NOT NULL DEFAULT '1',
  `CPC_Hora` time NOT NULL,
  `ALMAP_Codigo` int(11) NOT NULL,
  `CPP_Codigo_Canje` int(11) DEFAULT '0',
  `CPC_NumeroAutomatico` int(1) DEFAULT NULL,
  `PROYP_Codigo` int(11) NOT NULL,
  `IMPOR_Nombre` int(11) NOT NULL,
  `CPC_FlagUsaAdelanto` bit(1) NOT NULL DEFAULT b'0',
  `CPC_Direccion` varchar(250) DEFAULT NULL,
  `CPC_Compra` int(10) DEFAULT NULL,
  `CPC_Retencion` varchar(40) DEFAULT NULL,
  `CPC_RetencionPorc` float DEFAULT '0',
  `CAJA_Codigo` int(11) NOT NULL,
  `CPC_Tipo_venta` int(2) NOT NULL DEFAULT '1',
  `CPC_Tipodetraccion` int(3) DEFAULT NULL,
  `CPC_Pordetraccion` float DEFAULT NULL,
  `CPC_Pagodetraccion` int(3) DEFAULT NULL,
  `CPC_Percepcion` int(2) DEFAULT NULL,
  `CAJCIERRE_Codigo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cji_comprobante`
--
ALTER TABLE `cji_comprobante`
  ADD PRIMARY KEY (`CPP_Codigo`),
  ADD KEY `FK_cji_factura_cji_presupuesto` (`PRESUP_Codigo`),
  ADD KEY `FK_cji_factura_cji_compania` (`COMPP_Codigo`),
  ADD KEY `FK_cji_factura_cji_cliente` (`CLIP_Codigo`),
  ADD KEY `FK_cji_factura_cji_usuario` (`USUA_Codigo`),
  ADD KEY `FK_cji_factura_cji_moneda` (`MONED_Codigo`),
  ADD KEY `FK_cji_factura_cji_formapago` (`FORPAP_Codigo`),
  ADD KEY `FK_cji_comprobante_proveedor` (`PROVP_Codigo`),
  ADD KEY `FK_cji_comprobante_ocompra` (`OCOMP_Codigo`),
  ADD KEY `GUIAREMP_Codigo` (`GUIAREMP_Codigo`),
  ADD KEY `CPC_Vendedor` (`CPC_Vendedor`),
  ADD KEY `GUIASAP_Codigo` (`GUIASAP_Codigo`),
  ADD KEY `GUIAINP_Codigo` (`GUIAINP_Codigo`),
  ADD KEY `caja` (`CAJA_Codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cji_comprobante`
--
ALTER TABLE `cji_comprobante`
  MODIFY `CPP_Codigo` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-02-2025 a las 16:41:40
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
-- Estructura de tabla para la tabla `cji_rutas`
--

CREATE TABLE `cji_rutas` (
  `COD_Ruta` int(11) NOT NULL,
  `Ruc_Empresa` varchar(20) NOT NULL,
  `Nombre_Ruta` varchar(150) NOT NULL,
  `Nombre_Empresa` varchar(250) NOT NULL,
  `Nombre_Conductor` varchar(250) NOT NULL,
  `Apellido_Conductor` varchar(250) NOT NULL,
  `Dni_Conductor` varchar(15) NOT NULL,
  `Licencia` varchar(50) NOT NULL,
  `Placa` varchar(20) NOT NULL,
  `Marca` varchar(100) NOT NULL,
  `Certificado` varchar(200) NOT NULL,
  `MTC` varchar(150) NOT NULL,
  `Estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cji_rutas`
--
ALTER TABLE `cji_rutas`
  ADD PRIMARY KEY (`COD_Ruta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cji_rutas`
--
ALTER TABLE `cji_rutas`
  MODIFY `COD_Ruta` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

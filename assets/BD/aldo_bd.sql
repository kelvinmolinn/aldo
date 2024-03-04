-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 04-03-2024 a las 06:09:06
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `aldo_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_actividad_economica`
--

CREATE TABLE `cat_actividad_economica` (
  `actividadEconomicaId` int NOT NULL,
  `actividadEconomica` varchar(150) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_documentos_identificacion`
--

CREATE TABLE `cat_documentos_identificacion` (
  `documentoIdentificacionId` int NOT NULL,
  `documentoIdentificacion` varchar(45) DEFAULT NULL,
  `formatoDocumentoIdentificacion` varchar(150) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_formas_pago`
--

CREATE TABLE `cat_formas_pago` (
  `formaPagoId` int NOT NULL,
  `formaPago` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_paises`
--

CREATE TABLE `cat_paises` (
  `paisId` int NOT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `abreviaturaPais` varchar(45) DEFAULT NULL,
  `codigoTelefonoPais` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_paises_ciudades`
--

CREATE TABLE `cat_paises_ciudades` (
  `paisCiudadId` int NOT NULL,
  `paisId` int DEFAULT NULL,
  `paisCiudad` varchar(50) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_paises_estados`
--

CREATE TABLE `cat_paises_estados` (
  `paisEstadoId` int NOT NULL,
  `paisCiudadId` int DEFAULT NULL,
  `paisEstado` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_tipo_contacto`
--

CREATE TABLE `cat_tipo_contacto` (
  `tipoContactoId` int NOT NULL,
  `tipoContacto` varchar(50) DEFAULT NULL,
  `formatoContacto` varchar(45) DEFAULT NULL,
  `cat_tipo_contactocol` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_tipo_contribuyente`
--

CREATE TABLE `cat_tipo_contribuyente` (
  `tipoContribuyenteId` int NOT NULL,
  `tipoContribuyente` varchar(150) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_tipo_persona`
--

CREATE TABLE `cat_tipo_persona` (
  `tipoPersonaId` int NOT NULL,
  `tipoPersona` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_unidades_medida`
--

CREATE TABLE `cat_unidades_medida` (
  `unidadMedidaId` int NOT NULL,
  `unidadMedida` varchar(45) DEFAULT NULL,
  `abreviaturaUnidadMedida` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cof_empleados`
--

CREATE TABLE `cof_empleados` (
  `empleadoId` int NOT NULL,
  `sucursalId` int DEFAULT NULL,
  `dui` varchar(45) DEFAULT NULL,
  `primerNombre` varchar(45) DEFAULT NULL,
  `segundoNombre` varchar(45) DEFAULT NULL,
  `primerApellido` varchar(45) DEFAULT NULL,
  `segundoApellido` varchar(45) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `sexoEmpleado` varchar(45) DEFAULT NULL,
  `estadoEmpleado` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cof_parametrizaciones`
--

CREATE TABLE `cof_parametrizaciones` (
  `parametrizacionId` int NOT NULL,
  `tipoParametrizacion` varchar(45) DEFAULT NULL,
  `valorParametrizacion` int DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cof_sucursales_contacto`
--

CREATE TABLE `cof_sucursales_contacto` (
  `sucursalContactoId` int NOT NULL,
  `sucursalId` int DEFAULT NULL,
  `tipoContactoId` int DEFAULT NULL,
  `contactoSucursal` varchar(50) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comp_compras`
--

CREATE TABLE `comp_compras` (
  `compraId` int NOT NULL,
  `proveedorId` int DEFAULT NULL,
  `tipoDocumento` varchar(50) DEFAULT NULL,
  `fechaDocumento` date DEFAULT NULL,
  `numDocumento` int DEFAULT NULL,
  `paisId` int DEFAULT NULL,
  `ObsCompra` varchar(250) DEFAULT NULL,
  `porcentajeIva` int DEFAULT NULL,
  `condicionPago` varchar(45) DEFAULT NULL,
  `flgRetaceo` int DEFAULT NULL,
  `estadoCompra` varchar(45) DEFAULT NULL,
  `fechaAnulacion` date DEFAULT NULL,
  `obsAnulacion` varchar(250) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comp_compras_detalle`
--

CREATE TABLE `comp_compras_detalle` (
  `compraDetalleId` int NOT NULL,
  `compraId` int DEFAULT NULL,
  `productoId` int DEFAULT NULL,
  `cantidadProducto` int DEFAULT NULL,
  `precioUnitario` int DEFAULT NULL,
  `precioUnitarioIVA` int DEFAULT NULL,
  `ivaUnitario` int DEFAULT NULL,
  `ivaTotal` int DEFAULT NULL,
  `totalCompraDetalle` int DEFAULT NULL,
  `totalCompraDetalleIVA` int DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comp_proveedores`
--

CREATE TABLE `comp_proveedores` (
  `proveedorId` int NOT NULL,
  `tipoProveedorOrigen` varchar(50) DEFAULT NULL,
  `tipoPersonaId` int DEFAULT NULL,
  `documentoIdentificacionId` int DEFAULT NULL,
  `comp_proveedorescol` varchar(45) DEFAULT NULL,
  `ncrProveedor` varchar(150) DEFAULT NULL,
  `numDocumentoIdentificacion` varchar(45) DEFAULT NULL,
  `proveedor` varchar(150) DEFAULT NULL,
  `proveedorComercial` varchar(150) DEFAULT NULL,
  `actividadEconomicaId` int DEFAULT NULL,
  `tipoContribuyenteId` int DEFAULT NULL,
  `direccionProveedor` varchar(250) DEFAULT NULL,
  `estadoProveedor` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comp_proveedores_contacto`
--

CREATE TABLE `comp_proveedores_contacto` (
  `proveedorContactoId` int NOT NULL,
  `proveedorId` int DEFAULT NULL,
  `tipoContactoId` int DEFAULT NULL,
  `contactoProveedor` varchar(150) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comp_retaceo`
--

CREATE TABLE `comp_retaceo` (
  `retaceoId` int NOT NULL,
  `numRetaceo` int DEFAULT NULL,
  `fechaRetaceo` date DEFAULT NULL,
  `totalFlete` int DEFAULT NULL,
  `obsRetaceo` varchar(250) DEFAULT NULL,
  `estadoRetaceo` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comp_retaceo_costos`
--

CREATE TABLE `comp_retaceo_costos` (
  `retaceoCostoId` int NOT NULL,
  `retaceoId` int DEFAULT NULL,
  `tipoCosto` varchar(45) DEFAULT NULL,
  `conceptoCosto` varchar(150) DEFAULT NULL,
  `montoCosto` int DEFAULT NULL,
  `obsCosto` varchar(250) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comp_retaceo_detalle`
--

CREATE TABLE `comp_retaceo_detalle` (
  `retaceoDetalleId` int NOT NULL,
  `retaceoId` int DEFAULT NULL,
  `compraDetalleId` int DEFAULT NULL,
  `cantidadProducto` int DEFAULT NULL,
  `precioFOBUnitario` int DEFAULT NULL,
  `importe` int DEFAULT NULL,
  `gasto` int DEFAULT NULL,
  `DAI` int DEFAULT NULL,
  `costoTotal` int DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_menus`
--

CREATE TABLE `conf_menus` (
  `menuId` int NOT NULL,
  `moduloId` int DEFAULT NULL,
  `menu` varchar(45) DEFAULT NULL,
  `iconoMenu` varchar(45) DEFAULT NULL,
  `urlMenu` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_menu_permisos`
--

CREATE TABLE `conf_menu_permisos` (
  `menuPermisoId` int NOT NULL,
  `menuId` int DEFAULT NULL,
  `menuPermiso` varchar(150) DEFAULT NULL,
  `descripcionMenuPermiso` varchar(250) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_modulos`
--

CREATE TABLE `conf_modulos` (
  `moduloId` int NOT NULL,
  `modulo` varchar(50) DEFAULT NULL,
  `iconoModulo` varchar(50) DEFAULT NULL,
  `urlModulo` varchar(50) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_roles`
--

CREATE TABLE `conf_roles` (
  `rolId` int NOT NULL,
  `rol` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_roles_permisos`
--

CREATE TABLE `conf_roles_permisos` (
  `rolMenuId` int NOT NULL,
  `rolId` int DEFAULT NULL,
  `menuPermisoId` int DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_sucursales`
--

CREATE TABLE `conf_sucursales` (
  `sucursalId` int NOT NULL,
  `sucursal` varchar(50) DEFAULT NULL,
  `direccionSucursal` varchar(50) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_sucursales_usuarios`
--

CREATE TABLE `conf_sucursales_usuarios` (
  `sucursalUsuarioId` int NOT NULL,
  `sucursalId` int DEFAULT NULL,
  `usuarioId` int DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conf_usuarios`
--

CREATE TABLE `conf_usuarios` (
  `usuarioId` int NOT NULL,
  `empleadoId` int DEFAULT NULL,
  `rolId` int DEFAULT NULL,
  `correo` varchar(60) DEFAULT NULL,
  `clave` varchar(150) DEFAULT NULL,
  `flgEnLinea` int DEFAULT NULL,
  `numIngresos` int DEFAULT NULL,
  `fhUltimoIngreso` datetime DEFAULT NULL,
  `intentosIngreso` int DEFAULT NULL,
  `estadoUsuario` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `conf_usuarios`
--

INSERT INTO `conf_usuarios` (`usuarioId`, `empleadoId`, `rolId`, `correo`, `clave`, `flgEnLinea`, `numIngresos`, `fhUltimoIngreso`, `intentosIngreso`, `estadoUsuario`, `fhEdita`, `fhAgrega`, `fhElimina`, `flgElimina`, `usuarioIdEdita`, `usuarioIdAgrega`, `usuarioIdElimina`) VALUES
(1, NULL, NULL, 'kelvinmolinn@gmail.com', '123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fact_clientes`
--

CREATE TABLE `fact_clientes` (
  `clienteId` int NOT NULL,
  `tipoPersonaId` int DEFAULT NULL,
  `nrcCliente` int DEFAULT NULL,
  `documentoIdentificacionId` int DEFAULT NULL,
  `numDocumentoIdentificacion` int DEFAULT NULL,
  `cliente` varchar(150) DEFAULT NULL,
  `clienteComercial` varchar(150) DEFAULT NULL,
  `actividadEconomicaId` int DEFAULT NULL,
  `tipoContribuyenteId` int DEFAULT NULL,
  `direccionCliente` varchar(250) DEFAULT NULL,
  `porcentajeDescuentoMaximo` int DEFAULT NULL,
  `estadoCliente` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fact_cliente_contacto`
--

CREATE TABLE `fact_cliente_contacto` (
  `clienteContactoId` int NOT NULL,
  `clienteId` int DEFAULT NULL,
  `tipoContactoId` int DEFAULT NULL,
  `contactoCliente` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fact_correlativos`
--

CREATE TABLE `fact_correlativos` (
  `correlativoId` int NOT NULL,
  `sucursalId` int DEFAULT NULL,
  `tipoFactura` varchar(45) DEFAULT NULL,
  `numSerie` int DEFAULT NULL,
  `fechaResolucion` date DEFAULT NULL,
  `correlativoDesde` int DEFAULT NULL,
  `correlativoHasta` int DEFAULT NULL,
  `fechaUltimoCorrelativo` date DEFAULT NULL,
  `estadoCorrelativo` varchar(150) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fact_facturas`
--

CREATE TABLE `fact_facturas` (
  `facturaId` int NOT NULL,
  `sucursalId` int DEFAULT NULL,
  `correlativoId` int DEFAULT NULL,
  `correlativoFactura` int DEFAULT NULL,
  `clienteId` int DEFAULT NULL,
  `condicionFactura` varchar(250) DEFAULT NULL,
  `porcentajeIVA` int DEFAULT NULL,
  `comentariosFactura` varchar(250) DEFAULT NULL,
  `fechaAnulacionFactura` date DEFAULT NULL,
  `obsAnulacionFactura` varchar(250) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fact_facturas_detalle`
--

CREATE TABLE `fact_facturas_detalle` (
  `facturaDetalleId` int NOT NULL,
  `facturaId` int DEFAULT NULL,
  `productoId` int DEFAULT NULL,
  `conceptoProductoFactura` varchar(250) DEFAULT NULL,
  `cantidadProducto` int DEFAULT NULL,
  `precioUnitario` int DEFAULT NULL,
  `precioUnitarioIVA` int DEFAULT NULL,
  `porcentajeDescuento` int DEFAULT NULL,
  `precioUnitarioFacturado` int DEFAULT NULL,
  `ivaUnitario` int DEFAULT NULL,
  `ivaTotal` int DEFAULT NULL,
  `totalFacturaDetalle` int DEFAULT NULL,
  `totalFacturaDetalleIVA` int DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fact_facturas_pago`
--

CREATE TABLE `fact_facturas_pago` (
  `facturaPagoId` int NOT NULL,
  `facturaId` int DEFAULT NULL,
  `formaPagoId` int DEFAULT NULL,
  `fechaFacturaPago` date DEFAULT NULL,
  `numComprobantePago` int DEFAULT NULL,
  `montoPago` int DEFAULT NULL,
  `comentarioPago` varchar(250) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fact_reservas`
--

CREATE TABLE `fact_reservas` (
  `reservaId` int NOT NULL,
  `sucursalId` int DEFAULT NULL,
  `clienteId` int DEFAULT NULL,
  `facturaId` int DEFAULT NULL,
  `fechaReserva` date DEFAULT NULL,
  `comentarioReserva` varchar(250) DEFAULT NULL,
  `porcentajeIva` int DEFAULT NULL,
  `estadoReserva` varchar(45) DEFAULT NULL,
  `fechaAnulacionReserva` date DEFAULT NULL,
  `obsAnulacionReserva` varchar(250) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fact_reservas_detalle`
--

CREATE TABLE `fact_reservas_detalle` (
  `reservaDetalleId` int NOT NULL,
  `reservaId` int DEFAULT NULL,
  `productoId` int DEFAULT NULL,
  `cantidadProducto` int DEFAULT NULL,
  `precioUnitario` int DEFAULT NULL,
  `precioUnitarioIVA` int DEFAULT NULL,
  `porcentajeDescuento` int DEFAULT NULL,
  `precioUnitarioVenta` int DEFAULT NULL,
  `precioUnitarioVentaIVA` int DEFAULT NULL,
  `ivaUnitario` int DEFAULT NULL,
  `ivaTotal` int DEFAULT NULL,
  `totalReservaDetalle` int DEFAULT NULL,
  `totalReservaDetalleIVA` int DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fact_reservas_pago`
--

CREATE TABLE `fact_reservas_pago` (
  `reservaPagoId` int NOT NULL,
  `reservaId` int DEFAULT NULL,
  `fechaReservaPago` date DEFAULT NULL,
  `formaPagoId` int DEFAULT NULL,
  `numComprobantePago` int DEFAULT NULL,
  `montoPago` int DEFAULT NULL,
  `comentarioPago` varchar(250) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inv_kardex`
--

CREATE TABLE `inv_kardex` (
  `kardexId` int NOT NULL,
  `tipoMovimiento` int DEFAULT NULL,
  `descripcionMovimiento` varchar(150) DEFAULT NULL,
  `productoExistenciaId` int DEFAULT NULL,
  `existenciaAntesMovimiento` int DEFAULT NULL,
  `cantidadMovimiento` int DEFAULT NULL,
  `existenciaDespuesMovimiento` int DEFAULT NULL,
  `costoUnitarioFOB` int DEFAULT NULL,
  `costoUnitarioRetaceo` int DEFAULT NULL,
  `costoPromedio` int DEFAULT NULL,
  `precioVentaUnitario` int DEFAULT NULL,
  `fechaDocumento` date DEFAULT NULL,
  `fechaMovimiento` date DEFAULT NULL,
  `tablaMovimiento` varchar(45) DEFAULT NULL,
  `tablaMovimientoId` int DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inv_productos`
--

CREATE TABLE `inv_productos` (
  `productoId` int NOT NULL,
  `productoTipoId` int DEFAULT NULL,
  `productoPlataformaId` int DEFAULT NULL,
  `unidadMedidaId` int DEFAULT NULL,
  `codigoProducto` varchar(45) DEFAULT NULL,
  `producto` varchar(45) DEFAULT NULL,
  `descripcionProducto` varchar(150) DEFAULT NULL,
  `existenciaMinima` int DEFAULT NULL,
  `fechaInicioInventario` date DEFAULT NULL,
  `costoUnitarioFOB` int DEFAULT NULL,
  `CostoUnitarioRetaceo` int DEFAULT NULL,
  `CostoPromedio` int DEFAULT NULL,
  `flgProductoVenta` int DEFAULT NULL,
  `precioVenta` int DEFAULT NULL,
  `estadoProducto` varchar(45) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inv_productos_existencias`
--

CREATE TABLE `inv_productos_existencias` (
  `productoExistenciaId` int NOT NULL,
  `productoId` int DEFAULT NULL,
  `sucursalId` int DEFAULT NULL,
  `existenciaProducto` int DEFAULT NULL,
  `existenciaReservada` int DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inv_productos_plataforma`
--

CREATE TABLE `inv_productos_plataforma` (
  `productoPlataformaId` int NOT NULL,
  `productoPlataforma` varchar(50) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inv_productos_tipo`
--

CREATE TABLE `inv_productos_tipo` (
  `productoTipoId` int NOT NULL,
  `productoTipo` varchar(50) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_productos_precios`
--

CREATE TABLE `log_productos_precios` (
  `logProductoPrecioId` int NOT NULL,
  `productoId` int DEFAULT NULL,
  `costoUnitarioFOB` int DEFAULT NULL,
  `CostoUnitarioRetaceo` int DEFAULT NULL,
  `costoPromedio` int DEFAULT NULL,
  `precioVentaAntes` int DEFAULT NULL,
  `precioVentaNuevo` int DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_usuarios`
--

CREATE TABLE `log_usuarios` (
  `logUsuarioId` int NOT NULL,
  `usuarioId` int DEFAULT NULL,
  `fhIngreso` datetime DEFAULT NULL,
  `fhSalida` datetime DEFAULT NULL,
  `logInterfaces` varchar(60) DEFAULT NULL,
  `logReportes` varchar(60) DEFAULT NULL,
  `logAgrega` varchar(60) DEFAULT NULL,
  `logEdita` varchar(45) DEFAULT NULL,
  `logElimina` varchar(45) DEFAULT NULL,
  `ipIngreso` varchar(45) DEFAULT NULL,
  `infoNavegador` varchar(50) DEFAULT NULL,
  `fhEdita` datetime DEFAULT NULL,
  `fhAgrega` datetime DEFAULT NULL,
  `fhElimina` datetime DEFAULT NULL,
  `flgElimina` int DEFAULT NULL,
  `usuarioIdEdita` int DEFAULT NULL,
  `usuarioIdAgrega` int DEFAULT NULL,
  `usuarioIdElimina` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cat_actividad_economica`
--
ALTER TABLE `cat_actividad_economica`
  ADD PRIMARY KEY (`actividadEconomicaId`);

--
-- Indices de la tabla `cat_documentos_identificacion`
--
ALTER TABLE `cat_documentos_identificacion`
  ADD PRIMARY KEY (`documentoIdentificacionId`);

--
-- Indices de la tabla `cat_formas_pago`
--
ALTER TABLE `cat_formas_pago`
  ADD PRIMARY KEY (`formaPagoId`);

--
-- Indices de la tabla `cat_paises`
--
ALTER TABLE `cat_paises`
  ADD PRIMARY KEY (`paisId`);

--
-- Indices de la tabla `cat_paises_ciudades`
--
ALTER TABLE `cat_paises_ciudades`
  ADD PRIMARY KEY (`paisCiudadId`),
  ADD KEY `fk_cat_paises_ciudades_cat_paises1_idx` (`paisId`);

--
-- Indices de la tabla `cat_paises_estados`
--
ALTER TABLE `cat_paises_estados`
  ADD PRIMARY KEY (`paisEstadoId`),
  ADD KEY `fk_cat_paises_estados_cat_paises_ciudades1_idx` (`paisCiudadId`);

--
-- Indices de la tabla `cat_tipo_contacto`
--
ALTER TABLE `cat_tipo_contacto`
  ADD PRIMARY KEY (`tipoContactoId`);

--
-- Indices de la tabla `cat_tipo_contribuyente`
--
ALTER TABLE `cat_tipo_contribuyente`
  ADD PRIMARY KEY (`tipoContribuyenteId`);

--
-- Indices de la tabla `cat_tipo_persona`
--
ALTER TABLE `cat_tipo_persona`
  ADD PRIMARY KEY (`tipoPersonaId`);

--
-- Indices de la tabla `cat_unidades_medida`
--
ALTER TABLE `cat_unidades_medida`
  ADD PRIMARY KEY (`unidadMedidaId`);

--
-- Indices de la tabla `cof_empleados`
--
ALTER TABLE `cof_empleados`
  ADD PRIMARY KEY (`empleadoId`),
  ADD KEY `fk_cof_empleados_conf_sucursales_idx` (`sucursalId`);

--
-- Indices de la tabla `cof_parametrizaciones`
--
ALTER TABLE `cof_parametrizaciones`
  ADD PRIMARY KEY (`parametrizacionId`);

--
-- Indices de la tabla `cof_sucursales_contacto`
--
ALTER TABLE `cof_sucursales_contacto`
  ADD PRIMARY KEY (`sucursalContactoId`),
  ADD KEY `fk_cof_sucursales_contacto_conf_sucursales1_idx` (`sucursalId`),
  ADD KEY `fk_cof_sucursales_contacto_cat_tipo_contacto1_idx` (`tipoContactoId`);

--
-- Indices de la tabla `comp_compras`
--
ALTER TABLE `comp_compras`
  ADD PRIMARY KEY (`compraId`),
  ADD KEY `fk_comp_compras_comp_proveedores1_idx` (`proveedorId`),
  ADD KEY `fk_comp_compras_cat_paises1_idx` (`paisId`);

--
-- Indices de la tabla `comp_compras_detalle`
--
ALTER TABLE `comp_compras_detalle`
  ADD PRIMARY KEY (`compraDetalleId`),
  ADD KEY `fk_comp_compras_detalle_comp_compras1_idx` (`compraId`),
  ADD KEY `fk_comp_compras_detalle_inv_productos1_idx` (`productoId`);

--
-- Indices de la tabla `comp_proveedores`
--
ALTER TABLE `comp_proveedores`
  ADD PRIMARY KEY (`proveedorId`),
  ADD KEY `fk_comp_proveedores_cat_tipo_persona1_idx` (`tipoPersonaId`),
  ADD KEY `fk_comp_proveedores_cat_actividad_economica1_idx` (`actividadEconomicaId`),
  ADD KEY `fk_comp_proveedores_cat_tipo_contribuyente1_idx` (`tipoContribuyenteId`);

--
-- Indices de la tabla `comp_proveedores_contacto`
--
ALTER TABLE `comp_proveedores_contacto`
  ADD PRIMARY KEY (`proveedorContactoId`),
  ADD KEY `fk_comp_proveedores_contacto_comp_proveedores1_idx` (`proveedorId`),
  ADD KEY `fk_comp_proveedores_contacto_cat_tipo_contacto1_idx` (`tipoContactoId`);

--
-- Indices de la tabla `comp_retaceo`
--
ALTER TABLE `comp_retaceo`
  ADD PRIMARY KEY (`retaceoId`);

--
-- Indices de la tabla `comp_retaceo_costos`
--
ALTER TABLE `comp_retaceo_costos`
  ADD PRIMARY KEY (`retaceoCostoId`),
  ADD KEY `fk_comp_retaceo_costos_comp_retaceo1_idx` (`retaceoId`);

--
-- Indices de la tabla `comp_retaceo_detalle`
--
ALTER TABLE `comp_retaceo_detalle`
  ADD PRIMARY KEY (`retaceoDetalleId`),
  ADD KEY `fk_comp_retaceo_detalle_comp_retaceo1_idx` (`retaceoId`),
  ADD KEY `fk_comp_retaceo_detalle_comp_compras_detalle1_idx` (`compraDetalleId`);

--
-- Indices de la tabla `conf_menus`
--
ALTER TABLE `conf_menus`
  ADD PRIMARY KEY (`menuId`),
  ADD KEY `fk_conf_menus_conf_modulos1_idx` (`moduloId`);

--
-- Indices de la tabla `conf_menu_permisos`
--
ALTER TABLE `conf_menu_permisos`
  ADD PRIMARY KEY (`menuPermisoId`),
  ADD KEY `fk_conf_menu_permisos_conf_menus1_idx` (`menuId`);

--
-- Indices de la tabla `conf_modulos`
--
ALTER TABLE `conf_modulos`
  ADD PRIMARY KEY (`moduloId`);

--
-- Indices de la tabla `conf_roles`
--
ALTER TABLE `conf_roles`
  ADD PRIMARY KEY (`rolId`);

--
-- Indices de la tabla `conf_roles_permisos`
--
ALTER TABLE `conf_roles_permisos`
  ADD PRIMARY KEY (`rolMenuId`),
  ADD KEY `fk_conf_roles_permisos_conf_roles1_idx` (`rolId`),
  ADD KEY `fk_conf_roles_permisos_conf_menu_permisos1_idx` (`menuPermisoId`);

--
-- Indices de la tabla `conf_sucursales`
--
ALTER TABLE `conf_sucursales`
  ADD PRIMARY KEY (`sucursalId`);

--
-- Indices de la tabla `conf_sucursales_usuarios`
--
ALTER TABLE `conf_sucursales_usuarios`
  ADD PRIMARY KEY (`sucursalUsuarioId`),
  ADD KEY `fk_conf_sucursales_usuarios_conf_sucursales1_idx` (`sucursalId`);

--
-- Indices de la tabla `conf_usuarios`
--
ALTER TABLE `conf_usuarios`
  ADD PRIMARY KEY (`usuarioId`),
  ADD KEY `fk_conf_usuarios_cof_empleados1_idx` (`empleadoId`),
  ADD KEY `fk_conf_usuarios_conf_roles1_idx` (`rolId`);

--
-- Indices de la tabla `fact_clientes`
--
ALTER TABLE `fact_clientes`
  ADD PRIMARY KEY (`clienteId`),
  ADD KEY `fk_fact_clientes_cat_actividad_economica1_idx` (`actividadEconomicaId`),
  ADD KEY `fk_fact_clientes_cat_tipo_persona1_idx` (`tipoPersonaId`),
  ADD KEY `fk_fact_clientes_cat_tipo_contribuyente1_idx` (`tipoContribuyenteId`);

--
-- Indices de la tabla `fact_cliente_contacto`
--
ALTER TABLE `fact_cliente_contacto`
  ADD PRIMARY KEY (`clienteContactoId`),
  ADD KEY `fk_fact_cliente_contacto_fact_clientes1_idx` (`clienteId`),
  ADD KEY `fk_fact_cliente_contacto_cat_tipo_contacto1_idx` (`tipoContactoId`);

--
-- Indices de la tabla `fact_correlativos`
--
ALTER TABLE `fact_correlativos`
  ADD PRIMARY KEY (`correlativoId`),
  ADD KEY `fk_fact_correlativos_conf_sucursales1_idx` (`sucursalId`);

--
-- Indices de la tabla `fact_facturas`
--
ALTER TABLE `fact_facturas`
  ADD PRIMARY KEY (`facturaId`),
  ADD KEY `fk_fact_facturas_fact_correlativos1_idx` (`correlativoId`),
  ADD KEY `fk_fact_facturas_fact_clientes1_idx` (`clienteId`),
  ADD KEY `fk_fact_facturas_conf_sucursales1_idx` (`sucursalId`);

--
-- Indices de la tabla `fact_facturas_detalle`
--
ALTER TABLE `fact_facturas_detalle`
  ADD PRIMARY KEY (`facturaDetalleId`),
  ADD KEY `fk_fact_facturas_detalle_fact_facturas1_idx` (`facturaId`),
  ADD KEY `fk_fact_facturas_detalle_inv_productos1_idx` (`productoId`);

--
-- Indices de la tabla `fact_facturas_pago`
--
ALTER TABLE `fact_facturas_pago`
  ADD PRIMARY KEY (`facturaPagoId`),
  ADD KEY `fk_fact_facturas_pago_cat_formas_pago1_idx` (`formaPagoId`),
  ADD KEY `fk_fact_facturas_pago_fact_facturas1_idx` (`facturaId`);

--
-- Indices de la tabla `fact_reservas`
--
ALTER TABLE `fact_reservas`
  ADD PRIMARY KEY (`reservaId`),
  ADD KEY `fk_fact_reservas_conf_sucursales1_idx` (`sucursalId`),
  ADD KEY `fk_fact_reservas_fact_clientes1_idx` (`clienteId`),
  ADD KEY `fk_fact_reservas_fact_facturas1_idx` (`facturaId`);

--
-- Indices de la tabla `fact_reservas_detalle`
--
ALTER TABLE `fact_reservas_detalle`
  ADD PRIMARY KEY (`reservaDetalleId`),
  ADD KEY `fk_fact_reservas_detalle_fact_reservas1_idx` (`reservaId`),
  ADD KEY `fk_fact_reservas_detalle_inv_productos1_idx` (`productoId`);

--
-- Indices de la tabla `fact_reservas_pago`
--
ALTER TABLE `fact_reservas_pago`
  ADD PRIMARY KEY (`reservaPagoId`),
  ADD KEY `fk_fact_reservas_pago_fact_reservas1_idx` (`reservaId`),
  ADD KEY `fk_fact_reservas_pago_cat_formas_pago1_idx` (`formaPagoId`);

--
-- Indices de la tabla `inv_kardex`
--
ALTER TABLE `inv_kardex`
  ADD PRIMARY KEY (`kardexId`),
  ADD KEY `fk_inv_kardex_inv_productos_existencias1_idx` (`productoExistenciaId`);

--
-- Indices de la tabla `inv_productos`
--
ALTER TABLE `inv_productos`
  ADD PRIMARY KEY (`productoId`),
  ADD KEY `fk_inv_productos_inv_productos_tipo1_idx` (`productoTipoId`),
  ADD KEY `fk_inv_productos_inv_productos_plataforma1_idx` (`productoPlataformaId`);

--
-- Indices de la tabla `inv_productos_existencias`
--
ALTER TABLE `inv_productos_existencias`
  ADD PRIMARY KEY (`productoExistenciaId`),
  ADD KEY `fk_inv_productos_existencias_inv_productos1_idx` (`productoId`),
  ADD KEY `fk_inv_productos_existencias_conf_sucursales1_idx` (`sucursalId`);

--
-- Indices de la tabla `inv_productos_plataforma`
--
ALTER TABLE `inv_productos_plataforma`
  ADD PRIMARY KEY (`productoPlataformaId`);

--
-- Indices de la tabla `inv_productos_tipo`
--
ALTER TABLE `inv_productos_tipo`
  ADD PRIMARY KEY (`productoTipoId`);

--
-- Indices de la tabla `log_productos_precios`
--
ALTER TABLE `log_productos_precios`
  ADD PRIMARY KEY (`logProductoPrecioId`),
  ADD KEY `fk_log_productos_precios_inv_productos1_idx` (`productoId`);

--
-- Indices de la tabla `log_usuarios`
--
ALTER TABLE `log_usuarios`
  ADD PRIMARY KEY (`logUsuarioId`),
  ADD KEY `fk_log_usuarios_conf_usuarios1_idx` (`usuarioId`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cat_actividad_economica`
--
ALTER TABLE `cat_actividad_economica`
  MODIFY `actividadEconomicaId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cat_documentos_identificacion`
--
ALTER TABLE `cat_documentos_identificacion`
  MODIFY `documentoIdentificacionId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cat_formas_pago`
--
ALTER TABLE `cat_formas_pago`
  MODIFY `formaPagoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cat_paises`
--
ALTER TABLE `cat_paises`
  MODIFY `paisId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cat_paises_ciudades`
--
ALTER TABLE `cat_paises_ciudades`
  MODIFY `paisCiudadId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cat_paises_estados`
--
ALTER TABLE `cat_paises_estados`
  MODIFY `paisEstadoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cat_tipo_contacto`
--
ALTER TABLE `cat_tipo_contacto`
  MODIFY `tipoContactoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cat_tipo_contribuyente`
--
ALTER TABLE `cat_tipo_contribuyente`
  MODIFY `tipoContribuyenteId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cat_tipo_persona`
--
ALTER TABLE `cat_tipo_persona`
  MODIFY `tipoPersonaId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cat_unidades_medida`
--
ALTER TABLE `cat_unidades_medida`
  MODIFY `unidadMedidaId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cof_empleados`
--
ALTER TABLE `cof_empleados`
  MODIFY `empleadoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cof_parametrizaciones`
--
ALTER TABLE `cof_parametrizaciones`
  MODIFY `parametrizacionId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cof_sucursales_contacto`
--
ALTER TABLE `cof_sucursales_contacto`
  MODIFY `sucursalContactoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comp_compras`
--
ALTER TABLE `comp_compras`
  MODIFY `compraId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comp_compras_detalle`
--
ALTER TABLE `comp_compras_detalle`
  MODIFY `compraDetalleId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comp_proveedores`
--
ALTER TABLE `comp_proveedores`
  MODIFY `proveedorId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comp_proveedores_contacto`
--
ALTER TABLE `comp_proveedores_contacto`
  MODIFY `proveedorContactoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comp_retaceo`
--
ALTER TABLE `comp_retaceo`
  MODIFY `retaceoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comp_retaceo_costos`
--
ALTER TABLE `comp_retaceo_costos`
  MODIFY `retaceoCostoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comp_retaceo_detalle`
--
ALTER TABLE `comp_retaceo_detalle`
  MODIFY `retaceoDetalleId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conf_menus`
--
ALTER TABLE `conf_menus`
  MODIFY `menuId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conf_menu_permisos`
--
ALTER TABLE `conf_menu_permisos`
  MODIFY `menuPermisoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conf_modulos`
--
ALTER TABLE `conf_modulos`
  MODIFY `moduloId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conf_roles`
--
ALTER TABLE `conf_roles`
  MODIFY `rolId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conf_roles_permisos`
--
ALTER TABLE `conf_roles_permisos`
  MODIFY `rolMenuId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conf_sucursales`
--
ALTER TABLE `conf_sucursales`
  MODIFY `sucursalId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conf_sucursales_usuarios`
--
ALTER TABLE `conf_sucursales_usuarios`
  MODIFY `sucursalUsuarioId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conf_usuarios`
--
ALTER TABLE `conf_usuarios`
  MODIFY `usuarioId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `fact_clientes`
--
ALTER TABLE `fact_clientes`
  MODIFY `clienteId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fact_cliente_contacto`
--
ALTER TABLE `fact_cliente_contacto`
  MODIFY `clienteContactoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fact_correlativos`
--
ALTER TABLE `fact_correlativos`
  MODIFY `correlativoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fact_facturas`
--
ALTER TABLE `fact_facturas`
  MODIFY `facturaId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fact_facturas_detalle`
--
ALTER TABLE `fact_facturas_detalle`
  MODIFY `facturaDetalleId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fact_facturas_pago`
--
ALTER TABLE `fact_facturas_pago`
  MODIFY `facturaPagoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fact_reservas`
--
ALTER TABLE `fact_reservas`
  MODIFY `reservaId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fact_reservas_detalle`
--
ALTER TABLE `fact_reservas_detalle`
  MODIFY `reservaDetalleId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fact_reservas_pago`
--
ALTER TABLE `fact_reservas_pago`
  MODIFY `reservaPagoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inv_kardex`
--
ALTER TABLE `inv_kardex`
  MODIFY `kardexId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inv_productos`
--
ALTER TABLE `inv_productos`
  MODIFY `productoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inv_productos_existencias`
--
ALTER TABLE `inv_productos_existencias`
  MODIFY `productoExistenciaId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inv_productos_plataforma`
--
ALTER TABLE `inv_productos_plataforma`
  MODIFY `productoPlataformaId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inv_productos_tipo`
--
ALTER TABLE `inv_productos_tipo`
  MODIFY `productoTipoId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `log_productos_precios`
--
ALTER TABLE `log_productos_precios`
  MODIFY `logProductoPrecioId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `log_usuarios`
--
ALTER TABLE `log_usuarios`
  MODIFY `logUsuarioId` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cat_paises_ciudades`
--
ALTER TABLE `cat_paises_ciudades`
  ADD CONSTRAINT `fk_cat_paises_ciudades_cat_paises1` FOREIGN KEY (`paisId`) REFERENCES `cat_paises` (`paisId`);

--
-- Filtros para la tabla `cat_paises_estados`
--
ALTER TABLE `cat_paises_estados`
  ADD CONSTRAINT `fk_cat_paises_estados_cat_paises_ciudades1` FOREIGN KEY (`paisCiudadId`) REFERENCES `cat_paises_ciudades` (`paisCiudadId`);

--
-- Filtros para la tabla `cof_empleados`
--
ALTER TABLE `cof_empleados`
  ADD CONSTRAINT `fk_cof_empleados_conf_sucursales` FOREIGN KEY (`sucursalId`) REFERENCES `conf_sucursales` (`sucursalId`);

--
-- Filtros para la tabla `cof_sucursales_contacto`
--
ALTER TABLE `cof_sucursales_contacto`
  ADD CONSTRAINT `fk_cof_sucursales_contacto_cat_tipo_contacto1` FOREIGN KEY (`tipoContactoId`) REFERENCES `cat_tipo_contacto` (`tipoContactoId`),
  ADD CONSTRAINT `fk_cof_sucursales_contacto_conf_sucursales1` FOREIGN KEY (`sucursalId`) REFERENCES `conf_sucursales` (`sucursalId`);

--
-- Filtros para la tabla `comp_compras`
--
ALTER TABLE `comp_compras`
  ADD CONSTRAINT `fk_comp_compras_cat_paises1` FOREIGN KEY (`paisId`) REFERENCES `cat_paises` (`paisId`),
  ADD CONSTRAINT `fk_comp_compras_comp_proveedores1` FOREIGN KEY (`proveedorId`) REFERENCES `comp_proveedores` (`proveedorId`);

--
-- Filtros para la tabla `comp_compras_detalle`
--
ALTER TABLE `comp_compras_detalle`
  ADD CONSTRAINT `fk_comp_compras_detalle_comp_compras1` FOREIGN KEY (`compraId`) REFERENCES `comp_compras` (`compraId`),
  ADD CONSTRAINT `fk_comp_compras_detalle_inv_productos1` FOREIGN KEY (`productoId`) REFERENCES `inv_productos` (`productoId`);

--
-- Filtros para la tabla `comp_proveedores`
--
ALTER TABLE `comp_proveedores`
  ADD CONSTRAINT `fk_comp_proveedores_cat_actividad_economica1` FOREIGN KEY (`actividadEconomicaId`) REFERENCES `cat_actividad_economica` (`actividadEconomicaId`),
  ADD CONSTRAINT `fk_comp_proveedores_cat_tipo_contribuyente1` FOREIGN KEY (`tipoContribuyenteId`) REFERENCES `cat_tipo_contribuyente` (`tipoContribuyenteId`),
  ADD CONSTRAINT `fk_comp_proveedores_cat_tipo_persona1` FOREIGN KEY (`tipoPersonaId`) REFERENCES `cat_tipo_persona` (`tipoPersonaId`);

--
-- Filtros para la tabla `comp_proveedores_contacto`
--
ALTER TABLE `comp_proveedores_contacto`
  ADD CONSTRAINT `fk_comp_proveedores_contacto_cat_tipo_contacto1` FOREIGN KEY (`tipoContactoId`) REFERENCES `cat_tipo_contacto` (`tipoContactoId`),
  ADD CONSTRAINT `fk_comp_proveedores_contacto_comp_proveedores1` FOREIGN KEY (`proveedorId`) REFERENCES `comp_proveedores` (`proveedorId`);

--
-- Filtros para la tabla `comp_retaceo_costos`
--
ALTER TABLE `comp_retaceo_costos`
  ADD CONSTRAINT `fk_comp_retaceo_costos_comp_retaceo1` FOREIGN KEY (`retaceoId`) REFERENCES `comp_retaceo` (`retaceoId`);

--
-- Filtros para la tabla `comp_retaceo_detalle`
--
ALTER TABLE `comp_retaceo_detalle`
  ADD CONSTRAINT `fk_comp_retaceo_detalle_comp_compras_detalle1` FOREIGN KEY (`compraDetalleId`) REFERENCES `comp_compras_detalle` (`compraDetalleId`),
  ADD CONSTRAINT `fk_comp_retaceo_detalle_comp_retaceo1` FOREIGN KEY (`retaceoId`) REFERENCES `comp_retaceo` (`retaceoId`);

--
-- Filtros para la tabla `conf_menus`
--
ALTER TABLE `conf_menus`
  ADD CONSTRAINT `fk_conf_menus_conf_modulos1` FOREIGN KEY (`moduloId`) REFERENCES `conf_modulos` (`moduloId`);

--
-- Filtros para la tabla `conf_menu_permisos`
--
ALTER TABLE `conf_menu_permisos`
  ADD CONSTRAINT `fk_conf_menu_permisos_conf_menus1` FOREIGN KEY (`menuId`) REFERENCES `conf_menus` (`menuId`);

--
-- Filtros para la tabla `conf_roles_permisos`
--
ALTER TABLE `conf_roles_permisos`
  ADD CONSTRAINT `fk_conf_roles_permisos_conf_menu_permisos1` FOREIGN KEY (`menuPermisoId`) REFERENCES `conf_menu_permisos` (`menuPermisoId`),
  ADD CONSTRAINT `fk_conf_roles_permisos_conf_roles1` FOREIGN KEY (`rolId`) REFERENCES `conf_roles` (`rolId`);

--
-- Filtros para la tabla `conf_sucursales_usuarios`
--
ALTER TABLE `conf_sucursales_usuarios`
  ADD CONSTRAINT `fk_conf_sucursales_usuarios_conf_sucursales1` FOREIGN KEY (`sucursalId`) REFERENCES `conf_sucursales` (`sucursalId`),
  ADD CONSTRAINT `fk_conf_sucursales_usuarios_conf_usuarios1` FOREIGN KEY (`sucursalId`) REFERENCES `conf_usuarios` (`usuarioId`);

--
-- Filtros para la tabla `conf_usuarios`
--
ALTER TABLE `conf_usuarios`
  ADD CONSTRAINT `fk_conf_usuarios_cof_empleados1` FOREIGN KEY (`empleadoId`) REFERENCES `cof_empleados` (`empleadoId`),
  ADD CONSTRAINT `fk_conf_usuarios_conf_roles1` FOREIGN KEY (`rolId`) REFERENCES `conf_roles` (`rolId`);

--
-- Filtros para la tabla `fact_clientes`
--
ALTER TABLE `fact_clientes`
  ADD CONSTRAINT `fk_fact_clientes_cat_actividad_economica1` FOREIGN KEY (`actividadEconomicaId`) REFERENCES `cat_actividad_economica` (`actividadEconomicaId`),
  ADD CONSTRAINT `fk_fact_clientes_cat_tipo_contribuyente1` FOREIGN KEY (`tipoContribuyenteId`) REFERENCES `cat_tipo_contribuyente` (`tipoContribuyenteId`),
  ADD CONSTRAINT `fk_fact_clientes_cat_tipo_persona1` FOREIGN KEY (`tipoPersonaId`) REFERENCES `cat_tipo_persona` (`tipoPersonaId`);

--
-- Filtros para la tabla `fact_cliente_contacto`
--
ALTER TABLE `fact_cliente_contacto`
  ADD CONSTRAINT `fk_fact_cliente_contacto_cat_tipo_contacto1` FOREIGN KEY (`tipoContactoId`) REFERENCES `cat_tipo_contacto` (`tipoContactoId`),
  ADD CONSTRAINT `fk_fact_cliente_contacto_fact_clientes1` FOREIGN KEY (`clienteId`) REFERENCES `fact_clientes` (`clienteId`);

--
-- Filtros para la tabla `fact_correlativos`
--
ALTER TABLE `fact_correlativos`
  ADD CONSTRAINT `fk_fact_correlativos_conf_sucursales1` FOREIGN KEY (`sucursalId`) REFERENCES `conf_sucursales` (`sucursalId`);

--
-- Filtros para la tabla `fact_facturas`
--
ALTER TABLE `fact_facturas`
  ADD CONSTRAINT `fk_fact_facturas_conf_sucursales1` FOREIGN KEY (`sucursalId`) REFERENCES `conf_sucursales` (`sucursalId`),
  ADD CONSTRAINT `fk_fact_facturas_fact_clientes1` FOREIGN KEY (`clienteId`) REFERENCES `fact_clientes` (`clienteId`),
  ADD CONSTRAINT `fk_fact_facturas_fact_correlativos1` FOREIGN KEY (`correlativoId`) REFERENCES `fact_correlativos` (`correlativoId`);

--
-- Filtros para la tabla `fact_facturas_detalle`
--
ALTER TABLE `fact_facturas_detalle`
  ADD CONSTRAINT `fk_fact_facturas_detalle_fact_facturas1` FOREIGN KEY (`facturaId`) REFERENCES `fact_facturas` (`facturaId`),
  ADD CONSTRAINT `fk_fact_facturas_detalle_inv_productos1` FOREIGN KEY (`productoId`) REFERENCES `inv_productos` (`productoId`);

--
-- Filtros para la tabla `fact_facturas_pago`
--
ALTER TABLE `fact_facturas_pago`
  ADD CONSTRAINT `fk_fact_facturas_pago_cat_formas_pago1` FOREIGN KEY (`formaPagoId`) REFERENCES `cat_formas_pago` (`formaPagoId`),
  ADD CONSTRAINT `fk_fact_facturas_pago_fact_facturas1` FOREIGN KEY (`facturaId`) REFERENCES `fact_facturas` (`facturaId`);

--
-- Filtros para la tabla `fact_reservas`
--
ALTER TABLE `fact_reservas`
  ADD CONSTRAINT `fk_fact_reservas_conf_sucursales1` FOREIGN KEY (`sucursalId`) REFERENCES `conf_sucursales` (`sucursalId`),
  ADD CONSTRAINT `fk_fact_reservas_fact_clientes1` FOREIGN KEY (`clienteId`) REFERENCES `fact_clientes` (`clienteId`),
  ADD CONSTRAINT `fk_fact_reservas_fact_facturas1` FOREIGN KEY (`facturaId`) REFERENCES `fact_facturas` (`facturaId`);

--
-- Filtros para la tabla `fact_reservas_detalle`
--
ALTER TABLE `fact_reservas_detalle`
  ADD CONSTRAINT `fk_fact_reservas_detalle_fact_reservas1` FOREIGN KEY (`reservaId`) REFERENCES `fact_reservas` (`reservaId`),
  ADD CONSTRAINT `fk_fact_reservas_detalle_inv_productos1` FOREIGN KEY (`productoId`) REFERENCES `inv_productos` (`productoId`);

--
-- Filtros para la tabla `fact_reservas_pago`
--
ALTER TABLE `fact_reservas_pago`
  ADD CONSTRAINT `fk_fact_reservas_pago_cat_formas_pago1` FOREIGN KEY (`formaPagoId`) REFERENCES `cat_formas_pago` (`formaPagoId`),
  ADD CONSTRAINT `fk_fact_reservas_pago_fact_reservas1` FOREIGN KEY (`reservaId`) REFERENCES `fact_reservas` (`reservaId`);

--
-- Filtros para la tabla `inv_kardex`
--
ALTER TABLE `inv_kardex`
  ADD CONSTRAINT `fk_inv_kardex_inv_productos_existencias1` FOREIGN KEY (`productoExistenciaId`) REFERENCES `inv_productos_existencias` (`productoExistenciaId`);

--
-- Filtros para la tabla `inv_productos`
--
ALTER TABLE `inv_productos`
  ADD CONSTRAINT `fk_inv_productos_inv_productos_plataforma1` FOREIGN KEY (`productoPlataformaId`) REFERENCES `inv_productos_plataforma` (`productoPlataformaId`),
  ADD CONSTRAINT `fk_inv_productos_inv_productos_tipo1` FOREIGN KEY (`productoTipoId`) REFERENCES `inv_productos_tipo` (`productoTipoId`);

--
-- Filtros para la tabla `inv_productos_existencias`
--
ALTER TABLE `inv_productos_existencias`
  ADD CONSTRAINT `fk_inv_productos_existencias_conf_sucursales1` FOREIGN KEY (`sucursalId`) REFERENCES `conf_sucursales` (`sucursalId`),
  ADD CONSTRAINT `fk_inv_productos_existencias_inv_productos1` FOREIGN KEY (`productoId`) REFERENCES `inv_productos` (`productoId`);

--
-- Filtros para la tabla `log_productos_precios`
--
ALTER TABLE `log_productos_precios`
  ADD CONSTRAINT `fk_log_productos_precios_inv_productos1` FOREIGN KEY (`productoId`) REFERENCES `inv_productos` (`productoId`);

--
-- Filtros para la tabla `log_usuarios`
--
ALTER TABLE `log_usuarios`
  ADD CONSTRAINT `fk_log_usuarios_conf_usuarios1` FOREIGN KEY (`usuarioId`) REFERENCES `conf_usuarios` (`usuarioId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

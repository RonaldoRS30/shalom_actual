<?php
include("system/application/libraries/cezpdf.php");
include("system/application/libraries/class.backgroundpdf.php");

class Almacen extends controller{

    private $empresa;
    private $compania;
    private $url;
    private $establecimiento;
    private $nombre_establecimiento;

    public function __construct(){
        parent::__construct();

        $this->load->helper('form');
        $this->load->helper('date');
        $this->load->helper('util');
        $this->load->helper('utf_helper');
        $this->load->helper('form', 'url');
        
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('html');
        $this->load->library('pagination');
        
        
        $this->load->model('almacen/almacen_model');
        $this->load->model('almacen/tipoalmacen_model');
        $this->load->model('almacen/almacenproducto_model');
        $this->load->model('almacen/fabricante_model');
        $this->load->model('almacen/unidadmedida_model');

        $this->empresa = $this->session->userdata('empresa');
        $this->compania = $this->session->userdata('compania');
        $this->establecimiento = $this->session->userdata('establec');
        $this->nombre_establecimiento = $this->session->userdata('nombre_establec');
        $this->url = base_url();
    }

    ######################
    #### FUNCTIONS NEWS
    ######################

        public function listar(){
            $data['base_url'] = $this->url;
            $data['tipo_almacen'] = $this->tipoalmacen_model->listar();
            $data['establecimiento'] = $this->establecimiento;
            $data['nombre_establecimiento'] = $this->nombre_establecimiento;

            $data['titulo_busqueda'] = "BUSCAR ALMACEN";
            $data['titulo'] = "RELACIÓN DE ALMACENES";
            $this->layout->view('almacen/almacen_index', $data);
        }

        public function datatable_almacen(){

            $columnas = array(
                                0 => "ALMAC_CodigoUsuario",
                                1 => "EESTABC_Descripcion",
                                2 => "ALMAC_Descripcion",
                                3 => "TIPALM_Descripcion",
                                4 => "ALMAC_Direccion"
                            );
            
            $filter = new stdClass();
            $filter->start = $this->input->post("start");
            $filter->length = $this->input->post("length");
            $filter->search = $this->input->post("search")["value"];

            $ordenar = $this->input->post("order")[0]["column"];
            if ($ordenar != ""){
                $filter->order = $columnas[$ordenar];
                $filter->dir = $this->input->post("order")[0]["dir"];
            }

            $item = ($this->input->post("start") != "") ? $this->input->post("start") : 0;

            $filter->descripcion = $this->input->post('descripcion');
            $filter->tipo = $this->input->post('tipo');

            $almacenInfo = $this->almacen_model->getAlmacens($filter);
            $lista = array();
            
            if (count($almacenInfo) > 0) {
                foreach ($almacenInfo as $indice => $valor) {
                    $btn_modal = "<button type='button' onclick='editar($valor->ALMAP_Codigo)' class='btn btn-default'>
                                    <img src='".$this->url."/images/modificar.png' class='image-size-1b'>
                                </button>";

                    $btn_eliminar = "<button type='button' onclick='deshabilitar($valor->ALMAP_Codigo)' class='btn btn-default'>
                                    <img src='".$this->url."/images/documento-delete.png' class='image-size-1b'>
                                </button>";

                    $lista[] = array(
                                        0 => $valor->ALMAC_CodigoUsuario,
                                        1 => $valor->EESTABC_Descripcion,
                                        2 => $valor->ALMAC_Descripcion,
                                        3 => $valor->TIPALM_Descripcion,
                                        4 => $valor->ALMAC_Direccion,
                                        5 => $btn_modal,
                                        6 => $btn_eliminar
                                    );
                }
            }

            unset($filter->start);
            unset($filter->length);

            $json = array(
                                "draw"            => intval( $this->input->post('draw') ),
                                "recordsTotal"    => count($this->almacen_model->getAlmacens()),
                                "recordsFiltered" => intval( count($this->almacen_model->getAlmacens($filter)) ),
                                "data"            => $lista
                        );

            echo json_encode($json);
        }

        public function getAlmacen(){

            $almacen = $this->input->post("almacen");

            $almacenInfo = $this->almacen_model->getAlmacen($almacen);
            $lista = array();
            
            if ( $almacenInfo != NULL ){
                foreach ($almacenInfo as $indice => $val) {
                    $lista = array(
                                        "almacen" => $val->ALMAP_Codigo,
                                        "codigo" => $val->ALMAC_CodigoUsuario,
                                        "descripcion" => $val->ALMAC_Descripcion,
                                        "tipo" => $val->TIPALM_Codigo,
                                        "direccion" => $val->ALMAC_Direccion
                                    );
                }

                $json = array("match" => true, "info" => $lista);
            }
            else
                $json = array("match" => false, "info" => "");

            echo json_encode($json);
        }

        public function guardar_registro(){

            $almacen = $this->input->post("almacen");
            $establecimiento = $this->input->post("establecimiento");
            $codigo_almacen = $this->input->post("codigo_almacen");
            $descripcion_almacen = $this->input->post("descripcion_almacen");
            $tipo_almacen = $this->input->post("tipo_almacen");
            $direccion_almacen = $this->input->post("direccion_almacen");
            
            $filter = new stdClass();
            $filter->TIPALM_Codigo = $tipo_almacen;
            $filter->EESTABP_Codigo = $this->establecimiento;
            $filter->CENCOSP_Codigo = "1";
            $filter->ALMAC_Descripcion = strtoupper($descripcion_almacen);
            $filter->ALMAC_Direccion = strtoupper($direccion_almacen);
            $filter->ALMAC_CodigoUsuario = $codigo_almacen;
            $filter->ALMAC_FlagEstado = "1";

            if ($almacen != ""){
                $filter->ALMAP_Codigo = $almacen;
                $filter->ALMAC_FechaModificacion = date("Y-m-d H:i:s");
                $result = $this->almacen_model->actualizar_almacen($almacen, $filter);
            }
            else{
                $filter->COMPP_Codigo = $this->compania;
                $filter->ALMAC_FechaRegistro = date("Y-m-d H:i:s");
                $result = $this->almacen_model->insertar_almacen($filter);

                $filter_inve = new stdClass();
                $filter_inve->INVE_Titulo   = "INVENTARIO ".$filter->ALMAC_Descripcion;
                $filter_inve->COMPP_Codigo  = $this->compania;
                $filter_inve->INVE_Serie    = "INV";
                $filter_inve->INVE_Numero   = $filter->ALMAC_CodigoUsuario;
                $filter_inve->ALMAP_Codigo  = $result;
                $filter_inve->INVE_FechaInicio  = date("Y-m-d H:i:s");
                $filter_inve->INVE_FechaRegistro = date("Y-m-d H:i:s");
                $filter_inve->INVE_FlagEstado = "1";

                $this->almacen_model->insertar_inventario($filter_inve);
    
            }

            if ($result)
                $json = array("result" => "success");
            else
                $json = array("result" => "error");
            
            echo json_encode($json);
        }

        public function deshabilitar_almacen(){

            $almacen = $this->input->post("almacen");

            $filter = new stdClass();
            $filter->ALMAC_FlagEstado  = "0";

            if ($almacen != ""){
                $filter->ALMAC_FechaModificacion = date("Y-m-d H:i:s");
                $result = $this->almacen_model->deshabilitar_almacen($almacen, $filter);
            }

            if ($result)
                $json = array("result" => "success");
            else
                $json = array("result" => "error");
            
            echo json_encode($json);
        }

    ######################
    #### FUNCTIONS OLDS
    ######################

    public function nuevo()
    {
        $lista_compania = $this->compania_model->obtener_compania($this->compania);
        $lista_estab = $this->emprestablecimiento_model->obtener($lista_compania[0]->EESTABP_Codigo);

        
        $lblEstab = form_label("Establecimiento", "Establecimiento");
        $lblDescripcion = form_label("Nombre Almacen", "Nombre Almacen");
        $lblTipoAlmacen = form_label('Tipo Almacen', 'Tipo Almacen');
        $lblDireccionAlmacen = form_label('Direccón Almacen', 'Dirección Almacen');
        $lblCodigoUsuario = form_label("Código", "CodigoUsuario");
        $nombre_estab = form_input(array('name' => 'establecimiento', 'id' => 'establecimiento', 'value' => $lista_estab[0]->EESTABC_Descripcion, 'maxlength' => '100', 'class' => 'cajaGrande cajaSoloLectura', 'readonly' => 'readonly'));
        $nombre_almacen = form_input(array('name' => 'descripcion', 'id' => 'descripcion', 'value' => '', 'maxlength' => '100', 'class' => 'cajaMedia'));
        $tipo_almacen = form_dropdown('tipo_almacen', $this->tipoalmacen_model->seleccionar(), 'large', "id='tipo_almacen' class='comboMedio'");
        $direccion_almacen = form_input(array('name' => 'direccion', 'id' => 'direccion', 'value' => '', 'maxlength' => '249', 'class' => 'cajaGrande', 'style' => 'width:100%;'));
        $codigo_usuario = form_input(array('name' => 'codigo_usuario', 'id' => 'codigo_usuario', 'value' => '', 'maxlength' => '20', 'class' => 'cajaPequena'));
        $data['titulo'] = "REGISTRAR ALMACEN";
        $data['form_open'] = form_open(base_url() . 'index.php/almacen/almacen/grabar', array("name" => "frmAlmacen", "id" => "frmAlmacen"));
        $data['form_close'] = form_close();
        $data['campos'] = array($lblEstab, $lblDescripcion, $lblTipoAlmacen, $lblDireccionAlmacen, $lblCodigoUsuario);
        $data['valores'] = array($nombre_estab, $nombre_almacen, $tipo_almacen, $direccion_almacen, $codigo_usuario);
        $data['oculto'] = form_hidden(array('codigo' => "", 'base_url' => base_url(), 'almacen_id' => ''));
        $data['onload'] = "onload=\"$('#nombres').focus();\"";
        $this->layout->view('almacen/almacen_nuevo', $data);
    }

    public function editar($id)
    {
        $lista_compania = $this->compania_model->obtener_compania($this->compania);
        $lista_estab = $this->emprestablecimiento_model->obtener($lista_compania[0]->EESTABP_Codigo);

        
        $oAlmacen = $this->almacen_model->obtener($id);
        $lblEstab = form_label("Establecimiento", "Establecimiento");
        $lblDescripcion = form_label("Nombre Almacen", "Nombre Almacen");
        $lblTipoAlmacen = form_label("Tipo Almacen", "Tipo almacen");
        $lblDireccionAlmacen = form_label('Direccón Almacen', 'Dirección Almacen');
        $lblCodigoUsuario = form_label("Código", "CodigoUsuario");
        $nombre_estab = form_input(array('name' => 'establecimiento', 'id' => 'establecimiento', 'value' => $lista_estab[0]->EESTABC_Descripcion, 'maxlength' => '100', 'class' => 'cajaGrande cajaSoloLectura', 'readonly' => 'readonly'));
        $nombre_almacen = form_input(array('name' => 'descripcion', 'id' => 'descripcion', 'value' => $oAlmacen[0]->ALMAC_Descripcion, 'maxlength' => '100', 'class' => 'cajaMedia'));
        $tipo_almacen = form_dropdown('tipo_almacen', $this->tipoalmacen_model->seleccionar(), $oAlmacen[0]->TIPALM_Codigo, "id='tipo_almacen' class='fuente8'");
        $direccion_almacen = form_input(array('name' => 'direccion', 'id' => 'direccion', 'value' => $oAlmacen[0]->ALMAC_Direccion, 'maxlength' => '249', 'class' => 'cajaGrande', 'style' => 'width:100%;'));
        $codigo_usuario = form_input(array('name' => 'codigo_usuario', 'id' => 'codigo_usuario', 'value' => $oAlmacen[0]->ALMAC_CodigoUsuario, 'maxlength' => '20', 'class' => 'cajaPequena'));
        $data['form_open'] = form_open(base_url() . 'index.php/almacen/almacen/grabar/', array("name" => "frmAlmacen", "id" => "frmAlmacen"));
        $data['campos'] = array($lblEstab, $lblDescripcion, $lblTipoAlmacen, $lblDireccionAlmacen, $lblCodigoUsuario);
        $data['valores'] = array($nombre_estab, $nombre_almacen, $tipo_almacen, $direccion_almacen, $codigo_usuario);
        $data['oculto'] = form_hidden(array('codigo' => "", 'base_url' => base_url(), 'almacen_id' => $id));
        $data['form_close'] = form_close();
        $data['titulo'] = "Editar ALMACEN";
        $this->layout->view('almacen/almacen_nuevo', $data);
    }

    public function grabar()
    {
        $this->form_validation->set_rules('descripcion', 'Nombre de almacen', 'required');
        $this->form_validation->set_rules('tipo_almacen', 'Tipo de almacen', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->nuevo();
        } else {
            $descripcion = $this->input->post("descripcion");
            $tipo_almacen = $this->input->post("tipo_almacen");
            $almacen_id = $this->input->post("almacen_id");
            $direccion = $this->input->post("direccion");
            $codigo_usuario = $this->input->post("codigo_usuario");
            $filter = new stdClass();
            $lista_compania = $this->compania_model->obtener_compania($this->compania);
            $filter->EESTABP_Codigo = $lista_compania[0]->EESTABP_Codigo;
            $filter->ALMAC_Descripcion = strtoupper($descripcion);
            $filter->TIPALM_Codigo = $tipo_almacen;
            $filter->CENCOSP_Codigo = 1;
            $filter->ALMAC_CodigoUsuario = $codigo_usuario;
            $filter->ALMAC_Direccion = $direccion;
            if (isset($almacen_id) && $almacen_id > 0) {
                $this->almacen_model->modificar($almacen_id, $filter);
            } else {
                $filter->COMPP_Codigo = $this->compania;
                $this->almacen_model->insertar($filter);
            }
            header("location:" . base_url() . "index.php/almacen/almacen/listar");
        }
    }

    public function eliminar()
    {
        $id = $this->input->post('almacen');
        $this->almacen_model->eliminar($id);
    }

    public function ver($codigo)
    {
        
        $datos_almacen = $this->almacen_model->obtener($codigo);
        $nombre_almacen = $datos_almacen[0]->ALMAC_Descripcion;
        $direccion_almacen = $datos_almacen[0]->ALMAC_Direccion;
        $tipo_almacen = $datos_almacen[0]->TIPALM_Codigo;
        $datos_tipoalmacen = $this->tipoalmacen_model->obtener($tipo_almacen);
        $nombre_tipoalmacen = $datos_tipoalmacen[0]->TIPALM_Descripcion;
        $data['nombre_almacen'] = $nombre_almacen;
        $data['direccion_almacen'] = $direccion_almacen;
        $data['nombre_tipoalmacen'] = $nombre_tipoalmacen;
        $data['titulo'] = "VER ALMACEN";
        $data['oculto'] = form_hidden(array('base_url' => base_url()));
        $this->layout->view('almacen/almacen_ver', $data);
    }

    public function buscar($j = 0)
    {
        
        $nombre_almacen = $this->input->post('nombre_almacen');
        $tipo_almacen = $this->input->post('tipo_almacen');
        $filter = new stdClass();
        $filter->ALMAC_Descripcion = $nombre_almacen;
        $filter->TIPALM_Codigo = $tipo_almacen;
        $data['registros'] = count($this->almacen_model->buscar($filter));
        $conf['base_url'] = site_url('almacen/almacen/buscar/');
        $conf['per_page'] = 10;
        $conf['num_links'] = 3;
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link'] = "&gt;&gt;";
        $conf['total_rows'] = $data['registros'];
        $offset = (int)$this->uri->segment(4);
        $listado = $this->almacen_model->buscar($filter, $conf['per_page'], $offset);
        $item = $j + 1;
        $lista = array();
        if (count($listado) > 0) {
            foreach ($listado as $indice => $valor) {
                $codigo = $valor->ALMAP_Codigo;
                $editar = "<a href='#' onclick='editar_almacen(" . $codigo . ")'><img src='" . base_url() . "images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
                $ver = "<a href='#' onclick='ver_almacen(" . $codigo . ")'><img src='" . base_url() . "images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
                $eliminar = "<a href='#' onclick='eliminar_almacen(" . $codigo . ")'><img src='" . base_url() . "images/eliminar.png' width='16' height='16' border='0' title='Modificar'></a>";
                $lista[] = array($item++, $valor->ALMAC_Descripcion, $valor->EESTABC_Descripcion, $valor->ALMAC_CodigoUsuario, $valor->TIPALM_Descripcion, $editar, $ver, $eliminar, $valor->ALMAC_Direccion);
            }
        }
        $data['titulo_tabla'] = "RESULTADO DE BUSQUEDA de ALMACENES";
        $data['titulo_busqueda'] = "BUSCAR ALMACEN";
        $data['nombre_almacen'] = form_input(array('name' => 'nombre_almacen', 'id' => 'nombre_almacen', 'value' => $nombre_almacen, 'maxlength' => '100', 'class' => 'cajaMedia'));
        $data['tipo_almacen'] = form_dropdown('tipo_almacen', $this->tipoalmacen_model->seleccionar(), $tipo_almacen, "id='tipo_almacen' class='comboMedio'");
        $data['form_open'] = form_open(base_url() . 'index.php/almacen/almacen/buscar', array("name" => "form_busquedaAlmacen", "id" => "form_busquedaAlmacen"));
        $data['form_close'] = form_close();
        $data['lista'] = $lista;
        $data['oculto'] = form_hidden(array('base_url' => base_url()));
        $this->pagination->initialize($conf);
        $data['paginacion'] = $this->pagination->create_links();
        $this->layout->view('almacen/almacen_index', $data);
    }

    public function reportes()
    {
        $combo = '';
        
        $data['titulo'] = "REPORTES DE ALMACEN";
        $data['combo'] = $combo;
        $data['cboAlmacen'] = form_dropdown("almacen_id", $this->almacen_model->seleccionar("TODOS"), false, " class='comboMedio' id='almacen_id'");
        $this->layout->view('almacen/alamcen_reporte', $data);
    }

    public function reporte_xls($almacen_id = "")
    {
        $listado = $this->almacenproducto_model->listar($almacen_id);
        $xls = utf8_decode_seguro('<b>REPORTE DE PRODUCTOS POR ALMACEN: ') . '</b>';
        $date = date('Y-m-d');
        //$item               = $j+1;
        $kk = 1;
        $lista = array();
        $producto_anterior = 0;
        $cantidad_anterior = 0;
        $costo_anterior = 0;
        $filtro = $almacen_id != "" ? true : false;
        header('Content-Disposition: attachment; filename="' . $date . '.xls"');
        header("Content-Type: application/vnd.ms-excel");
        $xls .= "
		<table border=1>
		<tr><th>Item</th><th>" . utf8_decode_seguro('Código Interno') . "</th><th>" . utf8_decode_seguro('Descripción') . "</th><th>" . utf8_decode_seguro('Código de Usuario') . "</th><th>Stock</th><th>Uni.</th><th>Costo</th><th>Valor</th></tr>
		";
        if (count($listado) > 0) {
            foreach ($listado as $indice => $valor) {
                $almacen = $valor->ALMAC_Codigo;
                $producto = $valor->PROD_Codigo;
                $cantidad = $valor->ALMPROD_Stock;
                $costo = $valor->ALMPROD_CostoPromedio;
                $producto = $valor->PROD_Codigo;
                $kardex = "<a href='#' onclick='ver_kardex(" . $producto_anterior . ")'><img src='" . base_url() . "images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
                if ($producto != $producto_anterior && $producto_anterior != 0) {
                    $kk++;
                    $datos_producto = $this->producto_model->obtener_producto($producto_anterior);
                    $nombre_prod = $datos_producto[0]->PROD_Nombre;
                    $codigo_prod = $datos_producto[0]->PROD_CodigoInterno;
                    $fabricante = $datos_producto[0]->FABRIP_Codigo;
                    $flagGenInd = $datos_producto[0]->PROD_GenericoIndividual;
                    $datos_fab = $this->fabricante_model->obtener($fabricante);
                    $nombre_fab = $datos_fab[0]->FABRIC_Descripcion;
                    $datos_unidad = $this->producto_model->obtener_producto_unidad($producto_anterior);
                    $unidad_med = $datos_unidad[0]->UNDMED_Codigo;
                    $datos_unidad2 = $this->unidadmedida_model->obtener($unidad_med);
                    $nombre_und = $datos_unidad2[0]->UNDMED_Simbolo;
                    $xls .= "<tr><td>" . ($indice++) . "</td><td>" . $codigo_prod . "</td><td>" . utf8_decode_seguro($nombre_prod) . "</td><td>" . $valor->PROD_CodigoUsuario . "</td><td>" . $cantidad_anterior . "</td><td>" . $nombre_und . "</td><td>" . number_format($costo_anterior, 2) . "</td><td>" . number_format($cantidad_anterior * $costo_anterior, 2) . "</td></tr>";
                } elseif ($producto == $producto_anterior) {
                    $cantidadn = $cantidad_anterior + $cantidad;
                    $coston = ($cantidad_anterior * $costo_anterior + $cantidad * $costo) / $cantidadn;
                    $cantidad = $cantidadn;
                    $costo = $coston;
                }
                $producto_anterior = $producto;
                $cantidad_anterior = $cantidad;
                $costo_anterior = $costo;
            }
            $datos_producto = $this->producto_model->obtener_producto($producto);
            $nombre_prod = $datos_producto[0]->PROD_Nombre;
            $codigo_prod = $datos_producto[0]->PROD_CodigoInterno;
            $fabricante = $datos_producto[0]->FABRIP_Codigo;
            $flagGenInd = $datos_producto[0]->PROD_GenericoIndividual;
            $datos_fab = $this->fabricante_model->obtener($fabricante);
            $nombre_fab = $datos_fab[0]->FABRIC_Descripcion;
            $datos_unidad = $this->producto_model->obtener_producto_unidad($producto);
            $unidad_med = $datos_unidad[0]->UNDMED_Codigo;
            $datos_unidad2 = $this->unidadmedida_model->obtener($unidad_med);
            $nombre_und = $datos_unidad2[0]->UNDMED_Simbolo;
            $xls .= "<tr><td>" . ($indice++) . "</td><td>" . $codigo_prod . "</td><td>" . utf8_decode_seguro($nombre_prod) . "</td><td>" . $valor->PROD_CodigoUsuario . "</td><td>" . $cantidad_anterior . "</td><td>" . $nombre_und . "</td><td>" . number_format($costo_anterior, 2) . "</td><td>" . number_format($cantidad_anterior * $costo_anterior, 2) . "</td></tr>";
        }
        $data['xls'] = $xls;
        $this->load->view('almacen/almacen_reporte_xls', $data);
    }
    
    
    public function obtener_stock_general_por_almacenes(){
		$producto = $this->input->post('product');

 		$consulta = $this->almacen_model->stock_general_por_almacenes($producto);
        $result = array();
      
        foreach ($consulta AS $datos => $value) {
 
          $empresa = $value->EMPRC_RazonSocial;
          $sucursal = $value->EESTABC_Descripcion;
          $almacen = $value->ALMAC_Descripcion;
          $stock = $value->ALMPROD_Stock ;
          $result[] = array("resultado" => "success" , "empresa" => $empresa, "sucursal" => $sucursal, "almacen" => $almacen, "stock" => $stock);
        }    
        
        echo json_encode($result);
	}

    public function obtene_stock(){
        $codigoInterno = $this->input->post('codigoInterno');
        $selectAlmacen = $this->input->post('selectAlmacen');
        $consulta = $this->almacen_model->consultar_stock_porAlmaen($codigoInterno,$selectAlmacen);
        
          $result = array();
          foreach ($consulta AS $datos => $value) {
            $empresa = $value->EMPRC_RazonSocial;
            $sucursal = $value->EESTABC_Descripcion;
            $almacen = $value->ALMAC_Descripcion;
            $stock = $value->ALMPROD_Stock ;
            $result[] = array("resultado" => "success" , "empresa" => $empresa, "sucursal" => $sucursal, "almacen" => $almacen, "stock" => $stock);
          }    
          echo json_encode($result);
      }
      //########## FIN DE AXCEL  ##########
    
      public function Leer_Guias_Excel(){
        //$compania = $this->compania;
        $temp = $this->almacen_model->obtener_compania($this->compania);
        $establCodigo = $temp[0]->EESTABP_Codigo;
        $respuesta = array();
        $this->load->library('Excel');
        $archivo = $_FILES["file"]["tmp_name"];
        $inputFileType = PHPExcel_IOFactory::identify($archivo);
        // Crea un lector basado en el tipo de archivo identificado
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        // Carga el archivo Excel
        $objPHPExcel = $objReader->load($archivo);
        // Obtiene la hoja de trabajo (worksheet)
        $sheet = $objPHPExcel->getSheet(0);
        // Obtiene el número total de filas y columnas en la hoja de trabajo
        $highestRow = $sheet->getHighestRow();
        $highestColumn = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
        $row_uno = 8;
        for ($row = 9; $row <= $highestRow; $row++) {
            $codigo_producto = $sheet->getCellByColumnAndRow(1, $row)->getValue();     
            for ($col = 1; $col < $highestColumn - 1; $col++) {
                $value = $sheet->getCellByColumnAndRow($col + 1, $row)->getValue();
                $codigo_tipo_cliente = $sheet->getCellByColumnAndRow($col + 1, $row_uno)->getValue();//funciona
                $respuesta_codigo = $this->almacen_model->get_tipo_cliente($codigo_tipo_cliente);
                $codigo_categoria_precios = $respuesta_codigo[0]->TIPCLIP_Codigo;//el numero de la respuesta del tipo cliente
                // print_r($value.'--');
                // print_r($compania.'--');
                // print_r($codigo_producto.'--');
                // print_r($codigo_categoria_precios.'<br>');
                $actualiza_precios = $this->almacen_model->actualiza_precio($value,$establCodigo,$codigo_producto,$codigo_categoria_precios);
            } 
        }
      }
    
    
      public function insertarProductosMasiva(){
        $this->load->library('Excel');
        $archivo = $_FILES["file"]["tmp_name"];
        $inputFileType = PHPExcel_IOFactory::identify($archivo);
        // Crea un lector basado en el tipo de archivo identificado
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        // Carga el archivo Excel
        $objPHPExcel = $objReader->load($archivo);
        $sheet = $objPHPExcel->getSheet(0);
        // Obtiene el número total de filas en la hoja de trabajo.
        $highestRow = $sheet->getHighestRow();
        //Obtiene el numero total de columnas en la hoja de trabajo.
        $highestColumn = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
    
        #OBTENER COMPANIA Y ESTABLECIMIENTO.
        $establecimiento_compania = $this->almacen_model->getCompaniaEstablecimiento();
    
        #OBTENEMOS LA CATEGORIA DE PRECIOS, NO IMPORTA LA CANTIDAD.
        $categoria_precios = $this->almacen_model->getCategoriaPrecios();
      
        #OBTENEMOS LAS DIFERENTES MONEDAS QUE PUEDE UTILIZAR.
        $moneda_producto = $this->almacen_model->getMonedaProducto();
    
        $array = array();
    
        // Itera sobre las filas y columnas
        for ($row = 2; $row <= $highestRow; ++$row) {
    
          // Obtén el valor de la celda en la columna A y la fila actual
          $productos_repetidos = $sheet->getCellByColumnAndRow(0, $row)->getValue();
    
          $validar_productos_repetidos = $this->almacen_model->getDatosValidarProductos($productos_repetidos);
    
          if ($validar_productos_repetidos == null) {
    
            $producto_descripcion = $sheet->getCellByColumnAndRow(1, $row)->getValue();
            $product_modelo = $sheet->getCellByColumnAndRow(2, $row)->getValue();
            $product_marca = $sheet->getCellByColumnAndRow(3, $row)->getValue();
            $product_familia = $sheet->getCellByColumnAndRow(4, $row)->getValue();
            $product_fabricante = $sheet->getCellByColumnAndRow(5, $row)->getValue();
            $product_precio_costo = $sheet->getCellByColumnAndRow(6, $row)->getValue();
            $stock_minimo = $sheet->getCellByColumnAndRow(7, $row)->getValue();
      
            $marca_producto = $this->almacen_model->getMarcaProducto($product_marca);
            $familia_producto = $this->almacen_model->getFamiliaProducto($product_familia);
            $fabricante_producto = $this->almacen_model->getFabricanteProducto($product_fabricante);
    
            $filter_producto = new stdClass();
            $filter_producto->PROD_FlagBienServicio = "B";
            $filter_producto->AFECT_Codigo = 1;
            $filter_producto->FAMI_Codigo = $familia_producto;
            $filter_producto->MARCP_Codigo = $marca_producto;
            $filter_producto->FABRIP_Codigo = $fabricante_producto;
            $filter_producto->PROD_Nombre = $producto_descripcion;
            $filter_producto->PROD_NombreCorto = $producto_descripcion;
            $filter_producto->PROD_Stock = 0;
            $filter_producto->PROD_StockMinimo = $stock_minimo;
            $filter_producto->PROD_CodigoInterno = $productos_repetidos;
            $filter_producto->PROD_CodigoUsuario = $productos_repetidos;
            $filter_producto->PROD_UltimoCosto = $product_precio_costo;
            $filter_producto->PROD_Modelo = $product_modelo;
            $filter_producto->PROD_GenericoIndividual = "G";
            $filter_producto->PROD_FlagActivo = 1;
            $filter_producto->PROD_FlagEstado = 1;
      
            #EN ESTA VARIABLE ALMACENAMOS EL ID DEL PRODUCTO A INSERTAR.  PROD_GenericoIndividual
            $id_insertar_productos = $this->almacen_model->insertProductos($filter_producto);
           
            for ($i=0; $i < count($establecimiento_compania) ; $i++) { 
    
              $filter_producto_compania = new stdClass();
              $filter_producto_compania->PROD_Codigo = $id_insertar_productos;
              $filter_producto_compania->COMPP_Codigo = $establecimiento_compania[$i]->COMPP_Codigo;
    
              $insertar_producto_compania = $this->almacen_model->insertProductoCompania($filter_producto_compania);
              
            }
    
            for ($j=0; $j < count($establecimiento_compania); $j++) { 
    
              $valor_establecimiento = $establecimiento_compania[$j]->EESTABP_Codigo;
            }
            $filter_producto_precio = new stdClass();
            $filter_producto_precio->PROD_Codigo = $id_insertar_productos;
            $filter_producto_precio->TIPCLIP_Codigo = $categoria_precios[$k];
    
    
            if ($k == 0 || $k == 1) {
              $filter_producto_precio->TIPCLIP_Codigo = $categoria_precios[0]->TIPCLIP_Codigo;
            }
    
            if ($k == 2 || $k == 3) {
              $filter_producto_precio->TIPCLIP_Codigo = $categoria_precios[1]->TIPCLIP_Codigo;
            }
    
            if ($k == 4 || $k == 5) {
              $filter_producto_precio->TIPCLIP_Codigo = $categoria_precios[2]->TIPCLIP_Codigo;
            }
    
            $filter_producto_precio->EESTABP_Codigo = $valor_establecimiento;
            $filter_producto_precio->MONED_Codigo = 1;
            $filter_producto_precio->PRODUNIP_Codigo = 1;
            $filter_producto_precio->PRODPREC_Precio = 0;
            $filter_producto_precio->PRODPREC_FlagEstado = 1;
            $insertar_producto_precio = $this->almacen_model->insertPorductoPrecio($filter_producto_precio);
    
            }else{
    
              foreach ($validar_productos_repetidos as $value) {
                  $array_datos = array(
                      "codigo_interno_producto" => $value->PROD_CodigoUsuario,
                      "descripcion_codigo" => $value->PROD_Nombre
                  );
              
                  // Agrega cada $array_datos al array $array
                  $array[] = $array_datos;
              }
              
            }
          }
          // Imprime el array completo después del bucle
          echo json_encode($array);
         
      }
      
    
      public function getFormatoCargaProductos(){
    
        $this->load->library('Excel');
        
        /** ESTILOS **/
        $estiloTitulo = array(
            'font' => array(
                'name'      => 'Calibri',
                'bold'      => true,
                'color'     => array(
                    'rgb' => '000000'
                ),
                'size' => 11
            ),
            'fill'  => array(
                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => 'ECF0F1')
            ),
            'alignment' =>  array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'          => TRUE
            )
        );
        
        $hoja = 0;
        /** HOJA 0 COTIZACIÓN **/
        $this->excel->setActiveSheetIndex($hoja);
        $this->excel->getActiveSheet()->setTitle("Formato de carga");
    
        $i = "A";
        $this->excel->getActiveSheet()->getColumnDimension($i)->setWidth("25");
        $this->excel->getActiveSheet()->getColumnDimension(++$i)->setWidth("40");
        $this->excel->getActiveSheet()->getColumnDimension(++$i)->setWidth("25");
        $this->excel->getActiveSheet()->getColumnDimension(++$i)->setWidth("25");
        $this->excel->getActiveSheet()->getColumnDimension(++$i)->setWidth("25");
        $this->excel->getActiveSheet()->getColumnDimension(++$i)->setWidth("25");
        $this->excel->getActiveSheet()->getColumnDimension(++$i)->setWidth("25");
    
        /** Columnas **/
        $lugar = 1;
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar",  "CÓDIGO DE PRODUCTO"); 
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("B$lugar",  "NOMBRE Ó DESCRIPCION");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("C$lugar",  "MODELO");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("D$lugar",  "MARCA");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("E$lugar",  "FAMILIA");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("F$lugar",  "FABRICANTE");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("G$lugar",  "PRECIO COSTO");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("H$lugar",  "STOCK MINIMO");
    
        $this->excel->getActiveSheet()->getStyle("A$lugar:H$lugar")->applyFromArray($estiloTitulo);
        $this->excel->getActiveSheet()->getRowDimension($lugar)->setRowHeight(30);
        $lugar++;
        
        /** Items **/
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar",  "CODE001");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("B$lugar",  "EL NOMBRE DEL PRODUCTO");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("C$lugar",  "EL MODELO");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("D$lugar",  "LA MARCA");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("E$lugar",  "FAMILIA");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("F$lugar",  "EL FABRICANTE");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("G$lugar",  "PRECIO COSTO");
        $this->excel->setActiveSheetIndex($hoja)->setCellValue("H$lugar",  "0");
    
        $filename = "Formato de carga.xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
      }
    
    
      public function formato_importacion_precio()
      {
            $this->load->library('Excel');
            $hoja = 0;
        
            ###########################################
            ######### ESTILOS
            ###########################################
            $estiloTitulo = array(
              'font' => array(
                'name'      => 'Calibri',
                'bold'      => true,
                'color'     => array(
                  'rgb' => '000000'
                ),
                'size' => 14
              ),
              'alignment' =>  array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'          => TRUE
              )
            );
        
            $estiloColumnasTitulo = array(
              'font' => array(
                  'name' => 'Calibri',
                  'bold' => true,
                  'color' => array(
                      'rgb' => '000000',
                  ),
                  'size' => 12,
              ),
              'fill' => array(
                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => '69c9966e'),
              ),
              'alignment' => array(
                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                  'wrap' => TRUE,
              ),
              'borders' => array(
                  'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN,
                      'color' => array('rgb' => '000000'),
                  ),
              ),
              'height' => 200, // Altura de la fila
              'numberformat' => array(
                  'code' => PHPExcel_Style_NumberFormat::FORMAT_TEXT,
              ),
            );
        
        
            $estiloColumnasTituloCabezera= array(
              'font' => array(
                'name'      => 'Calibri',
                'bold'      => true,
                'color'     => array(
                  'rgb' => '000000'
                ),
                'size' => 11
              ),
              'fill'  => array(
                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '97d6ed')
              ),
              'alignment' =>  array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'          => TRUE
              )
            );
        
        
            $estiloColumnasPar = array(
              'font' => array(
                'name'      => 'Calibri',
                'bold'      => false,
                'color'     => array(
                  'rgb' => '000000'
                )
              ),
              'fill'  => array(
                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => 'FFFFFFFF')
              ),
              'alignment' =>  array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'          => TRUE
              ),
              'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN,
                  'color' => array('rgb' => "000000")
                )
              )
            );
            $estiloColumnasTitulo2 = array(
              'font' => array(
                  'name' => 'Calibri',
                  'bold' => true,
                  'color' => array(
                      'rgb' => '000000'
                  ),
                  'size' => 13
              ),
              'fill'  => array(
                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFF00')  // Cambiado a amarillo
              ),
              'alignment' =>  array(
                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                  'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                  'wrap'       => TRUE
              ),
              'borders' => array(
                  'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN,
                      'color' => array('rgb' => '000000')
                  )
              )
          );
          
          
        
            $estiloColumnasImpar = array(
              'font' => array(
                'name'      => 'Calibri',
                'bold'      => false,
                'color'     => array(
                  'rgb' => '000000'
                )
              ),
              'fill'  => array(
                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => 'DCDCDCDC')
              ),
              'alignment' =>  array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'          => TRUE
              ),
              'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN,
                  'color' => array('rgb' => "000000")
                )
              )
            );
            $estiloBold = array(
              'font' => array(
                'name'      => 'Calibri',
                'bold'      => true,
                'color'     => array(
                  'rgb' => '000000'
                ),
                'size' => 11
              )
            );
            $estiloCenter = array(
              'alignment' =>  array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'          => TRUE
              )
            );
            $estiloRight = array(
              'alignment' =>  array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'          => TRUE
              )
            );
        
            # ROJO PARA ANULADOS
            $colorCelda = array(
              'font' => array(
                'name'      => 'Calibri',
                'bold'      => false,
                'color'     => array(
                  'rgb' => '000000'
                )
              ),
              'fill'  => array(
                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => "F28A8C")
              )
            );
    
            $estiloColumnasTitulo3 = array(
              'font' => array(
                  'name' => 'Calibri',
                  'bold' => true,
                  'color' => array(
                      'rgb' => '000000'
                  ),
                  'size' => 11
              ),
              'fill'  => array(
                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'C0C0C0')  // Amarillo
              ),
              'alignment' =>  array(
                  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                  'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                  'wrap'       => TRUE
              ),
              'borders' => array(
                  'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN,
                      'color' => array('rgb' => 'C0C0C0')  // Gris (puedes ajustar el tono de gris)
                  )
              )
          );
          
            $this->excel->setActiveSheetIndex($hoja);
            $lugar = 8;
            $lugar2 = 2;
            $columnas = array(
                          0 => "PROD_CodigoUsuario",
                          1 => "PROD_Nombre",
                          2 => "FAMI_Descripcion",
                          3 => "MARCC_Descripcion",
                          4 => "PROD_UltimoCosto",
                          5 => "UNDMED_Simbolo",
                          6 => ""
                            );
            $filter = new stdClass();
            $filter->start = $this->input->post("start");
            $filter->length = $this->input->post("length");
            $filter->search = $this->input->post("search")["value"];
            $ordenar = $this->input->post("order")[0]["column"];
            if ($ordenar != ""){
                $filter->order = $columnas[$ordenar];
                $filter->dir = $this->input->post("order")[0]["dir"];
            }
          $this->load->helper('array');
          $surcusal = $_SESSION['establec'];
          $resultado = $this->almacen_model->obtenerNombreSurcusal($surcusal);
          $primerResultado = $resultado[0];
          // Obtener resultados del modelo
          $temp = $this->almacen_model->obtener_compania($this->compania);
          $establCodigo = $temp[0]->EESTABP_Codigo;
          // var_dump($establCodigo);
          // exit;
          $result = $this->almacen_model->obtenerAlmacenn($establCodigo);
          $descripcion = $primerResultado->EESTABC_Descripcion;
          $this->excel->setActiveSheetIndex($hoja)->setCellValue("B2",$descripcion);
          $this->excel->setActiveSheetIndex($hoja)->setCellValue("A1", "EMPRESA");
          $this->excel->setActiveSheetIndex($hoja)->setCellValue("B1", "SURCUSAL");
          $this->excel->getActiveSheet()->getStyle("a1:b1")->applyFromArray($estiloColumnasTitulo2);
          $this->excel->getActiveSheet()->getStyle("a2:b2")->applyFromArray($estiloColumnasTitulo3);
    
          $i = 8; // Empieza desde la fila 2, ya que la primera fila es para los títulosss
          $this->excel->setActiveSheetIndex($hoja)->setCellValue("A2",$_SESSION['nombre_empresa']);
          // Crear un array para almacenar descripciones únicas
          $descripcionesUnicas = array();
    
          // Definir un array de letras
          $letras = range('A', 'Z');
    
          foreach ($result as $resultado) {
    
              $descripcion = $resultado->TIPCLIC_Descripcion;
    
              if (!in_array($descripcion, $descripcionesUnicas)) {
                  $indiceActual = count($descripcionesUnicas);
                  $letraInicial = $letras[$indiceActual];
                  $letraFinal = $letras[$indiceActual + 2];
                  $rango = $letraInicial . $lugar . ':' . $letraFinal . $lugar;
                  $this->excel->getActiveSheet()->getStyle($rango)->applyFromArray($estiloColumnasTitulo);
                  $descripcionesUnicas[] = $descripcion;
              }
          }
    
          $codigoUnitario = array();
          foreach($result as $resultado)
          {
            $codigo = $resultado->PROD_CodigoInterno;
            if(!in_array($codigo, $codigoUnitario))
            {
              $codigoUnitario[] = $codigo;
            }
          }
          $nombre_producto = array();
          foreach($result as $resultado)
          {
            $nombre = $resultado->PROD_Nombre;
            if(!in_array($nombre, $nombre_producto))
            {
              $nombre_producto[] = $nombre;
            }
          }
          foreach($descripcionesUnicas as $descripcionesUnica)
          { 
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("A8",  "NOMBRE DE PRODUCTO");
          
          }
    
          $letra='C';
          foreach($descripcionesUnicas as $descripcionesUnica)
          { 
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("B8",  "CODIGO DE PRODUCTO");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("$letra"."8", $descripcionesUnica);
            $letra++;
          }
          $fila = 9;
          $columna= "B";
          foreach($codigoUnitario as $codigoUnitarios)
          { 
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("$columna".$fila, $codigoUnitarios);
            // Aplicar estilo a las celdas con códigos unitarios
            $this->excel->getActiveSheet()->getStyle("$columna$fila")->applyFromArray($estiloColumnasTitulo3);
            $fila++;
          }
          $filas = 9;
          $columna= "A";
          foreach($nombre_producto as $codigoUnitarios)
          { 
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("$columna".$filas, $codigoUnitarios);
            // Aplicar estilo a las celdas con códigos unitarios
            $this->excel->getActiveSheet()->getStyle("$columna$filas")->applyFromArray($estiloColumnasTitulo3);
            $filas++;
          }
    
          $valores = array();
    
          // Iterar sobre los resultados y agregarlos a $valores
          foreach ($result as $resultado) {
            $codigo = $resultado->PROD_CodigoInterno;
            $descripcion = $resultado->TIPCLIC_Descripcion;
            $nombre = $resultado->PROD_Nombre;
            $precio = $resultado->PRODPREC_Precio;
            // Verificar si ya existe una entrada para este código y descripción
            if (!isset($valores[$codigo][$nombre][$descripcion])) {
                // Si no existe, crear una nueva entrada
                $valores[$nombre][$codigo][$descripcion] = $precio;
            }
          }
          $fila = 9;
          $letra="C";
          foreach ($valores as $nombreProducto => $productos) {
            foreach ($productos as $codigoProducto => $categorias) {
              // Mostrar los precios para cada categoría en columnas sucesivas
              foreach ($categorias as $descripcion => $precio) {
                  $this->excel->setActiveSheetIndex($hoja)->setCellValue("$letra$fila", $precio);
                  $letra++;
              }
              $fila++;
              $letra = 'C';  
            }
          }
          $this->excel->getActiveSheet()->getColumnDimension("A")->setWidth("40");
          $this->excel->getActiveSheet()->getColumnDimension("B")->setWidth("25");
          $this->excel->getActiveSheet()->getColumnDimension("C")->setWidth("25");
          $this->excel->getActiveSheet()->getColumnDimension("D")->setWidth("25");
          $this->excel->getActiveSheet()->getColumnDimension("E")->setWidth("25");
          $this->excel->getActiveSheet()->getColumnDimension("F")->setWidth("25");
          $this->excel->getActiveSheet()->getColumnDimension("G")->setWidth("25");
          $this->excel->getActiveSheet()->getColumnDimension("H")->setWidth("25");
          $filename = "FORMATO_ARTICULO_PRECIO_".date("YmdHis").".xls";
          header("Content-Type: application/vnd.ms-excel");
          header("Content-Disposition: attachment;filename=$filename");
          header("Cache-Control: max-age=0");
          $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
          $objWriter->save('php://output');
        }

    

    
}

?>
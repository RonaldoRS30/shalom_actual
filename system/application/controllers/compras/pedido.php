<?php
class Pedido extends Controller{
    public function __construct()
    {
        parent::Controller();
        $this->load->model('almacen/producto_model');
        $this->load->model('almacen/unidadmedida_model');
        $this->load->model('almacen/guiatrans_model');
        $this->load->model('almacen/guiatransdetalle_model');
        $this->load->model('ventas/comprobante_formapago_model');

        $this->load->model('almacen/almacen_model');
        $this->load->model('almacen/guiain_model');
        $this->load->model('almacen/guiaindetalle_model');
        $this->load->model('compras/cotizacion_model');
        $this->load->model('compras/ocompra_model');
        $this->load->model('compras/proveedor_model');
        $this->load->model('compras/pedido_model');
        $this->load->model('compras/pedidodetalle_model');
        $this->load->model('compras/guiarempedido_model');
        $this->load->model('compras/comprobantepedido_model');
        $this->load->model('compras/presupuesto_model');

        $this->load->model('maestros/persona_model');
        $this->load->model('maestros/documento_model');
        $this->load->model('maestros/empresa_model');
        $this->load->model('maestros/proyecto_model');
        $this->load->model('maestros/emprcontacto_model');
        $this->load->model('maestros/formapago_model');
        $this->load->model('maestros/condicionentrega_model');
        $this->load->model('maestros/centrocosto_model');
        $this->load->model('maestros/moneda_model');
        $this->load->model('maestros/directivo_model');
        $this->load->model('maestros/configuracion_model');
        $this->load->model('tesoreria/cuota_model');

        
        $this->load->model('seguridad/usuario_model');
        $this->load->model('ventas/cliente_model');
        $this->load->model('ventas/comprobante_model');
        $this->load->model('ventas/importacion_model');
        
        $this->load->helper('json');
        $this->load->helper('form');
        $this->load->helper('utf_helper');
        $this->load->helper('my_permiso');
        $this->load->helper('my_almacen');

        $this->load->library('html');
        $this->load->library('table');
        $this->load->library('layout','layout');
        $this->load->library('pagination');
        $this->load->library('lib_props');
        $this->somevar['compania'] = $this->session->userdata('compania');
        $this->somevar['usuario']= $this->session->userdata('user');
        $this->somevar['user'] = $this->session->userdata('user');
        $this->somevar['rol'] = $this->session->userdata('rol');
        $this->somevar['url'] = $_SERVER['REQUEST_URI'];
        $this->somevar['hoy']       = mdate("%Y-%m-%d",time());
        $this->base_url = base_url();
        
        date_default_timezone_set("America/Lima");

    }

    public function index(){
        $this->layout->view('seguridad/inicio');    
    }

    public function pedidos( $j = 0 ){
        $filter = new stdClass();
        if (count($_POST) > 0) {
            $filter->fechai = $this->input->post('fechai');
            $filter->fechaf = $this->input->post('fechaf');
            $filter->numero = $this->input->post('txtNumDoc');
            $filter->cliente = $this->input->post('cliente');
            $filter->ruc_cliente = $this->input->post('ruc_cliente');
            $filter->nombre_cliente = $this->input->post('nombre_cliente');
        
        } else {
            $filter->fechai = "";
            $filter->fechaf = "";
            $filter->numero = "";
            $filter->cliente = "";
            $filter->ruc_cliente = "";
            $filter->nombre_cliente = "";
        }
        $data['numdoc'] = $filter->numero;
        $data['fechai'] = $filter->fechai;
        $data['fechaf'] = $filter->fechaf;
        $data['cliente'] = $filter->cliente;
        $data['ruc_cliente'] = $filter->ruc_cliente;
        $data['nombre_cliente'] = $filter->nombre_cliente;
        $data['afectaciones'] = $this->producto_model->tipo_afectacion();
        $data['compania'] = $this->somevar['compania'];
        $data['hoy'] = date('Y-m-d');
        $data['hoyhora'] = date('Y-m-d')."T".date('H:i');
        
        $data['titulo_tabla']    = "RELACIÓN DE PEDIDOS / REQUERIMIENTOS";
        $data['registros']  = count($this->pedido_model->listar_pedidos_todos($filter));
        $data['action'] = base_url()."index.php/compras/pedido/pedidos";
        $conf['base_url']   = site_url('maestros/compras/pedidos/');
        $conf['total_rows'] = $data['registros'];
        $conf['per_page']   = 50;
        $conf['num_links']  = 3;
        $conf['next_link'] = "&gt;";
        $conf['prev_link'] = "&lt;";
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link']  = "&gt;&gt;";
        $conf['uri_segment'] = 4;
        $this->pagination->initialize($conf);
        $data['paginacion'] = $this->pagination->create_links();
        
         
        $this->layout->view("compras/pedido_index",$data);
    }

    public function datatable_pedido(){
        $filter = new stdClass();
        $filter->start = $this->input->post("start");
        $filter->length = $this->input->post("length");
        $filter->search = $this->input->post("search")["value"];

        $columnas = array(
            0 => "PEDIC_FechaRegistro",
            1 => "PEDIC_Numero",
            2 => "documento",
            3 => "nombre",
            4 => "PEDIC_PrecioTotal",
            5 => "PEDIC_FlagEstado",
        );

        $ordenar = $this->input->post("order")[0]["column"];
        if ($ordenar != ""){
            $filter->order = $columnas[$ordenar];
            $filter->dir = $this->input->post("order")[0]["dir"];
        }

        $filter->fechai         = $this->input->post('fechai');
        $filter->fechaf         = $this->input->post('fechaf');
        $filter->numero         = $this->input->post('txtNumDoc');
        $filter->cliente        = $this->input->post('cliente');
        $filter->ruc_cliente    = $this->input->post('ruc_cliente');
        $filter->nombre_cliente = $this->input->post('nombre_cliente');     
        $filter->estado         = $this->input->post('estado');   
        $item = ($this->input->post("start") != "") ? $this->input->post("start") : 0;

        $listado_pedidosV = $this->pedido_model->listar_pedidos_todos($filter);
        $lista = array();
        if(count($listado_pedidosV)>0){
            foreach($listado_pedidosV as $indice=>$valor){
                $tipodocu = $valor->PEDIC_TipoDocume;
                $codigo   = $valor->PEDIP_Codigo;
                $numero  =  $this->getOrderNumero($valor->PEDIC_Numero);
                $serie  =  $valor->PEDIC_Serie;
                $docu = $valor->documento;
                $nombre = $valor->nombre;
                $arrfecha = explode(" ", $valor->PEDIC_FechaRegistro);
                $fecha = $this->lib_props->formatHours($arrfecha[1]) . " del " . mysql_to_human($arrfecha[0]);
                $total = $valor->PEDIC_PrecioTotal;
                $estadoPedido = $valor->PEDIC_FlagEstado;

                $guiarem_codigo = $valor->GUIAREMP_Codigo;
                $guiarem_relacionada = $valor->GUIAREMC_SerieNumero;
                $guiarem_estado = $valor->GUIAREMC_FlagEstado;

                $listaGuiaremAsociados  = $this->comprobante_model->buscarComprobanteGuiarem($valor->CPP_Codigo,"");

                $comprobante = $valor->CPP_Codigo;
                $comprobante_serieNumero = $valor->CPC_SerieNumero;
                $comprobante_estado = $valor->CPC_FlagEstado;

                if ($comprobante_serieNumero != NULL){
                $formatNumber = explode("-",$comprobante_serieNumero);
                $comprobante_serieNumero = $formatNumber[0]."-".$this->lib_props->getOrderNumeroSerie($formatNumber[1]);
                }


                // var_dump($guiarem_estado);
                // die();



                switch ($valor->PEDIC_FlagEstado) {
                    case '1':
                        $estado = "<span id='ped".$codigo."' style='background:green; color:white; font-weight:bold; font-size:6.5pt; text-align:center; display:block; width:10em; padding:0.3em;'>ENTREGADO</span>";
                        break;
                    case '2':
                        $estado = "<span id='ped".$codigo."' style='background:orange; color:white; font-weight:bold; font-size:6.5pt; text-align:center; display:block; width:10em; padding:0.3em;'>EN PROCESO</span>";
                        break;
                    case '3':
                        $estado = "<span id='ped".$codigo."' style='background:red; color:white; font-weight:bold; font-size:6.5pt; text-align:center; display:block; width:10em; padding:0.3em;'>ANULADO</span>";
                        break;
                    
                    default:
                        $estado = "<span id='ped".$codigo."' style='background:blue; color:white; font-weight:bold; font-size:6.5pt; text-align:center; display:block; width:10em; padding:0.3em;'>EN ESPERA</span>";
                        break;
                }

                if($estadoPedido == 2){
                    $estado = "<a href='javascript:;' onclick='terminar_pedido($estadoPedido, $codigo, $item)'>$estado</a>";
                }else if($estadoPedido == 0){
                    $estado = "<a href='javascript:;' onclick='aprobar_pedido($estadoPedido, $codigo)'>$estado</a>";
                }
                                
                $comppName = $this->pedido_model->nameEstablecimiento($valor->COMPP_Codigo);
                $compp = $comppName[0]->EESTABC_Descripcion;

                if ( $guiarem_codigo != NULL ){
                    $guiarem_relacionada = "<a href='".base_url()."index.php/almacen/guiarem/guiarem_ver_pdf/$guiarem_codigo/a4/1' data-fancybox data-type='iframe'> <span style='font-weight: bold; font-size: 7pt; color:green'>$guiarem_relacionada</span> </a>";
      
                    if ($guiarem_estado == "2")
                        $guiarem_relacionada .= "<a href='".base_url()."index.php/almacen/guiarem/editar/$guiarem_codigo/$docu'><img src='" . base_url() . "images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
                }else{
                        $guiarem_relacionada = "";
                        $guiarem_codigo = 0;
                    if($listaGuiaremAsociados != NULL){
                        $guiarem_relacionada = "<a href='".base_url()."index.php/almacen/guiarem/guiarem_ver_pdf/".$listaGuiaremAsociados[0]->GUIAREMP_Codigo."/a4/1' data-fancybox data-type='iframe'> <span style='font-weight: bold; font-size: 7pt; color:green'>".$listaGuiaremAsociados[0]->GUIAREMC_Serie."-".$listaGuiaremAsociados[0]->GUIAREMC_Numero."</span> </a>";

                        if($listaGuiaremAsociados[0]->GUIAREMC_FlagEstado == 2){
                            $guiarem_relacionada .= "<a href='".base_url()."index.php/almacen/guiarem/editar/".$listaGuiaremAsociados[0]->GUIAREMP_Codigo."/$docu'><img src='" . base_url() . "images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
                        }
                    }else{
                        $guiarem_relacionada = "";
                        $guiarem_codigo = 0;
                    }
                }

                if ($comprobante != NULL) {
                    $comprobante_serieNumero = "<a href='".base_url()."index.php/ventas/comprobante/comprobante_ver_pdf/$comprobante/a4' data-fancybox data-type='iframe'> <span style='font-weight: bold; font-size: 7pt; color:green'>$comprobante_serieNumero</span> </a>";

                    // if ($comprobante_estado == "2")
                    //     $comprobante_serieNumero .= "<a href='".base_url()."index.php/ventas/comprobante/disparador/$tipodocu/$comprobante'><img src='" . base_url() . "images/active.png' width='16' height='16' border='0'></a>";
                }else{
                    $comprobante_serieNumero = "";
                    $comprobante = 0;
                }


                $eliminar = ""; #"<a href='javascript:;' onclick='eliminar_pedido(".$codigo.")'><img src='".base_url()."images/eliminar.png' width='16' height='16' border='0' title='Eliminar'></a>";
                
                $ver2 = "<a href='javascript:;' class='btn2 btn-default' onclick='pedido_pdf($codigo,1)'  target='_parent'><img src='" . base_url() . "images/pdf.png' width='16' height='16' border='0' title='Ver PDF'></a>";
                
                $ver = "<a href='javascript:;' onclick='pedido_pdf($codigo,0)'  target='_parent'><img src='" . base_url() . "images/imprimir.png' width='16' height='16' border='0' title='Ver PDF'></a>";
                #$ver = "<a href='javascript:;' onclick='comprobante_ver_pdf_conmenbrete(" . $codigo .",".$ConversorDeNumero.",".$imp.",".$tipo_oper2.")'  target='_parent'><img src='" . base_url() . "images/pdf.png' width='16' height='16' border='0' title='Ver PDF'></a>";
                $btn_options = "<button type='button' id='btnPadreCanje$codigo' class='btn2 btn-default btn-padre' onclick='btnHijo(\"btnPadreCanje$codigo\", $codigo, \"oc\", $item, $estadoPedido, $guiarem_codigo, $comprobante);'>
                            <img src='".$this->base_url."images/icono-documentos.png' class='image-size-1l' title='Más opciones'>
                            <ul class='btn-hijo'></ul>
                        </button>";
                $lista[] = array(
                                    $fecha,          
                                    $serie."-".$numero,  
                                    $docu,
                                    $nombre,   
                                    $total,
                                    "<div align='left' class='gResult_$item'>$guiarem_relacionada</div> <div align='left'><span class='icon-loading loading_g_$item'></span></div>",
                                    "<div align='left' class='cResult_$item'>$comprobante_serieNumero</div> <div align='left'><span class='icon-loading loading_c_$item'></span></div>",
                                    $estado, 
                                    $btn_options
                                );
                $item++;
            }
        }

        unset($filter->start);
        unset($filter->length);

        $filterAll = new stdClass();

        $filterAll->count = true;
        $filter->count = true;

        $listado_pedidosT = $this->pedido_model->listar_pedidos_todos();
        $listado_pedidos = $this->pedido_model->listar_pedidos_todos($filter);

        $json = array(
                            "draw"            => intval( $this->input->post('draw') ),
                            "recordsTotal"    => count($listado_pedidosT),
                            "recordsFiltered" => count($listado_pedidos),
                            "data"            => $lista
                    );

        echo json_encode($json);
    }

    public function nuevo_pedido( $tipo_oper = 'V' ){
        $tipo_oper = "V"; #$this->uri->segment(4);
        $tipo_docu = "P"; #$this->uri->segment(5);
        $tipoDocumento = 'PEDIDO'; #strtoupper($this->obtener_tipo_documento($tipo_docu));
        /* :::: SE CREA LA SESSION :::*/
        $hoy = date('Y-m-d H:i:s');
        $cadena = strtotime($hoy).substr((string)microtime(), 1, 8);
        $tempSession = str_replace('.','',$cadena);
        $data['tempSession']  = $tempSession;
        /* :::::::::::::::::::::::::::*/
        if($tipoDocumento == ''){
            redirect(base_url().'index.php/index/inicio');
        }else {
            
            // Variables
            $compania = $this->somevar['compania'];
            $codigo = "";
            unset($_SESSION['serie']);
            /*PARA CUOTAS*/
            /* // CUOTAS*/

            $comp_confi = $this->companiaconfiguracion_model->obtener($compania);
            
            $data['compania'] = $compania;
            //Para cambio comprobante_A
            $data['cambio_comp'] = "0";
            $data['total_det'] = "0";   
            $data['codigo'] = $codigo;

            $data['cboObra'] = form_dropdown("obra", array('' => ':: Seleccione ::'), "", " class='comboGrande'  id='obra'");

            $data['contiene_igv'] = (($comp_confi[0]->COMPCONFIC_PrecioContieneIgv == '1') ? true : false);
            $oculto = form_hidden(array('codigo' => $codigo, 'base_url' => base_url(), 'tipo_oper' => $tipo_oper, 'tipo_docu' => $tipo_docu, 'contiene_igv' => ($data['contiene_igv'] == true ? '1' : '0')));
            $data['url_action'] = base_url() . "index.php/compras/pedido/insertar_pedido";
            $data['titulo'] = "REGISTRAR " . $tipoDocumento;
            $data['tit_imp'] = $tipoDocumento;
            $data['tipo_docu'] = $tipo_docu;
            $data['tipo_oper'] = $tipo_oper;
            $data['formulario'] = "frmPedido";
            $data['afectaciones'] = $this->producto_model->tipo_afectacion();
            $data['oculto'] = $oculto;
            $data["modo"] = "insertar";
            $data['usa_adelanto'] = 0;
            $data["categorias_cliente"] = $this->tipocliente_model->listar2();
            $lista_almacen = $this->almacen_model->seleccionar();
            $data['guia'] = "";
            $data['cboproyecto'] =$this->OPTION_generador($this->proyecto_model->listar_proyectos(), 'PROYP_Codigo', 'PROYC_Nombre', '1');
            $data['cboimportacion'] =$this->OPTION_generador($this->importacion_model->listar_importacion(0), 'IMPOR_Codigo', 'IMPOR_Nombre', '2');
            $data['cboAlmacen'] = form_dropdown("almacen", $lista_almacen, obtener_val_x_defecto($lista_almacen), " class='comboMedio' style='width:auto;' id='almacen'");
            $data['cboMoneda'] = $this->OPTION_generador($this->moneda_model->listar(), 'MONED_Codigo', 'MONED_Descripcion', '1');
            $data['cboFormaPago'] = $this->OPTION_generador($this->formapago_model->listar(), 'FORPAP_Codigo', 'FORPAC_Descripcion', '23');
            $data['cboFormaPagos']   = $this->OPTION_generador($this->formapago_model->listar(), 'FORPAP_Codigo', 'FORPAC_Descripcion', '23');
            $data['cboFormaPagosmulti'] = $this->OPTION_generador($this->formapago_model->listarmulti(), 'FORPAP_Codigo', 'FORPAC_Descripcion', '23');
            $data['cboPresupuesto'] = $this->OPTION_generador($this->presupuesto_model->listar_presupuestos_nocomprobante_cualquiera($tipo_oper, $tipo_docu), 'PRESUP_Codigo', array('PRESUC_Numero', 'nombre'), '', array('', '::Seleccione::'), ' / ');
            #$data['cboOrdencompra'] = $this->OPTION_generador($this->ocompra_model->listar_ocompras_nocomprobante($tipo_oper), 'OCOMP_Codigo', array('OCOMC_Numero', 'nombre'), '', array('', '::Seleccione::'), ' - ');
            $data['cboGuiaRemision'] = $this->OPTION_generador($this->guiarem_model->listar_guiarem_nocomprobante($tipo_oper), 'GUIAREMP_Codigo', array('codigo', 'nombre'), '', array('', '::Seleccione::'), ' / ');
            $data['cboVendedor'] = $this->OPTION_generador($this->directivo_model->listar_directivo_personal(), 'DIREP_Codigo', array('PERSC_ApellidoPaterno', 'PERSC_ApellidoMaterno', 'PERSC_Nombre'), '', array('', '::Seleccione::'), ' ');
            $data['direccionsuc'] = form_input(array("name" => "direccionsuc", "id" => "direccionsuc", "class" => "cajaGeneral", "size" => "40", "maxlength" => "250", "value" => $punto_llegada));
            
            $cambio_dia = $this->tipocambio_model->obtener_tdc_dolar(date('Y-m-d'));

            if (count($cambio_dia) > 0) {
                $data['tdcDolar'] = $cambio_dia[0]->TIPCAMC_FactorConversion;
            } else {
                $data['tdcDolar'] = '';
            }   
            $cofiguracion_datos = $this->configuracion_model->obtener_numero_documento($compania, $tipo_oper = 22);
            $cofiguracion_datos[0]->CONFIC_Serie;
            $cofiguracion_datos[0]->CONFIC_Numero;

            $data['serie'] = $cofiguracion_datos[0]->CONFIC_Serie;
            $data['serie_suger_oc'] = $cofiguracion_datos[0]->CONFIC_Serie;
            $data['numero_suger_oc'] = $cofiguracion_datos[0]->CONFIC_Numero + 1;
            
            $data['serie'] = '';
            $data['numero'] = '';

            $data['cliente'] = "";
            $data['ruc_cliente'] = "";
            $data['nombre_cliente'] = "";
            $data['proveedor'] = "";
            $data['ruc_proveedor'] = "";
            $data['nombre_proveedor'] = "";
            $data['detalle_comprobante'] = array();
            $data['observacion'] = "";
            $data['focus'] = "";
            $data['pedido'] = "";
            $data['descuento'] = "0";
            $data['igv'] = $comp_confi[0]->COMPCONFIC_Igv;
            $data['igv_default'] = $comp_confi[0]->COMPCONFIC_Igv;
            $data['hidden'] = "";
            $data['preciototal'] = "";
            $data['descuentotal'] = "";
            $data['igvtotal'] = "";
            $data['importetotal'] = "";
            $data['preciototal_conigv'] = "";
            $data['descuentotal_conigv'] = "";
            $data['hidden'] = "";
            $data['observacion'] = "";
            $data['ordencompra'] = "";
            $data['presupuesto_codigo'] ="";
            $data['dRef'] = "";
            $data['guiarem_codigo'] = "";
            $data['docurefe_codigo'] = "";
            $data['estado'] = "2";
            $data['numeroAutomatico'] = 1;
            $data['isProvieneCanje'] =false;
            $data['oc_cliente'] = "";
            $data['afectaciones'] = $this->producto_model->tipo_afectacion();

            $data['idOcompra'] = "";
            
            $data['modo_impresion'] = "1";
            if ($tipo_docu != 'B') {
                if (FORMATO_IMPRESION == 1)
                    $data['modo_impresion'] = "2";
                else
                    $data['modo_impresion'] = "1";
            }
            $data['hoy'] = mysql_to_human(mdate("%Y-%m-%d ", time()));
            $atributos = array('width' => 700, 'height' => 450, 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0');
            $contenido = "<img height='16' width='16' src='" . base_url() . "images/ver.png' title='Buscar Cliente' border='0'>";
            $data['vercliente'] = anchor_popup('ventas/cliente/ventana_busqueda_cliente', $contenido, $atributos, 'linkVerCliente');
            $data['verproveedor'] = anchor_popup('compras/proveedor/ventana_busqueda_proveedor', $contenido, $atributos, 'linkVerProveedor');
            $data['verproducto'] = anchor_popup('almacen/producto/ventana_busqueda_producto', $contenido, $atributos, 'linkVerProducto');
            //obtengo las series de la configuracion
            
            if ($tipo_docu == 'P') {
                $tipo = 1;
            }
            
            
            /**gcbq limpiamos la session de series guardadas**/
            unset($_SESSION['serie']);
            unset($_SESSION['serieReal']);
            unset($_SESSION['serieRealBD']);
            /**fin de limpiar session***/
            //$hoy = date('Y-m-d H:m:s');
            //var_dump(microtime());
            $listaGuiarem=array();
            $listaGuiarem=null;
            $data['listaGuiaremAsociados']=$listaGuiarem;

            $cofiguracion_datos = $this->configuracion_model->obtener_numero_documento($compania, $tipo);
            //$ultimo_serie_numero = $this->comprobante_model->ultimo_serie_numero($tipo_oper, 'B');
            $data['serie_suger_b'] = $cofiguracion_datos[0]->CONFIC_Serie;
            $data['numero_suger_b'] =$this->getOrderNumeroSerie($cofiguracion_datos[0]->CONFIC_Numero + 1);
            //$ultimo_serie_numero = $this->comprobante_model->ultimo_serie_numero($tipo_oper, 'F');
            $data['serie_suger_f'] = $cofiguracion_datos[0]->CONFIC_Serie;
            $data['numero_suger_f'] = $this->getOrderNumeroSerie($cofiguracion_datos[0]->CONFIC_Numero + 1);
            $data['cmbVendedor'] = $this->select_cmbVendedor($this->session->set_userdata('codUsuario'));
            $this->layout->view('compras/pedido_nuevo', $data);
        }
    }
    public function pedidos_diarios(){
        $hola = $this->pedido_model->pedidos_diarios();
        
    }
    public function insertar_guiarem(){
        $this->load->model("maestros/compania_model");
        $this->load->model('maestros/emprestablecimiento_model');
    
        $datos_ocompra = NULL;
        $error = false;
        $codigoP = $this->input->post("idOC");
    
        if ($codigoP == ""){
            $message = "Documento de origen no definido.";
            $error = true;
        }
        else{
            $datos_pedido = $this->pedido_model->obtener_pedido($codigoP);
            $listaGuiaremAsociados  = $this->comprobante_model->buscarComprobanteGuiarem($datos_pedido[0]->CPP_Codigo,"");
            $compania = $datos_pedido[0]->COMPP_Codigo;
            
            if ($datos_pedido[0]->GUIAREMP_Codigo != NULL){
                $guia_ant = $datos_pedido[0]->GUIAREMP_Codigo;
                $sernum_ant = $datos_pedido[0]->GUIAREMC_SerieNumero;
                $message = "Una guia fue asociada anteriormente: ".$datos_pedido[0]->GUIAREMC_SerieNumero;
                $error = true;
            }
            
            if($listaGuiaremAsociados != NULL){
                $guia_ant = $listaGuiaremAsociados[0]->GUIAREMP_Codigo;
                $sernum_ant = $listaGuiaremAsociados[0]->GUIAREMC_Serie."-".$listaGuiaremAsociados[0]->GUIAREMC_Numero;
                $message = "Una guia fue asociada anteriormente: ".$sernum_ant;
                $error = true;
            }
        }
        if($datos_pedido[0]->PEDIC_FlagEstado == 3){
            $message = "Pedido Anulado";
            $error = true;
        }
    
        if ($datos_pedido == NULL){
            $message = "Documento origen no encontrado.";
            $error = true;
        }

        if ($error == false){
            ## DATOS DE LA EMPRESA EMISORA
              $companiaInfo = $this->compania_model->obtener($compania);
              $establecimientoInfo = $this->emprestablecimiento_model->listar( $companiaInfo[0]->EMPRP_Codigo, '', $companiaInfo[0]->COMPP_Codigo );
              $empresaInfo =  $this->empresa_model->obtener_datosEmpresa( $establecimientoInfo[0]->EMPRP_Codigo );
    
              $ubigeo_origen = $establecimientoInfo[0]->UBIGP_Codigo;
              $direccion_origen = $establecimientoInfo[0]->EESTAC_Direccion;
              
              $configuracion_datos = $this->configuracion_model->obtener_numero_documento($compania, 10);
              $serie = ($configuracion_datos[0]->CONFIC_Serie == NULL || $configuracion_datos[0]->CONFIC_Serie == "") ? 1 : $configuracion_datos[0]->CONFIC_Serie;
              $numero = $this->lib_props->getNumberFormat($configuracion_datos[0]->CONFIC_Numero + 1, 6);
            
            ## PEDIDOS DETALLE GENERAL
              $tipo_oper = $datos_pedido[0]->PEDIC_TipoDocume;
              $serieOC = $datos_pedido[0]->PEDIC_Serie;
              $numeroOC = $datos_pedido[0]->PEDIC_Numero;
              $cliente = $datos_pedido[0]->CLIP_Codigo;
              $moneda = $datos_pedido[0]->MONED_Codigo;
              $fecha = $datos_pedido[0]->PEDIC_FechaRegistro;
              $fecha = ( $datos_pedido[0]->OCOMC_FechaEntrega != NULL && $datos_pedido[0]->OCOMC_FechaEntrega != "" ) ? $datos_pedido[0]->OCOMC_FechaEntrega : $fecha;
              $almacen = $datos_pedido[0]->ALMAP_Codigo;
              //$direccion = $datos_pedido[0]->OCOMC_EnvioDireccion;
              //$observacion = $datos_pedido[0]->OCOMC_Observacion;
              # Numero de orden de compra cliente
              //$OCcliente = $datos_pedido[0]->OCOMC_PersonaAutorizada;
              # $tipo_movimiento por defecto es venta (1)
              $tipo_movimiento = 1;
              $otro_motivo = NULL;
              
            ## TOTALES
              $igv = $datos_pedido[0]->PEDIC_IGVTotal;
              $igv100 = $datos_pedido[0]->PEDIC_IGV;
              $descuento = $datos_pedido[0]->PEDIC_DescuentoTotal;
              if($descuento = ""){
                $descuento = 0;
              }
              $descuento100 = $datos_pedido[0]->PEDIC_Descuento100;
              $subtotal = ($datos_pedido[0]->PEDIC_PrecioTotal - $datos_pedido[0]->PEDIC_IGVTotal);
              $total = $datos_pedido[0]->PEDIC_PrecioTotal;
    
            ## A insertar en la guia R.
              $filter = new stdClass();
              $filter->GUIAREMC_TipoOperacion = $tipo_oper;
              $filter->GUIAREMC_Serie = $serie;
              $filter->GUIAREMC_Numero = $numero;
              $filter->MONED_Codigo = $moneda;
    
              
              $filter->PRESUP_Codigo = NULL;
    
              $filter->TIPOMOVP_Codigo = $tipo_movimiento;
              $filter->GUIAREMC_OtroMotivo = $otro_motivo;
              
              $filter->GUIAREMC_CodigoUsuario = NULL;
              $filter->USUA_Codigo = $this->somevar['usuario'];
    
              $filter->COMPP_Codigo = $compania;
              # OC cliente guardado en $filter->DOCUP_Codigo
    
              $filter->GUIAREMC_PersReceNombre = "-";
              $filter->GUIAREMC_PersReceDNI = "-";
              $filter->GUIAREMC_NumeroRef = "";
              $filter->GUIAREMC_OCompra = "$serieOC-$numeroOC";
              $filter->GUIAREMC_FechaTraslado = $fecha;
              
              $filter->GUIAREMC_Fecha = date("Y-m-d");
              $filter->EMPRP_Codigo = NULL;
    
            ## CLIENTE Y DIRECCIONES
                  $filter->CLIP_Codigo = $cliente;
                  $datos_cliente = $this->cliente_model->obtener($cliente);
    
                  $nombre_cliente = $datos_cliente->nombre;
                  $ruc_cliente = $datos_cliente->ruc;
                  $dni_cliente = $datos_cliente->dni;
                  $ruc_cliente = ( $ruc_cliente == "" ) ? $dni_cliente : $ruc_cliente ;
                  $email   = $datos_cliente->correo;
                  $direccion_destino = (trim($direccion) == "") ? $datos_cliente->direccion : trim($direccion);
                  $ubigeo_destino = $datos_cliente->ubigeo;
              
                
                $filter->GUIAREMC_UbigeoPartida = $ubigeo_origen;
                $filter->GUIAREMC_PuntoPartida = strtoupper($direccion_origen);
                #$ubigeo_destino;
                $filter->GUIAREMC_UbigeoLlegada = 0;
                $filter->GUIAREMC_PuntoLlegada = strtoupper($direccion_destino);
    
            ## TRANSPORTE
              $filter->EMPRP_Codigo = 1;
              $filter->GUIAREMC_Marca = "-";
              $filter->GUIAREMC_Placa = "-";
              $filter->GUIAREMC_RegistroMTC = "";
              $filter->GUIAREMC_Certificado = "";
              $filter->GUIAREMC_Licencia = "";
              $filter->GUIAREMC_PersReceDNI = "00000000";
              $filter->GUIAREMC_NombreConductor = "-";
              
              $filter->GUIAREMC_descuento100 = $descuento100;
              $filter->GUIAREMC_igv100 = $igv100;
              $filter->GUIAREMC_subtotal = $subtotal;
              $filter->GUIAREMC_descuento = $descuento;
              $filter->GUIAREMC_igv = $igv;
              $filter->GUIAREMC_total = $total;
              $filter->GUIAREMC_FlagEstado = "2";

              $guiarem_id = $this->guiarem_model->insertar($filter);
              $guiaped_id = $this->guiarempedido_model->insertar($codigoP,$guiarem_id,$serie,$numero);
              
            ## ACTUALIZA EL CORRELATIVO DE GUIAS
            if ($tipo_oper == 'V')
                $this->configuracion_model->modificar_configuracion($compania, 10, $numero);
    
            ## ARTICULOS
            $detalle = $this->pedido_model->obtener_detalle_pedido($codigoP);
            $detalle_ocompra = array();
            if ($detalle != NULL) {
              foreach ($detalle as $indice => $valor) {
                $filterGuia = new stdClass();
                $filterGuia->GUIAREMP_Codigo = $guiarem_id;
                $filterGuia->PRODCTOP_Codigo = $valor->PROD_Codigo;
                $filterGuia->UNDMED_Codigo = $valor->UNDMED_Codigo;
                //$filterGuia->LOTP_Codigo = $valor->LOTP_Codigo;
                $filterGuia->AFECT_Codigo = $valor->AFECT_Codigo;
                $filterGuia->GUIAREMDETC_Cantidad = $valor->PEDIDETC_Cantidad;
                $filterGuia->GUIAREMDETC_Pu = $valor->PEDIDETC_PSIGV;
                $filterGuia->GUIAREMDETC_Subtotal = $valor->PEDIDETC_Precio;
                $filterGuia->GUIAREMDETC_Descuento = $valor->PEDIDETC_Descuento;
                $filterGuia->GUIAREMDETC_Igv = $valor->PEDIDETC_IGV;
                $filterGuia->GUIAREMDETC_Total = $valor->PEDIDETC_Importe;
                $filterGuia->GUIAREMDETC_Pu_ConIgv = $valor->PEDIDETC_PCIGV;
                $filterGuia->GUIAREMDETC_Descuento100 = $valor->PEDIDETC_Descuento100;
                $filterGuia->GUIAREMDETC_Igv100 = 18;
                $filterGuia->GUIAREMDETC_Costo = 0;
                
                $filterGuia->GUIAREMDETC_Venta = NULL;
                $filterGuia->GUIAREMDETC_ITEM = $indice + 1;
                $filterGuia->GUIAREMDETC_Peso = $valor->PEDIP_Peso;
    
                $filterGuia->GUIAREMDETC_GenInd = "";
                $filterGuia->GUIAREMDETC_Descripcion = $valor->PROD_Nombre;
                $filterGuia->GUIAREMDETC_Observacion = " ";
                $filterGuia->ALMAP_Codigo = $almacen;
                $this->guiaremdetalle_model->insertar($filterGuia);
              }
            }
    
              $json = array(
                            "result" => "success",
                            "message" => "Operacion exitosa.",
                            "guia" => $guiarem_id,
                            "sernum" => $serie."-".$this->lib_props->getOrderNumeroSerie($numero)
                          );
          }
          else{
                $json = array(
                            "result" => "error",
                            "message" => $message,
                            "guia" => (isset($guia_ant)) ? $guia_ant : "",
                            "sernum" => (isset($sernum_ant)) ? $sernum_ant : ""
                          );
          }
    
        echo json_encode($json);
      }

    public function insertar_guiarem_multiple(){
        $this->load->model("maestros/compania_model");
        $this->load->model('maestros/emprestablecimiento_model');
    
        $datos_ocompra = NULL;
        $error = false;
        $codigoP = $this->input->post("idOC");
    
        if ($codigoP == ""){
            $message = "Documento de origen no definido.";
            $error = true;
        }
        else{
            $datos_pedido = $this->pedido_model->obtener_pedido($codigoP);
            $compania = $datos_pedido[0]->COMPP_Codigo;
    
            if ($datos_pedido[0]->GUIAREMP_Codigo != NULL){
                $guia_ant = $datos_pedido[0]->GUIAREMP_Codigo;
                $sernum_ant = $datos_pedido[0]->GUIAREMC_SerieNumero;
                $message = "Una guia fue asociada anteriormente: ".$datos_pedido[0]->GUIAREMC_SerieNumero;
                $error = true;
            }
          }
        if($datos_pedido[0]->PEDIC_FlagEstado == 3){
            $message = "Pedido Anulado";
            $error = true;
        }
    
        if ($datos_pedido == NULL){
            $message = "Documento origen no encontrado.";
            $error = true;
        }else{
            $message = "Documento origen encontrado.";
            $error = true;
        }

        if ($error == false){
            ## DATOS DE LA EMPRESA EMISORA
              $companiaInfo = $this->compania_model->obtener($compania);
              $establecimientoInfo = $this->emprestablecimiento_model->listar( $companiaInfo[0]->EMPRP_Codigo, '', $companiaInfo[0]->COMPP_Codigo );
              $empresaInfo =  $this->empresa_model->obtener_datosEmpresa( $establecimientoInfo[0]->EMPRP_Codigo );
    
              $ubigeo_origen = $establecimientoInfo[0]->UBIGP_Codigo;
              $direccion_origen = $establecimientoInfo[0]->EESTAC_Direccion;
              
              $configuracion_datos = $this->configuracion_model->obtener_numero_documento($compania, 10);
              $serie = ($configuracion_datos[0]->CONFIC_Serie == NULL || $configuracion_datos[0]->CONFIC_Serie == "") ? 1 : $configuracion_datos[0]->CONFIC_Serie;
              $numero = $this->lib_props->getNumberFormat($configuracion_datos[0]->CONFIC_Numero + 1, 6);
            
            ## PEDIDOS DETALLE GENERAL
              $tipo_oper = $datos_pedido[0]->PEDIC_TipoDocume;
              $serieOC = $datos_pedido[0]->PEDIC_Serie;
              $numeroOC = $datos_pedido[0]->PEDIC_Numero;
              $cliente = $datos_pedido[0]->CLIP_Codigo;
              $moneda = $datos_pedido[0]->MONED_Codigo;
              $fecha = $datos_pedido[0]->PEDIC_FechaRegistro;
              $fecha = ( $datos_pedido[0]->OCOMC_FechaEntrega != NULL && $datos_pedido[0]->OCOMC_FechaEntrega != "" ) ? $datos_pedido[0]->OCOMC_FechaEntrega : $fecha;
              $almacen = $datos_pedido[0]->ALMAP_Codigo;
              //$direccion = $datos_pedido[0]->OCOMC_EnvioDireccion;
              //$observacion = $datos_pedido[0]->OCOMC_Observacion;
              # Numero de orden de compra cliente
              //$OCcliente = $datos_pedido[0]->OCOMC_PersonaAutorizada;
              # $tipo_movimiento por defecto es venta (1)
              $tipo_movimiento = 1;
              $otro_motivo = NULL;
              
            ## TOTALES
              $igv = $datos_pedido[0]->PEDIC_IGVTotal;
              $igv100 = $datos_pedido[0]->PEDIC_IGV;
              $descuento = $datos_pedido[0]->PEDIC_DescuentoTotal;
              $descuento100 = $datos_pedido[0]->PEDIC_Descuento100;
              $subtotal = ($datos_pedido[0]->PEDIC_PrecioTotal - $datos_pedido[0]->PEDIC_IGVTotal);
              $total = $datos_pedido[0]->PEDIC_PrecioTotal;
    
            ## A insertar en la guia R.
              $filter = new stdClass();
              $filter->GUIAREMC_TipoOperacion = $tipo_oper;
              $filter->GUIAREMC_Serie = $serie;
              $filter->GUIAREMC_Numero = $numero;
              $filter->MONED_Codigo = $moneda;
    
              
              $filter->PRESUP_Codigo = NULL;
    
              $filter->TIPOMOVP_Codigo = $tipo_movimiento;
              $filter->GUIAREMC_OtroMotivo = $otro_motivo;
              
              $filter->GUIAREMC_CodigoUsuario = NULL;
              $filter->USUA_Codigo = $this->somevar['usuario'];
    
              $filter->COMPP_Codigo = $compania;
              # OC cliente guardado en $filter->DOCUP_Codigo
    
              $filter->GUIAREMC_PersReceNombre = "-";
              $filter->GUIAREMC_PersReceDNI = "-";
              $filter->GUIAREMC_NumeroRef = "";
              $filter->GUIAREMC_OCompra = "$serieOC-$numeroOC";
              $filter->GUIAREMC_FechaTraslado = $fecha;
              
              $filter->GUIAREMC_Fecha = date("Y-m-d");
              $filter->EMPRP_Codigo = NULL;
    
            ## CLIENTE Y DIRECCIONES
                  $filter->CLIP_Codigo = $cliente;
                  $datos_cliente = $this->cliente_model->obtener($cliente);
    
                  $nombre_cliente = $datos_cliente->nombre;
                  $ruc_cliente = $datos_cliente->ruc;
                  $dni_cliente = $datos_cliente->dni;
                  $ruc_cliente = ( $ruc_cliente == "" ) ? $dni_cliente : $ruc_cliente ;
                  $email   = $datos_cliente->correo;
                  $direccion_destino = (trim($direccion) == "") ? $datos_cliente->direccion : trim($direccion);
                  $ubigeo_destino = $datos_cliente->ubigeo;
              
                
                $filter->GUIAREMC_UbigeoPartida = $ubigeo_origen;
                $filter->GUIAREMC_PuntoPartida = strtoupper($direccion_origen);
                #$ubigeo_destino;
                $filter->GUIAREMC_UbigeoLlegada = 0;
                $filter->GUIAREMC_PuntoLlegada = strtoupper($direccion_destino);
    
            ## TRANSPORTE
              $filter->EMPRP_Codigo = 1;
              $filter->GUIAREMC_Marca = "-";
              $filter->GUIAREMC_Placa = "-";
              $filter->GUIAREMC_RegistroMTC = "";
              $filter->GUIAREMC_Certificado = "";
              $filter->GUIAREMC_Licencia = "";
              $filter->GUIAREMC_PersReceDNI = "00000000";
              $filter->GUIAREMC_NombreConductor = "-";
              
              $filter->GUIAREMC_descuento100 = $descuento100;
              $filter->GUIAREMC_igv100 = $igv100;
              $filter->GUIAREMC_subtotal = $subtotal;
              $filter->GUIAREMC_descuento = $descuento;
              $filter->GUIAREMC_igv = $igv;
              $filter->GUIAREMC_total = $total;
              $filter->GUIAREMC_FlagEstado = "2";
              $filter->PEDIP_Codigo = $codigoP;

              $guiarem_id = $this->guiarem_model->insertar($filter);
              $guiaped_id = $this->guiarempedido_model->insertar($codigoP,$guiarem_id);
              
            ## ACTUALIZA EL CORRELATIVO DE GUIAS
            if ($tipo_oper == 'V')
                $this->configuracion_model->modificar_configuracion($compania, 10, $numero);
    
            ## ARTICULOS
            $detalle = $this->pedido_model->obtener_detalle_pedido($codigoP);
            $detalle_ocompra = array();
            if ($detalle != NULL) {
              foreach ($detalle as $indice => $valor) {
                $filterGuia = new stdClass();
                $filterGuia->GUIAREMP_Codigo = $guiarem_id;
                $filterGuia->PRODCTOP_Codigo = $valor->PROD_Codigo;
                $filterGuia->UNDMED_Codigo = $valor->UNDMED_Codigo;
                //$filterGuia->LOTP_Codigo = $valor->LOTP_Codigo;
                $filterGuia->AFECT_Codigo = $valor->AFECT_Codigo;
                $filterGuia->GUIAREMDETC_Cantidad = $valor->PEDIDETC_Cantidad;
                $filterGuia->GUIAREMDETC_Pu = $valor->PEDIDETC_PSIGV;
                $filterGuia->GUIAREMDETC_Subtotal = $valor->PEDIDETC_Precio;
                $filterGuia->GUIAREMDETC_Descuento = $valor->PEDIDETC_Descuento;
                $filterGuia->GUIAREMDETC_Igv = $valor->PEDIDETC_IGV;
                $filterGuia->GUIAREMDETC_Total = $valor->PEDIDETC_Importe;
                $filterGuia->GUIAREMDETC_Pu_ConIgv = $valor->PEDIDETC_PCIGV;
                $filterGuia->GUIAREMDETC_Descuento100 = $valor->PEDIDETC_Descuento100;
                $filterGuia->GUIAREMDETC_Igv100 = 18;
                $filterGuia->GUIAREMDETC_Costo = 0;
                
                $filterGuia->GUIAREMDETC_Venta = NULL;
                $filterGuia->GUIAREMDETC_ITEM = $indice + 1;
                $filterGuia->GUIAREMDETC_Peso = 0;
    
                $filterGuia->GUIAREMDETC_GenInd = "";
                $filterGuia->GUIAREMDETC_Descripcion = $valor->PROD_Nombre;
                $filterGuia->GUIAREMDETC_Observacion = $valor->OCOMDEC_Observacion;
                $filterGuia->ALMAP_Codigo = $almacen;
                $this->guiaremdetalle_model->insertar($filterGuia);
              }
            }
    
                $json = array(
                            "result" => "success",
                            "message" => "Operacion exitosa.",
                            "guia" => $guiarem_id,
                            "sernum" => $serie."-".$this->lib_props->getOrderNumeroSerie($numero)
                          );
          }
          else{
                $json = array(
                            "result" => "error",
                            "message" => $message,
                            "guia" => (isset($guia_ant)) ? $guia_ant : "",
                            "sernum" => (isset($sernum_ant)) ? $sernum_ant : ""
                          );
          }
    
        echo json_encode($json);
    }
      public function insertar_comprobante(){
        $this->load->model("maestros/compania_model");
        $this->load->model('maestros/documento_model');
        $this->load->model('ventas/comprobante_model');
        $this->load->model('ventas/comprobantedetalle_model');
    
        $datos_ocompra  = NULL;
        $error          = false;

        $codigoP       = $this->input->post("idOC");
        
        $tipoDocumento  = trim($this->input->post("doc"));
    
        if ($codigoP == ""){
            $message = "Documento de origen no definido.";
            $error = true;
        }
        else{
            $datos_pedido = $this->pedido_model->obtener_pedido($codigoP);
            $compania = $datos_pedido[0]->COMPP_Codigo;
    
            if ($datos_pedido[0]->CPP_Codigo != NULL){
                $comprobante_ant = $datos_pedido[0]->CPP_Codigo;
                    $sernum_ant = $datos_pedido[0]->CPC_SerieNumero;
                $message = "Un documento fue asociado anteriormente: ".$datos_pedido[0]->CPC_SerieNumero;
                $error = true;
            }
        }

        if($datos_pedido[0]->PEDIC_FlagEstado == 3){
            $message = "Pedido Anulado";
            $error = true;
        }
    
        if ($tipoDocumento == ""){
            $message = "Tipo de documento destino no definido.";
            $error = true;
        }
    
        if ($datos_pedido == NULL){
            $message = "Documento origen no encontrado.";
            $error = true;
        }
    
        if ($error == false){
          ## OC DETALLE GENERAL
              $compania  = $datos_pedido[0]->COMPP_Codigo;
              $tipo_oper = $datos_pedido[0]->PEDIC_TipoDocume;
              $tipo_docu = $tipoDocumento;
              $serieP   = $datos_pedido[0]->PEDIC_Serie;
              $numeroP  = $datos_pedido[0]->PEDIC_Numero;
              $cliente   = $datos_pedido[0]->CLIP_Codigo;
              $fecha     = date("Y-m-d");
              $hora      = date("H:i:s");
              $almacen   = $datos_pedido[0]->ALMAP_Codigo;
              
              $direccion = "";
              if ($tipo_oper == 'V'){
                if ($datos_ocompra[0]->OCOMC_FactDireccion != "" && $datos_ocompra[0]->OCOMC_FactDireccion != "0")
                  $direccion = $datos_ocompra[0]->OCOMC_FactDireccion;
                else{
                  $datos_cliente = $this->cliente_model->obtener($cliente);
                  $direccion = $datos_cliente->direccion;
                }
              }
            
          ## COMPROBANTE DETALLE GENERAL
            $filter = new stdClass();
            $filter->CPC_TipoOperacion = $tipo_oper;
            $filter->CPC_TipoDocumento = $tipo_docu;
            $filter->ALMAP_Codigo = $almacen;
            $filter->CPC_NumeroAutomatico = 1;
            $filter->CPC_Fecha = $fecha;
            $filter->CPC_FechaVencimiento = $fecha;
            $filter->CPC_Hora = $hora;
            // $filter->CPC_Observacion = $datos_pedido[0]->OCOMC_Observacion;
            $filter->CPC_FlagEstado = "2";
            
            $documento = $this->documento_model->obtenerAbreviatura($tipo_docu);
            $tipo = $documento[0]->DOCUP_Codigo;
    
            # Correlativo del documento
            $configuracion_datos = $this->configuracion_model->obtener_numero_documento($compania, $tipo);
            $filter->CPC_Serie = $configuracion_datos[0]->CONFIC_Serie;
            $filter->CPC_Numero = $configuracion_datos[0]->CONFIC_Numero + 1;
    
            $cSerie = $configuracion_datos[0]->CONFIC_Serie;
            $cNumero = $configuracion_datos[0]->CONFIC_Numero + 1;
    
            $filter->CLIP_Codigo = $cliente;
            $filter->CPC_Direccion = $direccion;
            //$filter->PROVP_Codigo = $proveedor;
    
          ## PAGO Y TOTAL
            $filter->FORPAP_Codigo = $datos_pedido[0]->FORPAP_Codigo;
            //$f_pago = $datos_ocompra[0]->FORPAP_Codigo;
            $filter->MONED_Codigo = $datos_pedido[0]->MONED_Codigo;
            
            $filter->CPC_igv = $datos_pedido[0]->PEDIC_IGVTotal;
            $filter->CPC_igv100 = $datos_pedido[0]->PEDIC_IGV;
            $filter->CPC_descuento = $datos_pedido[0]->PEDIC_DescuentoTotal;
            $filter->CPC_descuento100 = $datos_pedido[0]->PEDIC_Descuento100;
            $filter->CPC_subtotal = ($datos_pedido[0]->PEDIC_PrecioTotal - $datos_pedido[0]->PEDIC_IGVTotal);
            $filter->CPC_total = $datos_pedido[0]->PEDIC_PrecioTotal;
            $filter->FORPAP_Monto = $datos_pedido[0]->FORPAP_Monto;
            $filter->CPC_Vendedor = $datos_pedido[0]->PEDIC_Vendedor;
            $filter->CAJA_Codigo = 3;
            // $filter->CPC_TDC = $datos_ocompra[0]->OCOMP_TDC;
            // $filter->CPC_TDC_opcional = $datos_ocompra[0]->OCOMP_TDC_opcional;
            
          ## OTROS DETALLES
            
            $filter->CPC_Tipclip = $datos_pedido[0]->PEDIC_Tipclip;
          $filter->CPC_FlagUsaAdelanto = 0;        
            $filter->PRESUP_Codigo = NULL;
            $filter->CPC_GuiaRemCodigo = "";
            $filter->GUIAREMP_Codigo = "";
            $filter->CPC_DocuRefeCodigo = "";
            $filter->CPC_ModoImpresion = '1';
            $filter->IMPOR_Nombre = 0;
            $filter ->DUA_Codigo =0;
            $filter->INV_FlagEstado = 0;
            $filter->PAIS_Codigo = 0;
            $filter->CPC_subtotal_conigv = 0.00;
            $filter->CPC_descuento_conigv = 0.00;
            $filter->CPC_TDC  = 0.000;
            $filter->COMPP_Codigo = $compania;
            $comprobante = $this->comprobante_model->insertar_comprobante($filter);
            $compped_id = $this->comprobantepedido_model->insertar($codigoP,$comprobante,$cSerie, $cNumero);

            
          ## ASOCIAMOS LA GUIA DE REMISION
            if ($datos_pedido[0]->GUIAREMP_Codigo != NULL){
              $filterCG = new stdClass();
              $filterCG->CPP_Codigo = $comprobante;
              $filterCG->GUIAREMP_Codigo = $datos_pedido[0]->GUIAREMP_Codigo;
              $filterCG->COMPGUI_FlagEstado = 1;
              $filterCG->COMPGU_FechaRegistro = date("Y-m-d H:i:s");
              $this->comprobante_model->insertar_comprobante_guiarem($filterCG);
            }
        ## formas de pago multiple lista 
            
        $datospedidoformapago = $this->comprobante_formapago_model->obtenerxPedidoformapago($codigoP);

        foreach ($datospedidoformapago as $dato) { 
            $stdFormasPago = new StdClass();
            $stdFormasPago->CPP_Codigo      = $comprobante;
            $stdFormasPago->FORPAP_Codigo   = $dato->FORPAP_Codigo;
            $stdFormasPago->MONED_Codigo    = $dato->MONED_Codigo;
            $stdFormasPago->monto           = $dato->monto;
            $stdFormasPago->compro_flag_FechaRegistro = date('Y-m-d H:i:s');
        
            $this->comprobante_formapago_model->insertar($stdFormasPago);
        }
        
          /*:::::::::::::::::FORMAS DE PAGO :::::::::::::::::::*/

          ## ARTICULOS
            $detalle = $this->pedido_model->obtener_detalle_pedido($codigoP);
            $detalle_ocompra = array();
            if (count($detalle) > 0) {
              foreach ($detalle as $indice => $valor) {        
                $filterDet = new stdClass();
                $filterDet->CPP_Codigo = $comprobante;
                $filterDet->PROD_Codigo = $valor->PROD_Codigo;
                //$filterDet->CPDEC_GenInd = $valor->OCOMDEC_GenInd;
                $filterDet->UNDMED_Codigo = $valor->UNDMED_Codigo;
                $filterDet->LOTP_Codigo = 0;
                $filterDet->AFECT_Codigo = $valor->AFECT_Codigo;
                $filterDet->CPDEC_Cantidad = $valor->PEDIDETC_Cantidad;
                $filterDet->CPDEC_Costo = $valor->PEDIDETC_Costo;
                $filterDet->CPDEC_Pu = $valor->PEDIDETC_PSIGV;
                $filterDet->CPDEC_Subtotal = $valor->PEDIDETC_Precio;
                $filterDet->CPDEC_Descuento = $valor->PEDIDETC_Descuento;
                $filterDet->CPDEC_Descuento100 = $valor->PEDIDETC_Descuento100;
                $filterDet->CPDEC_Igv = $valor->PEDIDETC_IGV;
                $filterDet->CPDEC_Igv100 = 18;
                $filterDet->CPDEC_Pu_ConIgv = $valor->PEDIDETC_PCIGV;
                $filterDet->CPDEC_Total = $valor->PEDIDETC_Importe;
                $filterDet->CPDEC_Descripcion = $valor->PROD_Nombre;
                //$filterDet->CPDEC_Observacion = $valor->OCOMDEC_Observacion;
                $filterDet->ALMAP_Codigo = $almacen;
    
                $this->comprobantedetalle_model->insertar($filterDet);
              }
            }
    
          $json = array(
                            "result" => "success",
                            "message" => "Operacion exitosa.",
                            "comprobante" => $comprobante,
                            "sernum" => $cSerie."-".$this->lib_props->getOrderNumeroSerie($cNumero)
                          );
          }
          else{
          $json = array(
                            "result" => "error",
                            "message" => $message,
                            "comprobante" => (isset($comprobante_ant)) ? $comprobante_ant : "",
                            "sernum" => (isset($sernum_ant)) ? $sernum_ant : "" 
                          );
          }
    
        echo json_encode($json);
    }

    public function aprobar_pedido(){
        $userCod    = $this->session->userdata('user');
        $codPed   = $this->input->post('codPed');
        
        $estado     = $this->input->post('estado');
        $filter = new stdClass();
        $filter->PEDIC_FlagEstado = 2;
        $pedido = $this->pedido_model->obtener_pedido($codPed);
        $almacen = $pedido[0]->ALMAP_Codigo;
        $datapedido = $this->pedido_model->obtener_detalle_pedido($codPed);
        $terminar = $this->pedido_model->modificar_pedido($codPed,$filter);
                    if ($terminar){
                        $data = array( 
                            "estado"        => "success",
                            "movimiento"    => 1,
                            "mensaje"       => "El envío fue completado con éxito"
                        );
                    }else{
                        $data = array( 
                            "estado"        => "error",
                            "movimiento"    => 1,
                            "mensaje"       => "Se ha presentado un error al confirmar el pedido"
                        );
                    }
        echo json_encode($data);
    }

    public function terminar_pedido(){
        $userCod    = $this->session->userdata('user');
        $codPed   = $this->input->post('codPed');
        $estado     = $this->input->post('estado');
        $filter = new stdClass();
        $filter->PEDIC_FlagEstado = 1;
        $pedido = $this->pedido_model->obtener_pedido($codPed);
        $datapedido = $this->pedido_model->obtener_detalle_pedido($codPed);
        $terminar = $this->pedido_model->modificar_pedido($codPed,$filter);
                    if ($terminar){
                        $data = array( 
                            "estado"        => "success",
                            "movimiento"    => 1,
                            "mensaje"       => "El envío fue completado con éxito"
                        );
                    }else{
                        $data = array( 
                            "estado"        => "error",
                            "movimiento"    => 1,
                            "mensaje"       => "Se ha presentado un error al confirmar el pedido"
                        );
                    }
        echo json_encode($data);
    }

    public function anular_pedido(){
        $userCod    = $this->session->userdata('user');
        $codPed   = $this->input->post('codPed');
        $estado     = $this->input->post('estado');
        $filter = new stdClass();
        $filter->PEDIC_FlagEstado = 3;
        $pedido = $this->pedido_model->obtener_pedido($codPed);
        $almacen = $pedido[0]->ALMAP_Codigo;
        $datapedido = $this->pedido_model->obtener_detalle_pedido($codPed);

        $terminar = $this->pedido_model->modificar_pedido($codPed,$filter);
                    if ($terminar){
                        if(count($datapedido) > 0){
                            foreach($datapedido as $indice => $valor){
                                $this->almacenproducto_model->AnularPedido($almacen,$valor->PROD_Codigo,$valor->PEDIDETC_Cantidad);
                            }
                        }
                        $data = array( 
                            "estado"        => "success",
                            "movimiento"    => 1,
                            "mensaje"       => "El envío fue ANULADO con éxito"
                        );
                    }else{
                        $data = array( 
                            "estado"        => "error",
                            "movimiento"    => 1,
                            "mensaje"       => "Se ha presentado un error al anular el pedido"
                        );
                    }
        echo json_encode($data);
    }

    public function select_cmbVendedor($index){
        $array_dist = $this->comprobante_model->select_cmbVendedor();
        $arreglo = array();
        foreach ($array_dist as $indice => $valor) {
            $indice1 = $valor->PERSP_Codigo;
            $valor1 = $valor->PERSC_Nombre." ".$valor->PERSC_ApellidoPaterno;
            $arreglo[$indice1] = $valor1;
            }
            $resultado = $this->html->optionHTML($arreglo, $index, array('', '::Seleccione::'));
            return $resultado;
    }

    public function editar_pedido($codigo){
        /* :::: SE CREA LA SESSION :::*/
        $hoy = date('Y-m-d H:i:s');
        $cadena = strtotime($hoy).substr((string)microtime(), 1, 8);
        $tempSession = str_replace('.','',$cadena);
        $data['tempSession']  = $tempSession;
        /* :::::::::::::::::::::::::::*/

        $tipo_oper = "V"; #$this->uri->segment(4);
        $tipo_docu = "P"; #$this->uri->segment(5);
        $tipoDocumento = 'PEDIDO';
        $tipo = 1;

        $datos_pedido = $this->pedido_model->obtener_pedido($codigo);
        $detalle_pedido = $this->pedidodetalle_model->listar($codigo);
       
        $codigopedido = $datos_pedido[0]->PEDIP_Codigo;
        $numero = $datos_pedido[0]->PEDIC_Numero;
        $serie = $datos_pedido[0]->PEDIC_Serie;
        $flagPedido = $datos_pedido[0]->PEDIC_FlagEstado;
        $compania = $this->somevar['compania'];
        $comp_confi = $this->companiaconfiguracion_model->obtener($compania);
        $observacion = $datos_pedido[0]->PEDIC_Observacion;
        $estado = $datos_pedido[0]->PEDIC_FlagEstado;
        $fechaReg = explode(" ",$datos_pedido[0]->PEDIC_FechaRegistro);
        $fecha = mysql_to_human($fechaReg[0]);
        $categoriaprecioped= $datos_pedido[0]->PEDIC_Tipclip;
        $ordencompra = $datos_pedido[0]->OCOMP_Codigo;

        $cliente = $datos_pedido[0]->CLIP_Codigo;
        $vendedor = $datos_pedido[0]->PEDIC_Vendedor;
        
        $forma_pago = $datos_pedido[0]->FORPAP_Codigo;
        $monto_forma_pago   = $datos_pedido[0]->FORPAP_Monto;
        $moneda = $datos_pedido[0]->MONED_Codigo;

        $data['modo'] = 'modificar';
        $data['titulo'] = "EDITAR PEDIDO";
        $data['codigo'] = '';
        $data['numero_suger'] = '';
        $data['serie_suger'] = '';
        $data['id'] = "";
        $data['flagPedido'] = $flagPedido;
        $data['numero'] = $numero;
        $data['serie'] = $serie;
        $data['igv'] = $datos_pedido[0]->PEDIC_IGVTotal;
        $data['tipo_oper'] = $tipo_oper;
        $data['descuento'] = $datos_pedido[0]->PEDIC_Descuento100;
        $data['importetotal'] = $datos_pedido[0]->PEDIC_PrecioTotal;
        $data['contiene_igv'] = (($comp_confi[0]->COMPCONFIC_PrecioContieneIgv == '1') ? true : false);
        $data['compania'] = $this->somevar['compania'];
        $data['afectaciones'] = $this->producto_model->tipo_afectacion();
        $data["categorias_cliente"] = $this->tipocliente_model->listar2($categoriaprecioped);


        $data['idOcompra'] = $ordencompra;
        $data['serieNumeroCotizacion'] = $datos_pedido[0]->serieNumero;
        
        $subtotal = 0;
        $descuento = 0;
        $igv = 0;
        $total = 0;
        $subtotal_conigv = 0;
        $descuento_conigv = 0;
        $igv100 = 18;
        $descuento100 = 0;
        $guiarem_codigo = 0;
        $docurefe_codigo = 0;
        
        $codigocliente = $datos_pedido[0]->CLIP_Codigo;
        
        $data["oc_cliente"]= $oc_cliente;
        $ruc_cliente = '';
        $nombre_cliente = '';
        $nombre_proveedor = '';
        $ruc_proveedor = '';
        if ($cliente != '' && $cliente != '0') {
            $datos_cliente = $this->cliente_model->obtener($cliente);
            if ($datos_cliente) {
                $nombre_cliente = $datos_cliente->nombre;
                $ruc_cliente = $datos_cliente->ruc;
            }
        }

        /**gcbq implementamos el tipo de documento dinamico***/
        $this->load->model('maestros/documento_model');
        $documento=$this->documento_model->obtenerAbreviatura(trim($tipo_docu));
        $tipo=$documento[0]->DOCUP_Codigo;
        /**FIN codigo del tipo de documento**/
        
        $data['direccionsuc'] = form_input(array("name" => "direccionsuc", "id" => "direccionsuc", "class" => "cajaGeneral", "size" => "40", "maxlength" => "250", "value" => $direccion));
        $data['codigo'] = $codigo;
        $lista_almacen = $this->almacen_model->seleccionar();
        $data['cboAlmacen'] = form_dropdown("almacen", $lista_almacen, obtener_val_x_defecto($lista_almacen), " class='comboMedio' style='width:125px;' id='almacen'");
        $data['cboPresupuesto'] = $this->OPTION_generador($this->presupuesto_model->listar_presupuestos_nocomprobante($tipo_oper, $tipo_docu, $codigo), 'PRESUP_Codigo', array('PRESUC_Numero', 'nombre'), $presupuesto, array('', '::Seleccione::'), ' / ');
        $data['cboOrdencompra'] = $this->OPTION_generador($this->ocompra_model->listar_ocompras_nocomprobante($tipo_oper, $codigo), 'OCOMP_Codigo', array('OCOMC_Numero', 'nombre'), $ordencompra, array('', '::Seleccione::'), ' / ');
        $data['cboFormaPago'] = $this->OPTION_generador($this->formapago_model->listar(), 'FORPAP_Codigo', 'FORPAC_Descripcion', $forma_pago);
        $data['cboFormaPagos']   = $this->OPTION_generador($this->formapago_model->listar(), 'FORPAP_Codigo', 'FORPAC_Descripcion', $forma_pago);
        $data['cboFormaPagosmulti'] = $this->OPTION_generador($this->formapago_model->listarmulti(), 'FORPAP_Codigo', 'FORPAC_Descripcion', $forma_pago);
        $data['cboMoneda'] = $this->OPTION_generador($this->moneda_model->listar(), 'MONED_Codigo', 'MONED_Descripcion', $moneda);
        $data['cboVendedor']    = $this->lib_props->listarVendedores($vendedor);
        $data['cboproyecto'] = $this->OPTION_generador($this->proyecto_model->listar_proyectos(), 'PROYP_Codigo', 'PROYC_Nombre', $proyecto);
        $data['cboimportacion'] = $this->OPTION_generador($this->importacion_model->listar_importacion(), 'IMPOR_Codigo', 'IMPOR_Nombre', $importacion);
        $data['serie'] = $serie;
        $data['numero'] = $numero;
        $fechaMin = $datos_pedido[0]->PEDIC_FechaEntregaMin;
        $fechaMax = $datos_pedido[0]->PEDIC_FechaEntregaMax;
        $fechaEntregaMin = explode(" ",$fechaMin);
        $fechaEntregaMax = explode(" ",$fechaMax);
        $data['fechaeMin'] = $fechaEntregaMin[0]."T".$fechaEntregaMin[1];
        $data['fechaeMax'] = $fechaEntregaMax[0]."T".$fechaEntregaMax[1];
        $data['usa_adelanto'] = $datos_comprobante[0]->CPC_FlagUsaAdelanto;
        $data['direccion'] = $datos_pedido[0]->PEDIC_Direccion;
        $data['othersFormasP']  = $this->comprobante_formapago_model->getListXPedido($codigo);
        
        
        $data['descuento'] = $descuento100;
        $data['igv'] = $igv100;
        $data['igv_default'] = $comp_confi[0]->COMPCONFIC_Igv;
        $data['preciototal'] = $subtotal;
        $data['descuentotal'] = $descuento;
        $data['igvtotal'] = $datos_pedido[0]->PEDIC_IGVTotal;
        $data['importetotal'] = $datos_pedido[0]->PEDIC_PrecioTotal;
        $data['preciototal_conigv'] = $subtotal_conigv;
        $data['descuentotal_conigv'] = $descuento_conigv;
        $data['cliente'] = $cliente;
        $data['ruc_cliente'] = $ruc_cliente;
        $data['nombre_cliente'] = $nombre_cliente;
        
        $data['contiene_igv'] = (($comp_confi[0]->COMPCONFIC_PrecioContieneIgv == '1') ? true : false);
        $oculto = form_hidden(array('codigo' => $codigo, 'base_url' => base_url(), 'tipo_oper' => $tipo_oper, 'tipo_docu' => $tipo_docu, 'contiene_igv' => ($data['contiene_igv'] == true ? '1' : '0')));
        $data['titulo'] = "EDITAR PEDIDO";
        $data['tipo_docu'] = $tipo_docu;
        $data['formulario'] = "frmPedido";
        $data['oculto'] = $oculto;
        $data['url_action'] = base_url() . "index.php/compras/pedido/modificar_pedido";
        $atributos = array('width' => 700, 'height' => 450, 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0');
        $contenido = "<img height='16' width='16' src='" . base_url() . "images/ver.png' title='Buscar Cliente' border='0'>";
        $data['vercliente'] = anchor_popup('ventas/cliente/ventana_busqueda_cliente', $contenido, $atributos, 'linkVerCliente');
        $data['verproveedor'] = anchor_popup('compras/proveedor/ventana_busqueda_proveedor', $contenido, $atributos, 'linkVerProveedor');
        $data['verproducto'] = anchor_popup('almacen/producto/ventana_busqueda_producto', $contenido, $atributos, 'linkVerProducto');
        $data['hoy'] = $fecha;
        $data['guiarem_codigo'] = $guiarem_codigo;
        $data['docurefe_codigo'] = $docurefe_codigo;
        $data['observacion'] = $observacion;
        $data['estado'] = $estado;
        $data['hidden'] = "";
        $data['focus'] = "";
        $data['modo_impresion'] = $modo_impresion;
        $data['serie_suger'] = "";
        $data['numero_suger'] = "";
        $data['afectaciones'] = $this->producto_model->tipo_afectacion();
        
        $data['tdcDolar'] = 3;
        $data['tdcEuro'] = 3;

        $data['tipoOperCodigo'] = $tipo;

        /**gcbq verificamos si el detalle dee comprobante contiene productos individuales**/
        
        /**fin de procewso de realizaciom**/
        
        $this->layout->view('compras/pedido_nuevo', $data);
    }

    public function modificar_pedido(){

        $pedido = $this->input->post('codigo');
        if ($pedido == NULL)
            exit('{"result":"error3", "msj":"No se pudo completar la operación. Intentelo Nuevamente.\nSi el inconveniente persiste comuniquese con el administrador."}');


        $filter = new stdClass();
        $filter->PEDIC_Numero = $this->input->post('numero');
        $filter->PEDIC_Serie = $this->input->post('serie');
        $filter->CLIP_Codigo =$this->input->post('cliente');
        $filter->ECONP_Contacto = $this->input->post('contacto');
        $filter->OCOMP_Codigo = $this->input->post('idOcompra');
        $filter->PROYP_Codigo = $this->input->post('obra');
        $filter->MONED_Codigo = $this->input->post('moneda');
        $filter->PEDIC_Observacion = $this->input->post('observacion');
        
        $filter->PEDIC_ImporteBruto =$this->input->post('importebruto');
        $filter->PEDIC_DescuentoTotal =$this->input->post('descuentotal');
        $filter->PEDIC_ValorVenta =$this->input->post('vventa');
        $filter->PEDIC_IGVTotal =$this->input->post('igvtotal');
        $filter->PEDIC_PrecioTotal =$this->input->post('importetotal');
        $filter->PEDIC_descuento100 = $this->input->post('descuento');
        $filter->PEDIC_FlagEstado = $this->input->post('estatus');
        $vendedor = $this->input->post('cboVendedor');
        $filter->PEDIC_Tipclip = $this->input->post('TipCli');
        $idpersonavendedor=$this->persona_model->obtenerCodigopersxcoddirectivo($vendedor);
        $filter->PEDIC_Vendedor = $idpersonavendedor;


        $estadoPedido = $this->input->post('estatus');

        $this->pedido_model->modificar_pedido($pedido,$filter);
        
        if ( $filter->OCOMP_Codigo != NULL && $filter->OCOMP_Codigo != "" ){
            $filterOC = new stdClass();
            $filterOC->PEDIP_Codigo = $pedido;
            $this->ocompra_model->modificar_ocompra($filter->OCOMP_Codigo, $filterOC);
        }

        //detalle
        $almprod = $this->input->post('almacenProducto');
        $prodcodigo = $this->input->post('prodcodigo');
        $proddescuento = $this->input->post('proddescuento');
        $produnidad = $this->input->post('produnidad');
        $prodcantidad = $this->input->post('prodcantidad');
        $prodpu = $this->input->post('prodpu');
        $prodprecio = $this->input->post('prodprecio');
        $prodigv = $this->input->post('prodigv');
        $prodimporte = $this->input->post('prodimporte');
        $prodpu_conigv = $this->input->post('prodpu_conigv');
        
        $detaccion = $this->input->post('detaccion');
        $detacodi = $this->input->post('detacodi');
        //$prodigv100 = $this->input->post('prodigv100');
        $proddescuento100 = $this->input->post('proddescuento100');
        $proddescri = $this->input->post('proddescri');
        $pedido = $this->input->post('codigo');
        
        /*if ($estadoPedido == 1){ // SI ESTA FINALIZADO 1 CREA LA GUIA DE INGRESO
            $filterGuiain = new stdClass();
            $filterGuiain->TIPOMOVP_Codigo = 6;
            $filterGuiain->ALMAP_Codigo = 3; // ALMACEN PRODUCCION
            $filterGuiain->DOCUP_Codigo = 1; // ORDEN DE PEDIDO 1
            $filterGuiain->GUIAINC_Fecha = date('Y-m-d');
            $filterGuiain->USUA_Codigo = $_SESSION['user'];
            $guiin_id = $this->guiain_model->insertar($filterGuiain);

            # SE GENERA LA GUIA DE TRANSFERENCIA
            $detaPedido = $this->pedido_model->obtener_pedido($pedido);
            
            $data_confi1 = $this->configuracion_model->obtener_numero_documento($this->somevar['compania'], 10);
            $serie = $data_confi1[0]->CONFIC_Serie;
            $numero = $data_confi1[0]->CONFIC_Numero + 1;
            $almacen = 3; #$this->almacen_model->obtenerAlmacenCompania(3); // Los translados de los pedidos siempre se realizan desde el almacen - taller
            $almacen_destino = $this->almacen_model->obtenerAlmacenCompania($detaPedido[0]->COMPP_Codigo);

            $filter = new stdClass();
            $filter->GTRANC_Serie = $serie;
            $filter->GTRANC_Numero = $numero;
            $filter->GTRANC_CodigoUsuario = $_SESSION['user'];

            $filter->GTRANC_AlmacenOrigen = $almacen;
            $filter->GTRANC_AlmacenDestino = $almacen_destino;
            $filter->GTRANC_Fecha = date("Y-m-d");
            $filter->GTRANC_Observacion = "ENVIO DESDE EL TALLER. PEDIDO FINALIZADO.";
            $filter->GTRANC_Placa = "";
            $filter->GTRANC_Licencia = "";
            $filter->GTRANC_Chofer = "";
            $filter->EMPRP_Codigo = NULL;
            $filter->COMPP_Codigo = $this->somevar['compania'];
            $filter->USUA_Codigo = $this->somevar['user'];
            $filter->GTRANC_EstadoTrans = 1;
            $filter->GTRANC_FlagEstado = 1;
            $guiatrans_id = $this->guiatrans_model->insertar($filter);

            $this->configuracion_model->modificar_configuracion($this->somevar['compania'], 10, $numero, $serie);
        }*/

        if (is_array($detacodi)) {
            foreach ($detacodi as $indice => $valor) {
                $dataprod = $this->producto_model->getProducto($prodcodigo[$indice]);
                $detalle_accion = $detaccion[$indice];
            
                $filter = new stdClass();
                $filter->PEDIP_Codigo = $pedido;
                $filter->PROD_Codigo = $prodcodigo[$indice];
                $filter->PROD_Nombre = $dataprod[0]->PROD_Nombre;

                
                if ($produnidad[$indice] == '' || $produnidad[$indice] == "null")
                    $produnidad[$indice] = NULL;
                    //if ($flagBS[$indice] == 'B')

                    $filter->UNDMED_Codigo = $produnidad[$indice];
                    $filter->PEDIDETC_Cantidad = $prodcantidad[$indice];
                    //if ($tipo_docu != 'B') {
                    $filter->PEDIDETC_PSIGV = $prodpu[$indice];
                    $filter->PEDIDETC_Precio = $prodprecio[$indice];
                    $filter->PEDIDETC_Descuento = $proddescuento[$indice];
                    $filter->PEDIDETC_PSIGV = $prodigv[$indice];
                    /* } else {
                     $filter->PRESDEC_Subtotal_ConIgv = $prodprecio_conigv[$indice];
                     $filter->PRESDEC_Descuento_ConIgv = $proddescuento_conigv[$indice];
                     } */
                    $filter->PEDIDETC_Importe = $prodimporte[$indice];
                    $filter->PEDIDETC_PCIGV = $prodpu_conigv[$indice];
                    $filter->PEDIDETC_Descuento100 = $proddescuento100[$indice];
                    //$filter->PRESDEC_Igv100 = $prodigv100[$indice];
                    //$filter->PRESDEC_Descripcion = strtoupper($proddescri[$indice]);
                    //$filter->PRESDEC_Observacion = "";
                    
                    if ($detalle_accion == 'n') {
                        $this->almacenproducto_model->colocar_stock_comprometido($almprod[$indice],$prodcodigo[$indice],$prodcantidad[$indice],$prodprecio[$indice]);
                        $this->pedidodetalle_model->insertar($filter);
                    } elseif ($detalle_accion == 'm') {
                        $actual_Prod = $this->pedidodetalle_model->getActualProd($pedido, $prodcodigo[$indice]);
                        $actual_cant = $actual_Prod[0]->PEDIDETC_Cantidad;
                        $this->almacenproducto_model->modificar_stock_comprometido($almprod[$indice],$prodcodigo[$indice],$prodcantidad[$indice], $actual_cant);
                        $this->pedidodetalle_model->modificar($valor, $filter);
                    } elseif ($detalle_accion == 'e') {
                        $this->almacenproducto_model->AnularPedido($almprod[$indice],$prodcodigo[$indice],$prodcantidad[$indice]);
                        $this->pedidodetalle_model->eliminar($valor);
                    }
                
                /*if ($estadoPedido == 1){
                    if ($detalle_accion == 'n' || $detalle_accion == 'm'){ // SI ESTA FINALIZADA 1 INGRESA LOS ARTICULOS ACTIVOS
                        $filterIngreso = new stdClass();
                        $filterIngreso->GUIAINP_Codigo = $guiin_id;
                        $filterIngreso->PRODCTOP_Codigo = $prodcodigo[$indice];
                        $filterIngreso->UNDMED_Codigo = $produnidad[$indice];
                        $filterIngreso->GUIAINDETC_Cantidad = $prodcantidad[$indice];
                        $filterIngreso->GUIAINDETC_Costo = 0;
                        $filterIngreso->GUIIAINDETC_GenInd = 'G';
                        $filterIngreso->ALMAP_Codigo = 3; // ALMACEN - TALLER
                        $insertGuiain = $this->guiaindetalle_model->insertar_2015($filterIngreso, 'INGRESO DEL TALLER', $pedido, 6);

                        $filter2 = new stdClass();
                        $filter2->GTRANP_Codigo = $guiatrans_id;
                        $filter2->PROD_Codigo = $prodcodigo[$indice];
                        $filter2->UNDMED_Codigo = $produnidad[$indice];
                        $filter2->GTRANDETC_Cantidad = $prodcantidad[$indice];
                        $filter2->GTRANDETC_Costo = 0;
                        $filter2->GTRANDETC_GenInd = 'G';
                        $filter2->GTRANDETC_Descripcion = $proddescri[$indice];
                        $filter2->GTRANDETC_FlagEstado = 1;
                        $this->guiatransdetalle_model->insertar($filter2);
                    }
                }*/
            }
        }
        exit('{"result":"ok", "codigo":"' . $pedido . '"}');
    }

    public function busqueda_personas(){
        $ruc      = $this->input->post('ruc');
        $nombre  = $this->input->post('nombre');
        $telefono = $this->input->post('telefono');
                $filter=new stdClass();
                $filter->nombre=$nombre;
        $data['n']                  = $this->input->post('n');
        $data['resultado_personas'] = $this->persona_model->buscar_personas($filter);
        $this->load->view('compras/busqueda_personas',$data);
    }

    public function JSON_busca_persona_xdoc($tipo, $numero){
            $datos_persona = $this->persona_model->busca_xnumeroDoc($tipo, $numero);  //Esta funcion me devuelde el registro de la empresa
            
            $resultado = '[]';
            if(count($datos_persona)>0){
                $dpto_domicilio = "15";
                $prov_domicilio = "01";
                $dist_domicilio = "00";             
                $ubigeo_domicilio = $datos_persona[0]->UBIGP_Domicilio;
                if($ubigeo_domicilio!='000000' && $ubigeo_domicilio!=''){
                    $dpto_domicilio = substr($ubigeo_domicilio,0,2);
                    $prov_domicilio = substr($ubigeo_domicilio,2,2);
                    $dist_domicilio = substr($ubigeo_domicilio,4,2);    
                }
                $ubig_naci = $datos_persona[0]->UBIGP_LugarNacimiento;
                $ubignom = '';
                if($ubig_naci!='000000' && $ubig_naci!=''){
                    $temp =  $this->ubigeo_model->obtener_ubigeo($ubig_naci);
                    if(count($temp)>0)
                        $ubignom=$temp[0]->UBIGC_Descripcion;
                }
                
                $resultado   = '[{"codigo":"'.$datos_persona[0]->PERSP_Codigo.
                                '","nombre":"'.$datos_persona[0]->PERSC_Nombre.
                                '","apepat":"'.$datos_persona[0]->PERSC_ApellidoPaterno.
                                '","apemat":"'.$datos_persona[0]->PERSC_ApellidoMaterno.
                                '","ubignom":"'.$ubignom.
                                '","ubigcod":"'.$datos_persona[0]->UBIGP_LugarNacimiento.
                                '","sexo":"'.$datos_persona[0]->PERSC_Sexo.
                                '","estadocivil":"'.$datos_persona[0]->ESTCP_EstadoCivil.
                                '","nacionalidad":"'.$datos_persona[0]->NACP_Nacionalidad.
                                '","ruc":"'.$datos_persona[0]->PERSC_Ruc.
                                '","departamento":"'.$dpto_domicilio.
                                '","provincia":"'.$prov_domicilio.
                                '","distrito":"'.$dist_domicilio.
                                '","direccion":"'.$datos_persona[0]->PERSC_Direccion.
                                '","telefono":"'.$datos_persona[0]->PERSC_Telefono.
                                '","movil":"'.$datos_persona[0]->PERSC_Movil.
                                '","fax":"'.$datos_persona[0]->PERSC_Fax.
                                '","correo":"'.$datos_persona[0]->PERSC_Email.
                                '","paginaweb":"'.$datos_persona[0]->PERSC_Web.
                                '","ctactesoles":"'.$datos_persona[0]->PERSC_CtaCteSoles.
                                '","ctactedolares":"'.$datos_persona[0]->PERSC_CtaCteDolares.'"}]';
            }
            echo $resultado;
        }

    public function eliminar_persona(){
            $persona = $this->input->post('persona');

            $this->persona_model->eliminar_persona($persona);
    }

    public function seleccionar_estadoCivil($indSel){
            $array_dist = $this->estadocivil_model->listar_estadoCivil();
            $arreglo = array();
            foreach($array_dist as $indice=>$valor){
                    $indice1   = $valor->ESTCP_Codigo;
                    $valor1    = $valor->ESTCC_Descripcion;
                    $arreglo[$indice1] = $valor1;
            }
            $resultado = $this->html->optionHTML($arreglo,$indSel,array('0','::Seleccione::'));
            return $resultado;
    }

    public function seleccionar_nacionalidad($indSel=''){
            $array_dist = $this->nacionalidad_model->listar_nacionalidad();
            $arreglo = array();
            foreach($array_dist as $indice=>$valor){
                    $indice1   = $valor->NACP_Codigo;
                    $valor1    = $valor->NACC_Descripcion;
                    $arreglo[$indice1] = $valor1;
            }
            $resultado = $this->html->optionHTML($arreglo,$indSel,array('','::Seleccione::'));
            return $resultado;
    }
    /*public function insertar_areaEmpresa($nombre_area){
            $this->empresa_model->insertar_areaEmpresa($area,$empresa,$descripcion);

    }*/
    public function seleccionar_departamento($indDefault=''){
            $array_dpto = $this->ubigeo_model->listar_departamentos();
            $arreglo = array();
            if(count($array_dpto)>0){
                    foreach($array_dpto as $indice=>$valor){
                            $indice1   = $valor->UBIGC_CodDpto;
                            $valor1    = $valor->UBIGC_Descripcion;
                            $arreglo[$indice1] = $valor1;
                    }
            }
            $resultado = $this->html->optionHTML($arreglo,$indDefault,array('00','::Seleccione::'));
            return $resultado;
    }
    public function seleccionar_provincia($departamento,$indDefault=''){
            $array_prov = $this->ubigeo_model->listar_provincias($departamento);
            $arreglo = array();
            if(count($array_prov)>0){
                    foreach($array_prov as $indice=>$valor){
                            $indice1   = $valor->UBIGC_CodProv;
                            $valor1    = $valor->UBIGC_Descripcion;
                            $arreglo[$indice1] = $valor1;
                    }
            }
            $resultado = $this->html->optionHTML($arreglo,$indDefault,array('00','::Seleccione::'));
            return $resultado;
    }
    public function seleccionar_distritos($departamento,$provincia,$indDefault=''){
            $array_dist = $this->ubigeo_model->listar_distritos($departamento,$provincia);
            $arreglo = array();
            if(count($array_dist)>0){
                    foreach($array_dist as $indice=>$valor){
                            $indice1   = $valor->UBIGC_CodDist;
                            $valor1    = $valor->UBIGC_Descripcion;
                            $arreglo[$indice1] = $valor1;
                    }
            }
            $resultado = $this->html->optionHTML($arreglo,$indDefault,array('00','::Seleccione::'));
            return $resultado;
    }
    public function seleccionar_tipodocumento($indDefault=''){
            $array_dist = $this->tipodocumento_model->listar_tipo_documento();
            $arreglo = array();
            if(count($array_dist)>0){
                    foreach($array_dist as $indice=>$valor){
                            $indice1   = $valor->TIPDOCP_Codigo;
                            $valor1    = $valor->TIPOCC_Inciales;
                            $arreglo[$indice1] = $valor1;
                    }
            }
            $resultado = $this->html->optionHTML($arreglo,$indDefault,array('0','::Seleccione::'));
            return $resultado;
    }

    public function ver_persona($persona){

        $datos                = $this->persona_model->obtener_datosPersona($persona);
        $tipo_doc             = $datos[0]->PERSC_TipoDocIdentidad;
        $estado_civil         = $datos[0]->ESTCP_EstadoCivil;
        $nacionalidad         = $datos[0]->NACP_Nacionalidad;
        $nacimiento           = $datos[0]->UBIGP_LugarNacimiento;
        $sexo                 = $datos[0]->PERSC_Sexo;
        $ubigeo_domicilio     = $datos[0]->UBIGP_Domicilio;
        $datos_nacionalidad   = $this->nacionalidad_model->obtener_nacionalidad($nacionalidad);
        $datos_nacimiento     = $this->ubigeo_model->obtener_ubigeo($nacimiento);
        $datos_ubigeoDom_dpto = $this->ubigeo_model->obtener_ubigeo_dpto($ubigeo_domicilio);
        $datos_ubigeoDom_prov = $this->ubigeo_model->obtener_ubigeo_prov($ubigeo_domicilio);
        $datos_ubigeoDom_dist = $this->ubigeo_model->obtener_ubigeo($ubigeo_domicilio);
        $datos_doc            = $this->tipodocumento_model->obtener_tipoDocumento($tipo_doc);
        $datos_estado_civil   = $this->estadocivil_model->obtener_estadoCivil($estado_civil);
        $data['nacionalidad'] = $datos_nacionalidad[0]->NACC_Descripcion;
        $data['nacimiento']   = $datos_nacimiento[0]->UBIGC_Descripcion;
        $data['tipo_doc']     = $datos_doc[0]->TIPOCC_Inciales;
        $data['estado_civil'] = $datos_estado_civil[0]->ESTCC_Descripcion;
        $data['sexo']         = $sexo==0?'MASCULINO':'FEMENINO';
        $data['telefono']     = $datos[0]->PERSC_Telefono;
        $data['movil']        = $datos[0]->PERSC_Movil;
        $data['fax']          = $datos[0]->PERSC_Fax;
        $data['email']        = $datos[0]->PERSC_Email;
        $data['web']          = $datos[0]->PERSC_Web;
        $data['direccion']    = $datos[0]->PERSC_Direccion;
        $data['dpto']         = $datos_ubigeoDom_dpto[0]->UBIGC_Descripcion;
        $data['prov']         = $datos_ubigeoDom_prov[0]->UBIGC_Descripcion;
        $data['dist']         = $datos_ubigeoDom_dist[0]->UBIGC_Descripcion;


        $data['datos']  = $datos;
        $data['titulo'] = "VER PERSONA";

        $this->load->view('maestros/persona_ver',$data);
    }
   
    public function JSON_datos_persona($persona){
        $datos_persona = $this->persona_model->obtener_datosPersona($persona);
        $result=array();
        if(count($datos_persona)>0)
            $result=$datos_persona[0];
        echo json_encode($result);
   }
   
    public function eliminar_pedido(){
        $pedido = $this->input->post('pedido');
        //primero busca si no esta amarrado a un presupuesto
        $presupuesto = $this->presupuesto_model->buscar_presu_x_pedido($pedido);
        if(count($presupuesto) == 0){
            $this->pedido_model->eliminar_pedido($pedido);
            $this->pedido_model->eliminar_producto_pedido2($pedido);
        }else{
            echo "Tiene este pedido amarrados a un presupuesto";
        }
    }
    
    public function obtener_detalle_presupuesto($pedido){
        $detalle_pedido = $this->pedidodetalle_model->listar($pedido);
        $array_detallepedido = array();
        foreach($detalle_pedido as $indice=>$value){
            $pedido     = $value->PEDIP_Codigo;
            $producto   = $value->PROD_Codigo;
            $unidad     = $value->UNDMED_Codigo;
            $cantidad   = $value->PEDIDETC_Cantidad;
            $des_producto = $this->producto_model->obtener_producto($producto);
            $nombre_unidad = $this->unidadmedida_model->obtener($unidad);
            
            $objeto = new stdClass();
            $objeto->cod_pedido     = $pedido;
            $objeto->cod_producto   = $producto;
            $objeto->des_producto   = $des_producto[0]->PROD_Nombre;
            $objeto->unidad         = $unidad;
            $objeto->nombre_unidad  = $nombre_unidad[0]->UNDMED_Simbolo;
            $objeto->cantidad       = $cantidad;
            $objeto->detalle        = $value->PEDIDETP_Detalle;
            $array_detallepedido[] = $objeto;
        }
        $resultado = json_encode($array_detallepedido);
        echo $resultado;
    }

    public function obtener_detalle_pedido($ocompra)
    {
        $datos_detalle_ocompra = $this->pedido_model->obtener_detalle_pedido($ocompra);
        $listado = array();
        if (count($datos_detalle_ocompra) > 0) {
            foreach ($datos_detalle_ocompra as $indice => $valor) {
                $detocompra = $valor->PEDIDETC_Codigo;
                $ocompra = $valor->PEDIP_Codigo;
                $producto = $valor->PROD_Codigo;
                $unidad_medida = ""; #$valor->UNDMED_Codigo;
                $cantidad = $valor->PEDIDETC_Cantidad;
                $costo = ""; #$valor->OCOMDEC_Pu;
                $costoPUconIgv = ""; #$valor->OCOMDEC_Pu_ConIgv;
                $costoTotal = ""; #$valor->OCOMDEC_Total;
                $subTotal = ""; #$valor->OCOMDEC_Subtotal;
                $igvocom = ""; #$valor->OCOMDEC_Igv;
                $igvocom100 = ""; #$valor->OCOMDEC_Igv100;
                $descuento = ""; #$valor->OCOMDEC_Descuento;
                $descuento100 = ""; #$valor->OCOMDEC_Descuento100;
                $flagGenInd = "G"; #$valor->OCOMDEC_GenInd;
                
                $datos_ocompra = $this->pedido_model->obtener_pedido($ocompra);
                /*if ($datos_ocompra[0]->PERSP_Codigo == '') {
                    $proveedor = $datos_ocompra[0]->CLIP_Codigo;
                    $datos_proveedor = $this->cliente_model->obtener($proveedor);
                    $razon_social = $datos_proveedor->nombre;
                    $ruc = $datos_proveedor->ruc;
                } else {
                    $proveedor = $datos_ocompra[0]->PROVP_Codigo;
                    $datos_proveedor = $this->proveedor_model->obtener($proveedor);
                    $razon_social = $datos_proveedor->nombre;
                    $ruc = $datos_proveedor->ruc;
                }*/
                
                $almacen = $datos_ocompra[0]->ALMAP_Codigo;
                $formapago = ""; #$datos_ocompra[0]->FORPAP_Codigo;
                $moned_codigo = ""; #$datos_ocompra[0]->MONED_Codigo;

                #$datos_producto = $this->producto_model->obtener_producto($producto);
                $datos_umedida = is_null($unidad_medida) ? NULL : $this->unidadmedida_model->obtener($unidad_medida);
                $codigo_interno = $valor->PROD_CodigoUsuario; # $datos_producto[0]->PROD_CodigoUsuario;
                $nombre_producto = $valor->PROD_Nombre; # $datos_producto[0]->PROD_Nombre;
                //$flagGenInd = $datos_producto[0]->PROD_GenericoIndividual;
                
                $nombre_unidad = is_null($datos_umedida) ? '' : $datos_umedida[0]->UNDMED_Simbolo;
                $objeto = new stdClass();
                $objeto->OCOMDEP_Codigo = $detocompra;
                $objeto->OCOMP_Codigo = $ocompra;
                $objeto->PROD_Codigo = $producto;
                $objeto->UNDMED_Codigo = $unidad_medida;
                $objeto->MONED_Codigo = $moned_codigo;
                $objeto->OCOMDEC_Cantidad = $cantidad;
                $objeto->OCOMDEC_Pu = $costo;
                $objeto->OCOMDEC_Igv = $igvocom;
                $objeto->OCOMDEC_Igv100 = $igvocom100;
                $objeto->OCOMDEC_Descuento = $descuento;
                $objeto->OCOMDEC_Descuento100 = $descuento100;
                $objeto->OCOMDEC_Pu_ConIgv = $costoPUconIgv;
                $objeto->OCOMDEC_Subtotal = $subTotal;
                $objeto->OCOMDEC_Total = $costoTotal;
                $objeto->OCOMDEC_GenInd = $flagGenInd;
                
                $objeto->PROD_CodigoUsuario = $codigo_interno;
                $objeto->PROD_Nombre = $nombre_producto;
                $objeto->FABRIC_Descripcion = $valor->FABRIC_Descripcion;
                $objeto->UNDMED_Simbolo = $nombre_unidad;
                $objeto->Ruc = $ruc;
                $objeto->RazonSocial = $razon_social;
                $objeto->PROVP_Codigo = $proveedor;
                $objeto->ALMAP_Codigo = $almacen;
                $objeto->FORPAP_Codigo = $formapago;
                $objeto->PROD_GenericoIndividual = $flagGenInd;
                $listado[] = $objeto;
            }
        }
        $resultado = json_encode($listado);
        echo $resultado;
    }
    
    public function obtener_detalle_lista($codigopedido){
            $listado_detalle = $this->pedidodetalle_model->listar($codigopedido);
            $lista_detalles = array();
            if(count($listado_detalle)>0){
                foreach($listado_detalle as $key=>$value){
                    $productocodigo   = $value->PROD_Codigo;
                    $productobusca   = $this->producto_model->obtener_producto($productocodigo);
                    $codigousuario = $productobusca[0]->PROD_CodigoUsuario;
                    $nombre = $productobusca[0]->PROD_Nombre;
                    $cantidad = $value->PEDIDETC_Cantidad;
                    $codigounidad = $value->UNDMED_Codigo;
                    $unidadbusca     = $this->unidadmedida_model->obtener($codigounidad);
                    $unidaddescripcion     = $unidadbusca[0]->UNDMED_Descripcion;
                    $pu_CIGV =  $value->PEDIDETC_PCIGV;
                    $pu_SIGV =$value->PEDIDETC_PSIGV;
                    $descuento = $value->PEDIDETC_Descuento;
                    $descuento100 = $value->PEDIDETC_Descuento100;
                    $PRECIO = $value->PEDIDETC_Precio;
                    $codigo = $value->PEDIDETP_Codigo;
                    $IGV =$value->PEDIDETC_IGV;
                    $IMPORTE = $value->PEDIDETC_Importe;
                    
                    $objeto = new stdClass();
                    $objeto->PROD_Codigo = $productocodigo;
                    $objeto->PROD_CodigoUsuario = $codigousuario;
                    $objeto->PROD_Nombre = $nombre;
                    $objeto->PEDIDETC_Cantidad = $cantidad;
                    $objeto->UNDMED_Descripcion = $unidaddescripcion;
                    $objeto->PEDIDETC_PCIGV = $pu_CIGV;
                    $objeto->PEDIDETC_PSIGV = $pu_SIGV;
                    $objeto->PEDIDETC_Precio = $PRECIO;
                    $objeto->PEDIDETC_Descuento = $descuento;
                    $objeto->PEDIDETC_Descuento100 = $descuento100;
                    $objeto->PEDIDETC_IGV = $IGV;
                    $objeto->PEDIDETP_Codigo = $codigo;
                    $objeto->PEDIDETC_Importe = $IMPORTE;
                    $lista_detalles[] = $objeto;
                }
            }
            return $lista_detalles;
        
    }
    public function contacto(){
        $codigo = $this->input->post('codigoempre');
        $respuesta = $this->pedido_model->contactos($codigo);
            
        echo json_encode($respuesta);
    
    }
    public function obra(){
    
        $codigo = $this->input->post('codigoempre');
        $respuesta = $this->pedido_model->obras($codigo);
            
        echo json_encode($respuesta);
    
    }
    
    public function seleccionar_centrocosto($indDefault=''){
        $array_dist = $this->centrocosto_model->listar_centros_costo();
        $arreglo = array();
        if(count($array_dist)>0){
            foreach($array_dist as $indice=>$valor){
                $indice1   = $valor->CENCOSP_Codigo;
                $valor1    = $valor->CENCOSC_Descripcion;
                $arreglo[$indice1] = $valor1;
            }
        }
        $resultado = $this->html->optionHTML($arreglo,$indDefault,array('0','::Seleccione::'));
        return $resultado;
    }
    
    public function insertar_pedido(){
        $serie = $this->input->post('serie');
        $numero = $this->input->post('numero');
        $fechasistema = $this->input->post('fecha');
        $moneda = $this->input->post('moneda');
        $obra = $this->input->post('obra');
        $cliente = $this->input->post('cliente');
        $contacto = $this->input->post('contacto');
        $ocompra = $this->input->post('idOcompra');
        $igvpp = $this->input->post('igv');
        $importebruto = $this->input->post('importebruto');
        $descuentotal = $this->input->post('descuentotal');
        $vventa = $this->input->post('vventa');
        $igvtotal = $this->input->post('igvtotal');
        $preciototal= $this->input->post('importetotal');
        $descuento100 = $this->input->post('descuento');
        $observacion = $this->input->post('observacion');
        $estado = $this->input->post('estatus');
        $almacen = $this->input->post('almacen');
        $fechaEntregaMin= $this->input->post('fechaEntregaMin');
        $fechaEntregaMax= $this->input->post('fechaEntregaMax');
        $direccion = $this->input->post("direccionCombo");
        $compania = $this->somevar['compania'];
        $prodpeso = $this->input->post('prod_peso');
        $vendedor = $this->input->post('cboVendedor');
        $idpersonavendedor=$this->persona_model->obtenerCodigopersxcoddirectivo($vendedor);
        // Formas de Pago
        $formPagoFP     = $this->input->post('cmbFormasPago');
        $monedaFP       = $this->input->post('cmbMoneda');
        $montoFP        = $this->input->post('monto');
        $categoriaprecio = $this->input->post('TipCli');
        $cmbFormasPago     = $this->input->post('cmbFormasPago');
        $this->session->set_userdata('cmbFormasPago', $cmbFormasPago);

        
        $filterP = new stdClass();
        $filterP->PEDIC_TipoDocume = "V";
        $filterP->PEDIC_Numero = $numero;
        $filterP->PEDIC_Serie = $serie;
        $filterP->PEDIC_FechaSistema = $fechasistema;
        $filterP->MONED_Codigo = $moneda;
        $filterP->PROYP_Codigo = $obra;
        $filterP->CLIP_Codigo = $cliente;
        $filterP->ECONP_Contacto = $contacto;
        $filterP->OCOMP_Codigo = $ocompra;
        $filterP->ALMAP_Codigo = 1;
        $filterP->PEDIC_IGV = $igvpp;
        $filterP->COMPP_Codigo = $compania;
        $filterP->PEDIC_ImporteBruto = $importebruto;
        $filterP->PEDIC_DescuentoTotal = $descuentotal;
        $filterP->PEDIC_Descuento100 = $descuento100;
        $filterP->PEDIC_ValorVenta = $vventa;
        $filterP->PEDIC_IGVTotal = $igvtotal;
        $filterP->PEDIC_PrecioTotal = $preciototal;
        $filterP->PEDIC_FlagEstado = $estado;
        $filterP->PEDIC_EstadoPresupuesto = 1;
        $filterP->PEDIC_Observacion = $observacion;
        $filterP->PEDIC_Direccion = $direccion;
        $filterP->PEDIC_FechaEntregaMin = $fechaEntregaMin;
        $filterP->PEDIC_FechaEntregaMax = $fechaEntregaMax;
        $filterP->PEDIC_Vendedor = $idpersonavendedor;
        $filterP->PEDIC_Tipclip = $categoriaprecio;
        if ( $this->input->post("forma_pago") != "" && $this->input->post("forma_pago") != "0" )
        {
            $f_pago = $this->input->post("forma_pago");
            $fp_monto = $this->input->post("montoFP_default");
            $filterP->FORPAP_Codigo = $f_pago;
            $filterP->FORPAP_Monto = $fp_monto;
        }

        
        $cod_pedido = $this->pedido_model->insertar_pedido($filterP);

        //CUOTAS
        $cuota_fechai = $this->input->post('cuota-fechai');
        $cuota_fechaf = $this->input->post('cuota-fechaf');
        $monto_cuotas = $this->input->post('cuota-monto');

        if (isset($f_pago) && $f_pago != 1) {
            if($monto_cuotas != ""){
                foreach ($monto_cuotas as $indice => $cuota) {
                    $cuotasData = new stdClass();
                    $cuotasData->CUOT_Numero = $indice + 1;
                    $cuotasData->CPP_Codigo = $cod_pedido;
                    $cuotasData->CUOT_Monto = $monto_cuotas[$indice];
                    $cuotasData->CUOT_FechaInicio = $cuota_fechai[$indice];
                    $cuotasData->CUOT_Fecha = $cuota_fechaf[$indice];
                    $cuotasData->CUOT_FlagFisica = 0;
                    $cuotasData->CUOT_FlagEstado = 1;
                    $cuotasData->CUOT_FlagPagado = 0;
                    $cuotasData->CUOT_UsuarioRegistro = $_SESSION['user'];
                    $cuotasData->CUOT_TipoCuenta = ($tipo_oper == "V") ? 1 : 2;
                    
                    $this->cuota_model->registrar($cuotasData);
                }
            }
        }

        //FORMAS DE PAGO MULTIPLE

        if (is_array($formPagoFP) and count($formPagoFP) > 0)
        {
            foreach ($formPagoFP as $key => $fpago)
            {
                $stdFormasPago                  = new StdClass();
                $stdFormasPago->PEDIP_Codigo      = $cod_pedido;
                $stdFormasPago->FORPAP_Codigo   = $fpago;
                $stdFormasPago->MONED_Codigo    = $monedaFP[$key];
                $stdFormasPago->monto           = $montoFP[$key];
                $stdFormasPago->pedi_flag_FechaRegistro = date('Y-m-d h:i:s');

                if (!empty($montoFP[$key]))
                    $this->comprobante_formapago_model->insertarxPedido($stdFormasPago);
            }
        }

        
        if ($cod_pedido == NULL)
            exit('{"result":"error3", "msj":"No se pudo completar la operación. Intentelo Nuevamente.\nSi el inconveniente persiste comuniquese con el administrador."}');
        
        if ( $ocompra != NULL && $ocompra != "" ){
            $filter = new stdClass();
            $filter->PEDIP_Codigo = $cod_pedido;
            $this->ocompra_model->modificar_ocompra($codigoOC, $filter);
        }

        $almprod = $this->input->post('almacenProducto');
        $prodcodigo = $this->input->post('prodcodigo');
        $prodcantidad = $this->input->post('prodcantidad');
        $produnidad = $this->input->post('produnidad');
        $ppcigv = $this->input->post('prodpu_conigv');
        $ppsigv = $this->input->post('prodpu');
        $precio = $this->input->post('prodprecio');
        $proddescuento100 = $this->input->post('proddescuento100');
        $proddescuento = $this->input->post('proddescuento');
        $igv =  $this->input->post('prodigv');
        $importe = $this->input->post('prodimporte');
        
        
    
        $compania = $this->somevar['compania'];
        $configuracion_datos = $this->configuracion_model->obtener_numero_documento($compania, 22);
        

        $numero_predt = $this->pedido_model->ultimo_numero();
        $numero = $numero_predt[0]->PEDIC_Numero;
        $num = $configuracion_datos[0]->CONFIC_Numero + 1;
        
        $filter->PEDIC_Numero = $numero + 1;
        $numero = $this->input->post('numero');
         $filter->PEDIC_Numero = $this->input->post('numero');
         
         if ($this->input->post('serie') != '' && $this->input->post('serie') != '0') {
            $filter->PRESUC_Serie = $this->input->post('serie');
         }

        if ($this->input->post('serie') != '' && $this->input->post('serie') != '0') {
            $filter2->PEDIP_Codigo = $this->input->post('guia');
        }
         
        $this->configuracion_model->modificar_configuracion($compania, 22, $numero);
        $fecha = date('Y-m-d h:i:s');
        if(count($prodcodigo) > 0){
            foreach($prodcodigo as $indice => $value){
                $dataprod = $this->producto_model->getProducto($value);

                $filterDP = new stdClass();

                $filterDP->PEDIP_Codigo = $cod_pedido;
                $filterDP->PROD_Codigo = $prodcodigo[$indice];
                $filterDP->PROD_Nombre = $dataprod[0]->PROD_Nombre;
                $filterDP->UNDMED_Codigo = $produnidad[$indice];
                $filterDP->PEDIDETC_Almacen = $almprod[$indice];
                $filterDP->PEDIDETC_Cantidad = $prodcantidad[$indice];
                $filterDP->PEDIDETC_PCIGV = $ppcigv[$indice];
                $filterDP->PEDIDETC_PSIGV = $ppsigv[$indice];
                $filterDP->PEDIDETC_Precio  = $precio[$indice];
                $filterDP->PEDIDETC_Descuento100 = $proddescuento100[$indice];
                $filterDP->PEDIDETC_Descuento = $proddescuento[$indice];
                $filterDP->PEDIDETC_IGV = $igv[$indice];
                $filterDP->PEDIP_Peso = $prodpeso[$indice];

                $filterDP->PEDIDETC_Importe = $importe[$indice];
                $filterDP->PEDIDETC_FechaRegistro =   $fecha;
                
                $this->pedidodetalle_model->insertar_varios($filterDP);
                $this->almacenproducto_model->colocar_stock_comprometido($almprod[$indice],$prodcodigo[$indice],$prodcantidad[$indice],$precio[$indice]);
            }
            $this->lib_props->sendMail(116, $cod_pedido, NULL, NULL, "PD"); # MENU 116 = Producción
        }

        exit('{"result":"ok", "codigo":"' . $cod_pedido . '"}');
    }
    
    public function obtener_persona($persona)
    {
        $datos_persona = $this->persona_model->obtener_datosPersona($persona);
        echo json_encode($datos_persona);
    }
    
    public function buscar_personas($j='0'){
        $filter = new stdClass();
        $filter->PERSC_NumeroDocIdentidad = $this->input->post('txtNumDoc');;
        $filter->nombre = $this->input->post('txtNombre');
        $filter->PERSC_Telefono = $this->input->post('txtTelefono');
    
        $data['numdoc']    = $filter->PERSC_NumeroDocIdentidad;
        $data['nombre']    = $filter->nombre;
        $data['telefono']  = $filter->PERSC_Telefono;
        $data['titulo_tabla']    = "RESULTADO DE BÚSQUEDA DE PERSONAS";
    
        $data['registros']  = count($this->persona_model->buscar_personas($filter));
        $data['action'] = base_url()."index.php/maestros/persona/buscar_personas";
        $conf['base_url'] = site_url('maestros/persona/buscar_personas/');
        $conf['total_rows'] = $data['registros'];
        $conf['per_page']   = 10;
        $conf['num_links']  = 3;
        $conf['next_link'] = "&gt;";
        $conf['prev_link'] = "&lt;";
        $conf['first_link'] = "&lt;&lt;";
        $conf['last_link']  = "&gt;&gt;";
        $conf['uri_segment'] = 4;
        $this->pagination->initialize($conf);
        $data['paginacion'] = $this->pagination->create_links();
        $listado_personas = $this->persona_model->buscar_personas($filter, $conf['per_page'],$j);
        $item            = $j+1;
        $lista           = array();
        if(count($listado_personas)>0){
            foreach($listado_personas as $indice=>$valor){
                $persona   = $valor->PERSP_Codigo;
                $ruc            = $valor->PERSC_NumeroDocIdentidad;
                $nombres   = $valor->PERSC_Nombre;
                $telefono       = $valor->PERSC_Telefono;
                $movil          = $valor->PERSC_Movil;
                $editar         = "<a href='#' onclick='editar_persona(".$persona.")'><img src='".base_url()."images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
                $ver            = "<a href='#' onclick='ver_persona(".$persona.")'><img src='".base_url()."images/ver.png' width='16' height='16' border='0' title='Modificar'></a>";
                $eliminar       = "<a href='#' onclick='eliminar_persona(".$persona.")'><img src='".base_url()."images/eliminar.png' width='16' height='16' border='0' title='Modificar'></a>";
                $lista[]        = array($item,$ruc,$nombres,$telefono,$movil,$editar,$ver,$eliminar);
                $item++;
            }
        }
        $data['lista'] = $lista;
        $this->layout->view("maestros/persona_index",$data);
    
    }
    public function getOrderNumero($numero){
       $dato ="";
       $cantidad=strlen($numero);
    
       if($cantidad==1){
        $dato ="00000$numero";
       }
       if($cantidad==2){
        $dato ="0000$numero";
       }
       if($cantidad==3){
        $dato ="000$numero";
       }
       if($cantidad==4){
        $dato= "00$numero";
       }
       if($cantidad==5){
        $dato ="0$numero";
       }
       if($cantidad==6){
        $dato ="$numero";
       }
       return $dato;
    }
    public function getOrderSerie($numero){
       $dato ="";
       $cantidad=strlen($numero);
    
       if($cantidad==1){
        $dato ="00$numero";
       }
       if($cantidad==2){
        $dato ="0$numero";
       }
       if($cantidad==3){
        $dato ="$numero";
       }
       return $dato;
    }
    
    
    public function obtenercontacto_obra(){
        
        $codigoobra = $this->input->post('codigo');
        $result = array();
        
        if($codigoobra!=null && count(trim($codigoobra))>0){
            $obten_cont = $this->proyecto_model->obtenerContacto($codigoobra);
            if($obten_cont !=null && count($obten_cont)){
                foreach ($obten_cont as $indice => $valor){
        
                    $codigo = $valor->EMPRP_Codigo;
                    $nombre = $valor->EMPRC_RazonSocial;

                    $result[] = array("codigo" => $codigo , "nombre" => $nombre  );
        
                }
            }
        }
        echo json_encode($result);
        
        
    }
    public function getOrderNumeroSerie($numero) {
        $cantidad = strlen($numero);
        if ($cantidad == 1) {
            $dato = "00000$numero";
        }
        if ($cantidad == 2) {
            $dato = "0000$numero";
        }
        if ($cantidad == 3) {
            $dato = "000$numero";
        }
        if ($cantidad == 4) {
            $dato = "00$numero";
        }
        if ($cantidad == 5) {
            $dato = "0$numero";
        }
        if ($cantidad == 6) {
            $dato = "$numero";
        }
        return $dato;
    }
    
    public function registro_pedido_pdf($fechai, $fechaf, $numero, $cliente)
    {
    
        $fi = explode("-",$fechai);
        $ff = explode("-",$fechaf);
        $fechain = $fi[2].'/'.$fi[1].'/'.$fi[0];
        $fechafin = $ff[2].'/'.$ff[1].'/'.$ff[0];
        if($fechain=="//" || $fechafin=="//"){
            $fechain = "--";
            $fechafin = "--";
        }
    
        $this->load->library('cezpdf');
        $this->load->helper('pdf_helper');
        //prep_pdf();
        $this->cezpdf = new Cezpdf('a4');
        $datacreator = array(
                'Title' => 'Estadillo de ',
                'Name' => 'Estadillo de ',
                'Author' => 'Vicente Producciones',
                'Subject' => 'PDF con Tablas',
                'Creator' => 'info@vicenteproducciones.com',
                'Producer' => 'http://www.vicenteproducciones.com'
        );
    
        $this->cezpdf->addInfo($datacreator);
        $this->cezpdf->selectFont(APPPATH . 'libraries/fonts/Helvetica.afm');
        $delta = 20;
    
    
        
        $titulo="RELACION DE PEDIDOS";
        $fonttitle = array("leading" => 30, "left" => 150);
        $fontespacio = array("leading" => 10, "left" => 100);
        $fontdataright = array("leading" => 10, "left" => 370);
        
        $hoy = date("d-m-Y");
        $this->cezpdf->ezText($titulo, 17, $fonttitle);
        $this->cezpdf->ezText("", 17, $fontespacio);
        $this->cezpdf->ezText("FECHA DE REPORTE: ".$hoy, 8, $fontdataright);
        $this->cezpdf->ezText("", 17, $fontespacio);
    
    
        $db_data = array();
    
    
        $listado_pedido = $this->pedido_model->listar_pedido_pdf($fechain, $fechafin, $numero, $cliente);
    
    
        if (count($listado_pedido) > 0) {
            foreach ($listado_pedido as $indice => $valor) {
                $fecha = $valor->FECHA;
            
                
                $serie =  $this->getOrderSerie($valor->PEDIC_Serie);
                $numero =  $this->getOrderNumero($valor->PEDIC_Numero);
                //cliente
                $codigocliente   = $valor->CLIP_Codigo;//
                $buscarcliente = $this->cliente_model->obtener_datosCliente($codigocliente);
                foreach ($buscarcliente as $indice2=>$valor2){
                    $tipopersona = $valor2->CLIC_TipoPersona;
                
                    if($tipopersona == 1){
                        $codigoempresa = $valor2->EMPRP_Codigo;
                        $buscarempresa = $this->cliente_model->obtener_datosCliente2($codigoempresa);
                        foreach ($buscarempresa as $indice3 => $valor3){
                            $nombrededos = $valor3->EMPRC_RazonSocial;
                        }
                    }else{
                        $codigopersona = $valor2->PERSP_Codigo;
                        $buscarpersona = $this->cliente_model->obtener_datosCliente3($codigopersona);
                        foreach ($buscarpersona as $indice4 => $valor4){
                            $nombre = $valor4->PERSC_Nombre;
                            $ap =$valor4->PERSC_ApellidoPaterno;
                            $am =$valor4->PERSC_ApellidoMaterno;
                            $nombrededos = $nombre." ".$ap." ".$am;
                        }
                    }
                }
                
                //fin cliente
                
                $numeropresupuesto = $valor->PRESUC_Serie."-".$valor->PRESUC_Numero;//
                
                $total = $valor->MONED_Simbolo.$valor->PEDIC_PrecioTotal;
                $Stotal+= $valor->PEDIC_PrecioTotal;
    
                $db_data[] = array(
                        'cols1' => $indice + 1,
                        'cols2' => $fecha,
                        'cols3' => $serie,//
                        'cols4' => $numero,//
                        'cols5' => $nombrededos,//
                        'cols6' => $numeropresupuesto,//presu
                        'cols7' => $total
                );
            }
        }
    
    
    
    
        $col_names = array(
                'cols1' => '<b>ITEM</b>',
                'cols2' => '<b>FECHA</b>',
                'cols3' => '<b>SERIE</b>',
                'cols4' => '<b>NUMERO</b>',
                'cols5' => '<b>RAZON SOCIAL</b>',
                'cols6' => '<b>PRESUPUESTO</b>',
                'cols7' => '<b>TOTAL</b>'
        );
    
        $this->cezpdf->ezTable($db_data, $col_names, '', array(
                'width' => 600,
                'showLines' => 1,
                'shaded' => 1,
                'showHeadings' => 1,
                'xPos' => 'center',
                'fontSize' => 8,
                'cols' => array(
                        'cols1' => array('width' => 30, 'justification' => 'center'),
                        'cols2' => array('width' => 60, 'justification' => 'center'),
                        'cols3' => array('width' => 40, 'justification' => 'center'),
                        'cols4' => array('width' => 50, 'justification' => 'center'),
                        'cols5' => array('width' => 160, 'justification' => 'center'),
                        'cols6' => array('width' => 75, 'justification' => 'center'),
                        'cols7' => array('width' => 60, 'justification' => 'center')
                )
        ));
        $this->cezpdf->ezText('TOTAL:   '. $valor->MONED_Simbolo.number_format($Stotal,2), '8', array("leading" => 15, 'left' => 410));
    
    
        $cabecera = array('Content-Type' => 'application/pdf', 'Content-Disposition' => $codificacion . '.pdf', 'Expires' => '0', 'Pragma' => 'cache', 'Cache-Control' => 'private');
    
        ob_end_clean();
    
        $this->cezpdf->ezStream($cabecera);
    }
    
    public function pedido_pdf($codigo = null, $flagPdf = 1, $fechaini= null, $fechafin = null,  $enviarcorreo = false){
        
        $this->lib_props->pedido_pdf($codigo, $flagPdf, $fechaini, $fechafin, $enviarcorreo);
        return NULL;

        /*$this->load->model('almacen/almacen_model');

        $datos_pedido = $this->pedido_model->obtener_pedido($codigo);
       
        $codigopedido = $datos_pedido[0]->PEDIP_Codigo;
        $numero = $datos_pedido[0]->PEDIC_Numero;
        $serie = $datos_pedido[0]->PEDIC_Serie;
        $flagPedido = $datos_pedido[0]->PEDIC_FlagEstado;
        $compania = $datos_pedido[0]->COMPP_Codigo;
        $observacion = $datos_pedido[0]->PEDIC_Observacion;
        $estado = $datos_pedido[0]->PEDIC_FlagEstado;
        $fechaReg = explode(" ",$datos_pedido[0]->PEDIC_FechaRegistro);
        $fecha = mysql_to_human($fechaReg[0]);

        $ordencompra = $datos_pedido[0]->OCOMP_Codigo;

        $cliente = $datos_pedido[0]->CLIP_Codigo;
        $personal = $datos_pedido[0]->PERSP_Codigo;
        $forma_pago = $datos_pedido[0]->FORPAP_Codigo;
        $moneda = $datos_pedido[0]->MONED_Codigo;

        $nFechaEntrega = explode( '/', $fecha );
        $fecha_entrega = $nFechaEntrega[0]." de ".ucfirst( strtolower($this->lib_props->mesesEs($nFechaEntrega[1])) )." del ".$nFechaEntrega[2];

        $fecha_entrega = ($fechaEntrega != "") ? $fecha_entrega : "";

        $nombre_almacen = '';
        #if ($almacen != '') {
        #    $datos_almacen = $this->almacen_model->obtener($almacen);
        #    $nombre_almacen = $datos_almacen[0]->ALMAC_Descripcion;
        #}

        $nombre_formapago = '';
        #if ($formapago != '') {
        #    $datos_formapago = $this->formapago_model->obtener($formapago);
        #    $nombre_formapago = $datos_formapago[0]->FORPAC_Descripcion;
        #}

        $datos_moneda = $this->moneda_model->obtener($moneda);
        $simbolo_moneda = $datos_moneda[0]->MONED_Simbolo;
        $moneda_nombre = (count($datos_moneda) > 0 ? $datos_moneda[0]->MONED_Descripcion : 'Soles');

        #$arrfecha = explode(" ", $datos_ocompra[0]->OCOMC_Fecha);
        #$nFecha = explode( '/', mysql_to_human($arrfecha[0]) );
        #$fecha = $nFecha[0]." de ".ucfirst( strtolower($this->lib_props->mesesEs($nFecha[1])) )." del ".$nFecha[2]; // strlower -> todo a minuscula, ucfirst primer caracter a mayuscula
        #$flagIngreso = $datos_ocompra[0]->OCOMC_FlagIngreso;

        #if ($tipo_oper == 'C') {
        #    $datos_proveedor = $this->proveedor_model->obtener($proveedor);
        #    $nombres = $datos_proveedor->nombre;
        #    $ruc = $datos_proveedor->ruc;
        #    $telefono = $datos_proveedor->telefono;
        #    $direccion = $datos_proveedor->direccion;
        #    $fax = $datos_proveedor->fax;
        #} else {
            if ($cliente != NULL && $cliente != ""){
                $datos_cliente = $this->cliente_model->obtener($cliente);
                $nombres = $datos_cliente->nombre;
                $ruc = $datos_cliente->ruc;
                $telefono = $datos_cliente->telefono;
                $direccion = $datos_cliente->direccion;
                $fax = $datos_cliente->fax;
            }
            else{
                $datos_persona = $this->persona_model->obtener_datosPersona($personal);
                $nombres = $datos_persona[0]->PERSC_Nombre;
                $ruc = $datos_persona[0]->PERSC_NumeroDocIdentidad." / ".$datos_persona[0]->PERSC_Ruc;
                $telefono = $datos_persona[0]->PERSC_Telefono." / ".$datos_persona[0]->PERSC_Movil;
                $direccion = $datos_persona[0]->PERSC_Direccion;
                $fax = $datos_persona[0]->PERSC_Fax;
            }
        #}

        $comppName = $this->pedido_model->nameEstablecimiento($compania);
        $compp = $comppName[0]->EESTABC_Descripcion;

        #$contacto = $this->persona_model->obtener_datosPersona($contacto);
        
        $this->load->library("tcpdf");
        $medidas = "a4"; // a4 - carta
        $this->pdf = new pdfGeneral('P', 'mm', $medidas, true, 'UTF-8', false);
        $this->pdf->SetMargins(10, 40, 10); // Cada 10 es 1cm - Como es hoja estoy tratando las medidad en cm -> Rawil
        $this->pdf->SetTitle('PEDIDO '.$serie.'-'.$numero);
        $this->pdf->SetFont('times', '', 8);
        if ($flagPdf == 1)
            $this->pdf->setPrintHeader(true);
        else
            $this->pdf->setPrintHeader(false);

        $this->pdf->setPrintFooter(false);

        $this->pdf->SetAutoPageBreak(true, 20);
        $this->pdf->AddPage();
        #$this->pdf->Footer( $miPersonal );
        
        $detalles_pedido = $this->pedidodetalle_model->listar($codigo);
            $detaProductos = "";
            $j = 1;
            foreach ($detalles_pedido as $indice => $valor) {
                $listaProductos = $this->producto_model->obtener_producto($valor->PROD_Codigo);
                $unidadMedida = $this->unidadmedida_model->obtener($valor->UNDMED_Codigo);
                $medidaDetalle = "";
                $medidaDetalle = ($unidadMedida[0]->UNDMED_Simbolo != "") ? $unidadMedida[0]->UNDMED_Simbolo : "NIU";

                $bgcolor = ( $indice % 2 == 0 ) ? "#FFFFFF" : "#F1F1F1";

                    $detaProductos = $detaProductos. '
                    <tr bgcolor="'.$bgcolor.'">
                        <td style="text-align:center;">'.$j.'</td>
                        <td style="text-align:center;">'.$listaProductos[0]->PROD_CodigoUsuario.'</td>
                        <td style="text-align:left;">'.$listaProductos[0]->PROD_Nombre.'</td>
                        <td style="text-align:center;">'.$medidaDetalle.'</td>
                        <td style="text-align:right;">'.$valor->PEDIDETC_Cantidad.'</td>
                    </tr>';
                $j++;
            }


        $cotizacionHTML = '
                        <table style="text-align:center;" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="width:11cm;"></td>
                                <td style="width:3.5cm; font-weight:normal; font-style:italic; text-align:right; font-size:10pt;">Pedido No.:</td>
                                <td style="width:3.5cm; font-weight:bold; font-size:14pt; color:#35ae53;">'.$this->lib_props->getOrderNumeroSerie($serie).' - '.$this->lib_props->getOrderNumeroSerie($numero).'</td>
                            </tr>
                        </table>
                        ';
        $this->pdf->writeHTML($cotizacionHTML,false,false,true,'');

        $tpCliente = ($tipo_oper == "V") ? "Cliente" : "Cliente";
        $clienteHTML = '<table style="font-size:8pt;" cellpadding="0.1cm" border="0">
                            <tr>
                                <td bgcolor="#F1F1F1" style="width:9cm;">'.$tpCliente.'</td>
                            </tr>
                            <tr>
                                <td style="width:1.5cm; font-style:italic;">RUC:</td>
                                <td style="text-indent:0.1cm;">'.$ruc.'</td>
                            </tr>
                            <tr>
                                <td style="width:1.5cm; font-style:italic;">Nombre:</td>
                                <td style="text-indent:0.1cm; text-align:justification">'.$nombres.'</td>
                            </tr>
                            <tr> 
                                <td style="width:1.5cm; font-style:italic;">Direccion:</td>
                                <td style="text-indent:0.1cm; text-align:justification">'.$direccion.'</td>
                            </tr>
                        </table><table style="font-size:8pt;" border="0">
                            <tr>
                                <td style="width:7cm;"><table cellpadding="0.1cm" border="0">
                                        <tr> 
                                            <td style="width:1.5cm; font-style:italic; text-indent:-0.1cm;">Telefono:</td>
                                            <td style="text-indent:0.1cm; text-align:justification">'.$telefono.'</td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width:12cm;"><table cellpadding="0.1cm" border="0">
                                        <tr>
                                            <td style="text-align:left; font-style:italic;">Fecha de Elaboracion:</td>
                                            <td style="text-align:left; font-style:italic;"><span style="font-weight:normal"></span></td>
                                        </tr>
                                        <tr>
                                            <td style="font-style:italic; text-align:left;">'.$fecha_entrega.'</td>
                                            <td style="font-style:italic; text-align:left;"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>';

        $this->pdf->writeHTML($clienteHTML,true,false,true,'');

        $productoHTML = '
                <table cellpadding="0.05cm" style="font-size:8pt;">
                    <tr bgcolor="#F1F1F1" style="font-size:9pt;">
                        <th style="font-style:italic; font-weight:0.5cm; text-align:center; width:1.5cm;">Item</th>
                        <th style="font-style:italic; font-weight:0.5cm; text-align:center; width:2.5cm;">Codigo</th>
                        <th style="font-style:italic; font-weight:0.5cm; text-align:center; width:9.5cm;">Descripcion</th>
                        <th style="font-style:italic; font-weight:0.5cm; text-align:right; width:3.6cm;">Unidad de Medida</th>
                        <th style="font-style:italic; font-weight:0.5cm; text-align:right; width:1.8cm;">Cantidad</th>
                    </tr>
                    '.$detaProductos.'
                </table>';
        $this->pdf->writeHTML($productoHTML,true,false,true,'');
                    
        $nameFile = "Pedido -".$this->lib_props->getOrderNumeroSerie($serie)."-".$this->lib_props->getOrderNumeroSerie($numero)." ".$fecha." ".$nombres.".pdf";

        if ($enviarcorreo == false)
            $this->pdf->Output($nameFile, 'I');
        else
            return $this->pdf->Output($nameFile, 'S');*/
    }

    public function ventana_muestra_pedido($tipo_oper, $codigo = '', $formato = 'SELECT_ITEM', $docu_orig = '', $almacen = "", $comprobante = '', $ventana = ''){
        // $formato: SELECT_ITEM, SELECT_HEADER, $docu_orig: DOCUMENTO QUE SOLICITA LA REFERENCIA, FACTURA, GUIA DE REMISION, ETC
        $cliente = '';
        $nombre_cliente = '';
        $ruc_cliente = '';
        $proveedor = '';
        $nombre_proveedor = '';
        $ruc_proveedor = '';
        /*if ($tipo_oper == 'V') {
            $cliente = $codigo;
            $datos_cliente = $this->cliente_model->obtener($cliente);
            if ($datos_cliente) {
                $nombre_cliente = $datos_cliente->nombre;
                $ruc_cliente = $datos_cliente->ruc;
            }
            $filter = new stdClass();
            $filter->cliente = $cliente;
        } else {
            $proveedor = $codigo;
            $datos_proveedor = $this->proveedor_model->obtener($proveedor);
            if ($datos_proveedor) {
                $nombre_proveedor = $datos_proveedor->nombre;
                $ruc_proveedor = $datos_proveedor->ruc;
            }
            $filter = new stdClass();
            $filter->proveedor = $proveedor;
        }*/


        $lista_comprobante = $this->pedido_model->listar_pedido_asoc();
        $lista = array();
        foreach ($lista_comprobante as $indice => $value) {
            $ver = "<a href='javascript:;' onclick='ver_detalle_pedido(\"$value->PEDIP_Codigo\")'><img src='" . base_url() . "images/ver.png' width='16' height='16' border='0' title='Ver Detalles'></a>";

            #if ($formato == 'SELECT_HEADER') {
                    $select = "<a href='javascript:;' onclick='seleccionar_pedido(\"$value->PEDIP_Codigo\",\"$value->PEDIC_Serie\",\"$value->PEDIC_Numero\")'><img src='" . base_url() . "images/ir.png' width='16' height='16' border='0' title='Seleccionar Pedido'></a>";
            #}
            $fecha = explode(" ",$value->PEDIC_FechaRegistro);
            $lista[] = array(mysql_to_human($fecha[0]), $value->PEDIC_Serie, $value->PEDIC_Numero, "", $value->establecimiento, "", $ver, $select);
        }

        $data['lista'] = $lista;
        $data['cliente'] = "";
        $data['nombre_cliente'] = $value->establecimiento;
        $data['ruc_cliente'] = "";
        $data['proveedor'] = "";
        $data['almacen'] = $almacen;
        $data['comprobante'] = $comprobante;
        $data['tipo_oper'] = $tipo_oper;
        $data['docu_orig'] = $docu_orig;
        $data['formato'] = $formato;

            $data['form_open'] = form_open(base_url() . "index.php/compras/pedido/ventana_muestra_pedido", array("name" => "frmPedido", "id" => "frmPedido"));
            $data['form_close'] = form_close();
            $data['form_hidden'] = form_hidden(array("base_url" => base_url(), "docu_orig" => $docu_orig, "formato" => $formato));
            $this->load->view('compras/ventana_muestra_pedido', $data);
    }

    public function obtener_pedido(){
        $codigoPedido      = $this->input->post('codigoPedido');
        //$session = $this->somevar['temp_session'];

        $result = array();
        $data = $this->pedido_model->obtener_pedido_pedidodetalle($codigoPedido);

        if (count($data)>0) {
            $result = array(
                "message" => "1",
                "datos" => $data
            );

        }else{
            $result = array(
                "message" => "0",
                "datos"    => ""
            );
        }

        echo json_encode($result);
    }
}

?>
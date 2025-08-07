<?php
class Ruta extends Controller{

    private $compania;
    private $usuario;
    private $url;

    public function __construct(){
        parent::__construct();
        $this->load->helper('form');

        $this->load->library('layout','layout');
        $this->load->library('form_validation');
        $this->load->library('lib_props');
        
        $this->load->model('maestros/ruta_model');

        $this->compania = $this->session->userdata("compania");
        $this->usuario = $this->session->userdata("user");
        $this->url = base_url();
    }

    public function index(){
        $this->rutas();
    }


    public function rutas( $j = "" ){
        $data['registros']  = 0;
        $conf['base_url']   = $this->url;

        $data['titulo']    = "RELACIÓN DE TRANSPORTE";
        $data['titulo_busqueda'] = "BUSCAR TRANSPORTE";
        $this->layout->view('maestros/ruta_index',$data);
    }

    public function datatable_ruta(){

        
        $filter = new stdClass();
        $filter->start = $this->input->post("start");
        $filter->length = $this->input->post("length");
        $filter->search = $this->input->post("search")["value"];
        $item = ($this->input->post("start") != "") ? $this->input->post("start") : 0;
        $filter->nombre = $this->input->post('nombre');

        $rutaInfo = $this->ruta_model->getRutas($filter);
        $lista = array();

        
        if (count($rutaInfo) > 0) {
            foreach ($rutaInfo as $indice => $valor) {

                $btn_modal = "<button type='button' onclick='editar($valor->COD_Ruta)' class='btn btn-default'>
                                <img src='".$this->url."/images/modificar.png' class='image-size-1b'>
                             </button>";
                // $btn_borrar = "<button type='button' onclick='deshabilitar($valor->CARGP_Codigo)' class='btn btn-default'>
                //                 <img src='".$this->url."/images/documento-delete.png' class='image-size-1b'>
                //             </button>";
                $lista[] = array(
                                    0 => $indice + 1,
                                    1 => $valor->Nombre_Ruta,
                                    2 => $valor->Ruc_Empresa,
                                    3 => $valor->Nombre_Empresa,
                                    4 => $valor->Nombre_Conductor,
                                    5 => $valor->Licencia,
                                    6 => $valor->Placa,
                                    7 => $valor->Marca,
                                    8 => $valor->Certificado,
                                    9 => $valor->MTC,
                                    10 => $btn_modal
                                );
            }
        }

        unset($filter->start);
        unset($filter->length);

        $json = array(
                            "draw"            => intval( $this->input->post('draw') ),
                            "recordsTotal"    => count(count($this->ruta_model->getRutas($filter))),
                            "recordsFiltered" => intval(count($rutaInfo)),
                            "data"            => $lista
                    );

        echo json_encode($json);
    }

    public function getRuta(){

        $codigo = $this->input->post("ruta");

        $cargoInfo = $this->ruta_model->getRuta($codigo);
        $lista = array();
        
        if ( $cargoInfo != NULL ){
            foreach ($cargoInfo as $indice => $val) {
                $lista = array(
                                    "Nombre_Ruta"      			=> $val->Nombre_Ruta,
                                    "Ruc_Empresa"      			=> $val->Ruc_Empresa,
                                    "Nombre_Empresa"   			=> $val->Nombre_Empresa,
                                    "Nombre_Conductor" 			=> $val->Nombre_Conductor,
                                    "Licencia"         			=> $val->Licencia,
                                    "Placa"            			=> $val->Placa,
                                    "Marca"            			=> $val->Marca,
                                    "Certificado"      			=> $val->Certificado,
                                    "MTC"              			=> $val->MTC,
                                    "Dni_Conductor"    			=> $val->Dni_Conductor,
                                    "Apellido_Conductor"    => $val->Apellido_Conductor,
                                    "COD_Ruta"    					=> $val->COD_Ruta
                                );
            }

            $json = array("match" => true, "info" => $lista);
        }
        else
            $json = array("match" => false, "info" => "");

        echo json_encode($json);
    }

    public function guardar_registro(){

    	$ruta = $this->input->post("ruta");
    	$ruc = $this->input->post("ruc");
    	$nombre = $this->input->post("nombre");
    	$nombre_conductor = $this->input->post("nombre_conductor");
    	$apellido_conductor = $this->input->post("apellido_conductor");
    	$dni_conductor = $this->input->post("dni_conductor");
    	$licencia = $this->input->post("licencia");
    	$placa = $this->input->post("placa");
    	$marca = $this->input->post("marca");
    	$certificado = $this->input->post("certificado");
    	$mtc = $this->input->post("mtc");
    	$nombre_ruta = $this->input->post("nombre_ruta");



  	  $filter = new stdClass();
      $filter->Ruc_Empresa          = $ruc;
      $filter->Nombre_Ruta          = $nombre_ruta;
      $filter->Nombre_Empresa       = $nombre;
      $filter->Nombre_Conductor     = $nombre_conductor;
      $filter->Apellido_Conductor   = $apellido_conductor;
      $filter->Dni_Conductor        = $dni_conductor;
      $filter->Licencia             = $licencia;
      $filter->Placa                = $placa;
      $filter->Marca                = $marca;
      $filter->Certificado          = $certificado;
      $filter->MTC                  = $mtc;
      $filter->Estado               = 1;

      if ($ruta>0) 
      {
      	$result = $this->ruta_model->actualizar($ruta, $filter);
      }
      else
      {
      	 $result = $this->ruta_model->insertar($filter);
      }

    }

    // public function deshabilitar_cargo(){

    //     $cargo = $this->input->post("cargo");

    //     $filter = new stdClass();
    //     $filter->CARGC_FlagEstado  = "0";

    //     if ($cargo != "")
    //         $result = $this->cargo_model->actualizar($cargo, $filter);

    //     if ($result)
    //         $json = array("result" => "success");
    //     else
    //         $json = array("result" => "error");
        
    //     echo json_encode($json);
    // }
    



}

?>
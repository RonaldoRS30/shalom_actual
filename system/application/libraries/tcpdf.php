<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once APPPATH."/third_party/TCPDF/tcpdf.php";
 
class pdf extends TCPDF {
    public function __construct(){
        parent::__construct(); 
    }

    public function Header( $flagPdf = 1 ){
        $this->Image("images/cabeceras/au.jpg", 10, 15, 40, 30, '', '', '', true, 300, '', false, false, 0);
    }
}

class pdfGeneral extends TCPDF {

    protected $ci;
    private $ruc;
    private $doc;
    private $RazonSocial;
    private $serie;
    private $numero;
    private $fondo;
    private $compania;
    private $empresa;
    private $usuario;
    private $persona;

    public function __construct(){
        parent::__construct();
        $this->ci =& get_instance();
        $this->compania = $_SESSION["compania"];
        $this->empresa = $_SESSION["empresa"];
        $this->usuario = $_SESSION["usuario"];
        $this->persona = $_SESSION["persona"];
        $this->fondo = "images/img_db/comprobante_general_".$this->compania.".jpg";
    }

    public function Header( $flagPdf = 1 ){
        if ($flagPdf == 1){
            $this->SetAutoPageBreak(false, 0);
            //$this->Image($this->fondo, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
            $this->SetAutoPageBreak(true, 40);
        }
        
        $this->printHeaderData();
    }

    public function settingHeaderData($filter,$doc){
        $this->ruc      = $filter->ruc;
        $this->doc      = $doc;
        $this->serie    = $filter->serie;
        $this->numero   = $filter->numero;
        $this->RazonSocial  = $filter->RazonSocial;
        $this->direccion    = $filter->direccion;
        $this->ubigeo       = $filter->ubigeo;

    }

    public function printHeaderData(){
        
        $posY = 15;
        $posX = 139;
        $this->RoundedRect($posX, $posY, 60, 35, 1.50, '1111', '');
        $this->SetY($posY + 4);
        $this->SetX($posX);

        $rucHTML = ($this->ruc != NULL) ? '<tr>
                                            <td style="">R.U.C. '.$this->ruc.'</td>
                                        </tr>' : '';

        $comprobanteHTML = '<table style="text-align:center; line-height:20pt; width:6cm; font-weight:bold; font-size:14pt;" border="0">
                            '.$rucHTML.'
                            <tr>
                                <td style="">'.$this->doc.'</td>
                            </tr>
                            <tr>
                                <td style="">'.$this->serie.' - '.$this->numero.'</td>
                            </tr>
                        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetX(10);
        //SE IMPRIME EL LOGO DE LA EMPRESA RAZON ANCHO/ALTO=4.7

        $logo_empresa = base_url() . 'images/cabeceras/logo'.$this->empresa.'.png';
        $this->Image($logo_empresa, 10, 8, 123, 26, '', '', '', false, 300);

        $this->SetY(34);
        
        $comprobanteHTML = '<table style="width:12cm; font-size:8pt;" border="0">
        <tr>
        <td style="font-weight:bold;">'.$this->RazonSocial.'</td>
        </tr>
        <tr>
        <td>' . $this->direccion . '<br>' . $this->ubigeo. '<br>---
        </td>
        </tr>
        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetY(55);
    }
}

class pdfCotizacion extends TCPDF {

    protected $ci;
    private $ruc;
    private $doc;
    private $serie;
    private $numero;
    private $fondo;
    private $compania;
    private $empresa;
    private $usuario;
    private $persona;

    public function __construct(){
        parent::__construct();
        $this->ci =& get_instance();
        $this->ci->load->model('maestros/persona_model');
        $this->compania = $_SESSION["compania"];
        $this->empresa = $_SESSION["empresa"];
        $this->usuario = $_SESSION["usuario"];
        $this->persona = $_SESSION["persona"];
        $this->fondo = "images/img_db/comprobante_general_".$this->compania.".jpg";
    }

    public function Header( $flagPdf = 1 ){
        $this->SetAutoPageBreak(false, 0);
        //$this->Image($this->fondo, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
        $this->SetAutoPageBreak(true, 5);

        $this->printHeaderData();
    }

    public function settingHeaderData($filter,$doc){
        $this->ruc      = $filter->ruc;
        $this->doc      = $doc;
        $this->serie    = $filter->serie;
        $this->numero   = $filter->numero;
        $this->RazonSocial  = $filter->RazonSocial;
        $this->direccion    = $filter->direccion;
        $this->ubigeo       = $filter->ubigeo;
    }

    public function printHeaderData(){
        
        $posY = 15;
        $posX = 139;
        $this->RoundedRect($posX, $posY, 60, 35, 1.50, '1111', '');
        $this->SetY($posY + 4);
        $this->SetX($posX);

        $rucHTML = ($this->ruc != NULL) ? '<tr>
                                            <td style="">R.U.C. '.$this->ruc.'</td>
                                        </tr>' : '';

        $comprobanteHTML = '<table style="text-align:center; line-height:20pt; width:6cm; font-weight:bold; font-size:14pt;" border="0">
                            '.$rucHTML.'
                            <tr>
                                <td style="">'.$this->doc.'</td>
                            </tr>
                            <tr>
                                <td style="">'.$this->serie.' - '.$this->numero.' - 25 </td>
                            </tr>
                        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetX(10);
        //SE IMPRIME EL LOGO DE LA EMPRESA RAZON ANCHO/ALTO=4.7

        $logo_empresa = base_url() . 'images/cabeceras/logo'.$this->empresa.'.png';
        $this->Image($logo_empresa, 10, 8, 123, 26, '', '', '', false, 300);

        $this->SetY(34);

        $comprobanteHTML = '<table style="width:12cm; font-size:8pt;" border="0">
        <tr>
        <td style="font-weight:bold;">'.$this->RazonSocial.'</td>
        </tr>
        <tr>
        <td>' . $this->direccion . '<br>
        </td>
        </tr>
        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetY(55);
    }

    public function Footer( $personal = NULL ){
                $this->SetFont('freesans', '', 7);
        $this->SetY(-9);
        $pieHTML = '
            <table border="0" cellpadding="0.1cm">
                <tr>
                    <td style="text-align:center;">Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages().'</td>
                </tr>
            </table>';

        $this->writeHTML($pieHTML,false,'');
        $this->SetY(55);

        /* $personal = $this->ci->persona_model->obtener_datosPersona($personal);

        $this->SetFont('freesans', '', 8);
        $this->SetY(-30);
        $pieHTML = '
            <table border="0" cellpadding="0.1cm">
                <tr bgcolor="#F6F6F6">
                    <td style="border-right: 1px #000 solid; font-weight:bold; text-align:center;">Elaboró</td>
                    <td style="border-right: 1px #000 solid; font-weight:bold; text-align:center;">Teléfonos</td>
                    <td style="border-right: 1px #000 solid; font-weight:bold; text-align:center;">Móvil</td>
                    <td style="font-weight:bold; text-align:center;">Email</td>
                </tr>
                <tr>
                    <td style="text-align:center;">'.$personal[0]->PERSC_Nombre.' '.$personal[0]->PERSC_ApellidoPaterno.' '.$personal[0]->PERSC_ApellidoMaterno.'</td>
                    <td style="text-align:center;">'.$personal[0]->PERSC_Telefono.'</td>
                    <td style="text-align:center;">'.$personal[0]->PERSC_Movil.'</td>
                    <td style="text-align:center;">'.$personal[0]->PERSC_Email.'</td>
                </tr>
            </table>';

        $this->writeHTML($pieHTML,false,'');
        */
        $this->SetY(55);
    }
}

class pdfComprobante extends TCPDF {

    protected $ci;
    private $ruc;
    private $doc;
    private $serie;
    private $numero;
    private $fondo;
    private $compania;
    private $empresa;
    private $usuario;
    private $persona;

    public function __construct(){
        parent::__construct();
        $this->ci =& get_instance();
        $this->compania = $_SESSION["compania"];
        $this->empresa = $_SESSION["empresa"];
        $this->usuario = $_SESSION["usuario"];
        $this->persona = $_SESSION["persona"];
        $this->fondo = "images/img_db/comprobante_general_".$this->compania.".jpg";
    }

    public function Header( $flagPdf = 1 ){
        if ($flagPdf == 1){
            $this->SetAutoPageBreak(false, 0);
            //$this->Image($this->fondo, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
            $this->SetAutoPageBreak(true, 40);
        }
        
        $this->printHeaderData();
    }

    public function settingHeaderData($filter,$doc){
        $this->ruc      = $filter->ruc;
        $this->doc      = $doc;
        $this->serie    = $filter->serie;
        $this->numero   = $filter->numero;
        $this->RazonSocial  = $filter->RazonSocial;
        $this->direccion    = $filter->direccion;
        $this->ubigeo       = $filter->ubigeo;
    }

    public function printHeaderData(){
        
        $posY = 15;
        $posX = 139;
        $this->RoundedRect($posX, $posY, 60, 35, 1.50, '1111', '');
        $this->SetY($posY + 4);
        $this->SetX($posX);

        $rucHTML = ($this->ruc != NULL) ? '<tr>
                                            <td style="">R.U.C. '.$this->ruc.'</td>
                                        </tr>' : '';

        $comprobanteHTML = '<table style="text-align:center; line-height:20pt; width:6cm; font-weight:bold; font-size:14pt;" border="0">
                            '.$rucHTML.'
                            <tr>
                                <td style="">'.$this->doc.'</td>
                            </tr>
                            <tr>
                                <td style="">'.$this->serie.' - '.$this->numero.'</td>
                            </tr>
                        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetX(10);
        //SE IMPRIME EL LOGO DE LA EMPRESA RAZON ANCHO/ALTO=4.7

        $logo_empresa = base_url() . 'images/cabeceras/logo'.$this->empresa.'.png';
        $this->Image($logo_empresa, 10, 8, 80, 25, '', '', '', false, 300);

        $this->SetY(34);

        if ($this->empresa!="1041") {
            $datos_contacto = $this->direccion . '<br>' . $this->ubigeo. '<br>Teléfono: 4931507 Cel.: 947134734  /  954170999   E-mail: rcardozo@solucioneseinversionesmontoya.com  administracion@solucioneseinversionesmontoya.com';
        }else{
            $datos_contacto = $this->direccion . '<br>' . $this->ubigeo. '<br>Teléfono Cel.: 954170999  /  992785827 <br>E-mail: distribucion@montoyacf.com.pe  rcardozo@montoyacf.com.pe';
        }

        $comprobanteHTML = '<table style="width:13cm; font-size:8pt;" border="0">
        <tr>
        <td style="font-weight:bold;">'.$this->RazonSocial.'</td>
        </tr>
        <tr>
        
        <td>' . $datos_contacto . '
        </td>
        
        </tr>
        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetY(55);
    }

    public function Footer( $personal = NULL ){
                $this->SetFont('freesans', '', 7);
        $this->SetY(-9);
        $pieHTML = '
            <table border="0" cellpadding="0.1cm">
                <tr>
                    <td style="text-align:center;">Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages().'</td>
                </tr>
            </table>';

        $this->writeHTML($pieHTML,false,'');
        $this->SetY(55);

        if ($this->empresa!="1041") {
            
        }else{
            $logo_empresa = base_url() . 'images/cabeceras/footer.png';
            $this->SetFont('freesans', '', 8);
            $this->SetY(-30);
            $pieHTML = '
                <table border="0" cellpadding="0.1cm">
                    <tr bgcolor="#F6F6F6">
                        <td style="font-weight:bold; text-align:center;">Distribuidor autorizado</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;"><img src="'.$logo_empresa.'" style="width:15cm; height:1.3cm;" > </td>
                        
                    </tr>
                </table>';

            $this->writeHTML($pieHTML,false,'');
        }
        
        
        $this->SetY(55);
    }
}

class pdfGarantiaComprobante extends TCPDF {

    protected $ci;
    private $ruc;
    private $doc;
    private $serie;
    private $numero;
    private $fondo;
    private $compania;
    private $empresa;
    private $usuario;
    private $persona;

    public function __construct(){
        parent::__construct();
        $this->ci =& get_instance();

        $this->ci->load->model('maestros/persona_model');
        $this->compania = $_SESSION["compania"];
        $this->empresa = $_SESSION["empresa"];
        $this->usuario = $_SESSION["usuario"];
        $this->persona = $_SESSION["persona"];
        $this->fondo = "images/img_db/garantia".$this->compania.".jpg";
    }

    public function Header( $flagPdf = 1 ){
        $this->SetAutoPageBreak(false, 0);
        //$this->Image($this->fondo, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
        $this->SetAutoPageBreak(true, 5);
    }

    public function settingHeaderData($filter,$doc){
        $this->ruc      = $filter->ruc;
        $this->doc      = $doc;
        $this->serie    = $filter->serie;
        $this->numero   = $filter->numero;
        $this->RazonSocial  = $filter->RazonSocial;
        $this->direccion    = $filter->direccion;
        $this->ubigeo       = $filter->ubigeo;
    }

    public function printHeaderData(){
        
        $posY = 15;
        $posX = 139;
        $this->RoundedRect($posX, $posY, 60, 35, 1.50, '1111', '');
        $this->SetY($posY + 4);
        $this->SetX($posX);

        $rucHTML = ($this->ruc != NULL) ? '<tr>
                                            <td style="">R.U.C. '.$this->ruc.'</td>
                                        </tr>' : '';

        $comprobanteHTML = '<table style="text-align:center; line-height:20pt; width:6cm; font-weight:bold; font-size:14pt;" border="0">
                            '.$rucHTML.'
                            <tr>
                                <td style="">'.$this->doc.'</td>
                            </tr>
                            <tr>
                                <td style="">'.$this->serie.' - '.$this->numero.'</td>
                            </tr>
                        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetX(10);
        //SE IMPRIME EL LOGO DE LA EMPRESA RAZON ANCHO/ALTO=4.7

        $logo_empresa = base_url() . 'images/cabeceras/logo'.$this->empresa.'.png';
        $this->Image($logo_empresa, 10, 8, 123, 26, '', '', '', false, 300);

        $this->SetY(34);

        $comprobanteHTML = '<table style="width:12cm; font-size:8pt;" border="0">
        <tr>
        <td style="font-weight:bold;">'.$this->RazonSocial.'</td>
        </tr>
        <tr>
        <td>' . $this->direccion . '<br>' . $this->ubigeo. '<br>---
        </td>
        </tr>
        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetY(55);
    }

    public function Footer(){
        $this->SetFont('freesans', '', 7);
        $this->SetY(-9);
        $pieHTML = '
            <table border="0" cellpadding="0.1cm">
                <tr>
                    <td style="text-align:center;">Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages().'</td>
                </tr>
            </table>';

        #$this->writeHTML($pieHTML,false,'');
        $this->SetY(55);
    }
}

class pdfCaja extends TCPDF {

    protected $ci;
    private $ruc;
    private $doc;
    private $serie;
    private $numero;
    private $fondo;
    private $compania;
    private $empresa;
    private $usuario;
    private $persona;

    public function __construct(){
        parent::__construct();
        $this->ci =& get_instance();
        $this->compania = $_SESSION["compania"];
        $this->empresa = $_SESSION["empresa"];
        $this->usuario = $_SESSION["usuario"];
        $this->persona = $_SESSION["persona"];
        $this->fondo = "images/img_db/comprobante_general_".$this->compania.".jpg";
    }

    public function Header( $flagPdf = 1 ){
        if ($flagPdf == 1){
            $this->SetAutoPageBreak(false, 0);
            //$this->Image($this->fondo, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
            $this->SetAutoPageBreak(true, 40);
        }
        
        $this->printHeaderData();
    }

    public function settingHeaderData($filter,$doc){
        $this->ruc      = $filter->ruc;
        $this->doc      = $doc;
        $this->serie    = $filter->serie;
        $this->numero   = $filter->numero;
        $this->RazonSocial  = $filter->RazonSocial;
        $this->direccion    = $filter->direccion;
        $this->ubigeo       = $filter->ubigeo;
    }

    public function printHeaderData(){
        
        $logo_empresa = base_url() . 'images/cabeceras/logo'.$this->empresa.'.png';
        $this->Image($logo_empresa, 10, 9, 126, 26, '', '', '', false, 300);


        $this->SetY(55);
    }
}

class pdfGuiaRemision extends TCPDF {

    protected $ci;
    private $ruc;
    private $doc;
    private $serie;
    private $numero;
    private $fondo;
    private $compania;
    private $empresa;
    private $usuario;
    private $persona;

    public function __construct(){
        parent::__construct(); 
        $this->ci =& get_instance();
        $this->ci->load->library('lib_props');
        $this->compania = $_SESSION["compania"];
        $this->empresa = $_SESSION["empresa"];
        $this->usuario = $_SESSION["usuario"];
        $this->persona = $_SESSION["persona"];
        $this->fondo = "images/img_db/comprobante_general_".$this->compania.".jpg";
    }

    public function Header( $flagPdf = 1 ){
        if ($flagPdf == 1){
            $this->SetAutoPageBreak(false, 0);
            //$this->Image($this->fondo, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
            $this->SetAutoPageBreak(true, 40);
        }
        
        $this->printHeaderData();
    }

    public function settingHeaderData($filter,$doc){
        $this->ruc      = $filter->ruc;
        $this->doc      = $doc;
        $this->serie    = $filter->serie;
        $this->numero   = $filter->numero;
        $this->RazonSocial  = $filter->RazonSocial;
        $this->direccion    = $filter->direccion;
        $this->ubigeo       = $filter->ubigeo;
    }

    public function printHeaderData(){
        
        $posY = 15;
        $posX = 139;
        $this->RoundedRect($posX, $posY, 60, 35, 1.50, '1111', '');
        $this->SetY($posY + 4);
        $this->SetX($posX);

        $rucHTML = ($this->ruc != NULL) ? '<tr>
                                            <td style="">R.U.C. '.$this->ruc.'</td>
                                        </tr>' : '';

        $comprobanteHTML = '<table style="text-align:center; line-height:20pt; width:6cm; font-weight:bold; font-size:10pt;" border="0">
                            '.$rucHTML.'
                            <tr>
                                <td style="">'.$this->doc.'</td>
                            </tr>
                            <tr>
                                <td style="">'.$this->serie.' - '.$this->numero.'</td>
                            </tr>
                        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetX(10);
        //SE IMPRIME EL LOGO DE LA EMPRESA RAZON ANCHO/ALTO=4.7

        $logo_empresa = base_url() . 'images/cabeceras/logo'.$this->empresa.'.png';
        $this->Image($logo_empresa, 10, 8, 123, 26, '', '', '', false, 300);

        $this->SetY(34);

        $comprobanteHTML = '<table style="width:12cm; font-size:8pt;" border="0">
        <tr>
        <td style="font-weight:bold;">'.$this->RazonSocial.'</td>
        </tr>
        <tr>
        <td>' . $this->direccion .'<br>
        </td>
        </tr>
        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetY(55);
    }
}

class pdfPedidos extends TCPDF {

    protected $ci;
    private $ruc;
    private $doc;
    private $serie;
    private $numero;
    private $fondo;
    private $compania;
    private $empresa;
    private $usuario;
    private $persona;

    public function __construct(){
        parent::__construct();
        $this->ci =& get_instance();
        $this->ci->load->model('maestros/persona_model');
        $this->compania = $_SESSION["compania"];
        $this->empresa = $_SESSION["empresa"];
        $this->usuario = $_SESSION["usuario"];
        $this->persona = $_SESSION["persona"];
        $this->fondo = "images/img_db/comprobante_general_".$this->compania.".jpg";
    }

    public function Header( $flagPdf = 1 ){
        $this->SetAutoPageBreak(false, 0);
        //$this->Image($this->fondo, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
        $this->SetAutoPageBreak(true, 5);

        $this->printHeaderData();
    }

    public function settingHeaderData($filter,$doc){
        $this->ruc      = $filter->ruc;
        $this->doc      = $doc;
        $this->serie    = $filter->serie;
        $this->numero   = $filter->numero;
        $this->RazonSocial  = $filter->RazonSocial;
        $this->direccion    = $filter->direccion;
        $this->ubigeo       = $filter->ubigeo;
    }

    public function printHeaderData(){
        
        $posY = 15;
        $posX = 139;
        $this->RoundedRect($posX, $posY, 60, 35, 1.50, '1111', '');
        $this->SetY($posY + 4);
        $this->SetX($posX);

        $rucHTML = ($this->ruc != NULL) ? '<tr>
                                            <td style="">R.U.C. '.$this->ruc.'</td>
                                        </tr>' : '';

        $comprobanteHTML = '<table style="text-align:center; line-height:20pt; width:6cm; font-weight:bold; font-size:14pt;" border="0">
                            '.$rucHTML.'
                            <tr>
                                <td style="">'.$this->doc.'</td>
                            </tr>
                            <tr>
                                <td style="">'.$this->serie.' - '.$this->numero.'</td>
                            </tr>
                        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetX(10);
        //SE IMPRIME EL LOGO DE LA EMPRESA RAZON ANCHO/ALTO=4.7

        $logo_empresa = base_url() . 'images/cabeceras/logo'.$this->empresa.'.png';
        $this->Image($logo_empresa, 10, 8, 123, 26, '', '', '', false, 300);

        $this->SetY(34);

        $comprobanteHTML = '<table style="width:12cm; font-size:8pt;" border="0">
        <tr>
        <td style="font-weight:bold;">'.$this->RazonSocial.'</td>
        </tr>
        <tr>
        <td>' . $this->direccion . '<br>
        </td>
        </tr>
        </table>';

        $this->writeHTML($comprobanteHTML,true,false,true,'');
        $this->SetY(55);
    }

    public function Footer( $personal = NULL ){
                $this->SetFont('freesans', '', 7);
        $this->SetY(-9);
        $pieHTML = '
            <table border="0" cellpadding="0.1cm">
                <tr>
                    <td style="text-align:center;">Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages().'</td>
                </tr>
            </table>';

        $this->writeHTML($pieHTML,false,'');
        $this->SetY(55);

        /* $personal = $this->ci->persona_model->obtener_datosPersona($personal);

        $this->SetFont('freesans', '', 8);
        $this->SetY(-30);
        $pieHTML = '
            <table border="0" cellpadding="0.1cm">
                <tr bgcolor="#F6F6F6">
                    <td style="border-right: 1px #000 solid; font-weight:bold; text-align:center;">Elaboró</td>
                    <td style="border-right: 1px #000 solid; font-weight:bold; text-align:center;">Teléfonos</td>
                    <td style="border-right: 1px #000 solid; font-weight:bold; text-align:center;">Móvil</td>
                    <td style="font-weight:bold; text-align:center;">Email</td>
                </tr>
                <tr>
                    <td style="text-align:center;">'.$personal[0]->PERSC_Nombre.' '.$personal[0]->PERSC_ApellidoPaterno.' '.$personal[0]->PERSC_ApellidoMaterno.'</td>
                    <td style="text-align:center;">'.$personal[0]->PERSC_Telefono.'</td>
                    <td style="text-align:center;">'.$personal[0]->PERSC_Movil.'</td>
                    <td style="text-align:center;">'.$personal[0]->PERSC_Email.'</td>
                </tr>
            </table>';

        $this->writeHTML($pieHTML,false,'');
        */
        $this->SetY(55);
    }
}

?>
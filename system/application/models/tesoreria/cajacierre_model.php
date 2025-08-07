<?php
/* *********************************************************************************
Autor: Martin Trujillo
Fecha: 14/01
 * 
 */
/* ******************************************************************************** */
class Cajacierre_model extends model{
    
    private $empresa;
    private $compania;
    private $usuario;
    
    public function __construct(){
        parent::__construct();
        $this->empresa = $this->session->userdata('empresa');
        $this->compania = $this->session->userdata('compania');
        $this->usuario = $this->session->userdata('user');
    }
    
   public function getCierres($filter = NULL) {

        $limit = ( isset($filter->start) && isset($filter->length) ) ? " LIMIT $filter->start, $filter->length " : "";
        $order = ( isset($filter->order) && isset($filter->dir) ) ? "ORDER BY $filter->order $filter->dir " : ""; 

        $where = '';
        if (isset($filter->caja) && $filter->caja != '')
            $where .= " AND c.CAJA_Codigo = $filter->caja";     
        
        if (isset($filter->situacion) && $filter->situacion != '')
            $where .= " AND c.CAJCIERRE_FlagSituacion = $filter->situacion";            

        $sql = "SELECT c.*,
                       d.CAJA_Nombre,
                       concat(p.PERSC_Nombre,' ',p.PERSC_ApellidoPaterno,' ',p.PERSC_ApellidoMaterno) nombres
                FROM cji_cajacierre c
                inner JOIN cji_caja d ON (d.CAJA_Codigo = c.CAJA_Codigo)
                INNER JOIN cji_usuario u on (u.USUA_Codigo = d.CAJA_Usuario)
                INNER JOIN cji_persona p on (p.PERSP_Codigo = u.PERSP_Codigo)
                WHERE c.CAJCIERRE_FlagEstado LIKE '1' AND d.COMPP_Codigo = $this->compania $where 
                $order $limit
               ";
             /*   var_dump($sql);
               exit; */
        
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return array();
    }
    
    public function getCierre($codigo) {
        $codigo = $this->db->escape($codigo);
    
        $sql = "SELECT c.*,
                       concat(p.PERSC_Nombre,' ',p.PERSC_ApellidoPaterno,' ',p.PERSC_ApellidoMaterno) nombres
                FROM cji_cajacierre c 
                INNER JOIN cji_caja caj ON caj.CAJA_Codigo = c.CAJA_Codigo
                INNER JOIN cji_usuario u ON u.USUA_Codigo = caj.CAJA_Usuario
                INNER JOIN cji_persona p ON p.PERSP_Codigo = u.PERSP_Codigo   
                WHERE c.CAJCIERRE_Codigo = $codigo;";
    
        $query = $this->db->query($sql);
    
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return array();
    }
    
    public function insertar_cierre($filter){
        $this->db->insert("cji_cajacierre", (array) $filter);
        return $this->db->insert_id();
    }

    public function actualizar_cierre($cierre, $filter){
        $this->db->where('CAJCIERRE_Codigo',$cierre);
        return $this->db->update('cji_cajacierre', $filter);
    }
    
    public function disminuye_monto_cierre($cierre, $monto){
        $this->db->set("CAJCIERRE_Ingresos", "CAJCIERRE_Ingresos - '".$monto."'", FALSE);
        $this->db->set("CAJCIERRE_Saldo", "CAJCIERRE_Saldo - '".$monto."'", FALSE);
        $this->db->where('CAJCIERRE_Codigo',$cierre);
        return $this->db->update('cji_cajacierre');
    }

    public function deshabilitar_cierre($cierre, $filter){
        $this->db->where('CAJCIERRE_Codigo',$cierre);
        $query = $this->db->update('cji_cajacierre', $filter);
        return $query;
    }

    public function get_ultimo_Cierre($incaja) {

        $caja =(int)$incaja;
    
        $sql = "SELECT * FROM `cji_cajacierre` WHERE `CAJA_Codigo`= $caja AND `CAJCIERRE_FlagSituacion` = 1";
        $query = $this->db->query($sql);
        return $query->result();
    }

    
    
    
}

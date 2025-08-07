<?php

class Comprobantepedido_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->somevar ['compania'] = $this->session->userdata('compania');
        $this->somevar ['user'] = $this->session->userdata('user');
        $this->somevar['hoy'] = mdate("%Y-%m-%d %h:%i:%s", time());
    }


    public function listarPedido($codigoPedido)
    {
    	$this->db->select('cji_comprobantepedido.*');
    	$this->db->from('cji_comprobantepedido');
    	$this->db->join('cji_pedido','cji_pedido.PEDIP_Codigo=cji_comprobantepedido.PEDIP_Codigo');
    	$this->db->where('cji_comprobantepedido.PEDIP_Codigo',$codigoPedido);
    	$query = $this->db->get();
    	if($query->num_rows>0)
    		return $query->result();
    	else
    		return array();
    }
    
    public function listarComprobante($codigoComprobante)
    {
		$this->db->select('cji_comprobantepedido.*');
    	$this->db->from('cji_comprobantepedido');
    	$this->db->join('cji_comprobante','cji_comprobante.CPP_Codigo=cji_comprobantepedido.CPP_Codigo');
    	$this->db->where('cji_comprobantepedido.CPP_Codigo',$codigoComprobante);
    	$query = $this->db->get();
    	if($query->num_rows>0)
    		return $query->result();
    	else
    		return array();
    }
    
    
    public function obtener($codigoComprobantePedido)
    {
    	$where = array("COMPPE_Codigo "=>$codigoComprobantePedido);
    	$query = $this->db->where($where)->get('cji_comprobantepedido');
    	if($query->num_rows>0){
    		return $query->result();
    	}
    }
    public function insertar($codigoPedido,$codigoComprobante, $serie, $numero)
    {
    	$data = array("PEDIP_Codigo"=>$codigoPedido,"CPP_Codigo "=>$codigoComprobante, "CPC_Serie"=>$serie, "CPC_Numero"=>$numero, "COMPPE_FlagEstado"=>2);
    	$this->db->insert("cji_comprobantepedido",$data);
		$compped_id = $this->db->insert_id();
        /* if($guiarem_id!=0) $this->configuracion_model->modificar_configuracion($this->somevar['compania'],10,$numero); */
        return $compped_id;
    }
    public function modificar($id,$filter)
    {
    	$this->db->where("COMPPE_Codigo",$id);
    	$this->db->update("cji_comprobantepedido",(array)$filter);
    }
    public function eliminarXComprobante($id)
    {
    	$this->db->delete('cji_comprobantepedido',array('CPP_Codigo' => $id));
    }
    public function eliminarXPedido($id)
    {
    	$this->db->delete('cji_comprobantepedido',array('PEDIP_Codigo' => $id));
    }

}

?>
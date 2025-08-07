<?php

class Guiarempedido_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->somevar ['compania'] = $this->session->userdata('compania');
        $this->somevar ['user'] = $this->session->userdata('user');
        $this->somevar['hoy'] = mdate("%Y-%m-%d %h:%i:%s", time());
    }


    public function listarPedido($codigoPedido)
    {
    	$this->db->select('cji_guiarempedido.*');
    	$this->db->from('cji_guiarempedido');
    	$this->db->join('cji_pedido','cji_pedido.PEDIP_Codigo=cji_guiarempedido.PEDIP_Codigo');
    	$this->db->where('cji_guiarempedido.PEDIP_Codigo',$codigoPedido);
    	$query = $this->db->get();
    	if($query->num_rows>0)
    		return $query->result();
    	else
    		return array();
    }
    
    public function listarGuiarem($codigoGuiarem)
    {
		$this->db->select('cji_guiarempedido.*');
    	$this->db->from('cji_guiarempedido');
    	$this->db->join('cji_guiarem','cji_guiarem.GUIAREMP_Codigo=cji_guiarempedido.GUIAREMP_Codigo');
    	$this->db->where('cji_guiarempedido.GUIAREMP_Codigo',$codigoGuiarem);
    	$query = $this->db->get();
    	if($query->num_rows>0)
    		return $query->result();
    	else
    		return array();
    }
    
    
    public function obtener($codigoGuiaremPedido)
    {
    	$where = array("GUIAPED_Codigo"=>$codigoGuiaremPedido);
    	$query = $this->db->where($where)->get('cji_guiarempedido');
    	if($query->num_rows>0){
    		return $query->result();
    	}
    }
    public function insertar($codigoPedido,$codigoGuiarem, $serie, $numero)
    {
    	$data = array("PEDIP_Codigo"=>$codigoPedido,"GUIAREMP_Codigo"=>$codigoGuiarem, "GUIAREMC_Serie"=>$serie, "GUIAREMC_Numero"=>$numero,"GUIAPED_flagEstado"=>2);
    	$this->db->insert("cji_guiarempedido",$data);
		$guiaped_id = $this->db->insert_id();
        /* if($guiarem_id!=0) $this->configuracion_model->modificar_configuracion($this->somevar['compania'],10,$numero); */
        return $guiaped_id;
    }
    public function modificar($id,$filter)
    {
    	$this->db->where("GUIAPED_Codigo",$id);
    	$this->db->update("cji_guiarempedido",(array)$filter);
    }

    public function modificarXGuia($id,$filter)
    {
    	$this->db->where("GUIAREMP_Codigo",$id);
    	$this->db->update("cji_guiarempedido",(array)$filter);
    }
    public function eliminarXGuia($id)
    {
    	$this->db->delete('cji_guiarempedido',array('GUIAREMP_Codigo' => $id));
    }
    public function eliminarXPedido($id)
    {
    	$this->db->delete('cji_guiarempedido',array('PEDIP_Codigo' => $id));
    }

}

?>
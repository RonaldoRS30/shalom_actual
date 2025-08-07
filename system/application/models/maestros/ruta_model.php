<?php
class Ruta_model extends Model{

	private $compania;
	private $usuario;
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('date');
        
        $this->compania = $this->session->userdata('compania');
        $this->usuario = $this->session->userdata('usuario');
	}


	###########################
	##### FUNCTIONS NEWS
	###########################

		public function getRutas($filter = NULL) {

	        $limit = ( isset($filter->start) && isset($filter->length) ) ? " LIMIT $filter->start, $filter->length " : "";
	        $order = ( isset($filter->order) && isset($filter->dir) ) ? "ORDER BY $filter->order $filter->dir " : "";

	        $where = '';
	        if (isset($filter->nombre) && $filter->nombre != '')
	            $where .= " AND r.Nombre_Ruta LIKE '%$filter->nombre%'";

	        $sql = "SELECT * FROM cji_rutas r WHERE r.Estado LIKE '1' $where $order $limit";

	        $query = $this->db->query($sql);
	        if ($query->num_rows > 0) {
	            return $query->result();
	        }
	        return array();
	    }

	    public function getRuta($codigo) {

	        $sql = "SELECT * FROM cji_rutas r WHERE r.COD_Ruta = $codigo";
	        $query = $this->db->query($sql);

	        if ($query->num_rows > 0) {
	            return $query->result();
	        }
	        return array();
	    }

    public function insertar($filter){
        $this->db->insert("cji_rutas", (array) $filter);
        return $this->db->insert_id();
    }

    public function actualizar($alergia, $filter){
        $this->db->where('COD_Ruta',$alergia);
        return $this->db->update('cji_rutas', $filter);
    }
	public function getempresaruc($keyword) {

		$sql = "SELECT * FROM cji_rutas r WHERE r.Ruc_Empresa LIKE ?";
		$query = $this->db->query($sql, ["%$keyword%"]); 
	
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return array();
	}
}
?>
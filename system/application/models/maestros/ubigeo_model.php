<?php
class Ubigeo_model extends Model{
    
    public $somevar;

	public function __construct(){
		parent::__construct();
	}

	public function getProvincias($departamento){
		$sql = "SELECT SUBSTRING(u.UBIGC_CodProv, 3) as UBIGC_CodProv, u.UBIGC_DescripcionProv
						FROM cji_ubigeo u
						WHERE u.UBIGC_FlagEstado LIKE '1' AND u.UBIGC_CodDpto LIKE '$departamento'
						GROUP BY u.UBIGC_DescripcionProv
						ORDER BY u.UBIGC_DescripcionProv ASC
				";
		$query = $this->db->query($sql);
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;		
		}
	}

	public function getDistritos($departamento, $provincia){
		$sql = "SELECT SUBSTRING(u.UBIGC_CodDist, 5) as UBIGC_CodDist, u.UBIGC_Descripcion
						FROM cji_ubigeo u
						WHERE u.UBIGC_FlagEstado LIKE '1' AND u.UBIGC_CodDpto LIKE '$departamento' AND u.UBIGC_CodProv LIKE '$departamento$provincia'
						ORDER BY u.UBIGC_Descripcion ASC
				";
		$query = $this->db->query($sql);
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;		
		}
	}

	public function listar_departamentos(){
		$sql = "SELECT * FROM cji_ubigeo WHERE UBIGC_FlagEstado = 1 GROUP BY UBIGC_DescripcionDpto ORDER BY UBIGC_DescripcionDpto ASC";
		$query = $this->db->query($sql);
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;
		}
	}

	public function listar_provincias($departamento){
		$sql = "SELECT * FROM cji_ubigeo WHERE UBIGC_FlagEstado = 1 AND UBIGC_CodDpto LIKE '$departamento' GROUP BY UBIGC_DescripcionProv ORDER BY UBIGC_DescripcionProv ASC";
		$query = $this->db->query($sql);
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;		
		}
	}

	public function listar_distritos($departamento,$provincia){

		$provincia = ( strlen($provincia) == 4 ) ? substr($provincia, 2, 2) : $provincia;

		$sql = "SELECT * FROM cji_ubigeo WHERE UBIGC_FlagEstado = 1 AND UBIGC_CodDpto LIKE '$departamento' AND UBIGC_CodProv LIKE '$departamento$provincia' ORDER BY UBIGC_Descripcion ASC";
		$query = $this->db->query($sql);
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;		
		}	
	}
	
	public function obtener_ubigeo($ubigeo){
		$query = $this->db->where('UBIGP_Codigo',$ubigeo)->get('cji_ubigeo');
		if($query->num_rows>0){
			foreach($query->result() as $fila){
				$data[] = $fila;
			}
			return $data;		
		}			
	}
	
	public function obtener_ubigeo_dpto($ubigeo){
		$departamento = substr($ubigeo,0,2);
		$sql = "SELECT * FROM cji_ubigeo WHERE UBIGC_FlagEstado = 1 AND UBIGC_CodDpto LIKE '$departamento' GROUP BY UBIGC_DescripcionDpto ORDER BY UBIGC_DescripcionDpto ASC";
		$query = $this->db->query($sql);
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                $data[] = $fila;
            }
            return $data;		
        }
	}

	public function obtener_ubigeo_prov($ubigeo){
		$departamento = substr($ubigeo,0,2);
		$provincia = substr($ubigeo,2,2);
		$sql = "SELECT * FROM cji_ubigeo WHERE UBIGC_FlagEstado = 1 AND UBIGC_CodDpto LIKE '$departamento' AND UBIGC_CodProv LIKE '$departamento$provincia' GROUP BY UBIGC_DescripcionProv ORDER BY UBIGC_DescripcionProv ASC";
		$query = $this->db->query($sql);

        if($query->num_rows>0){
            foreach($query->result() as $fila){
                $data[] = $fila;
            }
            return $data;		
        }
	}
    
    public function obtener_ubigeo_dist($ubigeo){
		$departamento = substr($ubigeo,0,2);
		$provincia = substr($ubigeo,2,2);
		$dist = substr($ubigeo,4,2);

        if ( strlen($ubigeo) == 4 )
        	$sql = "SELECT * FROM cji_ubigeo WHERE UBIGC_FlagEstado = 1 AND UBIGP_Codigo = $ubigeo ORDER BY UBIGC_Descripcion ASC";
        else
        	$sql = "SELECT * FROM cji_ubigeo WHERE UBIGC_FlagEstado = 1 AND UBIGP_Codigo = $departamento$provincia$dist ORDER BY UBIGC_Descripcion ASC";
        	
		$query = $this->db->query($sql);
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                $data[] = $fila;
            }
            return $data;		
        }
	}

	public function buscar_ubigeo($buscar) {
    $buscar = trim($buscar);

    // Siempre buscar por descripciones si hay guiones (se asume formato tipo "Ica-Pisco-Túpac Amaru")
    if (strpos($buscar, '-') !== false) {
        $params = explode("-", $buscar);
        $size = count($params);

        $depa = isset($params[$size - 3]) ? trim($params[$size - 3]) : '';
        $prov = isset($params[$size - 2]) ? trim($params[$size - 2]) : '';
        $dist = isset($params[$size - 1]) ? trim($params[$size - 1]) : '';

        $where = " UBIGC_DescripcionDpto LIKE '%$depa%' 
                   OR UBIGC_DescripcionProv LIKE '%$prov%' 
                   OR UBIGC_Descripcion LIKE '%$dist%' ";
    } else {
        // Permitir buscar desde 2 caracteres
        $where = " UBIGP_Codigo LIKE '%$buscar%' 
                   OR UBIGC_DescripcionDpto LIKE '%$buscar%' 
                   OR UBIGC_DescripcionProv LIKE '%$buscar%' 
                   OR UBIGC_Descripcion LIKE '%$buscar%' ";
    }

    $sql = "SELECT * FROM cji_ubigeo WHERE $where LIMIT 10";

    $query = $this->db->query($sql);

    return ($query->num_rows() > 0) ? $query->result() : NULL;
}

}
?>
<?php
class Eliminar_model extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('date');
        $this->somevar['compania'] = $this->session->userdata('compania');
        $this->somevar['usuario'] = $this->session->userdata('usuario');
        $this->somevar['hoy'] = mdate("%Y-%m-%d %h:%i:%s", time());
    }

    public function Agregar_Tabla($ordAdj)
    {
        $sql = "$ordAdj";
        $this->db->query($sql);
    }

    public function Eliminar_Tabla($ordAdj)
    {
        $sql = "$ordAdj";
        $this->db->query($sql);
    }

    public function EliminarTransaccionales()
    {
        $this->db->truncate('cji_cotizacion');
        $this->db->truncate('cji_cotizaciondetalle');
        $this->db->truncate('cji_comprobante');
        $this->db->truncate('cji_comprobantedetalle');
        $this->db->truncate('cji_guiarem');
        $this->db->truncate('cji_guiaremdetalle');
        $this->db->truncate('cji_guiasa');
        $this->db->truncate('cji_guiasadetalle');
        $this->db->truncate('cji_guiain');
        $this->db->truncate('cji_guiaindetalle');
        $this->db->truncate('cji_guiatrans');
        $this->db->truncate('cji_guiatransdetalle');
        $this->db->truncate('cji_ordencompra');
        $this->db->truncate('cji_ocompradetalle');
        $this->db->truncate('cji_presupuesto');
        $this->db->truncate('cji_presupuestodetalle');
        $this->db->truncate('cji_nota');
        $this->db->truncate('cji_notadetalle');
        $this->db->truncate('cji_cuentas');
        $this->db->truncate('cji_cuentasempresas');
        $this->db->truncate('cji_cuentaspago');
        $this->db->truncate('cji_pago');
        $this->db->truncate('cji_kardex');
        $this->db->truncate('cji_inventario');
        $this->db->truncate('cji_inventariodetalle');
        $this->db->truncate('cji_letra');
    }

    #############################################
    ######## TRUNCATES - RAWIL
    #############################################

    public function truncate_comprobantes()
    {

        #############################################
        ######## VENTAS Y ENVIOS AL FACTURADOR
        #############################################

        # Deshabilitamos verificaciones de llave foránea
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        # Primero eliminamos tablas dependientes
        $this->db->query('DELETE FROM cji_comprobantedetalle');
        $this->db->query('DELETE FROM cji_comprobante_guiarem');
        $this->db->query('DELETE FROM comprobantes_cuotas');
        $this->db->query('DELETE FROM cji_comprobante_letra');
        $this->db->query('DELETE FROM cji_guiaremdetalle');
        $this->db->query('DELETE FROM cji_notadetalle');
        $this->db->query('DELETE FROM cji_cuentaspago');
        $this->db->query('DELETE FROM cji_cuentasempresas');
        $this->db->query('DELETE FROM cji_cajamovimiento');

        # Luego eliminamos tablas principales
        $this->db->query('DELETE FROM cji_comprobante');
        $this->db->query('DELETE FROM cji_letra');
        $this->db->query('DELETE FROM cji_guiarem');
        $this->db->query('DELETE FROM cji_nota');
        $this->db->query('DELETE FROM cji_respuestasunat');
        $this->db->query('DELETE FROM cji_cuentas');
        $this->db->query('DELETE FROM cji_pago');
        $this->db->query('DELETE FROM cji_caja');
        $this->db->query('DELETE FROM cji_cheque');
        $this->db->query('DELETE FROM cji_tipocaja');
        $this->db->query('DELETE FROM cji_flujocaja');
        $this->db->query('DELETE FROM cji_reponsblmoviminto');
        $this->db->query('DELETE FROM cji_kardex');
        $this->db->query('DELETE FROM temporal_detalle');

        # Restauramos verificaciones de llave foránea
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        $sql = "UPDATE cji_configuracion SET CONFIC_Numero = 0 WHERE DOCUP_Codigo IN(8,9,10,11,12,14,16)";
        $this->db->query($sql);
    }

    public function truncate_docs()
    {

        #############################################
        ######## OTROS DOCUMENTOS
        #############################################

        $this->db->truncate("cji_ordencompra");
        $this->db->truncate("cji_ocompradetalle");
        $this->db->truncate("cji_presupuesto");
        $this->db->truncate("cji_presupuestodetalle");

        $this->db->truncate("cji_cotizacion");
        $this->db->truncate("cji_cotizaciondetalle");

        $this->db->truncate("cji_pedido");
        $this->db->truncate("cji_pedidodetalle");

        $this->db->truncate("cji_produccion");
        $this->db->truncate("cji_producciondetalle");

        $this->db->truncate("cji_despacho");
        $this->db->truncate("cji_despachodetalle");
    }

    public function truncate_inventarios()
    {
        #############################################
        ############# STOCK
        #############################################
            
            # Deshabilitamos verificaciones de llave foránea
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');

            # Primero eliminamos tablas dependientes
            $this->db->query('DELETE FROM cji_almacenproductoserie');
            $this->db->query('DELETE FROM cji_almacenproducto');
            $this->db->query('DELETE FROM cji_almaprolote');
            $this->db->query('DELETE FROM cji_loteprorrateo');
            $this->db->query('DELETE FROM cji_seriedocumento');
            $this->db->query('DELETE FROM cji_seriemov');
            $this->db->query('DELETE FROM cji_inventariodetalle');
            $this->db->query('DELETE FROM cji_guiasadetalle');
            $this->db->query('DELETE FROM cji_guiaindetalle');
            $this->db->query('DELETE FROM cji_guiatransdetalle');

            # Luego eliminamos tablas principales
            $this->db->query('DELETE FROM cji_almacen');
            $this->db->query('DELETE FROM cji_lote');
            $this->db->query('DELETE FROM cji_serie');
            $this->db->query('DELETE FROM cji_inventario');
            $this->db->query('DELETE FROM cji_guiasa');
            $this->db->query('DELETE FROM cji_guiain');
            $this->db->query('DELETE FROM cji_guiatrans');
            $this->db->query('DELETE FROM cji_kardex');

            # Restauramos verificaciones de llave foránea
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function truncate_stock()
    {
        #############################################
        ############# STOCK
        #############################################
        $this->db->truncate("cji_almacenproducto");
        $this->db->truncate("cji_almacenproductoserie");
        $this->db->truncate("cji_almaprolote");

        $this->db->truncate("cji_lote");
        $this->db->truncate("cji_loteprorrateo");

        $this->db->truncate("cji_serie");
        $this->db->truncate("cji_seriedocumento");
        $this->db->truncate("cji_seriemov");

        $this->db->truncate('cji_inventariodetalle');

        #############################################
        ######## GUIAS INTERNAS
        #############################################

        $this->db->truncate("cji_guiasa");
        $this->db->truncate("cji_guiasadetalle");
        $this->db->truncate("cji_guiain");
        $this->db->truncate("cji_guiaindetalle");
        $this->db->truncate("cji_guiatrans");
        $this->db->truncate("cji_guiatransdetalle");

        $this->db->truncate("cji_kardex");
    }

    public function truncate_productos()
    {
        #############################################
        ############# PRODUCTOS
        #############################################

            # Deshabilitamos verificaciones de llave foránea
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');
            
            # Primero eliminamos tablas dependientes
            $this->db->query('DELETE FROM cji_almacenproducto_carga');
            $this->db->query('DELETE FROM cji_productocompania');
            $this->db->query('DELETE FROM cji_productounidad');
            $this->db->query('DELETE FROM cji_productoprecio');
            $this->db->query('DELETE FROM cji_productoproveedor');
            // $this->db->query('DELETE FROM cji_familiacompania');
            $this->db->query('DELETE FROM cji_proveedormarca');
            $this->db->query('DELETE FROM cji_recetadetalle');

            # Luego eliminamos tablas principales
            $this->db->query('DELETE FROM cji_producto');
            // $this->db->query('DELETE FROM cji_familia');
            // $this->db->query('DELETE FROM cji_marca');
            $this->db->query('DELETE FROM cji_receta');

            # Restauramos verificaciones de llave foránea
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function truncate_usuarios($all = false)
    {

        # BORRAMOS A LOS USUARIOS QUE NO SON ADMINISTRADOR Y CCAPA
        $sql = "DELETE FROM cji_usuario WHERE USUA_Codigo NOT IN (1,2)";
        $this->db->query($sql);

        # INICIAMOS EL INDICE "AUTOINCREMENT" DE LA TABLA EN 3
        $sql = "ALTER TABLE cji_usuario AUTO_INCREMENT = 3";
        $this->db->query($sql);

        if ($all == false) {
            # BORRAMOS A LOS USUARIOS QUE NO SON ADMINISTRADOR Y CCAPA
            $sql = "DELETE FROM cji_usuario_compania WHERE USUA_Codigo NOT IN (1,2)";
            $this->db->query($sql);

            # OBTENEMOS EL ULTIMO ID INGRESADO EN LA TABLA
            $sql = "SELECT MAX(USUCOMP_Codigo) as id FROM cji_usuario_compania";
            $query = $this->db->query($sql);

            if ($query->num_rows > 0) {
                foreach ($query->result() as $val) {
                    $id = $val->id + 1;
                }

                # INICIAMOS EL INDICE "AUTOINCREMENT" DE LA TABLA EN $id
                $sql = "ALTER TABLE cji_usuario_compania AUTO_INCREMENT = $id";
                $this->db->query($sql);
            }
        } else
            $this->db->truncate("cji_usuario_compania");
    }

    public function truncate_personal()
    {

        # BORRAMOS AL PERSONAL QUE NO SON ADMINISTRADOR Y CCAPA
        $sql = "DELETE FROM cji_persona WHERE EXISTS( SELECT d.PERSP_Codigo FROM cji_directivo d WHERE d.PERSP_Codigo = cji_persona.PERSP_Codigo) AND PERSP_Codigo NOT IN (1,2)";
        $this->db->query($sql);

        # BORRAMOS A LOS DIRECTIVOS QUE NO SON ADMINISTRADOR
        $sql = "DELETE FROM cji_directivo WHERE DIREP_Codigo <> 1";
        $this->db->query($sql);

        # INICIAMOS EL INDICE "AUTOINCREMENT" DE LA TABLA EN 2
        $sql = "ALTER TABLE cji_directivo AUTO_INCREMENT = 2";
        $this->db->query($sql);

        # INICIAMOS EL INDICE "AUTOINCREMENT" DE LA TABLA EN 3
        # $sql = "ALTER TABLE cji_persona AUTO_INCREMENT = 3";
        # $this->db->query($sql);
    }

    public function truncate_clientes_proveedores()
    {

        # BORRAMOS LAS PERSONAS QUE SON CLIENTES
        $sql = "DELETE FROM cji_persona WHERE EXISTS( SELECT c.PERSP_Codigo FROM cji_cliente c WHERE c.PERSP_Codigo = cji_persona.PERSP_Codigo) AND PERSP_Codigo NOT IN (1,2)";
        $this->db->query($sql);

        # BORRAMOS LAS PERSONAS QUE SON PROVEEDORES
        $sql = "DELETE FROM cji_persona WHERE EXISTS( SELECT pr.PERSP_Codigo FROM cji_proveedor pr WHERE pr.PERSP_Codigo = cji_persona.PERSP_Codigo) AND PERSP_Codigo NOT IN (1,2)";
        $this->db->query($sql);

        # Deshabilitamos temporalmente las verificaciones de llave foránea
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        # Limpiamos las tablas relacionadas
        $this->db->query('DELETE FROM cji_clientecompania');
        $this->db->query('DELETE FROM cji_proveedorcompania');
        $this->db->query('DELETE FROM cji_emprcontacto');
        $this->db->query('DELETE FROM cji_cliente');

        # Restauramos las verificaciones de llave foránea
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        # Deshabilitamos temporalmente las verificaciones de llave foránea
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        # BORRAMOS TODAS LAS EMPRESAS MENOS LAS QUE TIENEN COMPAÑiA
        $sql = "DELETE FROM cji_empresa WHERE NOT EXISTS (SELECT c.EMPRP_Codigo FROM cji_compania c WHERE c.EMPRP_Codigo = cji_empresa.EMPRP_Codigo)";
        $this->db->query($sql);

        # Restauramos las verificaciones de llave foránea
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        # OBTENGO EL ID DEL ULTIMO REGISTRO + 1 PARA AJUSTAR EL AUTOINCREMENT DE LA TABLA
        $sql = "SELECT (MAX(EMPRP_Codigo) + 1) as id FROM cji_empresa";
        $query = $this->db->query($sql);

        if ($query->num_rows > 0) {
            foreach ($query->result() as $key => $value) {
                $id = $value->id;
            }
        } else
            $id = 1;

        # INICIAMOS EL INDICE "AUTOINCREMENT" DE LA TABLA EN EL ULTIMO ID REGISTRADO + 1
        if ($id != NULL && $id != "") {
            $sql = "ALTER TABLE cji_empresa AUTO_INCREMENT = $id";
            $this->db->query($sql);
        }


        $id = NULL;

        # BORRAMOS TODOS LOS ESTABLECIMIENTOS MENOS LOS QUE TIENEN REGISTRO EN LA TABLA COMPAÑIA
        $sql = "DELETE FROM cji_emprestablecimiento WHERE NOT EXISTS (SELECT c.EESTABP_Codigo FROM cji_compania c WHERE c.EESTABP_Codigo = cji_emprestablecimiento.EESTABP_Codigo)";
        $this->db->query($sql);

        # OBTENGO EL ID DEL ULTIMO REGISTRO + 1 PARA AJUSTAR EL AUTOINCREMENT DE LA TABLA
        $sql = "SELECT (MAX(EESTABP_Codigo) + 1) as id FROM cji_emprestablecimiento";
        $query = $this->db->query($sql);

        if ($query->num_rows > 0) {
            foreach ($query->result() as $key => $value) {
                $id = $value->id;
            }
        } else
            $id = 1;

        # INICIAMOS EL INDICE "AUTOINCREMENT" DE LA TABLA EN EL ULTIMO ID REGISTRADO + 1
        if ($id != NULL && $id != "") {
            $sql = "ALTER TABLE cji_emprestablecimiento AUTO_INCREMENT = $id";
            $this->db->query($sql);
        }

        $id = NULL;

        # BORRAMOS A LOS PROVEEDORES QUE NO TENGAN REGISTRO EN LA TABLA EMPRESA
        $sql = "DELETE FROM cji_proveedor WHERE NOT EXISTS (SELECT e.EMPRP_Codigo FROM cji_empresa e WHERE e.EMPRP_Codigo = cji_proveedor.EMPRP_Codigo)";
        $this->db->query($sql);

        # OBTENGO EL ID DEL ULTIMO REGISTRO + 1 PARA AJUSTAR EL AUTOINCREMENT DE LA TABLA
        $sql = "SELECT (MAX(PROVP_Codigo) + 1) as id FROM cji_proveedor";
        $query = $this->db->query($sql);

        if ($query->num_rows > 0) {
            foreach ($query->result() as $key => $value) {
                $id = $value->id;
            }
        } else
            $id = 1;

        # INICIAMOS EL INDICE "AUTOINCREMENT" DE LA TABLA EN EL ULTIMO ID REGISTRADO + 1
        if ($id != NULL && $id != "") {
            $sql = "ALTER TABLE cji_proveedor AUTO_INCREMENT = $id";
            $this->db->query($sql);
        }
    }

    public function truncate_empresas()
    {

        $this->truncate_clientes_proveedores();

        # CLIENTES
        $this->db->truncate("cji_cliente");
        $this->db->truncate("cji_clientecompania");

        # PROVEEDORES
        $this->db->truncate("cji_proveedor");
        $this->db->truncate("cji_proveedorcompania");

        # EMPRESAS
        $this->db->truncate("cji_empresa");
        $this->db->truncate("cji_emprestablecimiento");

        # COMPAÑIAS
        $this->db->truncate("cji_compania");
        $this->db->truncate("cji_companiaconfidocumento");
        $this->db->truncate("cji_companiaconfiguracion");
        $this->db->truncate("cji_compadocumenitem");
        $this->db->truncate("cji_configuracion");
    }

    public function truncate_cargas()
    {
        $this->db->truncate('cji_almacenproducto_carga');
    }

    public function truncate_clientecompania($value = '')
    {
        $this->db->truncate("cji_clientecompania");
    }

    public function truncate_respuesta_sunat()
    {
        $this->db->truncate("cji_respuestas_sunat");
    }

    public function truncate_productocompania($value = '')
    {
        $this->db->truncate("cji_productocompania");
    }

    public function truncate_all()
    {
        $this->truncate_comprobantes();
        $this->truncate_docs();
        $this->truncate_inventarios();
        $this->truncate_stock();
        $this->truncate_productos();
        $this->truncate_usuarios(true); # true VACIAS LA TABLA usuario_compania
        $this->truncate_personal(); # INCLUYE DIRECTIVOS
        $this->truncate_empresas(); # INCLUYE CLIENTES Y PROVEEDORES
        $this->truncate_cargas();
        $this->truncate_respuesta_sunat();
    }
}
?>
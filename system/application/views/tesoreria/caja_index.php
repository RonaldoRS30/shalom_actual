<link href="<?=base_url();?>js/fancybox/dist/jquery.fancybox.css?=<?=CSS;?>" rel="stylesheet">
<script src="<?=base_url();?>js/fancybox/dist/jquery.fancybox.js?=<?=JS;?>"></script>

<div class="container-fluid">
    <div class="row header">
        <div class="col-md-12 col-lg-12">
            <div><?=$titulo;?></div>
        </div>
    </div>
    <form id="form_busqueda" method="post">
        <div class="row fuente8 py-1">
            <div class="col-sm-4 col-md-4 col-lg-4"></div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <input type="text" name="search_codigo" id="search_codigo" value="" placeholder="Código de caja" class="form-control h-1 w-porc-90"/>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <input type="text" name="search_descripcion" id="search_descripcion" value="" placeholder="Nombre de la caja" class="form-control h-1 w-porc-90"/>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <select name="search_tipo" id="search_tipo" class="form-control w-porc-90 h-2 w-porc-90">
                    <option value=""> :: TODAS :: </option> <?php
                    if ($tipo_caja != NULL){
                        foreach ($tipo_caja as $indice => $val){ ?>
                            <option value="<?=$val->tipCa_codigo;?>"><?=$val->tipCa_Descripcion;?></option> <?php
                        }
                    } ?>
                </select>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <div class="acciones">
                        <div id="botonBusqueda">
                            <ul class="lista_botones">
                                <li id="nuevo" data-toggle='modal' data-target='#add_caja'>Caja</li>
                            </ul>
                            <ul id="limpiarC" class="lista_botones">
                                <li id="limpiar">Limpiar</li>
                            </ul>
                            <ul id="buscarC" class="lista_botones">
                                <li id="buscar">Buscar</li>
                            </ul> 
                        </div>
                        <div id="lineaResultado">Registros encontrados</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <div class="header text-align-center"><?=$titulo;?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <table class="fuente8 display" id="table-caja">
                        <div id="cargando_datos" class="loading-table">
                            <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                        </div>
                        <thead>
                            <tr class="cabeceraTabla">
                                <td style="width:10%" data-orderable="false">N°</td>
                                <td style="width:10%" data-orderable="true">CÓDIGO</td>
                                <td style="width:25%" data-orderable="true">NOMBRE</td>
                                <td style="width:15%" data-orderable="true">TIPO DE CAJA</td>
                                <td style="width:30%" data-orderable="true">OBSERVACIONES</td>
                                <td style="width:05%" data-orderable="false"></td>
                                <td style="width:05%" data-orderable="false"></td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="add_caja" class="modal fade" role="dialog">
    <div class="modal-dialog w-porc-60">
        <div class="modal-content">
            <form id="formCaja" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title text-center">REGISTRAR CAJA</h4>
                </div>
                <div class="modal-body panel panel-default">
                    <input type="hidden" id="caja" name="caja" value="">

                    <div class="row form-group">
                        <div class="col-sm-3 col-md-3 col-lg-3">
                            <label for="codigo_caja">CÓDIGO</label>
                            <input type="text" id="codigo_caja" name="codigo_caja" class="form-control h-2 w-porc-90" placeholder="Indique el codigo" value="" maxlength="30">
                        </div>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <label for="nombre_caja">NOMBRE *</label>
                            <input type="text" id="nombre_caja" name="nombre_caja" class="form-control h-2" placeholder="Indique la caja" value="" maxlength="200">
                        </div>

                        <div class="col-sm-3 col-md-3 col-lg-3">
                            <label for="tipo_caja">TIPO</label>
                            <select name="tipo_caja" id="tipo_caja" class="form-control w-porc-90 h-3"> <?php
                                if ($tipo_caja != NULL){
                                    foreach ($tipo_caja as $indice => $val){ ?>
                                        <option value="<?=$val->tipCa_codigo;?>"><?=$val->tipCa_Descripcion;?></option> <?php
                                    }
                                } ?>
                            </select>
                        </div>

                    <div class="row form-group">
                        <div class="col-sm-3 col-md-3 col-lg-3">
                        <label for="cajero_caja">VENDEDOR</label>
                        
                            <select id="cajero_caja" name="cajero_caja" class="form-control w-porc-90 h-3">
                                <?= $cajeros; ?>                
                            </select>
                            
                            <!-- 
                            <button type="button" class="btn btn-default" id="open_modal_credencial">
                                <img src='<?= base_url(); ?>/images/icon-lock.png' class='image-size-1b'>
                            </button>
                            -->
                        </div>
                    </div>


                    <div class="col-md-12 col-lg-6">
                            <label for="estado_caja">SITUACIÓN</label>
                            <select name="estado_caja" id="estado_caja" class="form-control w-porc-90 h-3"> 
                                <option value="1" selected="selected">ABIERTA</option>
                            </select>
                            <input type="hidden" id="estado_caja_ant" name="c">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-md-12 col-lg-6">
                            <label for="obs_caja">OBSERVACIONES</label>
                            <textarea class="form-control h-5" id="obs_caja" name="obs_caja" placeholder="Indique las observaciones" maxlength="800"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" accesskey="x" onclick="registrar_caja()">Guardar Registro</button>
                    <button type="button" class="btn btn-info" onclick="clean()">Limpiar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    base_url = "<?=$base_url;?>";

    $(document).ready(function(){
        $('#table-caja').DataTable({ responsive: true,
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax:{
                    url : '<?=base_url();?>index.php/tesoreria/caja/datatable_caja/',
                    type: "POST",
                    data: { dataString: "" },
                    beforeSend: function(){
                        $("#table-caja .loading-table").show();
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#table-caja .loading-table").hide();
                    }
            },
            language: spanish,
            columnDefs: [{"className": "dt-center", "targets": 0}],
            order: [[ 1, "asc" ]]
        });

        $("#buscarC").click(function(){
            search();
        });

        $("#limpiarC").click(function(){
            search(false);
        });

        $('#form_busqueda').keypress(function(e){
            if ( e.which == 13 ){
                return false;
            } 
        });

        $("#nuevo").click(function () {
         clean();
         $("#estado_caja option:not(:selected)").hide();
         
         //Habilitamos la caja codigo
         $("#codigo_caja").removeAttr("readonly")
         
         //Mostramos el modal
         $('#add_caja').modal('show');
      });

        $('#search_descripcion').keyup(function(e){
            if ( e.which == 13 ){
                if( $(this).val() != '' )
                    search();
            }
        });
        $("#imgGuardarProyecto").click(function(){
		dataString = $('#frmProyecto').serialize();
		$("#container").show();
		$("#frmProyecto").submit();
    });
    
    $("#buscarProyecto").click(function(){
		$("#form_busqueda").submit();
    });	
    
    $("#nuevaCaja").click(function(){
		url = base_url+"index.php/tesoreria/caja/nueva_caja";
		$("#zonaContenido").load(url);
    });
    
    $("#limpiarProyecto").click(function(){
        url = base_url+"index.php/maestros/proyecto/proyectos";
        location.href=url;
    });
    
    $("#imgCancelarProyecto").click(function(){
        base_url = $("#base_url").val();
        location.href = base_url+"index.php/tesoreria/caja/cajas";
    });
    
    $(":radio").click(function(){
        valor = $(this).attr("value");
        if(valor==0){//CAJA
            $("#datosBanco").hide();
            $("#datosCaja").show();
            $("#tabs-2").hide();
            $("#tabChequera").css("display","none");
        }
        else if(valor==1){//BANCOS
            $("#datosBanco").show();
            $("#datosCaja").hide();
            $("#tabs-2").show();
            $("#tabChequera").show();
        }
    });
    });

    function search( search = true){
        if (search == true){
            search_codigo = $("#search_codigo").val();
            search_descripcion = $("#search_descripcion").val();
            search_tipo = $("#search_tipo").val();
        }
        else{
            $("#search_codigo").val("");
            $("#search_descripcion").val("");
            $("#search_tipo").val("");
            search_codigo = "";
            search_descripcion = "";
            search_tipo = "";
        }
        
        $('#table-caja').DataTable({ responsive: true,
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax:{
                    url : '<?=base_url();?>index.php/tesoreria/caja/datatable_caja/',
                    type: "POST",
                    data: {
                            codigo: search_codigo,
                            descripcion: search_descripcion,
                            tipo: search_tipo,
                    },
                    beforeSend: function(){
                        $("#table-caja .loading-table").show();
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#table-caja .loading-table").hide();
                    }
            },
            language: spanish,
            columnDefs: [{"className": "dt-center", "targets": 0}],
            order: [[ 1, "asc" ]]
        });
    }

    function editar(id){
        var url = base_url + "index.php/tesoreria/caja/getCaja";
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data:{
                    caja: id
            },
            beforeSend: function(){
                clean();
            },
            success: function(data){
                if (data.match == true) {
                    info = data.info;

                    $("#caja").val(info.caja);
                    $("#codigo_caja").val(info.codigo);
                    $("#nombre_caja").val(info.nombre);
                    $("#tipo_caja").val(info.tipocaja);
                    $("#obs_caja").val(info.obs);
                    $("#estado_caja").val(info.estado);
                    $("#estado_caja_ant").val(info.estado);
                    $("#cajero_caja").val(info.cajero_caja);
                    $("#nombrecajero_caja").val(info.nombre_responsable);

                    $("#codigo_caja").attr("readonly","readonly");

                    $("#add_caja").modal("toggle");
                }
                else{
                    Swal.fire({
                                icon: "info",
                                title: "Información no disponible.",
                                html: "<b class='color-red'></b>",
                                showConfirmButton: true,
                                timer: 4000
                            });
                }
            },
            complete: function(){
            }
        });
    }

    function registrar_caja(){

var cajero_caja = document.getElementById('cajero_caja');
var selectedVendedor = cajero_caja.options[cajero_caja.selectedIndex].value;

if (selectedVendedor === '') {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Por favor, seleccione un vendedor antes de guardar.'
    });
    return;
}

Swal.fire({
            icon: "question",
            title: "¿Esta seguro de guardar el registro?",
            html: "<b class='color-red'></b>",
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar"
        }).then(result => {
            if (result.value){
                var caja = $("#caja").val();
                var estado = $("#estado_caja").val();
                var descripcion = $("#nombre_caja").val();
                validacion = true;
                if (descripcion == ""){
                    Swal.fire({
                                icon: "error",
                                title: "Verifique los datos ingresados.",
                                html: "<b class='color-red'>Debe ingresar un nombre para la caja.</b>",
                                showConfirmButton: true,
                                timer: 4000
                            });
                    $("#nombre_caja").focus();
                    validacion = false;
                    return null;
                }
                if (validacion == true){
                    var url = base_url + "index.php/tesoreria/caja/guardar_registro";
                    var info = $("#formCaja").serialize();
                    $.ajax({
                        type: 'POST',
                        url: url,
                        dataType: 'json',
                        data: info,
                        success: function(data){
                            if (data.result == "success") {
                                if (caja == "")
                                    titulo = "¡Registro exitoso!";
                                else
                                    titulo = "¡Actualización exitosa!";

                                Swal.fire({
                                    icon: "success",
                                    title: titulo,
                                    showConfirmButton: true,
                                    timer: 2000
                                });

                                //Cerramos modal
                                 $("#add_caja").modal("hide");

                                clean();
                            }
                            else{
                                Swal.fire({
                                    icon: "error",
                                    title: "Sin cambios.",
                                    html: "<b class='color-red'>La información no fue registrada/actualizada, intentelo nuevamente.</b>",
                                    showConfirmButton: true,
                                    timer: 4000
                                });
                                
                            }
                        },
                        complete: function(){
                            search(false);
                            $("#nombre_caja").focus();
                        }
                    });
                }
            }
        });
}

    function deshabilitar(caja){
        Swal.fire({
                    icon: "info",
                    title: "Debe confirmar esta acción.",
                    html: "<b class='color-red'>Esta acción no se puede deshacer</b>",
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Aceptar",
                    cancelButtonText: "Cancelar"
                }).then(result => {
                    if (result.value){
                        var url = base_url + "index.php/tesoreria/caja/deshabilitar_caja";
                        $.ajax({
                            type: 'POST',
                            url: url,
                            dataType: 'json',
                            data: {
                                caja: caja
                            },
                            success: function(data){
                                if (data.result == "success") {
                                    titulo = "¡Registro eliminado!";
                                    Swal.fire({
                                        icon: "success",
                                        title: titulo,
                                        showConfirmButton: true,
                                        timer: 2000
                                    });
                                }
                                else{
                                    Swal.fire({
                                        icon: "error",
                                        title: "Sin cambios.",
                                        html: "<b class='color-red'>Algo ha ocurrido, verifique he intentelo nuevamente.</b>",
                                        showConfirmButton: true,
                                        timer: 4000
                                    });
                                }
                            },
                            complete: function(){
                                search(false);
                            }
                        });
                    }
                });
    }

    function clean(){
        $("#formCaja")[0].reset();
        $("#caja").val("");
    }


    
    function editar_caja(caja){
   var url = base_url+"index.php/tesoreria/caja/editar_caja/"+caja;
	$("#zonaContenido").load(url);
}

function eliminar_caja(caja){
	if(confirm('Esta seguro desea eliminar esta caja ?')){
		dataString = "caja="+caja;
		url = base_url+"index.php/tesoreria/caja/eliminar_caja";
		$.post(url,dataString,function(data){
			url = base_url+"index.php/tesoreria/caja/cajas";
			location.href = url;
		});
	}
}

function ver_caja(caja){
	url = base_url+"index.php/tesoreria/caja/ver_caja/"+caja;
	$("#zonaContenido").load(url);
}

function listamultiple_caja(caja){
	url = base_url+"index.php/tesoreria/movimiento/movimientos/"+caja;
	$("#zonaContenido").load(url);
}

function atras_proyecto(){
	location.href = base_url+"index.php/maestros/proyecto/proyectos";
}


function agregar_chequera() {
	
	posicion = $("#posicionEditarDos").val();
	if(posicion.trim()!=""){
		a='descripcion['+posicion+']';
		b='bancoCodigo['+posicion+']';
		c='cuenta['+posicion+']';
		d='cboSerie['+posicion+']';
		
		descripcionGeneral=$("#descripcion").val();
		$("#idldescripcion"+posicion).html(descripcionGeneral);
		document.getElementById(a).value=descripcionGeneral;
		
		document.getElementById(b).value=$("#cboBancoCuenta").val();
		document.getElementById(c).value=$("#cboCuentaCheque").val();
		document.getElementById(d).value=$("#cboSerie").val();		
		$("#idlbancoCodigo"+posicion).html($("#cboBancoCuenta option:selected").text());
		$("#idlnumroCuenta"+posicion).html($("#cboCuentaCheque option:selected").text());
		$("#idlchequera"+posicion).html($("#cboSerie option:selected").text());
		
	}else{
		chequeraCodigo 		= null;
		descripcion 		= $("#descripcion").val();
		cboBancoCuenta 		= $("#cboBancoCuenta").val();
		nombreBancoCuenta   = $("#cboBancoCuenta option:selected").text();
		cboCuentaCheque 	= $("#cboCuentaCheque").val();
		nombreCuentaCheque  = $("#cboCuentaCheque option:selected").text();
		cboSerie 			= $("#cboSerie").val();
		nombreSerie			= $("#cboSerie option:selected").text();
		n = document.getElementById('tblDetalleChequera').rows.length;   
		j = n + 1;
		if (j % 2 == 0) {
			clase = "itemParTabla";
		} else {
			clase = "itemImparTabla";
		}    
		fila = '<tr id="' + n + '" class="' + clase + '" >';
		fila += '<td width="1.5%">';
		fila += ' '+j;
		fila += '</td>';
		fila += '<input type="hidden" value="" name="chequeraCodigo[' + n + ']" id="chequeraCodigo[' + n + ']">';
		fila += '<td width="6.5%"><div align="center">'
		fila += '<label id="idldescripcion">'+ descripcion +'</label>'
		fila += '<input type="hidden" size="8" maxlength="10" class="cajaGeneral" value="' + descripcion + '" name="descripcion[' + n + ']" id="descripcion[' + n + ']"></div></td>'
		fila += '<td width="5.5%"><div align="center">'
		fila += '<label id="idlnombreBancoCuenta">'+nombreBancoCuenta+'</label>'
		fila += '<input type="hidden" size="8" maxlength="10" class="cajaGeneral" value="' + cboBancoCuenta + '" name="cboBancoCuenta[' + n + ']" id="cboBancoCuenta[' + n + ']"></div></td>'
		fila += '<td width="5.5%"><div align="center">'
		fila += '<label id="idlnombreCuentaCheque">'+nombreCuentaCheque+'</label>'
		fila += '<input type="hidden" size="8" maxlength="10" class="cajaGeneral" value="' + cboCuentaCheque + '" name="cboCuentaCheque[' + n + ']" id="cboCuentaCheque[' + n + ']"></div></td>'
		fila += '<td width="5%"><div align="center">'
		fila += '<label id="idlnombreCuentaCheque">'+nombreSerie+'</label>'
		fila += '<input type="hidden" size="8" maxlength="10" class="cajaGeneral" value="' + cboSerie + '" name="cboSerie[' + n + ']" id="cboSerie[' + n + ']"></div></td>'
		fila += '<td width="2.5%"><div align="center"><font color="red"><strong><a href="javascript:;" onclick="eliminar_chequera(' + n + ');">';
		fila += '<span style="border:1px solid red;background: #ffffff;">&nbsp;X&nbsp;</span>';
		fila += '</a></strong></font></div></td>';;
		fila += '<input type="hidden" class="cajaMinima" name="chequeaccion[' + n + ']" id="chequeaccion[' + n + ']" value="n">';
		fila += '</tr>';
		$("#tblDetalleChequera").append(fila);
		$("#chequera").focus();
	}
}

function agregar_cuenta() {
	posicion = $("#posicionEditar").val();
	if(posicion.trim()!=""){
		a='cboBancos['+posicion+']';
		b='cboCuentas['+posicion+']';
		c='tipCuenta['+posicion+']';
		d='monedaCuenta['+posicion+']';
		
		e='limiteRetiro['+posicion+']';
		f='tipoCaja['+posicion+']';
		
		limiteRetiro=$("#limiteRetiro").val();
		$("#idllimiteRetiro"+posicion).html(limiteRetiro);		
		document.getElementById(e).value=limiteRetiro;
		
		document.getElementById(a).value=$("#cboBancos").val();
		
		document.getElementById(f).value=$("#cboTipoCaja").val();
		
		document.getElementById(b).value=$("#cboCuentas").val();

		monedaCuenta=$("#monedaCuenta").val();
		$("#idlmoneda"+posicion).html(monedaCuenta);
		document.getElementById(d).value=monedaCuenta;
		
		tipCuenta=$("#tipCuenta").val();
		$("#idltipCuenta"+posicion).html(tipCuenta);
		document.getElementById(c).value=tipCuenta;

		$("#idlbancoCodigo"+posicion).html($("#cboBancos option:selected").text());
		$("#idlnumroCuenta"+posicion).html($("#cboCuentas option:selected").text());
		$("#idltipo"+posicion).html($("#cboTipoCaja option:selected").text());
		
	 inicializar_cuenta();		
	}else{
	cuentaCodigo       = null;
	cboBancos	 	   = $("#cboBancos").val();
	nombreBancos	   = $("#cboBancos option:selected").text();
	cboCuentas	 	   = $("#cboCuentas").val();
	nombreCuentas 	   = $("#cboCuentas option:selected").text();
	tipCuenta 	 	   = $("#tipCuenta").val();	
	monedaCuenta 	   = $("#monedaCuenta").val();
	tipoCaja 	 	   = $("#cboTipoCaja").val();
	tipocajaSelec=$("#cboTipoCaja option:selected").text();
	tipoCajas=$("#cboTipoCaja").val();
	if(tipoCaja == 1){
		nomTipoCaja    = "INGRESO";
	}else if (tipoCaja == 2) {
		nomTipoCaja    = "SALIDA";
	}
	NombretipoCaja 	   = $("#cboTipoCaja option:selected").text();
	limiteRetiro 	   =  $("#limiteRetiro").val();  
    n = document.getElementById('tblDetalleCuenta').rows.length; 
    //alert("caja: "+tipoCajas+" cboBancos: "+cboBancos+" tipCuenta: "+cboCuentas);
    verificarExiste=verificarCuentaCajas(tipoCajas,cboBancos,cboCuentas);
    if(!verificarExiste){
  	
    }else{
  	alert("ya Existe");
  	//alert("son iguales no");
  	return !verificarExiste;
    }
  //return false;
    j = n + 1;
    if (j % 2 == 0) {
        clase = "itemParTabla";
    } else {
        clase = "itemImparTabla";
    }    
    fila = '<tr id="' + j + '" class="' + clase + '" >';
    fila += '<td width="1%">';
    fila += ' '+j;
    fila += '<input type="hidden" value="" name="cuentaCodigo[' + j + ']" id="cuentaCodigo[' + j + ']">';
    fila += '</td>';
    fila += '<td width="6.5%"><div align="center">'
    fila += '<label id="idlbancoCodigo'+j+'">'+nombreBancos+'</label>'
    fila += '<input type="hidden" class="cajaGeneral"  name="cboBancos['+j+']" id="cboBancos['+j+']"value="'+$("#cboBancos").val()+'"/>' 
    fila += '</div></td>'
    fila += '<td width="5.5%"><div align="center">'
    fila += '<input type="hidden" size="8"  class="cajaGeneral" value="' + cboCuentas + '" name="cboCuentas[' + j + ']" id="cboCuentas[' + j + ']">'
    fila += '<label id="idlnumroCuenta'+j+'">'+nombreCuentas+'</label>'
    fila += '</div></td>'
    fila += '<td width="5%"><div align="center">'
    fila += '<input type="hidden" size="8"  class="cajaGeneral" value="' + tipCuenta + '" name="tipCuenta[' + j + ']" id="tipCuenta[' + j + ']">'
    fila += '<label id="idltipCuenta'+j+'">'+tipCuenta+'</label>'
    fila += '</div></td>'
    fila += '<td width="5%"><div align="center">'
    fila += '<label id="idlmoneda'+j+'">'+monedaCuenta+'</label>'
    fila += '<input type="hidden" size="8" class="cajaGeneral" value="' + monedaCuenta + '" name="monedaCuenta[' + j + ']" id="monedaCuenta[' + j + ']">'
    fila += '</div></td>'
    fila += '<td width="5%"><div align="center">'
    fila += '<label id="idltipo'+j+'">'+nomTipoCaja+'</label>'	
    fila += '<input type="hidden" size="8" class="cajaGeneral" value="' + tipoCajas + '" name="tipoCaja[' + j + ']" id="tipoCaja[' + j + ']">'
    fila += '</div></td>'
    fila += '<td width="5%"><div align="center">'
    fila += '<label id="idllimiteRetiro'+j+'">'+limiteRetiro+'</label>'	
    fila += '<input type="hidden" size="8" maxlength="10" class="cajaGeneral" value="' + limiteRetiro + '" name="limiteRetiro[' + j + ']" id="limiteRetiro[' + j + ']">'
    fila += '</div></td>'; 
    fila += '<td width="1%"><div align="center"><font color="red"><strong><a href="javascript:;" onclick="eliminar_cuenta(' + j + ');">';
    fila += '<span style="border:1px solid red;background: #ffffff;">&nbsp;X&nbsp;</span>';
    fila += '</a></strong></font></div></td>';
    fila += '<td  width="1%">';
    fila += '<a href="javascript:;" onclick="editar_cuenta('+j+')"><img src="'+base_url+'images/modificar.png" width="16" height="16" border="0" title="Modificar"></a>';
    fila += '<input type="hidden"  name="cuentaaccion[' + j + ']" id="cuentaaccion[' + j + ']" value="n">';
    fila += '</td>';
    fila += '</tr>';
   
    $("#tblDetalleCuenta").append(fila);

    $("#cuenta").focus();

    inicializar_cuenta();
	}


}

function inicializar_cuenta() {
    $("#cboBancos").val('');
    $("#cboCuentas").val('');
    $("#tipoCuenta").val('');
    $("#monedaCuenta").val('');
    $("#cboTipoCaja").val('');
    $("#limiteRetiro").val('');
    $("#posicionEditar").val('');
    $("#cuentaCodigo").val('');
    $("#tipCuenta").val('');
}

function editar_cuenta(posicion){

	a='cboBancos['+posicion+']';
	b='cboCuentas['+posicion+']';
	
	c='tipCuenta['+posicion+']';
	d='monedaCuenta['+posicion+']';
	e='limiteRetiro['+posicion+']';
	
	f='tipoCaja['+posicion+']';
	g='cuentaCodigo['+posicion+']';
	z='txtCuentaCodigo'+posicion;
	cboBancos=document.getElementById(a).value;
	cboCuentas=document.getElementById(b).value;
	
	tipCuenta=document.getElementById(c).value;
	monedaCuenta=document.getElementById(d).value;
	limiteRetiro=document.getElementById(e).value;
	
	tipoCaja=document.getElementById(f).value;
	cuentaCodigo=document.getElementById(g).value;

	cargar_cuantas(cboBancos,cboCuentas);

	$('#cboBancos').val(cboBancos);
//cargar_cuenta(document.getElementById(a))
	//cargar_cuenta(document.getElementById(a));
//cboCuentas
		//$('#cboCuentas').val(cboCuentas);
		cargar_datosCuenta(document.getElementById(b));
		$('#tipCuenta').val(tipCuenta);
$('#monedaCuenta').val(monedaCuenta);
$('#cboTipoCaja').val(tipoCaja);
$('#limiteRetiro').val(limiteRetiro);
$('#cuentaCodigo').val(cuentaCodigo);
$('#posicionEditar').val(posicion);
}

function editar_chequera(posicion){
	a='descripcion['+posicion+']';
	b='bancoCodigo['+posicion+']';
	
	c='cuenta['+posicion+']';
	d='cboSerie['+posicion+']';

	e='chequeraCodigo['+posicion+']';
	
	descripcion=document.getElementById(a).value;
	bancoCodigo=document.getElementById(b).value;
	
	cuenta=document.getElementById(c).value;
	cboSerie=document.getElementById(d).value;
	
	chequeraCodigo=document.getElementById(e).value;
	$('#descripcion').val(descripcion);
	$('#cboBancoCuenta').val(bancoCodigo);
	cargar_cuentaCheque(document.getElementById(b));
		$('#cboCuentaCheque').val(cuenta);
		cargar_serieCuenta(document.getElementById(c));
		$('#cboSerie').val(cboSerie);
		$('#chequeraCodigo').val(chequeraCodigo);
		$('#posicionEditarDos').val(posicion);
}


function listar_bancos(){
	n = document.getElementById('tblDetalleCuenta').rows.length;	
	for(x=0;x<n;x++){
		 valor= "cboBancos["+x+"]"; 
         valor_banco = document.getElementById(valor).value;
	}
	url = base_url+"index.php/tesoreria/caja/cargar_banco/"+valor_banco;
    $("#cboBancoCuenta").load(url);	
}


function cargar_banco_moneda(obj){
	cuenta = obj.value;
	url = base_url+"index.php/tesoreria/caja/cargar_tabla_cuenta/"+cuenta;
	$("#tableCuenta").load(url);
}

function cargar_serieNumero(obj){
	numeroSerie = obj.value;
	url = base_url+"index.php/tesoreria/caja/cargar_serie/"+numeroSerie;
	$("#numeross").load(url);
}

function cargar_serieCuenta(obj){
	cuenta = obj.value;
	url = base_url+"index.php/tesoreria/caja/cargar_serieCuenta/"+cuenta;
	$("#cboSerie").load(url);
}

function cargar_cuentaCheque(obj){
	bancoCodigo = obj.value;
	url = base_url+"index.php/tesoreria/caja/cargar_cuentaCheque/"+bancoCodigo;
	$("#cboCuentaCheque").load(url);
}

function cargar_cuenta(obj){
	bancoCodigo = obj.value;
	url = base_url+"index.php/tesoreria/caja/cargar_cuenta/"+bancoCodigo;
	$("#cboCuentas").load(url);
}
 function cargar_cuantas(cuentaCodigo,bancoCodigo){
 	url = base_url+"index.php/tesoreria/caja/cargarCuentaEmpresa/"+cuentaCodigo+"/"+bancoCodigo;
	$("#cboCuentas").load(url);
 }
 function cargar_bancoEdit(codigo){
 	url = base_url+"index.php/tesoreria/caja/cargarCuentaBanco/"+codigo;
	$("#cboBancos").load(url);
 }
function cargar_datosCuenta(obj){
	cuentaCodigo = obj.value;
	url = base_url+"index.php/tesoreria/caja/cargar_datosCuenta/"+cuentaCodigo;
	$("#TipoCuenta").load(url);
}

function eliminar_cuenta(n) {
    if (confirm('Esta seguro que desea eliminar esta Cuenta ?')) {
    	a = "cuentaCodigo[" + n + "]";
    	b = "cuentaCodigo[" + n + "]";
        fila = document.getElementById(a).parentNode;
        fila.style.display = "none";
        document.getElementById(b).value = "e";
    }
}

function eliminar_chequera(n) {
    if (confirm('Esta seguro que desea eliminar esta chequera ?')) {
    	a = "chequeraCodigo[" + n + "]";
    	b = "chequeraCodigo[" + n + "]";
        fila = document.getElementById(a).parentNode;
        fila.style.display = "none";
        document.getElementById(b).value = "e";
    }
}

function cambiar_estado_campos(estado){
    //Para los campos del banco
    $("#cboBancos").attr('disabled', estado);
    $("#sectorista").attr('disabled', estado);
    $("#telefono").attr('disabled', estado);
    $("#direccion").attr('disabled', estado);
    $("#sobregiro").attr('disabled', estado);

    //Para los campos de la persona
    $("#moneda").attr('disabled', estado);
    $("#limiteRetiro").attr('disabled', estado);
    $("#observaciones").attr('disabled', estado);
    
}
function validateFormulario(){
 // Campos de texto
 if($("#nombreCaja").val() == ""){
 $('#nombreCaja').css('background-color', '#FFC1C1').focus();
        return false;
}//|| /^\s*$/.test(la caja de texto) cuando hay muchos espacios en blanco
 if($("#cboTipCaja").val() == "" || /^\s*$/.test($("#cboTipCaja").val())){
$('#cboTipCaja').css('background-color', '#FFC1C1').focus();
      return false;
 }
 return true; // Si todo está correcto
}
function verificarCuentaCajas(codTipo,codbanco,codcuenta){
		n = document.getElementById('tblDetalleCuenta').rows.length;	
		isEncuentra=false;
		if(n!=0){
			for(x=0;x<n;x++){
					var contador=x+1;
					valorTipo=document.getElementById("tipoCaja["+contador+"]").value;
					cboBancos =document.getElementById("cboBancos["+contador+"]").value;
					cboCuentas= document.getElementById("cboCuentas["+contador+"]").value;
					if(codTipo==valorTipo 
						&& codbanco==cboBancos 
						&& codcuenta==cboCuentas){
						isEncuentra=true;	
						break;
					}
			}
		}
		return isEncuentra;
		}
	

function keypressError(id){
  $("#"+id).css({"background-color": "#fff"}); 
}

</script>
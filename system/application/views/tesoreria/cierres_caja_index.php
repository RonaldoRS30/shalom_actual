<link href="<?=base_url();?>js/fancybox/dist/jquery.fancybox.css?=<?=CSS;?>" rel="stylesheet">
<script src="<?=base_url();?>js/fancybox/dist/jquery.fancybox.js?=<?=JS;?>"></script>

<div class="col-sm-2 col-md-2 col-lg-2">
                <label for="search_codigo">CAJA</label>
                <select name="search_codigo" id="search_codigo" class="form-control w-porc-90 h-2">
                    <option value=""> :: TODOS :: </option> <?php
                    if ($caja != NULL){
                        foreach ($caja as $indice => $val){ ?>
                            <option value="<?=$val->CAJA_Codigo;?>"><?=$val->CAJA_Nombre;?></option> <?php
                        }
                    } ?>
                </select>
</div>
<div class="col-sm-2 col-md-2 col-lg-2">
                <label for="search_fechai">FECHA INICIO</label>
                <input type="datetime-local" name="search_fechai" id="search_fechai" value="" class="form-control h-1 w-porc-90"/>
</div>
<div class="col-sm-2 col-md-2 col-lg-2">
                <label for="search_fechaf">FECHA FIN</label>
                <input type="datetime-local" name="search_fechaf" id="search_fechaf" value="" class="form-control h-1 w-porc-90"/>
</div>
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
                <input type="text" name="search_codigo" id="search_codigo" value="" placeholder="Codigo de caja" class="form-control h-1 w-porc-90"/>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <input type="text" name="search_descripcion" id="search_descripcion" value="" placeholder="Nombre de la caja" class="form-control h-1 w-porc-90"/>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <div class="acciones">
                        <div id="botonBusqueda">
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
                        <table class="table table-striped table-bordered" id="table-cierre">
                            <thead class="cabeceraTabla">
                                <tr>
                                    <th style="width: 5%;">N°</th>
                                    <th style="width: 15%;">F.APERTURA</th>
                                    <th style="width: 15%;">F.CIERRE</th>
                                    <th style="width: 8%;">CAJA</th>
                                    <th style="width: 8%;">INGRESOS</th>
                                    <th style="width: 8%;">EGRESOS</th>
                                    <th style="width: 8%;">SALDO</th>
                                    <th style="width: 15%;">F.REGISTRO</th>
                                    <th style="width: 5%;"></th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se cargarán los datos dinámicamente -->
                            </tbody>
                        </table>
                        <div id="cargando_datos" class="loading-table text-center">
                            <img src="<?= base_url() . 'images/loading.gif?' . IMG; ?>" alt="Loading...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="modal_movimientoscierre" class="modal fade">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4>MOVIMIENTOS GENERADOS - CIERRE <span class="titleCierre"></span></h4>
                </div>
            </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <label>CAJA:</label> <span class="titleCaja"></span>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label>RESPONSABLE:</label> <span class="titleResponsable"></span>
                        </div>
                        <div class="col-lg-4 form-group">
                            <a href='#' onclick="reportePdf()">
                                <img src="<?php echo base_url();?>images/pdf.png" width="22" height="22" 
                                     class="imgBoton img-fluid">
                            </a> 
                            <!--a href="#" data-fancybox data-type='iframe' id="imprimir_detalle_cierres_excel">
                                <img src="< ?php echo base_url();?>public/images/icons/xls.png" width="25" height="25" 
                                     class="imgBoton img-fluid imprimir_tickets_activos">
                            </a-->                            
                        </div>
                    </div>
                        <input type="datetime-local" id="fecha_inicio" name="fecha_inicio" hidden>
                        <input type="datetime-local" id="fecha_fin" name="fecha_fin" hidden>

                    <div class="row mt-0">
                        <div class="col-lg-4 form-group">
                            <label>INGRESOS:</label> <span class="titleIngresos"></span>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label>EGRESOS:</label> <span class="titleEgresos"></span>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label>SALDO:</label> <span class="titleSaldos"></span>
                        </div>                        
                    </div>                    

                    <div class="row">
                        <div class="col-lg-11-5 form-group">
                            <table class="table table-striped table-bordered" id="table-movimientoscierre">
                                <thead>
                                    <tr>
                                        <td style="width:15%" data-orderable="true">F.REGISTRO</td>
                                        <td style="width:15%" data-orderable="true">F.MOVIMIENTO</td>
                                        <td style="width:15%" data-orderable="false">SERIE</td>
                                        <td style="width:15%" data-orderable="false">NUMERO</td>
                                        <td style="width:15%" data-orderable="true">F.PAGO</td>
                                        <td style="width:10%" data-orderable="false">MONTO</td>
                                        <td style="width:10%" data-orderable="false">MOVIMIENTO</td>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                    <input type="hidden" id="documento_cliente" name="documento_cliente">
                </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#table-cierre').DataTable({ responsive: true,
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax:{
                    url : '<?=base_url();?>index.php/tesoreria/cajacierre/datatable_cajacierre/',
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
            order: [[ 1, "desc" ]],
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

        $('#search_descripcion').keyup(function(e){
            if ( e.which == 13 ){
                if( $(this).val() != '' )
                    search();
            }
        });
    });

    function search(){

        
        $('#table-cierre').DataTable({ responsive: true,
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax:{
                    url : '<?=base_url();?>index.php/tesoreria/cajacierre/datatable_cajacierre/',
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
            order: [[ 1, "desc" ]],
        });
    }

    function closecaja(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas confirmar la operación de cerrar la caja?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cerrar caja',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var url = '<?=base_url();?>index.php/tesoreria/cajacierre/Closecaja/';
            var flag = 0;

            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: {
                    id: id,
                    flagC: flag,
                },
                success: function(data) {
                    Swal.fire(
                        '¡Cerrada!',
                        'La caja ha sido cerrada correctamente.',
                        'success'
                    );
                    search();
                },
                error: function() {
                    Swal.fire(
                        'Error',
                        'Hubo un problema al cerrar la caja.',
                        'error'
                    );
                }
            });
        } else {
            Swal.fire(
                'Cancelado',
                'La operación ha sido cancelada.',
                'info'
            );
        }
    });
}

/* INFORMACIÓN DE CAJAS */

function modal_movimientoscierre(cajacierre, caja='', responsable='',ingresos='0',egresos='0',saldo='0', fechaI, fechaF){
	//Asignamos los enlaces a los botones
	url = base_url + "index.php/tesoreria/cajacierre/imprimir_detalle_cierres/"+cajacierre;
	url2 = base_url + "index.php/tesoreria/cajacierre/imprimir_detalle_cierres_excel/"+cajacierre;
	$("#modal_movimientoscierre").modal("toggle"); 
	$("#imprimir_detalle_cierres").attr("href", url)
	$("#imprimir_detalle_cierres_excel").attr("href", url2)
	
	//Colocamos etiquetas
	$(".titleCaja").html(caja);
	$(".titleIngresos").html(ingresos);
	$(".titleEgresos").html(egresos);
	$(".titleSaldos").html(saldo);
	$(".titleCierre").html(cajacierre);
	$(".titleResponsable").html(responsable);

    $("#fecha_inicio").val(fechaI);
	$("#fecha_fin").val(fechaF);
	getTableMovimientosCierre(cajacierre);
}

function reportePdf() {
    // Obtener las fechas seleccionadas
    let fechaInicio = document.getElementById('fecha_inicio').value;
    let fechaFin = document.getElementById('fecha_fin').value;

	if(fechaInicio == ''&& fechaFin == ''){
		Swal.fire({
				icon: "warning",
				title: "¡Sin fecha de cierre!",
				html: "<b class='color-red'></b>",
			});
		return;
	}
    fechasfinales = fechaFin;

	 fechaInicio = convertirFormato24Horas(fechaInicio);
	 fechaFin = convertirFormato24Horas(fechaFin);
       
    // Realizar la llamada AJAX para obtener el PDF con los resultados filtrados
    let pdfUrl = "<?= base_url(); ?>index.php/reportes/ventas/ventasDiarias_pdf_cierre/" + fechaInicio + "/" + fechaFin;

    fetch(pdfUrl, {
        method: 'GET',
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        $("#pdfFrame").attr("src", url);
        $("#modal_ticket2").modal("show");
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Ingreso de caja '+ fechasfinales +'.pdf';
        a.style.display = 'none';
        document.body.appendChild(a);
        a.click();
        a.onload = function() {
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        };
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function convertirFormato24Horas(fechaInput) {
    // Dividir la fecha y la hora
    let partesFechaHora = fechaInput.split("T");
    let fecha = partesFechaHora[0];
    let horaMinutos = partesFechaHora[1];

    // Dividir la hora y los minutos
    let partesHoraMinutos = horaMinutos.split(":");
    let hora = partesHoraMinutos[0];
    let minutos = partesHoraMinutos[1];

    // Devolver la fecha y hora en formato de 24 horas
    return `${fecha} ${hora}:${minutos}`;
}

function getTableMovimientosCierre(cajacierre){
	$('#table-movimientoscierre').DataTable({ responsive: true,
		filter: false,
		destroy: true,
		processing: true,
		serverSide: true,
		autoWidth: false,
		ajax:{
			url : base_url + 'index.php/tesoreria/cajacierre/datatable_movimientoscierre',
			type: "POST",
			data: {
				cajacierre: cajacierre,
			},
			beforeSend: function(){
				$("#table-movimientoscierre .loading-table").show();
			},
			error: function(){
			},
			complete: function(){
			}
		},
		language: spanish,
		order: [[ 0, "asc" ]]
	});
}


</script>
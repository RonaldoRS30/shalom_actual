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
			<div class="col-sm-9 col-md-9 col-lg-9"></div>
			<div class="col-sm-2 col-md-2 col-lg-2">
				<input type="text" name="nombre_ruta_seach" id="nombre_ruta_seach" value="" placeholder="Buscar Ruta" class="form-control h-1" autocomplete="off"/>
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
								<li id="nuevo" data-toggle='modal' data-target='#add_ruta'>Transporte</li>
							</ul>
							<ul id="limpiarC" class="lista_botones">
								<li id="limpiar">Limpiar</li>
							</ul>
							<ul id="buscarC" class="lista_botones">
								<li id="buscar">Buscar</li>
							</ul> 
						</div>
						<!-- <div id="lineaResultado">Registros encontrados</div> -->
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
					<table class="fuente8 display" id="table-ruta">
						<div id="cargando_datos" class="loading-table">
							<img src="<?=base_url().'images/loading.gif?='.IMG;?>">
						</div>
						<thead>
							<tr class="cabeceraTabla">
								<td style="width:05%" data-orderable="false">N°</td>
								<td style="width:05%" data-orderable="false">NOMBRE RUTA</td>
								<td style="width:05%" data-orderable="false">RUC E. TRANSPORTE</td>
								<td style="width:05%" data-orderable="false">E. TRANSPORTE</td>
								<td style="width:05%" data-orderable="false">CONDUCTOR</td>
								<td style="width:05%" data-orderable="false">LICENCIA</td>
								<td style="width:05%" data-orderable="false">PLACA</td>
								<td style="width:05%" data-orderable="false">MARCA</td>
								<td style="width:05%" data-orderable="false">CERTIFICADO</td>
								<td style="width:05%" data-orderable="false">REGISTRO MTC</td>
								<td style="width:05%" data-orderable="false">EDITAR</td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="add_ruta" class="modal fade" role="dialog">
	<div class="modal-dialog w-porc-60">
		<div class="modal-content">
			<form id="formruta" method="POST">
				<div class="modal-header">
					<h4 class="modal-title"></h4>
				</div>
				<div style="text-align: center;">
					<h3><b>REGISTRAR TRANSPORTE</b></h3>
				</div>
				<div class="modal-body panel panel-default">
					<input type="hidden" id="ruta" name="ruta" value="">

					<div class="row">

					<div class="col-md-5">
						<label>Nombre de Ruta</label>
						<input class="form-control" type="text" name="nombre_ruta" id="nombre_ruta">
					</div>

					

        	<div class="col-md-10">
						<label>Empresa Transporte</label>
					</div>

					
					<div class="col-md-3">
						<label>RUC</label>
						<input type="text" id="ruc" name="ruc" class="form-control">
					</div>
					<div class="col-md-6">
						<label>NOMBRE EMPRESA TRANSPORTE</label>
						<input type="text" id="nombre" name="nombre" class="form-control">
					</div>
					<br>

					<br>
					<div class="col-md-10">
						<label>Datos del Conductor</label>
					</div>


					<div class="col-md-3">
						<label>NOMBRES</label>
						<input type="text" id="nombre_conductor" name="nombre_conductor" class="form-control">
					</div>

					<div class="col-md-3">
						<label>APELLIDOS</label>
						<input type="text" id="apellido_conductor" name="apellido_conductor" class="form-control">
					</div>

					<div class="col-md-3">
						<label>DNI</label>
						<input type="text" id="dni_conductor" name="dni_conductor" class="form-control">
					</div>
					<hr>
					<br>
					<div class="col-md-10">
						<label>Automovil</label>
					</div>

					<div class="col-md-3">
						<label>LICENCIA</label>
						<input type="text" id="licencia" name="licencia" class="form-control">
					</div>

					<div class="col-md-3">
						<label>PLACA</label>
						<input type="text" id="placa" name="placa" class="form-control">
					</div>

					<div class="col-md-3">
						<label>MARCA</label>
						<input type="text" id="marca" name="marca" class="form-control">
					</div>

					<div class="col-md-3">
						<label>CERTIFICADO</label>
						<input type="text" id="certificado" name="certificado" class="form-control">
					</div>
					<div class="col-md-3">
						<label>REGISTRO MTC</label>
						<input type="text" id="mtc" name="mtc" class="form-control">
					</div>
					</div>




				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" accesskey="x" onclick="registrar_ruta()">Guardar Registro</button>
					<button type="button" class="btn btn-info" onclick="clean()">Limpiar</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	base_url = "<?=$base_url;?>";

	function registrar_ruta()
	{

		 var parametros=$("#formruta").serialize();
     $.ajax({
            data:  parametros, 
            url:   '<?=base_url(); ?>index.php/maestros/ruta/guardar_registro', 
            type:  'POST',
            beforeSend: function(){
            	$("#add_ruta").modal("toggle");
            },
            success:  function (response) 
            {
								Swal.fire(
								  'Exito',
								  'Información Guardada',
								  'success'
								)
								buscar();
            }
        });

	}

	function buscar(pase=true)
	{
		var nombre_ruta="";
		if (pase==true) 
		{
		 nombre_ruta=$("#nombre_ruta_seach").val();
		}
		else
		{
			$("#nombre_ruta_seach").val("");
		}

		$('#table-ruta').DataTable({ responsive: true,
			filter: false,
			destroy: true,
			processing: true,
			serverSide: true,
			autoWidth: false,
			ajax:{
				url : '<?=base_url();?>index.php/maestros/ruta/datatable_ruta/',
				type: "POST",
				data: { nombre: nombre_ruta },
				beforeSend: function(){
				},
				error: function(){
				},
				complete: function(){
				}
			},
			language: spanish,
			columnDefs: [{"className": "dt-center", "targets": 0}],
			order: [[ 1, "asc" ]]
		});
	}

	$(document).ready(function(){

		$("#nuevo").click(function()
			{
				clean();

			});
			buscar();

		$("#buscarC").click(function()
			{
				buscar();
			});
		$("#limpiarC").click(function()
			{
				buscar(false);
			});
	});

	// 	$("#buscarC").click(function(){
	// 		search();
	// 	});

	// 	$("#limpiarC").click(function(){
	// 		search(false);
	// 	});

	// 	$('#form_busqueda').keypress(function(e){
	// 		if ( e.which == 13 ){
	// 			return false;
	// 		} 
	// 	});

	// 	$('#nombre_cargo').keyup(function(e){
	// 		if ( e.which == 13 ){
	// 			if( $(this).val() != '' )
	// 				search();
	// 		}
	// 	});
	// });

	// function search( search = true){
	// 	if (search == true){
	// 		nombre = $("#nombre_cargo").val();
	// 	}
	// 	else{
	// 		$("#nombre_cargo").val("");
	// 		nombre = "";
	// 	}
		
	// 	$('#table-cargo').DataTable({ responsive: true,
	// 		filter: false,
	// 		destroy: true,
	// 		processing: true,
	// 		serverSide: true,
	// 		ajax:{
	// 			url : '<?=base_url();?>index.php/maestros/cargo/datatable_cargo/',
	// 			type: "POST",
	// 			data: {
	// 				nombre: nombre
	// 			},
	// 			beforeSend: function(){
	// 				$("#table-cargo .loading-table").show();
	// 			},
	// 			error: function(){
	// 			},
	// 			complete: function(){
	// 				$("#table-cargo .loading-table").hide();
	// 			}
	// 		},
	// 		language: spanish,
	// 		columnDefs: [{"className": "dt-center", "targets": 0}],
	// 		order: [[ 1, "asc" ]]
	// 	});
	// }

	function editar(id){
		var url = base_url + "index.php/maestros/ruta/getRuta";
		$.ajax({
			type: 'POST',
			url: url,
			dataType: 'json',
			data:{
				ruta: id
			},
			beforeSend: function(){
				clean();
			},
			success: function(data){

				if (data.match == true) {
					info = data.info;
					$("#ruta").val(info.COD_Ruta);
					$("#nombre_ruta").val(info.Nombre_Ruta);
					$("#ruc").val(info.Ruc_Empresa);
					$("#nombre").val(info.Nombre_Empresa);
					$("#nombre_conductor").val(info.Nombre_Conductor);
					$("#apellido_conductor").val(info.Apellido_Conductor);
					$("#dni_conductor").val(info.Dni_Conductor);
					$("#licencia").val(info.Licencia);
					$("#placa").val(info.Placa);
					$("#marca").val(info.Marca);
					$("#certificado").val(info.Certificado);
					$("#mtc").val(info.MTC);
					$("#add_ruta").modal("toggle");
				}

			},
			complete: function(){
			}
		});
	}

	// function registrar_cargo(){
	// 	Swal.fire({
	// 		icon: "info",
	// 		title: "¿Esta seguro de guardar el registro?",
	// 		html: "<b class='color-red'></b>",
	// 		showConfirmButton: true,
	// 		showCancelButton: true,
	// 		confirmButtonText: "Aceptar",
	// 		cancelButtonText: "Cancelar"
	// 	}).then(result => {
	// 		if (result.value){
	// 			var cargo = $("#cargo").val();
	// 			var url = base_url + "index.php/maestros/cargo/guardar_registro";
	// 			var nombre = $("#cargo_nombre").val();
	// 			var descripcion = $("#cargo_descripcion").val();
	// 			validacion = true;

	// 			if (nombre == ""){
	// 				Swal.fire({
	// 					icon: "error",
	// 					title: "Verifique los datos ingresados.",
	// 					html: "<b class='color-red'>Debe ingresar un nombre.</b>",
	// 					showConfirmButton: true,
	// 					timer: 4000
	// 				});
	// 				$("#cargo_descripcion").focus();
	// 				validacion = false;
	// 			}

	// 			if (validacion == true){
	// 				$.ajax({
	// 					type: 'POST',
	// 					url: url,
	// 					dataType: 'json',
	// 					data: {
	// 						cargo: cargo,
	// 						cargo_nombre: nombre,
	// 						cargo_descripcion: descripcion
	// 					},
	// 					success: function(data){
	// 						if (data.result == "success") {
	// 							if (cargo == "")
	// 								titulo = "¡Registro exitoso!";
	// 							else
	// 								titulo = "¡Actualización exitosa!";

	// 							Swal.fire({
	// 								icon: "success",
	// 								title: titulo,
	// 								showConfirmButton: true,
	// 								timer: 2000
	// 							});

	// 							clean();
	// 						}
	// 						else{
	// 							Swal.fire({
	// 								icon: "error",
	// 								title: "Sin cambios.",
	// 								html: "<b class='color-red'>La información no fue registrada/actualizada, intentelo nuevamente.</b>",
	// 								showConfirmButton: true,
	// 								timer: 4000
	// 							});
	// 						}
	// 					},
	// 					complete: function(){
	// 						$("#cargo_nombre").focus();
	// 					}
	// 				});
	// 			}
	// 		}
	// 	});
	// }

	// function deshabilitar(cargo){
	// 	Swal.fire({
	// 		icon: "info",
	// 		title: "¿Esta seguro de eliminar el registro seleccionado?",
	// 		html: "<b class='color-red'>Esta acción no se puede deshacer.</b>",
	// 		showConfirmButton: true,
	// 		showCancelButton: true,
	// 		confirmButtonText: "Aceptar",
	// 		cancelButtonText: "Cancelar"
	// 	}).then(result => {
	// 		if (result.value){
	// 			var url = base_url + "index.php/maestros/cargo/deshabilitar_cargo";
	// 			$.ajax({
	// 				type: 'POST',
	// 				url: url,
	// 				dataType: 'json',
	// 				data: {
	// 					cargo: cargo
	// 				},
	// 				success: function(data){
	// 					if (data.result == "success") {
	// 						titulo = "¡Registro eliminado!";
	// 						Swal.fire({
	// 							icon: "success",
	// 							title: titulo,
	// 							showConfirmButton: true,
	// 							timer: 2000
	// 						});
	// 					}
	// 					else{
	// 						Swal.fire({
	// 							icon: "error",
	// 							title: "Sin cambios.",
	// 							html: "<b class='color-red'>La información no pudo ser eliminada, intentelo nuevamente.</b>",
	// 							showConfirmButton: true,
	// 							timer: 4000
	// 						});
	// 					}
	// 				},
	// 				complete: function(){
	// 				}
	// 			});
	// 		}
	// 	});
	// }

	function clean(){
		$("#formruta")[0].reset();
		$("#ruta").val("");
	}
</script>
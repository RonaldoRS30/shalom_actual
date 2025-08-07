<?php
$nombre_persona = $this->session->userdata('nombre_persona');
$persona = $this->session->userdata('persona');
$usuario = $this->session->userdata('usuario');
$url = base_url() . "index.php";
if (empty($persona))
    header("location:$url");

?>
<html>
<head>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js?=<?=JS;?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-1.8.17.custom.min.js?=<?=JS;?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/funciones.js?=<?=JS;?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/ventas/importacion.js?=<?=JS;?>"></script>
    <script src="<?php echo base_url(); ?>js/jquery.columns.min.js?=<?=JS;?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/fancybox/jquery.mousewheel-3.0.4.pack.js?=<?=JS;?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/fancybox/jquery.fancybox-1.3.4.pack.js?=<?=JS;?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/fancybox/jquery.fancybox-1.3.4.css?=<?=CSS;?>"
    media="screen"/>

    <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js?=<?=JS;?>"></script>
    <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.js?=<?=JS;?>"></script>

    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.css?=<?=CSS;?>" rel="stylesheet">
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap-theme.css?=<?=CSS;?>" rel="stylesheet">

    <script type="text/javascript">
        $(document).ready(function () {
            
            /**dialogo series asosicadas**/
            $("#dialogSeriesAsociadas").dialog({
                resizable: false,
                height: "auto",
                width: 400,
                autoOpen: false,
                show: {
                   effect: "blind",
                   duration: 500
               },
               hide: {
                   effect: "blind",
                   duration: 500
               }
           });
            /**fin **/

            /**dialogo series asosicadas**/
            $("#dialogoSeleccionarALmacenProducto").dialog({
                resizable: false,
                height: "auto",
                width: 400,
                autoOpen: false,
                show: {
                   effect: "blind",
                   duration: 500
               },
               hide: {
                   effect: "blind",
                   duration: 500
               },
               buttons: {
                 "Aceptar": function() {
                    grabarSeleccionarAlmacen();
                },
                Cancel: function() {
                   $(this).dialog( "close" );
               }
           }
       });
            /**fin **/
            /***verificacion de si es editar y esta relacionada con otras guias **/
            <?php   if(count($listaGuiaremAsociados)>0){  ?>
             document.getElementById("buscar_producto").readOnly = true;
             $("#idDivAgregarProducto").hide(200);
             <?php } ?>
             /***fin de realizar verificacion**/

             /**ejecutar mostrar orden de compra vista si existe**/
             <?php if($ordencompra!=0 &&  trim($ordencompra)!="" && $ordencompra!=null){   ?>
                 mostrarOdenCompraVista(<?php echo $ordencompra.",".$serieOC.",".$numeroOC.",". $valorOC; ?>);
                 <?php } ?>
                 /**no mostrar**/
                 /**ejecutar mostrar PRESUPUESTO vista si existe**/
                 <?php if($presupuesto_codigo!=0 &&  trim($presupuesto_codigo)!="" && $presupuesto_codigo!=null){   ?>
                     mostrarPresupuestoVista(<?php echo $presupuesto_codigo.",'".$seriePre."',".$numeroPre.",'". $tipo_oper."'"; ?>);7
                     <?php } ?>
                     /**no mostrar**/



                     if ($('#tdc').val() == '') {
                        alert("Antes de registrar importacion debe ingresar Tipo de Cambio");
                        top.location = "<?php echo base_url(); ?>index.php/index/inicio";
                    }

                    base_url = $("#base_url").val();
                    tipo_oper = $("#tipo_oper").val();
                    almacen = $("#cboCompania").val();

                    $("a#linkVerCliente, a#linkSelecCliente, a#linkVerProveedor, a#linkSelecProveedor").fancybox({
                        'width': 800,
                        'height': 550,
                        'autoScale': false,
                        'transitionIn': 'none',
                        'transitionOut': 'none',
                        'showCloseButton': true,
                        'modal': false,
                        'type': 'iframe'

                    });

                    $(" #linkSelecProducto").fancybox({
                        'width': 800,
                        'height': 500,
                        'autoScale': false,
                        'transitionIn': 'none',
                        'transitionOut': 'none',
                        'showCloseButton': true,
                        'modal': false,
                        'type': 'iframe'

                    });

                    $("a#linkVerProducto").fancybox({
                        'width': 800,
                        'height': 650,
                        'autoScale': false,
                        'transitionIn': 'none',
                        'transitionOut': 'none',
                        'showCloseButton': true,
                        'modal': true,
                        'type': 'iframe'
                    });

                    $("#linkVerImpresion").fancybox({
                        'width': 300,
                        'height': 450,
                        'autoScale': false,
                        'transitionIn': 'none',
                        'transitionOut': 'none',
                        'showCloseButton': true,
                        'modal': true
                    });

                    $(".verDocuRefe").fancybox({
                        'width': 770,
                        'height': 520,
                        'autoScale': false,
                        'transitionIn': 'none',
                        'transitionOut': 'none',
                        'showCloseButton': true,
                        'modal': false,
                        'type': 'iframe',
                        'onStart': function () {
                           tipoMoneda=$("#moneda").val();
                           almacen=$("#almacen").val();
                           if (tipo_oper == 'V') {
                            if ($('#cliente').val() == '') {
                                alert('Debe seleccionar el cliente.');
                                $('#nombre_cliente').focus();
                                return false;
                            } else {
                                if ($(".verDocuRefe::checked").val() == 'G')
                                    baseurl = base_url + 'index.php/almacen/guiarem/ventana_muestra_guiarem/' + tipo_oper + '/' + $('#cliente').val() + '/SELECT_HEADER/F/' + almacen + '/G/'+tipoMoneda;
                                else if ($('.verDocuRefe::checked').val() == 'P')
                                    baseurl = base_url + 'index.php/ventas/presupuesto/ventana_muestra_presupuestoCom/' + tipo_oper + '/' + $('#cliente').val() + '/SELECT_HEADER/<?php echo $tipo_docu; ?>/' + almacen + '/P';
                                else if ($('.verDocuRefe::checked').val() == 'O')
                                    baseurl = base_url + 'index.php/compras/ocompra/ventana_muestra_ocompra_importacion/' + tipo_oper + '/' + $('#cliente').val() + '/SELECT_HEADER/<?php echo $tipo_docu; ?>/' + almacen + '/O';
                                else if ($('.verDocuRefe::checked').val() == 'R')
                                    baseurl = base_url + 'index.php/ventas/comprobante/ventana_muestra_recurrentes/' + tipo_oper + '/' + $('#cliente').val() + '/SELECT_HEADER/<?php echo $tipo_docu; ?>/' + almacen + '/R';

                                $('.verDocuRefe::checked').attr('href', baseurl);
                            }
                        } else {

                            if ($('#proveedor').val() == '') {
                                alert('Debe seleccionar el proveedor.');
                                $('#nombre_proveedor').focus();
                                return false;
                            } else {
                                if ($('.verDocuRefe::checked').val() == 'G') {
                                    baseurl = base_url + 'index.php/almacen/guiarem/ventana_muestra_guiarem/' + tipo_oper + '/' + $('#proveedor').val() + '/SELECT_HEADER/F/' + almacen + '/G/'+tipoMoneda;
                                }
                                else if ($('.verDocuRefe::checked').val() == 'P') {
                                    if (tipo_oper == 'V')
                                        baseurl = base_url + 'index.php/ventas/presupuesto/ventana_muestra_presupuestoCom/' + tipo_oper + '/' + $('#proveedor').val() + '/SELECT_HEADER/<?php echo $tipo_docu; ?>/' + almacen + '/P';
                                    else
                                        baseurl = base_url + 'index.php/compras/presupuesto/ventana_muestra_presupuestoCom/' + tipo_oper + '/' + $('#proveedor').val() + '/SELECT_HEADER/<?php echo $tipo_docu; ?>/' + almacen + '/P';
                                }
                                else if ($('.verDocuRefe::checked').val() == 'O') {
                                    baseurl = base_url + 'index.php/compras/ocompra/ventana_muestra_ocompra_importacion/' + tipo_oper + '/' + $('#proveedor').val() + '/SELECT_HEADER/<?php echo $tipo_docu; ?>/' + almacen + '/O';
                                }
                                else if ($('.verDocuRefe::checked').val() == 'R') {
                                    baseurl = base_url + 'index.php/ventas/comprobante/ventana_muestra_recurrentes/' + tipo_oper + '/' + $('#proveedor').val() + '/SELECT_HEADER/<?php echo $tipo_docu; ?>/' + almacen + '/R';
                                }
                            //alert(baseurl);

                            $('.verDocuRefe::checked').attr('href', baseurl);
                        }
                    }
                }
            });

});

//AUTOCOMPLETO DE PRODUCTOS
$(function () {
    $("#buscar_producto").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/almacen/producto/autocomplete/" + $("#flagBS").val() + "/" + $("#compania").val()+"/"+$("#almacen").val(),
                type: "POST",
                data: {
                    term: $("#buscar_producto").val()
                },
                dataType: "json",
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
           /**si el producto tiene almacen : es que no esta inventariado en ese almacen , se le asigna el almacen general de cabecera**/
           if(ui.item.almacenProducto==0){
               ui.item.almacenProducto=$("#almacen").val();
           }
           /**fin de asignacion**/
           isEncuentra=verificarProductoDetalle(ui.item.codigo,ui.item.almacenProducto);
           if(!isEncuentra){
               $("#buscar_producto").val(ui.item.codinterno);
               $("#producto").val(ui.item.codigo);
               $("#codproducto").val(ui.item.codinterno);
               $("#costo").val(ui.item.pcosto);
               $("#stock").val(ui.item.stock);
               $("#flagGenInd").val(ui.item.flagGenInd);
               $("#almacenProducto").val(ui.item.almacenProducto);
               $("#cantidad").focus();
               listar_unidad_medida_producto(ui.item.codigo);
                        //verificar_Inventariado_producto();
                    }else{
                    	$("#buscar_producto").val("");
                      $("#producto").val("");
                      $("#codproducto").val("");
                      $("#costo").val("");
                      $("#stock").val("");
                      $("#flagGenInd").val("");
                      $("#nombre_producto").val("");
                      $("#almacenProducto").val("");
                      $("#buscar_producto").val("");
                      alert("El producto ya se encuentra ingresado en la lista de detalles.");
                      return !isEncuentra;
                  }
              },
              minLength: 1
          });


//****** nuevo para ruc
$("#buscar_cliente").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/ventas/cliente/autocomplete_ruc/",
            type: "POST",
            data: {
                term: $("#buscar_cliente").val()
            },
            dataType: "json",
            success: function (data) {
                response(data);
            }
        });
    },
    select: function (event, ui) {
     $("#nombre_cliente").val(ui.item.nombre);
     $("#cliente").val(ui.item.codigo);
     $("#ruc_cliente").val(ui.item.ruc);
     $("#buscar_producto").focus();
     codigo=ui.item.codigo;
     get_obra(codigo);
 },
 minLength: 2
});

/* Descativado hasta corregir vico 22082013 - quien es vico? (fixed) - pregunto lo mismo que es vicio(ABAc). */

            //AUTOCOMENTADO EN CLIENTE BUSCAR
            $("#nombre_cliente").autocomplete({
                //flag = $("#flagBS").val();
                source: function (request, response) {
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/ventas/cliente/autocomplete/",
                        type: "POST",
                        data: {
                            term: $("#nombre_cliente").val()
                        },
                        dataType: "json",
                        success: function (data) {
                            response(data);
                        }
                    });

                },

                select: function (event, ui) {
                    $("#buscar_cliente").val(ui.item.ruc);
                    $("#cliente").val(ui.item.codigo);
                    $("#ruc_cliente").val(ui.item.ruc);
                    $("#buscar_producto").focus();
                    codigo=ui.item.codigo;
                    get_obra(codigo);
                },
                minLength: 2
            });


            /* Descativado hasta corregir vico 22082013  */
            nombreProveedorAnterior="";
            $("#nombre_proveedor").autocomplete({
                //flag = $("#flagBS").val();
                source: function (request, response) {
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/compras/proveedor/autocomplete/",
                        type: "POST",
                        data: {
                            term: $("#nombre_proveedor").val()
                        },
                        dataType: "json",
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {

                    /**verificamos si tiene un proveedor agregadoiy si se modifica debe eliminar todo los demas**/
                    n = document.getElementById('idTableGuiaRelacion').rows.length;
                    if(n>1){
                    	if(confirm("¿Desea cambiar de proveedor, se eliminaran las guias relacionadas?")){

                        }else{
                        	//$("#nombre_proveedor").val(nombreProveedorAnterior);
                        }
                    }else{
                    	$("#buscar_proveedor").val(ui.item.ruc);
                        $("#proveedor").val(ui.item.codigo);
                        $("#ruc_proveedor").val(ui.item.ruc);
                        $("#buscar_producto").focus();
                    }	
                },
                minLength: 1
            });

            //****** nuevo para ruc PROVEEDOR
            $("#buscar_proveedor").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/compras/proveedor/autocomplete_ruc/",
                        type: "POST",
                        data: {
                            term: $("#buscar_proveedor").val()
                        },
                        dataType: "json",
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    $("#nombre_proveedor").val(ui.item.nombre);
                    $("#proveedor").val(ui.item.codigo);
                    $("#ruc_proveedor").val(ui.item.ruc);
                    $("#buscar_producto").focus();
                },
                minLength:2 
            });



        });
/*-agregado fa--------------------------------------------------------*/

$("#linkVerproyectoss").click(function () {
    if (tipo_oper == 'V')
        var url = base_url + "index.php/maestros/proyecto/JSON_listar_proyectos/" +$("#cliente").val();
    $("#lista_proyecto ul").html('');
    $("#lista_proyecto").slideToggle("fast", function () {

        $.getJSON(url, function (data) {
            $.each(data, function (i, item) {
                fila = '';
                fila += '<li><a href="javascript:;">';
                if (item.nombre != '')
                    fila += ' ' + item.nombre;
                if (item.descripcion != '')
                    fila += ' - ' + item.descripcion;
                fila += '</a></li>';
                $("#lista_proyecto  ul").append(fila);
            });
        });
    });
}); 
/*--------------------------------------------------*/

function seleccionar_cliente(codigo, ruc, razon_social) {
    $("#cliente").val(codigo);
    $("#buscar_cliente").val(ruc);
    $("#nombre_cliente").val(razon_social);
    get_obra(codigo);
}
function seleccionar_proveedor(codigo, ruc, razon_social) {
    $("#proveedor").val(codigo);
    $("#buscar_proveedor").val(ruc);
    $("#nombre_proveedor").val(razon_social);
}

function seleccionar_producto(producto, cod_interno, familia, stock, costo, flagGenInd,codigoAlmacenProducto) {
   /**si el producto tiene almacen : es que no esta inventariado en ese almacen , se le asigna el almacen general de cabecera**/
   if(codigoAlmacenProducto==0){
       codigoAlmacenProducto=$("#almacen").val();
   }
   /**fin de asignacion**/
   /**verificamos si se e3ncuentra en la lista**/
   isEncuentra=verificarProductoDetalle(producto,codigoAlmacenProducto);
   if(!isEncuentra){
       $("#codproducto").val(cod_interno);
       $("#producto").val(producto);
       $("#cantidad").focus();
       $("#stock").val(stock);
       $("#costo").val(costo);
       $("#flagGenInd").val(flagGenInd);
       $("#almacenProducto").val(codigoAlmacenProducto);
       listar_unidad_medida_producto(producto);
   }else{
       $("#buscar_producto").val("");
       $("#producto").val("");
       $("#codproducto").val("");
       $("#costo").val("");
       $("#stock").val("");
       $("#flagGenInd").val("");
       $("#nombre_producto").val("");
       $("#almacenProducto").val("");
       $("#buscar_producto").val("");
       $("#buscar_producto").focus();
       alert("El producto ya se encuentra ingresado en la lista de detalles.");
   }

}

function seleccionar_documento_detalle(producto, codproducto, nombre_producto, cantidad, flagBS, flagGenInd, unidad_medida, nombre_medida, precio_conigv, precio_sinigv, precio, igv, importe, stock, costo) {
    agregar_fila(producto, codproducto, nombre_producto, cantidad, flagBS, flagGenInd, unidad_medida, nombre_medida, precio_conigv, precio_sinigv, precio, igv, importe, stock, costo);
}

function seleccionarOdenCompra(oCompra, serie, numero, valor)
{
   mostrarOdenCompraVista(oCompra,serie, numero, valor);
   obtener_detalle_ocompra(oCompra);

   /**quitamos lista de guiarem **/
   listadoGuiaremEstadoDeseleccionado();
   verificarOcultarListadoGuiaremAsociado();

   $("#serie").val(serie);
   $("#numero").val(numero);
}

function joinOComprasDetalle(data) {
    renderJoinsItems(data);

    var formulario = $("#frmComprobante");

    formulario.find('.ordencomprajoin').remove();
    $.each(data.ordenes, function(i, oc) {
        formulario.append('<input name="ordencompra[]" type="hidden" class="ordencomprajoin" value="'+oc+'">');
    });

    $("#moneda").val(data.moneda).trigger('change');
}


function mostrarOdenCompraVista(oCompra,serie, numero, valor){
 if(valor == 1){
    serienumero = "Numero de Orden Compra. :" + serie + " - " + numero;
}else{
    serienumero = "Numero de Orden Venta. :" + serie + " - " + numero;
}
$("#serieguiaverOC").html(serienumero);
$("#serieguiaverOC").show(200);
$("#serieguiaverPre").hide(200);
$("#serieguiaver").hide(200);
$("#serieguiaverRecu").hide(200);
$('#ordencompra').val(oCompra);

codigoPresupuesto=$("#presupuesto_codigo").val();
if(codigoPresupuesto!="" && codigoPresupuesto!=0){
   modificarTipoSeleccionPrersupuesto(codigoPresupuesto,0);
}
$("#presupuesto_codigo").val("");
}

function seleccionar_guiarem(guia, serieguia, numeroguia) {
    realizado=agregar_todo(guia);
    if(realizado!=false){
       $("#serieguiaverPre").hide(200);
       $("#serieguiaverOC").hide(200);
       $("#serieguiaverRecu").hide(200);
       $('#ordencompra').val('');
   }

   codigoPresupuesto=$("#presupuesto_codigo").val();
   if(codigoPresupuesto!="" && codigoPresupuesto!=0){
       modificarTipoSeleccionPrersupuesto(codigoPresupuesto,0);
   }
   $("#presupuesto_codigo").val("");

}



function seleccionar_presupuesto(guia, serieguia, numeroguia) {
    isRealizado=modificarTipoSeleccionPrersupuesto(guia,1);
    if(isRealizado){
       tipo_oper = $("#tipo_oper").val();
       agregar_todopresupuesto(guia, tipo_oper);
       mostrarPresupuestoVista(guia, serieguia, numeroguia,tipo_oper);
       /**quitamos lista de guiarem **/
       listadoGuiaremEstadoDeseleccionado();
       verificarOcultarListadoGuiaremAsociado();
   }


}

function mostrarPresupuestoVista(guia, serieguia, numeroguia,tipo_oper){
 if(tipo_oper=="V")
    serienumero = "Numero de PRESUPUESTO :" + serieguia + " - " + numeroguia;
else
    serienumero = "Numero de COTIZACIÓN :" + serieguia + " - " + numeroguia;

$("#serieguiaverPre").html(serienumero);
$("#serieguiaverPre").show(200);
$("#serieguiaver").hide(200);
$("#serieguiaverOC").hide(200);
$("#serieguiaverRecu").hide(200);
$("#docurefe_codigo").val('');
$("#dRef").val('');
$('#ordencompra').val('');
$("#numero_ref").val('');
$("#presupuesto_codigo").val(guia);
}

function seleccionar_comprobante_recu(guia, serieguia, numeroguia) {
    agregar_todo_recu(guia);
    serienumero = "NÂ° de Comprobante: <br>" + serieguia + " - " + numeroguia;
    $("#serieguiaverRecu").html('<span style="font-size:15px" >Doc. Recurrente: <br>' + serienumero + '</span>');
    $("#serieguiaverRecu").show(200);
    $("#serieguiaver").hide(200);
    $("#serieguiaverRecuFlecha").show(400);
    $("#serieguiaverPre").hide(200);
    $("#serieguiaverOC").hide(200);
    $("#numero_ref").val('');
    $("#dRef").val('');
    $('#ordencompra').val('');
    $("#docurefe_codigo").val('');

    codigoPresupuesto=$("#presupuesto_codigo").val();
    if(codigoPresupuesto!="" && codigoPresupuesto!=0){
        modificarTipoSeleccionPrersupuesto(codigoPresupuesto,0);
    }
    $("#presupuesto_codigo").val("");


    /**quitamos lista de guiarem **/
    listadoGuiaremEstadoDeseleccionado();
    verificarOcultarListadoGuiaremAsociado();
}

function valida() {
    if (document.forms[0].seriep.value.length > 2) {
        document.forms[0].presupuesto.focus();
        return false;
    }
    else
        return true;

}
function tdc_cambiar() {
    $.ajax({
        url: "<?php echo base_url(); ?>index.php/maestros/tipocambio/buscar_json",
        type: "POST",
        data: {
            fecha: $('#fecha').val()
        },
        success: function (data) {
            if (data == 0) {
                alert('error Tipo de cambio en esta fecha no ingresada');
                $('#fecha').val('<?php echo date('d/m/Y');?>');
                tdc_cambiar();
            } else {
                $('#tdc').val(data);
            }
        }
    });
}
        // End -->

        function cambiarAlmacenProductoCodigo(almacen){

          posicionSeleccionado=$("#posicionSeleccionadaSerie").val();
          if(posicionSeleccionado!=null && posicionSeleccionado!=''){
              a="almacenProducto["+posicionSeleccionado+"]";
              document.getElementById(a).value=almacen;

          }
      }
      /**seleccionamos un almacen para el producto agregaod po o.vc cotizacioon, recurrentes**/
      function mostrarPopUpSeleccionarAlmacen(posicionSeleccionado){
          a="almacenProducto["+posicionSeleccionado+"]";
          b="prodcodigo["+posicionSeleccionado+"]";
          $("#posicionSeleccionadaSerie").val(posicionSeleccionado);
          almacenProducto=document.getElementById(a).value;
          codigoProducto=document.getElementById(b).value;
          url="<?php echo base_url(); ?>index.php/almacen/producto/buscarAlmacenProducto/"+codigoProducto;

          n = document.getElementById('idTblAlmacen').rows.length;
          if(n!=null && n!='' && n>1){
             for(x=1;x<n;x++){
                document.getElementById("idTblAlmacen").deleteRow(1);
            }
        }

        $.ajax({
          url: url,
          dataType: 'json',
          async: false, 
          success: function (data) {
             $.each(data, function (i, item) {
              codigoAlmacen=item.codigo;
              nombreAlmacen=item.nombreAlmacen;
              stock=item.stock;
              j=i+1;
              fila="<tr id='idTr_"+j+"' >";
              fila+="<td>";
              fila+="<input type='radio' name='almacenListado' id='idRdAlmacen"+j+"' value='"+codigoAlmacen+"'>";	
              fila+="</td>";
              fila+="<td>";
              fila+="<label for='idRdAlmacen"+j+"' >"+nombreAlmacen+"</label>";	
              fila+="</td>";
              fila+="<td>";
              fila+="<label>"+stock+"</label>";	
              fila+="</td>";
              fila+="</tr>";
              $("#idTblAlmacen").append(fila);
          });
             $("#dialogoSeleccionarALmacenProducto").dialog("open");
         }
     });
    }

    function grabarSeleccionarAlmacen(){
      almacen=$('input:radio[name=almacenListado]:checked').val();
      if(almacen!=null && almacen!=""){
         cambiarAlmacenProductoCodigo(almacen);
         $("#dialogoSeleccionarALmacenProducto").dialog("close");
     }else{
         alert("Debe de seleccionar un almacen para el producto.");
     }
 }
 function get_obra(codigo) {
		//alert(codigo);
		$.post("<?php echo base_url(); ?>index.php/compras/pedido/obra", {
          "codigoempre" : codigo
      }, function(data) {
				//alert("hola"+data);
				var c = JSON.parse(data);
				$('#obra').html('');
				$('#obra').append("<option value='0'>::Seleccione::</option>");
				$.each(c,function(i,item){
					$('#obra').append("<option value='"+item.PROYP_Codigo+"'>"+item.proyecto+"</option>");
				});
          });
	}
</script>

</head>

<body>

    <input type="hidden" name="codigoguia" id="codigoguia" value="<?php echo $guia; ?>"/>

    <?php

//echo date("Y-m-d H:i:s");
// stylo para ocultar botones combos, etc

    $style = "";
    if (FORMATO_IMPRESION == 8) {
        $style = "display:none;";

    }

    ?>

    <!-- Inicio -->
    <input value='<?php echo $compania; ?>' name="compania" type="hidden" id="compania" 
    />

    <div id="VentanaTransparente" style="display:none;">
        <div class="overlay_absolute"></div>
        <div id="cargador" style="z-index:2000">
            <table width="100%" height="100%" border="0" class="fuente8">
                <tr valign="middle">
                    <td> Por Favor Espere</td>
                    <td>
                       <img src="<?php echo base_url(); ?>images/cargando.gif?=<?=IMG;?>" border="0" title="CARGANDO"/>
                       <a href="#" id="hider2"></a>
                   </td>
               </tr>
           </table>
       </div>
   </div>

   <!-- Fin -->

   <form id="<?php echo $formulario; ?>" method="post" action="<?php echo $url_action; ?>">
    <div id="popup" style="display: none;">
        <div class="content-popup">
            <div class="close">
                <a href="#" id="close">
                    <img src="<?=base_url()?>images/delete.gif?=<?=IMG;?>"/></a></div>
                    <div>
                     <h2>Falta Ingresar inventario</h2>
                     <div id="contendio">
                     </div>
                     <a onclick="ejecutarModal()" target="_blank" href="<?=base_url()?>index.php/almacen/inventario/listar" id="btnInventario">IR A INGRESAR INVENTARIO </a>

                 </div>
             </div>
         </div>
         <div id="zonaContenido" align="center">
            <?php echo validation_errors("<div class='error'>", '</div>'); ?>
            <div id="tituloForm" class="header" style="height: 20px;font-size: 20pt;">
                <?php echo $titulo; ?>
                <?php
                if ($tipo_docu != 'N') {
                    if ($codigo == '') { ?>
                        <select id="cboTipoDocu" name="cboTipoDocu" class="comboMedio" hidden>
                            <option value="F" <?php if ($tipo_docu == 'F') echo 'selected="selected"'; ?>>FACTURA</option>
                            <option value="B" <?php if ($tipo_docu == 'B') echo 'selected="selected"'; ?>>BOLETA</option>
                        </select>
                    <?php
                    }else{ ?>
                        <input type="hidden" value="N" id="cboTipoDocu" name="cboTipoDocu"/>
                <?php }
                } else {
                    ?>
                    <input type="hidden" value="N" id="cboTipoDocu" name="cboTipoDocu"/>
                <?php } ?>
            </div>


                <div id="idDivGuiaRelacion" style="<?php echo (count($listaGuiaremAsociados)>0)?'':'display:none'; ?>">
                  <div id="dialogSeriesAsociadas" title="Series Ingresadas">
                    <div id="mostrarDetallesSeriesAsociadas">	
                       <div id="detallesSeriesAsociadas"></div>
                   </div>
               </div>

               <!-- dialogo para mostrarse que sleccionar elñ almacen de un producto -->

               <div id="dialogoSeleccionarALmacenProducto" title="Seleccionar Almacen">
                <div id="mostrarDetallesSeleecionarALmacen">	
                 <table id="idTblAlmacen" >
                  <tr id="idTr_0">
                     <td></td>
                     <td width="200px" >Descripci&oacute;n</td>
                     <td width="50px">Stock</td>            	
                 </tr>
             </table>
         </div>
     </div>
     <!-- fin de dialogo -->

     <div id="tituloForm" class="header" style="height: 30px">
         <h3>COMPRAS RELACIONADAS</h3>
     </div>
     <table class="fuente8" id="idTableGuiaRelacion">
         <tr id="idTrDetalleRelacion_0" >
            <td></td>
            <td>ITEM</td>
            <td>SERIE</td>
            <td>NUMERO</td>
            <td>BACKGROUND</td>
        </tr>
        <?php if(count($listaGuiaremAsociados)>0){ 
           foreach ($listaGuiaremAsociados as $indice=>$valorGuiarem){
             $codigoGuiarem=$valorGuiarem->codigoGuiarem;
             $serieGuiarem=$valorGuiarem->serie;
             $numeroGuiarem=$valorGuiarem->numero;
             $j=$indice+1;
             $colorGuiar[$codigoGuiarem]="#".dechex(rand(0,10000000));

             ?>

             <tr id="idTrDetalleRelacion_<?php echo $j; ?>"> 
                 <td> 
                     <a href="javascript:void(0);" onclick="deseleccionarGuiaremision(<?php echo $codigoGuiarem; ?>,<?php echo $j; ?>)" title="Deseleccionar Guia de remision"> 
                         x 
                     </a> 
                 </td> 
                 <td><?php echo $j; ?></td> 
                 <td><?php echo $serieGuiarem; ?></td> 
                 <td><?php echo $numeroGuiarem; ?></td> 
                 <td><div style="width:10px;height:10px;background-color:<?php echo $colorGuiar[$codigoGuiarem] ?>; border:1px solid black"></div> 
                    <input type="hidden" id="codigoGuiaremAsociada[<?php echo $j; ?>]"  name="codigoGuiaremAsociada[<?php echo $j; ?>]" value="<?php echo $codigoGuiarem; ?>" /> 
                    <input type="hidden" id="accionAsociacionGuiarem[<?php echo $j; ?>]"  name="accionAsociacionGuiarem[<?php echo $j; ?>]" value="2" />
                    <input type="hidden" id="proveedorRelacionGuiarem[<?php echo $j; ?>]"  name="proveedorRelacionGuiarem[<?php echo $j; ?>]" value="<?php echo $proveedor; ?>" />
                </td> 

            </tr> 
            <?php }} ?>
        </table>
    </div>
    <div id="frmBusqueda">
        <table class="fuente8" width="100%" cellspacing="0" cellpadding="5" >
            <tr>
                <td width="8%">Número*</td>
                <td width="50%" valign="middle">
                    <input type="hidden" id="guiaremision" value="<?php echo $guiaremision; ?>"/>
                    <input type="hidden" id="posicionSeleccionadaSerie" value="" />
                        
                    <input class="cajaGeneral" placeholder="serie" name="serie" type="text" id="serie" size="3" maxlength="10" value="<?php echo $serie; ?>" <?=($tipo_oper == 'V') ? 'readonly' : '';?>/>&nbsp;
                    <input class="cajaGeneral" placeholder="numero" name="numero" id="numero" size="6" maxlength="20" value="<?php echo $numero; ?>" <?=($tipo_oper == 'V') ? 'readonly' : '';?>/>
                    <?php if ($tipo_oper == 'V') { ?>
                        <a href="javascript:;" id="linkVerSerieNum" <?php if ($codigo != '') echo 'style="display:none"' ?>>
                            <p class="boleta" style="display:none"><?php echo $serie_suger_b . '-'. $numero_suger_b ?></p>
                            <p class="factura" style="display:none"><?php echo $serie_suger_f . '-' . $numero_suger_f ?></p>
                            <p class="comprobante" style="display:none"><?php echo $serie_suger_f . '-' . $numero_suger_f ?></p>
                            <img src="<?php echo base_url(); ?>images/flecha.png?=<?=IMG;?>" border="0" alt="Serie y nÃºmero sugerido" title="Serie y número sugerido"/>
                        </a>
                                
                    <input type="checkbox" name="numeroAutomatico"  id="numeroAutomatico" <?=($numeroAutomatico==1)?'checked=true':'';?> value="1" title="SERIE-NUMERO AUTOMATICO SI SE SELECCIONA">
                    <?php } ?>
                    <!--input type="hidden" name="descuento" id="descuento" value=""/-->
                    <label hidden>
                        <input id="chk-exonera-igv" type="checkbox" <?php if($igv == 0) echo "checked";?>>Exonerar
                    </label>
                    <script>
                        $(document).ready(function () {
                            $("#chk-exonera-igv").change(function(event) {
                                var isCheck = $(this).attr('checked'),
                                    igv = <?php echo $igv != 0 ? $igv : $igv_default; ?>;
                                $("#igv").val(isCheck ? 0 : igv);
                            }).trigger('change');
                        });
                    </script>
                </td>
                <td width="5%">Fecha de emisión</td>
                <td width="5%">
                    <input type="date" class="cajaGeneral cajaSoloLectura" id="fecha" name="fecha" value="<?=$hoy;?>">
                </td>
                <td colspan="2">
                    TDC
                    
                    Dolar : &nbsp;
                    <input name="tdcDolar" type="text" class="cajaGeneral cajaSoloLectura" style="width: 28px" id="tdcDolar" size="3" value="<?php echo $tdcDolar; ?>" onkeypress="return numbersonly(this,event,'.');" readonly="readonly"/>&nbsp;
                    <span id="tdcOpcional">
                        Euro : &nbsp;
                        <input name="tdcEuro" type="text" class="cajaGeneral cajaSoloLectura" style="width: 28px" id="tdcEuro" size="3" value="<?php echo $tdcEuro; ?>" onkeypress="return numbersonly(this,event,'.');"/>
                    </span>
                </td>
                <td hidden>
                    <label style="margin-left:20px;">IGV</label>
                    <input name="igv" type="text" class="cajaGeneral cajaSoloLectura" id="igv" size="2" maxlength="2" value="<?=$igv;?>" onkeypress="return numbersonly(this,event,'.');" onblur="modifica_igv_total();" readonly="readonly"/> %
                </td>
                <td>
                    <label style="margin-left:20px;">AD VALOREM</label>
                    <input name="adValorem" type="text" class="cajaGeneral cajaSoloLectura" id="adValorem" size="2" maxlength="2" value="<?=($adValorem != '') ? $adValorem : 0;?>" onkeypress="return numbersonly(this,event,'.');" onchange="calcularAdValorem()"/> %
                </td>
                <!-- <td width="5%" valign="middle">Fecha de vencimiento</td>
                <td width="30%" valign="middle">
                    <input type="date" class="cajaGeneral cajaSoloLectura" id="fecha_vencimiento" name="fecha_vencimiento" value="<?=$fecha_vencimiento;?>">
                </td> -->
            </tr>

            <tr>
                    <td><?=($tipo_oper=="V") ? "Cliente *" : "Proveedor *";?></td>
                    <td valign="middle"> <?php
                        if ($tipo_oper == "V") { ?>
                            <input type="hidden" name="cliente" id="cliente" value="<?php echo $cliente ?>"/>
                            <input type="hidden" name="tipocliente_doc" id="tipocliente_doc" value="<?php echo $tipocliente_doc ?>"/>
                            <input placeholder="ruc" name="buscar_cliente" type="text" class="cajaGeneral" id="buscar_cliente" size="10" value="<?php echo $ruc_cliente; ?>" title="Ingrese parte del nombre o el nro. de documento, luego presione ENTER."/>&nbsp;
                            <input type="hidden" name="ruc_cliente" class="cajaGeneral" id="ruc_cliente" size="10" maxlength="11" onblur="obtener_cliente();" value="<?php echo $ruc_cliente; ?>" onkeypress="return numbersonly(this,event,'.');"/>
                            <input placeholder="razon social" type="text" name="nombre_cliente" class="cajaGeneral" id="nombre_cliente" size="37"  value="<?php echo trim($nombre_cliente, '"'); ?>"/>
                            
                             <?php
                        }
                        else { ?>
                            <input type="hidden" name="proveedor" id="proveedor" value="<?php echo $proveedor ?>"/>
                            <input name="buscar_proveedor" type="text" class="cajaGeneral" id="buscar_proveedor" size="10" placeholder="ruc" value="<?php echo $ruc_proveedor; ?>" title="Ingrese parte del nombre o el nro. de documento, luego presione ENTER."/>&nbsp;
                            <input type="hidden" name="ruc_proveedor" class="cajaGeneral" id="ruc_proveedor" size="10" maxlength="11" onblur="obtener_proveedor();" value="<?php echo $ruc_proveedor; ?>" placeholder="ruc" onkeypress="return numbersonly(this,event,'.');"/>
                            <input type="text" name="nombre_proveedor" class="cajaGeneral cajaSoloLectura" id="nombre_proveedor" size="25" placeholder="razon social" value="<?php echo trim($nombre_proveedor, '"');?>"/>
                            

                            <?php
                        }

                        //$this->load->view('layout/modalClienteNuevo'); ?>
                        <button id="nuevo_cliente" type="button" class="btn btn-default" data-target="#modal_addcliente" data-toggle="modal">NUEVO</button>
                    </td>
                    <td>DUA</td>
                    <td colspan="3">
                        <input type="hidden" name="dua_cod_int" class="cajaGeneral" id="dua_cod_int" size="37"  value=""/>
                        <input type="text" name="dua_cod" class="cajaGeneral" id="dua_cod" size="37"  value="<?=$duacodigo?>"/>  
                        <button id="buscar_dua" type="button" class="btn btn-default" onclick="getDuaCod()">BUSCAR</button> 
                    </td>
                    <td colspan="3" <?=($tipo_oper == "C") ? "style='display:none'" : "";?>>
                        Vendedor * &nbsp;&nbsp;&nbsp;
                        <select id="cboVendedor" name="cboVendedor" class="comboMedio">
                            <?=$cboVendedor;?>
                        </select>
                        <input type="hidden" readonly id="VerificadoSuccess" name="VerificadoSuccess" value="<?=($tipo_oper == 'C') ? 1 : 0;?>"/>
                       <!-- <button type="button" class="btn btn-default" id="open_modal_credencial">
                            <img src='<?=base_url();?>/images/icon-lock.png' class='image-size-1b'>
                        </button>-->
                    </td>
                    <td>
                    <td valign="middle" style="position: relative;"></td>
                </tr>
                <tr>
                    <td>Almacen*</td>
                    <td><?php echo $cboAlmacen; ?></td>
                    <td valign="middle">Moneda*</td>
                    <td valign="middle" id="idTdMoneda">
                        <select name="moneda" id="moneda" class="comboPequeno" style="width:150px;">
                            <?php echo $cboMoneda; ?>
                        </select>
                       <label id="textoMoneda"></label> 
                        
                        <?php if(count($listaGuiaremAsociados)>0){  ?> 
                        <script type="text/javascript">
                            $("#moneda").hide(200);
                            textoMoneda=$("#moneda option:selected").text();
                            $("#textoMoneda").html(textoMoneda);
                            $("#textoMoneda").show(200);
                        </script>
                        
                        <?php } ?>
                    </td>
                    <script>
                        $("#moneda").change(function(event) {
                            var combo = $(this),
                                codigo = combo.val();

                            $("#tdcOpcional").css('display', codigo > 2 ? '' : 'none');

                            if(codigo > 2) $("#tdcEuro").focus();
                        });

                        $(document).ready(function () {
                            $("#moneda").trigger('change');
                        });
                    </script>

                </tr>
        </table>
    </div>
    <div id="frmBusqueda"  <?php echo $hidden; ?> class="box-add-product" style="text-align: right;">
        <a href="#" id="verServicios" name="verServicios" style="color:#ffffff;" class="btn btn-success" data-toggle="modal" data-target=".modal-serv" data-backdrop="static">Servicios</a></td>
        <a href="#" id="addItems" name="addItems" style="color:#ffffff;" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg" data-backdrop="static" onclick="limpiar_campos_modal(); ">Agregar Items</a></td>
    </div>
    <?php $this->load->view('maestros/temporal_detalles'); ?>

<!-- LISTADO DE GUIAS ASOCIADAS  -->
<?php $this->load->view('maestros/temporal_subdetalles'); ?>
<!--Modal para registrar nuevo cliente-->
<?php $this->load->view('ventas/modal_clientes'); ?>

<div id="importacionModal" class="modal fade modal-comp" role="dialog" data-backdrop="static">
        <div class="modal-dialog w-porc-80">
            <div class="modal-content">
                <form id="asoc_Cot" method="POST">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                    </div>
                    <div style="text-align: center;">
                        <h3><b>ASOCIAR COMPROBANTE</b></h3>
                    </div>
                    <div class="modal-body panel panel-default">
                        <div class="row form-group">
                            <div class="col-sm-11 col-md-11 col-lg-11 header form-group">
                                <span>INFORMACIÓN DEL DOCUMENTO</span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                            <table class="fuente8 display" id="table-comprobante-modal">
                            <div id="cargando_datos" class="loading-table">
                                <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                            </div>
                            <thead>
                                <tr class="cabeceraTabla">
                                    <th style="width: 08%" data-orderable="true">FECHA</th>
                                    <th style="width: 05%" data-orderable="true">SERIE</th>
                                    <th style="width: 05%" data-orderable="true">NÚMERO</th>
                                    <th style="width: 10%" data-orderable="true">RUC</th>
                                    <th style="width: 50%" data-orderable="true">RAZON SOCIAL</th>
                                    <th style="width: 07%" data-orderable="false">PRODUCTOS</th>
                                    <th style="width: 07%" data-orderable="false">PDF.</th>
                                    <th style="width: 07%" data-orderable="false">SEL.</th>
                                </tr>
                            </thead>
                            <tbody id="comprobantes_dua" class="table-hover"></tbody>
                            </table>
                        </div>                
                    </div>
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" id="importacionID" onclick="cerrar_modal()">Salir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<div id="serviciosModal" class="modal fade modal-serv" role="dialog" data-backdrop="static">
    <div class="modal-dialog w-porc-80">
        <div class="modal-content">
            <form id="asoc_Cot" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div style="text-align: center;">
                    <h3><b>SERVICIOS ASOCIADOS</b></h3>
                </div>
                <div class="modal-body panel panel-default">
                    <div class="row form-group">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                        <table class="fuente8 display" id="table-servicios-modal">
                        <div id="cargando_datos" class="loading-table">
                            <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                        </div>
                        <thead>
                            <tr class="cabeceraTabla">
                                <th style="width: 05%" data-orderable="true"></th>
                                <th style="width: 35%" data-orderable="true">DESCRIPCION</th>
                                <th style="width: 10%" data-orderable="true">CANTIDAD</th>
                                <th style="width: 10%" data-orderable="true">P/U</th>
                                <th style="width: 10%" data-orderable="true">V/U</th>
                                <th style="width: 10%" data-orderable="false">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody id="servicios_asoc" class="table-hover"></tbody>
                        </table>
                    </div>                
                </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="importacionID" data-dismiss="modal">Salir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="costosModal" class="modal fade modal-cost" role="dialog" data-backdrop="static">
    <div class="modal-dialog w-porc-80">
        <div class="modal-content">
            <form id="asoc_Cot" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div style="text-align: center;">
                    <h3><b><span id="productoGet"></span></b></h3>
                </div>
                <div class="modal-body panel panel-default">
                    <div class="row form-group">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                        <table class="fuente8 display" id="table-servicios-modal">
                        <div id="cargando_datos" class="loading-table">
                            <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                        </div>
                        <thead>
                            <tr class="cabeceraTabla">
                                <th style="width: 05%" data-orderable="true"></th>
                                <th style="width: 35%" data-orderable="true">DESCRIPCION</th>
                                <th style="width: 10%" data-orderable="true">TOTAL</th>
                                <th style="width: 10%" data-orderable="true">COSTO AÑADIDO</th>
                            </tr>
                        </thead>
                        <tbody id="servicio_producto" class="table-hover"></tbody>
                        </table>
                        <tfoot>
                            <tr class="cabeceraTabla">
                                <th style="width: 05%" data-orderable="true">Total: <span id="totalservpro"></span></th>
                            </tr>
                        </tfoot>
                    </div>                
                </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="importacionID" data-dismiss="modal">Salir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- FIN DE LISTADO DE GUIAS ASOCIADAS --> 
<div id="frmBusqueda">
    <?php $colors = array(); ?>
    <div>
        <table id="tblDetalleComprobante" class="fuente8" width="100%" border="0">
            <?php

            if (count($detalle_comprobante) > 0) {

                foreach ($detalle_comprobante as $indice => $valor) {

                    if(!isset($colors[$valor->OCOMP_Codigo_venta_ref])) $colors[$valor->OCOMP_Codigo_venta_ref] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);

                    $detacodi = $valor->IMPORDEP_Codigo;
                    $flagBS = $valor->flagBS;
                    $prodproducto = $valor->PROD_Codigo;
                    $unidad_medida = $valor->UNDMED_Codigo;
                    $codigo_interno = $valor->PROD_CodigoInterno;
                    $nombre_producto = $valor->PROD_Nombre;
                    $nombre_unidad = $valor->UNDMED_Simbolo;
                    $costo = $valor->IMPORDEC_Costo;
                    $GenInd = $valor->IMPORDEC_GenInd;
                    $prodcantidad = $valor->IMPORDEC_Cantidad;
                    $prodpu = $valor->IMPORDEC_Pu;
                    $prodsubtotal = $valor->IMPORDEC_Subtotal;
                    $proddescuento = $valor->IMPORDEC_Descuento;
                    $prodigv = $valor->IMPORDEC_Igv;
                    $prodtotal = $valor->IMPORDEC_Total;
                    $prodpu_conigv = $valor->IMPORDEC_Pu_ConIgv;
                    $prodsubtotal_conigv = $valor->IMPORDEC_Subtotal_ConIgv;
                    $proddescuento_conigv = $valor->IMPORDEC_Descuento_ConIgv;
                    $almacenProducto=$valor->ALMAP_Codigo;
                    $codigoGuiaremAsociadaDetalle=$valor->GUIAREMP_Codigo;
                    $readonly="";
                    if($codigoGuiaremAsociadaDetalle!=0)
                       $readonly="readonly";

                   if (($indice + 1) % 2 == 0) {
                    $clase = "itemParTabla";
                } else {
                    $clase = "itemImparTabla";
                }
                ?>

                <tr id="<?php echo $indice ?>" t-doc="<?php echo $tipo_docu ?>"
                    class="tooltiped <?php echo $clase; ?> id_ref_ocompra_<?php echo $valor->OCOMDEP_Codigo ?>"

                   data-toggle="tooltip" data-placement="top" title="<?php echo $valor->PROYP_Codigo != 0 ? ("Proyecto : " . $valor->PROYC_Nombre) : (($tipo_oper == 'C' ? 'Cliente : ' : 'Proveedor : ') . $valor->RazonSocial) ?>">
                   <td width="3%">
                       <div align="center">

                           <?php  if(count($listaGuiaremAsociados)==0){ ?>
                           <font color="red"><strong><a href="javascript:;"
                             onclick="eliminar_producto_comprobante(<?php echo $indice; ?>);">
                             <span
                             style="border:1px solid red;background: #ffffff;">&nbsp;X&nbsp;</span></a>
                         </strong>
                     </font>

                     <?php } ?>
                 </div>
             </td>
             <td width="4%">
               <div align="center"><?php echo $indice + 1; ?></div>
           </td>
           <td width="8%" style="border-left: 10px solid <?php echo isset($valor->OCOMP_Codigo_venta_ref) ? $colors[$valor->OCOMP_Codigo_venta_ref] : '#FFFFFF'; ?>;">
              <div align="center"><?php echo $codigo_interno; ?></div>
          </td>
          <td width="5%">
            <div align="left"><input type="text" class="cajaGeneral" style="width:390px;"
               maxlength="250" name="proddescri[<?php echo $indice; ?>]"
               id="proddescri[<?php echo $indice; ?>]"
               value="<?php echo $nombre_producto; ?>"/></div>
           </td>
           <?php if ($tipo_docu != 'B' && $tipo_docu != 'N') { ?>
           <td width="12%">
             <div align="left"><input type="text" size="1" maxlength="10" class="cajaGeneral" style="text-align: right;"
                 name="prodcantidad[<?php echo $indice; ?>]"
                 id="prodcantidad[<?php echo $indice; ?>]"
                 value="<?php echo $prodcantidad; ?>"
                 onchange="calcula_cantidad(<?php echo $indice; ?>);"
                 onblur="calcula_importe(<?php echo $indice; ?>);"
                 onkeypress="return numbersonly(this,event,'.');"  <?php echo $readonly; ?> /><?php echo $nombre_unidad; ?>
                 <input type="hidden" name="prodpendiente[<?php echo $indice; ?>]" id="prodpendiente[<?php echo $indice; ?>]" value="<?php echo $prodcantidad; ?>">
                 <?php if($GenInd=='I') {?>
                 <?php if($codigoGuiaremAsociadaDetalle!=0 ||  $isProvieneCanje){ ?>

                 <?php if(!$isProvieneCanje){ ?>
                 <!-- Guiade remision mostra -->
                 <a href="javascript:;" id="imgEditarSeries<?php echo $indice; ?>" onclick="ventana_producto_serieMostrar(10,<?php echo $codigoGuiaremAsociadaDetalle; ?>,<?php echo $prodproducto; ?>,<?php echo $almacenProducto; ?>)" ><img src="<?php echo base_url(); ?>images/flag-green_icon.png?=<?=IMG;?>" width="20" height="20" class="imgBoton"></a>
                 <?php }else{ ?> 
                 <!-- mostrar detalles de comprante que genraron la factura o boleta -->
                 <a href="javascript:;" id="imgEditarSeries<?php echo $indice; ?>" onclick="ventana_producto_serieMostrar(<?php echo $tipoOperCodigo; ?>,<?php echo $codigo; ?>,<?php echo $prodproducto; ?>,<?php echo $almacenProducto; ?>)" ><img src="<?php echo base_url(); ?>images/flag-green_icon.png?=<?=IMG;?>" width="20" height="20" class="imgBoton"></a>

                 <?php } ?>

                 <?php }else{?>
                 <a href="javascript:;" id="imgEditarSeries<?php echo $indice; ?>" onclick="ventana_producto_serie(<?php echo $indice; ?>)" ><img src="<?php echo base_url(); ?>images/flag-green_icon.png?=<?=IMG;?>" width="20" height="20" class="imgBoton"></a>
                 <?php } ?>
                 <?php } ?>

             </div>
         </td>
         <td width="6%">
            <div align="center">
                <input type="text" size="5" maxlength="10" class="cajaGeneral" name="prodpu_conigv[<?php echo $indice; ?>]" style="text-align: right;"
                id="prodpu_conigv[<?php echo $indice; ?>]"
                value="<?php echo str_replace(",", "", number_format($prodpu_conigv, 2)); ?>"
                onblur="modifica_pu_conigv(<?php echo $indice; ?>);"
                onkeypress="return numbersonly(this,event,'.');" <?php echo $readonly; ?>/></div>
            </td>
            <td width="6%">
                <div align="center"><input type="text" size="5" maxlength="10" class="cajaGeneral" style="text-align: right;"
                 name="prodpu[<?php echo $indice; ?>]"
                 id="prodpu[<?php echo $indice; ?>]"
                 value="<?php echo str_replace(",", "", number_format($prodpu, 2)); ?>"
                 onblur="modifica_pu(<?php echo $indice; ?>);"
                 onkeypress="return numbersonly(this,event,'.');" <?php echo $readonly; ?>/>
                 <td width="6%">
                     <div align="center">
                        <input type="text" size="5" maxlength="10" style="text-align: right;"
                        class="cajaGeneral cajaSoloLectura"
                        name="prodprecio[<?php echo $indice; ?>]"
                        id="prodprecio[<?php echo $indice; ?>]"
                        value="<?php echo str_replace(",", "", number_format($prodsubtotal, 2)); ?>"
                        readonly="readonly"/></div>
                    </td>
                    <?php } else { ?>
                    <td width="12%">
                       <div align="left"><input type="text" size="7" maxlength="10" style="text-align: right;"
                           class="cajaGeneral"
                           name="prodcantidad[<?php echo $indice; ?>]"
                           id="prodcantidad[<?php echo $indice; ?>]"
                           value="<?php echo str_replace(",", "", number_format($prodcantidad, 2)) ?>"
                           onblur="calcula_importe(<?php echo $indice; ?>);"
                           onkeypress="return numbersonly(this,event,'.');" <?php echo $readonly; ?>/>
                           <?php echo $nombre_unidad; ?>


                           <?php if($GenInd=='I') {?>
                           <?php if($codigoGuiaremAsociadaDetalle!=0){ ?>
                           <a href="javascript:;" id="imgEditarSeries<?php echo $indice; ?>" onclick="ventana_producto_serieMostrar(10,<?php echo $codigoGuiaremAsociadaDetalle; ?>,<?php echo $prodproducto; ?>,<?php echo $almacenProducto; ?>)" ><img src="<?php echo base_url(); ?>images/flag-green_icon.png?=<?=IMG;?>" width="20" height="20" class="imgBoton"></a>

                           <?php }else{?>
                           <a href="javascript:;" id="imgEditarSeries<?php echo $indice; ?>" onclick="ventana_producto_serie(<?php echo $indice; ?>)" ><img src="<?php echo base_url(); ?>images/flag-green_icon.png?=<?=IMG;?>" width="20" height="20" class="imgBoton"></a>
                           <?php } ?>
                           <?php } ?>
                       </div>

                   </td>
                   <td width="6%">
                     <div align="center"><input type="text" size="5" maxlength="10" style="text-align: right;"
                         class="cajaGeneral"
                         name="prodpu_conigv[<?php echo $indice; ?>]"
                         id="prodpu_conigv[<?php echo $indice; ?>]"
                         value="<?php echo str_replace(",", "", number_format($prodpu_conigv)); ?>"
                         onblur="modifica_pu_conigv(<?php echo $indice; ?>);"
                         onkeypress="return numbersonly(this,event,'.');" <?php echo $readonly; ?>/>
                     </div>
                 </td>
                 <td width="6%">
                     <div align="center"><input type="text" size="5" maxlength="10" style="text-align: right;"
                      class="cajaGeneral"
                      name="prodpu[<?php echo $indice; ?>]"
                      id="prodpu[<?php echo $indice; ?>]"
                      value="<?php echo str_replace(",", "", number_format($prodpu)); ?>"
                      onblur="modifica_pu(<?php echo $indice; ?>);"
                      onkeypress="return numbersonly(this,event,'.');" <?php echo $readonly; ?>/>
                      <td width="6%">
                         <div align="center">

                             <input type="text" size="5" maxlength="10" style="text-align: right;"
                             class="cajaGeneral cajaSoloLectura"
                             name="prodprecio[<?php echo $indice; ?>]"   id="prodprecio[<?php echo $indice; ?>]"
                             value="<?php echo str_replace(",", "", number_format($prodsubtotal)); ?>"
                             readonly="readonly"/></div>
                         </td>

                         <td width="6%" style="display:none">
                            <div align="center"><input type="text" size="5" maxlength="10" style="text-align: right;"
                                class="cajaGeneral" name="prodprecio_conigv[<?php echo $indice; ?>]"
                                id="prodprecio_conigv[<?php echo $indice; ?>]"
                                value="<?php echo str_replace(",", "", number_format($prodsubtotal_conigv)); ?>"
                                readonly="readonly"/></div>
                            </td>
                            <?php } ?>
                            <?php if ($tipo_docu != 'B' && $tipo_docu != 'N') { ?>
                            <td width="6%" style="display:none;">
                                <div align="center">
                                 <input type="text" size="5" style="text-align: right;"
                                 class="cajaGeneral cajaSoloLectura"
                                 name="prodigv[<?php echo $indice; ?>]"
                                 id="prodigv[<?php echo $indice; ?>]"
                                 readonly="readonly" value="<?php echo str_replace(",", "", number_format($prodigv)); ?>"/>
                             </div>
                         </td>
                         <?php } ?>
                         <td width="6%" style="display:none;">
                           <div align="center">
                            <input type="hidden" size="5"
                            class="cajaGeneral cajaSoloLectura" style="text-align: right;"
                            name="prodigv[<?php echo $indice; ?>]"
                            id="prodigv[<?php echo $indice; ?>]" readonly="readonly"
                            value="<?php echo str_replace(",", "", number_format($prodigv)); ?>"/>
                            <input type="hidden" name="detaccion[<?php echo $indice; ?>]"
                            id="detaccion[<?php echo $indice; ?>]" value="m"/>
                            <input type="hidden" name="prodigv100[<?php echo $indice; ?>]"
                            id="prodigv100[<?php echo $indice; ?>]"
                            value="0"/>
                            <input type="text" name="detacodi[<?php echo $indice; ?>]"
                            id="detacodi[<?php echo $indice; ?>]"
                            value="<?php echo $detacodi; ?>"/>
                            <input type="hidden" name="flagBS[<?php echo $indice; ?>]"
                            id="flagBS[<?php echo $indice; ?>]" value="<?php echo $flagBS; ?>"/>
                            <input type="hidden" name="prodcodigo[<?php echo $indice; ?>]"
                            id="prodcodigo[<?php echo $indice; ?>]" value="<?php echo $prodproducto; ?>"/>
                            <input type="hidden" name="produnidad[<?php echo $indice; ?>]"
                            id="produnidad[<?php echo $indice; ?>]" value="<?php echo $unidad_medida; ?>"/>
                            <input type="hidden"  name="flagGenIndDet[<?php echo $indice; ?>]"
                            id="flagGenIndDet[<?php echo $indice; ?>]" value="<?php echo $GenInd; ?>"/>
                            <input type="hidden" name="prodstock[<?php echo $indice; ?>]"
                            id="prodstock[<?php echo $indice; ?>]" value=""/>
                            <input type="hidden" name="prodcosto[<?php echo $indice; ?>]"
                            id="prodcosto[<?php echo $indice; ?>]"
                            value="<?php echo $costo; ?>"/>
                             <input type="hidden" name="ocomdet[<?php echo $indice; ?>]"
                            id="ocomdet[<?php echo $indice; ?>]"
                            value="<?php echo $valor->OCOMDEP_Codigo; ?>"/>

                            <input type="hidden" name="flete[<?php echo $indice ?>]" id="flete[<?php echo $indice ?>]" value="<?php echo $valor->OCOMDEC_flete ?>">

                            <input type="hidden" name="almacenProducto[<?php echo $indice; ?>]"
                            id="almacenProducto[<?php echo $indice; ?>]"
                            value="<?php echo $almacenProducto; ?>"/>      
                            <input type="hidden"  name="proddescuento100[<?php echo $indice; ?>]"
                            id="proddescuento100[<?php echo $indice; ?>]"
                            value="<?php echo $descuento; ?>"/>

                            <?php if($codigoGuiaremAsociadaDetalle!=0 || ($flagBS=='S' && count($listaGuiaremAsociados)>0)){ ?>
                            <!--  /**se agrega la guia de remision asociada***/ -->   
                            <input type="hidden" name="codigoGuiarem[<?php echo $indice; ?>]" id="codigoGuiarem[<?php echo $indice; ?>]" value="<?php echo $codigoGuiaremAsociadaDetalle; ?>">
                            <!--             /**fin de agregar la guia de remision**/-->
                            <?php } ?>


                            <?php
                            if ($tipo_docu != 'B' && $tipo_docu != 'N') {
                             if ($tipo_oper == 'C') {
                                ?>
                                <input type="text" size="1" class="proddescuento" style="text-align: right;"
                                name="proddescuento[<?php echo $indice; ?>]"
                                id="proddescuento[<?php echo $indice; ?>]"
                                value="<?php echo str_replace(",", "", number_format($proddescuento)); ?>"
                                onblur="calcula_importe2(<?php echo $indice; ?>);"/>
                                <?php } else {
                                    ?>
                                    <input type="hidden"
                                    name="proddescuento[<?php echo $indice; ?>]"
                                    id="proddescuento[<?php echo $indice; ?>]"
                                    value="<?php echo $proddescuento; ?>"
                                    onblur="calcula_importe2(<?php echo $indice; ?>);"/>
                                    <?php
                                }
                            } else {
                             ?>
                             <input type="hidden"  name="proddescuento[<?php echo $indice; ?>]"
                             id="proddescuento[<?php echo $indice; ?>]"
                             value="<?php echo $proddescuento; ?>"
                             onblur="calcula_importe2(<?php echo $indice; ?>);"/>
                             <?php } ?>
                             <input type="text" size="5" class="cajaGeneral cajaSoloLectura" style="text-align: right;"
                             name="prodimporte[<?php echo $indice; ?>]"
                             id="prodimporte[<?php echo $indice; ?>]"
                             readonly="readonly" value="<?php echo str_replace(",", "", number_format($prodtotal)); ?>"/>
                         </div>
                     </td>      
                 </tr>
                 <?php
             }
         }
         ?>
     </table>
 </div>
</div>
<div id="frmBusqueda3">
  <table width="100%" border="0" align="right" cellpadding=3 cellspacing=0 class="fuente8">
    <tr>
      <td width="80%" rowspan="5" align="left">
        <table width="100%" border="0" align="right" cellpadding=3 cellspacing=0 class="fuente8"
        style="width: 736px;">
        <!-- <tr>
          <td width="14%" height="30">Modo de impresiÃ³n</td>
          <td width="50%"><select
            name="modo_impresion" <?php if ($tipo_docu == 'B' || $tipo_docu == 'N') echo 'disabled="disabled"'; ?>
            id="modo_impresion" class="comboGrande" style="width:307px">
            <option <?php if ($modo_impresion == '1') echo 'selected="selected"'; ?>
              value="1">LOS PRECIOS DE LOS PRODUCTOS DEBEN INCLUIR IGV
            </option>
            <option <?php if ($modo_impresion == '2') echo 'selected="selected"'; ?>
              value="2">LOS PRECIOS DE LOS PRODUCTOS NO DEBEN INCLUIR IGV
            </option>
          </select>
          &nbsp;&nbsp;Num Ref Guia Remision&nbsp;
          <input class="cajaGeneral" name="docurefe_codigo" type="text"
          id="docurefe_codigo" size="14"
          maxlength="26"
          value="<?php echo $docurefe_codigo; ?>"/>


        </td>

        <td width="7%" style="display: none;">Estado</td>

        <td style="display: none;">

                                      <input type="hidden" name="estado" id="estado"  value="<?php echo $estado; ?>" />

                                    </td>

                                  </tr> -->

                                  <tr>

                                    <td colspan="4">ObservaciÃ³n</td>

                                  </tr>

                                  <tr>

                                   <td colspan="4"><textarea id="observacion" name="observacion" class="cajaTextArea"
                                     style="width:97%; height:70px;"><?php echo $observacion; ?></textarea>
                                   </td>

                                 </tr>

                               </table>

                             </td>

                            

                            

                            <tr>

                              <td class="busqueda">CIF</td>

                              <?php if ($tipo_docu != 'B' && $tipo_docu != 'N') { ?>

                              <td align="right">
                                <div align="right"><input class="cajaTotales" name="importetotal" type="text"
                                 id="importetotal" readonly="" size="12" align="right"
                                 value="<?php echo str_replace(",", "", number_format($descuentotal, 2)); ?>"></div>
                               </td>

                               <?php } else { ?>

                               <td align="right">
                                <div align="right"><input class="cajaTotales" name="importetotal" type="text"
                                 readonly="" id="importetotal" size="12" align="right"
                                 value="<?php echo str_replace(",", "", number_format($descuentotal_conigv, 2)); ?>"></div>
                               </td>
 
                               <?php } ?>

                            </tr>
                            <tr>

                              <td class="busqueda">Ad Valorem</td>

                              <?php if ($tipo_docu != 'B' && $tipo_docu != 'N') { ?>

                              <td align="right">
                                <div align="right"><input class="cajaTotales" name="advaloremspan" type="text"
                                 id="advaloremspan" readonly="" size="12" align="right"
                                 value="<?php echo str_replace(",", "", number_format($descuentotal, 2)); ?>"></div>
                               </td>

                               <?php } else { ?>

                               <td align="right">
                                <div align="right"><input class="cajaTotales" name="advaloremspan" type="text"
                                 readonly="" id="advaloremspan" size="12" align="right"
                                 value="<?php echo str_replace(",", "", number_format($descuentotal_conigv, 2)); ?>"></div>
                               </td>

                               <?php } ?>

                            </tr>

                            <tr>

                              <td class="busqueda">Precio Total</td>

                              <td align="right">
                                <div align="right"><input class="cajaTotales" name="totalimport" type="text" id="totalimport"
                                 size="12" align="right" <?php

                                 if ($tipo_oper == 'V') {

                                  echo 'readonly="readonly"';

                                }

                                ?> value="<?php echo str_replace(",", "", number_format($importetotal, 2)); ?>"
                                onKeyPress="return numbersonly(this,event,'.');"/></div>
                              </td>

                            </tr>

                          </table>


            </div>

            <br/>

            <div id="botonBusqueda2" style="padding-top:20px;">

                <img id="loading" src="<?php echo base_url(); ?>images/loading.gif?=<?=IMG;?>" style="visibility: hidden"/>
                <?php if($liquidada != '1'): ?>
                    <a href="javascript:;" id="grabarComprobante"><img src="<?php echo base_url(); ?>images/botonaceptar.jpg?=<?=IMG;?>" width="85" height="22" class="imgBoton"></a>
                <?php endif; ?>
                <a href="javascript:;" id="limpiarComprobante"><img src="<?php echo base_url(); ?>images/botonlimpiar.jpg?=<?=IMG;?>"
                    width="69" height="22" class="imgBoton"></a>
                    <a href="javascript:;" id="cancelarComprobante"><img src="<?php echo base_url(); ?>images/botoncancelar.jpg?=<?=IMG;?>"
                      width="85" height="22" class="imgBoton"></a>
                      <?php echo $oculto ?>
                      <input type="hidden" name="cod" id="cod" value="<?php echo $codigo;?>">
                  </div>

              </div>
              <?php
              if ($cambio_comp == 1 && $total_det != 0) {
                if ($tipo_docu != "B" && $tipo_docu != "N") {
                 ?>
                 <script lang="javascript" type="text/javascript">
                    calcular_importe_todos(<?= $total_det ?>)
                </script>
                <?php
            } else {
                ?>
                <script lang="javascript" type="text/javascript">
                    modificar_pu_conigv_todos(<?= $total_det ?>)
                </script>
                <?php        }

            }

            ?>
            <style type="text/css">
                #popup {
                    left: 0;
                    position: absolute;
                    top: 0;
                    width: 100%;
                    z-index: 1001;
                }

                .content-popup {
                    margin:0px auto;
                    margin-top:150px;
                    position:relative;
                    padding:10px;
                    width:300px;
                    min-height:150px;
                    border-radius:4px;
                    background-color:#FFFFFF;
                    box-shadow: 0 2px 5px #666666;
                }

                .content-popup h2 {
                    color:#48484B;
                    border-bottom: 1px solid #48484B;
                    margin-top: 0;
                    padding-bottom: 4px;
                }

                .popup-overlay {
                    left: 0;
                    position: absolute;
                    top: 0;
                    width: 100%;
                    z-index: 999;
                    display:none;
                    background-color: #777777;
                    cursor: pointer;
                    opacity: 0.7;
                }

                .close {
                    position: absolute;
                    right: 15px;
                }
                #btnInventario{
                    size: 20px;
                    width: 200px;
                    height: 50px;
                    border-radius: 33px 33px 33px 33px;
                    -moz-border-radius: 33px 33px 33px 33px;
                    -webkit-border-radius: 33px 33px 33px 33px;
                    border: 0px solid #000000;
                    background-color:rgba(199, 255, 206, 1);

                }
            </style>
        </form>


        <a id="linkVerImpresion" href="#ventana"></a>
        <div id="ventana" style="display: none; width: 350px">
            <div id="imprimir" style="padding:20px; text-align: center">
                <span style="font-weight: bold;">
                  <?php if ($tipo_docu == 'F') echo 'FACTURA'; else echo 'BOLETA'; ?>
              </span>
              <div style="padding-left: 100px" >
                  <input type="text" name="ser_imp" id="ser_imp" readonly="readonly"
                  style="border: 0px; font: bold 10pt helvetica;" value="fsd" class="cajaMinima" maxlength="3"
                  >- <input type="text" name="num_imp" id="num_imp" readonly="readonly" style="border: 0px; font: bold 10pt helvetica;" value="lknmlk" class="cajaMedia"
                  maxlength="10">
              </div>  <br/>
              <a href="javascript:;" id="imprimirComprobante"><img src="<?php echo base_url(); ?>images/impresora.jpg?=<?=IMG;?>"
               class="imgBoton" alt="Imprimir"></a>
               <br/> <br/>
               <a href="javascript:;" id="cancelarImprimirComprobante"><img
                   src="<?php echo base_url(); ?>images/botoncancelar.jpg?=<?=IMG;?>" width="85" height="22" class="imgBoton"></a>
               </div>

           </div>


       </body>

       </html>

       <script>
        $("#moneda").change(function () {
            var combo = $(this),
            codigo = combo.val();

            $("#tdcOpcional").css('display', codigo > 2 ? '' : 'none');

            if(codigo > 2) $("#tdcEuro").focus();
        });

        $(document).ready(function () {
            $("#moneda").trigger('change');

            $(".tooltiped").tooltip();
        });
    </script>
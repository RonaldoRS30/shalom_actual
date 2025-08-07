<!--vista => producto_view.php-->

<script type="text/javascript" src="<?php echo base_url(); ?>js/almacen/producto.js?=<?=JS;?>"></script>

<link href="<?=base_url();?>js/fancybox/dist/jquery.fancybox.css?=<?=CSS;?>" rel="stylesheet">
<script src="<?=base_url();?>js/fancybox/dist/jquery.fancybox.js?=<?=JS;?>"></script>

<style>
.costo {
    display: inline-block;
    position: relative;
    padding: 0.5em;
    cursor: pointer;
}

.costo:hover {
    background: rgba(51, 51, 51, .1);
}

.costo .editar_costo {
    text-align: left;
    position: absolute;
    visibility: hidden;
    padding: 0.7em 0.7em 0.7em 0.7em;
    width: 25em;
    top: -25%;
    right: 100%;
    background: rgba(51, 51, 51, .9);
}

.costo:hover .editar_costo {
    visibility: visible;
    width: 25em;
    background: rgba(51, 51, 51, .9);
    border-radius: 0.1em 0.1em 0.1em 0.1em;
}

.costo:hover input,
.costo:hover .editar_costo img {
    opacity: 1;
}

.editar_costo input,
.editar_costo img {
    opacity: 0;
    width: auto;
}

.busqueda_opcinal {
    position: relative;
    text-align: center;
}

.busqueda_opcinal_1 {
    position: absolute;
    background-color: #004488;
    color: #f1f4f8;
    width: 98px;
    height: 70px;
    top: 14px;
    left: 135px;
    -webkit-box-shadow: 0px 0px 0px 3px rgba(47, 50, 50, 0.34);
    -moz-box-shadow: 0px 0px 0px 3px rgba(47, 50, 50, 0.34);
    box-shadow: 0px 0px 0px 3px rgba(47, 50, 50, 0.34);
    cursor: pointer;
}

.control_1 .seleccionado {
    position: absolute;
    border-radius: 3px;
    background-color: #29fb00;
    width: 98px;
    height: 5px;
    bottom: 20px;
    left: 135px;
}

.busqueda_opcinal_2 {
    position: absolute;
    background: #109EC8;
    color: #f1f4f8;
    width: 95px;
    height: 70px;
    top: 14px;
    right: 102px;
    cursor: pointer;
    -webkit-box-shadow: 0px 0px 0px 3px rgba(47, 50, 50, 0.34);
    -moz-box-shadow: 0px 0px 0px 3px rgba(47, 50, 50, 0.34);
    box-shadow: 0px 0px 0px 3px rgba(47, 50, 50, 0.34);
}

.control_2 .seleccionado {
    position: absolute;
    border-radius: 3px;
    background-color: #ab1c27;
    width: 96px;
    height: 5px;
    bottom: 21px;
    right: 102px;
}
</style>

<div class="container-fluid">
    <div class="row header">
        <div class="col-md-12 col-lg-12">
            <div><?=$titulo_busqueda;?></div>
        </div>
    </div>
    <form id="form_busqueda" method="post">
        <div class="row fuente8 py-1">
            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                <label for="txtCodigo">Código: </label>
                <input id="txtCodigo" name="txtCodigo" type="text" class="form-control w-porc-90 h-1"
                    placeholder="Codigo" maxlength="30" value="<?=$codigo;?>">
            </div>
            <?php if($flagBS == 'S'): ?>
            <div class="col-sm-2 col-md-2 col-lg-2 form-group">
                <label for="txtNombre">Nombre: </label>
                <input id="txtNombre" name="txtNombre" type="text" class="form-control w-porc-90 h-1" maxlength="100"
                    placeholder="Nombre servicio" value="<?php echo $nombre; ?>">
            </div>
            <?php else: ?>
            <div class="col-sm-2 col-md-2 col-lg-2 form-group">
                <label for="txtNombre">Nombre: </label>
                <input id="txtNombre" name="txtNombre" type="text" class="form-control w-porc-90 h-1" maxlength="100"
                    placeholder="Nombre producto" value="<?php echo $nombre; ?>">
            </div>
            <?php endif; ?>
            <div class="col-sm-2 col-md-2 col-lg-2 form-group">
                <label for="txtFamilia">Familia: </label>
                <select name="txtFamilia" id="txtFamilia" class="form-control w-porc-90 h-2">
                    <option value=""> TODOS </option><?php
                    if ($familias != NULL){
                        foreach ($familias as $i => $v){ ?>
                    <option value="<?=$v->FAMI_Codigo;?>"><?=$v->FAMI_Descripcion;?></option> <?php
                        }
                    } ?>
                </select>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 form-group" <?=($flagBS == 'S') ? 'hidden' : '';?>>
                <label for="txtMarca">Marca: </label>
                <input id="txtMarca" type="text" class="form-control w-porc-90 h-1" name="txtMarca" maxlength="100"
                    placeholder="Marca producto" value="<?=$marca;?>">
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 form-group" <?=($flagBS == 'S') ? 'hidden' : '';?>>
                <label for="txtModelo">Modelo: </label>
                <select name="txtModelo" id="txtModelo" class="form-control w-porc-90 h-2">
                    <option value=""> TODOS </option><?php
                    if ($modelos != NULL){
                        foreach ($modelos as $indice => $val){
                            if ($val->PROD_Modelo != ''){ ?>
                    <option value="<?=$val->PROD_Modelo;?>"><?=$val->PROD_Modelo;?></option> <?php
                            }
                        }
                    } ?>
                </select>
            </div>
            <?php if($flagBS == 'S'): ?>

            <?php else: ?>
            <div class="col-sm-1 col-md-1 col-lg-1"><br>
            <ul id="cargaMasivaProductos" class="lista_botones lola">
                <li id="excel">Cargar Productos</li>
            </ul>
            </div>

            <style>
                .lola{
                    background-color: slategrey;
                    border-radius: 2rem;
                }
            </style>

            <script>
                function modalCargarInventario(ajuste, almacen) {
                    $("#formCargaStock")[0].reset();
                    $("#ajusteCS").val(ajuste);
                    $("#almacenCS").val(almacen);
                    $("#modalCargarStock").modal("toggle");
                }
            </script>
            
            <?php endif; ?>
            <input id="codigoInterno" name="codigoInterno" type="hidden" class="cajaGrande" maxlength="100"
                placeholder="Codigo original" value="<?=$codigoInterno;?>">
        </div>
    </form>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <div class="acciones">
                        <div id="botonBusqueda">
                        <?php if($flagBS == 'S'):  ?>
                             <!-- <ul id="imprimirServicio" class="lista_botones">
                                <li id="imprimir">Imprimir </li>                               
                              </ul> -->
                            <?php else: ?>
                              <!-- <ul id="imprimirProducto" class="lista_botones">
                                <li id="imprimir">Imprimir </li>                             
                              </ul> -->
                             <?php endif; ?>

                            <ul class="lista_botones" data-toggle="modal" data-target="#modal_producto">
                                <li id="nuevo">Nuevo <?php if ($flagBS == 'B') echo 'Artículo'; else echo 'Servicio'; ?></li>
                            </ul>
                            <ul id="limpiarP" class="lista_botones">
                                <li id="limpiar">Limpiar</li>
                            </ul>
                            <ul id="buscarP" class="lista_botones">
                                <li id="buscar">Buscar</li>
                            </ul>
                             <ul id="modelo_exportacion" class="lista_botones">
                                <li id="excel">Exportar modelo</li>
                            </ul>
                            <ul id="SubirFormato" class="lista_botones">
                                <li id="subir">Cargar Formato</li>
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
                    <div id="cargando_datos" class="loading-table">
                        <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                    </div>
                    <table class="fuente8 display" id="table-productos">
                        <thead>
                            <tr class="cabeceraTabla">
                                <th style="width: 05%" data-orderable="true">CÓDIGO</th>
                                <th style="width: 20%" data-orderable="true">NOMBRE</th>
                                <th style="width: 10%" data-orderable="true">FAMILIA</th>
                                <th style="width: 10%" data-orderable="true"><?=($flagBS == "B") ? "MARCA" : "";?></th>
                                <th style="width: 10%" data-orderable="false">UNIDAD MEDIDA</th>
                                <th data-orderable="true"><?=($flagBS == "B") ? "P. COSTO" : "";?></th> <?php
                                
                                foreach ($categorias as $key => $val){ ?>
                                    <th style="text-indent: 0;" data-orderable="false"><?=$val->TIPCLIC_Descripcion;?></th> <?php
                                }?>
                                
                                <th style="width: 05%" data-orderable="false">EDITAR</th>
                                <th style="width: 05%" data-orderable="false">BARCODE</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal_totales" class="modal fade" role="dialog">
    <div class="modal-dialog w-porc-60">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div style="text-align: center;">
                <h3><b>TOTAL EN INVERSIÓN</b></h3>
            </div>
            <div class="modal-body panel panel-default">
                <div class="row form-group">
                    <div class="col-sm-11 col-md-11 col-lg-11">
                        <table class="fuente8 display" id="table-totales">
                            <thead>
                                <tr class="cabeceraTabla">
                                    <th style="width:60%;" data-orderable="true">CATEGORIA</th>
                                    <th style="width:40%;" data-orderable="true">TOTAL EN ARTICULOS</th>
                                </tr>
                            </thead>
                            <tbody> <?php
                                if ( isset($totalesCat) && $totalesCat != NULL){
                                    foreach ($totalesCat as $key => $value) { ?>
                                <tr>
                                    <td style="text-align: left;"><?=$value->categoria;?></td>
                                    <td align="right"><?="$value->moneda ".number_format($value->total,2);?></td>
                                </tr>
                                <?php
                                    }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_producto" class="modal fade" role="dialog">
    <div class="modal-dialog w-porc-80">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-center">REGISTRAR <?=($flagBS == 'B') ? 'ARTICULO' : 'SERVICIO';?></h3>
            </div>
            <div class="modal-body panel panel-default">
                <form id="form_nvo" method="POST" action="#">
                    <div class="row form-group header">
                        <div class="col-sm-11 col-md-11 col-lg-11">
                            DETALLES DEL <?=($flagBS == 'B') ? 'ARTICULO' : 'SERVICIO';?>
                            <input type="hidden" id="id" name="id" />
                            <input type="hidden" id="flagB_S" name="flagB_S" value="<?=$flagBS;?>" />
                        </div>
                    </div>

                    <div class="row form-group font-9">
                        <div class="col-sm-1 col-md-1 col-lg-1">
                            <label for="nvo_codigo">CÓDIGO:</label>
                            <input type="text" id="nvo_codigo" name="nvo_codigo" oninput="validarNumero()"
                                class="form-control h-2 w-porc-90" />
                            <p id="mensajeError" style="color: red;"></p>
                        </div>

                        <script>
                        function validarNumero() {
                            var input = document.getElementById("nvo_codigo");
                            var mensajeError = document.getElementById("mensajeError");
                            var valor = input.value;

                            // Verifica si hay ceros adelante
                            if (valor.length > 1 && valor[0] === "0") {
                                mensajeError.textContent = "No se permiten ceros adelante.";
                                input.value = valor.slice(1); // Elimina el cero inicial
                            } else {
                                mensajeError.textContent = "";
                            }
                        }
                        </script>

                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <label for="nvo_nombre">NOMBRE:</label>
                            <input type="text" id="nvo_nombre" name="nvo_nombre" class="form-control h-2 w-porc-90" />
                        </div>

                        <div class="col-sm-2 col-md-2 col-lg-2" hidden>
                            <label for="nvo_autocompleteCodigoSunat">CÓDIGO SUNAT:</label>
                            <input type="text" id="nvo_autocompleteCodigoSunat" name="nvo_autocompleteCodigoSunat"
                                class="form-control h-2 w-porc-90" />
                        </div>
                        <div class="col-sm-1 col-md-1 col-lg-1" hidden><br>
                            <input type="text" id="nvo_codigoSunat" name="nvo_codigoSunat"
                                class="form-control h-2 w-porc-90" readOnly />
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-3">
                            <label for="nvo_tipoAfectacion">AFECTACIÓN:</label>
                            <select id="nvo_tipoAfectacion" name="nvo_tipoAfectacion" class="form-control h-3"> <?php
                                foreach ($afectaciones as $i => $val) { ?>
                                <option value="<?=$val->AFECT_Codigo?>"><?=$val->AFECT_DescripcionSmall;?></option> <?php
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group font-9" hidden>
                        <div class="col-sm-10 col-md-10 col-lg-11">
                            <label for="nvo_descripcion">DESCRIPCIÓN</label>
                            <textarea class="form-control" id="nvo_descripcion" name="nvo_descripcion" maxlength="800"
                                placeholder="Indique una descripción"></textarea>
                            <div class="pull-right">
                                Caracteres restantes:
                                <span class="contadorCaracteres">800</span>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group font-9">
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <label for="nvo_familia">FAMILIA:</label>
                            <select id="nvo_familia" name="nvo_familia" class="form-control h-3"> <?php
                                foreach ($familias as $i => $val) { ?>
                                <option value="<?=$val->FAMI_Codigo?>"><?=$val->FAMI_Descripcion;?></option> <?php
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-2" <?=($flagBS == 'S') ? 'hidden' : '';?>>
                            <label for="nvo_fabricante">FABRICANTE:</label>
                            <select id="nvo_fabricante" name="nvo_fabricante" class="form-control h-3">
                                <option value=""> :: SELECCIONE :: </option> <?php
                                foreach ($fabricantes as $i => $val) { ?>
                                <option value="<?=$val->FABRIP_Codigo?>"><?=$val->FABRIC_Descripcion;?></option> <?php
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-2" <?=($flagBS == 'S') ? 'hidden' : '';?>>
                            <label for="nvo_marca">MARCA:</label>
                            <select id="nvo_marca" name="nvo_marca" class="form-control h-3">
                                <option value=""> :: SELECCIONE :: </option> <?php
                                foreach ($marcas as $i => $val) { ?>
                                <option value="<?=$val->MARCP_Codigo?>"><?=$val->MARCC_Descripcion;?></option> <?php
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-2" <?=($flagBS == 'S') ? 'hidden' : '';?>>
                            <label for="nvo_modelo">MODELO:</label>
                            <input type="text" id="nvo_modelo" name="nvo_modelo" class="form-control h-2 w-porc-90" />
                            <!--
                                LOS DATOS DEL MODELO SON UTILIZADOS EN PRODUCCION, ASI DIFERENCIA ENTRE ARTICULOS E INSUMOS.
                                valor = "ARTICULO" ó "INSUMO"
                            -->
                        </div>
                        <div class="col-sm-1 col-md-1 col-lg-1" <?=($flagBS == 'S') ? 'hidden' : '';?>>
                            <label for="nvo_stockMin">STOCK MINIMO:</label>
                            <input type="number" step="1" min="0" id="nvo_stockMin" name="nvo_stockMin" value="0"
                                class="form-control h-2 w-porc-90" />
                        </div>
                    </div>

                    <div class="row form-group font-9">
                        <?php if($flagBS == 'S'): ?>
                        <input type="hidden" value="32" id="nvo_unidad[0]" name="nvo_unidad[0]" />
                        <?php else: ?>
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <label for="nvo_unidad">UNIDAD DE MEDIDA:</label>
                            <select id="nvo_unidad[0]" name="nvo_unidad[0]" class="form-control h-3"> <?php
                                foreach ($unidades as $i => $val) { ?>
                                <option value="<?=$val->UNDMED_Codigo?>"
                                    <?=($flagBS == 'S' && trim($val->UNDMED_Simbolo) != 'ZZ') ? 'disabled' : '';?>
                                    <?=($flagBS == 'B' && trim($val->UNDMED_Simbolo) == 'NIU') ? 'selected' : '';?>
                                    <?=($flagBS == 'B' && trim($val->UNDMED_Simbolo) == 'ZZ') ? 'disabled' : '';?>>
                                    <?="$val->UNDMED_Descripcion | $val->UNDMED_Simbolo";?></option> <?php
                                } ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <label for="nvo_preciocosto">PRECIO COSTO S/:</label>
                            <input type="number" id="nvo_preciocosto" name="nvo_preciocosto" value="0"
                                class="form-control h-2 w-porc-90" />
                        </div>
                          <div class="col-sm-2 col-md-2 col-lg-2">
                                <label for="nvo_peso">PESO</label>
                                    <input type="text" id="nvo_peso" name="nvo_peso" 
                                        class="form-control h-2 w-porc-90"
                                        pattern="^\d+(\.\d{1,2})?$" 
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')"
                                    />
                            </div>


                        <div class="col-sm-2 col-md-2 col-lg-2">
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-2">
                        </div>
                        <div class="col-sm-1 col-md-1 col-lg-1">
                        </div>
                        <!-- botom para listar sucursales-->
                        <div style="display:none" class="function">
                            <div id="btn-sucursales" class="col-sm-2 col-md-2 col-2" style="margin-top: 25px;">
                                <a class="btn btn-danger" onclick="toggleSucursales()">Sucursales</a>
                            </div>
                            <div id="btn-guia" class="col-sm-2 col-md-2 col-2"
                                style="margin-top: 25px; width: 100px;  position:relative; right: 130px;"
                                onmouseover="mostrarMensaje()" onmouseout="ocultarMensaje()">
                                <a style="color:#085AB2; font-size:large"><strong>?</strong></a>
                                <div id="mensajeEmergente">Con esta opcion podra registrar el articulo tanto en esta
                                    sucursal como en las disponibles que usted elija.</div>
                            </div>
                        </div>
                        <style>
                        /* @keyframes neon {
                                0% { text-shadow: 0 0 10px #00fff9, 0 0 20px #00fff9, 0 0 30px #00fff9; }
                                50% { text-shadow: 0 0 10px #00fff9, 0 0 20px #00fff9, 0 0 30px #00fff9, 0 0 40px #00fff9; }
                                100% { text-shadow: 0 0 10px #00fff9, 0 0 20px #00fff9, 0 0 30px #00fff9, 0 0 40px #00fff9, 0 0 50px #00fff9; }
                            }

                            @keyframes blink {
                                0% { opacity: 1; }
                                50% { opacity: 0; }
                                100% { opacity: 1; }
                            }

                            #btn-guia a {
                                color: #085AB2;
                                font-size: large;
                                text-decoration: none;
                                animation: neon 1.5s infinite alternate, blink 1s infinite step-start;
                            } */
                        #mensajeEmergente {
                            display: none;
                            position: absolute;
                            background-color: #fff;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                            padding: 10px;
                            z-index: 1;
                        }
                        </style>
                        <script>
                            document.getElementById("nvo_peso").addEventListener("input", function (e) {
                                    let valor = e.target.value;

                                    
                                    valor = valor.replace(/[^0-9.]/g, '');

                                   
                                    let partes = valor.split('.');
                                    if (partes.length > 2) {
                                        valor = partes[0] + '.' + partes.slice(1).join('');
                                    }

                                    
                                    if (partes.length === 2 && partes[1].length > 2) {
                                        valor = partes[0] + '.' + partes[1].slice(0, 2);
                                    }

                                    e.target.value = valor;
                                });
                        function mostrarMensaje() {
                            var mensajeEmergente = document.getElementById("mensajeEmergente");
                            mensajeEmergente.style.display = "block";
                        }

                        function ocultarMensaje() {
                            var mensajeEmergente = document.getElementById("mensajeEmergente");
                            mensajeEmergente.style.display = "none";
                        }
                        </script>
                        <div id="lista-sucursales" style="display: none; ">
                            <table class="table table-bordered" style="margin-top: 90px;">
                                <thead style="background-color:#F1F9CB">
                                    <tr>
                                        <th style="width: 210px;">EMPRESA</th>
                                        <th style="width: 200px;">SUCURSAL</th>
                                        <th style="width: 50px;">SELECIONAR</th>
                                    </tr>
                                </thead>
                                <tbody> <?php
                                        $empresa = NULL;
                                        foreach ($establecimientos as $i => $val){ ?>
                                    <tr>
                                        <td><?=$val->EMPRC_RazonSocial;?></td>
                                        <td><?=$val->EESTABC_Descripcion;?>
                                            <input type="hidden" name="establecimientos[]"
                                                class="establecimientos-input" value="<?=$val->COMPP_Codigo;?>" />
                                        </td>
                                        <td>
                                            <input name="seleccion-sucursal" class="seleccion-sucursal" type="checkbox"
                                                value="<?=$val->COMPP_Codigo;?>" checked>
                                        </td>

                                    </tr> <?php
                                        } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row form-group header info_formapago">
                        <div class="col-sm-11 col-md-11 col-lg-11">
                            PRECIOS
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                            <table class="fuente8 display" id="table-precios">
                                <thead>
                                    <tr class="cabeceraTabla">
                                        <th data-orderable="false">CATEGORIA</th> <?php
                                        foreach ($precio_monedas as $i => $val) { ?>
                                        <th style="width: 15%" data-orderable="false"><?=$val->MONED_Descripcion;?></th> <?php
                                        } ?>
                                    </tr>
                                </thead>
                                <tbody> <?php
                                        foreach ($precio_categorias as $i => $val) { ?>
                                    <tr>
                                        <td><?=$val->TIPCLIC_Descripcion;?></td> <?php
                                                foreach ($precio_monedas as $j => $value) { ?>
                                        <td>
                                            <input type="number" name="nvo_pcategoria[]"
                                                value="<?=$val->TIPCLIP_Codigo;?>" hidden />
                                            <input type="number" name="nvo_pmoneda[]" value="<?=$value->MONED_Codigo;?>"
                                                hidden />
                                            <input type="number" step="1.00" min="1" name="precios[]" value="0"
                                                class="form-control h-1 w-porc-80 precio-<?=$val->TIPCLIP_Codigo.$value->MONED_Codigo;?>" />
                                        </td> <?php
                                                } ?>
                                    </tr><?php
                                        } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="registrar()">Guardar</button>
                <button type="button" class="btn btn-info nvo_limpiar">Limpiar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>
<div id="modal_precios" class="modal fade" role="dialog" style="width: 60%;">
    <div class="modal-dialog w-porc-80">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-center">REGISTRAR PRECIOS</h3>
            </div>
            <div class="modal-body panel panel-default">
                <form id="form_precios" method="POST" action="#">
                    <div class="row form-group header info_formapago">
                        <div class="col-sm-11 col-md-11 col-lg-11">
                            PRECIOS
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                            <input type="text" id="idP" name="idP" />
                            <table class="fuente8 display" id="table-precios">
                                <thead>
                                    <tr class="cabeceraTabla">
                                        <th data-orderable="false">CATEGORIA</th> <?php
                                        foreach ($precio_monedas as $i => $val) { ?>
                                        <th style="width: 15%" data-orderable="false"><?=$val->MONED_Descripcion;?></th> <?php
                                        } ?>
                                    </tr>
                                </thead>
                                <tbody> <?php
                                        foreach ($precio_categorias as $i => $val) { ?>
                                    <tr>
                                        <td><?=$val->TIPCLIC_Descripcion;?></td> <?php
                                                foreach ($precio_monedas as $j => $value) { ?>
                                        <td>
                                            <input type="number" name="nvo_pcategoria[]"
                                                value="<?=$val->TIPCLIP_Codigo;?>" hidden />
                                            <input type="number" name="nvo_pmoneda[]" value="<?=$value->MONED_Codigo;?>"
                                                hidden />
                                            <input type="number" step="1.00" min="1" name="precios[]" value="0"
                                                class="form-control h-1 w-porc-80 precio-<?=$val->TIPCLIP_Codigo.$value->MONED_Codigo;?>" />
                                        </td> <?php
                                                } ?>
                                    </tr><?php
                                        } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="registrar()">Guardar</button>
                <button type="button" class="btn btn-info nvo_limpiar">Limpiar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" style="width: 800px;" tabindex="-1" role="dialog" id="modal_carga_masiva">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">IMPORTACION DE PRECIOS</h5>
        
      </div>
      <div class="modal-body">
        <div class="row">
            <input type="file" id="archivo_guias" class="form-control w-porc-90">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary subir_formato">Cargar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Cargar Stock -->
<div id="modalCargarStock" class="modal fade" role="dialog">
    <div class="modal-dialog w-porc-50">
        <div class="modal-content">
            
                <input type="hidden" name="ajusteCS" id="ajusteCS" value="">
                <div class="modal-header">
                    <h3 class="modal-title">Cargar Masiva de Productos</h3>
                </div>
                <div class="modal-body panel panel-default">
                    <div class="row form-group">
                        <div class="col-sm-11 col-md-11 col-lg-11">
                    </div>

                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="excelCS">Documento excel *</label>
                            <input type="file" name="excelCS" id="excelCS" class="oculto">
                        </div>
                        <div class="col-md-4"><br>
                            <button class="btn btn-warning" id="descargar_modelo" type="button">Descargar Formato</button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success productoCargaMasiva">Cargar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                </div>
        </div>
    </div>
</div>
<!-- End modal cargar stock -->


<script language="javascript">
$(document).ready(function() {

    $("#cargaMasivaProductos").click(function(){
        $("#excelCS").val("");
        $("#modalCargarStock").modal("show");
    });

    $(".productoCargaMasiva").click(function(){
            let inputArchivo = document.getElementById("excelCS");
            let archivo = inputArchivo.files[0];
            if (!archivo) {
                Swal.fire(
                    'Archivo no cargado',
                    'Debe seleccionar un archivo antes de continuar.',
                    'error'
                );
                return false;
            }
            let nombre_archivo = archivo.name;
            let extension = nombre_archivo.split(".").pop().toLowerCase();
            if (extension !== "xls" && extension !== "xlsx") {
                Swal.fire(
                    'Archivo no válido',
                    'Por favor, selecciona un archivo de Excel válido (con extensión .xls o .xlsx).',
                    'error'
                );
                return false;
            }
            Swal.fire({
                title: '¿Generar cambios en los precios?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Generar',
                cancelButtonText: 'Cerrar'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData();
                    var files = $('#excelCS')[0].files[0];
                    formData.append('file',files);
                    $.ajax({
                        url: base_url+"index.php/almacen/almacen/insertarProductosMasiva/",
                        type:'POST',
                        data: formData,
                        dataType:'json',
                        contentType: false,
                        processData: false,
                        beforeSend: function()
                        {
                            Swal.fire({
                                imageUrl: base_url + 'images/cargaexcel.gif',
                                html: "<b class='color-green'>Dependiendo de la cantidad de registros, esto puede tardar varios minutos</b>" 
                            });
                        },
                        success: function(response) {
                            console.log(response.length);

                            if (response.length == 0) {
                                Swal.fire({
                                    icon: "success",
                                    title: "!Productos registrados!",
                                    html: "<b class='color-red'></b>",
                                    showConfirmButton: true,
                                    allowOutsideClick: false,
                                    confirmButtonText: "Ok" 
                                });
                                $("#modalCargarStock").modal("hide");

                                return;
                            }

                            let tableRows = "";
                            response.forEach(function(producto) {
                                tableRows += `
                                    <tr>
                                        <td>${producto.codigo_interno_producto}</td>
                                        <td>${producto.descripcion_codigo}</td>
                                    </tr>
                                `;
                            });

                            $(".table tbody").html(tableRows);

                            Swal.fire({
                                title: "Productos Repetidos",
                                html: `
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>COD INTERNO</th>
                                                <th>NOMBRE PRODUCTO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${tableRows}  <!-- Insertar las filas aquí -->
                                        </tbody>
                                    </table>
                                `,
                                showConfirmButton: true,
                                allowOutsideClick: false,
                                confirmButtonText: "Ok"
                            });

                            // Ocultar otro modal si es necesario
                            $("#modalCargarStock").modal("hide");
                        },

                        error: function()
                        {
                            Swal.fire(
                                'Error',
                                'ERROR AL CARGARSE LOS PRODUCTOS, COMUNIQUESE CON SOPORTE TECNICO .',
                                'error'
                            )
                            $("#modalCargarStock").modal("hide");
                        }
                    });
                }
            })
        });

    $("#SubirFormato").click(function(){
            $("#archivo_guias").val("");
            $("#modal_carga_masiva").modal("show");
        });
        $(".subir_formato").click(function(){
            let inputArchivo = document.getElementById("archivo_guias");
            let archivo = inputArchivo.files[0];
            if (!archivo) {
                Swal.fire(
                    'Archivo no cargado',
                    'Debe seleccionar un archivo antes de continuar.',
                    'error'
                );
                return false;
            }
            let nombre_archivo = archivo.name;
            let extension = nombre_archivo.split(".").pop().toLowerCase();
            if (extension !== "xls" && extension !== "xlsx") {
                Swal.fire(
                    'Archivo no válido',
                    'Por favor, selecciona un archivo de Excel válido (con extensión .xls o .xlsx).',
                    'error'
                );
                return false;
            }
            Swal.fire({
                title: '¿Generar cambios en los precios?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Generar',
                cancelButtonText: 'Cerrar'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData();
                    var files = $('#archivo_guias')[0].files[0];
                    formData.append('file',files);
                    $.ajax({
                        url: base_url+"index.php/almacen/almacen/Leer_Guias_Excel/",
                        type:'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function()
                        {
                            Swal.fire('Cargando...')
                        },
                        success:  function (response)
                        {
                            console.log(response);
                            Swal.fire(
                                'Exito',
                                'Sus guias fueron generadas',
                                'success'
                            )
                            $("#modal_carga_masiva").modal("hide");
                        },
                        error: function()
                        {
                            Swal.fire(
                                'Error',
                                'Sus guías no Pudieron Cargarse, Verifique que las rutas existan.',
                                'error'
                            )
                            $("#modal_carga_masiva").modal("hide");
                        }
                    });
                }
            })
        });

    $("#descargar_modelo").click(function()
    {
        postFormDescarga(base_url+"index.php/almacen/almacen/getFormatoCargaProductos/", {pase: '1'});
    });

    $("#modelo_exportacion").click(function()
    {
        postForm(base_url+"index.php/almacen/almacen/formato_importacion_precio/", {pase: '1'});
    });

    $('#table-totales').DataTable({
        responsive: true,
        filter: false,
        destroy: true,
        autoWidth: false,
        paging: false,
        language: spanish
    });

    $('#table-precios').DataTable({
        responsive: true,
        filter: false,
        destroy: true,
        autoWidth: false,
        paging: false,
        language: spanish
    });

    $('#table-productos').DataTable({
        responsive: true,
        filter: false,
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?=base_url();?>index.php/almacen/producto/datatable_productos/<?=$flagBS;?>",
            type: "POST",
            data: {
                dataString: ""
            },
            beforeSend: function() {},
            error: function() {}
        },
        language: spanish
    });

    $("#buscarP").click(function() {
        codigo = $('#txtCodigo').val();
        producto = $('#txtNombre').val();
        familia = $('#txtFamilia').val();
        marca = $('#txtMarca').val();
        modelo = $('#txtModelo').val();

        $('#table-productos').DataTable({
            responsive: true,
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?=base_url();?>index.php/almacen/producto/datatable_productos/<?=$flagBS;?>",
                type: "POST",
                data: {
                    txtCodigo: codigo,
                    txtNombre: producto,
                    txtFamilia: familia,
                    txtMarca: marca,
                    txtModelo: modelo
                },
                error: function() {}
            },
            language: spanish
        });
    });

    $("#limpiarP").click(function() {

        $("#txtCodigo").val("");
        $("#txtNombre").val("");
        $("#txtFamilia").val("");
        $("#txtMarca").val("");
        $("#txtModelo").val("");

        codigo = "";
        producto = "";
        familia = "";
        marca = "";
        modelo = "";

        $('#table-productos').DataTable({
            responsive: true,
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?=base_url();?>index.php/almacen/producto/datatable_productos/<?=$flagBS;?>",
                type: "POST",
                data: {
                    txtCodigo: codigo,
                    txtNombre: producto,
                    txtFamilia: familia,
                    txtMarca: marca,
                    txtModelo: modelo
                },
                error: function() {}
            },
            language: spanish
        });
    });

    $("#nvo_autocompleteCodigoSunat").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "<?=base_url();?>index.php/almacen/producto/autocompleteIdSunat/",
                type: "POST",
                data: {
                    term: $("#nvo_autocompleteCodigoSunat").val()
                },
                dataType: "json",
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.descripcion,
                            value: item.descripcion,
                            idsunat: item.idsunat
                        }
                    }));
                }
            });
        },
        select: function(event, ui) {
            $("#nvo_codigoSunat").val(ui.item.idsunat);
        },
        minLength: 2
    });

    $("#nvo_codigo").change(function() {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?=base_url();?>index.php/almacen/producto/existsCode/",
            data: {
                codigo: $(this).val(),
                producto: $("#id").val()
            },
            success: function(data) {
                if (data.match == true) {
                    Swal.fire({
                        icon: "info",
                        title: "Código registrado.",
                        html: "<b class='color-red'>El código ingresado ha sido registrado anteriormente.</b>",
                        showConfirmButton: true
                    });
                }
            }
        });
    });

    $("#nvo_nombre").change(function() {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?=base_url();?>index.php/almacen/producto/existsNombre/",
            data: {
                nombre: $(this).val(),
                producto: $("#id").val()
            },
            success: function(data) {
                if (data.match == true) {
                    Swal.fire({
                        icon: "info",
                        title: "Nombre registrado.",
                        html: "<b class='color-red'>El nombre ingresado ha sido registrado anteriormente.</b>",
                        showConfirmButton: true
                    });
                }
            }
        });
    });

    $("#nvo_descripcion").keyup(function() {
        var descripcion = $("#nvo_descripcion").val().length;

        longitud = 800 - descripcion;
        $(".contadorCaracteres").html(longitud);
    });

    $(".nvo_limpiar").click(function() {
        clean();
    });
});

function getProducto(id) {
    var url = base_url + "index.php/almacen/producto/getProductoInfo";
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: {
            producto: id
        },
        beforeSend: function() {
            clean();
        },
        success: function(data) {
            console.log(data)
            if (data.match == true) {
                info = data.producto;
                unidad = data.unidades;
                precio = data.precios;
                compania = data.establecimientos;

                $("#id").val(info.producto);
                $("#nvo_codigo").val(info.codigo);
                $("#nvo_nombre").val(info.nombre);
                $("#nvo_autocompleteCodigoSunat").val(info.sunatDescripcion);
                $("#nvo_codigoSunat").val(info.sunatCodigo);
                $("#nvo_tipoAfectacion").val(info.afectacion);
                $("#nvo_descripcion").val(info.descripcion);
                $("#nvo_familia").val(info.familia);
                $("#nvo_fabricante").val(info.fabricante);
                $("#nvo_marca").val(info.marca);
                $("#nvo_modelo").val(info.modelo);
                $("#nvo_stockMin").val(info.stockMin);
                $("#nvo_preciocosto").val(info.nvo_preciocosto);
                $("#nvo_peso").val(info.peso);

                $.each(compania, function(i, codigo) {
                    var checkbox = $('input[value="' + codigo.Codigo + '"]');
                    if (checkbox.length > 0) {
                        checkbox.prop('checked', true);
                    }
                });
                /*
                $("#nvo_codigo").attr({
                  readOnly: true
                });
                */

                campo_unidad = "nvo_unidad[0]";
                $.each(unidad, function(i, v) {
                    document.getElementById(campo_unidad).value = v.unidad;
                });

                $.each(precio, function(i, v) {
                    $(".precio-" + v.categoria + v.moneda).val(v.precio);
                });

                $("#modal_producto").modal("toggle");
            } else {
                Swal.fire({
                    icon: "info",
                    title: "Información no disponible.",
                    html: "<b class='color-red'></b>",
                    showConfirmButton: true,
                    timer: 4000
                });
            }
        },
        complete: function() {}
    });
}

function toggleSucursales() {
    var listaSucursales = document.getElementById("lista-sucursales");
    if (listaSucursales.style.display === "none") {
        listaSucursales.style.display = "block";
    } else {
        listaSucursales.style.display = "none";
    }
}

function registrar() {
    Swal.fire({
        icon: "info",
        title: "¿Esta seguro de guardar el registro?",
        html: "<b class='color-red'></b>",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar"
    }).then(result => {
        if (result.value) {
            let id = $("#id").val();
            let nombre = $("#nvo_nombre").val();
            validacion = true;
            var codigo_usuario = $("#nvo_codigo").val();
            if (nombre == "") {
                Swal.fire({
                    icon: "error",
                    title: "Verifique los datos ingresados.",
                    html: "<b class='color-red'>Debe ingresar un nombre.</b>",
                    showConfirmButton: true,
                    timer: 4000
                });
                $("#nvo_nombre").focus();
                validacion = false;
                return null;
            }
            if ($("#nvo_codigo").val() == "") {
                Swal.fire({
                    icon: "error",
                    title: "Verifique los datos ingresados.",
                    html: "<b class='color-red'>Debe ingresar un código.</b>",
                    showConfirmButton: true,
                    timer: 4000
                });
                $("#nvo_codigo").focus();
                validacion = false;
                return null;
            }

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?=base_url();?>index.php/almacen/producto/existsCode/",
                data: {
                    codigo: codigo_usuario,
                    producto: $("#id").val()
                },
                success: function(data) {
                    if (validacion) {
                        if (data.match == true) {
                            Swal.fire({
                                icon: "info",
                                title: "Este código ya se encuentra registrado.",
                                html: "<b style='color: red; font-size: 12pt;'>¿Desea continuar?</b>",
                                showConfirmButton: true,
                                showCancelButton: true,
                                confirmButtonText: "Aceptar",
                                cancelButtonText: "Cancelar"
                            }).then(result => {
                                if (result.value) {
                                    if (validacion == true) {
                                        registro_producto();
                                    }
                                } else {
                                    $("#nvo_codigo").focus();
                                    return false;
                                }
                            });
                        } else {
                            registro_producto();
                        }
                    }
                }
            });
        }
    });
}

// if(seleccionados == 0){
//                 Swal.fire({
//                     icon: "error",
//                     title: "Advertencia",
//                     html: "<b class='color-red'>Debe ingresar el articula a una a mas sucusrsales</b>",
//                     showConfirmButton: true,
//                     timer: 4000
//                 });
//                 return;
//                 }
function registro_producto() {
    var url = base_url + "index.php/almacen/producto/guardar_registro";
    var info = $("#form_nvo").serialize();

    var seleccionados = [];
    $(".seleccion-sucursal:checked").each(function() {
        seleccionados.push($(this).val());
    });

    info += "&seleccionados=" + JSON.stringify(seleccionados);
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: info,
        success: function(data) {

            let id = $("#id").val();
            if (data.result == "success") {
                if (id == "")
                    titulo = "¡Registro exitoso!";
                else
                    titulo = "¡Actualización exitosa!";

                Swal.fire({
                    icon: "success",
                    title: titulo,
                    showConfirmButton: true,
                    timer: 2000
                });
                clean();
                $("#limpiar").click();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Sin cambios.",
                    html: "<b class='color-red'>La información no fue registrada/actualizada, intentelo nuevamente.</b>",
                    showConfirmButton: true,
                    timer: 4000
                });
            }
        },
        complete: function() {
            $("#nvo_codigo").focus();
        }
    });
}

function insertar_costo(id, precioc) {
    costo = $("#" + precioc).val();

    if (id != '' && costo != '') {
        url = base_url + "index.php/almacen/producto/nvoCosto";

        $.ajax({
            type: "POST",
            url: url,
            data: {
                codigo: id,
                nvoCosto: costo
            },
            dataType: 'json',
            beforeSend: function() {
                $("#btnCosto" + precioc).hide();
                $("#loading" + precioc).show();
            },
            success: function(data) {
                console.log(data);
                if (data.result == 'success') {

                    Swal.fire({
                        icon: "success",
                        title: "Precio actualizado.",
                        showConfirmButton: true,
                        timer: 2000
                    });

                    $("#span" + precioc).html(costo);
                    $("#loading" + precioc).hide();
                    $("#btnCosto" + precioc).show();
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: data.msg,
                        showConfirmButton: true,
                        timer: 2000
                    });

                    $("#loading" + precioc).hide();
                    $("#btnCosto" + precioc).show();
                }
            },
            error: function(HXR, error) {
                $("#loading" + precioc).hide();
                $("#btnCosto" + precioc).show();
            }
        });
    }
}

function cambiarEstado(estado, producto) {
    url = '<?php echo base_url(); ?>index.php/almacen/producto/cambiarEstado/';
    $.ajax({
        url: url,
        type: "POST",
        data: {
            estado: Number(estado),
            cod_producto: producto
        },
        dataType: "json",
        beforeSend: function(data) {
            $('#cargando_datos').show();
        },
        success: function(data) {
            if (data.cambio == true || data.cambio == 'true') {
                $('#cargando_datos').hide();
                alert('Cambio de estado correctamente!');
                window.location = "<?php echo base_url(); ?>index.php/almacen/producto/productos/B";
            } else {
                $('#cargando_datos').hide();
                alert('Ah Ocurrido un error con el cambio de estado!');
            }
        },
        error: function(data) {
            $('#cargando_datos').hide();
            console.log('Error en cambio de fase');
        }
    });
}

function clean() {
    $("#form_nvo")[0].reset();
    $("#id").val("");
    $(".contadorCaracteres").html("800");

    $("#nvo_codigo").removeAttr("readOnly");
}

function postForm(path, params, method) {
        method = method || 'post';

        var form = document.createElement('form');
        form.setAttribute('method', method);
        form.setAttribute('action', path);

        for (var key in params) {
            if (params.hasOwnProperty(key)) {
                var hiddenField = document.createElement('input');
                hiddenField.setAttribute('type', 'hidden');
                hiddenField.setAttribute('name', key);
                hiddenField.setAttribute('value', params[key]);

                form.appendChild(hiddenField);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }

    function postFormDescarga(path, params, method) {
        method = method || 'post';

        var form = document.createElement('form');
        form.setAttribute('method', method);
        form.setAttribute('action', path);

        for (var key in params) {
            if (params.hasOwnProperty(key)) {
                var hiddenField = document.createElement('input');
                hiddenField.setAttribute('type', 'hidden');
                hiddenField.setAttribute('name', key);
                hiddenField.setAttribute('value', params[key]);

                form.appendChild(hiddenField);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }
</script>
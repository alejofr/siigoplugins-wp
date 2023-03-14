<?php 

    $jobs_pedidos = [];
    $tipo = 'pedidos';
    $mensaje = "";

    if(ejecutarSQL::check_table('sg_pedidos_jobs')){
        $consultas = ejecutarSQL::consultar("select * from sg_pedidos_jobs");
        $jobs_pedidos = mysqli_fetch_all($consultas);
    }else{
        ejecutarSQL::create_sg_pedidos_jobs_table();
    }

    if ( isset($_POST['fecha_c']) && isset($_POST['comprobante']) && isset($_POST['code_comprobante']) && isset($_POST['iva_id']) && isset($_POST['pago_id']) && isset($_POST['pago_due_date']) && isset($_POST['vendedor_id']) ) {

        if ( $_POST['fecha_c'] != '' && $_POST['comprobante'] != '' && $_POST['code_comprobante'] != '' && $_POST['iva_id'] != ''  && $_POST['pago_id'] != ''  && $_POST['pago_due_date'] != '' && $_POST['vendedor_id'] != '' ){


            $tipos_documentos = RequestApi::request('GET', 'https://api.siigo.com/v1/document-types?type=FV', true, $usuario['access_token'], "");
            $aux = '';

            for ($i=0; $i < count($tipos_documentos) ; $i++) { 
                if( $tipos_documentos[$i]->active == 1 && $tipos_documentos[$i]->code == $_POST['code_comprobante'] &&  ( $tipos_documentos[$i]->description == $_POST['comprobante'] || $tipos_documentos[$i]->name == $_POST['comprobante'] )  ){
                    $aux = $tipos_documentos[$i]->id;
                    break;
                }
            }
            //Factura electrónica de venta página web
            if( $aux != '' ){
             
                $consultar = ejecutarSQL::consultar("select * from sg_configuracion_jobs where tipo='".$tipo."'");
                $fecha = $Object->format("Y-m-d h:i:s a");

                $setting['fecha_c'] = $_POST['fecha_c'];
                $setting['document_id'] = $aux;
                $setting['comprobante'] = $_POST['comprobante'];
                $setting['code_comprobante'] = $_POST['code_comprobante'];
                $setting['iva_id'] = $_POST['iva_id'];
                $setting['pago_id'] = $_POST['pago_id'];
                $setting['pago_due_date'] = $_POST['pago_due_date'];
                $setting['vendedor_id'] = $_POST['vendedor_id'];
                $setting = json_encode($setting);
        
                if (mysqli_num_rows($consultar) != 0){
                    consultasSQL::UpdateSQL('sg_configuracion_jobs', "
                        setting = '".$setting."',
                        fecha_editado = '".$fecha."'
                    ", 'tipo="pedidos"');
        
                    $mensaje = "Configuacion Actualizada";
                }else{ 
                    consultasSQL::InsertSQL('sg_configuracion_jobs', 'tipo,	setting,fecha_creado,fecha_editado', "
                        '$tipo',
                        '$setting',
                        '$fecha',
                        '$fecha'
                    ");
        
                    $mensaje = "Configuacion agregada";
                    
                }
                
                $class = 'updated';

            }else{
                $mensaje = "El nombre o descripciíon del comprobante: ".$_POST['comprobante']." con el codigo: ".$_POST['code_comprobante'];
                $class = 'error';
            } 

        }else{
            $mensaje = "Debe completar el formulario";
            $class = 'error';
        }
    }

    $consultar = ejecutarSQL::consultar("select * from sg_configuracion_jobs where tipo='".$tipo."'");


    if (mysqli_num_rows($consultar) != 0){
        $configuracion = mysqli_fetch_array($consultar);
        $aux = "";
        foreach ($configuracion as $key => $value) {
            if ( $key == 'setting' ){
                $aux = json_decode($configuracion[$key]);
            }
        }
        $configuracion = $aux;
    }

    $usuario = auth::user();

    $impuestos = RequestApi::request('GET', 'https://api.siigo.com/v1/taxes', true, $usuario['access_token'], "");
    $formas_pagos = RequestApi::request('GET', 'https://api.siigo.com/v1/payment-types?document_type=FV', true, $usuario['access_token'], "");
    $vendedores = RequestApi::request('GET', 'https://api.siigo.com/v1/users', true, $usuario['access_token'], "");
    print_r($vendedores);

?>

<div class="table100 ver1 m-b-110">
    <div class="table100-head">
        <table>
            <thead>
                <tr class="row100 head">
                    <th class="cell100 column1">Orden Id</th>
                    <th class="cell100 column2">Cliente Identidad</th>
                    <th class="cell100 column3">Total</th>
                    <th class="cell100 column4">Fecha</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="table100-body js-pscroll">
        <table>
            <tbody>
            <?php
                if( count($jobs_pedidos) != 0 ){ 
                    for ($i=0; $i < count($jobs_pedidos); $i++) {  
                        $ident = json_decode($jobs_pedidos[$i][2]);
                             
                ?>
                    <tr class="row100 body">
                        <td class="cell100 column1"><?php  echo $jobs_pedidos[$i][0]; ?></td>
                        <td class="cell100 column2"><?php  echo $ident->identification ?></td>
                        <td class="cell100 column3"><?php  echo $jobs_pedidos[$i][4]; ?></td>
                        <td class="cell100 column4"><?php  echo $jobs_pedidos[$i][5] ?></td>
                    </tr>
                <?php 
                    }//for
                }//cierre if
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php  if( $mensaje != "" ) { ?>
        <div id="message"  class="<?php echo $class; ?> notice is-dismissible" style="margin: 15px 0 15px 0;">
            <p><?php echo $mensaje; ?></p>
            <button type="button" class="notice-dismiss close_div_message" onclick="closeMensaje(event)">
                <span class="screen-reader-text"> Descartar Este Aviso </span>
            </button>
        </div>
        <?php } ?>
<div property="content" typeof="Item"style="grid-template-columns: 1.5fr 2fr;">
    <div>
        <h3 property="headline" aria-label="Headline" class="">Configuración Pedidos</h3>
        <p property="text" aria-label="Text" class="">
             Es Importante agregar el parametro de <strong> fecha de aranque </strong>.
             <br><br>
            <strong>Advertencia</strong> por favor agregar el tipo de IVA, tipo de comprobante, la forma de pago y el vendedor
        </p>
    </div>
    <div>
        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" class="__form_sg_api" method="POST" style="max-width: 380px;margin:0 auto;" >
            <div class="group_input">
                <label for="one" class="form-label">Fecha de Comienzo.</label>
                <input type="date" class="form-control input_style_0" name="fecha_c" <?php if( isset($configuracion) && $configuracion->fecha_c != '' ) { ?> disabled value="<?php echo $configuracion->fecha_c; ?>" <?php } ?> id="one" >
            </div>
            <div class="group_input">
                <label for="dos" class="form-label">Nombre o descripcion del comprobante</label>
                <input type="text" class="form-control input_style_0" <?php if (isset($configuracion) && $configuracion->comprobante != '' ) { ?> value="<?php echo $configuracion->comprobante; ?>" <?php } ?> name="comprobante" id="dos" >
            </div>
            <div class="group_input">
                <label for="dos" class="form-label">Código comprobante</label>
                <input type="text" class="form-control input_style_0" <?php if (isset($configuracion) && $configuracion->code_comprobante != '' ) { ?> value="<?php echo $configuracion->code_comprobante; ?>" <?php } ?> name="code_comprobante" id="dos" >
            </div>
            <div class="group_input">
                <label for="tres" class="form-label">selecciona el IVA</label>
                <select name="iva_id" id="tres" class="form-control input_style_0">
                    <?php 
                        for ($i=0; $i < count($impuestos) ; $i++) { 
                            if( $impuestos[$i]->active == 1 ){
                    ?>
                        <option value="<?php echo $impuestos[$i]->id;  ?>" <?php if (isset($configuracion) && $configuracion->iva_id == $impuestos[$i]->id ) { ?> selected <?php } ?> ><?php echo $impuestos[$i]->name.' - %'.$impuestos[$i]->percentage; ?></option>
                    <?php
                            }//cierre if
                        }//cierre for
                    ?>
                </select>
            </div>
            <div class="group_input">
                <label for="cinco" class="form-label">Selecciona la forma de pago</label>
                <select name="pago_id" id="tres" class="form-control input_style_0"  onchange="changeSelectPago(event)">
                    <?php 
                        for ($i=0; $i < count($formas_pagos) ; $i++) { 
                            if( $formas_pagos[$i]->active == 1 ){
                    ?>
                        <option <?php if (isset($configuracion) && $configuracion->pago_id == $formas_pagos[$i]->id ) { ?> selected <?php } ?> value="<?php echo $formas_pagos[$i]->id; ?>"><?php echo $formas_pagos[$i]->name.' - tipo: '.$formas_pagos[$i]->type; ?></option>
                    <?php
                            }//cierre if
                        }//cierre for
                    ?>
                </select>
            </div>
            <div class="group_input">
                <input type="hidden" id="pago_due_date" name="pago_due_date" <?php if (isset($configuracion) && $configuracion->pago_due_date != '' ) { ?> value="<?php echo $configuracion->pago_due_date; ?>" <?php }else{ ?> value="<?php echo $formas_pagos[0]->due_date; ?>" <?php } ?>>
            </div>
            <div class="group_input">
                <label for="seis" class="form-label">Selecciona el vendedor para la factura</label>
                <select name="vendedor_id" id="seis" class="form-control input_style_0">
                    <?php 
                    $vendedores = $vendedores->results;
                        for ($i=0; $i < count($vendedores) ; $i++) { 
                            if( $vendedores[$i]->active == 1 ){
                    ?>
                        <option  <?php if (isset($configuracion) && $configuracion->vendedor_id == $vendedores[$i]->id ) { ?> selected <?php } ?> value="<?php echo $vendedores[$i]->id; ?>"><?php echo $vendedores[$i]->first_name.' - '.$vendedores[$i]->last_name; ?></option>
                    <?php
                            }//cierre if
                        }//cierre for
                    ?>
                </select>
            </div>
            <button type="submit" class="btn-submit-table" style="margin-top: 10px;cursor:pointer;">
                <span property="destination" aria-label="Destination" class="">Guardar</span>
            </button>
        </form>
    </div>
</div>

<script>
    const formas_pagos = <?php  echo json_encode($formas_pagos); ?>;

    function changeSelectPago(e){
        let element = document.getElementById('pago_due_date');
        let id_pago = e.target.value;

        for (let i = 0; i < formas_pagos.length; i++) {
            if( formas_pagos[i].id == id_pago && formas_pagos[i].active == 1 ){
                element.value = ( formas_pagos[i].due_date ) ? 1 : 0;
                break;
            }
            
        }
    }
</script>
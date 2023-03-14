<?php 

    $mensaje = "";
    $jobs_clientes = [];
    $tipo = 'clientes';
    $configuracion = [];

    if(ejecutarSQL::check_table('sg_clientes_jobs')){
        $consultas = ejecutarSQL::consultar("select * from sg_clientes_jobs");
        $jobs_clientes = mysqli_fetch_all($consultas);
    }else{
        ejecutarSQL::create_sg_clientes_jobs_table();
    }

    if ( isset($_POST['atribute_type_doc']) && isset($_POST['atributte_doc'])  &&  isset($_POST['atributte_address']) 
        && isset($_POST['atributte_number']) && $_POST['atribute_type_doc'] != '' ) {

        if ( $_POST['atributte_doc'] != ''
        && $_POST['atributte_address'] != '' && $_POST['atributte_number'] != '' ){

            $consultar = ejecutarSQL::consultar("select * from sg_configuracion_jobs where tipo='".$tipo."'");
            $fecha = $Object->format("Y-m-d h:i:s a");
    
            $setting['atribute_type_doc'] = $_POST['atribute_type_doc'];
            $setting['atributte_doc'] = $_POST['atributte_doc'];
            $setting['atributte_address'] = $_POST['atributte_address'];
            $setting['atributte_number'] = $_POST['atributte_number'];
            $setting = json_encode($setting);
    
    
            if (mysqli_num_rows($consultar) != 0){
                consultasSQL::UpdateSQL('sg_configuracion_jobs', "
                    setting = '".$setting."',
                    fecha_editado = '".$fecha."'
                ", 'tipo="clientes"');
    
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
?>
<div class="table100 ver1 m-b-110">
    <div class="table100-head">
        <table>
            <thead>
                <tr class="row100 head">
                    <th class="cell100 column2">Tipo de identificación</th>
                    <th class="cell100 column4">Nombre</th>
                    <th class="cell100 column2">Telefono</th>
                    <th class="cell100 column3">Dirección</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="table100-body js-pscroll">
        <table>
            <tbody>
                 <?php
                if( count($jobs_clientes) != 0 ){ 
                    for ($i=0; $i < count($jobs_clientes); $i++) {  
                        $dir = json_decode($jobs_clientes[$i][7]);
                        $contact = json_decode($jobs_clientes[$i][9]);
                        $tlf = json_decode($jobs_clientes[$i][8]);
                             
                ?>
                    <tr class="row100 body">
                        <td class="cell100 column2"><?php  echo $jobs_clientes[$i][5]; ?></td>
                         <td class="cell100 column4"><?php  echo $contact[0]->first_name.', '.$contact[0]->last_name; ?></td>
                        <td class="cell100 column2"><?php  echo $tlf[0]->number; ?></td>
                        <td class="cell100 column3"><?php  echo $dir->address; ?></td>
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
        <h3 property="headline" aria-label="Headline" class="">Configuración Clientes</h3>
        <p property="text" aria-label="Text" class="">
            Es Importante agregar estos paramentros para guardar los clientes a Siigo para la gestión exitosa de los pedidos de tu tienda online.
            <br><br>
            <strong>Advertencia</strong> por favor agregar el nombre o atributo de la (<strong>dirección, identificacion, número telefónico</strong>) de cliente de manera correcta.Este Nombre o Atributo se declara en el formulario de pago de WooCommerce
            <br><br>
            <strong>Advertencia</strong> por favor agregar el tipo de documentación de los clientes de tu sitio web. 
        </p>
    </div>
    <div>
        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" class="__form_sg_api" method="POST" style="max-width: 380px;margin:0 auto;" >
            <div class="group_input" style="margin-bottom: 10px;">
                <label for="one" class="form-label"style="margin-bottom: 10px;">Seleciona el tipo de documento de cliente.</label>
               
                <select name="atribute_type_doc" id="one">
                    <option value="13" <?php if( isset($configuracion) && $configuracion->atribute_type_doc == '13' ) { ?> selected <?php } ?> >Cédula de ciudadanía</option>
                    <option value="31" <?php if( isset($configuracion) && $configuracion->atribute_type_doc == '31' ) { ?> selected <?php } ?> >NIT</option>
                    <option value="22" <?php if( isset($configuracion) && $configuracion->atribute_type_doc == '22' ) { ?> selected <?php } ?> >Cédula de extranjería</option>
                    <option value="41" <?php if( isset($configuracion) && $configuracion->atribute_type_doc == '41' ) { ?> selected <?php } ?> >Pasaporte</option>
                    <option value="47" <?php if( isset($configuracion) && $configuracion->atribute_type_doc == '47' ) { ?> selected <?php } ?>>Permiso especial de permanencia PEP</option>
                </select>
            </div>
            <div class="group_input">
                <label for="one" class="form-label">Nombre de identificación del formulario.</label>
                <input type="text" class="form-control input_style_0" name="atributte_doc" <?php if( isset($configuracion) ) { ?> value="<?php echo $configuracion->atributte_doc ?>" <?php } ?> id="one" placeholder="ejem: billing_cedula">
            </div>
            <div class="group_input">
                <label for="one" class="form-label">Nombre dirección del formulario.</label>
                <input type="text" class="form-control input_style_0" name="atributte_address" <?php if( isset($configuracion) ) { ?> value="<?php echo $configuracion->atributte_address ?>" <?php } ?> id="one" placeholder="ejem: billing_addres">
            </div>
            <div class="group_input">
                <label for="one" class="form-label">Nombre de número telefónico del formulario.</label>
                <input type="text" class="form-control input_style_0" name="atributte_number" <?php if( isset($configuracion) ) { ?> value="<?php echo $configuracion->atributte_number ?>" <?php } ?> id="one" placeholder="ejem: billing_phone">
            </div>
            <button type="submit" class="btn-submit-table" style="margin-top: 10px;cursor:pointer;">
                <span property="destination" aria-label="Destination" class="">Guardar</span>
            </button>
        </form>
    </div>
</div>
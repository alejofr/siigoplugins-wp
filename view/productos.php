<?php 
    $mensaje = "";
    $jobs_productos = [];
    $tipo = 'producto';
    $configuracion = [];
    

 

    if(ejecutarSQL::check_table('sg_productos_jobs')){
        $consultas = ejecutarSQL::consultar("select * from sg_productos_jobs");
        $jobs_productos = mysqli_fetch_all($consultas);
    }else{
        ejecutarSQL::create_sg_productos_jobs_table();
    }

    if (isset($_POST['precio']) ||  isset($_POST['stock'])) {
        $consultar = ejecutarSQL::consultar("select * from sg_configuracion_jobs where tipo='".$tipo."'");
        $fecha = $Object->format("Y-m-d h:i:s a");
        $setting['min_price'] = ( isset($_POST['precio']) ) ? "SI" : "NO";
        $setting['max_price'] = ( isset($_POST['precio']) ) ? "SI" : "NO";
        $setting['stock_quantity'] = (isset($_POST['stock']) ) ? "SI" : "NO";
        $setting = json_encode($setting);

        

        if (mysqli_num_rows($consultar) != 0){
            consultasSQL::UpdateSQL('sg_configuracion_jobs', "
                setting = '".$setting."',
                fecha_editado = '".$fecha."'
            ", 'tipo="producto"');

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

    }
?>
<div class="table100 ver1 m-b-110">
    <div class="table100-head">
        <table>
            <thead>
                <tr class="row100 head">
                    <th class="cell100 column1">ID Producto</th>
                    <th class="cell100 column2">Sku</th>
                    <th class="cell100 column3">Campos Actualizado</th>
                    <th class="cell100 column4">Fecha</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="table100-body js-pscroll">
        <table>
            <tbody>
                <?php
                    if( count($jobs_productos) != 0 ){ 
                      for ($i=0; $i < count($jobs_productos); $i++) {  
                          $setting = json_decode($jobs_productos[$i][3]);
                             
                ?>
                    <tr class="row100 body">
                        <td class="cell100 column1"><?php  echo $jobs_productos[$i][1]; ?></td>
                        <td class="cell100 column2"><?php  echo $jobs_productos[$i][2];; ?></td>
                        <td class="cell100 column3">
                            <?php  
                                echo ($setting->min_price != 'NO')  ? "Precio" : "";
                                echo ($setting->min_price != 'NO' && $setting->stock_quantity  != 'NO') ? " y ": "";
                                echo ($setting->stock_quantity  != 'NO')  ? "Stock" : "";  
                            ?>
                        </td>
                        <td class="cell100 column4"><?php  echo $jobs_productos[$i][5]; ?></td>
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
        <h3 property="headline" aria-label="Headline" class="">Configuración Productos</h3>
        <p property="text" aria-label="Text" class="">
            Definir los campos para actualizar los productos y que asi tenga relación de con su base de datos de Siigo.
        </p>
    </div>
    <div>
        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST" style="text-align: center;">
            <div class="row justify-content-center">
                <div class="col-md-4" style="margin-bottom: 40px;">
                    <div class="wrap w-100">
                        <div class="heading-title mb-4 text-center">
                            <h3>Selecciona los campos</h3>
                        </div>
                        <ul class="ks-cboxtags p-0 m-0">
                        <?php 
                            $consultar = ejecutarSQL::consultar("select * from sg_configuracion_jobs where tipo='".$tipo."'");
                            $precio_check = '';
                            $stock_check = '';

                            if (mysqli_num_rows($consultar) != 0){
                                $configuracion = mysqli_fetch_array($consultar);
                                $aux = "";
                                foreach ($configuracion as $key => $value) {
                                    if ( $key == 'setting' ){
                                        $aux = json_decode($configuracion[$key]);
                                    }
                                }
                                $configuracion = $aux;
                                if( $configuracion->min_price != 'NO'){
                                    $precio_check = 'checked';
                                }
                                if( $configuracion->stock_quantity != 'NO' ){
                                    $stock_check = 'checked';
                                }
                            }
                               
                           
                        ?>
                        <li>
                            <input type="checkbox" id="checkboxOne" name="precio" value="precio" <?php echo $precio_check; ?>>
                            <label for="checkboxOne">Precio</label>
                        </li>
                        <li>
                            <input type="checkbox" id="checkboxTwo" name="stock" value="stock" <?php echo $stock_check; ?>>
                            <label for="checkboxTwo">Stock</label>
                        </li>
                        </ul>
                    </div>
                </div>
                <button type="submit" class="btn-submit-table" style="margin: 0 auto;cursor:pointer;">
                    <span property="destination" aria-label="Destination" class="">Guardar</span>
                </button>
            </div>
        </form>
    </div>
</div>


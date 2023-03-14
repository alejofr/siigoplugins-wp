<?php 

    if( isset($_POST['usuario']) && isset($_POST['clave']) ){
       if(!auth::update($_POST['usuario'],$_POST['clave'], $Object)){
         header('Location: '.$_SERVER['REQUEST_URI']);
       }
    }
    
    $usuario = auth::user();

?>

<div property="content" typeof="Item"style="grid-template-columns: 1.5fr 2fr;">
    <div>
        <h3 property="headline" aria-label="Headline" class="">Configuración General</h3>
        <p property="text" aria-label="Text" class="">
            Para desplegar los modulos y ejecutarlo en automatico deberá ejecutar desde su servidor Cpanel, el <strong>Cron Jobs</strong>.
            Allí podrá definir la ejecución de nuestras funciones. En la apartado <strong>Comando</strong> debe ingresar esta ruta.
            <strong> PHP <?php echo SIIGO_RUTA.'jobs/jobs.php' ?> </strong>
        </p>
    </div>
    <div>
            <form  action="<?php $_SERVER['REQUEST_URI'] ?>" name="formApiSiigo" class="__form_sg_api" method="POST" style="max-width: 380px;margin:0 auto;">
                <div class="group_input">
                    <label for="username" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control input_style_0" name="usuario" value="<?php echo $usuario['username'] ?>" id="usuario" placeholder="Direccion de Correo Electronico" onblur="formInput(event)">
                </div>
                <div class="group_input">
                    <label for="apikey" class="form-label">Api Key</label>
                    <input type="text" class="form-control input_style_0" name="clave" value="<?php echo $usuario['api_key'] ?>" id="apikey" placeholder="Api key" onblur="formInput(event)">
                </div>
                <div class="group_input mt-35">
                    <button type="submit" id="btnSubmit" class="btn_style_0">Actualizar</button>
                </div>
            </form>
    </div>
</div>
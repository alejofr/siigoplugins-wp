<div class="container">
    <div class="sg_table_form">
        <?php  if( $message != "" ) { ?>
        <div id="message"  class="<?php echo $class; ?> notice is-dismissible" style="margin: 15px 0 15px 0;">
            <p><?php echo $message; ?></p>
            <button type="button" class="notice-dismiss close_div_message">
                <span class="screen-reader-text"> Descartar Este Aviso </span>
            </button>
        </div>
        <?php } ?>
        <div class="sg_card _card_form">
            <div class="__card_head">
                <h1>Formulario Credenciales API Siigo</h1>
                <div class="__div_nota">
                    <p>Nota: Deberas Ingresar las Credenciales de la API de Siigo, para poder disfrutar de los modulos</p>
                </div>
            </div>
            <form  action="<?php $_SERVER['REQUEST_URI'] ?>" name="formApiSiigo" class="__form_sg_api" method="POST">
                <div class="group_input">
                    <label for="username" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control input_style_0" name="email" id="username" placeholder="Direccion de Correo Electronico" onblur="formInput(event)">
                </div>
                <div class="group_input">
                    <label for="apikey" class="form-label">Api Key</label>
                    <input type="text" class="form-control input_style_0" name="key" id="apikey" placeholder="Api key" onblur="formInput(event)">
                </div>
                <div class="group_input mt-35">
                    <button type="button" id="btnSubmit" class="btn_style_0 btnDisabled">Comprobar Credenciales</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
.container{

    }

    .sg_table_form{
        width: 480px;
        padding: 8% 0 0;
        margin: auto;
    }

    .sg_card {
        position: relative;
        z-index: 1;
        background: #FFFFFF;
        max-width: 480px;
        padding: 30px;
    }

    .__form_sg_api{
        margin-top: 20px;
    }

    .__form_sg_api .group_input{
        width: 100%;
    }

    .form-label {
        margin-bottom: 0.5rem;
        font-size: 12px;
    }

    .form-control {
        margin-top: 8px !important;
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.6;
        color: #212529;
        background-color: #f8fafc;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .__form_sg_api .input_style_0 {
        background: #f2f2f2;
        width: 100%;
        border: 0;
        margin: 0 0 15px;
        padding: 0 15px;
        box-sizing: border-box;
        font-size: 14px;
        min-height: 46px;
        border-radius: 0;
    }

    .__form_sg_api .btn_style_0 {
        font-family: "Poppins", sans-serif;
        text-transform: uppercase;
        background: #FE6700;
        width: 100%;
        border: 0;
        padding: 0 15px;
        min-height: 46px;
        color: #FFFFFF;
        font-size: 14px;
        -webkit-transition: all 0.3 ease;
        transition: all 0.3 ease;
        cursor: pointer;
    }

    .__form_sg_api .btn_style_0.btnDisabled, .__form_sg_api .btn_style_0.btnDisabled:hover {
        background-color: #8c8c8c;
    }

    .mt-35{
        margin-top: 35px;
    }

</style>
<script>
    if( document.querySelector('.close_div_message') ){
        let  messageDIV = document.querySelector('.close_div_message'); 
        messageDIV.addEventListener('click', function(e){
            console.log(e);
            e.target.parentElement.style.display = 'none';
        });
    }
 
   let input = null; 
    //Validacion formulario
    function formInput(e){
        if( input == null )
            input = e.target;

        if ( input.id != e.target.id ){
            if ( input.value != "" && e.target.value != "" ){
                document.querySelector('#btnSubmit').setAttribute('type', 'submit');
                document.querySelector('#btnSubmit').classList.remove('btnDisabled');
            }else{
                document.querySelector('#btnSubmit').setAttribute('type', 'button');
                document.querySelector('#btnSubmit').classList.add('btnDisabled');
            }
        }
    }

</script>
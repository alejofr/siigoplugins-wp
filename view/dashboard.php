<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  
<main mv-app="tabs" mv-bar="no-login" mv-storage="https://github.com/DmitrySharabin/mavo-tabs-widget" typeof="Item" mv-permissions="read login" style="" class="mv-unsaved-changes"><div class="mv-bar mv-ui"><!--mv-status--><!--mv-edit--><!--mv-save--><!--mv-logout--></div>
	<h2 property="headline" aria-label="Headline" class="">Tablero de Tareas Ejecutadas</h2>
	<section class="tabs" style="--count: 5;">
		<details property="tab" mv-multiple="" mv-action="set(open.$all, false), set(open, true)" typeof="Item" 	<?php if ((isset($_GET['menu']) && $_GET['menu'] == 'inicio') || (!isset($_GET['menu']) ) ){ ?> open="" <?php } ?> >
			<meta property="open" aria-label="Open" class="" datatype="boolean" content="true">
			
			<summary property="title" typeof="Item">
        <a href="<?php echo $_SERVER['REQUEST_URI'].'&menu=inicio'; ?>" class="a-dt">
          <img class="icon" src="<?php echo wp_guess_url(); ?>/wp-content/plugins/siigoplugins/assets/images/inicio.svg" alt="Icon">
          <span property="text" aria-label="Text" class="">Iníco</span>
        </a>
      </summary>
			<?php if ( (isset($_GET['menu']) && $_GET['menu'] == 'inicio') || (!isset($_GET['menu']) ) ){ 
        require_once SIIGO_RUTA.'/view/inicio.php'; 
      }//cierre menu inicio ?>		
		</details>
    <details property="tab" mv-multiple="" mv-action="set(open.$all, false), set(open, true)" typeof="Item" <?php if ( isset($_GET['menu']) && $_GET['menu'] == 'productos' ) {?> open="" <?php } ?> >
			<meta property="open" aria-label="Open" class="mv-empty" datatype="boolean">
			
			<summary  property="title" typeof="">
        <a href="<?php echo $_SERVER['REQUEST_URI'].'&menu=productos'; ?>" class="a-dt">
          <img class="icon" src="<?php echo wp_guess_url(); ?>/wp-content/plugins/siigoplugins/assets/images/productos.svg" alt="Icon">
          <span property="text" aria-label="Text" class="">Productos</span>
        </a>
      </summary>
			<section property="content" typeof="Item" style="grid-template-columns: 1fr;display:block;">
        <?php if ( isset($_GET['menu']) && $_GET['menu'] == 'productos'){ 
          require_once SIIGO_RUTA.'/view/productos.php'; 
        }//cierre menu productos ?>	
      </section>	
		</details>
    <details property="tab" mv-multiple="" mv-action="set(open.$all, false), set(open, true)" typeof="Item" <?php if ( isset($_GET['menu']) && $_GET['menu'] == 'pedidos' ) {?> open="" <?php } ?>>
			<meta property="open" aria-label="Open" class="mv-empty" datatype="boolean">
			
			<summary property="title" typeof="">
        <a href="<?php echo $_SERVER['REQUEST_URI'].'&menu=pedidos'; ?>" class="a-dt">
          <img class="icon" src="<?php echo wp_guess_url(); ?>/wp-content/plugins/siigoplugins/assets/images/movimientos.svg" alt="Icon">
          <span property="text" aria-label="Text" class="">Pedidos</span>
        </a>
			</summary>
			
			<section property="content" typeof="" style="grid-template-columns: 1fr;display:block;">
        <?php if ( isset($_GET['menu']) && $_GET['menu'] == 'pedidos'){ 
            require_once SIIGO_RUTA.'/view/pedidos.php'; 
          }//cierre menu productos ?>	
			</section>		
		</details>
    <details property="tab" mv-multiple="" mv-action="set(open.$all, false), set(open, true)" typeof="Item" <?php if ( isset($_GET['menu']) && $_GET['menu'] == 'clientes' ) {?> open="" <?php } ?>>
			<meta property="open" aria-label="Open" class="mv-empty" datatype="boolean">
			
			<summary property="title" typeof="">
        <a href="<?php echo $_SERVER['REQUEST_URI'].'&menu=clientes'; ?>" class="a-dt">
          <img class="icon" src="<?php echo wp_guess_url(); ?>/wp-content/plugins/siigoplugins/assets/images/clientes.svg" alt="Icon">
          <span property="text" aria-label="Text" class="">Clientes</span>
        </a>
			</summary>
			
			<section property="content" typeof=""  style="grid-template-columns: 1fr;display:block;">
        <?php if ( isset($_GET['menu']) && $_GET['menu'] == 'clientes'){ 
              require_once SIIGO_RUTA.'/view/clientes.php'; 
            }//cierre menu productos ?>
			</section>		
		</details>
    <details property="tab" mv-multiple="" mv-action="set(open.$all, false), set(open, true)" typeof="Item" <?php if ( isset($_GET['menu']) && $_GET['menu'] == 'configuracion' ) {?> open="" <?php } ?>>
			<meta property="open" aria-label="Open" class="mv-empty" datatype="boolean">
			
			<summary property="title" typeof="">
        <a href="<?php echo $_SERVER['REQUEST_URI'].'&menu=configuracion'; ?>" class="a-dt">
          <img class="icon" src="<?php echo wp_guess_url(); ?>/wp-content/plugins/siigoplugins/assets/images/ajustes.svg" alt="Icon">
          <span property="text" aria-label="Text" class="">Configuración</span>
        </a>
			</summary>
			
			<section property="content" typeof="" style="grid-template-columns: 1fr;display:block;">
        <?php if ( isset($_GET['menu']) && $_GET['menu'] == 'configuracion'){ 
              require_once SIIGO_RUTA.'/view/configuracion.php'; 
            }//cierre menu productos ?>
			</section>		
		</details>
	</section>
</main>

<style>
:root {
  --main-color: hsl(232, 47%, 56%);
  --main-color-accent: hsl(230, 58%, 30%);
  --text-color: hsl(208, 13%, 45%);
  --text-color-accent: hsl(235, 26%, 35%);
  --background-color: hsl(220, 38%, 97%);
  --line-color: hsl(249, 20%, 86%);
  --max-width: 60rem;
  --min-width: 45rem;
}

*, *::before, *::after {
  box-sizing: border-box;
}

body > p {
  margin: 1em 2em;
  text-align: center;
}
#wpcontent{
    padding-right: 20px;
}
[mv-app] {
  margin: 50px 30px;
  min-width: var(--min-width);
  max-width: 90vw;
  background-color: white;
  border-radius: 0.5em;
}

[mv-app] > [property=headline] {
  margin: 0;
  padding: 1.1em 1em;
  text-align: center;
  color: var(--text-color-accent);
  border-bottom: 1px solid var(--line-color);
}

.tabs {
  display: grid;
  grid-template-columns: repeat(var(--count, 5), minmax(8em, 1fr));
  grid-template-rows: auto auto;
  padding: 0 30px 20px 30px;
}

details[property=tab] {
  display: contents;
}
details[property=tab][open] [property=content] {
  grid-column: 1/-1;
  width: auto;
}
details[property=tab]:not([open]) [property=title] {
  color: #8c90ab;
  background-color: #f5f7fa;
  border-bottom: 1px solid var(--line-color);
}

details[property=tab]:not([open]) [property=title] a {
  color: #8c90ab;
  background-color: #f5f7fa;
}

details[property=tab]:not([open]) [property=title] .icon {
  filter: grayscale(85%) opacity(45%);
}
details[property=tab]:not([open]) [property=content] {
  display: none;
}
details[property=tab]:first-child [property=title] {
  border-left: none;
}

[property=title] {
  grid-row: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  border-left: 1px solid var(--line-color);
  color: var(--main-color);
  text-align: center;
  list-style: none;
}
[property=title] a{
  color: var(--main-color);
}
[property=title]::-webkit-details-marker {
  display: none;
}
[property=title] .icon {
  max-height: 5em;
  margin-left: auto;
  margin-right: auto;
  margin-bottom: 1em;
}
[property=title]:focus {
  outline: none;
}

[property=content] {
  grid-row: 2;
  padding: 3em 1em;
  display: grid;
  grid-template-columns: 1.5fr 2fr;
  grid-column-gap: 1.2em;
  grid-template-areas:
		"image headline"
		"image text"
		"image link";
}
[property=content] [property=image] {
  grid-area: image;
  max-width: 70%;
  max-height: 22em;
  margin: auto;
}
[property=content] [property=headline] {
  grid-area: headline;
  margin-bottom: 0.5em;
  color: var(--main-color);
  font-size: 170%;
}
[property=content] [property=headline]:not([mv-mode=edit]) {
  -webkit-background-clip: text;
          background-clip: text;
  background-image: linear-gradient(to right, var(--main-color), var(--main-color-accent));
}
[property=content] [property=text] {
  grid-area: text;
  line-height: 1.5em;
}
[property=content] [property=url], .btn-submit-table {
  grid-area: link;
  align-self: center;
  display: block;
  width: -webkit-max-content;
  width: -moz-max-content;
  width: max-content;
  margin-top: 1.5em;
  padding: 0.7em 2em;
  color: white;
  background-image: linear-gradient(135deg, var(--main-color), var(--main-color-accent));
  border-radius: 999px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  font-size: 0.8em;
  text-decoration: none;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}
[property=content] [property=url]:hover {
  filter: brightness(135%);
}

[mv-app][mv-mode=edit] [property=open] {
  display: none;
}

footer {
  margin-top: 3em;
  text-align: center;
}
footer a {
  color: var(--main-color);
}

::-moz-focus-inner {
  border: 0;
}

.a-dt{
    text-decoration: none;
    padding: 1em;
    width: 100%;
    height: 100%;
    text-align: center;
    grid-row: 1;
    padding: 1em;
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>

</body>
</html>
<?php
//Aquí pondremos las distintas partes de contenido html que podremos reusar
//como $titulo,$cabecera,$menu dependiendo del tipo de usuario

//Título del la aplicacion web para la el head
$titulo="Aplicación web de anuncios por palabras";
$rutaScript=RUTA_APP."/js/script.js";

//Encabezado de la sección header
$cabecera="Anuncios";
//lo que pondremos dentro del la barra de navegacion nav
$menu="
<li><a href='".RUTA_APP."/'>Inicio</a></li>
<li><a href='".RUTA_APP."/?route=login'>Login</a></li>
<li><a href='".RUTA_APP."/?route=registro'>Registro</a></li>
";
//lo que pondremos dentro de la sección que representará al usuario o su gestión.


$pie="@mvaronc-DWES-2026 Aplicación web de anucios por palabras";
?>

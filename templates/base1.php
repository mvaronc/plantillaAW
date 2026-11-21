<?php 

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo RUTA_APP ??''; ?>/css/styles.css">
    <title><?php  echo $titulo?? ''; ?></title>
    <script src=<?= $rutaScript ?? '' ?>></script>
</head>
<body>
    <header>
        <h1><?php echo  $cabecera?? ''; ?></h1>
    <nav><!--Barra de menu y enlaces-->
        <ul>
            <?php echo $menu?? ''; ?>
        </ul>
    </nav>
    </header>
    <main>
        <!--Contenido en secciones  <section> -->
            <?php echo $contenidoPrincipalVista?? ''; ?>
    </main>
    <footer>
        &copy; <?php echo $pie?? ''; ?> 
    </footer>
    
    
</body>
</html>
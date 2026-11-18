<?php
$autoloadPath = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}
$configFile = dirname(__DIR__) . '/config/config.php';

if(isset($_POST['install'])){
//si existe el archivo de configuración lo usamos para connectar a la base de datos, si no existe lo creamos con los datos que llegan del formulario de instalación
if(file_exists($configFile)){
    include_once($configFile);
    try{
        $conexion=new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME,DB_PORT);
    }catch(Exception $e){
        $conexion=false;
    }}else{
//Datos que llegan del formulario de instalación de la base de datos
    $host=$_POST['host'];
    $user=$_POST['user'];
    $passDB=$_POST['passDB'];
    $nameDB=$_POST['nameDB'];
    $portDB=$_POST['portDB'];
    try{
        $conexion=new mysqli($host,$user,$passDB,$nameDB,$portDB);
    }catch(Exception $e){
        $conexion=false;
    }
}

if($conexion===false){
    echo "Error al conectar con la base de datos<br> Verifica los datos de conexión<br>";
   // echo $conexion->connect_error."<br>";
}else{
    //Creamos la ruta de la llamada del archivo de install.php
    $ruta=$_SERVER['REQUEST_URI'];
    //Extraemos la carpeta padre de la ruta actual
    $ruta=explode('/',$ruta);
    //le quitamos los dos últimos elementos del array
    array_pop($ruta);
    array_pop($ruta);
    //Unimos los elementos del array con una barra
    $ruta=implode('/',$ruta);
    $contenidoArchivoConfig="<?php
    define('DB_HOST','$host');
    define('DB_USER','$user');
    define('DB_PASS','$passDB');
    define('DB_NAME','$nameDB');
    define('DB_PORT','$portDB');
    define('RUTA_APP','$ruta');
    ?>";
    $fp=fopen($configFile,"w");
    if($fp===false){
        echo "Error al crear el archivo de configuración<br>";
        echo "Verifica los permisos de escritura en la carpeta config<br>";
        echo "El archivo config.php no se ha podido crear<br>";
        echo "Puede intentar crear el archivo config.php manualmente con el siguiente contenido:<br>";
        echo "<pre>$contenidoArchivoConfig</pre>";
    }else{
       fwrite($fp,$contenidoArchivoConfig);
       fclose($fp);
       echo "Archivo de configuración creado con éxito<br>";
        
        //Datos que llegan del formulario de instalación del primer usuario
        $loginAdmin=$_POST['login'];
        $nameAdmin=$_POST['name'];
        $surname1Admin=$_POST['surname1'];
        $mailAdmin=$_POST['mail'];
        $passAdmin=password_hash($_POST['pass'], PASSWORD_DEFAULT);

        // Incluir las consultas de creación de tablas
        include_once('consultas.php');
        // Insertar el primer usuario administrador
        $consultas[]="INSERT INTO `users` (`login`, `name`, `surname1`, `surname2`, `email`, `password`, `rol`, `enabled`) 
        VALUES 
        ('$loginAdmin', '$nameAdmin', '$surname1Admin', NULL,'$mailAdmin', '$passAdmin', 'admin', '1') ";

        // Ejecutar las consultas
        foreach($consultas as $consulta){
             $resultado=$conexion->query($consulta);
                if($resultado===false){
                    echo "Error en la consulta: $consulta<br>";
                    echo $conexion->error."<br>";
                    break;
                }else{
                    echo "Consulta <br/>$consulta<br/>
                        realizada con éxito<br>";
                }
        }
        $conexion->close();
        echo "Instalación finalizada con éxito<br>";    
        }
    }
    }//Fin del if que comprueba si se ha pulsado el botón de instalación




?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalación de anuncios</title>
    <link rel="stylesheet" href="../css/styles.css">

</head>
<body>
    <h1>Instalación de la aplicación de anuncios</h1>
    <form action="install.php" method="post">
        <?php
        //Si el archivo de configuración ya existe, mostramos un mensaje de advertencia informamos de que ya existe un archivo de configuración y que debe borrarse o será usado para instalar la base de datos con datos nuevos.
        if(file_exists($configFile)){
            echo "<p style='color:red'>El archivo de configuración ya existe. Si continúa, se sobrescribirá y se perderán los datos de la base de datos actual.</p>";
        }else{
        ?>
        <h2>Base de datos</h2>
        <label for="host">Servidor de base de datos</label>
        <input type="text" name="host" id="host" required>
        <label for="user">Usuario de la base de datos</label>
        <input type="text" name="user" id="user" required>
        <label for="passDB">Contraseña de la base de datos</label>
        <input type="password" name="passDB" id="passDB" required>
        <label for="nameDB">Nombre de la base de datos</label>
        <input type="text" name="nameDB" id="nameDB" required>
        <label for="portDB">Puerto de la base de datos</label>
        <input type="number" name="portDB" id="portDB" required>
        <?php
        }
        ?>

        <h2>Administrador de la aplicación</h2>
        <label for="login">Login del administrador</label>
        <input type="text" name="login" id="login" required>
        <label for="name">Nombre del administrador</label>
        <input type="text" name="name" id="name" required>
        <label for="surname1">Primer apellido del administrador</label>
        <input type="text" name="surname1" id="surname1" required>
        <label for="mail">Correo electrónico del administrador</label>
        <input type="email" name="mail" id="mail" required>
        <label for="pass">Contraseña del administrador</label>
        <input type="password" name="pass" id="pass" required>
        <input type="submit" name="install" value="Instalar">
    </form>
    
</body>
</html>
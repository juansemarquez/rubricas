<?php
$datos_post = ['archivo_calificaciones', 'archivo_devoluciones', 'reporte',
               'aprobado', 'nombre', 'apellido', 'calificacion'];
foreach ($datos_post as $d) {
    if(empty($_POST[$d])) {
        die("No se definió $d");
    }
    $$d = $_POST[$d];
}

$linea_calificaciones = "\"$apellido\",\"$nombre\",$calificacion,\"$aprobado\"";
$result = file_put_contents(
    $archivo_calificaciones,
    $linea_calificaciones . PHP_EOL,
    FILE_APPEND
);
if ($result === false) {
    die("Falla al escribir en $archivo_calificaciones");
}

$archivo = fopen($archivo_devoluciones, 'a');
fwrite($archivo, $_POST['reporte']);
fclose($archivo);
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="bootstrap.min.css" type="text/css" /> 
        <title>Archivos escritos</title>
    </head>
    <body class="container txt-center">
        <h1>Archivos escritos</h1>
        <p>Se escribió en
<?php echo $archivo_devoluciones . " y en " . $archivo_calificaciones;?>.</p>
        <button class="btn btn-primary" type="button" onclick="window.location.replace('index.php');">
            Volver
        </button>
    </body>
</html>

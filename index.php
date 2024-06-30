<?php
require_once 'inicio.php';
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>Rubricas</title>
        <link rel="stylesheet" href="bootstrap.min.css" type="text/css" /> 
    </head>
    <body class="container">
        <h1>Rúbricas</h1>
<?php
if ($hay_errores) {
    echo mostrar_errores($errores);
} else {
    echo mostrar_rubrica($r);
}
?>
<script src="rubricas.js"></script>
</body>
</html>

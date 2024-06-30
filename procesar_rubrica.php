<?php

require_once 'Rubrica.php';

$validacion_post = validarPostCompleto($_POST);
if ($validacion_post !== true) {
    $respuesta['error_faltante'] = $validacion_post;
    if (is_numeric($validacion_post)) {
        $respuesta['error_faltante'] = "porcent".$validacion_post;
        $validacion_post++;
        $validacion_post = "Valor del item número $validacion_post";
    }
    $respuesta['error'] = "Falta un dato: $validacion_post";
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($respuesta);
    die();
}

$r = new Rubrica();
[$puntaje, $invalidado] = $r->calcular_puntaje($_POST['porcent']);
$respuesta['titulo'] = $r->titulo;
$respuesta['descripcion'] = $r->descripcion;
$respuesta['items'] = $r->items;
$respuesta['aprobado'] = !$invalidado && $puntaje >= $r->nota_aprobacion;
$respuesta['calificacion'] = $puntaje;
$respuesta['puntaje_maximo'] = $r->total;
$respuesta['reporte'] = $r->armar_reporte($_POST);
$respuesta['error'] = '';
header('Content-Type: application/json; charset=utf-8');
echo json_encode($respuesta);

function validarPostCompleto($post) {
    $valores = ['apellido', 'nombre', 'cantidad_items'];
    foreach ($valores as $v) {
        if (empty($post[$v])) {
            return $v;
        }
    }
    for ($i=0; $i < $post['cantidad_items']; $i++) {
        if (empty($post['porcent'][$i]) && $post['porcent'][$i] !== "0") {
            return $i;
        }
    }
    return true;
}

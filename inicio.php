<?php
require_once 'Rubrica.php';
$r = new Rubrica();
$errores = $r->validar_config();
$hay_errores = count($errores) > 0;

function mostrar_errores($e) {
    $m = '';
    foreach ($e as $error) {
        $m.= "<div class='text-danger'><p>$error</p></div><br>" . PHP_EOL;
    }
    return $m;

}

function mostrar_rubrica($r) {
    $m = empty($r->titulo)?"<h2>Rúbrica sin título</h2>":"<h2>$r->titulo</h2>".PHP_EOL;
    $m.= empty($r->descripcion)?"":"<p>$r->titulo</p>".PHP_EOL;
    $m.= "<form method='post' action='escribir_archivos.php' id='form-puntajes'>".PHP_EOL; 
    if ($r->incluir_NyA) {
        $m.= "<label for='apellido'>Apellido:</label>";
        $m.= "<input name='apellido' id='apellido' required><br>".PHP_EOL;
        $m.= "<label for='nombre'>Nombre:</label>";
        $m.= "<input name='nombre' id='nombre' required><br>".PHP_EOL;
    } else {
        $m.= "<input name='apellido' type='hidden' value='no_incluir'><br>".PHP_EOL;
        $m.= "<input name='nombre' type='hidden' value='no_incluir'><br>".PHP_EOL;
    }
    $m.= "<input name='cantidad_items' type='hidden' value='".count($r->items)."'>";
    $m.= "<ol>".PHP_EOL;
    foreach ($r->items as $k => $i) {
        $m.= "<li><label for='texto[$k]'>".$i['texto'];
        $m.= $i['invalidante'] ? " <span class='text-danger'>(invalidante)</span></label>":"</label>";
        $m.= "<input type='number' min='0' max='100' step='5' name='porcent[$k]' ";
        $m.= "id='porcent$k' value='100' size='4' required>%</li>" . PHP_EOL;
    }
    $m.= "</ol>".PHP_EOL;
    $m.="<button type='button' id='btn_generar' class='btn btn-primary'>Generar reporte</button>".PHP_EOL;
    $m.="<hr>".PHP_EOL;
    $renglones = count($r->items) + 7;
    $m.="<textarea name='reporte' id='reporte' cols='80' rows='$renglones' required>";
    $m.="</textarea><br>".PHP_EOL;
    $m.="<button type='button' onclick='copiar()' class='btn btn-success'>";
    $m.="Copiar</button><br>". PHP_EOL;
    $m.="<label for='calificacion'>Calificación: </label>";
    $m.="<input name='calificacion' id='calificacion' readonly required><br>".PHP_EOL;
    $m.="<label for='aprobado'>Resultado: </label>";
    $m.="<input name='aprobado' id='aprobado' readonly required><br>" . PHP_EOL;
    if ($r->incluir_NyA) {
        $m.='<input type="submit" value="Escribir archivos" id="btn-archivos">'.PHP_EOL;
    }
    $m.="<input type='hidden' name='archivo_devoluciones' value='$r->archivo_devoluciones'>".PHP_EOL;
    $m.="<input type='hidden' name='archivo_calificaciones' value='$r->archivo_calificaciones'>".PHP_EOL;
    $m.="</form>".PHP_EOL;
    return $m;
}

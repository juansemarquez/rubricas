<?php
class Rubrica
{
    public function __construct() {
        require_once 'config.php';
        // El texto que encabezará cada reporte (pueden dejarse en blanco, ""):
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;

        // Si se debe incluir el nombre y apellido de la persona, definir en true. De
        // lo contrario, definir en false:
        $this->incluir_NyA = $incluir_NyA;

        // El máximo de puntos que se puede obtener en el trabajo a evaluar:
        $this->total = $total;

        // El mínimo de puntos que se debe obtener para aprobar:
        $this->nota_aprobacion = $nota_aprobacion;

        // Los items a evaluar:
        // texto: El texto del item, tal como aparecerá en el reporte
        // puntaje: El puntaje que otorga el item
        // invalidante: Si el hecho de tener mal este item implica desaprobar, definir
        //              como true. De lo contrario, definir en false.
        $this->items = $items;

        $this->escribir_archivos = $escribir_archivos; // booleano
        $this->archivo_calificaciones = $archivo_calificaciones;
        $this->archivo_devoluciones = $archivo_devoluciones;

        if (count($this->validar_config()) === 0) {
            $this->setear_valores_por_defecto();
        }
    }

    public function calcular_puntaje($porcentaje)
    {
        $puntaje = 0;
        $invalidado = false;
        foreach ($porcentaje as $k => $p) {
            $puntaje += $p * $this->items[$k]['puntaje'];
            if ($this->items[$k]['invalidante'] && $p == 0) {
                $invalidado = true;
            }
        }
        $puntaje = round($puntaje) / 100;
        return [$puntaje, $invalidado];
    }

    public function armar_reporte($post)
    {
        $porcentaje = $post['porcent'];
        [$puntaje, $invalidado] = $this->calcular_puntaje($porcentaje);
        $m = $this->titulo.PHP_EOL;
        $m.= $this->descripcion.PHP_EOL;
        if ($this->incluir_NyA) {
            $m.= "Estudiante: " . $post['nombre'] . " " . $post['apellido'] .PHP_EOL;
        }
        for($i=0; $i < count($this->items); $i++) {
            $m.= $this->items[$i]['texto'] . " - Puntaje: ";
            $m.= $porcentaje[$i] . "% de " . $this->items[$i]['puntaje'] . ": ";
            $m.= ($porcentaje[$i] * $this->items[$i]['puntaje'] / 100);
            if ($porcentaje[$i] == 0 && $this->items[$i]['invalidante']) {
                $m.= "(invalida examen)";
            }
            $m.=PHP_EOL;
        }
        $m.= "---------------------------------".PHP_EOL;
        $m.= "Calificación: $puntaje de un total de $this->total puntos" . PHP_EOL;
        $m.= "Resultado: ";
        $m.= $invalidado || $puntaje < $this->nota_aprobacion ? "NO APROBADO" : "APROBADO";
        $m.= PHP_EOL . PHP_EOL;
        return $m;
    }

    public function validar_config() {
        $errores = [];
        if (empty($this->nota_aprobacion)) {
            $errores[] = "La nota de aprobación no está definida.";
        }
        if (empty($this->total)) {
            $errores[] = "El puntaje total no está definido.";
        }
        if ($this->nota_aprobacion > $this->total) {
            $errores[] = "La nota de aprobación es mayor que el total.";
        }
        if (empty($this->items)) {
            $errores[] = "No se han definido items para evaluar.";
        }

        $suma = 0;
        foreach($this->items as $k => $v) {
            if (empty($v['texto'])) {
                $errores[] = "El " . $k+1 . "° de los items no tiene descripción.";
            }
            if (empty($v['puntaje']) || !is_numeric($v['puntaje'])) {
                $errores[] = "El " . $k+1 . "° de los items no tiene puntaje definido.";
            } else {
                $suma += $v['puntaje'];
            }
            if ($this->escribir_archivos === true) {
                if (!is_file($this->archivo_calificaciones)) {
                    $errores[] = "El archivo $this->archivo_calificaciones no existe";
                } else if (!is_writable($this->archivo_calificaciones)) {
                    $errores[] = "El archivo $this->archivo_calificaciones no puede ser escrito";
                }
                if (!is_file($this->archivo_devoluciones)) {
                    $errores[] = "El archivo $this->archivo_devoluciones no existe";
                } else if (!is_writable($this->archivo_devoluciones)) {
                    $errores[] = "El archivo $this->archivo_devoluciones no puede ser escrito";
                }
            }
        }
        if ($suma != $this->total) {
            $errores[] = "La sumatoria de los items da un total de $suma, cuando debería ser igual a $this->total.";
        }
        return $errores;
    }

    public function setear_valores_por_defecto() {
        if (empty($this->incluir_NyA)) {
            $this->incluir_NyA = false;
        }
        for ($i=0; $i < count($this->items); $i++) {
            if (empty($this->items[$i]['invalidante']) || !is_bool($this->items[$i]['invalidante'])) {
                $this->items[$i]['invalidante'] = false;
            }
        }
        if (empty($this->escribir_archivos) || !is_bool($this->escribir_archivos)) {
            $this->escribir_archivos = false;
        }
    }
}


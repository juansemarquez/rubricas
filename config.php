<?php

// El texto que encabezará cada reporte (pueden dejarse en blanco, ""):
$titulo = "Examen parcial de Programación I";
$descripcion = "Esta es la devolución del examen realizado el 24/06/2024 en la comisión 2do2da de Programación I.";

// Si se debe incluir el nombre y apellido de la persona, definir en true. De
// lo contrario, definir en false:
$incluir_NyA = true;
// Importante: Si no se identifica al estudiante, no se podrá escribir en
// archivos.

// El máximo de puntos que se puede obtener en el trabajo a evaluar:
$total = 10;

// El mínimo de puntos que se debe obtener para aprobar:
$nota_aprobacion = 5.5;

// Los items a evaluar:
// texto: El texto del item, tal como aparecerá en el reporte
// puntaje: El puntaje que otorga el item
// invalidante: Si el hecho de tener mal este item implica desaprobar, definir
//              como true. De lo contrario, definir en false.
$items = [
    [
        'texto' => 'Contesta la pregunta 1',
        'puntaje' => 2,
        'invalidante' => true,
    ],
    [
        'texto' => 'Contesta la pregunta 2',
        'puntaje' => 2,
        'invalidante' => false,
    ],
    [
        'texto' => 'Contesta la pregunta 3',
        'puntaje' => 2,
        'invalidante' => false,
    ],
    [
        'texto' => 'Contesta la pregunta 4',
        'puntaje' => 1.5,
        'invalidante' => false,
    ],
    [
        'texto' => 'Contesta la pregunta 5',
        'puntaje' => 2.5,
        'invalidante' => false,
    ],
];

$escribir_archivos = true;
$archivo_calificaciones = 'calificaciones.txt';
$archivo_devoluciones = 'devoluciones.txt';

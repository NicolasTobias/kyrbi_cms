<?php
/**
 * Formatea la fecha de la próxima quedada en español.
 *
 * Sin `intl` en la imagen y con strftime obsoleto en PHP 8.1+, la tabla a
 * mano es la opción con menos piezas móviles. Si no hay quedada convocada,
 * devuelve null y la plantilla no pinta el bloque.
 */
return function ($page) {

    $fecha = $page->fecha()->toDate();

    if ($fecha === null) {
        return ['quedada' => null];
    }

    $dias = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    $meses = [
        1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre',
    ];

    return [
        'quedada' => [
            'iso'   => date('c', $fecha),
            'texto' => sprintf(
                '%s %d de %s, %s',
                $dias[(int)date('w', $fecha)],
                (int)date('j', $fecha),
                $meses[(int)date('n', $fecha)],
                date('H:i', $fecha)
            ),
        ],
    ];
};

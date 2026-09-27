<?php

/**
 * Un proyecto: un titular, una introducción opcional y una secuencia de fotos.
 *
 * El orden de las fotos es el orden del panel (campo `sort` que escribe la
 * sección de ficheros ordenable). Es una secuencia decidida a mano, no una
 * galería ordenada por fecha, así que no se ordena por nada más.
 */
class ProyectoPage extends Page
{
    public function fotos()
    {
        return $this->images()->sortBy('sort', 'asc', 'filename', 'asc');
    }

    public function portada()
    {
        return $this->content()->get('portada')->toFile() ?? $this->fotos()->first();
    }
}

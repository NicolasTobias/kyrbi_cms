<?php

/**
 * Una nota. Sin portada, sin etiquetas, sin contador de lectura: la lista
 * solo muestra título y fecha.
 */
class NotaPage extends Page
{
    public function publicada(string $formato = 'j/n/Y'): string
    {
        return (string)$this->date()->toDate($formato);
    }
}

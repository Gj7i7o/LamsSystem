<?php

/*Clase PdfGenerator: Genera reportes para impresión usando window.print()*/

class pdfGenerator
{
    protected $html;

    public function __construct()
    {
        // No requiere inicialización de librerías externas
    }

    /*cargarVista: Carga una plantilla PHP y la convierte en HTML*/
    public function cargarVista(string $vista, array $datos = [])
    {
        extract($datos);
        ob_start();
        require "reportes/templates/{$vista}.php";
        $this->html = ob_get_clean();
        return $this;
    }

    /*generar: Muestra el HTML con script de impresión automática*/
    public function generar(string $nombre = 'documento.pdf', string $orientacion = 'portrait')
    {
        echo $this->html;
    }
}

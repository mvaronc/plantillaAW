<?php

namespace PlantillaAW\View;

use PlantillaAW\Seguridad\Seguridad;

class View {
    public static function render($vista) {
        $rootDir = dirname(__DIR__, 2);

        // Incluir variables de plantilla reutilizables si existe el archivo
        $templatesFile = $rootDir . '/templates/templates.php';
        if (file_exists($templatesFile)) {
            include_once $templatesFile;
        }

        // Obtener el contenido principal de la vista
        if (is_object($vista) && method_exists($vista, 'render')) {
            $contenidoPrincipalVista = $vista->render();
        } else {
            $contenidoPrincipalVista = $vista;
        }

        // Incluir la plantilla base
        $baseTemplate = $rootDir . '/templates/base1.php';
        if (file_exists($baseTemplate)) {
            include $baseTemplate;
        }
    }
}

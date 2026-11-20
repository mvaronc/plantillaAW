<?php

namespace PlantillaAW\View;

use PlantillaAW\Seguridad\Seguridad;


class View {
    /**
     * Renderiza la vista utilizando la plantilla base.
     *
     * @param mixed $vista La vista a renderizar.
     * @param array $variablesModificadas Un array asociativo con las variables a cambiar.
     */
    public static function render($vista,$variablesModificadas = []) {
        $rootDir = dirname(__DIR__, 2);

        // Incluir variables de plantilla reutilizables si existe el archivo
        $templatesFile = $rootDir . '/templates/templates.php';
        if (file_exists($templatesFile)) {
            include_once $templatesFile;
        }
        // Reescribir variables de plantilla si se proporcionan
        if (!empty($variablesModificadas)) {
            extract($variablesModificadas, EXTR_OVERWRITE);
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
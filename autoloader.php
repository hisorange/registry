<?php
// Register a general autoloader for the hisorange\Registry namespace in this package.
spl_autoload_register(function($class) {
    if (substr($class, 0, 19) == 'hisorange\\Registry\\') {
        if (file_exists(($path = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . strtr(substr($class, 19), '\\', DIRECTORY_SEPARATOR) . '.php'))) {
            require_once $path;
            return true;
        }
    }

    return false;
});
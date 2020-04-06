<?php

/**
 * the auto-loading function, which will be called every time a file "is missing"
 * NOTE: don't get confused, this is not "__autoload", the now deprecated function
 * The PHP Framework Interoperability Group (@see https://github.com/php-fig/fig-standards) recommends using a
 * standardized auto-loader https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md, so we do:
 */
function autoload($class) {
    // if file does not exist in LIBS_PATH folder [set it in config/config.php]
    if (file_exists(LIBS_PATH . $class . ".php")) {
        require_once LIBS_PATH . $class . ".php";
    } elseif (file_exists(MODELS_PATH . $class . ".php")) {
        require_once MODELS_PATH . $class . ".php";
    } else {
        exit ('The file ' . $class . '.php is missing in the libs or models folder.');
    }
}
spl_autoload_register("autoload");

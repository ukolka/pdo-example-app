<?php
/**
 * User: mykola
 * Date: 3/19/13
 * Time: 2:18 PM
 */

namespace Utils;


class Autoload {

    public function __construct() {
        spl_autoload_register(array($this, 'load'));
    }

    /**
     * Loads required classes automatically.
     * @param $className
     */
    private function load($className) {
        list ($namespace, $class) = explode('\\', $className);
        $filePath = SRC_DIR . DIRECTORY_SEPARATOR . $namespace . DIRECTORY_SEPARATOR . "$class.php";
        if (DEBUG === True) {
            require_once $filePath;
        } else {
            include_once $filePath;
        }
    }
}

$autoload = new Autoload();
<?php
/**
 * User: mykola
 * Date: 3/19/13
 * Time: 2:09 PM
 */

define('DEBUG', True);
define('ROOT_DIR', dirname(dirname(__FILE__)));
define('SRC_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'src');
define('COMMON_TEMPLATES_DIR', SRC_DIR . DIRECTORY_SEPARATOR . 'Views' .
    DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR);

include '../src/Utils/Autoload.php';

try {
    $dispatcher = new \Utils\Dispatcher();
} catch (Exception $e) {
    if (DEBUG === True) {
        echo $e;
    } else {
        ob_clean();
    }
    header('HTTP/1.0 404 Not Found');
}
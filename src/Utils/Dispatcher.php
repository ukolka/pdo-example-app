<?php
/**
 * User: mykola
 * Date: 3/19/13
 * Time: 2:57 PM
 */

namespace Utils;


class Dispatcher {

    private $pattern = "/^\/(?P<controller>\w+)\/?(?P<action>\w+)?/";

    /**
     * Parses controller and action name from request URI.
     * /user/create/ will be used to call \Controllers\User::create
     */
    public function __construct() {
        if(preg_match($this->pattern, $_SERVER['REQUEST_URI'], $matches)) {
            if (isset($matches['action'])) {
                $this->dispatch($matches['controller'], $matches['action']);
            } else {
                $this->dispatch($matches['controller']);
            }
        } else {
            throw new \Exception('URL doesn\'t match.');
        }
    }

    /**
     * @param $controller controller class name
     * @param string $action method of the controller.
     * @throws \Exception
     */
    private function dispatch($controller, $action = 'index') {
        $controller = ucfirst($controller);
        $className = "\\Controllers\\$controller";
        $templatePath = SRC_DIR . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR .
            $controller . DIRECTORY_SEPARATOR . "$action.phtml";
        if (class_exists($className)) {
            /**
             * @var $classInst \Controllers\AbstractController
             */
            $classInst = new $className($templatePath);
            if (is_callable(array($classInst, $action))) {
                $classInst->$action();
            } else {
                throw new \Exception("$className::$action is not callable.");
            }
        } else {
            throw new \Exception("$className does not exist.");
        }
    }
}
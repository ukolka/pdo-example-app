<?php
/**
 * User: mykola
 * Date: 3/19/13
 * Time: 4:02 PM
 */

namespace Controllers;


abstract class AbstractController {

    protected $templatePath;

    public function __construct($templatePath) {
        $this->templatePath = $templatePath;
    }

    protected function renderTemplate(array $data = array()) {
        $templatePath = $this->templatePath;
        if (!file_exists($templatePath)) {
            throw new \Exception("$templatePath file does not exist.");
        }

        return call_user_func(function () use ($data, $templatePath) {
            extract($data, EXTR_SKIP);
            ob_start();
            if (DEBUG === true) {
                require_once($templatePath);
            } else {
                include_once($templatePath);
            }
            return ob_get_clean();
        });
    }
}
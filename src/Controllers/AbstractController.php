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
        if (!file_exists($this->templatePath)) {
            throw new \Exception("$this->templatePath file does not exist.");
        }
        return call_user_func(function () use ($data) {
            extract($data, EXTR_SKIP);
            ob_start();
            if (DEBUG === true) {
                require_once($this->templatePath);
            } else {
                include_once($this->templatePath);
            }
            return ob_get_clean();
        });
    }
}
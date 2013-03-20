<?php
/**
 * User: mykola
 * Date: 3/20/13
 * Time: 8:17 AM
 */

namespace Controllers;


class DefaultController extends AbstractController {
    public function index () {
        echo $this->renderTemplate(array(
            'page_title' => 'Home'
        ));
    }
}
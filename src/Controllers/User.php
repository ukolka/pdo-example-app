<?php
/**
 * User: mykola
 * Date: 3/19/13
 * Time: 2:09 PM
 */

namespace Controllers;


class User extends AbstractController {

    public function add() {
        $templateData = array(
            'page_title' => 'Add User'
        );
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['username']) &&
                isset($_POST['password']) &&
                isset($_POST['description']) &&
                isset($_POST['level'])) {
                $user = new \Models\User(
                    null, $_POST['username'], $_POST['password'], $_POST['description'], $_POST['level']
                );
                if (!$user->exists()) {
                    $user->save();
                    $templateData['saved_successfully'] = True;
                }
            } else {
                throw \Exception('Missing data fields.');
            }
        }
        echo $this->renderTemplate($templateData);
    }

    public function index() {
        $templateData = array(
            'page_title' => 'List Users'
        );
        $users = \Models\User::getAll();
        $templateData['users'] = $users;
        echo $this->renderTemplate($templateData);
    }

    public function generate() {
        \Utils\UserGenerator::makeUsers();

    }
}
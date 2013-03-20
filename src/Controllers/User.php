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
        $users = array();
        $page = 1;
        $num_per_page = 10;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Number of results per page
            if (isset($_POST['num_per_page'])) {
                $num_per_page = $_POST['num_per_page'] !== 'All' ? $_POST['num_per_page'] : \Models\User::count();
                $templateData['num_per_page'] = $_POST['num_per_page'];
            }
            // Search
            if (isset($_POST['search']) ) {
                $users = \Models\User::search(strtolower(trim($_POST['search'])), $num_per_page, $page);
                $templateData['search'] = $_POST['search'];
            }
        } else {
            $users = \Models\User::getAll($num_per_page, $page);
        }
        $templateData['users'] = $users;
        echo $this->renderTemplate($templateData);
    }

    public function generate() {
        \Utils\UserGenerator::makeUsers();
    }
}
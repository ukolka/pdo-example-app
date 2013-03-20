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
        $userCount = \Models\User::count();
        $page = 1;
        $num_per_page = 10;
        // Number of results per page
        if (isset($_REQUEST['num_per_page'])) {
            $num_per_page = (int) ($_REQUEST['num_per_page'] !== 'All' ? $_REQUEST['num_per_page'] : $userCount);
            $templateData['num_per_page'] = $_REQUEST['num_per_page'];
        } else {
            $templateData['num_per_page'] = $num_per_page;
        }
        // Page number
        if (isset($_REQUEST['page'])) {
            $page = $_REQUEST['page'];
            if ($num_per_page === $userCount) {
                $page = 1;
            }
        }
        // Search
        if (isset($_REQUEST['search']) ) {
            $term = strtolower(trim($_REQUEST['search']));
            $users = \Models\User::search( $term, $num_per_page, ($page - 1) * $num_per_page);
            $userCount = \Models\User::searchCount($term);
            $templateData['search'] = $_REQUEST['search'];
        } else {
            $users = \Models\User::getAll($num_per_page, ($page - 1) * $num_per_page);
        }
        $templateData['users'] = $users;
        $templateData['user_count'] = intval($userCount, 10);
        $templateData['page'] = $page;
        echo $this->renderTemplate($templateData);
    }

    public function generate() {
        \Utils\UserGenerator::makeUsers();
    }
}
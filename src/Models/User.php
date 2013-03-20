<?php
/**
 * User: mykola
 * Date: 3/19/13
 * Time: 2:10 PM
 */

namespace Models;


class User {

    /* Database Mapping */
    public $id, $username, $password, $description, $level;

    /* Password Salt */
    private static $salt = 'slIjs^sa()923Jdf48&#';


    /* Queries */
    private $existsQuery = "SELECT COUNT(id) as count FROM users WHERE username = :username;";

    private $saveQuery = <<<SQL
INSERT INTO users (username, password, description, level)
VALUES (:username, :password, :description, :level);
SQL;

    private static $getAllQuery = "SELECT id, username, password, description, level FROM users;";

    public function __construct($id = null, $username, $password, $description, $level) {
        if (!isset($this->username)) {
            $this->id = $id;
            $this->username = $username;
            $this->password = $this->hashPassword($password);
            $this->description = $description;
            $this->level = $level;
        }
    }

    /**
     * Protect passwords in case if the database is compromised.
     * @param $password
     * @return string
     */
    private function hashPassword($password) {
        return md5(self::$salt . $password);
    }

    /**
     * Checks if a username is already taken.
     * @return bool
     */
    public function exists() {
        $con = \DB\Connection::getConnection();
        $stmt = $con->prepare($this->existsQuery);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        return intval($stmt->fetch()['count'], 10) > 0;
    }

    /**
     * Writes user to the database.
     * @return bool
     */
    public function save() {
        $con = \DB\Connection::getConnection();
        $stmt = $con->prepare($this->saveQuery);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':level', $this->level);
        return $stmt->execute();
    }

    /**
     * Returns list of users.
     * @return array
     */
    public static function getAll() {
        $con = \DB\Connection::getConnection();
        $stmt = $con->prepare(self::$getAllQuery);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class());
    }

    public function __toString() {
        return "User($this->id, $this->username, $this->password, $this->description, $this->level)";
    }
}
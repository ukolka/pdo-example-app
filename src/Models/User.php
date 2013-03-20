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

    private static $countQuery = "SELECT COUNT(id) as count FROM users;";

    private static $searchQuery = <<<SQL
SELECT * FROM users
  WHERE username = :username OR description LIKE :keyword
  LIMIT :limit OFFSET :offset;
SQL;

    private static $getAllQuery = "SELECT * FROM users LIMIT :limit OFFSET :offset;";

    public function __construct($id = null, $username = null, $password = null, $description = null, $level = null) {
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

    public static function count() {
        $con = \DB\Connection::getConnection();
        $stmt = $con->prepare(self::$countQuery);
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    /**
     * Looks up user by name or description.
     * @param $term
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function search($term, $limit=10, $offset=1) {
        $con = \DB\Connection::getConnection();
        $stmt = $con->prepare(self::$searchQuery);
        $stmt->bindParam(':username', $term);
        $keyword = "%$term%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->bindParam(':limit', intval($limit, 10), \PDO::PARAM_INT);
        $stmt->bindParam(':offset', intval($offset, 10), \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class());
    }

    /**
     * Returns list of users.
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public static function getAll( $limit=10, $offset=1) {
        $con = \DB\Connection::getConnection();
        $stmt = $con->prepare(self::$getAllQuery);
        $stmt->bindParam(':limit', intval($limit, 10), \PDO::PARAM_INT);
        $stmt->bindParam(':offset', intval($offset, 10), \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class());
    }

    public function __toString() {
        return "User($this->id, $this->username, $this->password, $this->description, $this->level)";
    }
}
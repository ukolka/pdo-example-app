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

    private $updateQuery = <<<SQL
UPDATE users SET username=:username, password=:password, description=:description, level=:level
  WHERE id = :id;
SQL;

    private static $countQuery = "SELECT COUNT(id) AS count FROM users;";

    private static $searchQuery = <<<SQL
SELECT * FROM users
  WHERE username = :username OR description LIKE :keyword
  ORDER BY %s
  LIMIT :limit OFFSET :offset;
SQL;

    private static $searchCountQeury =
        "SELECT COUNT(id) AS count FROM users WHERE username = :username OR description LIKE :keyword;";

    private static $getAllQuery = "SELECT * FROM users ORDER BY %s LIMIT :limit OFFSET :offset;";

    private static $deleteQuery = "DELETE FROM users WHERE username = :username;";

    private static $getQuery = "SELECT * FROM users WHERE username = :username;";

    public function __construct($id = null, $username = null, $password = null, $description = null, $level = null) {
        if (!isset($this->username)) {
            $this->id = $id;
            $this->username = $username;
            $this->setPassword($password);
            $this->description = $description;
            $this->level = $level;
        }
    }

    /**
     * Protect passwords in case if the database is compromised.
     * @param $password
     * @return string
     */
    public function setPassword($password) {
        $this->password = md5(self::$salt . $password);
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
        $stmt->bindParam(':level', $this->level, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Update existing database record.
     * @return bool
     */
    public function update() {
        $con = \DB\Connection::getConnection();
        $stmt = $con->prepare($this->updateQuery);
        $stmt->bindParam(':id', $this->id, \PDO::PARAM_INT);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':level', $this->level);
        return $stmt->execute();
    }

    /**
     * Compare with other user for equality.
     * @param User $other
     * @return bool
     */
    public function eq(User $other) {
        foreach ($this as $prop => $value) {
            if ($other->$prop !== $value) {
                return False;
            }
        }
        return True;
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
     * @param $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function search($term, $orderBy, $limit=10, $offset=1) {
        $con = \DB\Connection::getConnection();
        $query = sprintf(self::$searchQuery, $orderBy);
        $stmt = $con->prepare($query);
        $stmt->bindParam(':username', $term);
        $keyword = "%$term%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->bindParam(':limit', intval($limit, 10), \PDO::PARAM_INT);
        $stmt->bindParam(':offset', intval($offset, 10), \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class());
    }

    public static function searchCount($term) {
        $con = \DB\Connection::getConnection();
        $stmt = $con->prepare(self::$searchCountQeury);
        $stmt->bindParam(':username', $term);
        $keyword = "%$term%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetch()['count'];
    }

    /**
     * Returns list of users.
     * @param string $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function getAll($orderBy = 'id ASC', $limit = 10, $offset = 1) {
        $con = \DB\Connection::getConnection();
        $query = sprintf(self::$getAllQuery, $orderBy);
        $stmt = $con->prepare($query);
        $stmt->bindParam(':limit', intval($limit, 10), \PDO::PARAM_INT);
        $stmt->bindParam(':offset', intval($offset, 10), \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class());
    }

    /**
     * Get a single user by username.
     * @param $username
     * @return mixed
     */
    public static function get($username) {
        $con = \DB\Connection::getConnection();
        $stmt = $con->prepare(self::$getQuery);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, get_class());
        return $stmt->fetch();
    }

    public static function delete($username) {
        $con = \DB\Connection::getConnection();
        $stmt = $con->prepare(self::$deleteQuery);
        $stmt->bindParam(':username', $username);
        return $stmt->execute();
    }

    public function __toString() {
        return "User($this->id, $this->username, $this->password, $this->description, $this->level)";
    }
}
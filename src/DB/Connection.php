<?php
/**
 * User: mykola
 * Date: 3/19/13
 * Time: 2:11 PM
 */

namespace DB;


class Connection extends \PDO {
    private static $instance;

    public function __construct() {
        $settings = \Utils\Settings::getSettings();
        $dbSettings = $settings->database;
        parent::__construct(
            "$dbSettings->driver:host=$dbSettings->host;port=$dbSettings->port;dbname=$dbSettings->dbname",
            $dbSettings->user,
            $dbSettings->password
        );
        $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $this;
    }

    public static function getConnection() {
        if (empty(self::$instance)) {
            self::$instance = new Connection();
        }
        return self::$instance;
    }

}
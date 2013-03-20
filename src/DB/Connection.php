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
        if (APPFOG === True) {
            $services_json = json_decode(getenv("VCAP_SERVICES"),true);
            $mysql_config = $services_json["mysql-5.1"][0]["credentials"];
            $username = $mysql_config["username"];
            $password = $mysql_config["password"];
            $hostname = $mysql_config["hostname"];
            $port = $mysql_config["port"];
            $db = $mysql_config["name"];
            parent::__construct(
                "mysql:host=$hostname;port=$port;dbname=$db",
                $username,
                $password
            );
        } else {
            $settings = \Utils\Settings::getSettings();
            $dbSettings = $settings->database;
            parent::__construct(
                "$dbSettings->driver:host=$dbSettings->host;port=$dbSettings->port;dbname=$dbSettings->dbname",
                $dbSettings->user,
                $dbSettings->password
            );
        }
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
<?php
/**
 * User: mykola
 * Date: 3/19/13
 * Time: 2:01 PM
 */

namespace Utils;

class Settings {
    private $settings;
    private $settings_file;
    private static $instance;

    /**
     * Works with json settings file.
     * @param $settings_file
     * @throws \Exception
     */
    private function __construct($settings_file) {
        $settings_path = SRC_DIR . DIRECTORY_SEPARATOR . $settings_file;
        if (!file_exists($settings_path)) {
            throw new \Exception("Settings file $settings_path does not exist.");
        }
        $this->settings_file = $settings_path;
        $this->settings = json_decode(file_get_contents($settings_path));
    }

    /**
     * Singleton entry point.
     * @param string $settings_file
     * @return Settings
     */
    public static function getSettings($settings_file='settings.json') {
        if (empty(self::$instance)) {
            self::$instance = new Settings($settings_file);
        }
        return self::$instance;
    }

    public function __get($name) {
        if (isset($this->settings->$name)) {
            return $this->settings->$name;
        }
        return null;
    }

    public function __set($name, $value) {
        $this->settings->$name = $value;
    }

    public function save() {
        file_put_contents($this->settings_file, json_encode($this->settings, JSON_PRETTY_PRINT));
    }

    public function __destruct() {
        $this->save();
    }
}
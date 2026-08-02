<?php
class Database {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            require_once __DIR__ . '/../../dbconnection.php';
            self::$conn = $conn;
        }

        return self::$conn;
    }
}

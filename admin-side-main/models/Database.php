<?php
/**
 * Database
 *
 * Thin wrapper around the project's shared mysqli connection
 * (../../dbconnection.php) so that every Model in the admin-side
 * MVC layer can grab a single, shared connection instance instead
 * of each file opening its own.
 */
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

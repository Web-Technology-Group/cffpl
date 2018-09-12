<?php

namespace Com\PlayerArea\Database;

/**
 * Class DBConnection - A singleton class that will always return a single
 * instance of PDO. This singleton class only has one point of
 * access, i.e. through the static 'getPDOInstance' method
 */
class DBConnection
{
    // The single instance of the pdo
    private static $pdo;

    // The Database connection parameters... these should really be pulled in from some sort of php equivalent of
    // a properties files i.e. a php or .ini file
    private static $dbHost = "localhost";
    private static $dbName = "cffpl";
    private static $dbUser = "Mark";
    private static $dbPassword = "password";
    private static $charset = "utf8mb4";

    /**
     * DBConnection constructor.
     */
    private function __construct()
    {
    }

    /**
     * Cloning and unserialization are not permitted for singletons such as DBConnection.
     */
    private function __clone()
    {
    }

    /**
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton.");
    }

    /**
     * Get the PDO instance used for database queries
     * @return \PDO
     */
    public static function getPDOInstance() {
        if (!isset(self::$pdo)) {

            // Establish a PDO (PHP Data Object) connection to the database. Note, that PDO is effectively a
            // Database access abstraction layer

            // Use the DSN (Data Source Name) i.e. the semi-colon delimited string consisting of param=values pairs
            $dataSourceName = "mysql:host=".self::$dbHost.";dbname=".self::$dbName.";charset=".self::$charset;

            // Now attampt the the connection
            try {
                self::$pdo = new \PDO($dataSourceName, self::$dbUser, self::$dbPassword);
                return self::$pdo;
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), (int) $e->getCode());
            }
        }

        return self::$pdo;
    }
}
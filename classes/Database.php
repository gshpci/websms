<?php

    require_once (__DIR__.'/../config.inc.php');

    class Database {

        const HOST = CONF_WEBHOST;
        const USER = CONF_DB_USER;
        const PASS = CONF_DB_PASS;
        const DB = CONF_DB_NAME;

        public $varUsername = '';
        /* Properties */
        private $conn;

        // Constructor:
        public function __construct()
        {
            // At instantiation of the class/object, we will connect to our database.
            $this->connect();
        }

        /* Methods */

        // Method for connecting to database
        private function connect()
        {

            $this->conn = mysql_connect(self::HOST, self::USER, self::PASS)
                     or die("Unable to connect to MySQL");

            $selected = mysql_select_db(self::DB, $this->conn)
                or die("Error connecting to database: ");
        }

        // Method for doing query
        public function query($sql)
        {

            $resultSet = mysql_query($sql);

            return $resultSet;
        }

        // Method for real escaping string (we will call it from User class)
        public function escapeString($input)
        {
            $escapedString = $this->conn->real_escape_string($input);

            return $escapedString;
        }

        // Method for checking number of returned rows (when doing select queries)
        public function numRows($resultSet)
        {
            $numRows = mysql_fetch_array($resultSet);

            if($numRows > 0) {
                return $numRows;
            } else {
                return false;
            }
        }

        // Method for checking number of affected rows (when doing insert/update/delete queries etc..)
        public function affectedRows()
        {
            $affectedRows = mysql_affected_rows();

            if($affectedRows > 0) {
                return $affectedRows;
            } else {
                return false;
            }
        }
    }

    $database = new Database();

?>
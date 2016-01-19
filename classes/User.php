<?php

    require_once (__DIR__.'/../config.inc.php');

    $user = new User();

    class User {

        /* Variable */
        public $station = '';
        const HOST = CONF_WEBHOST;
        const USER = CONF_DB_USER;
        const PASS = CONF_DB_PASS;
        const DB = CONF_DB_NAME;

        /* Methods */

        // Set message to store in session
        protected function setMessage($msg)
        {
            echo "<script>
                    alert('$msg');
                </script>";
        }
//UserID, Name, Station, Username, Password, Usertype, Date
        // Method for checking if fields are not empty in registration form
        protected function checkRegistrationForEmpty($Station, $Password)
        {

            if (!empty($Station) && !empty($Password)) {
                return true;
            } else {
                return false;
            }
        }

        // Method for checking if fields are not empty in login form
        protected function checkLoginForEmpty($Station, $pass)
        {
            if (!empty($Station) && !empty($pass)) {
                return true;
            } else {
                return false;
            }
        }

        // Method for sanitizing user input
        protected function sanitize($input)
        {
            // We need to access database object
            global $database;

            $sanitizedInput = $database->escapeString(trim($input));

            return $sanitizedInput;
        }

        // Method for registering user
        public function register($Station, $Password)
        {
            // We need to access database object

		echo "Station : $Station <br>";
		echo "Password: $Password";

            global $database;

            if ($this->checkRegistrationForEmpty($Station, $Password)) {

                // Sanitizing user input
                $safeStation    = $Station;
                $safePassword = $Password;

                // First check if there is an existing user with that username and if there is, reject registration
                $sql       = "SELECT username FROM user WHERE username = '{$safeUsername}'";

                $resultSet = $database->query($sql);

                if ($row = mysql_fetch_array($resultSet)) {
                    // There is an user with that username, so reject registration
                    // We are using here simple echo function, ofcourse,you could redirect him to error page, or make fancier error message
                    /*echo "<p>Sorry, there is already user with the same username you provided in our database.</p>";*/
                    $this->setMessage("Sorry, there is already user with the same username.\n\n Please try again.");
                } else {
                    // We can freely register him

                    // First, make hash of the password user provided

                    $sql       = "INSERT INTO user(Station, Password, Date) VALUES ('{$safeStation}', '{$safePassword}', curdate())";

                    //echo $sql;

                    $resultSet = $database->query($sql);
                    //echo "$sql";
                    if ($database->affectedRows()) {
                        $this->setMessage("STATION is successfully Registered!");
                    } else {
                        $this->setMessage("System Error: We could not add new User");
                    }
                }
            } else {
               // $this->setMessage("Please, fill out all fields");
            }
        }

        public function getuserid(){


                    //connection to the database
                    $dbhandle = mysql_connect(self::HOST, self::USER, self::PASS)
                     or die("Unable to connect to MySQL");
                    //echo "Connected to MySQL<br>";

                    //select a database to work with
                    $selected = mysql_select_db(CONF_DB_NAME,$dbhandle)
                      or die("Could not select examples");


                    echo "this.varUsername : ".$this->varUsername;

                    $val = $this->varUsername;
                    $sql = "SELECT UserID, Name, Station, Username, Password, Usertype, Date FROM `user` u where username = '{$val}'";
                    //execute the SQL query and return records
                    echo "<br>".$sql;
                    $result = mysql_query($sql);
                    //fetch tha data from the database
                    echo "<br>".$sql;
                    if ($row = mysql_fetch_array($result)) {
                        //display the results
                       return $row{'UserID'};
                    }
                    //close the connection
                    mysql_close($dbhandle);
        }
        // Method for loging user in
        public function logIn($station, $pass)
        {
            // We need to access database object
            global $database;

            if ($this->checkLoginForEmpty($station, $pass)) {

                $safeStation = $station;
                $safePass     = $pass;


                // First,we access the existing pass hash from table
                $sql       = "SELECT password, station FROM user WHERE station = '{$safeStation}'";

                $resultSet = $database->query($sql);
                // If there is a user with that username

                if ($row = mysql_fetch_array($resultSet)) {
                    // Extract hash from resultSet

                    // Verify password
                    if ($row{'password'} == $safePass) {

			$sql       = "INSERT INTO logstrigger(Station, `action`, datetime) VALUES ('{$safeStation}', 'Login', concat(curdate(),' ',curtime()))";

                    //echo $sql;
			$this->station = $safeStation;
                    $resultSet = $database->query($sql);
 			if ($database->affectedRows()) {
			echo '<script type="text/javascript">';
                            echo 'alert("Authentication Granted!");';
                            echo 'window.location.href = "'.CONF_WEBDIR.'/sms.php";';
			echo '</script>';

                    }
			   //header("Location: success.php");
                        //define('varUsername', $row{'station'});

                    } else {
                        $this->setMessage("Error, incorrect password.");
                    }
                } else {
                    $this->setMessage("Authentication Failed!");
                }
            } else {
                $this->setMessage("Please, fill out all fields.");
            }
        }

        // Method for displaying users fetched from resultSet
        private function display($result)
        {
            // Here you can edit this method,if you want to display users in different way/format
            /*echo "<ul>";
            while($row = $resultSet->fetch_object()) {
                echo "<li>{$row->username} - {$row->email}</li>";
            }
            echo "</ul>";
*/
            //UserID, Name, Station, Username, Password, Usertype, Date

                    //connection to the database
                    $dbhandle = mysql_connect(self::HOST, self::USER, self::PASS)
                     or die("Unable to connect to MySQL");
                    //echo "Connected to MySQL<br>";

                    //select a database to work with
                    $selected = mysql_select_db(CONF_DB_NAME,$dbhandle)
                      or die("Could not select examples");

                    //execute the SQL query and return records
                    $result = mysql_query("SELECT UserID, Name, Station, Username, Password, Usertype, Date FROM `user` u order by Station desc;");
                    if (!$result) {
                        die('Invalid query: ' . mysql_error());
                    }

                    //fetch tha data from the database
                    while ($row = mysql_fetch_array($result)) {
                        //display the results
                       echo "<ul>
                            <li>".$row{'Name'}." ->> ".$row{'Station'}."</li>
                            </ul>
                        ";
                    }
                    //close the connection
                    mysql_close($dbhandle);


        }

        // Method for fetching existing users
        public function fetchAllUsers()
        {
            // We need to access database object
            global $database;

            $sql = "SELECT * FROM `user`";
            $resultSet = $database->query($sql);

            if($database->numRows($resultSet)) {
                $this->display($resultSet);
            } else {
                echo "<p>There are no registered users, nothing to show.</p>";
            }

        }
    }

$user = new User();

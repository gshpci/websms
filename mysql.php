<?php
$username = "root";
$password = "";
$hostname = "localhost"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) 
 or die("Unable to connect to MySQL");
echo "Connected to MySQL<br>";

//select a database to work with
$selected = mysql_select_db("gsh_sms",$dbhandle) 
  or die("Could not select examples");

//execute the SQL query and return records
$result = mysql_query("SELECT id, datetime, mobile_no, station, message, remarks FROM sms s;");

//fetch tha data from the database 
while ($row = mysql_fetch_array($result)) {
	//display the results
   echo "ID:".$row{'id'}.
   " Name:".$row{'datetime'}.
   " Name:".$row{'mobile_no'}.
   " Name:".$row{'station'}.
   " Name:".$row{'message'}.
   "Year: ". $row{'remarks'}."<br>";
}
//close the connection
mysql_close($dbhandle);
?>
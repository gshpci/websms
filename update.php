<?php
require_once 'config.inc.php';

$u = CONF_DB_USER;
$p = CONF_DB_PASS;
$h = CONF_WEBHOST;
$dbase = CONF_DB_NAME;

$connect_db = mysql_connect($h, $u, $p) or die("Cannot connect to server");
mysql_select_db($dbase,$connect_db) or die("Cannot connect to the database");

$id    = $_GET['id'];

$query = mysql_query("select * from phonebook where id='{$id}'");

echo "select * from phonebook where id='{$id}'";

if($row = mysql_fetch_array($query)){

?>



<html>

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">

<body>
				                    <div class="modal-header">
				                        <h4 class="modal-title" style="color:#000000;" id="myModalLabel">Edit Contact</h4>
				                    </div>
<br>
<br>
<br>
				                    <div class="modal-body">
				                    	<center>
											<form action="edit.php" method="post">
											<input type='text' name="id" value="<?php $_GET['bookId'] ?>" style="color:#000000;">
											<label style="color:#000000;">Fullname</label> <br><input type="text" name="Name" value="<? $row['Number'] ?>" placeholder="<?php $sel_id?>" disabled><br>
											<label style="color:#000000;">Number</label> <br><input style="color:#000000;" type="text" name="Number" placeholder="<?=$row['Number']?>" ><br><br>
											<input type="submit" value="Update"  class="btn btn-success">
											<!-- <input type="reset" value="Reset" class="btn btn-success"> -->
											</form>
										</center>
				                    </div>
<br>
<br>
<br>


				                    <div class="modal-footer">
										<a href="sms.php"  class="btn btn-danger">Close</a>
				                    </div>
				    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>

<?php

}

?>

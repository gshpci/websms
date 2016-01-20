<?php

require_once 'config.inc.php';

$u = CONF_DB_USER;
$p = CONF_DB_PASS;
$h = CONF_WEBHOST;
$dbase = CONF_DB_NAME;

$connect_db = mysql_connect($h,$u,$p) or die("Cannot connect to server");
mysql_select_db($dbase,$connect_db) or die("Cannot connect to the database");

$src = $_POST['source'];
$dst = $_POST['action'];
$action_stat = $src." -> ".$dst;
$station = ($_POST['station']);

if ($dst == 'Logout') {
    session_unset();
    session_destroy();
    $dst = 'index.php';
}

$sql = "INSERT INTO logstrigger(Station, `action`, datetime) VALUES ('{$station}', '{$action_stat}', concat(curdate(),' ',curtime()))";

mysql_query($sql);

echo "<script>window.location.href='$dst';</script>";

?>
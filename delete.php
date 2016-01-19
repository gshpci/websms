<?php
require_once 'config.inc.php';

$u = CONF_DB_USER;
$p = CONF_DB_PASS;
$h = CONF_WEBHOST;
$dbase = CONF_DB_NAME;

    $connect_db = mysql_connect($h, $u, $p) or die("Cannot connect to server");
    mysql_select_db($dbase,$connect_db) or die("Cannot connect to the database");

    $action = ($_POST['action']);
    $station = ($_POST['station']);
    $id = ($_POST['id']);

    $sql = "INSERT INTO logstrigger(Station, `action`, datetime) VALUES ('{$station}', '{$action}', concat(curdate(),' ',curtime()))";

    mysql_query($sql);

    mysql_query("delete from phonebook where id='".$id."'");

    echo "<script>alert('Record successfuly deleted.'); window.location.href='sms.php';</script>";
?>
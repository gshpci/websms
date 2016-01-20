<?php
require 'header.php';
require_once 'config.inc.php';
?>

<style>
  .dropdown {
    position: relative;
    width: 350px;
  }
  .dropdown select{
    width: 100%;
  }
  .dropdown > * {
    box-sizing: border-box;
    height: 1.5em;
  }
  .dropdown option{
  color: green;
  }
  .dropdown input {
  /*width: calc(100% - 20px);*/
  padding: 0 2.5em 0 2.5em;
  width: 100%;
  color: black;
  }
</style>

<?php

require_once "Imap.php";

$mailbox = CONF_WEBHOST.':993';
$username = CONFG_IMAP_USER;
$password = CONFG_PASSWORD;
$encryption = 'ssl'; // or ssl or ''

// open connection
$imap = new Imap($mailbox, $username, $password, $encryption);

// stop on error
if($imap->isConnected()===false)
    die($imap->getError());

// get all folders as array of strings
//$folders = $imap->getFolders();
//foreach($folders as $folder)
//    echo $folder.'<br>';

// select folder Inbox
$imap->selectFolder('INBOX');

// count messages in current folder
$overallMessages = $imap->countMessages();
$unreadMessages = $imap->countUnreadMessages();

//echo "Message Count: ".$overallMessages." | ".$unreadMessages."<br>";

// fetch all messages in the current folder
$emails = $imap->getMessages();

//var_dump($emails);
$datetime = '';
$subject = '';
$uid = '';
$sms = '';

$mobilenum = '';

$col = 0;
$cnt = 0;

//echo "<p style='color:#fff; font-size:16px;'> Emails: <b>".count($emails)."</b> <p>";
?>

<div id= 'load' class ='container-fluid'>
<fieldset>
    <h5 style="color:white;"><blockquote>
        <span class="glyphicon glyphicon-envelope">&nbsp;<strong>Inbox</strong>
    </blockquote>
</fieldset>

<table class="rwd-table" style="width: 100%">
    <thead>
        <tr>
            <th class="listtd" style="text-align: center;" width="15%" >Datetime</th>
            <th class="listtd" style="text-align: center;" width="25%">From</th>
            <th class="listtd" style="text-align: center;" width="35%">SMS</th>
            <th class="listtd" style="text-align: left;" width="25%">Actions</th>
        </tr>
    </thead>
</table>

<div id="myDIV" style="height: 520px;">
    <table class="rwd-table" style="width: 100%">
        <tbody>

<?php
foreach ($emails as $em) {
    //echo "<br>";
    $cnt++;
    $col = 0;
    echo "<tr class = 'listtr' >";
?>

<?php
    $u = CONF_DB_USER;
    $p = CONF_DB_PASS;
    $h = CONF_WEBHOST;
    $dbase = CONF_DB_NAME;

    mysql_connect($h, $u, $p) or die (mysql_error());
    mysql_select_db($dbase) or die (mysql_error());

    foreach ($em as $k => $val) {
        //echo "$k :- $val<br>";
        if ($k == "date") {
            $d = new DateTime($val);
            $val = $d->format('m-d-Y H:i:s');
            $datetime = $val;
        } else if ($k == "subject") {
            $mob_no = substr($val,12,10);
            $query = mysql_query("select concat(Lastname,', ', Firstname) as name from phonebook where Number = concat('0', '$mob_no') || Number = concat('+63', '$mob_no')");

            if ($row = mysql_fetch_array($query)) {
                $subject = $row['name']." <0".substr($val,12,10).">";
            } else {
                $subject = "UNKNOWN <0".substr($val,12,10).">";
                $mobilenum = "0".substr($val,12,10);
            }
        } else if ($k == "uid") {
            $uid = $val;
        } else if ($k == "body") {
            $sms = $val;
        }
    }
?>

    <td class = "listtd" style="text-align: center" width="15%"><?php echo $datetime ?></td>
    <td class = "listtd">
        <div style="width: 100%; float:right;" >
        <?php
            echo $subject;
            if (substr($subject, 0,7) == "UNKNOWN") {
        ?>
                <button style="text-decoration:none;cursor:pointer;" class = "btn btn-success" data-toggle="modal" data-target="#AddData1-<?php echo $uid; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true" style = "color:#ffffff;"></span><font size="1em">Add to Phonebook</font></button >
        <?php } ?>
        </div>
    </td>
    <td class = "listtd" width="35%">
        <div style="width: 100%; float:right;" >
            <?php
                if(strlen($sms) > 40){
                    echo substr($sms, 0, 40)."<span style='font-size:14 px; color:red;'> ... (truncated). </span>";
            ?>
            &nbsp;
            <button style="text-decoration:none;cursor:pointer;" class = "btn btn-info" data-toggle="modal" data-target="#ViewSMS-<?php echo $uid; ?>">
                <span class="glyphicon glyphicon-eye-open" aria-hidden="true" style = "color:#fff;"></span>
                View All
            </button >&nbsp;&nbsp;

            <?php
                }else{
                    echo substr($sms, 0, 40);
                }
            ?>
        </div>
    </td>
    <td class = "listtd" width="25%">
        <div style="width: 100%; float:right;" >

            <button style="text-decoration:none;cursor:pointer;" class = "btn btn-info" data-toggle="modal" data-target="#RelpySMS-<?php echo $uid; ?>">
                <span class="glyphicon glyphicon-envelope" aria-hidden="true" style = "color:#fff;"></span>
                <span class="glyphicon glyphicon-share-alt" aria-hidden="true" style = "color:#fff;"></span>
                Reply
            </button >
            &nbsp;&nbsp;
            <button style="text-decoration:none;cursor:pointer;" class = "btn btn-info" data-toggle="modal" data-target="#ForwardSMS-<?php echo $uid; ?>">
                <span class="glyphicon glyphicon-envelope" aria-hidden="true" style = "color:#fff;"></span>
                <span class="glyphicon glyphicon-arrow-right" aria-hidden="true" style = "color:#fff;"></span>
                Foward
            </button >
        </div>
    </td>

<?php
    echo "</tr>";
?>

<!--Modal for Add Contact-->
        <div class="modal fade" id="AddData1-<?php echo $uid; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabe">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabe">Add New Contact</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <br>
                            <br>
                            <br>
                            <center>
                                <form action="add.php" method="post">
                                <input type="hidden" id="Modal" name="Modal" value="myAddRecord">
                                <input type="hidden" id="action" name="action" value="Add Contact">
                                <input type="hidden" id="station" name="station" value="<?php echo $from_name; ?>">
                                    <div class="form-group has-info">
                                        <label class="control-label" for="From_Name">Lastname *</label>
                                            <input type="text" class="form-control" name="lName" style="width:50%;height:2em" required>
                                    </div>
                                    <div class="form-group has-info">
                                        <label class="control-label" for="From_Name">Firstname *</label>
                                            <input type="text" class="form-control" name="fName" style="width:50%;height:2em" required>
                                    </div>
                                    <div class="form-group has-info">
                                        <label class="control-label" for="From_Name">Middlename</label>
                                            <input type="text" class="form-control" name="mName" style="width:50%;height:2em">
                                    </div>

                                    <div class="form-group has-info">
                                        <label class="control-label" for="From_Name">Number (11-Digit Format: 09XXXXXXXXX) *</label>
                                        <br>
                                        <h4 class="control-label" for="From_Name" style="border-style: solid; border-radius: 6px; border-width: 1px; padding: 5px 0px 5px 0px; border-color: #CCCCCC; width: 50%;"><?php echo $mobilenum; ?></h4>
                                            <input pattern="^09[0-9]{9}" value ="<?php echo $mobilenum; ?>" title="11 digits and numbers only. Must start with 09"  maxlength="11" onkeypress="return /\d/.test(String.fromCharCode(((event||window.event).which||(event||window.event).which)));" type="hidden" class="form-control" name="Number" style="width:50%;height:2em" >
                                    </div>
                                    * - Required field.
                            </center>
                            <br>
                            <br>
                            <br>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
<!--End Modal for Add Contact-->

<!-- View Modal -->
<div class="modal fade" id="ViewSMS-<?php echo $uid; ?>" tabindex="-<?php echo $uid; ?>" role="dialog" aria-labelledby="myModalLabel-<?php echo $uid; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

                <div class="modal-header">
                        <span id="IL_AD4" class="IL_AD">
                    <a type="button" class="close glyphicon glyphicon-remove" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">

                        </span>
                    </a>
                    </span>

                        <h4 class="modal-title" id="myModalLabel-<?php echo $uid; ?>">
                    <span class="glyphicon glyphicon-envelope" aria-hidden="true" style = "color:#fff;"></span>
                    <span class="glyphicon glyphicon-share-alt" aria-hidden="true" style = "color:#fff;"></span>
                    SMS Details</h4>
                </div>

            <div class="modal-body" >
                <form action="delete.php" method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo $uid; ?>">

                    <h4 class="modal-title" id="lblmessage">Message &nbsp;</h4>
                        <textarea type="text" class="form-control" id="message" name="message" value="<?php echo $sms; ?>" style="height:33em" disabled><?php echo $sms; ?></textarea>

            </div>


                <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <!--
                    <input type="submit" value="Yes, Delete!" class="btn btn-danger">
                    -->
                </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
<!-- End View Modal -->

<!-- Reply Modal -->
<div class="modal fade" id="RelpySMS-<?php echo $uid; ?>" tabindex="-<?php echo $uid; ?>" role="dialog" aria-labelledby="myModalLabel-<?php echo $uid; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

                <div class="modal-header">
                        <span id="IL_AD4" class="IL_AD">
                    <a type="button" class="close glyphicon glyphicon-remove" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">

                        </span>
                    </a>
                    </span>

                        <h4 class="modal-title" id="myModalLabel-<?php echo $uid; ?>">
                            <span class="glyphicon glyphicon-envelope" aria-hidden="true" style = "color:#fff;"></span>
                            <span class="glyphicon glyphicon-share-alt" aria-hidden="true" style = "color:#fff;"></span>
                            Reply SMS
                        </h4>
                </div>

            <div class="modal-body" >


            <form action = "sendfunction.php" method="POST" >

                    <input type="hidden" class="form-control" name="From_Name" id="From_Name" value="<?php echo $from_name; ?>" style="width:120%;height:2em">
                    <input type="hidden" class="form-control" id="From_Email" name="From_Email" value="<?php echo $from_email; ?>" style="width:120%;height:2em">
                    <input type="hidden" class="form-control" id="To_Name" name="To_Name" value="<?php echo $to_name; ?>" style="width:120%;height:2em">
                    <input type="hidden" class="form-control" id="To_Email" name="To_Email" value="<?php echo $to_email; ?>" style="width:120%;height:2em">
                    <input type="hidden" class="form-control" id="cc_Email" name="cc_Email" value="<?php echo $cc_email; ?>" style="width:120%;height:2em">
                    <input type="hidden" class="form-control" id="bcc_Email" name="bcc_Email" value="<?php echo $bcc_email; ?>" style="width:120%;height:2em">

                    <label class="control-label" for="Message">Mobile :</label>

                    <select size="1" class="form-control" id="Subject" name="Subject">
                        <option value="0<?php echo $mob_no; ?>"><?php echo $subject; ?></option>
                    </select>

                    <br><br>
                    <label class="control-label" for="Message">Message :</label>
                    <textarea type="text" class="form-control" id="Message" name="Message" value="<?php echo $message; ?>" style="height:30em" required></textarea>


                <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                    <input type="submit" value="Send" name="submit" class="colrite btn btn-success pull-right">
                </div>
            </form>

            </div>
        </div>
    </div>
</div>
<!-- End Reply Modal -->

<!-- Forward Modal -->
<div class="modal fade" id="ForwardSMS-<?php echo $uid; ?>" tabindex="-<?php echo $uid; ?>" role="dialog" aria-labelledby="myModalLabel-<?php echo $uid; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

                <div class="modal-header">
                        <span id="IL_AD4" class="IL_AD">
                    <a type="button" class="close glyphicon glyphicon-remove" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">

                        </span>
                    </a>
                    </span>

                        <h4 class="modal-title" id="myModalLabel-<?php echo $uid; ?>">
                            <span class="glyphicon glyphicon-envelope" aria-hidden="true" style = "color:#fff;"></span>
                            <span class="glyphicon glyphicon-arrow-right" aria-hidden="true" style = "color:#fff;"></span>
                            Forward SMS
                        </h4>
                </div>

            <div class="modal-body" >


            <form action = "sendfunction.php" method="POST" >

                    <input type="hidden" class="form-control" name="From_Name" id="From_Name" value="<?php echo $from_name; ?>" style="width:120%;height:2em">
                    <input type="hidden" class="form-control" id="From_Email" name="From_Email" value="<?php echo $from_email; ?>" style="width:120%;height:2em">
                    <input type="hidden" class="form-control" id="To_Name" name="To_Name" value="<?php echo $to_name; ?>" style="width:120%;height:2em">
                    <input type="hidden" class="form-control" id="To_Email" name="To_Email" value="<?php echo $to_email; ?>" style="width:120%;height:2em">
                    <input type="hidden" class="form-control" id="cc_Email" name="cc_Email" value="<?php echo $cc_email; ?>" style="width:120%;height:2em">
                    <input type="hidden" class="form-control" id="bcc_Email" name="bcc_Email" value="<?php echo $bcc_email; ?>" style="width:120%;height:2em">

                    <label class="control-label" for="Message">Mobile :</label>
                    <div class="dropdown">
                            <input pattern="^09[0-9]{9}" title="11 digits and numbers only. Must start with 09" style="font-size: 14px;" maxlength="11" onkeypress="return /\d/.test(String.fromCharCode(((event||window.event).which||(event||window.event).which)));" type="text" id="Subject" name="Subject" placeholder='ENTER 09xxxxxxxxx or SELECT below' required />
                                <select onchange="this.previousElementSibling.value=this.value; this.previousElementSibling.focus()" size="1" class="form-control">
                                    <option value='09xxxxxxxxx'>SELECT HERE</option>

                                        <?php
                                        $u = CONF_DB_USER;
                                        $p = CONF_DB_PASS;
                                        $h = CONF_WEBHOST;
                                        $dbase = CONF_DB_NAME;
                                        $connect_db = mysql_connect($h, $u, $p) or die("Cannot connect to server");
                                        mysql_select_db($dbase,$connect_db) or die("Cannot connect to the database");

                                        $query = mysql_query("select id, concat(Lastname,', ',Firstname) as name, Number from phonebook order by name asc");

                                                            while ($row = mysql_fetch_array($query)) {
                                                        echo "<option value =".$row['Number'].">".$row['name']." &nbsp; < ".$row['Number']." ></option>";
                                                    }
                                        ?>
                                </select>
                    </div>

                    <br><br>
                    <label class="control-label" for="Message">Message :</label>
                    <textarea type="text" class="form-control" id="Message" name="Message" value="<?php echo $message; ?>" style="height:30em" required><?php echo $sms; ?></textarea>


                <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                    <input type="submit" value="Send" name="submit" class="colrite btn btn-success pull-right">
                </div>
            </form>

            </div>
        </div>
    </div>
</div>
<!-- End Forward Modal -->


<?php
}

//echo "Emails Read: $cnt";

?>
            </tbody>
        </table>
    </div>
</div>


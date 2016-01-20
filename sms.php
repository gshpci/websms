<?php
require 'header.php';
 ?>

<style type="text/css" media="screen">

    .dropdown {
        position: relative;
        width: 350px;
    }
    .dropdown select
    {
        width: 100%;
    }
    .dropdown > * {
        box-sizing: border-box;
        height: 1.5em;
    }
    .dropdown select {
    }
    .dropdown input {
        /* width: calc(100% - 20px); */
        padding: 0 2.5em 0 3em;
        width: 100%;
        color: black;
    }

</style>

<div class="container-fluid" id="cc">
<?php
    if (version_compare(PHP_VERSION, '5.0.0', '<')) {
        //echo 'Current PHP version: ' . phpversion() . "<br>";
        //echo exit("ERROR: Wrong PHP version. Must be PHP 5 or above.");
    }

    if (count($results_messages) > 0) {
        //echo '<h2>Run results</h2>';
        //echo '<ul>';
            foreach ($results_messages as $result) {
                //echo "<li>$result</li>";
                    }
                    //echo '</ul>';
                }

    if (isset($_POST["submit"]) && $_POST["submit"] == "Submit") {
        echo "<button type=\"submit\" onclick=\"startAgain();\">Start Over</button><br>\n";
        //echo "<br><span>Script:</span>\n";
        //echo "<pre class=\"brush: php;\">\n";
        //echo $example_code;
        //echo "\n</pre>\n";
        //echo "\n<hr style=\"margin: 3em;\">\n";
    }
?>

<form method="POST" enctype="multipart/form-data">
    <div class="col-md-6">
        <div class="container-fluid" id="conta1">
            <fieldset>
                <h5 style="color: white;">
                <blockquote>
                    <span class="glyphicon glyphicon-envelope">&nbsp;<strong>WebSMS</strong></span>
                </blockquote>


                    <div class="form-group has-info" style="display: none;">
                        <label class="control-label" for="From_Name">Sender :</label>
                            <input type="text" class="form-control" name="From_Name" id="From_Name" value="<?php echo $from_name; ?>" style="width:120%;height:2em">
                    </div>
                    <div class="form-group has-primary" style="display: none;">
                        <label class="control-label" for="Subject">email add</label>
                            <input type="text" class="form-control" id="From_Email" name="From_Email" value="<?php echo $from_email; ?>" style="width:120%;height:2em">
                    </div>
                    <div class="form-group has-primary"style="display: none;">
                        <label class="control-label" for="Message">Name</label>
                            <input type="text" class="form-control" id="To_Name" name="To_Name" value="<?php echo $to_name; ?>" style="width:120%;height:2em">
                    </div>
                    <div class="form-group has-primary"style="display: none;">
                        <label class="control-label" for="Subject">Email add</label>
                            <input type="text" class="form-control" id="To_Email" name="To_Email" value="<?php echo $to_email; ?>" style="width:120%;height:2em">
                    </div>
                    <div class="form-group has-primary"style="display: none;">
                        <label class="control-label" for="Message">cc</label>
                            <input type="text" class="form-control" id="cc_Email" name="cc_Email" value="<?php echo $cc_email; ?>" style="width:120%;height:2em">
                    </div>
                    <div class="form-group has-primary"style="display: none;">
                        <label class="control-label" for="Subject">bcc</label>
                            <input type="text" class="form-control" id="bcc_Email" name="bcc_Email" value="<?php echo $bcc_email; ?>" style="width:120%;height:2em">
                    </div>
                    <div class="form-group has-primary">
                        <label class="control-label" for="Message">Mobile :</label>
                        <div class="input-group">
                            <span class="input-group-addon" row="8">#</span>
                <!--
                            <input id = "mobileno" data-bind="value: ID, attr: {disabled: !$parent.IsEditMode()}" type="text" class="form-control" id="Subject" name="Subject" value="<?php echo $subject; ?>"  required>
                            -->
        <div class="dropdown">
                        <input pattern="^09[0-9]{9}" title="11 digits and numbers only. Must start with 09"  maxlength="11" onkeypress="return /\d/.test(String.fromCharCode(((event||window.event).which||(event||window.event).which)));" type="text" id="Subject" name="Subject" placeholder='ENTER 09xxxxxxxxx or SELECT below' required />
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
                </div>
                </div>
                <div class="form-group has-primary">
                    <label class="control-label" for="Message">Message :</label>
                        <textarea type="text" class="form-control" id="Message" name="Message" value="<?php echo $message; ?>" style="height:30em" required></textarea>
                </div>
                <button type="submit" value="submit" name="submit" class="colrite btn btn-success pull-right"><strong>Send</strong></button>
                <div style="margin:1em 0; display:none;">Test will include two attachments.</div></h5>
            </fieldset>
        </div>
    </div>

<!--MAIL TEST SPECS -->

        <div class="column-right">
            <fieldset class="inner" id="second"> <!-- SELECT TYPE OF MAIL -->
                <legend>Mail Test Specs</legend>
                <table border="hidden" class="column">
                    <tr>
                        <td class="colleft">Test Type</td>
                        <td class="colrite">
                            <div class="radio">
                                <label for="radio-mail">Mail()</label>
                                <input class="radio" type="radio" name="test_type" value="mail" id="radio-mail"
                                       onclick="showHideDiv(this.value, 'smtp-options-table');"
                                       <?php echo ($test_type == 'mail') ? 'checked' : ''; ?>
                                       required>
                            </div>
                            <div class="radio">
                                <label for="radio-sendmail">Sendmail</label>
                                <input class="radio" type="radio" name="test_type" value="sendmail" id="radio-sendmail"
                                       onclick="showHideDiv(this.value, 'smtp-options-table');"
                                       <?php echo ($test_type == 'sendmail') ? 'checked' : ''; ?>
                                       required>
                            </div>
                            <div class="radio">
                                <label for="radio-qmail">Qmail</label>
                                <input class="radio" type="radio" name="test_type" value="qmail" id="radio-qmail"
                                       onclick="showHideDiv(this.value, 'smtp-options-table');"
                                       <?php echo ($test_type == 'qmail') ? 'checked' : ''; ?>
                                       required>
                            </div>
                            <div class="radio">
                                <label for="radio-smtp">SMTP</label>
                                <input class="radio" type="radio" name="test_type" value="smtp" id="radio-smtp"
                                       onclick="showHideDiv(this.value, 'smtp-options-table');"
                                       <?php echo ($test_type == 'smtp') ? 'checked' : ''; ?>
                                       required>
                            </div>
                        </td>
                    </tr>
                </table>


<!-- SMPT SPECIFIC OPTIONS-->

                <div id="smtp-options-table" style="margin:1em 0 0 0;
                    <?php if ($test_type != 'smtp') {
                        echo "display: none;";
                    } ?>">
                    <span style="margin:1.25em 0; display:block;"><strong>SMTP Specific Options:</strong></span>
                    <table border="1" class="column">
                        <tr>
                            <td class="colleft"><label for="smtp_debug">SMTP Debug ?</label></td>
                            <td class="colrite">
                                <select size="1" id="smtp_debug" name="smtp_debug">
                                    <option <?php echo ($smtp_debug == '0') ? 'selected' : ''; ?> value="0">
                                        0 - Disabled
                                    </option>
                                    <option <?php echo ($smtp_debug == '1') ? 'selected' : ''; ?> value="1">
                                        1 - Client messages
                                    </option>
                                    <option <?php echo ($smtp_debug == '2') ? 'selected' : ''; ?> value="2">
                                        2 - Client and server messages
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="colleft"><label for="smtp_server">SMTP Server</label></td>
                            <td class="colrite">
                                    <input type="text" id="smtp_server" name="smtp_server"
                                       value="<?php echo $smtp_server; ?>" style="width:95%;"
                                      placeholder="smtp.gmail.com">
                            </td>
                        </tr>
                        <tr>
                            <td class="colleft" style="width: 5em;"><label for="smtp_port">SMTP Port</label></td>
                            <td class="colrite">
                                <input type="text" name="smtp_port" id="smtp_port" size="3"
                                       value="<?php echo $smtp_port; ?>" placeholder="587">
                            </td>
                        </tr>
                        <tr>
                            <td class="colleft"><label for="smtp_secure">SMTP Security</label></td>
                            <td>
                                <input type="text" name="smtp_secure" id="smtp_secure" size="3"
                                       value="<?php echo $smtp_secure; ?>" placeholder="SSL">
                            <?php
                            /*
                                <select size="1" name="smtp_secure" id="smtp_secure">
                                    <option <?php echo ($smtp_secure == 'none') ? 'selected' : '' ?>>None</option>
                                    <option <?php echo ($smtp_secure == 'tls') ? 'selected' : '' ?>>TLS</option>
                                   <option <?php echo ($smtp_secure == 'ssl') ? 'selected' : '' ?>>SSL</option>
                                </select>
                            */ ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="colleft"><label for="smtp-authenticate">SMTP Authenticate?</label></td>
                            <td class="colrite">
                                <input type="checkbox" id="smtp-authenticate"
                                       name="smtp_authenticate"
                <?php if ($smtp_authenticate != '') {
                    echo "checked";
                } ?>
                                       value="<?php echo $smtp_authenticate; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="colleft"><label for="authenticate_username">Authenticate Username</label></td>
                            <td class="colrite">
                                <input type="text" id="authenticate_username" name="authenticate_username"
                                       value="<?php echo $authenticate_username; ?>" style="width:95%;"
                                       placeholder="gshpci.adm@gmail.com">
                            </td>
                        </tr>
                        <tr>
                            <td class="colleft"><label for="authenticate_password">Authenticate Password</label></td>
                            <td class="colrite">
                                <input type="password" name="authenticate_password" id="authenticate_password"
                                       value="<?php echo $authenticate_password; ?>" style="width:95%;"
                                       placeholder="1R0nm4n14">
                            </td>
                        </tr>
                    </table>
                </div>
            </fieldset>
        </div>
</form>


        <div class="col-md-6">
            <div class="container-fluid" id="conta2">
                <fieldset>
                    <h5 style="color:white;"><blockquote>
                        <span class="glyphicon glyphicon-user">&nbsp;<strong>Phonebook</strong>
                    </blockquote>
                </fieldset>

            <!--Modal Trigger for Add Contact-->
                                <button type="button" data-toggle="modal" data-target="#myAddRecord" class="btn btn-success"> <strong>Add new record</strong> </button>
<!--End Modal Trigger for Add Contact-->

        <table id = "tableID" class="rwd-table" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr class = "listtr" >
                    <th class = "listtd" style="width: 15%; text-align: left; color: white;">Number</th>
                    <th class = "listtd" style="width: 45%; color: white;">Name</th>
                    <th class = "listtd" style="width: 40%; float: left; color: white;">Action</th>
                </tr>
            </thead>
        </table>

            <div id="myDIV">
                    <table id = "tableID" class="rwd-table">
<?php
                    $result = mysql_query("SELECT id,`Number`, concat(Lastname,', ',Firstname) as Name, Lastname, Firstname, Middlename FROM phonebook p order by name asc;");

                    //fetch tha data from the database

                    while ($row = mysql_fetch_array($result)) {
                        //display the results
?>
                <tbody>
                    <tr class = "listtr" id="scr">
                        <td class = "listtd" style="width: 15%"><?php echo $row['Number']; ?></td>
                        <td class = "listtd" style="width: 45%;"><?php echo $row['Name']; ?></td>
                        <td class = "listtd" style="width: 40;">
                            <div style="width: 100%; float:right;" >
                                <input id = 'pid' type='hidden' class='form-control' name='pid' value="<?php echo $row['id']; ?>" required/>
                                &nbsp;
                                <button style="text-decoration:none;cursor:pointer;" class = "btn btn-success" data-toggle="modal" data-target="#EditData-<?php echo $row['id']; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true" style = "color:#ffffff;"></span> Edit</button >
                                &nbsp;&nbsp;
                                <?php
                                    if($from_name == "Admin"){
                                ?>
                                    <button style="text-decoration:none;cursor:pointer;" class = "btn btn-danger" data-toggle="modal" data-target="#DeleteData-<?php echo $row['id']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true" style = "color:#ffffff;"></span> Delete</button >
                                <?php }?>
                            </div>
                        </td>
                    </tr>

<!-- Edit Modal -->
<div class="modal fade" id="EditData-<?php echo $row['id']; ?>" tabindex="-<?php echo $row['id']; ?>" role="dialog" aria-labelledby="myModalLabel-<?php echo $row['id']; ?>" aria-hidden="false">
      <div class="modal-dialog">
    <div class="modal-content">

            <div class="modal-header">
                    <span id="IL_AD4" class="IL_AD">
                <a  href="sms.php"  type="button" class="close glyphicon glyphicon-remove" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">

                    </span>
                </a>
                </span>

                    <h4 class="modal-title" id="myModalLabel-<?php echo $row['id']; ?>">Update Contact ID#&nbsp;<?php echo $row['id']; ?></h4>
            </div>
        <center>
            <div class="modal-body" style="width:50%;">
            <form action="edit.php" method="post">
                <input type="hidden" id="id" name="id" value="<?php echo $row['id']; ?>">
                <input type="hidden" id="Modal" name="Modal" value="EditData-<?php echo $row['id']; ?>">
                <input type="hidden" id="action" name="action" value="Edit Contact - <?php echo $row['Name']; ?>">
                <input type="hidden" id="station" name="station" value="<?php echo $from_name; ?>">
                <br>
                <label for="ln">Lastname *</label>
                <input type="text" class="form-control" id="name<?php echo $row['Lastname']; ?>" name="lName" value="<?php echo $row['Lastname']; ?>" required >
                <br>
                <label for="ln">Firstname *</label>
                <input type="text" class="form-control" id="name<?php echo $row['Firstname']; ?>" name="fName" value="<?php echo $row['Firstname']; ?>" required >
                <br>
                <label for="ln">Midddlename</label>
                <input type="text" class="form-control" id="name<?php echo $row['Middlename']; ?>" name="mName" value="<?php echo $row['Middlename']; ?>">
                <br>
                <label for="age">Number (11-Digit Format: 09XXXXXXXXX) *</label>
                <input pattern="^09[0-9]{9}" title="11 digits and numbers only. Must start with 09"  maxlength="11" onkeypress="return /\d/.test(String.fromCharCode(((event||window.event).which||(event||window.event).which)));" type="text" class="form-control" id="number<?php echo $row['Number']; ?>" name="Number" value="<?php echo $row['Number']; ?>" required >
                <br>
                              * - Required Field.
            </div>
        </center>
            <div class="modal-footer">
                    <a href="sms.php" type="button" class="btn btn-default" data-dismiss="modal">Close</a>

                <input type="submit" value="Save changes"  class="btn btn-primary" >
            </div>
        </form>
     </div>
    </div>
</div>
<!-- End Edit Modal -->


<!-- Delete Modal -->
<div class="modal fade" id="DeleteData-<?php echo $row['id']; ?>" tabindex="-<?php echo $row['id']; ?>" role="dialog" aria-labelledby="myModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
      <div class="modal-dialog">
    <div class="modal-content">

            <div class="modal-header">
                    <span id="IL_AD4" class="IL_AD">
                <a type="button" class="close glyphicon glyphicon-remove" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">

                    </span>
                </a>
                </span>

                    <h4 class="modal-title" id="myModalLabel-<?php echo $row['id']; ?>">Do you really want to delete this contact?</h4>
            </div>
        <center>
        <div class="modal-body" >
            <form action="delete.php" method="post">
                <input type="hidden" id="id" name="id" value="<?php echo $row['id']; ?>">
                <input type="hidden" id="action" name="action" value="Delete Contact - <?php echo $row['Name']; ?>">
                <input type="hidden" id="station" name="station" value="<?php echo $from_name; ?>">

                <h4 class="modal-title" id="myModalLabel-<?php echo $row['id']; ?>"> <?php echo $row['Name']; ?> - &nbsp;<?php echo $row['Number']; ?>  </h4>


        </div>
        </center>

            <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <input type="submit" value="Yes, Delete!" class="btn btn-danger">
            </div>
            </div>
        </form>
     </div>
    </div>
</div>

<!-- End Delete Modal -->

<?php
                    }

                    //close the connection
                    //mysql_close($dbhandle);
?>

        </tbody>
           </table></div>



    <script type="text/javascript">
    window.onload= function(){
        document.getElementById('tableID').onclick= function(e){
            e= e || window.event;
            var who= e.target || e.srcElement;
            if(who.tagName== 'TD'){

                var elem = document.getElementById("mobileno");
                //if(!(substr(who.innerText,0,1) == '0') || !(substr(who.innerText,0,1) == '+')){
                //elem.value = '';
                //}else{
                elem.value = who.textContent || who.innerText || '';
                //}

            }
        }
    }
    </script>
            </div>
        </div>
<!--Modal for Add Contact-->
        <div class="modal fade" id="myAddRecord" tabindex="-1" role="dialog" aria-labelledby="myModalLabe">
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
                                            <input pattern="^09[0-9]{9}" title="11 digits and numbers only. Must start with 09"  maxlength="11" onkeypress="return /\d/.test(String.fromCharCode(((event||window.event).which||(event||window.event).which)));" type="text" class="form-control" name="Number" style="width:50%;height:2em" required>
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

</body>

</html>

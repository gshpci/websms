<?php
require 'header.php';
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

  <div class="container-fluid" id="cc">
    <fieldset>
        <h5 style="color:white;"><blockquote>
            <span class="glyphicon glyphicon-envelope">&nbsp;<strong>Sent Items</strong>
        </blockquote>
    </fieldset>

<?php

$u = CONF_DB_USER;
$p = CONF_DB_PASS;
$h = CONF_WEBHOST;
$dbase = CONF_DB_NAME;

$dbhandle = mysql_connect($h, $u, $p) or die("Unable to connect to MySQL");
$selected = mysql_select_db($dbase,$dbhandle) or die("Could not select examples");

if ($from_name == 'Admin') {
    $sql = "SELECT message, id, datetime, mobile_no, station, remarks FROM sms s order by datetime desc  limit 50";
} else {
    //$sql = "SELECT message, id, datetime, mobile_no, station, remarks FROM sms s where station = '{$from_name}' order by datetime desc limit 15" ;
    $sql = "SELECT message, id, datetime, mobile_no, station, remarks FROM sms s where station = '{$from_name}' order by datetime desc limit 50" ;
}

$result = mysql_query($sql);
?>
  <table class="rwd-table" style="width: 100%;">
    <thead>
      <tr class="listtr">
        <th class="listtd" style="text-align: center" width="5%">Station</th>
        <th class="listtd" style="text-align: center" width="12%">Datetime</th>
        <th class="listtd" style="text-align: center" width="24%">Recipient</th>
        <th class="listtd" style="text-align: center" width="36%">Message</th>
        <th class="listtd" style="text-align: center" width="25%">Status</th>
      </tr>
    </thead>
  </table>

  <div id='myDIV' style='height: 520px;'>
    <table class='rwd-table' style='width: 100%;'>

<?php
    while ($row = mysql_fetch_array($result)) {
      echo "<tbody data-bind='foreach: Items' >
                <tr class = 'listtr'>
                  <td class = 'listtd1' style='width: 5%;'>".$row{'station'}."</td>
                  <td class = 'listtd1' style='width: 12%;'>".$row{'datetime'}."</td>";

                  $mobile = $row{'mobile_no'};
                  $q = mysql_query("select concat(Lastname,', ', Firstname) as name from phonebook where Number = $mobile");

                  if ($r = mysql_fetch_array($q)) {
                    $to = $r['name']." <".$mobile.">";
                  } else {
                    $to = "UNKNOWN <".$mobile.">";
                  }

            echo "<td class = 'listtd1' style='width: 25%;'>".$to."</td>
                  <td class = 'listtd1' style='width: 36%;'>";
        ?>
                      <div style="width: 100%; float:right;" >
                <?php
                      $sms = $row{'message'};
                      if (strlen($sms) > 50) {
                        echo substr($sms, 0, 50)."<span style='font-size:14 px; color:red;'> ... (trunc). </span>";
                ?>
                        <button style="text-decoration:none;cursor:pointer;" class = "btn btn-info" data-toggle="modal" data-target="#ViewSMS-<?php echo $row['id']; ?>">
                          <span class="glyphicon glyphicon-eye-open" aria-hidden="true" style = "color:#fff;"></span>
                          View
                        </button >

                <?php } else {
                        echo substr($sms, 0, 50);
                      }
                ?>
                      </div>
                  </td>
                  <td class = "listtd1"><?php echo $row{'remarks'} ?> &nbsp;&nbsp;
                    <button style="text-decoration:none;cursor:pointer;" class = "btn btn-info" data-toggle="modal" data-target="#ForwardSMS-<?php echo $row['id']; ?>">
                        <span class="glyphicon glyphicon-envelope" aria-hidden="true" style = "color:#fff;"></span>
                        <span class="glyphicon glyphicon-arrow-right" aria-hidden="true" style = "color:#fff;"></span>
                        Forward
                    </button >
                  </td>
              </tr>

        <!-- View Modal -->
        <div class="modal fade" id="ViewSMS-<?php echo $row['id']; ?>" tabindex="-<?php echo $row['id']; ?>" role="dialog" aria-labelledby="myModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                  <span id="IL_AD4" class="IL_AD">
                    <a type="button" class="close glyphicon glyphicon-remove" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true"></span>
                    </a>
                  </span>
                  <h4 class="modal-title" id="myModalLabel-<?php echo $row['id']; ?>">SMS Details</h4>
                </div>

                <div class="modal-body" >
                  <form action="delete.php" method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo $row['id']; ?>">
                    <h4 class="modal-title" id="lblmessage">Message &nbsp;</h4>
                    <textarea type="text" class="form-control" id="message" name="message" value="<?php echo $row['message']; ?>" style="height:33em" disabled><?php echo $row['message']; ?></textarea>
                  </form>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
          </div>
        </div>
        <!-- End View Modal -->

        <!-- Forward Modal -->
        <div class="modal fade" id="ForwardSMS-<?php echo $row['id']; ?>" tabindex="-<?php echo $row['id']; ?>" role="dialog" aria-labelledby="myModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                      <span id="IL_AD4" class="IL_AD">
                        <a type="button" class="close glyphicon glyphicon-remove" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </a>
                      </span>
                      <h4 class="modal-title" id="myModalLabel-<?php echo $row['id']; ?>">
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

    mysql_close($dbhandle);
?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>

<?php
require 'header.php';
 ?>


<div class="container-fluid" id="cc">
    <fieldset>
        <h5 style="color:white;"><blockquote>
            <span class="glyphicon glyphicon-envelope">&nbsp;<strong>Sent Items</strong>
        </blockquote>
    </fieldset>

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


            <?php
                    $u = CONF_DB_USER;
                    $p = CONF_DB_PASS;
                    $h = CONF_WEBHOST;
                    $dbase = CONF_DB_NAME;

                    //connection to the database
                    $dbhandle = mysql_connect($h, $u, $p)
                     or die("Unable to connect to MySQL");
                    //echo "Connected to MySQL<br>";

                    //select a database to work with
                    $selected = mysql_select_db($dbase,$dbhandle)
                      or die("Could not select examples");

                    //execute the SQL query and return records

      if($from_name == 'Admin'){
        $sql = "SELECT message, id, datetime, mobile_no, station, remarks FROM sms s order by datetime desc  limit 15";
      }else{
        //$sql = "SELECT message, id, datetime, mobile_no, station, remarks FROM sms s where station = '{$from_name}' order by datetime desc limit 15" ;
        $sql = "SELECT message, id, datetime, mobile_no, station, remarks FROM sms s order by datetime desc limit 50" ;
      }

        $result = mysql_query($sql);

        echo "<table class='rwd-table'>
              <thead>";
                echo "<tr class = 'listtr'>
                         <th class = 'listtd' style='text-align: left'>Station</th>
                         <th class = 'listtd' style='text-align: left'>Datetime</th>
                         <th class = 'listtd' style='text-align: left'>Mobile</th>
                         <th class = 'listtd' style='text-align: left'>Message</th>
                         <th class = 'listtd' style='text-align: left'>Remarks</td>
                      </tr>
              </thead>";

        while ($row = mysql_fetch_array($result)) {
          echo "<tbody data-bind='foreach: Items' >
                <tr class = 'listtr'>
                  <td class = 'listtd1'>".$row{'station'}."</td>
                  <td class = 'listtd1' style='width: 12%;'>".$row{'datetime'}."</td>";

                  $mobile = $row{'mobile_no'};
                  $q = mysql_query("select concat(Lastname,', ', Firstname) as name from phonebook where Number = $mobile");

                  if ($r = mysql_fetch_array($q)) {
                    $to = $r['name']." <".$mobile.">";
                  } else {
                    $to = "UNKNOWN <".$mobile.">";
                  }

          echo " <td class = 'listtd1' style='width: 25%;'>".$to."</td>
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
<?php
            echo "<td class = 'listtd1'>".$row{'remarks'}."</td>";
?>
                </tr>

<!-- View Modal -->
<div class="modal fade" id="ViewSMS-<?php echo $row['id']; ?>" tabindex="-<?php echo $row['id']; ?>" role="dialog" aria-labelledby="myModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
      <div class="modal-dialog">
  <div class="modal-content">

        <div class="modal-header">
            <span id="IL_AD4" class="IL_AD">
        <a type="button" class="close glyphicon glyphicon-remove" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">

          </span>
        </a>
        </span>

            <h4 class="modal-title" id="myModalLabel-<?php echo $row['id']; ?>">SMS Details</h4>
        </div>

      <div class="modal-body" >
      <form action="delete.php" method="post">
            <input type="hidden" id="id" name="id" value="<?php echo $row['id']; ?>">
              <!--
        <h4 class="modal-title" id="lblstation">Station: &nbsp;</h4>
          <input type="text" class="form-control" id="station" name="station" placeholder="<?php echo $row['station']; ?>" disabled>
          <h4 class="modal-title" id="lbldatetime">Datetime: &nbsp;</h4>
          <input type="text" class="form-control" id="datetime" name="datetime" placeholder="<?php echo $row['datetime']; ?>" disabled>
        <h4 class="modal-title" id="lblmobileno">Receiver: &nbsp;</h4>
          <input type="text" class="form-control" id="mobileno" name="mobileno" placeholder="<?php echo $row['mobile_no']; ?>" disabled>
        -->
        <h4 class="modal-title" id="lblmessage">Message &nbsp;</h4>
          <textarea type="text" class="form-control" id="message" name="message" value="<?php echo $row['message']; ?>" style="height:33em" disabled><?php echo $row['message']; ?></textarea>
        <!--
        <h4 class="modal-title" id="lblremarks">Remarks:
            <span style="font-size:20px;"><?php echo $row['remarks']; ?></span>
        </h4>
        -->

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
<!-- End View Modal -->


<?php
                    }


                    //close the connection
                    mysql_close($dbhandle);
                    ?>
           </tbody>
           </table>
    </div>
</body>
</html>

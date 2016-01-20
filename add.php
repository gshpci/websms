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

$modal = ($_POST['Modal']);

$lName = ($_POST['lName']);
$fName = ($_POST['fName']);
$mName = ($_POST['mName']);
$Number = ($_POST['Number']);
//$id    = ($_POST['id']);

$sql = "INSERT INTO logstrigger(Station, `action`, datetime) VALUES ('{$station}', Concat('Attempting ','{$action}'), concat(curdate(),' ',curtime()))";
mysql_query($sql);
?>


<?php require 'sms.php'?>
<!-- Notification Modal -->
<div class="modal fade" id="Notification" tabindex="alert" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
    <div class="modal-content">

            <div class="modal-header">
                    <span id="IL_AD4" class="IL_AD">
                <a type="button" class="close glyphicon glyphicon-remove" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">

                    </span>
                </a>
                </span>

                    <h4 class="modal-title" id="myModalLabel-Notification ">INFORMATION MESSAGE</h4>
            </div>
        <center>
        <div class="modal-body" >
            <form action="delete.php" method="post">
                <input type="hidden" id="id" name="id" value="Notification ">

                <h4 class="modal-title" id="myModalLabel-<?php echo $row['id']; ?>"> Invalid format of number.  </h4>


        </div>
        </center>

            <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>              </div>
            </div>
        </form>
     </div>
    </div>
</div>

<!-- End Notification Modal -->

<?php

$action = $_POST['action'];
$station = $_POST['station'];

$modal = ($_POST['Modal']);

$lName = ($_POST['lName']);
$fName = ($_POST['fName']);
$mName = ($_POST['mName']);

$Number = ($_POST['Number']);


if ($lName=="" || $fName=="" || $Number=="" ) {
    echo "<script>alert('Missing value in any of the required fields!');</script>";
} else {
    if (substr($Number, 0, 2) == "09") {
        if (strlen($Number) != 11) {
            echo "<script>alert('Invalid Format of number!');";
            echo "$('#".$modal."').modal({";

?>

                backdrop: 'static',
                keyboard: false
            })
            </script>

<?php
        return;
    }
}

if(substr($Number, 0, 4) == "+639"){
    if(strlen($Number) != 13){

        echo "<script>alert('Invalid Format of number!');";
        echo "$('#".$modal."').modal({";
?>
            backdrop: 'static',
            keyboard: false
        })
        </script>

<?php
        return;
    }
}

if( substr($Number, 0, 4) == "0817" ||
    substr($Number, 0, 4) == "0905" ||
    substr($Number, 0, 4) == "0906" ||
    substr($Number, 0, 4) == "0915" ||
    substr($Number, 0, 4) == "0916" ||
    substr($Number, 0, 4) == "0917" ||
    substr($Number, 0, 4) == "0925" ||
    substr($Number, 0, 4) == "0926" ||
    substr($Number, 0, 4) == "0927" ||
    substr($Number, 0, 4) == "0935" ||
    substr($Number, 0, 4) == "0936" ||
    substr($Number, 0, 4) == "0937" ||
    substr($Number, 0, 4) == "0975" ||
    substr($Number, 0, 4) == "0977" ||
    substr($Number, 0, 4) == "0994" ||
    substr($Number, 0, 4) == "0996" ||
    substr($Number, 0, 4) == "0997" ||
    substr($Number, 0, 6) == "+63817" ||
    substr($Number, 0, 6) == "+63905" ||
    substr($Number, 0, 6) == "+63906" ||
    substr($Number, 0, 6) == "+63915" ||
    substr($Number, 0, 6) == "+63916" ||
    substr($Number, 0, 6) == "+63917" ||
    substr($Number, 0, 6) == "+63925" ||
    substr($Number, 0, 6) == "+63926" ||
    substr($Number, 0, 6) == "+63927" ||
    substr($Number, 0, 6) == "+63935" ||
    substr($Number, 0, 6) == "+63936" ||
    substr($Number, 0, 6) == "+63937" ||
    substr($Number, 0, 6) == "+63975" ||
    substr($Number, 0, 6) == "+63977" ||
    substr($Number, 0, 6) == "+63994" ||
    substr($Number, 0, 6) == "+63996" ||
    substr($Number, 0, 6) == "+63997"||
    /* SMART */
    substr($Number, 0, 4) == "0813" ||
    substr($Number, 0, 4) == "0900" ||
    substr($Number, 0, 4) == "0907" ||
    substr($Number, 0, 4) == "0908" ||
    substr($Number, 0, 4) == "0909" ||
    substr($Number, 0, 4) == "0910" ||
    substr($Number, 0, 4) == "0911" ||
    substr($Number, 0, 4) == "0912" ||
    substr($Number, 0, 4) == "0918" ||
    substr($Number, 0, 4) == "0919" ||
    substr($Number, 0, 4) == "0920" ||
    substr($Number, 0, 4) == "0921" ||
    substr($Number, 0, 4) == "0928" ||
    substr($Number, 0, 4) == "0929" ||
    substr($Number, 0, 4) == "0930" ||
    substr($Number, 0, 4) == "0931" ||
    substr($Number, 0, 4) == "0938" ||
    substr($Number, 0, 4) == "0939" ||
    substr($Number, 0, 4) == "0940" ||
    substr($Number, 0, 4) == "0946" ||
    substr($Number, 0, 4) == "0947" ||
    substr($Number, 0, 4) == "0948" ||
    substr($Number, 0, 4) == "0949" ||
    substr($Number, 0, 4) == "0971" ||
    substr($Number, 0, 4) == "0980" ||
    substr($Number, 0, 4) == "0989" ||
    substr($Number, 0, 4) == "0998" ||
    substr($Number, 0, 4) == "0999" ||
    substr($Number, 0, 6) == "+63813" ||
    substr($Number, 0, 6) == "+630900" ||
    substr($Number, 0, 6) == "+630907" ||
    substr($Number, 0, 6) == "+630908" ||
    substr($Number, 0, 6) == "0909" ||
    substr($Number, 0, 6) == "0910" ||
    substr($Number, 0, 6) == "+63911" ||
    substr($Number, 0, 6) == "+63912" ||
    substr($Number, 0, 6) == "+63918" ||
    substr($Number, 0, 6) == "+63919" ||
    substr($Number, 0, 6) == "+63920" ||
    substr($Number, 0, 6) == "+63921" ||
    substr($Number, 0, 6) == "+63928" ||
    substr($Number, 0, 6) == "+63929" ||
    substr($Number, 0, 6) == "+63930" ||
    substr($Number, 0, 6) == "+63931" ||
    substr($Number, 0, 6) == "+63938" ||
    substr($Number, 0, 6) == "+63939" ||
    substr($Number, 0, 6) == "+63940" ||
    substr($Number, 0, 6) == "+63946" ||
    substr($Number, 0, 6) == "+63947" ||
    substr($Number, 0, 6) == "+63948" ||
    substr($Number, 0, 6) == "+63949" ||
    substr($Number, 0, 6) == "+63971" ||
    substr($Number, 0, 6) == "+63980" ||
    substr($Number, 0, 6) == "+63989" ||
    substr($Number, 0, 6) == "+63998" ||
    substr($Number, 0, 6) == "+63999" ||


    /* SUN */
    substr($Number, 0, 4) == "0922" ||
    substr($Number, 0, 4) == "0923" ||
    substr($Number, 0, 4) == "0925" ||
    substr($Number, 0, 4) == "0932" ||
    substr($Number, 0, 4) == "0933" ||
    substr($Number, 0, 4) == "0934" ||
    substr($Number, 0, 4) == "0942" ||
    substr($Number, 0, 4) == "0943" ||
    substr($Number, 0, 4) == "0944" ||

    substr($Number, 0, 4) == "+63922" ||
    substr($Number, 0, 4) == "+63923" ||
    substr($Number, 0, 4) == "+63925" ||
    substr($Number, 0, 4) == "+63932" ||
    substr($Number, 0, 4) == "+63933" ||
    substr($Number, 0, 4) == "+63934" ||
    substr($Number, 0, 4) == "+63942" ||
    substr($Number, 0, 4) == "+63943" ||
    substr($Number, 0, 4) == "+63944"){

    $query = mysql_query("select * from phonebook where Number = '$Number'");

        if ($row = mysql_fetch_array($query)) {
            echo "<script>alert('Number already exists!');";
            echo "$('#".$modal."').modal({";
?>
                backdrop: 'static',
                keyboard: false
            })

            </script>

<?php       return;
        } else {

            $sql = "INSERT INTO logstrigger(Station, `action`, datetime) VALUES ('{$station}', concat('{$action}',' - ','{$lName}',', ','{$fName}',' ','{$mName}') , concat(curdate(),' ',curtime()))";
            mysql_query($sql);

            mysql_query("insert phonebook set lastname='$lName', firstname='$fName', middlename='$mName', Number='$Number'");
            echo "<script>alert('Record successfuly saved.');window.location.href='sms.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid Format of Number.');</script>";
    }
}

?>


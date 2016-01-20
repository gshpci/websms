<?php
/*
 * A web form that both generates and uses PHPMailer code.
 * revised, updated and corrected 27/02/2013
 * by matt.sturdy@gmail.com
 */
require_once 'PHPMailerAutoload.php';
require_once 'config.inc.php';

$sel_id = '';
$sel_Name = '';
$sel_Number = '';
$u = CONF_DB_USER;
$p = CONF_DB_PASS;
$h = CONF_WEBHOST;
$dbase = CONF_DB_NAME;

//connection to the database
$conn = mysql_connect($h, $u, $p)
 or die("Unable to connect to MySQL");
//echo "Connected to MySQL<br>";

mysql_select_db($dbase);
//select a database to work with
$selected = mysql_select_db($dbase, $conn)
  or die("Could not select examples");

$sql = "select *, timestampdiff(second, datetime,  concat(curdate(),' ', curtime())) from logstrigger order by datetime desc limit 1";

//execute the SQL query and return records
$result = mysql_query("$sql");

//fetch tha data from the database
if ($row = mysql_fetch_array($result)) {
    if ($row['action'] == "Logout") {
        echo "<script>window.location.href='index.php';</script>";
    } else {

        $sql = "SELECT station from logstrigger order by `datetime` desc limit 1";
        $result = mysql_query("$sql");

        //fetch tha data from the database
        if ($row = mysql_fetch_array($result)) {
            $from_name = (isset($_POST['From_Name'])) ? $_POST['From_Name'] : $row{'station'};
        }
    }

} else {
    $sql = "SELECT station from logstrigger order by `datetime` desc limit 1";
    $result = mysql_query("$sql");

    if ($row = mysql_fetch_array($result)) {
        $from_name = (isset($_POST['From_Name'])) ? $_POST['From_Name'] : $row{'station'};
    }
}

$CFG['From_Email'] = CONFG_FROMEMAIL;
$CFG['To_Name'] = CONFG_TONAME;
$CFG['To_Email'] = CONFG_TOEMAIL;
$CFG['cc_Email'] = CONFG_CCEMAIL;

$CFG['smtp_debug'] = 1; //0 == off, 1 for client output, 2 for client and server
$CFG['smtp_debugoutput'] = 'html';
$CFG['smtp_server'] = CONF_WEBHOST;
$CFG['smtp_port'] = CONFG_PORT;
$CFG['smtp_authenticate'] = true;
$CFG['smtp_username'] = CONFG_USERNAME;
$CFG['smtp_password'] = CONFG_PASSWORD;
$CFG['smtp_secure'] = 'SSL';

$from_email = (isset($_POST['From_Email'])) ? $_POST['From_Email'] : $CFG['From_Email'];
$to_name = (isset($_POST['To_Name'])) ? $_POST['To_Name'] : $CFG['To_Name'];
$to_email = (isset($_POST['To_Email'])) ? $_POST['To_Email'] : $CFG['To_Email'];
$cc_email = (isset($_POST['cc_Email'])) ? $_POST['cc_Email'] : $CFG['cc_Email'];
$bcc_email = (isset($_POST['bcc_Email'])) ? $_POST['bcc_Email'] : '';
$subject = (isset($_POST['Subject'])) ? $_POST['Subject'] : '';
$message = (isset($_POST['Message'])) ? $_POST['Message'] : '';
$test_type = (isset($_POST['test_type'])) ? $_POST['test_type'] : 'smtp';
$smtp_debug = (isset($_POST['smtp_debug'])) ? $_POST['smtp_debug'] : $CFG['smtp_debug'];
$smtp_server = (isset($_POST['smtp_server'])) ? $_POST['smtp_server'] : $CFG['smtp_server'];
$smtp_port = (isset($_POST['smtp_port'])) ? $_POST['smtp_port'] : $CFG['smtp_port'];
$smtp_secure = strtoupper((isset($_POST['smtp_secure'])) ? $_POST['smtp_secure'] : $CFG['smtp_secure']);
$smtp_authenticate = (isset($_POST['smtp_authenticate'])) ?
    $_POST['smtp_authenticate'] : $CFG['smtp_authenticate'];
$authenticate_password = (isset($_POST['authenticate_password'])) ?
    $_POST['authenticate_password'] : $CFG['smtp_password'];
$authenticate_username = (isset($_POST['authenticate_username'])) ?
    $_POST['authenticate_username'] : $CFG['smtp_username'];

// storing all status output from the script to be shown to the user later
$results_messages = array();

// $example_code represents the "final code" that we're using, and will
// be shown to the user at the end.
$smsremarks = "Message Sent";
$example_code = '';

$mail = new PHPMailer(true);  //PHPMailer instance with exceptions enabled
$mail->CharSet = 'utf-8';
ini_set('default_charset', 'UTF-8');
$mail->Debugoutput = $CFG['smtp_debugoutput'];

class phpmailerAppException extends phpmailerException
{
}

try {
    if (isset($_POST["submit"]) && $_POST['submit'] == "submit") {
        $to = $_POST['To_Email'];
        if (!PHPMailer::validateAddress($to)) {
            throw new phpmailerAppException("Email address " . $to . " is invalid -- aborting!");
        }

        switch ($_POST['test_type']) {
            case 'smtp':
                $mail->isSMTP(); // telling the class to use SMTP
                $mail->SMTPDebug = (integer)$_POST['smtp_debug'];
                $mail->Host = $_POST['smtp_server']; // SMTP server
                $mail->Port = (integer)$_POST['smtp_port']; // set the SMTP port
                if ($_POST['smtp_secure']) {
                    $mail->SMTPSecure = strtolower($_POST['smtp_secure']);
                }
                $mail->SMTPAuth = array_key_exists('smtp_authenticate', $_POST); // enable SMTP authentication?
                if (array_key_exists('smtp_authenticate', $_POST)) {
                    $mail->Username = $_POST['authenticate_username']; // SMTP account username
                    $mail->Password = $_POST['authenticate_password']; // SMTP account password
                }

                break;

            default:
                throw new phpmailerAppException('Invalid test_type provided');
        }

        try {
            if ($_POST['From_Name'] != '') {
                $mail->addReplyTo($_POST['From_Email'], $_POST['From_Name']);
                $mail->setFrom($_POST['From_Email'], $_POST['From_Name']);

            } else {
                $mail->addReplyTo($_POST['From_Email']);
                $mail->setFrom($_POST['From_Email'], $_POST['From_Email']);

            }

            if ($_POST['To_Name'] != '') {
                $mail->addAddress($to, $_POST['To_Name']);

            } else {
                $mail->addAddress($to);

            }

        } catch (phpmailerException $e) { //Catch all kinds of bad addressing
            throw new phpmailerAppException($e->getMessage());
        }

        $mail->Subject = $_POST['Subject'] ;

        //body
        if ($_POST['Message'] == '') {
            echo "You are sending an empty SMS";
            $body = 'Empty SMS.'."\n\n -GSHPCI ". $_POST['From_Name'];
            //$body = file_get_contents('contents.html');
        } else {
            $body = $_POST['Message'] ."\n\n -GSHPCI ". $_POST['From_Name'];
        }


        if( substr($_POST['Subject'], 0, 4) == "0977" ||
            substr($_POST['Subject'], 0, 4) == "0905" ||
            substr($_POST['Subject'], 0, 4) == "0906" ||
            substr($_POST['Subject'], 0, 4) == "0915" ||
            substr($_POST['Subject'], 0, 4) == "0916" ||
            substr($_POST['Subject'], 0, 4) == "0917" ||
            substr($_POST['Subject'], 0, 4) == "0925" ||
            substr($_POST['Subject'], 0, 4) == "0926" ||
            substr($_POST['Subject'], 0, 4) == "0927" ||
            substr($_POST['Subject'], 0, 4) == "0935" ||
            substr($_POST['Subject'], 0, 4) == "0936" ||
            substr($_POST['Subject'], 0, 4) == "0937" ||
            substr($_POST['Subject'], 0, 4) == "0996" ||
            substr($_POST['Subject'], 0, 4) == "0997" ){

            $mail->Subject = '500:eimr-port:11-'.$_POST['Subject'] ;

        }else if(substr($_POST['Subject'], 0, 6) == "+63905" ||
            substr($_POST['Subject'], 0, 6) == "+63906" ||
            substr($_POST['Subject'], 0, 6) == "+63915" ||
            substr($_POST['Subject'], 0, 6) == "+63916" ||
            substr($_POST['Subject'], 0, 6) == "+63917" ||
            substr($_POST['Subject'], 0, 6) == "+63925" ||
            substr($_POST['Subject'], 0, 6) == "+63926" ||
            substr($_POST['Subject'], 0, 6) == "+63927" ||
            substr($_POST['Subject'], 0, 6) == "+63935" ||
            substr($_POST['Subject'], 0, 6) == "+63936" ||
            substr($_POST['Subject'], 0, 6) == "+63937" ||
            substr($_POST['Subject'], 0, 6) == "+63996" ||
            substr($_POST['Subject'], 0, 6) == "+63997" ){

            $mail->Subject = '500:eimr-port:11-09'.substr($_POST['Subject'], 4, 12);

        }else if(substr($_POST['Subject'], 0, 4) == "0907" ||
            substr($_POST['Subject'], 0, 4) == "0908" ||
            substr($_POST['Subject'], 0, 4) == "0909" ||
            substr($_POST['Subject'], 0, 4) == "0910" ||
            substr($_POST['Subject'], 0, 4) == "0912" ||
            substr($_POST['Subject'], 0, 4) == "0918" ||
            substr($_POST['Subject'], 0, 4) == "0919" ||
            substr($_POST['Subject'], 0, 4) == "0920" ||
            substr($_POST['Subject'], 0, 4) == "0921" ||
            substr($_POST['Subject'], 0, 4) == "0928" ||
            substr($_POST['Subject'], 0, 4) == "0929" ||
            substr($_POST['Subject'], 0, 4) == "0930" ||
            substr($_POST['Subject'], 0, 4) == "0938" ||
            substr($_POST['Subject'], 0, 4) == "0939" ||
            substr($_POST['Subject'], 0, 4) == "0942" ||
            substr($_POST['Subject'], 0, 4) == "0943" ||
            substr($_POST['Subject'], 0, 4) == "0946" ||
            substr($_POST['Subject'], 0, 4) == "0948" ||
            substr($_POST['Subject'], 0, 4) == "0989" ||
            substr($_POST['Subject'], 0, 4) == "0939" ||
            substr($_POST['Subject'], 0, 4) == "0922" ||
            substr($_POST['Subject'], 0, 4) == "0923" ||
            substr($_POST['Subject'], 0, 4) == "0932" ||
            substr($_POST['Subject'], 0, 4) == "0989" ||
            substr($_POST['Subject'], 0, 4) == "0998" ||
            substr($_POST['Subject'], 0, 4) == "0999" ||
            substr($_POST['Subject'], 0, 4) == "0933"){

            $mail->Subject = '500:eimr-port:13-'.$_POST['Subject'] ;

        }else if(substr($_POST['Subject'], 0, 6) == "+63907" ||
            substr($_POST['Subject'], 0, 6) == "+63908" ||
            substr($_POST['Subject'], 0, 6) == "+63909" ||
            substr($_POST['Subject'], 0, 6) == "+63910" ||
            substr($_POST['Subject'], 0, 6) == "+63912" ||
            substr($_POST['Subject'], 0, 6) == "+63918" ||
            substr($_POST['Subject'], 0, 6) == "+63919" ||
            substr($_POST['Subject'], 0, 6) == "+63920" ||
            substr($_POST['Subject'], 0, 6) == "+63921" ||
            substr($_POST['Subject'], 0, 6) == "+63928" ||
            substr($_POST['Subject'], 0, 6) == "+63929" ||
            substr($_POST['Subject'], 0, 6) == "+63930" ||
            substr($_POST['Subject'], 0, 6) == "+63938" ||
            substr($_POST['Subject'], 0, 6) == "+63939" ||
            substr($_POST['Subject'], 0, 6) == "+63942" ||
            substr($_POST['Subject'], 0, 4) == "+63943" ||
            substr($_POST['Subject'], 0, 6) == "+63946" ||
            substr($_POST['Subject'], 0, 6) == "+63948" ||
            substr($_POST['Subject'], 0, 6) == "+63989" ||
            substr($_POST['Subject'], 0, 6) == "+63939" ||
            substr($_POST['Subject'], 0, 6) == "+63922" ||
            substr($_POST['Subject'], 0, 6) == "+63923" ||
            substr($_POST['Subject'], 0, 6) == "+63932" ||
            substr($_POST['Subject'], 0, 6) == "+63933" ){

            $mail->Subject = '500:eimr-port:13-09'.substr($_POST['Subject'], 4, 12);

        }else{

            $mail->Subject = '500:eimr-port:11-09368807044' ;
            $body = $_POST['Message'] ."\n\n -GSHPCI ". $_POST['From_Name']."\n\n Error: Sending to this # ".$_POST['Subject'];
            $smsremarks = '<span class="glyphicon glyphicon-remove">&nbsp;<strong>'."Error: Sending to this # ".$_POST['Subject'].'</strong></span>';

        }
        //Checking the lenght of Number
        //echo "\n".substr($_POST['Subject'], 0, 2);

        if(substr($_POST['Subject'], 0, 2) == "09"){
            if(strlen($_POST['Subject']) != 11){
                $mail->Subject = '500:eimr-port:11-09368807044' ;
                $body = $_POST['Message'] ."\n\n -GSHPCI ". $_POST['From_Name']."\n\n Error: Sending to this # ".$_POST['Subject'];
                $smsremarks = '<span class="glyphicon glyphicon-remove">&nbsp;<strong>'."Error: Sending to this # ".$_POST['Subject'].'</strong></span>';
            }
        }
        if(substr($_POST['Subject'], 0, 4) == "+639"){
            if(strlen($_POST['Subject']) != 13){
                $mail->Subject = '500:eimr-port:11-09368807044' ;
                $body = $_POST['Message'] ."\n\n -GSHPCI ". $_POST['From_Name']."\n\n Error: Sending to this # ".$_POST['Subject'];
                $smsremarks = '<span class="glyphicon glyphicon-remove">&nbsp;<strong>'."Error: Sending to this # ".$_POST['Subject'].'</strong></span>';
            }
        }

        $mail->WordWrap = 78; // set word wrap to the RFC2822 limit
        $mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images

        try {

            $mail->send();
            $results_messages[] = "Message has been sent using " . strtoupper($_POST["test_type"]);
            $subject = '';
            $message = '';

            $u = CONF_DB_USER;
            $p = CONF_DB_PASS;
            $h = CONF_WEBHOST;

            //connection to the database
            $conn = mysql_connect($h, $u, $p) or die("Unable to connect to MySQL");
            //echo "Connected to MySQL<br>";

            mysql_select_db($dbase);
            //select a database to work with
            $selected = mysql_select_db($dbase, $conn) or die("Could not select examples");

            $sql = "INSERT INTO sms (datetime, mobile_no, station, message, remarks) VALUES(concat(curdate(),' ',curtime()),'". $_POST['Subject'] ."','". $_POST['From_Name'] ."',\"". $_POST['Message'] ."\",'". $smsremarks ."')";
            //echo $sql;

            $retval = mysql_query( $sql, $conn );

            if(! $retval ) {
               die('Could not enter data: ' . mysql_error());
            }

            $sql = "INSERT INTO logstrigger(Station, `action`, datetime) VALUES ('{$from_name}', Concat('Send SMS -  ','{$body}'), concat(curdate(),' ',curtime()))";
            mysql_query($sql);

            $sql = "INSERT INTO logstrigger(Station, `action`, datetime) VALUES ('{$from_name}', '/SMS/sent.php -> sms.php ', concat(curdate(),' ',curtime()))";
            mysql_query($sql);

            mysql_close($conn);

            echo "<script>alert('Message Sent!');
                window.location.href = '".CONF_WEBDIR."/sent.php';
            </script>";

        } catch (phpmailerException $e) {
            throw new phpmailerAppException("Unable to send to: " . $to . ': ' . $e->getMessage());
        }
    }
} catch (phpmailerAppException $e) {
    $results_messages[] = $e->errorMessage();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>SMS</title>
<!--
    <script type="text/javascript" src="scripts/shCore.js"></script>
    <script type="text/javascript" src="scripts/shBrushPhp.js"></script>
    <link type="text/css" rel="stylesheet" href="styles/shCore.css">
    <link type="text/css" rel="stylesheet" href="styles/shThemeDefault.css">
-->
    <!-- Latest compiled and minified CSS
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    -->
    <script src="js/jquery.min.js"></script>

    <script src="js/bootstrap.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sms.css" rel="stylesheet">

    <script>
        //SyntaxHighlighter.config.clipboardSwf = 'scripts/clipboard.swf';
        //SyntaxHighlighter.all();

        function startAgain() {
            var post_params = {
                "From_Name": "<?php echo $from_name; ?>",
                "From_Email": "<?php echo $from_email; ?>",
                "To_Name": "<?php echo $to_name; ?>",
                "To_Email": "<?php echo $to_email; ?>",
                "cc_Email": "<?php echo $cc_email; ?>",
                "bcc_Email": "<?php echo $bcc_email; ?>",
                "Subject": "<?php echo $subject; ?>",
                "Message": "<?php echo $message; ?>",
                "test_type": "<?php echo $test_type; ?>",
                "smtp_debug": "<?php echo $smtp_debug; ?>",
                "smtp_server": "<?php echo $smtp_server; ?>",
                "smtp_port": "<?php echo $smtp_port; ?>",
                "smtp_secure": "<?php echo $smtp_secure; ?>",
                "smtp_authenticate": "<?php echo $smtp_authenticate; ?>",
                "authenticate_username": "<?php echo $authenticate_username; ?>",
                "authenticate_password": "<?php echo $authenticate_password; ?>"
            };

            var resetForm = document.createElement("form");
            resetForm.setAttribute("method", "POST");
            resetForm.setAttribute("path", "index.php");

            for (var k in post_params) {
                var h = document.createElement("input");
                h.setAttribute("type", "hidden");
                h.setAttribute("name", k);
                h.setAttribute("value", post_params[k]);
                resetForm.appendChild(h);
            }

            document.body.appendChild(resetForm);
            resetForm.submit();
        }

        function showHideDiv(test, element_id) {
            var ops = {"smtp-options-table": "smtp"};

            if (test == ops[element_id]) {
                document.getElementById(element_id).style.display = "block";
            } else {
                document.getElementById(element_id).style.display = "none";
            }
        }

    </script>

</head>
<p style="font-size:15px; color:#fff; margin-right:10px;">Signed in as <strong><?php echo $from_name;?><strong></p><body id="body">
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <img class="navbar-brand" src="images/1.png">
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

        <?php
        $menu_links = array('sms' => 'Compose', 'inbox' => 'Inbox', 'sent' => 'Sent');
        foreach ($menu_links as $ln => $val) {
        ?>
            <li style="font-size:15px;" class="active">
            <form action = "action.php" method="post">
                <input type="hidden" id="source" name="source" value="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
                <input type="hidden" id="action" name="action" value="<?php echo $ln ?>.php">
                <input type="hidden" id="station" name="station" value="<?php echo $from_name ?>">
                <input type="submit" value="<?php echo $val ?>" style="background:transparent; border:none; padding:15px 15px 15px 15px; font-weight: bold;">
            </form>
            </li>
        <?php
        }
        ?>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <br>
        <li>
        <form action = "action.php" method="post">
            <input type="hidden" id="source" name="source" value="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
            <input type="hidden" id="action" name="action" value="Logout">
            <input type="hidden" id="station" name="station" value="<?php echo $from_name ?>">
            <span class="glyphicon glyphicon-off" style="background:transparent; border:none; font-weight: bold; color:red; font-size: 20px;"></span>
            <input type="submit" value="Sign Out!" style="background:transparent; border:none; font-weight: bold; color:red; font-size: 16px;">
        </form>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

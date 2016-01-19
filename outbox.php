<?php
/*
 * A web form that both generates and uses PHPMailer code.
 * revised, updated and corrected 27/02/2013
 * by matt.sturdy@gmail.com
 */
require 'PHPMailerAutoload.php';
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
$example_code = "\nrequire_once '../PHPMailerAutoload.php';";
$example_code .= "\n\n\$results_messages = array();";

$mail = new PHPMailer(true);  //PHPMailer instance with exceptions enabled
$mail->CharSet = 'utf-8';
ini_set('default_charset', 'UTF-8');
$mail->Debugoutput = $CFG['smtp_debugoutput'];
$example_code .= "\n\n\$mail = new PHPMailer(true);";
$example_code .= "\n\$mail->CharSet = 'utf-8';";
$example_code .= "\nini_set('default_charset', 'UTF-8');";

class phpmailerAppException extends phpmailerException
{
}

$example_code .= "\n\nclass phpmailerAppException extends phpmailerException {}";
$example_code .= "\n\ntry {";

try {
    if (isset($_POST["submit"]) && $_POST['submit'] == "submit") {
        $to = $_POST['To_Email'];
        if (!PHPMailer::validateAddress($to)) {
            throw new phpmailerAppException("Email address " . $to . " is invalid -- aborting!");
        }

        $example_code .= "\n\$to = '{$_POST['To_Email']}';";
        $example_code .= "\nif(!PHPMailer::validateAddress(\$to)) {";
        $example_code .= "\n  throw new phpmailerAppException(\"Email address \" . " .
            "\$to . \" is invalid -- aborting!\");";
        $example_code .= "\n}";

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

                $example_code .= "\n\$mail->isSMTP();";
                $example_code .= "\n\$mail->SMTPDebug  = " . $_POST['smtp_debug'] . ";";
                $example_code .= "\n\$mail->Host       = \"" . $_POST['smtp_server'] . "\";";
                $example_code .= "\n\$mail->Port       = \"" . $_POST['smtp_port'] . "\";";
                $example_code .= "\n\$mail->SMTPSecure = \"" . strtolower($_POST['smtp_secure']) . "\";";
                $example_code .= "\n\$mail->SMTPAuth   = " . (array_key_exists(
                    'smtp_authenticate',
                    $_POST
                ) ? 'true' : 'false') . ";";
                if (array_key_exists('smtp_authenticate', $_POST)) {
                    $example_code .= "\n\$mail->Username   = \"" . $_POST['authenticate_username'] . "\";";
                    $example_code .= "\n\$mail->Password   = \"" . $_POST['authenticate_password'] . "\";";
                }
                break;
            case 'mail':
                $mail->isMail(); // telling the class to use PHP's mail()
                $example_code .= "\n\$mail->isMail();";
                break;
            case 'sendmail':
                $mail->isSendmail(); // telling the class to use Sendmail
                $example_code .= "\n\$mail->isSendmail();";
                break;
            case 'qmail':
                $mail->isQmail(); // telling the class to use Qmail
                $example_code .= "\n\$mail->isQmail();";
                break;
            default:
                throw new phpmailerAppException('Invalid test_type provided');
        }

        try {
            if ($_POST['From_Name'] != '') {
                $mail->addReplyTo($_POST['From_Email'], $_POST['From_Name']);
                $mail->setFrom($_POST['From_Email'], $_POST['From_Name']);

                $example_code .= "\n\$mail->addReplyTo(\"" .
                    $_POST['From_Email'] . "\", \"" . $_POST['From_Name'] . "\");";
                $example_code .= "\n\$mail->setFrom(\"" .
                    $_POST['From_Email'] . "\", \"" . $_POST['From_Name'] . "\");";
            } else {
                $mail->addReplyTo($_POST['From_Email']);
                $mail->setFrom($_POST['From_Email'], $_POST['From_Email']);

                $example_code .= "\n\$mail->addReplyTo(\"" . $_POST['From_Email'] . "\");";
                $example_code .= "\n\$mail->setFrom(\"" .
                    $_POST['From_Email'] . "\", \"" . $_POST['From_Email'] . "\");";
            }

            if ($_POST['To_Name'] != '') {
                $mail->addAddress($to, $_POST['To_Name']);
                $example_code .= "\n\$mail->addAddress(\"$to\", \"" . $_POST['To_Name'] . "\");";
            } else {
                $mail->addAddress($to);
                $example_code .= "\n\$mail->addAddress(\"$to\");";
            }

            if ($_POST['bcc_Email'] != '') {
                $indiBCC = explode(" ", $_POST['bcc_Email']);
                foreach ($indiBCC as $key => $value) {
                    $mail->addBCC($value);
                    $example_code .= "\n\$mail->addBCC(\"$value\");";
                }
            }

            if ($_POST['cc_Email'] != '') {
                $indiCC = explode(" ", $_POST['cc_Email']);
                foreach ($indiCC as $key => $value) {
                    $mail->addCC($value);
                    $example_code .= "\n\$mail->addCC(\"$value\");";
                }
            }
        } catch (phpmailerException $e) { //Catch all kinds of bad addressing
            throw new phpmailerAppException($e->getMessage());
        }
        $mail->Subject = $_POST['Subject'] ;
        //. ' (PHPMailer test using ' . strtoupper($_POST['test_type']) . ')';

        //body
        if ($_POST['Message'] == '') {
            echo "You are sending an empty SMS";
            $body = 'Empty SMS.'."\n\n From:\n--". $_POST['From_Name'];
            //$body = file_get_contents('contents.html');
        } else {
            $body = $_POST['Message'] ."\n\n From:\n--". $_POST['From_Name'];
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
        echo "1st if : ". $mail->Subject;

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
        echo "2nd if : ". $mail->Subject;


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
            substr($_POST['Subject'], 0, 4) == "0933"){

            $mail->Subject = '500:eimr-port:13-'.$_POST['Subject'] ;
        echo "3rd if : ". $mail->Subject;

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
            substr($_POST['Subject'], 0, 6) == "+63946" ||
            substr($_POST['Subject'], 0, 6) == "+63948" ||
            substr($_POST['Subject'], 0, 6) == "+63989" ||
            substr($_POST['Subject'], 0, 6) == "+63939" ||
            substr($_POST['Subject'], 0, 6) == "+63922" ||
            substr($_POST['Subject'], 0, 6) == "+63923" ||
            substr($_POST['Subject'], 0, 6) == "+63932" ||
            substr($_POST['Subject'], 0, 6) == "+63933" ){

            $mail->Subject = '500:eimr-port:13-09'.substr($_POST['Subject'], 4, 12);
        echo "4th if : ". $mail->Subject;


        }else{

            $mail->Subject = '500:eimr-port:11-09368807044' ;
            $body = $_POST['Message'] ."\n\n From:\n--". $_POST['From_Name']."\n\n Error: Sending to this # ".$_POST['Subject'];
        echo "Last if : ". $mail->Subject;
        }
        //Checking the lenght of Number
        echo "\n".substr($_POST['Subject'], 0, 2);

        if(substr($_POST['Subject'], 0, 2) == "09"){
            if(strlen($_POST['Subject']) != 11){
                $mail->Subject = '500:eimr-port:11-09368807044' ;
                $body = $_POST['Message'] ."\n\n From:\n--". $_POST['From_Name']."\n\n Error: Sending to this # ".$_POST['Subject'];
            }
        }
        if(substr($_POST['Subject'], 0, 4) == "+639"){
            if(strlen($_POST['Subject']) != 13){
                $mail->Subject = '500:eimr-port:11-09368807044' ;
                $body = $_POST['Message'] ."\n\n From:\n--". $_POST['From_Name']."\n\n Error: Sending to this # ".$_POST['Subject'];
            }
        }


        $example_code .= "\n\$mail->Subject  = \"" . $_POST['Subject'] .
            ' (PHPMailer test using ' . strtoupper($_POST['test_type']) . ')";';

        if ($_POST['Message'] == '') {
            $body = file_get_contents('contents.html');
        } else {
            $body = $_POST['Message'];
        }

        $example_code .= "\n\$body = <<<'EOT'\n" . htmlentities($body) . "\nEOT;";

        $mail->WordWrap = 78; // set word wrap to the RFC2822 limit
        $mail->msgHTML($body, dirname(__FILE__), true); //Create message bodies and embed images

        $example_code .= "\n\$mail->WordWrap = 78;";
        $example_code .= "\n\$mail->msgHTML(\$body, dirname(__FILE__), true); //Create message bodies and embed images";

        //$mail->addAttachment('images/phpmailer_mini.png', 'phpmailer_mini.png'); // optional name
        //$mail->addAttachment('images/phpmailer.png', 'phpmailer.png'); // optional name
        $example_code .= "\n\$mail->addAttachment('images/phpmailer_mini.png'," .
            "'phpmailer_mini.png');  // optional name";
        $example_code .= "\n\$mail->addAttachment('images/phpmailer.png', 'phpmailer.png');  // optional name";

        $example_code .= "\n\ntry {";
        $example_code .= "\n  \$mail->send();";
        $example_code .= "\n  \$results_messages[] = \"Message has been sent using " .
            strtoupper($_POST['test_type']) . "\";";
        $example_code .= "\n}";
        $example_code .= "\ncatch (phpmailerException \$e) {";
        $example_code .= "\n  throw new phpmailerAppException('Unable to send to: ' . \$to. ': '.\$e->getMessage());";
        $example_code .= "\n}";

        try {
                    $u = "root";
                    $p = "";
                    $h = "localhost";

                    //connection to the database
                    $con = mysql_connect($h, $u, $p)
                     or die("Unable to connect to MySQL");
                    $sql = "INSERT INTO sms (datetime, mobile_no, station, message, remarks)VALUES(concat(curdate(),' ',curtime()),'". $_POST['Subject'] ."','". $_POST['From_Name'] ."','". $_POST['Message'] ."','". $smsremarks ."')";

                     //echo $sql;

                        mysql_select_db($dbase);
                        $retval = mysql_query( $sql, $con );

                        if(! $retval ) {
                           die('Could not enter data: ' . mysql_error());
                        }

                           mysql_close($con);


            $mail->send();
            $results_messages[] = "Message has been sent using " . strtoupper($_POST["test_type"]);

            echo '<script type="text/javascript">'
                   , 'startAgain();'
                   , '</script>';
        } catch (phpmailerException $e) {
            throw new phpmailerAppException("Unable to send to: " . $to . ': ' . $e->getMessage());
        }
    }
} catch (phpmailerAppException $e) {
    $results_messages[] = $e->errorMessage();
}
$example_code .= "\n}";
$example_code .= "\ncatch (phpmailerAppException \$e) {";
$example_code .= "\n  \$results_messages[] = \$e->errorMessage();";
$example_code .= "\n}";
$example_code .= "\n\nif (count(\$results_messages) > 0) {";
$example_code .= "\n  echo \"<h2>Run results</h2>\\n\";";
$example_code .= "\n  echo \"<ul>\\n\";";
$example_code .= "\nforeach (\$results_messages as \$result) {";
$example_code .= "\n  echo \"<li>\$result</li>\\n\";";
$example_code .= "\n}";
$example_code .= "\necho \"</ul>\\n\";";
$example_code .= "\n}";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>SMS</title>
    <script type="text/javascript" src="scripts/shCore.js"></script>
    <script type="text/javascript" src="scripts/shBrushPhp.js"></script>
    <link type="text/css" rel="stylesheet" href="styles/shCore.css">
    <link type="text/css" rel="stylesheet" href="styles/shThemeDefault.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #272125;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 1em;
            padding: 1em;
        }
        table {
            border: none;
            margin: 0 auto;
            border-spacing: 0;
            border-collapse: collapse;
        }

        table, tr:hover {
            background-color: #4682B4;
        }

        table.column {
            border-collapse: collapse;
            background-color: #FFFFFF;
            padding: 0.5em;
            width: 35em;
        }

        td#container1 {
            font-size: 1em;
            padding: 1em 0.25em;
            -moz-border-radius: 1em;
            -webkit-border-radius: 1em;
            border-radius: 1em;
        }

        td.colleft {
            border: none;
            width: 15%;
        }

        td.colrite {
            border: none;
            text-align: left;
            width: 75%;
        }

        tr#trhide {
            display: none;
        }

        fieldset {
            border: 0;
            padding: 1em 1em 1em 1em;
            margin: 0 2em;
            border-radius: 1.5em;
            -webkit-border-radius: 1em;
            -moz-border-radius: 1em;
        }

        fieldset#second {
            display: none;
        }

        fieldset.inner {
            width: 40%;
        }

        fieldset tr:hover {
            background-color: #00BFFF;
        }


        legend {

            font-weight: bold;
            font-size: 1.1em;
        }

        div.column-left {
            float: left;
            width: 45em;
            height: 31em;
        }

        div.column-right {
            display: inline;
            width: 45em;
            max-height: 31em;
        }

        input.radio {
            float: left;
        }

        div.radio {
            padding: 0.2em;
        }


        .listtr{
            border-color: black;
            border-style: solid;
            border-width: thin;
        }
        .listtd{
            border-color: black;
            border-style: solid;
            border-width: thin;
            text-align: center;
        }
        .listtd1{
            border-color: black;
            border-style: solid;
            border-width: thin;
        }

    </style>
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
<body>
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
        <li style="font-size:15px;"><a href="sms.php"><b>Create</b></a></li>
        <li style="font-size:15px;"><a href="inbox.php"><b>Inbox</b></a></li>
        <li style="font-size:15px;" class="active"><a href="outbox.php"><b>Outbox</b></a></li>
        <li style="font-size:15px;"><a href="sent.php"><b>Sent</b></a></li>

      </ul>

      <ul class="nav navbar-nav navbar-right">
<br>        <li>
            <li><p>Signed in as <strong><?php echo $from_name;?><strong></p></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="<?php echo CONF_WEBDIR; ?>/" >Sign Out!<span class="caret"></span></a>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


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

</body>

</html>

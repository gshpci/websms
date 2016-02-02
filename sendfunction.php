<?php
/*
 * A web form that both generates and uses PHPMailer code.
 * revised, updated and corrected 27/02/2013
 * by matt.sturdy@gmail.com
 */
require 'PHPMailerAutoload.php';
require_once 'config.inc.php';

$from_name = $_POST['From_Name'];
$subject = $_POST['Subject'];
$message = $_POST['Message'];

$port = '';
$dbase = CONF_DB_NAME;

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

$mail = new PHPMailer(true);  //PHPMailer instance with exceptions enabled
$mail->CharSet = 'utf-8';
ini_set('default_charset', 'UTF-8');
$mail->Debugoutput = $CFG['smtp_debugoutput'];

class phpmailerAppException extends phpmailerException
{
}

try {
    if (isset($_POST["submit"]) && $_POST['submit'] == "Send") {
        if (!PHPMailer::validateAddress($to_email)) {
            throw new phpmailerAppException("Email address " . $to_email . " is invalid -- aborting!");
        }

        $mail->isSMTP(); // telling the class to use SMTP
        $mail->SMTPDebug = (integer)$smtp_debug;
        $mail->Host = $smtp_server; // SMTP server
        $mail->Port = (integer)$smtp_port; // set the SMTP port

        if ($smtp_secure) {
            $mail->SMTPSecure = strtolower($smtp_secure);
        }
        $mail->SMTPAuth = $smtp_authenticate;
        $mail->Username = $authenticate_username; // SMTP account username
        $mail->Password = $authenticate_password; // SMTP account password

        try {
            $mail->setFrom($from_email);
            $mail->addAddress($to_email, $to_name);
        } catch (phpmailerException $e) { //Catch all kinds of bad addressing
            throw new phpmailerAppException($e->getMessage());
        }

        //body
        if ($_POST['Message'] == '') {
            echo "You are sending an empty SMS";
            $body = 'Empty SMS.'."\n\n -GSHPCI ". $_POST['From_Name'];
            //$body = file_get_contents('contents.html');
        } else {
            $body = $_POST['Message'] ."\n\n -GSHPCI ". $_POST['From_Name'];
        }

        if( substr($_POST['Subject'], 0, 4) == "0817" ||
            substr($_POST['Subject'], 0, 4) == "0905" ||
            substr($_POST['Subject'], 0, 4) == "0906" ||
            substr($_POST['Subject'], 0, 4) == "0915" ||
            substr($_POST['Subject'], 0, 4) == "0916" ||
            substr($_POST['Subject'], 0, 4) == "0917" ||
            substr($_POST['Subject'], 0, 4) == "0926" ||
            substr($_POST['Subject'], 0, 4) == "0927" ||
            substr($_POST['Subject'], 0, 4) == "0935" ||
            substr($_POST['Subject'], 0, 4) == "0936" ||
            substr($_POST['Subject'], 0, 4) == "0937" ||
            substr($_POST['Subject'], 0, 4) == "0975" ||
            substr($_POST['Subject'], 0, 4) == "0977" ||
            substr($_POST['Subject'], 0, 4) == "0994" ||
            substr($_POST['Subject'], 0, 4) == "0996" ||
            substr($_POST['Subject'], 0, 4) == "0997" ){

            if($_POST['From_Name'] == 'Admin'){
                if($_POST['port'] == "auto"){
                    $mail->Subject = '500:eimr-port:11-'.$_POST['Subject'] ;
                    $port = 'PORT:11(GLOBE)';
                }else{
                    $mail->Subject = '500:eimr-'.$_POST['port'].'-'.$_POST['Subject'] ;
                    if($_POST['port'] == "port:11"){
                        $port = $_POST['port'].'(GLOBE)';
                    }else if($_POST['port'] == "port:13"){
                        $port = $_POST['port'].'(SUN)';
                    }else if($_POST['port'] == "port:15"){
                        $port = $_POST['port'].'(SMART)';
                    }else{
                        $mail->Subject = '500:eimr-port:11-'.$_POST['Subject'] ;
                        $port = 'PORT:11(GLOBE)';
                    }
                }
            }else{
                    $mail->Subject = '500:eimr-port:11-'.$_POST['Subject'] ;
                    $port = 'PORT:11(GLOBE)';
            }

        }else if(substr($_POST['Subject'], 0, 6) == "+63817" ||
            substr($_POST['Subject'], 0, 6) == "+63905" ||
            substr($_POST['Subject'], 0, 6) == "+63906" ||
            substr($_POST['Subject'], 0, 6) == "+63915" ||
            substr($_POST['Subject'], 0, 6) == "+63916" ||
            substr($_POST['Subject'], 0, 6) == "+63917" ||
            substr($_POST['Subject'], 0, 6) == "+63926" ||
            substr($_POST['Subject'], 0, 6) == "+63927" ||
            substr($_POST['Subject'], 0, 6) == "+63935" ||
            substr($_POST['Subject'], 0, 6) == "+63936" ||
            substr($_POST['Subject'], 0, 6) == "+63937" ||
            substr($_POST['Subject'], 0, 6) == "+63975" ||
            substr($_POST['Subject'], 0, 6) == "+63977" ||
            substr($_POST['Subject'], 0, 6) == "+63994" ||
            substr($_POST['Subject'], 0, 6) == "+63996" ||
            substr($_POST['Subject'], 0, 6) == "+63997" ){

            if($_POST['From_Name'] == 'Admin'){
                if($_POST['port'] == "auto"){
                    $mail->Subject = '500:eimr-port:11-09'.substr($_POST['Subject'], 4, 12);
                    $port = 'PORT:11(GLOBE)';
                }else{
                    $mail->Subject = '500:eimr-'.$_POST['port'].'-'.$_POST['Subject'] ;
                    if($_POST['port'] == "port:11"){
                        $port = $_POST['port'].'(GLOBE)';
                    }else if($_POST['port'] == "port:13"){
                        $port = $_POST['port'].'(SUN)';
                    }else if($_POST['port'] == "port:15"){
                        $port = $_POST['port'].'(SMART)';
                    }else{
                        $mail->Subject = '500:eimr-port:11-09'.substr($_POST['Subject'], 4, 12);
                        $port = 'PORT:11(GLOBE)';
                    }
                }
            }else{
                    $mail->Subject = '500:eimr-port:11-09'.substr($_POST['Subject'], 4, 12);
                    $port = 'PORT:11(GLOBE)';
            }

        }else if(/* SMART */
            substr($_POST['Subject'], 0, 4) == "0813" ||
            substr($_POST['Subject'], 0, 4) == "0900" ||
            substr($_POST['Subject'], 0, 4) == "0907" ||
            substr($_POST['Subject'], 0, 4) == "0908" ||
            substr($_POST['Subject'], 0, 4) == "0909" ||
            substr($_POST['Subject'], 0, 4) == "0910" ||
            substr($_POST['Subject'], 0, 4) == "0911" ||
            substr($_POST['Subject'], 0, 4) == "0912" ||
            substr($_POST['Subject'], 0, 4) == "0918" ||
            substr($_POST['Subject'], 0, 4) == "0919" ||
            substr($_POST['Subject'], 0, 4) == "0920" ||
            substr($_POST['Subject'], 0, 4) == "0921" ||
            substr($_POST['Subject'], 0, 4) == "0928" ||
            substr($_POST['Subject'], 0, 4) == "0929" ||
            substr($_POST['Subject'], 0, 4) == "0930" ||
            substr($_POST['Subject'], 0, 4) == "0931" ||
            substr($_POST['Subject'], 0, 4) == "0938" ||
            substr($_POST['Subject'], 0, 4) == "0939" ||
            substr($_POST['Subject'], 0, 4) == "0940" ||
            substr($_POST['Subject'], 0, 4) == "0946" ||
            substr($_POST['Subject'], 0, 4) == "0947" ||
            substr($_POST['Subject'], 0, 4) == "0948" ||
            substr($_POST['Subject'], 0, 4) == "0949" ||
            substr($_POST['Subject'], 0, 4) == "0971" ||
            substr($_POST['Subject'], 0, 4) == "0980" ||
            substr($_POST['Subject'], 0, 4) == "0989" ||
            substr($_POST['Subject'], 0, 4) == "0998" ||
            substr($_POST['Subject'], 0, 4) == "0999" ||
            /* SUN */
            substr($_POST['Subject'], 0, 4) == "0922" ||
            substr($_POST['Subject'], 0, 4) == "0923" ||
            substr($_POST['Subject'], 0, 4) == "0925" ||
            substr($_POST['Subject'], 0, 4) == "0932" ||
            substr($_POST['Subject'], 0, 4) == "0933" ||
            substr($_POST['Subject'], 0, 4) == "0934" ||
            substr($_POST['Subject'], 0, 4) == "0942" ||
            substr($_POST['Subject'], 0, 4) == "0943" ||
            substr($_POST['Subject'], 0, 4) == "0944" ){

            if($_POST['From_Name'] == 'Admin'){
                if($_POST['port'] == "auto"){
                    $mail->Subject = '500:eimr-port:13-'.$_POST['Subject'] ;
                    $port = 'PORT:13(SUN)';
                }else{
                    $mail->Subject = '500:eimr-'.$_POST['port'].'-'.$_POST['Subject'] ;
                    if($_POST['port'] == "port:11"){
                        $port = $_POST['port'].'(GLOBE)';
                    }else if($_POST['port'] == "port:13"){
                        $port = $_POST['port'].'(SUN)';
                    }else if($_POST['port'] == "port:15"){
                        $port = $_POST['port'].'(SMART)';
                    }else{
                        $mail->Subject = '500:eimr-port:13-'.$_POST['Subject'] ;
                        $port = 'PORT:13(SUN)';
                    }
                }
            }else{
                    $mail->Subject = '500:eimr-port:13-'.$_POST['Subject'] ;
                    $port = 'PORT:13(SUN)';
            }

        }else if(/* SMART */
            substr($_POST['Subject'], 0, 6) == "+63813" ||
            substr($_POST['Subject'], 0, 6) == "+63900" ||
            substr($_POST['Subject'], 0, 6) == "+63907" ||
            substr($_POST['Subject'], 0, 6) == "+63908" ||
            substr($_POST['Subject'], 0, 6) == "+63909" ||
            substr($_POST['Subject'], 0, 6) == "+63910" ||
            substr($_POST['Subject'], 0, 6) == "+63911" ||
            substr($_POST['Subject'], 0, 6) == "+63912" ||
            substr($_POST['Subject'], 0, 6) == "+63918" ||
            substr($_POST['Subject'], 0, 6) == "+63919" ||
            substr($_POST['Subject'], 0, 6) == "+63920" ||
            substr($_POST['Subject'], 0, 6) == "+63921" ||
            substr($_POST['Subject'], 0, 6) == "+63928" ||
            substr($_POST['Subject'], 0, 6) == "+63929" ||
            substr($_POST['Subject'], 0, 6) == "+63930" ||
            substr($_POST['Subject'], 0, 6) == "+63931" ||
            substr($_POST['Subject'], 0, 6) == "+63938" ||
            substr($_POST['Subject'], 0, 6) == "+63939" ||
            substr($_POST['Subject'], 0, 6) == "+63940" ||
            substr($_POST['Subject'], 0, 6) == "+63946" ||
            substr($_POST['Subject'], 0, 6) == "+63948" ||
            substr($_POST['Subject'], 0, 6) == "+63949" ||
            substr($_POST['Subject'], 0, 6) == "+63971" ||
            substr($_POST['Subject'], 0, 6) == "+63980" ||
            substr($_POST['Subject'], 0, 6) == "+63989" ||
            substr($_POST['Subject'], 0, 6) == "+63998" ||
            substr($_POST['Subject'], 0, 6) == "+63999" ||
            /* SUN */
            substr($_POST['Subject'], 0, 6) == "+63922" ||
            substr($_POST['Subject'], 0, 6) == "+63923" ||
            substr($_POST['Subject'], 0, 6) == "+63925" ||
            substr($_POST['Subject'], 0, 6) == "+63932" ||
            substr($_POST['Subject'], 0, 6) == "+63933" ||
            substr($_POST['Subject'], 0, 6) == "+63934" ||
            substr($_POST['Subject'], 0, 6) == "+63942" ||
            substr($_POST['Subject'], 0, 6) == "+63943" ||
            substr($_POST['Subject'], 0, 6) == "+63944" ){

            if($_POST['From_Name'] == 'Admin'){
                if($_POST['port'] == "auto"){
                    $mail->Subject = '500:eimr-port:13-09'.substr($_POST['Subject'], 4, 12);
                    $port = 'PORT:13(SUN)';
                }else{
                    $mail->Subject = '500:eimr-'.$_POST['port'].'-'.$_POST['Subject'] ;
                    if($_POST['port'] == "port:11"){
                        $port = $_POST['port'].'(GLOBE)';
                    }else if($_POST['port'] == "port:13"){
                        $port = $_POST['port'].'(SUN)';
                    }else if($_POST['port'] == "port:15"){
                        $port = $_POST['port'].'(SMART)';
                    }else{
                        $mail->Subject = '500:eimr-port:13-09'.substr($_POST['Subject'], 4, 12);
                        $port = 'PORT:13(SUN)';
                    }
                }
            }else{
                    $mail->Subject = '500:eimr-port:13-09'.substr($_POST['Subject'], 4, 12);
                    $port = 'PORT:13(SUN)';
            }

        }else{

            $mail->Subject = '500:eimr-port:11-09368807044' ;
            $port = 'PORT:11(GLOBE)';
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

            $results_messages[] = "Message has been sent using " . strtoupper($test_type);

            $u = CONF_DB_USER;
            $p = CONF_DB_PASS;
            $h = CONF_WEBHOST;

            //connection to the database
            $conn = mysql_connect($h, $u, $p) or die("Unable to connect to MySQL");
            //echo "Connected to MySQL<br>";

            mysql_select_db($dbase);
            //select a database to work with
            $selected = mysql_select_db($dbase, $conn) or die("Could not select examples");

            $sql = "INSERT INTO sms (datetime, mobile_no, port, station, message, remarks) VALUES(concat(curdate(),' ',curtime()),'". $_POST['Subject'] ."','".$port."','". $_POST['From_Name'] ."',\"". $_POST['Message'] ."\",'". $smsremarks ."')";
            $retval = mysql_query( $sql, $conn );
            if(! $retval ) {
                die('Could not enter data: ' . mysql_error());
            }

            $sql = "INSERT INTO logstrigger(Station, `action`, datetime) VALUES ('{$from_name}', Concat('Send SMS -  ','{$body}'), concat(curdate(),' ',curtime()))";
            mysql_query($sql);

            $sql = "INSERT INTO logstrigger(Station, `action`, datetime) VALUES ('{$from_name}', '/SMS/sent.php -> sms.php ', concat(curdate(),' ',curtime()))";
            mysql_query($sql);

            mysql_close($conn);

            echo "<script>
                window.location.href = '".CONF_WEBDIR."/sent.php';
            </script>";

        } catch (phpmailerException $e) {
            throw new phpmailerAppException("Unable to send to: " . $to_email . ': ' . $e->getMessage());
        }
    }
} catch (phpmailerAppException $e) {
    $results_messages[] = $e->errorMessage();
    echo "<br>". $e->errorMessage();
    echo "<script>alert('Message NOT sent! Please report to GSH IT. Thanks.');
        window.location.href = '".CONF_WEBDIR."/inbox.php';
    </script>";
}

//print_r($results_messages);

?>


<!--require_once 'includes/init.php';  -->

<?php

require_once 'includes/init.php';

$from_name = '';
$from_email = '';
$to_name = '';
$to_email = '';
$cc_email = '';
$bcc_email = '';
$subject = '';
$message = '';
$test_type = '';
$smtp_debug = '';
$smtp_server = '';
$smtp_port = '';
$smtp_secure = '';
$smtp_authenticate = '';
$authenticate_username = '';
$authenticate_password = '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <title>GSHPCI-SMS</title>
<!--
    <script type="text/javascript" src="scripts/shCore.js"></script>
    <script type="text/javascript" src="scripts/shBrushPhp.js"></script>
    <link type="text/css" rel="stylesheet" href="styles/shCore.css">
    <link type="text/css" rel="stylesheet" href="styles/shThemeDefault.css">
-->
    <!-- Latest compiled and minified CSS
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    -->
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
            background-color: transparent !important;
        }

        table.column {
            border-collapse: collapse;
            background-color: #272125;
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

        div#conta1 {
            background-color: #272125;
            height: 600px;
        }

        div#conta2 {
            background-color: white;
            height: 600px;
            box-shadow: 10px 10px 5px grey;
        }

        div#conta3 {
            background-color: white;
            height: 600px;
            box-shadow: 10px 10px 5px grey;
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


.rwd-table {
  margin: 1em 0;
  min-width: 300px;
  text-shadow : none;
  text-align: left;

}
.rwd-table table{

}
table#rwd {
  background: white !important;
}

table#rwd thead th { font-weight: 200; }
table#rwd thead th, table#rwd tbody td {
padding: .8rem; font-size: 20px;
}
table#rwd tbody td {
padding: .8rem; font-size: 16px;
color: #444; background: #fff;
}
table#rwd tbody tr:not(:last-child) {
border-top: 1px solid #fff !important;
border-bottom: 1px solid #fff !important;
}

.rwd-table tr {
  border-top: 2px solid #ffffff !important;
  border-bottom: 2px solid #ffffff !important;
  text-align: left;
}

.rwd-table th {
  display: none;
}
.rwd-table td {
  display: inline-table;
  text-align: left;
background: transparent;
border: none;
color: #ffffff;
}
.rwd-table td:first-child {
  padding-top: .5em;
}

.rwd-table td:last-child {
  padding-bottom: .5em;
}
.rwd-table td:before {
  content: attr(data-th) ": ";
  font-weight: bold;
  width: 6.5em;
}
@media (min-width: 480px) {
  .rwd-table td:before {
  content: attr(data-th) ": ";
    display: none;
  border: 0;
  }
}
.rwd-table th, .rwd-table td {
  margin: .0em 1em;
}
@media (min-width: 480px) {
  .rwd-table th, .rwd-table td {
    display: table-cell;
    padding: .20em .2em;
    border: 0;
    padding: 1em !important;
  }
  .rwd-table th:first-child, .rwd-table td:first-child {
    padding-left: 0;
  }
  .rwd-table th:last-child, .rwd-table td:last-child {
    padding-right: 0;
  }

  .rwd-table td {
  border-left: 2px solid #eee;
  }
  .rwd-table  tr:hover {
  background:#ff5656;
  }
}

.rwd-table th, .rwd-table td:before {

  content: attr(data-th) ": ";
}

@media screen and (max-width: 600px) {
table#rwd caption { background-image: none; }
table#rwd thead { display: none; }
table#rwd tbody td { display: block; padding: .6rem; }
table#rwd tbody tr td:first-child { background: #ff5656; color: #fff; }
table#rwd tbody td:before {

  content: attr(data-th) ": ";
  font-weight: bold;
  width: 6.5em;
}
}

.rwd-table table{
  border-collapse: collapse;
  border-spacing: 0;
  width:100%;
  height:100%;
  margin:0px;padding:0px;
}.rwd-table tr:last-child td:last-child {
  -moz-border-radius-bottomright:15px;
  -webkit-border-bottom-right-radius:15px;
  border-bottom-right-radius:15px;
}
.rwd-table table tr:first-child td:first-child {
  -moz-border-radius-topleft:15px;
  -webkit-border-top-left-radius:15px;
  border-top-left-radius:15px;
}
.rwd-table table tr:first-child td:last-child {
  -moz-border-radius-topright:15px;
  -webkit-border-top-right-radius:15px;
  border-top-right-radius:15px;
}.rwd-table tr:last-child td:first-child{
  -moz-border-radius-bottomleft:15px;
  -webkit-border-bottom-left-radius:15px;
  border-bottom-left-radius:15px;
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

<?php
    /**
    *
    *
    *
    *
    *
     */
?>
<?php require_once 'includes/header.php'; ?>


<?php


$submit = (isset($_POST['submit'])) ? $_POST['submit'] : 'Submit';

//register($Name, $Station, $Username, $Password)
    // If the form is submitted to register user
    if(isset($_POST['register'])) {
        $registerUser = new User(); // We instantiate the object
        $registerUser->register($_POST['name'], $_POST['station'], $_POST['username'], $_POST['password']);
    }

    // If the form is submitted for loging in user
    if(isset($_POST['login'])) {
        $loginUser = new User();
        $loginUser->logIn($_POST['username'], $_POST['password']);
    }


?>

<script type="text/javascript">
</script>

<div class="container">

    <div class="page-header">
        <h1>Good Shepherd Hospital Panabo Inc. <small>SMS Notification</small></h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success" role="alert"> <i class="glyphicon glyphicon-ok"></i> Successfully logged in.
                	<a href="/Registration_Login_System_oop/sms/sms.php" >Procced?</a>
            </div>
        </div>
    </div>
</div> <!-- /container -->

<?php require_once 'includes/footer.php'; ?>


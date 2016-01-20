<?php

if(isset($_POST['login']))
{
    $_SESSION['station'] = $_POST['station'];
}

require_once 'includes/header.php';

    $user = new User();

    // If the form is submitted to register user
    if(isset($_POST['register'])) {
        global $user;
        $user->register($_POST['name'], $_POST['station'], $_POST['username'], $_POST['password']);
    }

    // If the form is submitted for loging in user
    if(isset($_POST['login'])) {
        global $user;
        $user->logIn($_POST['station'], $_POST['password']);

    }

?>

<div class="container">

    <div class="page-header">
        <h1 style = "color:#ffffff;">Good Shepherd Hospital Panabo Inc.<br><small>Web SMS</small></h1>
    </div>

    <div class="row">

        <div class="col-md-4 divregister" style="display:none;" >
            <!-- Register new user -->
            <div class="panel panel-primary">
                <div class="panel-heading" >
                    <h3 class="panel-title" >Register Here</h3>
                </div>
                <div class="panel-body">
                    <!-- form -->
                    <form action="#" method="post">
                        <div class="form-group">
                            <label>Station</label>
                            <input type="name" class="form-control" name="name" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label>Station</label>
                            <select size="1" class="form-control" name="station" id="station">
                                <option value ="NS1">NS1</option>
                                <option value ="NS2">NS2</option>
                                <option value ="ER">ER</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                        <!--
                        <button type="submit" class="btn btn-primary" name="register">Register</button>
                        -->
                        <button type="submit" class="btn btn-default pull-right" name="login">Login</button>
                    </form>
                    <!-- end -->
                </div>
            </div>
            <!-- end -->
        </div>

        <div class="col-md-4">
            <!-- Login with existing user credentials -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Login Here</h3>
                </div>
                <div class="panel-body">
                    <!-- form -->
                    <form action="" method="post">

                            <label>Station</label>
                            <select size="1" class="form-control" name ="station" value="<?php $station?>" id="station">
                                <option value ="NS1">NS1</option>
                                <option value ="NS2">NS2</option>
                                <option value ="ER">ER</option>
                                <option value ="Admin">ADMIN</option>
                            </select>
                            <br>
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
				<br>
				<!--
                        	<input type="submit" value="Login"  class="btn btn-primary" >
                        	-->
				<button type="submit" class="btn btn-default pull-right" name="login">Login</button>
                        	<!--
                        	<a id = "registerbut" type="submit" href="register.php" class="btn btn-default">Register</a>
                        	-->
                    </form>
                    <br>
                    <!-- end -->
                </div>
            </div>
            <!-- end -->
        </div>


        <div class="col-md-4" style="display: none;">
            <!-- List of all existing users -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">List of all registered users</h3>
                </div>
                <div class="panel-body">
                    <?php
                        $fetchUsers = new User();
                        $fetchUsers->fetchAllUsers();
                    ?>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</div> <!-- /container -->

<?php require_once 'includes/footer.php'; ?>


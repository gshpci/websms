<?php
    /**
     * Author: Bora
     * Contact: manasijevic.bora@gmail.com
     * Date: 11/13/2015
     * Project name: RegistrationLoginSystem_oop
     * File name: index.php
     * Desc:
     */
?>
<?php require_once 'includes/header.php'; ?>

<?php
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
<div class="container">

    <div class="page-header">
        <h1>Good Shepherd Hospital Panabo Inc. <small>SMS Notification</small></h1>
    </div>

    <div class="row">
        <div class="col-md-4" style="display:none;">
            <!-- Login with existing user credentials -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Login Here</h3>
                </div>
                <div class="panel-body">
                    <!-- form -->
                    <form action="" method="post">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary" name="login">Login</button>
                    </form>
                    <br>
                    <!-- end -->
                </div>
            </div>
            <!-- end -->
        </div>

        <div class="col-md-4" >
            <!-- Register new user -->
            <div class="panel panel-primary">
                <div class="panel-heading" >
                    <h3 class="panel-title" >Register Here</h3>
                </div>
                <div class="panel-body">
                    <!-- form -->
                    <form action="" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="name" class="form-control" name="name" placeholder="Name">
                        </div>
                        <div class="form-group"> 
                            <label>Station</label>
                            <select size="1" class="form-control" name="station"   name="smtp_secure" id="smtp_secure">
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
                        <button type="submit" class="btn btn-primary" name="register">Register</button>
                        <a href="index.php" type="submit" class="btn btn-default" name="login">Login</a>
                    </form>
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


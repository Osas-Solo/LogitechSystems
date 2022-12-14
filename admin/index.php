<?php
require_once("../entities.php");

if (isset($_POST["login"])) {
    login_admin($database_connection);
}   //  end of if submit is set

$page_title = "Admin Login";
require_once "header.php";
?>

<div class="col-12 header-followup">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12 mt-3 text-center text-uppercase">
            <h2><?php echo $page_title;?></h2>
        </div>
    </div>

    <main class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 mx-auto bg-white py-3 mb-4">
            <div class="row">
                <div class="col-12">
                    <form class="was-validated" action="index.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" required
                                   value="<?php
                                   if (isset($_POST["username"])) {
                                       echo $_POST["username"];
                                   }
                                   ?>" onfocus="hideUserNameErrorMessage()">
                            <div id="username-error-message">
                                <?php
                                display_username_error_message($database_connection);
                                ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control"
                                   required onfocus="hidePasswordErrorMessage()">
                            <div id="password-error-message">
                                <?php
                                display_password_error_message($database_connection);
                                ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit"  name="login" class="btn btn-outline-dark">Login</button>
                        </div>
                    </form>

                    <script src="../js/login-validation.js"></script>
                </div>
            </div>
        </div>

    </main>
    <!-- Main Content -->
</div>

<?php
/**
 * @param mysqli $database_connection
 */
function display_username_error_message(mysqli $database_connection) {
    if(isset($_POST["username"])) {
        if (!is_name_valid($_POST["username"])) {
            echo "Please enter a username";
        } else {
            $is_username_in_use = is_admin_username_in_use($database_connection);

            if (!$is_username_in_use) {
                echo $_POST["username"] . " not found";
            }
        }   //  end of else
    }
}

/**
 * @param mysqli $database_connection
 */
function display_password_error_message(mysqli $database_connection) {
    if (isset($_POST["password"])) {
        $admin = new Admin($database_connection, $_POST["username"], $_POST["password"]);

        if ($admin->password == null) {
            echo "Sorry, the password you entered is incorrect";
        }   //  end of if password is null
    }   //  end of if password is set
}

/**
 * @param mysqli $database_connection
 */
function login_admin(mysqli $database_connection) {
    $username = cleanse_data($_POST["username"], $database_connection);
    $password = cleanse_data($_POST["password"], $database_connection);

    $admin = new Admin($database_connection, $username, $password);

    if ($admin->is_found()) {
        session_start();
        $_SESSION["admin"] = $admin->username;

        $alert = "<script>
                    if (confirm('Login successful.')) {";
        $dashboard_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/dashboard.php";
        $alert .=           "window.location.replace('$dashboard_url');
                    } else {";
        $alert .=           "window.location.replace('$dashboard_url');
                    }";
        $alert .= "</script>";

        echo $alert;
    }
}

/**
 * @param mysqli $database_connection
 * @return int
 */
function is_admin_username_in_use(mysqli $database_connection): int {
    $is_username_in_use = 0;

    $admin = new Admin($database_connection, $_POST["username"]);

    if ($admin->is_found()) {
        $is_username_in_use = 1;
    }

    return $is_username_in_use;   //  end of if username is null
}

$database_connection->close();
require_once "footer.php";
?>
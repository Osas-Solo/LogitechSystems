<?php
$page_title = "Signup";
require_once "header.php";

if (isset($_SESSION["username"])) {
    session_destroy();
}

if (isset($_POST["signup"])) {
    signup_customer($database_connection);
}
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
                                <form class="was-validated" action="signup.php" method="post">
                                    <div class="mb-4">
                                        <label for="first-name" class="form-label">First Name</label>
                                        <input type="text" id="first-name" name="first-name" class="form-control" required
                                            value="<?php
                                            if (isset($_POST["first-name"])) {
                                                echo $_POST["first-name"];
                                            }
                                            ?>">
                                        <div class="invalid-feedback">Please enter a first name</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="last-name" class="form-label">Last Name</label>
                                        <input type="text" id="last-name" name="last-name" class="form-control" required
                                               value="<?php
                                               if (isset($_POST["last-name"])) {
                                                   echo $_POST["last-name"];
                                               }
                                               ?>">
                                        <div class="invalid-feedback">Please enter a last name</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" id="username" name="username" class="form-control" required value="<?php
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
                                    <div class="mb-4">
                                        <label for="email-address" class="form-label">Email Address</label>
                                        <input type="email" id="email-address" name="email-address" class="form-control"
                                            required value="<?php
                                            if (isset($_POST["email-address"])) {
                                                echo $_POST["email-address"];
                                            }
                                            ?>" onfocus="hideEmailAddressErrorMessage()">
                                        <div id="email-address-error-message">
                                            <?php
                                            if (isset($_POST["email-address"])) {
                                                if (!is_email_address_valid($_POST["email-address"])) {
                                                    echo "Please enter a valid email address";
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="phone-number" class="form-label">Phone Number</label>
                                        <input type="text" id="phone-number" name="phone-number" class="form-control"
                                            maxlength="11" required value="<?php
                                            if (isset($_POST["phone-number"])) {
                                                echo $_POST["phone-number"];
                                            }
                                            ?>" onfocus="hidePhoneNumberErrorMessage()">
                                        <div id="phone-number-error-message">
                                            <?php
                                            if (isset($_POST["phone-number"])) {
                                                if (!is_phone_number_valid($_POST["phone-number"])) {
                                                    echo "Please enter a valid phone number";
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" id="password" name="password" class="form-control" required
                                               onchange="checkPasswordValidity()">
                                        <div>
                                            Password length should be at least 8 characters.
                                            Password must contain a lowercase character, uppercase character and a digit

                                            <br><br>
                                            <span id = "password-error-message"
                                            <?php
                                            if(isset($_POST["password"])) {
                                                if (!is_password_valid($_POST["password"])) {
                                                    echo "";
                                                } else {
                                                    echo "style = 'display: none'";
                                                }
                                            } else {
                                                echo "style = 'display: none'";
                                            }
                                            ?>
                                            >Please enter a valid password</span>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="confirm-password" class="form-label">Confirm Password</label>
                                        <input type="password" id="confirm-password" name="confirm-password"
                                               class="form-control" required onchange="checkPasswordConfirmation()">
                                        <div id = "confirm-password-error-message"
                                            <?php
                                            if (isset($_POST["confirm-password"])) {
                                                if (!is_password_confirmed($_POST["password"], $_POST["confirm-password"])) {
                                                    echo "";
                                                } else {
                                                    echo "style = 'display: none'";
                                                }
                                            } else {
                                                echo "style = 'display: none'";
                                            }
                                            ?>>
                                            Passwords do not match
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="form-check-inline">
                                            <label for="gender" class="form-check-label">Gender</label> <br>
                                            <input type="radio" name="gender" class="form-check-input" value="M" checked> Male
                                            <input type="radio" name="gender" class="form-check-input" value="F"> Female
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-outline-dark" name="signup">Signup</button>
                                    </div>

                                    <div class="form-group mt-4 font-weight-bold text-center">
                                        Already have an account? <a href="login.php">Login instead.</a>
                                    </div>
                                </form>

                                <script src="js/signup-validation.js"></script>
                            </div>
                        </div>
                    </div>

                </main>
                <!-- Main Content -->
            </div>

<?php
$database_connection->close();
require_once "footer.php";

/**
 * @param mysqli $database_connection
 */
function signup_customer(mysqli $database_connection) {
    $first_name = cleanse_data($_POST["first-name"], $database_connection);
    $last_name = cleanse_data($_POST["last-name"], $database_connection);
    $username = cleanse_data($_POST["username"], $database_connection);
    $email_address = cleanse_data($_POST["email-address"], $database_connection);
    $phone_number = cleanse_data($_POST["phone-number"], $database_connection);
    $gender = cleanse_data($_POST["gender"], $database_connection);
    $password = cleanse_data($_POST["password"], $database_connection);
    $password_confirmer = cleanse_data($_POST["confirm-password"], $database_connection);

    $is_username_in_use = is_username_in_use($database_connection);

    if (!$is_username_in_use) {
        if (is_name_valid($first_name) && is_name_valid($last_name) && is_name_valid($username)
            && is_email_address_valid($email_address) && is_password_valid($password)
            && is_password_confirmed($password, $password_confirmer) && is_phone_number_valid($phone_number)) {
            $insert_query = "INSERT INTO customers(username, first_name, last_name, gender, phone_number, email_address, 
                            password, delivery_address) VALUES 
                            ('$username', '$first_name', '$last_name', '$gender', '$phone_number', '$email_address', 
                             SHA('$password'), '')";

            if ($database_connection->connect_error) {
                die("Connection failed: " . $database_connection->connect_error);
            }

            if ($database_connection->query($insert_query)) {
                $alert = "<script>
                        if (confirm('You\'ve successfully completed your registration. You may now proceed to login.')) {";
                $login_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/login.php";
                $alert .= "window.location.replace('$login_url');
                        }";
                $alert .= "</script>";
                echo $alert;
            }
        }   //  end of if details are valid
    }
}

/**
 * @param mysqli $database_connection
 */
function display_username_error_message(mysqli $database_connection) {
    if (isset($_POST["username"])) {
        if (!is_name_valid($_POST["username"])) {
            echo "Please enter a username";
        } else {
            $is_username_in_use = is_username_in_use($database_connection);

            if ($is_username_in_use) {
                echo $_POST["username"] . " is already in use";
            }
        }   //  end of else
    }   //  if username is set
}
?>
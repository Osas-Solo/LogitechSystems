<?php
$page_title = "Update Profile";
require_once "header.php";
require_once "entities.php";

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    $login_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/login.php";
    header("Location: " . $login_url);
}

if (isset($_POST["update"])) {
    update_customer_profile($database_connection);
}
?>

    <div class="col-12 header-followup">
        <!-- Main Content -->
        <div class="row">
            <div class="col-12 mt-3 text-center text-uppercase">
                <h2><?php echo $page_title?></h2>
            </div>
        </div>

        <main class="row">
            <div class="col-12 bg-white py-3 mb-3">
                <div class="row">
                    <div class="col-lg-6 col-md-8 col-sm-10 mx-auto table-responsive">
                        <div class="row">
                            <div class="col-12">
                                <form class="was-validated" action="update-profile.php" method="post">
                                    <div class="mb-4">
                                        <label for="first-name" class="form-label">First Name</label>
                                        <input type="text" id="first-name" name="first-name" class="form-control" required
                                               value="<?php
                                               if (isset($_POST["first-name"])) {
                                                   echo $_POST["first-name"];
                                               } else {
                                                   echo $customer->first_name;
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
                                               } else {
                                                   echo $customer->last_name;
                                               }
                                               ?>">
                                        <div class="invalid-feedback">Please enter a last name</div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="email-address" class="form-label">Email Address</label>
                                        <input type="email" id="email-address" name="email-address" class="form-control"
                                               required value="<?php
                                            if (isset($_POST["email-address"])) {
                                                echo $_POST["email-address"];
                                            } else {
                                                echo $customer->email_address;
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
                                        } else {
                                            echo $customer->phone_number;
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
                                        <label for="password" class="form-label">Current Password</label>
                                        <input type="password" id="password" name="password" class="form-control" required
                                               onfocus="checkPasswordValidity()">
                                        <div id="password-error-message">
                                            <?php
                                            display_password_error_message($database_connection);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="form-check-inline">
                                            <label for="gender" class="form-check-label">Gender</label> <br>
                                            <input type="radio" name="gender" class="form-check-input" value="M"
                                                <?php
                                                if ($customer->is_male()) {
                                                    echo 'checked';
                                                }?>> Male
                                            <input type="radio" name="gender" class="form-check-input" value="F"
                                                <?php
                                                if ($customer->is_female()) {
                                                    echo 'checked';
                                                }?>> Female
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="delivery-address" class="form-label">Delivery Address</label>
                                        <textarea id="delivery-address" name="delivery-address" class="form-control" required>
                                         <?php
                                         if (isset($_POST["delivery-address"])) {
                                             echo $_POST["delivery-address"];
                                         } else {
                                             echo $customer->delivery_address;
                                         }
                                         ?>
                                        </textarea>
                                        <div class="invalid-feedback">Please enter a delivery address</div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-outline-dark" name="update">Update</button>
                                    </div>
                                </form>

                                <script src="js/signup-validation.js"></script>
                            </div>
                        </div>
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
function update_customer_profile(mysqli $database_connection) {
    $first_name = cleanse_data($_POST["first-name"], $database_connection);
    $last_name = cleanse_data($_POST["last-name"], $database_connection);
    $username = $_SESSION["username"];
    $email_address = cleanse_data($_POST["email-address"], $database_connection);
    $phone_number = cleanse_data($_POST["phone-number"], $database_connection);
    $gender = cleanse_data($_POST["gender"], $database_connection);
    $password = cleanse_data($_POST["password"], $database_connection);
    $delivery_address = cleanse_data($_POST["delivery-address"], $database_connection);

    $customer = new Customer($database_connection, $username, $password);;

    if ($customer->is_found()) {
        $update_query = "UPDATE customers SET first_name = '$first_name', last_name = '$last_name', 
            email_address = '$email_address', phone_number = '$phone_number', gender = '$gender', 
            delivery_address = '$delivery_address' WHERE user_id = $customer->user_id";

        if ($database_connection->connect_error) {
            die("Connection failed: " . $database_connection->connect_error);
        }

        if ($database_connection->query($update_query)) {
            $alert = "<script>
                    if (confirm('Profile update successful.')) {";
            $dashboard_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/dashboard.php";
            $alert .= "window.location.replace('$dashboard_url');
                    } else {";
            $alert .= "window.location.replace('$dashboard_url');
                    }";
            $alert .= "</script>";

            echo $alert;
        }
    }
}

/**
 * @param mysqli $database_connection
 */
function display_password_error_message(mysqli $database_connection) {
    if (isset($_POST["password"])) {
        $customer = new Customer($database_connection, $_SESSION["username"], $_POST["password"]);

        if ($customer->password == null) {
            echo "Sorry, the password you entered is incorrect";
        }   //  end of if password is null
    }   //  end of if password is set
}

$database_connection->close();
require_once "footer.php";
?>
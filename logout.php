<?php
session_start();

if (isset($_SESSION["username"])) {
    session_destroy();

    $alert = "<script>
                    if (confirm('Logout successful.')) {";
    $login_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/login.php";
    $alert .=           "window.location.replace('$login_url');
                    } else {";
    $alert .=           "window.location.replace('$login_url');
                    }";
    $alert .= "</script>";

    echo $alert;
} else {
    $login_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/login.php";
    header("Location: " . $login_url);
}
?>
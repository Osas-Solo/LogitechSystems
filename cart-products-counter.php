<?php
require_once "entities.php";

session_start();

if ($_SESSION["username"]) {
    $customer = new Customer($database_connection, $_SESSION["username"]);
    $number_of_cart_products = CartProduct::get_number_of_cart_products($database_connection, $customer->username);
    echo $number_of_cart_products;
}
?>

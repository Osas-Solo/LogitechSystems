<?php
session_start();
require_once "entities.php";

$product_id = $_REQUEST["product-id"];
$quantity = $_REQUEST["quantity"];

$alert = "";

if (!isset($_SESSION["username"])) {
    $alert = "Sorry, you'd have to login to add product $product_id to your cart.";
} else {
    $customer = new Customer($database_connection, $_SESSION["username"]);

    $cart_product = new CartProduct($database_connection, $customer->username, $product_id);
    $cart_query = "";

    if ($cart_product->is_found()) {
        $cart_query = "UPDATE cart_products SET quantity = (quantity + $quantity) WHERE user_id = $customer->user_id AND 
                                                         product_id = '$product_id'";
    } else {
        $cart_query = "INSERT INTO cart_products(user_id, product_id, quantity) VALUE 
                            ('$customer->user_id', '$product_id', $quantity)";
    }

    if ($database_connection->connect_error) {
        die("Connection failed: " . $database_connection->connect_error);
    }

    if ($database_connection->query($cart_query)) {
        $alert = "$quantity ";
        $alert .= ($quantity == 1) ? "quantity" : "quantities";
        $alert .= " of product $product_id has been successfully added to your cart.";
    }
}

echo $alert;
?>
<?php
session_start();
require_once "entities.php";

$username = "";

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
}

$cart_products = CartProduct::get_cart_products($database_connection, $username);

$infeasible_order_message = "";

foreach ($cart_products as $current_cart_product) {
    if (!$current_cart_product->is_order_feasible()) {
        $infeasible_order_message .= "Sorry, there are ";
        $infeasible_order_message .= ($current_cart_product->product->quantity_in_stock == 0) ? "no quantity of "  :
            "only " . $current_cart_product->product->quantity_in_stock . " quantities";
        $infeasible_order_message .= " left of " . $current_cart_product->product->product_name;
        $infeasible_order_message .= ". Please reduce your order quantity or remove this item from your cart.\n\n";
    }
}

echo $infeasible_order_message;
?>
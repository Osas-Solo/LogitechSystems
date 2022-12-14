<?php
$page_title = "Cart";
require_once "header.php";
require_once "entities.php";

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    $login_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/login.php";
    header("Location: " . $login_url);
}

if (isset($_POST["update"])) {
    update_cart($customer, $database_connection);
}

if (isset($_POST["checkout"])) {
    checkout_cart($customer, $database_connection);
}

$number_of_cart_products = CartProduct::get_number_of_cart_products($database_connection, $username);
$cart_products = CartProduct::get_cart_products($database_connection, $username);
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
                        <?php
                        if ($number_of_cart_products == 0) {
                            ?>
                            <div class="col-12 text-center">
                                <h2>Sorry, you haven't added any item to your cart yet. Please visit out
                                    <a href="store.php">store</a> to see available products.</h2>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="col-lg-6 col-md-8 col-sm-10 mx-auto table-responsive">
                                <form id="cart-update-form" class="row" method="post">
                                    <div class="col-12">
                                        <table class="table table-striped table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Total Amount</th>
                                                    <th></th>
                                                </tr>
                                            </thead>

                                            <tbody id="cart-products-table-body">
                                            <?php
                                            foreach ($cart_products as $current_cart_product) {
                                            ?>
                                                <tr class="cart-product-row">
                                                    <td class="p-3">
                                                        <a href="product.php?product-id=<?php echo $current_cart_product->
                                                        product->product_id?>">
                                                            <img src="<?php echo $current_cart_product->product->display_photo?>" class="img-fluid"> <br><br>
                                                            <span class="product-name"><?php echo $current_cart_product->product->product_name?></span>
                                                            <input type="text" name="cart-product-ids[]" style="display: none"
                                                                   required
                                                                   value="<?php echo $current_cart_product->product->product_id?>">
                                                        </a>
                                                    </td>
                                                    <td class="px-2">
                                                        <?php echo $current_cart_product->product->get_price()?>
                                                        <input type="number" style="display: none" class="cart-product-price"
                                                               name="cart-product-prices[]"
                                                               value="<?php echo $current_cart_product->product->price?>">
                                                    </td>
                                                    <td class="px-2">
                                                        <input class="cart-product-quantity" type="number" name="cart-product-quantities[]"
                                                            id="<?php echo $current_cart_product->product->product_id?>-quantity"
                                                            min="1" value="<?php echo $current_cart_product->quantity?>"
                                                            oninput="updateCartProductTotalAmount(
                                                                <?php echo $current_cart_product->product->price?>,
                                                                    document.getElementById('<?php echo $current_cart_product->
                                                                        product->product_id?>-quantity'),
                                                                    document.getElementById('<?php echo $current_cart_product->
                                                                        product->product_id?>-total-amount'))"
                                                               pattern="[0-9]" required>
                                                    </td>
                                                    <td id="<?php echo $current_cart_product->product->product_id?>-total-amount">
                                                        <?php echo $current_cart_product->get_total_amount()?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-link text-danger"
                                                                onclick="removeProductFromCart(event)" type="button">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            </tbody>

                                            <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right">Total</th>
                                                <th id="total-price">
                                                    <input type="text" id="transaction-reference" style="display: none"
                                                           name="transaction-reference">
                                                    <input type="text" id="total-products-price" style="display: none" value="<?php
                                                        echo CartProduct::calculate_total_price_of_cart_products($cart_products)
                                                        ?>">
                                                    <input type="text" id="email-address" style="display: none"
                                                           value="<?php echo $customer->email_address?>">
                                                    <?php echo CartProduct::get_total_price_of_cart_products($cart_products)?></th>
                                                <th></th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                        <script src="js/cart-updater.js"></script>
                                        <script src="js/checkout.js"></script>
                                    </div>
                                    <?php
                                    if ($number_of_cart_products > 0) {
                                    ?>
                                    <div class="col-12 text-right">
                                        <button id="update-button" class="btn btn-outline-secondary me-3" type="submit"
                                                name="update" onclick="setUpdateFormAttributes()">
                                            Update
                                        </button>
                                        <button id="checkout-button" class="btn btn-outline-success" type="submit"
                                                name="checkout" onclick="beginCheckoutProcess()">
                                            Checkout
                                        </button>
                                    </div>
                                    <script src = "https://js.paystack.co/v1/inline.js"></script>
                                    <?php
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>

                </main>
                <!-- Main Content -->
            </div>

<?php
/**
 * @param Customer $customer
 * @param mysqli $database_connection
 */
function update_cart(Customer $customer, mysqli $database_connection) {
    $insert_query = "";

    $product_ids = array();
    $quantities = array();

    if (isset($_POST["cart-product-ids"])) {
        foreach ($_POST["cart-product-ids"] as $current_product_id) {
            array_push($product_ids, $current_product_id);
        }

        foreach ($_POST["cart-product-quantities"] as $current_quantity) {
            array_push($quantities, $current_quantity);
        }

        $number_of_products = count($product_ids);

        for ($i = 0; $i < $number_of_products; $i++) {
            $insert_query .= "INSERT INTO cart_products (user_id, product_id, quantity) VALUE 
                            ($customer->user_id, '$product_ids[$i]', $quantities[$i]);";
        }
    }

    $delete_previous_cart_products_query = "DELETE FROM cart_products WHERE user_id = $customer->user_id;";

    if ($database_connection->connect_error) {
        die("Connection failed: " . $database_connection->connect_error);
    }

    if ($database_connection->query($delete_previous_cart_products_query)) {
        if (count($product_ids)) {
            $database_connection->multi_query($insert_query);
        }

        $alert = "<script>
                    if (confirm('Cart updated successfully.')) {";
        $cart_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/cart.php";
        $alert .= "window.location.replace('$cart_url');
                    } else {";
        $alert .= "window.location.replace('$cart_url');
                    }";
        $alert .= "</script>";

        echo $alert;
    }
}

function checkout_cart(Customer $customer, mysqli $database_connection) {
    $order_insert_query = "";
    $update_products_query = "";
    $transaction_reference = $_POST["transaction-reference"];
    $order_date = date("Y-m-d");

    $cart_products = CartProduct::get_cart_products($database_connection, $customer->username);

    foreach ($cart_products as $current_cart_product) {
        $product_id = $current_cart_product->product->product_id;
        $quantity = $current_cart_product->quantity;
        $price = $current_cart_product->product->price;

        $order_insert_query .= "INSERT INTO orders (transaction_reference, product_id, amount_paid, quantity, order_date,
                                user_id, is_delivered) VALUE 
                                ('$transaction_reference', '$product_id', $price, $quantity, 
                                 '$order_date', $customer->user_id, 0);";

        $update_products_query .= "UPDATE products SET quantity_in_stock = quantity_in_stock - $quantity 
                                    WHERE product_id = '$product_id';";
    }

    $cart_removal_query = "DELETE FROM cart_products WHERE user_id = $customer->user_id";

    if ($database_connection->connect_error) {
        die("Connection failed: " . $database_connection->connect_error);
    }

    if ($database_connection->multi_query($order_insert_query)) {
        if ($database_connection->multi_query($update_products_query)) {
            if ($database_connection->query($cart_removal_query)) {
                $alert = "<script>
                    if (confirm('Order made successfully')) {";
                $order_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/order.php?transaction-reference=" . $transaction_reference;
                $alert .=           "window.location.replace('$order_url');
                    } else {";
                $alert .=           "window.location.replace('$order_url');
                    }";
                $alert .= "</script>";

                echo $alert;
            }
        }
    }
}

$database_connection->close();
require_once "footer.php";
?>

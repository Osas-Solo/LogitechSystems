<?php
$page_title = "Order";
require_once "header.php";
require_once "entities.php";

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    $login_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/login.php";
    header("Location: " . $login_url);
}

$transaction_reference = "\0";

if (isset($_GET['transaction-reference'])) {
    $transaction_reference = $_GET['transaction-reference'];
}

if (!empty($transaction_reference)) {
    $page_title .= " $transaction_reference";
}

$order_products = Order::get_order_products($database_connection, $customer->username, $transaction_reference);
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
                    if (count($order_products) == 0) {
                        ?>
                        <div class="col-12 text-center">
                            <h2>Sorry, the order with transaction reference: <?php echo $transaction_reference?> could not be found</h2>
                        </div>
                        <?php
                    } else {
                    ?>
                    <div class="col-lg-6 col-md-8 col-sm-10 mx-auto table-responsive">
                            <div class="col-12">
                                <table class="table table-striped table-hover table-sm">
                                    <thead>
                                    <tr>
                                        <th class="p-3">Product</th>
                                        <th class="px-2">Amount Paid</th>
                                        <th class="px-2">Quantity</th>
                                        <th class="px-2">Total Amount</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    foreach ($order_products as $current_order_product) {
                                        ?>
                                        <tr>
                                            <td class="p-3">
                                                <a href="product.php?product-id=<?php echo $current_order_product->
                                                product->product_id?>">
                                                    <img src="<?php echo $current_order_product->product->display_photo?>"
                                                         class="img-fluid"> <br><br>
                                                    <span class="product-name">
                                                        <?php echo $current_order_product->product->product_name?>
                                                    </span>
                                                </a>
                                            </td>
                                            <td class="px-2">
                                                <?php echo $current_order_product->get_amount_paid()?>
                                            </td>
                                            <td class="px-2">
                                                <?php echo $current_order_product->quantity?>
                                            </td>
                                            <td class="px-2">
                                                <?php echo $current_order_product->get_total_amount()?>
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
                                                <?php echo Order::get_total_price_of_order($order_products)?></th>
                                            <th></th>
                                        </tr>

                                        <tr>
                                            <th colspan="3">Delivery Status</th>
                                            <th><?php echo Order::get_delivery_status($order_products)?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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
$database_connection->close();
require_once "footer.php";
?>
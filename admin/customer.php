<?php
require_once("../entities.php");

$page_title = "Customer";

$username = "";

if (isset($_GET["username"])) {
    $username = $_GET["username"];
    $customer = new Customer($database_connection, $username);

    $page_title .= " $username";
}

require_once "header.php";

if (!isset($_SESSION["admin"])) {
    $login_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/index.php";
    header("Location: " . $login_url);
}

$order_products = Order::get_order_products($database_connection, $customer->username);
$distinct_orders = Order::get_distinct_orders($order_products);
$delivered_orders = Order::filter_orders_by_delivery_status($distinct_orders, 1);
$undelivered_orders = Order::filter_orders_by_delivery_status($distinct_orders, 0);
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
                    if (!$customer->is_found()) {
                        ?>
                        <div class="col-12 text-center">
                            <h2>Sorry, the customer with username: <?php echo $transaction_reference?> could not be found</h2>
                        </div>
                        <?php
                    } else {
                    ?>
                    <div class="col-lg-6 col-md-8 col-sm-10 mx-auto table-responsive">
                        <div class="col-12">
                            <table class="table table-striped table-hover table-sm text-center mb-5">
                                <h3 class="text-center">Customer Details</h3>

                                <tbody>
                                    <tr>
                                        <th class="p-2">Username</th>
                                        <td class="p-2"><?php echo $customer->username?></td>
                                    </tr>
                                    <tr>
                                        <th class="p-2">Name</th>
                                        <td class="p-2"><?php echo $customer->get_full_name()?></td>
                                    </tr>
                                    <tr>
                                        <th class="p-2">Gender</th>
                                        <td class="p-2"><?php echo $customer->gender?></td>
                                    </tr>
                                    <tr>
                                        <th class="p-2">Gender Address</th>
                                        <td class="p-2"><?php echo $customer->email_address?></td>
                                    </tr>
                                    <tr>
                                        <th class="p-2">Phone Number</th>
                                        <td class="p-2"><?php echo $customer->phone_number?></td>
                                    </tr>
                                    <tr>
                                        <th class="p-2">Delivery Address</th>
                                        <td class="p-2"><?php echo $customer->delivery_address?></td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-striped table-hover table-sm text-center mb-5">
                                <h3 class="text-center">Delivered Orders</h3>
                                <thead>
                                <tr>
                                    <th>Transaction Reference</th>
                                    <th>Order Date</th>
                                    <th>Delivery Address</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($delivered_orders as $current_delivered_order) {
                                    ?>
                                    <tr>
                                        <td class="p-2">
                                            <a href="order.php?transaction-reference=<?php echo $current_delivered_order->transaction_reference?>&username=<?php echo $current_delivered_order->customer->username?>">
                                                <?php echo $current_delivered_order->transaction_reference?>
                                            </a>
                                        </td>
                                        <td class="p-2">
                                            <?php echo convert_date_to_readable_form($current_delivered_order->order_date)?>
                                        </td>
                                        <td class="p-2">
                                            <?php echo $current_delivered_order->customer->delivery_address?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>

                            <table class="table table-striped table-hover table-sm text-center">
                                <h3 class="text-center">Undelivered Orders</h3>
                                <thead>
                                <tr>
                                    <th>Transaction Reference</th>
                                    <th>Order Date</th>
                                    <th>Delivery Address</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($undelivered_orders as $current_undelivered_order) {
                                    ?>
                                    <tr>
                                        <td class="p-2">
                                            <a href="order.php?transaction-reference=<?php echo $current_undelivered_order->transaction_reference?>&username=<?php echo $current_undelivered_order->customer->username?>">
                                                <?php echo $current_undelivered_order->transaction_reference?>
                                            </a>
                                        </td>
                                        <td class="p-2">
                                            <?php echo convert_date_to_readable_form($current_undelivered_order->order_date)?>
                                        </td>
                                        <td class="p-2">
                                            <?php echo $current_undelivered_order->customer->delivery_address?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
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
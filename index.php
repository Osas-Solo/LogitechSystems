<?php
$page_title = "Home";
require_once "header.php";
require_once "entities.php";

$featured_products = Product::get_products($database_connection, "", "lpt");
$recent_orders = Order::get_order_products($database_connection);
?>

<div class="col-12 header-followup">
                <!-- Main Content -->
                <main class="row">


                    <!-- Featured Products -->
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 py-3">
                                <div class="row">
                                    <div class="col-12 text-center text-uppercase">
                                        <h2>Featured Products</h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    for ($i = 0; $i < 4; $i++) {
                                    ?>
                                    <!-- Product -->
                                    <div class="col-lg-3 col-sm-6 my-3">
                                        <div class="col-12 bg-white text-center h-100 product-item">
                                            <div class="row h-100">
                                                <div class="col-12 p-0 mb-3">
                                                    <a href="product.php?product-id=<?php echo $featured_products[$i]->product_id?>">
                                                        <img src="<?php echo $featured_products[$i]->display_photo?>"
                                                             class="img-fluid">
                                                    </a>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <a href="product.php?product-id=<?php echo $featured_products[$i]->product_id?>"
                                                       class="product-name">
                                                        <?php echo $featured_products[$i]->product_name?>
                                                    </a>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <span class="product-price">
                                                        <?php echo $featured_products[$i]->get_price()?>
                                                    </span>
                                                </div>
                                                <div class="col-12 mb-3 align-self-end">
                                                    <button class="btn btn-outline-dark"  type="button"
                                                            onclick="updateCart('<?php echo $featured_products[$i]->product_id?>', 1)">
                                                        <i class="fas fa-cart-plus me-2"></i>Add to cart
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Product -->
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Featured Products -->

                    <?php
                    if (count($recent_orders) > 0) {
                    ?>
                    <div class="col-12">
                        <hr>
                    </div>

                    <!-- Recently Ordered Products -->
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 py-3">
                                <div class="row">
                                    <div class="col-12 text-center text-uppercase">
                                        <h2>Recently Ordered Products</h2>
                                    </div>
                                </div>
                                <div class="row">

                                    <?php
                                    for ($i = 0; $i < 4; $i++) {
                                    ?>
                                        <!-- Product -->
                                        <div class="col-lg-3 col-sm-6 my-3">
                                            <div class="col-12 bg-white text-center h-100 product-item">
                                                <div class="row h-100">
                                                    <div class="col-12 p-0 mb-3">
                                                        <a href="product.php?product-id=<?php echo $recent_orders[$i]->product->product_id?>">
                                                            <img src="<?php echo $recent_orders[$i]->product->display_photo?>"
                                                                 class="img-fluid">
                                                        </a>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <a href="product.php?product-id=<?php echo $recent_orders[$i]->product->product_id?>"
                                                           class="product-name">
                                                            <?php echo $recent_orders[$i]->product->product_name?>
                                                        </a>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                    <span class="product-price">
                                                        <?php echo $recent_orders[$i]->product->get_price()?>
                                                    </span>
                                                    </div>
                                                    <div class="col-12 mb-3 align-self-end">
                                                        <button class="btn btn-outline-dark"  type="button"
                                                                onclick="updateCart('<?php echo $recent_orders[$i]->product->product_id?>', 1)">
                                                            <i class="fas fa-cart-plus me-2"></i>Add to cart
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Product -->
                                    <?php
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Recently Ordered Products -->
                    <?php
                    }
                    ?>

                </main>
                <!-- Main Content -->
            </div>

    <script src="js/cart-updater.js"></script>

<?php
require_once "footer.php";
?>
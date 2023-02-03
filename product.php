<?php
$page_title = "Product";
require_once "header.php";

$product_id = $_GET['product-id'];

if (!empty($product_id)) {
    $page_title .= " $product_id";
}

$product = new Product($database_connection, $product_id);
?>

    <div class="col-12 header-followup">
                <!-- Main Content -->
                <main class="row">
                    <div class="col-12 bg-white py-3 my-3">
                        <div class="row">
                            <?php
                            if (!$product->is_found()) {
                            ?>
                            <div class="col-12 text-center">
                                <h2>Sorry, the product with id: <?php echo $product_id?> could not be found</h2>
                            </div>
                            <?php
                            } else {
                            ?>
                            <!-- Product Image -->
                            <div class="col-lg-5 col-md-12 mb-3">
                                <div class="col-12 mb-3">
                                    <div class="img-large border" style="background-image: url('<?php echo $product->display_photo?>')"></div>
                                </div>
                            </div>
                            <!-- Product Image -->

                            <!-- Product Info -->
                            <div class="col-lg-5 col-md-9">
                                <div class="col-12 product-name large">
                                    <?php echo $product->product_name?>
                                </div>
                                <div class="col-12 px-0">
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <?php
                                    $product->display_description();
                                    ?>
                                </div>
                            </div>
                            <!-- Product Info -->

                            <!-- Sidebar -->
                            <div class="col-lg-2 col-md-3 text-center">
                                <div class="col-12 sidebar h-100">
                                    <div class="row">
                                        <div class="col-12">
                                            <h2><?php echo $product->get_price()?></h2>
                                        </div>
                                        <div class="col-xl-5 col-md-9 col-sm-3 col-5 mx-auto mt-3">
                                            <div class="mb-3">
                                                <label for="<?php echo $product_id?>-quantity">Quantity</label>
                                                <input type="number" id="<?php echo $product_id?>-quantity" min="1"
                                                       value="1" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <button class="btn btn-outline-dark" type="button"
                                                    onclick="updateCart('<?php echo $product_id?>',
                                                            document.getElementById('<?php echo $product_id . '-quantity'?>')
                                                            .value)">
                                                <i class="fas fa-cart-plus me-2"></i>Add to cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Sidebar -->
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Similar Product -->
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 py-3">
                                <div class="row">
                                    <div class="col-12 text-center text-uppercase">
                                        <h2>Similar Products</h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    $similar_products = get_similar_products($product, $database_connection);

                                    foreach ($similar_products as $current_product) {
                                    ?>

                                    <!-- Product -->
                                    <div class="col-lg-3 col-sm-6 my-3">
                                        <div class="col-12 bg-white text-center h-100 product-item">
                                            <div class="row h-100">
                                                <div class="col-12 p-0 mb-3">
                                                    <a href="product.php?product-id=<?php echo $current_product->product_id?>">
                                                        <img src="<?php echo $current_product->display_photo?>" class="img-fluid">
                                                    </a>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <a href="product.php?product-id=<?php echo $current_product->product_id?>"
                                                       class="product-name"><?php echo $current_product->product_name?></a>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <span class="product-price">
                                                        <?php echo $current_product->get_price()?>
                                                    </span>
                                                </div>
                                                <div class="col-12 mb-3 align-self-end">
                                                    <button class="btn btn-outline-dark" type="button"
                                                            onclick="updateCart('<?php echo $current_product->product_id?>', 1)">
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
                    <!-- Similar Products -->

                </main>
                <!-- Main Content -->
            </div>

<?php
function get_similar_products(Product $product, mysqli $database_connection): array {
    $similar_products = array();

    if ($product->is_found()) {
        $similar_products = Product::get_products($database_connection, $product->product_name,
                                                    $product->product_category->product_category_id);

        $similar_products = remove_product_from_similar_product_list($similar_products, $product);
    } else {
        $search_text = $_GET["product-id"];

        $product_id_regex = "/(.*)-(.*)-(.*)/";

        if (preg_match($product_id_regex, $search_text, $match_groups)) {
            $search_text = $match_groups[2];
        }

        $similar_products = Product::get_products($database_connection, $search_text);
    }

    return $similar_products;
}

/**
 * @param array $similar_products
 * @param Product $product
 * @return array
 */
function remove_product_from_similar_product_list(array $similar_products, Product $product): array{
    foreach ($similar_products as $current_product) {
        if ($current_product->product_id == $product->product_id) {
            unset($similar_products[array_search($current_product, $similar_products)]);
        }
    }

    return $similar_products;
}

/**
 * @param array $products
 * @param Product $product
 */
function is_product_not_in_list(Product $current_product, Product $product) {
    return $product->product_id != $current_product->product_id;
}

?>

<script src="js/cart-updater.js"></script>
<?php
$database_connection->close();
require_once "footer.php";
?>
<?php
$page_title = "Store";
require_once "header.php";

if (!isset($_SESSION["admin"])) {
    $login_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/index.php";
    header("Location: " . $login_url);
}

$search_text = "";

if (isset($_GET["search-text"])) {
    $search_text = $_GET["search-text"];
}


$product_categories = ProductCategory::get_product_categories($database_connection);
$selected_product_category_ids = "";

foreach ($product_categories as $category) {
    if (isset($_GET[$category->product_category_id])) {
        $selected_product_category_ids .= "$category->product_category_id;";
    }
}

$minimum_price = 0;
$maximum_price = 0;

if (!empty($_GET["minimum-price"])) {
    $minimum_price = floatval($_GET["minimum-price"]);
}

if (!empty($_GET["maximum-price"])) {
    $maximum_price = floatval($_GET["maximum-price"]);
}

$page_number = 1;

if (isset($_GET["page"])) {
    $page_number = $_GET["page"];
}

$totalNumberOfSearchedProducts = 0;

$products = Product::get_products($database_connection, $search_text, $selected_product_category_ids,
    $minimum_price, $maximum_price, $page_number,
    $totalNumberOfSearchedProducts);

?>

    <div class="col-12 header-followup">
        <!-- Main Content -->
        <main class="row">

            <!-- Category Products -->
            <div class="col-12">
                <div class="row">
                    <div class="col-12 py-3">
                        <div class="row">
                            <div class="col-12 text-center text-uppercase">
                                <h2><?php echo $page_title;?></h2>
                            </div>
                        </div>

                        <form method="get" action="" id="apply-search-form">
                            <div class="row my-3 bg-white px-2 py-1">
                                <div class="col-form-legend bg-white text-center"><h4>Product Categories</h4></div>

                                <?php
                                foreach ($product_categories as $category) {
                                    ?>
                                    <div class="col mr-auto form-check">
                                        <input type="checkbox" id="<?php echo $category->product_category_id?>"
                                               name="<?php echo $category->product_category_id?>"
                                            <?php
                                            if (isset($_GET[$category->product_category_id])) {
                                                echo "checked";
                                            }
                                            ?>
                                               class="form-check-input">
                                        <label for="<?php echo $category->product_category_id?>"
                                               class="form-check-label ml-2"><?php echo $category->category_name?></label>
                                    </div>
                                    <?php
                                }
                                ?>

                                <div class="col mr-auto">
                                    <label for="minimum-price" class="form-label">Minimum Price</label>
                                    <input type="number" id="minimum-price" name="minimum-price" class="form-control"
                                           value="<?php
                                           if (isset($_GET["minimum-price"])) {
                                               echo $_GET["minimum-price"];
                                           }
                                           ?>" min="0">
                                </div>

                                <div class="col mr-auto">
                                    <label for="maximum-price" class="form-label">Maximum Price</label>
                                    <input type="number" id="maximum-price" name="maximum-price" class="form-control"
                                           value="<?php
                                           if (isset($_GET["maximum-price"])) {
                                               echo $_GET["maximum-price"];
                                           }
                                           ?>" min="0">
                                </div>

                                <div class="col">
                                    <button type="submit" name="apply" class="btn btn-outline-dark">Apply</button>
                                </div>
                            </div>
                        </form>

                        <div class="row">

                            <?php
                            foreach ($products as $current_product) {
                                ?>
                                <!-- Product -->
                                <div class="col-xl-2 col-lg-3 col-sm-6 my-3">
                                    <div class="col-12 bg-white text-center h-100 product-item">
                                        <div class="row h-100">
                                            <div class="col-12 p-0 mb-3">
                                                <a href="product.php?product-id=<?php echo $current_product->product_id?>">
                                                    <img src="../<?php echo $current_product->display_photo?>" class="img-fluid">
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
            <!-- Category Products -->

            <div class="col-12 text-center my-5">
                <a href="add-product.php" class="btn btn-outline-success mx-auto">Add Product</a>
            </div>

            <?php
            $products_per_page = 12;
            $number_of_pages = ceil($totalNumberOfSearchedProducts / 12);

            $selected_product_categories_query_parameters = convert_selected_categories_to_query_parameters($selected_product_category_ids);
            ?>

            <div class="col-12">
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php
                        if ($page_number == 1) {
                            echo "disabled";
                        }
                        ?>">
                            <a class="page-link" href="<?php
                            echo generate_page_link($page_number - 1, $selected_product_categories_query_parameters ) ?>" tabindex="-1" <?php
                            if ($page_number == 1) {
                                echo "aria-disabled=\"true\"";
                            }
                            ?>><i class="fas fa-long-arrow-alt-left"></i></a>
                        </li>
                        <?php
                        for ($i = 1; $i <= $number_of_pages; $i++) {
                            ?>
                            <li class="page-item <?php
                            if ($i == $page_number) {
                                echo "active\" aria-current=\"page\"";
                            }?>">
                                <a class="page-link" href="<?php
                                echo generate_page_link($i, $selected_product_categories_query_parameters ) ?>"><?php
                                    echo $i;
                                    if ($i == $page_number) {
                                        ?>
                                        <span class="sr-only">(current)</span>
                                        <?php
                                    }
                                    ?>
                                </a>
                            </li>
                            <?php
                        }
                        ?>

                        <li class="page-item <?php
                        if ($page_number == $number_of_pages) {
                            echo "disabled";
                        }
                        ?>">
                            <a class="page-link" href="<?php
                            echo generate_page_link($page_number + 1, $selected_product_categories_query_parameters ) ?>" tabindex="-1" <?php
                            if ($page_number == $number_of_pages) {
                                echo "aria-disabled=\"true\"";
                            }
                            ?>><i class="fas fa-long-arrow-alt-right"></i></a>
                        </li>

                    </ul>
                </nav>
            </div>

        </main>
        <!-- Main Content -->
    </div>

<?php
function generate_page_link (int $page_number, string $category_query_parameters) {
    $page_link = $_SERVER["PHP_SELF"] . "?";

    if (!empty($_GET["search-text"])) {
        $page_link .= "search-text=" . $_GET["search-text"];
    }

    if (!empty($category_query_parameters)) {
        $page_link .= $category_query_parameters;
    }

    if (!empty($_GET["minimum-price"])) {
        $page_link .= "&minimum-price=" . $_GET["minimum-price"];
    }

    if (!empty($_GET["maximum-price"])) {
        $page_link .= "&maximum-price=" . $_GET["maximum-price"];
    }

    $page_link .= "&page=" . $page_number;

    return $page_link;
}

function convert_selected_categories_to_query_parameters (string $selected_category_ids) {
    $separated_ids = explode(";", substr($selected_category_ids, 0, strlen($selected_category_ids) - 1));

    $parameters = "";

    foreach ($separated_ids as $id) {
        $parameters .= "&$id=on";
    }

    return $parameters;
}

$database_connection->close();
require_once "footer.php";
?>
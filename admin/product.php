<?php
$page_title = "Product";
require_once "header.php";

if (!isset($_SESSION["admin"])) {
    $login_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/index.php";
    header("Location: " . $login_url);
}

if (isset($_POST["update-product"])) {
    update_product($database_connection);
}

$product_id = $_GET['product-id'];

if (!empty($product_id)) {
    $page_title .= " $product_id";
}

$product = new Product($database_connection, $product_id);
$product_categories = ProductCategory::get_product_categories($database_connection);
?>

<div class="col-12 header-followup">
    <!-- Main Content -->
    <main class="row">
        <form action="product.php" method="post" enctype="multipart/form-data">
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
                                <img id="display-photo-preview" src="../<?php echo $product->display_photo?>"
                                     class="img-fluid d-block border">
                            </div>

                            <div class="col-12">
                                <label for="change-display-photo"><h2>Update Display Photo</h2></label>
                                <input type="file" id="change-display-photo" name="change-display-photo"
                                       accept="image/jpeg" class="form-control-file border"
                                       onchange="previewPhoto(event, '../<?php echo $product->display_photo?>')">
                            </div>
                        </div>
                        <script src="../js/product-utils.js"></script>
                        <!-- Product Image -->

                        <!-- Product Info -->
                        <div class="col-lg-5 col-md-9">
                            <div class="col-12">
                                <label><h2>Product ID</h2></label>
                                <input type="text" id="product-id" name="product-id"
                                       value="<?php echo $product->product_id?>" class="form-control" readonly
                                       required>
                            </div>
                            <div class="col-12 mb-5 px-0">
                                <hr>
                            </div>
                            <div class="col-12 product-name large">
                                <label><h2>Product Name</h2></label>
                                <textarea class="form-control" id="product-name" name="product-name" rows="5" required><?php echo $product->product_name?></textarea>
                            </div>
                            <div class="col-12 mb-5 px-0">
                                <hr>
                            </div>
                            <div class="col-12">
                                <label><h2>Product Category</h2></label>
                                <select class="form-select" name="product-category" id="product-category">
                                    <?php
                                    foreach ($product_categories as $current_product_category) {
                                        ?>
                                        <option value="<?php echo $current_product_category->product_category_id?>"
                                            <?php
                                            if ($current_product_category->product_category_id ==
                                                $product->product_category->product_category_id) {
                                                echo "selected";
                                            }
                                            ?>>
                                            <?php echo $current_product_category->category_name?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 mb-5 px-0">
                                <hr>
                            </div>
                            <div class="col-12">
                                <label for="brand-name"><h2>Brand Name</h2></label>
                                <input type="text" id="brand-name" name="brand-name"
                                       value="<?php echo $product->brand_name?>" class="form-control" required>
                            </div>
                            <div class="col-12 mb-5 px-0">
                                <hr>
                            </div>
                            <div class="col-12">
                                <label><h2>Description</h2></label>
                                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo $product->description?></textarea>
                            </div>
                        </div>
                        <!-- Product Info -->

                        <!-- Sidebar -->
                        <div class="col-lg-2 col-md-3 text-center">
                            <div class="col-12 sidebar h-100">
                                <div class="row">
                                    <div class="col-12 mx-0 mt-3">
                                        <div class="mb-3">
                                            <label for="price"><h2>Price (&#8358;)</h2></label>
                                            <input type="number" id="price" name="price" min="1"
                                                   value="<?php echo $product->price?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-12 mx-0 mt-3">
                                        <div class="mb-3">
                                            <label for="quantity-in-stock"><h2>Quantity in Stock</h2></label>
                                            <input type="number" id="quantity-in-stock" name="quantity-in-stock" min="0"
                                                   value="<?php echo $product->quantity_in_stock?>" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Sidebar -->

                        <div class="col-12 mt-5">
                            <button class="btn btn-outline-success mx-auto d-block" type="submit"
                                    id="update-product" name="update-product">
                                Update Product
                            </button>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </form>

    </main>
    <!-- Main Content -->
</div>

<?php
function update_product(mysqli $database_connection) {
    $product_id = cleanse_data($_POST["product-id"], $database_connection);;
    $product_name = cleanse_data($_POST["product-name"], $database_connection);
    $product_category = cleanse_data($_POST["product-category"], $database_connection);
    $brand_name = cleanse_data($_POST["brand-name"], $database_connection);
    $description = cleanse_data($_POST["description"], $database_connection);
    $price = cleanse_data($_POST["price"], $database_connection);
    $quantity_in_stock = cleanse_data($_POST["quantity-in-stock"], $database_connection);

    if (isset($_FILES["change-display-photo"])) {
        $display_photo = $_FILES["change-display-photo"];

        $target_directory = "../images/";
        $target_file = $target_directory . $product_id . ".jpg";

        move_uploaded_file($display_photo["tmp_name"], $target_file);
    }

    $update_query = "UPDATE products SET product_name = '$product_name', product_category_id = '$product_category', 
                        brand_name = '$brand_name', description = '$description', price = $price, 
                        quantity_in_stock = $quantity_in_stock
                        WHERE product_id = '$product_id'";

    if ($database_connection->connect_error) {
        die("Connection failed: " . $database_connection->connect_error);
    }

    if ($database_connection->query($update_query)) {
        $alert = "<script>
                    if (confirm('Product updated successfully.')) {";
        $product_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/product.php?";
        $product_url .= "product-id=$product_id";
        $alert .=           "window.location.replace('$product_url');
                    } else {";
        $alert .=           "window.location.replace('$product_url');
                    }";
        $alert .= "</script>";

        echo $alert;
    }
}

$database_connection->close();
require_once "footer.php";
?>
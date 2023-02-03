<?php
$page_title = "Add Product";
require_once "header.php";

if (!isset($_SESSION["admin"])) {
    $login_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/index.php";
    header("Location: " . $login_url);
}

if (isset($_POST["add-product"])) {
    add_product($database_connection);
}

$product_categories = ProductCategory::get_product_categories($database_connection);
?>

    <div class="col-12 header-followup">
        <div class="row">
            <div class="col-12 mt-3 text-center text-uppercase">
                <h2><?php echo $page_title?></h2>
            </div>
        </div>

        <!-- Main Content -->
        <main class="row">
            <form action="add-product.php" method="post" enctype="multipart/form-data" class="was-validated">
                <div class="col-12 bg-white py-3 my-3">
                    <div class="row">
                            <!-- Product Image -->
                            <div class="col-lg-5 col-md-12 mb-3">
                                <div class="col-12 mb-3">
                                    <img id="display-photo-preview" class="img-fluid d-block border">
                                </div>

                                <div class="col-12">
                                    <label for="display-photo"><h2>Display Photo</h2></label>
                                    <input type="file" id="display-photo" name="display-photo"
                                           accept="image/jpeg" class="form-control-file border" required
                                           onchange="previewPhoto(event, '')">
                                </div>
                            </div>
                            <script src="../js/product-utils.js"></script>
                            <!-- Product Image -->

                            <!-- Product Info -->
                            <div class="col-lg-5 col-md-9">
                                <div class="col-12">
                                    <label><h2>Product ID</h2></label><br>
                                    <span>
                                        Please use this format for the product id: <i>brd-xxxx</i>. Where brd is a
                                        short form of 2 or 3 letters only (no digits or special characters allowed) for
                                        the brand name .eg, HP would have a short form of <i>hp</i>, Samsung could have
                                        a short form of <i>smg</i>.<br>
                                        Then xxxxx could be anything from the product name.
                                    </span>
                                    <input type="text" id="product-id" name="product-id" class="form-control"
                                           required pattern="[a-zA-Z]{2,3}-[a-zA-Z0-9]+" maxlength="8"
                                           value="<?php if (isset($_POST['product-id'])) {
                                               echo $_POST['product-id'];
                                           }?>">
                                </div>
                                <div class="col-12 mb-5 px-0">
                                    <hr>
                                </div>
                                <div class="col-12 product-name large">
                                    <label><h2>Product Name</h2></label>
                                    <textarea class="form-control" id="product-name" name="product-name" rows="5"
                                              required><?php if (isset($_POST["product-name"])) {echo $_POST["product-name"];} ?></textarea>
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
                                                if (isset($_POST["product-category"])) {
                                                    if ($current_product_category->product_category_id ==
                                                        $_POST["product-category"]) {
                                                        echo "selected";
                                                    }
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
                                    <input type="text" id="brand-name" name="brand-name" class="form-control" required
                                           value="<?php if (isset($_POST['brand-name'])) {
                                               echo $_POST['brand-name'];
                                           }?>">
                                </div>
                                <div class="col-12 mb-5 px-0">
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <label><h2>Description</h2></label>
                                    <textarea class="form-control" id="description" name="description" rows="5"
                                              required><?php if (isset($_POST["description"])) {echo $_POST["description"];} ?></textarea>
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
                                                <input type="number" id="price" name="price" min="1" class="form-control"
                                                       required value="<?php if (isset($_POST['price'])) {
                                                            echo $_POST['price'];
                                                       }?>">
                                            </div>
                                        </div>
                                        <div class="col-12 mx-0 mt-3">
                                            <div class="mb-3">
                                                <label for="quantity-in-stock"><h2>Quantity in Stock</h2></label>
                                                <input type="number" id="quantity-in-stock" name="quantity-in-stock"
                                                       min="0" class="form-control" required
                                                       value="<?php if (isset($_POST['quantity-in-stock'])) {
                                                           echo $_POST['quantity-in-stock'];
                                                       }?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Sidebar -->

                            <div class="col-12 mt-5">
                                <button class="btn btn-outline-success mx-auto d-block" type="submit"
                                        id="add-product" name="add-product">
                                    Add Product
                                </button>
                            </div>
                    </div>
                </div>
            </form>

        </main>
        <!-- Main Content -->
    </div>

<?php
function add_product(mysqli $database_connection) {
    $product_category = cleanse_data($_POST["product-category"], $database_connection);
    $product_id = $product_category . "-" . cleanse_data($_POST["product-id"], $database_connection);
    $product_id = strtolower($product_id);
    $product_name = cleanse_data($_POST["product-name"], $database_connection);
    $brand_name = cleanse_data($_POST["brand-name"], $database_connection);
    $description = cleanse_data($_POST["description"], $database_connection);
    $price = cleanse_data($_POST["price"], $database_connection);
    $quantity_in_stock = cleanse_data($_POST["quantity-in-stock"], $database_connection);

    $product = new Product($database_connection, $product_id);

    if ($product->is_found()) {
        echo "<script>if (confirm('Sorry the product id you entered has already been assigned to another product.')) {
                        }</script>";

    } else if (is_product_id_valid($product_id) && is_textarea_filled($product_name) && is_name_valid($brand_name) &&
        is_textarea_filled($description) && is_numeric($price) && is_numeric($quantity_in_stock)) {
        if (isset($_FILES["display-photo"])) {
            $display_photo = $_FILES["display-photo"];

            $target_directory = "../images/";
            $target_file = $target_directory . $product_id . ".jpg";

            move_uploaded_file($display_photo["tmp_name"], $target_file);
        }

        $product_insert_query = "INSERT INTO products(product_id, product_name, brand_name, price, product_category_id, 
                                    display_photo, description, quantity_in_stock) VALUE 
                                    ('$product_id', '$product_name', '$brand_name', $price, '$product_category', 
                                     '$product_id.jpg', '$description', $quantity_in_stock)";


        if ($database_connection->connect_error) {
            die("Connection failed: " . $database_connection->connect_error);
        }

        if ($database_connection->query($product_insert_query)) {
            $alert = "<script>
                    if (confirm('Product added successfully.')) {";
            $product_url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/product.php?";
            $product_url .= "product-id=$product_id";
            $alert .=           "window.location.replace('$product_url');
                    } else {";
            $alert .=           "window.location.replace('$product_url');
                    }";
            $alert .= "</script>";

            echo $alert;
        }
    } else {
        echo "<script>if (confirm('Please ensure that every field is correctly filled and a display photo is uploaded')) {
                        }</script>";
    }
}

$database_connection->close();
require_once "footer.php";
?>
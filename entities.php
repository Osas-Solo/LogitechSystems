<?php
date_default_timezone_set("Africa/Lagos");
require_once("database-configuration.php");

class Customer {
    public $user_id;
    public $username;
    public $first_name;
    public $last_name; 
    public $gender;
    public $phone_number;
    public $email_address;
    public $delivery_address;
    public $password;

    function __construct(mysqli $database_connection = null, string $username = "", string $password = "") {
        if (isset($database_connection)) {
            $query = "SELECT * FROM customers WHERE username = '$username'";
            $query .= ($password != "") ? " AND password = SHA('$password')" : "";
    
            if ($database_connection->connect_error) {
                die("Connection failed: " . $database_connection->connect_error);
            }
    
            $query_result = $database_connection->query($query);
    
            if ($query_result->num_rows > 0) {
                $row = $query_result->fetch_assoc();
    
                $this->user_id = $row["user_id"];
                $this->username = $row["username"];
                $this->first_name = $row["first_name"];
                $this->last_name = $row["last_name"];
                $this->phone_number = $row["phone_number"];
                $this->email_address = $row["email_address"];
                $this->delivery_address = $row["delivery_address"];
                $this->password = $row["password"];

                switch ($row["gender"]) {
                    case 'M':
                        $this->gender = "Male";
                        break;
                    case 'F':
                        $this->gender = "Female";
                        break;
                }
            }   //  end of if number of rows > 0
        }   //  end of if $database_connection is set
    }   //  end of constructor

    function is_found(): bool {
        return $this->username != null;
    }

    public function get_full_name() {
        return $this->first_name . " " . $this->last_name;
    }

    function is_male(): bool {
        return $this->gender == "Male";
    }

    function is_female(): bool {
        return $this->gender == "Female";
    }

    public static function get_customers(mysqli $database_connection) {
        $customers = array();
        
        $query = "SELECT * FROM customers ORDER BY username";

        if ($database_connection->connect_error) {
            die("Connection failed: " . $database_connection->connect_error);
        }

        $query_result = $database_connection->query($query);

        if ($query_result->num_rows > 0) {
            while ($row = $query_result->fetch_assoc()) {
                $customer = new Customer();

                $customer->user_id = $row["user_id"];
                $customer->username = $row["username"];
                $customer->first_name = $row["first_name"];
                $customer->last_name = $row["last_name"];
                $customer->phone_number = $row["phone_number"];
                $customer->email_address = $row["email_address"];
                $customer->delivery_address = $row["delivery_address"];

                switch ($row["gender"]) {
                    case 'M':
                        $customer->gender = "Male";
                        break;
                    case 'F':
                        $customer->gender = "Female";
                        break;
                }


                array_push($customers, $customer);    
            }
        }   //  end of if number of rows > 0

        return $customers;
    }   //  end of get_customers()
}   //  end of Customer class

class Admin {
    public $username;
    public $password;

    function __construct(mysqli $database_connection = null, string $username = "", string $password = "") {
        if (isset($database_connection)) {
            $query = "SELECT * FROM admins WHERE username = '$username'";
            $query .= ($password != "") ? " AND password = SHA('$password')" : "";


            if ($database_connection->connect_error) {
                die("Connection failed: " . $database_connection->connect_error);
            }

            $query_result = $database_connection->query($query);

            if ($query_result->num_rows > 0) {
                $row = $query_result->fetch_assoc();

                $this->username = $row["username"];
                $this->password = $row["password"];
            }   //  end of if number of rows > 0
        }
    }   //  end of constructor

    function is_found(): bool {
        return $this->username != null;
    }
}   //  end of Admin class

class ProductCategory {
    public $product_category_id;
    public $category_name;

    function __construct(mysqli $database_connection = null, string $product_category_id = "") {
        if (isset($database_connection)) {
            $query = "SELECT * FROM product_categories WHERE product_category_id = '$product_category_id'";

            if ($database_connection->connect_error) {
                die("Connection failed: " . $database_connection->connect_error);
            }

            $query_result = $database_connection->query($query);

            if ($query_result->num_rows > 0) {
                $row = $query_result->fetch_assoc();

                $this->product_category_id = $row["product_category_id"];
                $this->category_name = $row["category_name"];
            }   //  end of if number of rows > 0
        }   //  end of if $database_connection is set
    }   //  end of constructor

    public static function get_product_categories(mysqli $database_connection) {
        $product_categories = array();
        
        $query = "SELECT * FROM product_categories ORDER BY category_name";

        if ($database_connection->connect_error) {
            die("Connection failed: " . $database_connection->connect_error);
        }

        $query_result = $database_connection->query($query);

        if ($query_result->num_rows > 0) {
            while ($row = $query_result->fetch_assoc()) {
                $product_category = new ProductCategory();

                $product_category->product_category_id = $row["product_category_id"];
                $product_category->category_name = $row["category_name"];

                array_push($product_categories, $product_category);
            }
        }   //  end of if number of rows > 0

        return $product_categories;
    }   //  end of get_product_categories()
}   //  end of ProductCategory class

class Product {
    public $product_id;
    public $product_name;
    public $brand_name;
    public $price;
    public ProductCategory $product_category;
    public $display_photo;
    public $description;
    public $quantity_in_stock;

    function __construct(mysqli $database_connection = null, string $product_id = "") {
        if (isset($database_connection)) {
            $query = "SELECT * FROM products WHERE product_id = '$product_id'";

            if ($database_connection->connect_error) {
                die("Connection failed: " . $database_connection->connect_error);
            }

            $query_result = $database_connection->query($query);

            if ($query_result->num_rows > 0) {
                $row = $query_result->fetch_assoc();

                $this->product_id = $row["product_id"];
                $this->product_name = $row["product_name"];
                $this->brand_name = $row["brand_name"];
                $this->price = $row["price"];
                $this->product_category = new ProductCategory($database_connection, $row["product_category_id"]);
                $this->display_photo = "images/" . $row["display_photo"];
                $this->description = $row["description"];
                $this->quantity_in_stock = $row["quantity_in_stock"];
            }   //  end of if number of rows > 0
        }   //  end of if $database_connection is set
    }   //  end of constructor

    public function is_found() {
        return $this->product_id != null;
    }

    public function get_price() {
        return "&#8358;" . number_format($this->price, 2);
    }

    public function display_description() {
        echo "<ul>";
        $split_description_lines = explode("\n", $this->description);

        foreach ($split_description_lines as $line) {
            echo "<li>$line</li>";
        }

        echo "</ul>";
    }

    public static function get_products(mysqli $database_connection, string $search_text = "",
                                        string $product_category_names = "", float $minimum_price = 0,
                                        float $maximum_price = 0, int $page_number = 0,
                                        int &$total_number_of_searched_products = 0) {
        $products = array();
        
        $query = "SELECT * FROM products";

        if (!empty($search_text)) {
            $query = self::append_keyword_to_query($query, "WHERE");

            $search_words = explode(" ", $search_text);

            if (count($search_words) > 1) {
                $search_words_query = array();

                foreach ($search_words as $word) {
                    $search_words_condition_list[] = "(product_name LIKE '%$word%' 
                        OR brand_name LIKE '%$word%'
                        OR description LIKE '%$word%')";
                }

                $query .= implode(" OR ", $search_words_condition_list);
            } else {
                $query .= "(product_name LIKE '%$search_text%' 
                    OR brand_name LIKE '%$search_text%'
                    OR description LIKE '%$search_text%')";
            }
        }

        if (!empty($product_category_names)) {
            $query = self::append_brackets_for_combined_conditions($query);

            $query = self::append_keyword_if_absent($query, "WHERE");
            $query = self::append_and_keyword_if_no_multiple_condition($query);

            $product_categories = explode(";", $product_category_names);

            if (count($product_categories) > 1) {
                foreach ($product_categories as $category) {
                    $product_categories_condition_list[] = " (product_category_id = '$category')";
                }

                $query .= implode(" OR ", $product_categories_condition_list);
            } else {
                $query .= " (product_category_id = '$product_category_names')";
            }
        }

        $query = self::append_brackets_for_combined_conditions($query);

        if ($minimum_price > 0) {
            $query = self::append_keyword_if_absent($query, "WHERE");
            $query = self::append_and_keyword_if_no_multiple_condition($query);

            $query .= " price >= $minimum_price";
        }

        if ($maximum_price > $minimum_price) {
            $query = self::append_keyword_if_absent($query, "WHERE");
            $query = self::append_and_keyword_if_no_multiple_condition($query);

            $query .= " price <= $maximum_price";
        }

        $total_number_of_searched_products = $database_connection->query($query)->num_rows;

        if (!empty($page_number)) {
            $maximum_number_of_products_in_a_page = 12;
            $limit_start = 0;

            if ($page_number == 1 || $page_number < 0) {
                $limit_start = 0;
            } else if ($page_number > 1) {
                $limit_start = ($page_number - 1) * $maximum_number_of_products_in_a_page;
            }

            $query .= " LIMIT $limit_start, $maximum_number_of_products_in_a_page";
        }

        if ($database_connection->connect_error) {
            die("Connection failed: " . $database_connection->connect_error);
        }

        $query_result = $database_connection->query($query);

        if ($query_result->num_rows > 0) {
            while ($row = $query_result->fetch_assoc()) {
                $product = new Product();

                $product->product_id = $row["product_id"];
                $product->product_name = $row["product_name"];
                $product->brand_name = $row["brand_name"];
                $product->price = $row["price"];
                $product->product_category = new ProductCategory($database_connection, $row["product_category_id"]);
                $product->display_photo = "images/" . $row["display_photo"];
                $product->description = $row["description"];
                $product->quantity_in_stock = $row["quantity_in_stock"];

                array_push($products, $product);    
            }
        }   //  end of if number of rows > 0

        return $products;
    }   //  end of get_products()

    /**
     * @param string $query
     * @return string
     */
    private static function append_keyword_if_absent(string $query, string $keyword): string {
        if (!self::is_keyword_in_query($query, $keyword)) {
            $query = self::append_keyword_to_query($query, $keyword);
        }

        return $query;
    }

    /**
     * @param string $query
     * @return string
     */
    private static function append_keyword_to_query(string $query, string $keyword): string {
        return $query .= " $keyword";
    }

    /**
     * @param string $query
     * @return bool
     */
    private static function is_keyword_in_query(string $query, string $keyword): bool {
        return strstr($query, $keyword);
    }

    /**
     * @param string $query
     * @return bool
     */
    private static function is_new_where_clause(string $query): bool {
        return substr($query, -5) == "WHERE";
    }

    /**
     * @param string $query
     */
    private static function append_and_keyword_if_no_multiple_condition(string $query): string {
        if (!self::is_new_where_clause($query)) {
            $query = self::append_keyword_to_query($query, "AND");
        }

        return $query;
    }

    private static function append_brackets_for_combined_conditions(string $query) {
        $query = str_replace("WHERE", "WHERE (", $query);

        if (strstr($query, "WHERE")) {
            $query .= ")";
        }

        $query = str_replace("AND", "AND (", $query);

        if (strstr($query, "AND")) {
            $query .= ")";
        }

        return $query;
    }
}   //  end of Product class

class CartProduct {
    public Customer $customer;
    public Product $product;
    public $quantity;

    function __construct(mysqli $database_connection = null, string $username = "", string $product_id = "") {
        if (isset($database_connection)) {
            $query = "SELECT * FROM cart_products p
                        INNER JOIN customers c ON p.user_id = c.user_id
                        WHERE username = '$username' AND product_id = '$product_id'";

            if ($database_connection->connect_error) {
                die("Connection failed: " . $database_connection->connect_error);
            }

            $query_result = $database_connection->query($query);

            if ($query_result->num_rows > 0) {
                $row = $query_result->fetch_assoc();

                $this->customer = new Customer($database_connection, $row["username"]);
                $this->product = new Product($database_connection, $row["product_id"]);
                $this->quantity = $row["quantity"];
            }   //  end of if number of rows > 0
        }   //  end of if $database_connection is set
    }   //  end of constructor

    public function get_total_amount() {
        return "&#8358;" . number_format(($this->calculate_total_amount()), 2);
    }

    private function calculate_total_amount() {
        return $this->quantity * $this->product->price;
    }

    public function is_order_feasible() {
        return $this->quantity <= $this->product->quantity_in_stock;
    }

    public static function get_number_of_cart_products(mysqli $database_connection, string $username): int {
        $total_number_of_cart_products = 0;

        $query = "SELECT COUNT(product_id) AS product_count FROM cart_products p
                        INNER JOIN customers c ON p.user_id = c.user_id
                        WHERE username = '$username'";

        if ($database_connection->connect_error) {
            die("Connection failed: " . $database_connection->connect_error);
        }

        $query_result = $database_connection->query($query);

        if ($query_result->num_rows > 0) {
            $row = $query_result->fetch_assoc();

            $total_number_of_cart_products = $row["product_count"];
        }   //  end of if number of rows > 0

        return $total_number_of_cart_products;
    }   //  end of get_number_of_cart_products()

    public function is_found() {
        return isset($this->product);
    }

    public static function get_cart_products(mysqli $database_connection, string $username) {
        $cart_products = array();

        $query = "SELECT * FROM cart_products p
                    INNER JOIN customers c ON p.user_id = c.user_id
                    WHERE username = '$username'
                    ORDER BY username";

        if ($database_connection->connect_error) {
            die("Connection failed: " . $database_connection->connect_error);
        }

        $query_result = $database_connection->query($query);

        if ($query_result->num_rows > 0) {
            while ($row = $query_result->fetch_assoc()) {
                $cart_product = new CartProduct();

                $cart_product->customer = new Customer($database_connection, $row["username"]);
                $cart_product->product = new Product($database_connection, $row["product_id"]);
                $cart_product->quantity = $row["quantity"];

                array_push($cart_products, $cart_product);
            }
        }   //  end of if number of rows > 0

        return $cart_products;
    }   //  end of get_cart_products()

    public static function get_total_price_of_cart_products(array $cart_products) {
        return "&#8358;" . number_format(self::calculate_total_price_of_cart_products($cart_products), 2);
    }

    public static function calculate_total_price_of_cart_products(array $cart_products) {
        return array_reduce($cart_products, function ($total, $current_product) {
            return $total + $current_product->calculate_total_amount();
        });
    }
}   //  end of CartProduct class

class Order {
    public $order_id;
    public $transaction_reference;
    public Product $product;
    public $amount_paid;
    public $quantity;
    public $order_date;
    public Customer $customer;
    public $is_delivered;

    function __construct(mysqli $database_connection = null, string $order_id = "") {
        if (isset($database_connection)) {
            $query = "SELECT * FROM orders o
                        INNER JOIN customers c ON o.user_id = c.user_id
                        WHERE order_id = '$order_id'";

            if ($database_connection->connect_error) {
                die("Connection failed: " . $database_connection->connect_error);
            }

            $query_result = $database_connection->query($query);

            if ($query_result->num_rows > 0) {
                $row = $query_result->fetch_assoc();

                $this->order_id = $row["order_id"];
                $this->transaction_reference = $row["transaction_reference"];
                $this->product = new Product($database_connection, $row["product_id"]);
                $this->amount_paid = $row["amount_paid"];
                $this->quantity = $row["quantity"];
                $this->order_date = $row["order_date"];
                $this->customer = new Customer($database_connection, $row["username"]);
                $this->is_delivered = $row["is_delivered"];
            }   //  end of if number of rows > 0
        }   //  end of if $database_connection is set
    }   //  end of constructor


    public function get_amount_paid() {
        return "&#8358;" . number_format(($this->amount_paid), 2);
    }

    public function get_total_amount() {
        return "&#8358;" . number_format(($this->calculate_total_amount()), 2);
    }

    private function calculate_total_amount() {
        return $this->quantity * $this->amount_paid;
    }

    public static function get_order_products(mysqli $database_connection, string $username = "",
                                              string $transaction_reference = "") {
        $orders = array();
        
        $query = "SELECT * FROM orders o 
                    INNER JOIN customers c ON o.user_id = c.user_id";

        if (!empty($transaction_reference)) {
            $query .= " WHERE transaction_reference = '$transaction_reference'";
        }

        if (!empty($username)) {
            if (empty($transaction_reference)) {
                $query .= " WHERE";
            } else {
                $query .= " AND";
            }

            $query .= " c.username = '$username'";
        }

        $query .= " ORDER BY order_date DESC";

        if ($database_connection->connect_error) {
            die("Connection failed: " . $database_connection->connect_error);
        }

        $query_result = $database_connection->query($query);

        if ($query_result->num_rows > 0) {
            while ($row = $query_result->fetch_assoc()) {
                $order = new Order();

                $order->order_id = $row["order_id"];
                $order->transaction_reference = $row["transaction_reference"];
                $order->product = new Product($database_connection, $row["product_id"]);
                $order->amount_paid = $row["amount_paid"];
                $order->quantity = $row["quantity"];
                $order->order_date = $row["order_date"];
                $order->customer = new Customer($database_connection, $row["username"]);
                $order->is_delivered = $row["is_delivered"];

                array_push($orders, $order);    
            }
        }   //  end of if number of rows > 0

        return $orders;
    }   //  end of get_order_products()

    public static function get_delivery_status(array $order_products) {
        if ($order_products[0]->is_delivered) {
            return "Delivered";
        } else {
            return "Undelivered";
        }
    }

    public static function is_delivered(array $order_products) {
        return self::get_delivery_status($order_products) == "Delivered";
    }

    public static function filter_orders_by_delivery_status(array $orders, int $is_delivered) {
        $delivered_orders = array();
        $undelivered_orders = array();

        foreach ($orders as $order) {
            if ($is_delivered) {
                if ($order->is_delivered) {
                    array_push($delivered_orders, $order);
                }
            } else if (!$is_delivered) {
                if (!$order->is_delivered) {
                    array_push($undelivered_orders, $order);
                }                
            }
        }   //  end of foreach

        if ($is_delivered) {
            return $delivered_orders;
        } else {
            return $undelivered_orders;
        }
    }   //  end of filter_orders_by_delivery_status()

    public static function get_distinct_orders(array $orders) {
        $distinct_orders = array();

        foreach ($orders as $current_order) {
            $is_order_already_distinct = false;
            foreach ($distinct_orders as $current_distinct_order) {
                if ($current_order->transaction_reference == $current_distinct_order->transaction_reference) {
                    $is_order_already_distinct = true;
                }
            }

            if (!$is_order_already_distinct) {
                array_push($distinct_orders, $current_order);
            }
        }

        return $distinct_orders;
    }   //  end of get_distinct_orders()

    public static function get_total_price_of_order(array $order_products) {
        return "&#8358;" . number_format(self::calculate_total_price_of_order($order_products), 2);
    }

    public static function calculate_total_price_of_order(array $order_products) {
        return array_reduce($order_products, function ($total, $current_product) {
            return $total + $current_product->calculate_total_amount();
        });
    }
}   //  end of Order class

function cleanse_data($data, $database_connection) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_escape_string($database_connection, $data);
    
    return $data;
}

function is_name_valid(string $name) {
    return strlen($name) > 0;
}

function is_email_address_valid(string $email_address) {
    $email_regex = "/^[A-Za-z0-9+_.-]+@(.+\..+)$/";

    return preg_match($email_regex, $email_address);
}

function is_phone_number_valid(string $phone_number) {
    $phone_number_regex = "/[0-9]{11}/";

    return preg_match($phone_number_regex, $phone_number);
}

function is_password_valid(string $password) {
    $lowercase_regex = "/[a-z]/";
    $uppercase_regex = "/[A-Z]/";
    $digit_regex = "/[0-9]/";

    return preg_match($lowercase_regex, $password) && preg_match($uppercase_regex, $password) 
            && preg_match($digit_regex, $password) && strlen($password) >= 8;
}

function is_password_confirmed(string $password, string $password_confirmer) {
    return $password == $password_confirmer;
}

function is_product_id_valid(string $product_id) {
    $product_id_regex = "/[a-zA-Z]{2,3}-[a-zA-Z0-9]+/";

    return preg_match($product_id_regex, $product_id) && strlen($product_id) <= 12;
}

function is_textarea_filled(string $text_area_text) {
    $text_area_regex = "/[a-zA-Z0-9]+/";

    return preg_match($text_area_regex, $text_area_text);
}

function convert_date_to_readable_form(string $reverse_date) {
    $reverse_date_regex = "/(\d{4})-(\d{2})-(\d{2})/";

    preg_match($reverse_date_regex, $reverse_date, $match_groups);

    $year = $match_groups[1];
    $month = $match_groups[2];
    $day = $match_groups[3];

    $month_names = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"];

    $month = $month_names[$month - 1];

    return $month . " " . $day . ", " . $year;
}

/**
 * @param mysqli $database_connection
 * @return int
 */
function is_username_in_use(mysqli $database_connection): int {
    $is_username_in_use = 0;

    $customer = new Customer($database_connection, $_POST["username"]);

    if ($customer->is_found()) {
        $is_username_in_use = 1;
    }

    return $is_username_in_use;   //  end of if username is null
}
?>
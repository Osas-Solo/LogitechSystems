<?php
require_once "../entities.php";

session_start();
$admin = new Admin();

if (isset($_SESSION["admin"])) {
    $admin = new Admin($database_connection, $_SESSION["admin"]);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logitech Systems - <?php echo $page_title ?></title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
<div class="container-fluid">

    <div class="row min-vh-100">
        <div class="col-12">
            <header class="row">

                <!-- Header -->
                <div class="col-12 bg-white pt-4">
                    <div class="row">
                        <div class="col-lg-auto">
                            <div class="site-logo text-center text-lg-left">
                                <a href="index.php"><img src="../images/logitech_systems_logo.png" alt="Logitech Systems"></a>
                            </div>
                        </div>
                    </div>

                    <?php
                    if ($admin->is_found()) {
                    ?>
                    <div class="col-lg-5 mx-auto mt-4 mt-lg-0">
                        <form action="store.php" method="get">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="search" id="search-text" name="search-text" class="form-control
                                            border-dark" placeholder="Search..." value="<?php
                                    if (isset($_GET["search-text"])) {
                                        echo $_GET["search-text"];
                                    }?>"" <?php
                                    if ($page_title == "Store") {
                                        echo "form='apply-search-form'";
                                    }
                                    ?>>
                                    <button class="btn btn-outline-dark" <?php
                                    if ($page_title == "Store") {
                                        echo "form='apply-search-form'";
                                    }
                                    ?>><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                    }
                    ?>

                    <!-- Nav -->
                    <div class="row">
                        <nav class="navbar navbar-expand-lg navbar-light bg-white col-12">
                            <button class="navbar-toggler d-lg-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="mainNav">
                                <ul class="navbar-nav mx-auto mt-2 mt-lg-0">
                                    <li class="nav-item active">
                                        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="store.php">Store</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                                    </li>
                                    <?php
                                    if ($admin->is_found()) {
                                        ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="logout.php">Logout</a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <!-- Nav -->

                </div>
                <!-- Header -->

            </header>
        </div>
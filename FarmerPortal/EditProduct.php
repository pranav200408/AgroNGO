<?php
include("../Includes/db.php");
session_start();

$sessphonenumber = $_SESSION['phonenumber'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <script
        src="https://kit.fontawesome.com/c587fc1763.js"
        crossorigin="anonymous"
    ></script>
    <link
        rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="../portal_files/bootstrap.min.css" />

    <title>Farmer - Edit Product</title>
    <style>
        @import url("https://fonts.googleapis.com/css?family=Raleway:300,400,600");

        body {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 400;
            line-height: 1.6;
            color: #212529;
            text-align: left;
            background-color: #f5f8fa;
        }

        .my-form,
        .login-form {
            font-family: Raleway, sans-serif;
        }

        .my-form {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .my-form .row,
        .login-form .row {
            margin-left: 0;
            margin-right: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <main class="my-form">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <?php
                            if (isset($_SESSION['phonenumber'])) {
                                $product_title = "";
                                $product_cat = "";
                                $product_type = "";
                                $product_stock = "";
                                $product_price = "";
                                $product_expiry = "";
                                $product_desc = "";
                                $product_keywords = "";
                                $product_delivery = "";
                                $id = 0;

                                if (isset($_GET['id'])) {
                                    $id = (int) $_GET['id']; // cast to int for safety
                                    $getting_prod = "SELECT * FROM products WHERE product_id = $id";
                                    $run = mysqli_query($con, $getting_prod);

                                    if ($details = mysqli_fetch_assoc($run)) {
                                        $product_title = htmlspecialchars($details['product_title']);
                                        $product_cat = $details['product_cat'];
                                        $product_type = htmlspecialchars($details['product_type']);
                                        $product_stock = htmlspecialchars($details['product_stock']);
                                        $product_price = htmlspecialchars($details['product_price']);
                                        $product_expiry = $details['product_expiry'];
                                        $product_desc = htmlspecialchars($details['product_desc']);
                                        $product_keywords = htmlspecialchars($details['product_keywords']);
                                        $product_delivery = $details['product_delivery'];
                                    }
                                }
                                
                            ?>

                            <div class="card-header">
                                <h4 class="text-center font-weight-bold">
                                    Edit Product <i class="fas fa-leaf"></i>
                                </h4>
                            </div>
                            <div class="card-body">
                                <form
                                    name="my-form"
                                    action="updateproduct.php"
                                    method="post"
                                    enctype="multipart/form-data"
                                >
                                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                    
                                    <div class="form-group row">
                                        <label
                                            for="product_title"
                                            class="col-md-4 col-form-label text-md-right text-center font-weight-bolder"
                                            >Product Title:</label
                                        >
                                        <div class="col-md-6">
                                            <input
                                                type="text"
                                                id="product_title"
                                                class="form-control"
                                                name="product_title"
                                                value="<?php echo $product_title; ?>"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            for="product_stock"
                                            class="col-md-4 col-form-label text-md-right text-center font-weight-bolder"
                                            >Product Stock: (In kg)</label
                                        >
                                        <div class="col-md-6">
                                            <input
                                                type="number"
                                                id="product_stock"
                                                class="form-control"
                                                name="product_stock"
                                                value="<?php echo $product_stock; ?>"
                                                min="0"
                                                step="any"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            for="product_cat"
                                            class="col-md-4 col-form-label text-md-right text-center font-weight-bolder"
                                            >Product Categories:</label
                                        >
                                        <div class="col-md-6">
                                            <select
                                                name="product_cat"
                                                id="product_cat"
                                                class="form-control"
                                                required
                                            >
                                                <option value="">Select a Category</option>
                                                <?php
                                                $get_cats = "SELECT * FROM categories";
                                                $run_cats = mysqli_query($con, $get_cats);
                                                while ($row_cats = mysqli_fetch_array($run_cats)) {
                                                    $cat_id = $row_cats['cat_id'];
                                                    $cat_title = $row_cats['cat_title'];
                                                    $selected = ($cat_id == $product_cat) ? "selected" : "";
                                                    echo "<option value='$cat_id' $selected>$cat_title</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            for="product_type"
                                            class="col-md-4 col-form-label text-md-right text-center font-weight-bolder"
                                            >Product Type:</label
                                        >
                                        <div class="col-md-6">
                                            <input
                                                type="text"
                                                id="product_type"
                                                class="form-control"
                                                name="product_type"
                                                value="<?php echo $product_type; ?>"
                                                placeholder="Example: potato"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            for="product_expiry"
                                            class="col-md-4 col-form-label text-md-right text-center font-weight-bolder"
                                            >Product Expiry:</label
                                        >
                                        <div class="col-md-6">
                                            <input
                                                type="date"
                                                id="product_expiry"
                                                class="form-control"
                                                name="product_expiry"
                                                value="<?php echo $product_expiry; ?>"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            for="product_image"
                                            class="col-md-4 col-form-label text-md-right text-center font-weight-bolder"
                                            >Product Image:</label
                                        >
                                        <div class="col-md-6">
                                            <input
                                                type="file"
                                                id="product_image"
                                                class="form-control-file"
                                                name="product_image"
                                                accept="image/*"
                                            />
                                            <small class="text-muted">Leave empty to keep current image</small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            for="product_price"
                                            class="col-md-4 col-form-label text-md-right text-center font-weight-bolder"
                                            >Product MRP (Per kg):</label
                                        >
                                        <div class="col-md-6">
                                            <input
                                                type="number"
                                                id="product_price"
                                                class="form-control"
                                                name="product_price"
                                                value="<?php echo $product_price; ?>"
                                                min="0"
                                                step="any"
                                                placeholder="Enter product price"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            for="product_desc"
                                            class="col-md-4 col-form-label text-md-right text-center font-weight-bolder"
                                            >Product Description:</label
                                        >
                                        <div class="col-md-6">
                                            <textarea
                                                id="product_desc"
                                                class="form-control"
                                                name="product_desc"
                                                rows="3"
                                                required
                                            ><?php echo $product_desc; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            for="product_keywords"
                                            class="col-md-4 col-form-label text-md-right text-center font-weight-bolder"
                                            >Product Keywords:</label
                                        >
                                        <div class="col-md-6">
                                            <input
                                                type="text"
                                                id="product_keywords"
                                                class="form-control"
                                                name="product_keywords"
                                                value="<?php echo $product_keywords; ?>"
                                                placeholder="Example: best potatoes"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-md-4 col-form-label text-md-right text-center font-weight-bolder"
                                            >Delivery:</label
                                        >
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="product_delivery"
                                                    id="delivery_yes"
                                                    value="yes"
                                                    <?php echo ($product_delivery === "yes") ? "checked" : ""; ?>
                                                    required
                                                />
                                                <label class="form-check-label" for="delivery_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="product_delivery"
                                                    id="delivery_no"
                                                    value="no"
                                                    <?php echo ($product_delivery === "no") ? "checked" : ""; ?>
                                                    required
                                                />
                                                <label class="form-check-label" for="delivery_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary" name="update_pro">
                                                UPDATE PRODUCT
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <?php
                            } else {
                                echo "<p class='text-danger text-center'>You must be logged in to access this page.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
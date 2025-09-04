<?php
include("../Includes/db.php");
session_start();

if (isset($_POST['update_pro'])) {
    // Get form data
    $product_id = (int)$_POST['product_id'];
    $product_title = mysqli_real_escape_string($con, $_POST['product_title']);
    $product_cat = (int)$_POST['product_cat'];
    $product_type = mysqli_real_escape_string($con, $_POST['product_type']);
    $product_stock = mysqli_real_escape_string($con, $_POST['product_stock']);
    $product_price = mysqli_real_escape_string($con, $_POST['product_price']);
    $product_expiry = mysqli_real_escape_string($con, $_POST['product_expiry']);
    $product_desc = mysqli_real_escape_string($con, $_POST['product_desc']);
    $product_keywords = mysqli_real_escape_string($con, $_POST['product_keywords']);
    $product_delivery = mysqli_real_escape_string($con, $_POST['product_delivery']);

    // Handle image upload if a new image is provided
    if (!empty($_FILES['product_image']['name'])) {
        $product_image = $_FILES['product_image']['name'];
        $product_image_tmp = $_FILES['product_image']['tmp_name'];
        move_uploaded_file($product_image_tmp, "../Admin/product_images/$product_image");
        
        // Update query with image
        $update_query = "UPDATE products SET 
                        product_title = '$product_title',
                        product_cat = '$product_cat',
                        product_type = '$product_type',
                        product_stock = '$product_stock',
                        product_price = '$product_price',
                        product_expiry = '$product_expiry',
                        product_image = '$product_image',
                        product_desc = '$product_desc',
                        product_keywords = '$product_keywords',
                        product_delivery = '$product_delivery'
                        WHERE product_id = '$product_id'";
    } else {
        // Update query without changing the image
        $update_query = "UPDATE products SET 
                        product_title = '$product_title',
                        product_cat = '$product_cat',
                        product_type = '$product_type',
                        product_stock = '$product_stock',
                        product_price = '$product_price',
                        product_expiry = '$product_expiry',
                        product_desc = '$product_desc',
                        product_keywords = '$product_keywords',
                        product_delivery = '$product_delivery'
                        WHERE product_id = '$product_id'";
    }

    $run_update = mysqli_query($con, $update_query);

    if ($run_update) {
        echo "<script>alert('Product has been updated successfully!')</script>";
        echo "<script>window.open('farmerHomepage.php','_self')</script>";
    } else {
        echo "<script>alert('Error updating product: " . mysqli_error($con) . "')</script>";
        echo "<script>window.open('editproduct.php?id=$product_id','_self')</script>";
    }
} else {
    echo "<script>alert('Invalid request')</script>";
    echo "<script>window.open('farmerHomepage.php','_self')</script>";
}
?>
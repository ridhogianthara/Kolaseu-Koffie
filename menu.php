<?php
session_start();
$conn = mysqli_connect('localhost','root','','food_order') or die('connection failed');
include('partial-front/menu.php');

// Determine active SESSION/TABLE ID
// Duplicate logic from index.php to ensure consistency across pages
if(isset($_SESSION['table_id'])){
    $active_id = "table_" . $_SESSION['table_id'];
} else {
    // If no table_id, we use session_id. Ensure it's set.
    if(!isset($_SESSION['session_id'])) {
        $_SESSION['session_id'] = session_id();
    }
    $active_id = $_SESSION['session_id'];
}

/* ================= ADD TO CART ================= */
if(isset($_POST['add_to_cart'])){

   $f_name = $_POST['f_name'];
   $f_price = $_POST['f_price'];
   $f_image = $_POST['f_image'];
   $f_quantity = 1;

   // Check if item exists IN THIS SESSION'S CART
   $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE food_name='$f_name' AND session_id='$active_id'");

   if(mysqli_num_rows($select_cart) > 0){
      $message[] = 'Food already added to cart.';
   }else{
      // Insert with session_id
      mysqli_query($conn, "INSERT INTO cart(food_name, price, img_name, qty, session_id)
      VALUES('$f_name','$f_price','$f_image','$f_quantity', '$active_id')");
      $message[] = 'Food added to cart.';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Menu</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

<!-- ================= NAVBAR ================= -->
<section class="nav_bar">
    <div class="container">
        <label class="logo">MENU</label>

        <ul>
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i> HOME
                </a>
            </li>

            <?php
            $select_rows = mysqli_query($conn, "SELECT * FROM cart WHERE session_id='$active_id'");
            $row_count = mysqli_num_rows($select_rows);
            ?>

            <li>
                <a href="checkout.php" class="cart">
                    <i class="fa fa-shopping-cart"></i>
                    CART | <span><?php echo $row_count; ?></span>
                </a>
            </li>
        </ul>
    </div>
</section>

<!-- ================= BACK BUTTON ================= -->
<section class="container" style="margin-top:90px;">
    <a href="categories.php" class="back_button">
        <i class="fa fa-arrow-circle-left"></i> Back
    </a>
</section>

<!-- ================= FOOD MENU ================= -->
<section class="menu container">
<?php
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "SELECT * FROM food WHERE category_id=$id";
    $res = mysqli_query($conn,$sql);

    if(mysqli_num_rows($res) > 0){
        while($fetch_food = mysqli_fetch_assoc($res)){
?>
    <form method="post">
        <div class="food_items">
            <img src="admin/upload_foodimg/<?php echo $fetch_food['img_name']; ?>" alt="Food">

            <div class="details">
                <div class="details2">
                    <h4><?php echo $fetch_food['food_name']; ?></h4>
                    <h4 class="price">Rp <?php echo number_format($fetch_food['price'],2); ?></h4>
                </div>

                <!-- ===== DESKRIPSI PRODUK (TAMBAHAN SAJA) ===== -->
                <p class="food_desc">
                    <?php echo $fetch_food['description']; ?>
                </p>

                <input type="hidden" name="f_name" value="<?php echo $fetch_food['food_name']; ?>">
                <input type="hidden" name="f_price" value="<?php echo $fetch_food['price']; ?>">
                <input type="hidden" name="f_image" value="<?php echo $fetch_food['img_name']; ?>">

                <input type="submit" name="add_to_cart" value="ADD TO CART" class="cart_button">
            </div>
        </div>
    </form>
<?php
        }
    }else{
        echo "<p>No food available</p>";
    }
}
?>
</section>

<?php include('partial-front/footer.php'); ?>
</body>
</html>

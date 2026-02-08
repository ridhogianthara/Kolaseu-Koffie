<?php
session_start();
$conn = mysqli_connect('localhost','root','','food_order') or die('connection failed');
include('partial-front/menu.php');

// Determine active SESSION/TABLE ID
if(isset($_SESSION['table_id'])){
    $active_id = "table_" . $_SESSION['table_id'];
} else {
    if(!isset($_SESSION['session_id'])) {
        $_SESSION['session_id'] = session_id();
    }
    $active_id = $_SESSION['session_id'];
}

if(isset($_POST['order_button'])){

   $name    = mysqli_real_escape_string($conn, $_POST['name']);
   $address = mysqli_real_escape_string($conn, $_POST['address']);

   // Set dummy values for removed fields
   $number  = "-";
   $email   = "-";
   $global_notes = "";

   // Ambil cart
   $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE session_id='$active_id'");
   if(mysqli_num_rows($cart_query) > 0){

      // Insert ke tabel order (customer)
      mysqli_query($conn, "INSERT INTO `order`
         (cus_name, cus_phone, cus_email, cus_add, notes)
         VALUES
         ('$name','$number','$email','$address','$global_notes')
      ");

      $order_id = mysqli_insert_id($conn);

      // Masukkan semua item ke order_items
      while($item = mysqli_fetch_assoc($cart_query)){
          $food  = mysqli_real_escape_string($conn, $item['food_name']);
          $item_notes = mysqli_real_escape_string($conn, $item['notes']);
          $qty   = intval($item['qty']);
          $price = floatval($item['price']);

          mysqli_query($conn, "INSERT INTO `order_items`
             (order_id, food_name, qty, price, notes)
             VALUES
             ($order_id, '$food', $qty, $price, '$item_notes')
          ");
      }

      // Kosongkan cart HANYA untuk user ini
      mysqli_query($conn, "DELETE FROM cart WHERE session_id='$active_id'");

      // Redirect ke halaman sukses
      header("Location: confirm.php?success=1");
      exit;

   } else {
       echo "<script>alert('Cart is empty!');</script>";
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

<!-- ================= NAVBAR ================= -->
<section class="nav_bar">
    <div class="container">
        <label class="logo">ORDER CONFIRM</label>
        <ul>
            <li><a href="index.php"><i class="fa fa-home"></i> HOME</a></li>
            <li><a href="checkout.php" class="cart"><i class="fa fa-shopping-cart"></i> CART</a></li>
        </ul>
    </div>
</section>

<!-- ================= SUCCESS MESSAGE ================= -->
<?php if(isset($_GET['success'])){ ?>
<section class="order_result">
   <div class="order_box">
      <h3>🎉 Thank you for your order!</h3>
      <p>Your order has been placed successfully.</p>
      <a href="index.php" class="ctn_button">Continue Ordering</a>
   </div>
</section>
<?php } ?>

<!-- ================= CONFIRM FORM ================= -->
<?php if(!isset($_GET['success'])){ ?>
<section class="confirm_section">
    <div class="container">
        <div class="confirm_box">
            <h2>Order Confirmation</h2>

            <form method="post" class="confirm_form">

                <div class="data">
                    <label>Name</label>
                    <input type="text" name="name" required>
                </div>

                <div class="data">
                    <label>Table No</label>
                    <input type="text" name="address" 
                           value="<?php echo isset($_SESSION['table_no']) ? $_SESSION['table_no'] : ''; ?>" 
                           <?php echo isset($_SESSION['table_no']) ? 'readonly style="background:#eee;"' : 'required'; ?> 
                    >
                </div>

                <input type="submit" name="order_button" value="PLACE ORDER" class="ctn_button">

            </form>
        </div>
    </div>
</section>
<?php } ?>

<?php include('partial-front/footer.php'); ?>
</body>
</html>

<?php
session_start();
$conn = mysqli_connect('localhost','root','','food_order') or die('connection failed');
include('partial-front/menu.php');

// CAPTURE TABLE NUMBER FROM URL OR SET DEFAULT SESSION
if(isset($_GET['table'])){
    $_SESSION['table_id'] = $_GET['table'];
    $_SESSION['table_no'] = $_GET['table']; // Keeping for backward compatibility if used elsewhere
}

// Generate a random session ID if not set (for non-QR users or fallback)
if(!isset($_SESSION['session_id']) && !isset($_SESSION['table_id'])) {
    $_SESSION['session_id'] = session_id();
}

// Determine the active ID for cart operations
$active_id = isset($_SESSION['table_id']) ? "table_" . $_SESSION['table_id'] : $_SESSION['session_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KOLASEU KOFFIEE</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

<!-- ================= NAVIGATION BAR ================= -->
<section class="nav_bar">
  <div class="container">

    <!-- LOGO DIUBAH JADI GAMBAR -->
    <label class="logo">
      <img src="css/img/kolaseu.png" alt="Kolaseu Koffiee" height="40">
    </label>

    <ul>
      <li>
        <a href="index.php">
          <i class="fa fa-home"></i> HOME
        </a>
      </li>

      <?php
        // Filter cart by active session ID
        $cart_query = "SELECT * FROM `cart` WHERE session_id = '$active_id'";
        $select_rows = mysqli_query($conn, $cart_query) or die('query failed');
        $row_count = mysqli_num_rows($select_rows);
      ?>

      <li>
        <a href="checkout.php" class="cart">
          <i class="fa fa-shopping-cart"></i>
          CART | <span><?php echo $row_count; ?></span>
        </a>
      </li>

      <li>
        <a href="admin/login.php">
          <i class="fa fa-sign-in"></i> LOGIN
        </a>
      </li>
    </ul>
  </div>
</section>

<!-- ================= HERO SECTION ================= -->
<section class="hero">
  <h1>
    Welcome to <br>
    <img src="css/img/kolaseulogo.png" height="50px" alt="logo"><br>
    KOLASEU KOFFIE
  </h1>

  <div class="center">
    <button class="ordernow_button">
      <a href="<?php echo HOMEURL; ?>categories.php">Order Now</a>
    </button>
  </div>
</section>

<?php include('partial-front/footer.php'); ?>
</body>
</html>

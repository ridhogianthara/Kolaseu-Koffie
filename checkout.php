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

/* ================= UPDATE QTY ================= */
if(isset($_POST['update_qty_btn'])){
    $update_value = intval($_POST['update_qty']);
    $update_id = intval($_POST['update_qty_id']);
    $update_notes = mysqli_real_escape_string($conn, $_POST['update_notes']);

    mysqli_query($conn, "UPDATE cart SET qty='$update_value', notes='$update_notes' WHERE id='$update_id' AND session_id='$active_id'");
    header('location:checkout.php');
    exit;
}

/* ================= REMOVE ITEM ================= */
if(isset($_GET['remove'])){
    $remove_id = intval($_GET['remove']);
    mysqli_query($conn, "DELETE FROM cart WHERE id='$remove_id' AND session_id='$active_id'");
    header('location:checkout.php');
    exit;
}

/* ================= DELETE ALL ================= */
if(isset($_GET['delete_all'])){
    mysqli_query($conn, "DELETE FROM cart WHERE session_id='$active_id'");
    header('location:checkout.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

<!-- ================= NAVBAR ================= -->
<section class="nav_bar">
    <div class="container">
        <label class="logo">MY CART</label>

        <ul>
            <li>
                <a href="index.php"><i class="fa fa-home"></i> HOME</a>
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

<!-- ================= CART TABLE ================= -->
<section class="cart_section">
    <div class="container">

        <table class="cart_table">
            <thead>
                <tr>
                    <th>Remove</th>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Notes</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
            <?php
            $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE session_id='$active_id'");
            $total = 0;

            if(mysqli_num_rows($select_cart) > 0){
                while($fetch_cart = mysqli_fetch_assoc($select_cart)){
                    $subtotal = $fetch_cart['price'] * $fetch_cart['qty'];
                    $total += $subtotal;
            ?>
                <tr>
                    <td>
                        <a href="checkout.php?remove=<?php echo $fetch_cart['id']; ?>"
                           onclick="return confirm('Remove this item?');"
                           class="del_button">✖</a>
                    </td>

                    <td>
                        <img src="admin/upload_foodimg/<?php echo $fetch_cart['img_name']; ?>" alt="">
                    </td>

                    <td><?php echo htmlspecialchars($fetch_cart['food_name']); ?></td>

                    <td>
                        <textarea form="form-<?php echo $fetch_cart['id']; ?>" name="update_notes" placeholder="misal 'less ice'" rows="2" style="width: 100%; resize: vertical;"><?php echo htmlspecialchars($fetch_cart['notes']); ?></textarea>
                    </td>

                    <td>Rp <?php echo number_format($fetch_cart['price'],2); ?></td>

                    <td>
                        <form method="post" class="qty_form" id="form-<?php echo $fetch_cart['id']; ?>">
                            <input type="hidden" name="update_qty_id" value="<?php echo $fetch_cart['id']; ?>">
                            <input type="number" name="update_qty" min="1"
                                   value="<?php echo $fetch_cart['qty']; ?>">
                            <input type="submit" name="update_qty_btn" value="Update">
                        </form>
                    </td>

                    <td>Rp <?php echo number_format($subtotal,2); ?></td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='6'>Cart is empty</td></tr>";
            }
            ?>
            </tbody>
        </table>

        <!-- ACTION BUTTONS -->
        <div style="margin-top:20px; display:flex; justify-content:space-between;">
            <a href="categories.php" class="ctn_button">Continue Ordering</a>
            <a href="checkout.php?delete_all"
               onclick="return confirm('Delete all items?');"
               class="del_button">
               Delete All
            </a>
        </div>

    </div>
</section>

<!-- ================= CHECKOUT SUMMARY ================= -->
<section class="checkout_section">
    <div class="container">
        <div class="total_box">
            <h5>CART TOTAL</h5>

            <div class="details3">
                <h6>Total</h6>
                <p>Rp <?php echo number_format($total,2); ?></p>
            </div>

            <button class="checkout_btn">
                <a href="confirm.php">CHECKOUT</a>
            </button>
        </div>
    </div>
</section>

<?php include('partial-front/footer.php'); ?>
</body>
</html>

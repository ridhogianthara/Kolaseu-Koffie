<?php 
include('partial_backend/navbar.php');
include('connectDB/connect.php');

// ================= VALIDASI PARAMETER =================
if(!isset($_GET['order_id'])){
    header('location:order.php');
    exit();
}

$order_id = intval($_GET['order_id']);

// ================= AMBIL DATA CUSTOMER =================
$sql_order = "SELECT * FROM `order` WHERE id = $order_id";
$res_order = mysqli_query($conn, $sql_order);

if(!$res_order || mysqli_num_rows($res_order) == 0){
    die("<div class='container'><p>Order not found.</p></div>");
}

$order = mysqli_fetch_assoc($res_order);
?>

<div class="main-content" style="background-color:white">
<br><br><br><br>

<div class="container">
  <h1>View Order</h1>
</div>

<div class="container">
  <a href="order.php" class="btn-addAdmin" style="width:18%">
    <i class="fa fa-arrow-circle-left"></i> Back
  </a>
</div>

<!-- ================= INFO CUSTOMER ================= -->
<div class="container" style="margin-top:20px;">
  <h3>Customer Details</h3>
  <p><strong>Name:</strong> <?php echo htmlspecialchars($order['cus_name']); ?></p>
  <p><strong>Table No:</strong> <?php echo htmlspecialchars($order['cus_add']); ?></p>
</div>

<!-- ================= ORDER ITEMS ================= -->
<div class="display_food" style="margin-top:20px;">
  <h3>Order Items</h3>
  <table border="1" cellpadding="8" cellspacing="0">
    <thead>
      <tr>
        <th>NO</th>
        <th>ITEM</th>
        <th>NOTES</th>
        <th>QUANTITY</th>
        <th>PRICE</th>
        <th>SUBTOTAL</th>
      </tr>
    </thead>
    <tbody>
<?php
// Ambil item dari tabel order_items
$sql_items = "SELECT * FROM `order_items` WHERE order_id = $order_id";
$res_items = mysqli_query($conn, $sql_items);

$total = 0;
$no = 1;

if($res_items && mysqli_num_rows($res_items) > 0){
    while($row = mysqli_fetch_assoc($res_items)){
        $food  = htmlspecialchars($row['food_name']);
        $item_notes = htmlspecialchars($row['notes']);
        $qty   = intval($row['qty']);
        $price = floatval($row['price']);
        $subtotal = $qty * $price;
        $total += $subtotal;
?>
      <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $food; ?></td>
        <td>
            <?php echo !empty($item_notes) ? $item_notes : '-'; ?>
        </td>
        <td><?php echo $qty; ?></td>
        <td>Rp <?php echo number_format($price, 2); ?></td>
        <td>Rp <?php echo number_format($subtotal, 2); ?></td>
      </tr>
<?php
    }
}else{
    echo "<tr><td colspan='6' style='text-align:center;'>No order items found</td></tr>";
}
?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="5" style="text-align:right;">TOTAL</th>
        <th>Rp <?php echo number_format($total, 2); ?></th>
      </tr>
    </tfoot>
  </table>
</div>

</div>

</body>
</html>

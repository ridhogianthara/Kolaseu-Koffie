<?php 
session_start();
include('partial_backend/navbar.php'); 
include('connectDB/connect.php');

// ================= PROSES FORM INPUT ORDER (SIMULASI) =================
if(isset($_POST['submit_order'])){
    // Ambil data customer
    $cus_name  = mysqli_real_escape_string($conn, $_POST['cus_name']);
    $cus_phone = mysqli_real_escape_string($conn, $_POST['cus_phone']);
    $cus_email = mysqli_real_escape_string($conn, $_POST['cus_email']);
    $cus_add   = mysqli_real_escape_string($conn, $_POST['cus_add']);

    // Insert ke tabel order
    $sql_order = "INSERT INTO `order` (cus_name, cus_phone, cus_email, cus_add)
                  VALUES ('$cus_name', '$cus_phone', '$cus_email', '$cus_add')";
    if(mysqli_query($conn, $sql_order)){
        // Ambil ID order yang baru saja dibuat
        $order_id = mysqli_insert_id($conn);

        // Insert ke tabel order_items jika ada
        if(isset($_POST['food_name']) && isset($_POST['qty']) && isset($_POST['price'])){
            $food_names = $_POST['food_name']; // array
            $qtys       = $_POST['qty'];       // array
            $prices     = $_POST['price'];     // array

            for($i=0; $i<count($food_names); $i++){
                $food  = mysqli_real_escape_string($conn, $food_names[$i]);
                $qty   = intval($qtys[$i]);
                $price = floatval($prices[$i]);

                $sql_item = "INSERT INTO `order_items` (order_id, food_name, qty, price)
                             VALUES ($order_id, '$food', $qty, $price)";
                mysqli_query($conn, $sql_item);
            }
        }

        $_SESSION['order_success'] = "<div class='popup'>Order berhasil disimpan!</div>";
        header("Location: order.php");
        exit;
    } else {
        $_SESSION['order_fail'] = "<div class='popup'>Gagal menyimpan order: ".mysqli_error($conn)."</div>";
        header("Location: order.php");
        exit;
    }
}
?>

<div class="main-content">

  <div class="container">
    <h1>Order Page</h1>
  </div>
  <hr>

  <!-- ================= POPUP MESSAGE ================= -->
  <div class="container">
    <?php
    if(isset($_SESSION['order_success'])){
        echo $_SESSION['order_success'];
        unset($_SESSION['order_success']);
    }
    if(isset($_SESSION['order_fail'])){
        echo $_SESSION['order_fail'];
        unset($_SESSION['order_fail']);
    }
    if(isset($_SESSION['delete'])){
        echo $_SESSION['delete'];
        unset($_SESSION['delete']);
    }
    ?>
  </div>

  <!-- ================= DISPLAY ORDER ================= -->
  <div class="container display_food">
    <table>
      <thead>
        <tr>
          <th>NO</th>
          <th>NAME</th>
          <th>TABLE NO</th>
          <th>ACTIONS</th>
        </tr>
      </thead>

      <tbody>
        <?php
        $i = 1;
        $sql = "SELECT * FROM `order` ORDER BY id DESC";
        $res = mysqli_query($conn, $sql);

        if($res && mysqli_num_rows($res) > 0){
          while($row = mysqli_fetch_assoc($res)){
            $order_id = $row['id'];
            $name     = htmlspecialchars($row['cus_name']);
            $phone    = htmlspecialchars($row['cus_phone']);
            $email    = htmlspecialchars($row['cus_email']);
            $address  = htmlspecialchars($row['cus_add']);
        ?>
        <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo $name; ?></td>
          <td><?php echo $address; ?></td>

          <td class="action-btn">
            <!-- VIEW -->
            <a href="viewOrder.php?order_id=<?php echo $order_id; ?>" 
               class="vieworder_button">
              View
            </a>

            <!-- DELETE -->
            <a href="deleteOrder.php?id=<?php echo $order_id; ?>"
               class="del_button"
               onclick="return confirm('Yakin ingin menghapus order ini?');">
               Delete
            </a>
          </td>
        </tr>
        <?php
          }
        } else {
          echo "<tr><td colspan='6'>No Order Found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

</div>

</body>
</html>

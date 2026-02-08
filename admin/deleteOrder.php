<?php
session_start(); // Jangan lupa start session
include('connectDB/connect.php');

// Validasi parameter id
if(isset($_GET['id'])){
    $id = intval($_GET['id']); // aman dari SQL Injection

    // ================= DELETE ORDER ITEMS TERKAIT =================
    $sql_items = "DELETE FROM `order_items` WHERE order_id = $id";
    mysqli_query($conn, $sql_items);

    // ================= DELETE ORDER =================
    $sql_order = "DELETE FROM `order` WHERE id = $id";
    $res_order = mysqli_query($conn, $sql_order);

    if($res_order){
        $_SESSION['delete'] = "<div class='popup'>Order berhasil dihapus</div>";
    } else {
        $_SESSION['delete'] = "<div class='popup'>Gagal menghapus order</div>";
    }

    // Redirect ke halaman order admin
    header('location:'.HOMEURL.'admin/order.php');
    exit;
} else {
    // Kalau tidak ada parameter id
    $_SESSION['delete'] = "<div class='popup'>ID order tidak valid</div>";
    header('location:'.HOMEURL.'admin/order.php');
    exit;
}
?>

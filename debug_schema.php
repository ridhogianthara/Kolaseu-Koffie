<?php
$conn = mysqli_connect('localhost','root','','food_order') or die('connection failed');

function verify_table($conn, $table) {
    echo "Checking table: $table\n";
    $query = mysqli_query($conn, "DESCRIBE `$table`");
    if($query) {
        while($row = mysqli_fetch_assoc($query)) {
            echo " - " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } else {
        echo " - Table does not exist or error: " . mysqli_error($conn) . "\n";
    }
    echo "\n";
}

verify_table($conn, 'cart');
verify_table($conn, 'order_items');
verify_table($conn, 'order');
?>

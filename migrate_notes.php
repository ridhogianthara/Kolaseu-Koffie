<?php
$conn = mysqli_connect('localhost','root','','food_order') or die('connection failed');

function add_column_if_not_exists($conn, $table, $column, $definition) {
    $check = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    if(mysqli_num_rows($check) == 0) {
        $sql = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
        if(mysqli_query($conn, $sql)) {
            echo "Successfully added column '$column' to table '$table'.\n";
        } else {
            echo "Error adding column '$column' to table '$table': " . mysqli_error($conn) . "\n";
        }
    } else {
        echo "Column '$column' already exists in table '$table'.\n";
    }
}

add_column_if_not_exists($conn, 'cart', 'notes', 'TEXT');
add_column_if_not_exists($conn, 'order_items', 'notes', 'TEXT');
?>

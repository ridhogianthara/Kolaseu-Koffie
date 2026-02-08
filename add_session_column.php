<?php
$conn = mysqli_connect('localhost','root','','food_order');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Add session_id column if it doesn't exist
$sql = "ALTER TABLE cart ADD COLUMN session_id VARCHAR(100) NOT NULL DEFAULT 'default'";

if (mysqli_query($conn, $sql)) {
    echo "Successfully added session_id column to cart table.\n";
} else {
    echo "Error adding column: " . mysqli_error($conn) . "\n";
}

// Verify structure
$result = mysqli_query($conn, "DESCRIBE cart");
while($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

mysqli_close($conn);
?>

<?php
$conn = mysqli_connect('localhost','root','','food_order') or die('connection failed');
include('partial-front/menu.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Categories</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

<!-- ================= NAVIGATION BAR ================= -->
<section class="nav_bar">
    <div class="container">
        <!-- logo kiri -->
        <label class="logo">KOLASEU KATEGORI</label>

        <!-- menu kanan -->
        <ul>
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i> HOME
                </a>
            </li>

            <?php
                $select_rows = mysqli_query($conn, "SELECT * FROM `cart`") or die('query failed');
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

<!-- ================= CATEGORIES ================= -->
<section class="categories">
    <div class="container_cat">

        <?php
            $sql = "SELECT * FROM category";
            $res = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($res);

            if ($count > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $image_name = $row['imgName'];
        ?>

        <a href="menu.php?id=<?php echo $id; ?>">
            <div class="box container_cat2">

                <?php
                    if ($image_name == "") {
                        echo "<p>Image not available</p>";
                    } else {
                ?>
                    <img 
                        src="<?php echo HOMEURL; ?>img/category/<?php echo $image_name; ?>" 
                        alt="<?php echo $title; ?>" 
                        class="img"
                    >
                <?php } ?>

                <h3 class="text_cat"><?php echo $title; ?></h3>
            </div>
        </a>

        <?php
                }
            }
        ?>

    </div>
</section>

<?php include('partial-front/footer.php'); ?>
</body>
</html>

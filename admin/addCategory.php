<?php 
include('partial_backend/navbar.php'); 
?>

<br><br><br>

<div class="main-content">
    <div class="container">

        <form action="" method="POST" class="add_food_form" enctype="multipart/form-data">
            <h3>ADD CATEGORY</h3>

            <div class="form-grid grid-2">
                
                <!-- CATEGORY NAME -->
                <div class="form-group">
                    <label>CATEGORY NAME</label>
                    <input type="text" name="title" placeholder="e.g: Food" class="box" required>
                </div>

                <!-- CATEGORY IMAGE -->
                <div class="form-group">
                    <label>CATEGORY IMAGE</label>
                    <input type="file" name="image" accept="image/png, image/jpg, image/jpeg" class="box" required>
                </div>

            </div>

            <div class="form-actions justify-end">
                <input type="reset" value="CANCEL" id="close-edit" class="cancel_button">
                <input type="submit" value="ADD CATEGORY" name="submit" class="add_button">
            </div>

        </form>

        <?php
        if(isset($_POST['submit']))
        {
            $title = $_POST['title'];
            $image_name = "";

            // Cek apakah file dipilih
            if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "")
            {
                $original_name = $_FILES['image']['name'];

                // Ambil ekstensi file dengan aman
                $extension = pathinfo($original_name, PATHINFO_EXTENSION);

                // Rename file
                $image_name = "Food_Category_" . rand(100,999) . "." . $extension;

                // Path upload
                $source_path = $_FILES['image']['tmp_name'];
                $destination_path = "../img/category/" . $image_name;

                // Upload file
                $upload = move_uploaded_file($source_path, $destination_path);

                // Jika upload gagal
                if($upload == false)
                {
                    $_SESSION['upload'] = "Failed to upload image";
                    header('location:'.HOMEURL.'admin/category.php');
                    die();
                }
            }

            // Insert ke database
            $sql = "INSERT INTO category SET
                    title='$title',
                    imgName='$image_name'
                   ";

            $res = mysqli_query($conn, $sql);

            if($res == true)
            {
                $_SESSION['addCategory'] = "Category added successfully";
                header('location:'.HOMEURL.'admin/category.php');
            }
            else
            {
                $_SESSION['addCategory'] = "Failed to add category";
                header('location:'.HOMEURL.'admin/category.php');
            }
        }
        ?>

    </div>
</div>

<script>
document.querySelector('#close-edit').onclick = () => {
    window.location.href = 'category.php';
};
</script>

</body>
</html>

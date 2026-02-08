<?php 
include('partial_backend/navbar.php'); 
?>

<div class="main-content">
  <div class="container">

    <?php
      // ================= GET DATA =================
      if(isset($_GET['id'])){
        $id = $_GET['id'];

        $sql = "SELECT * FROM category WHERE id=$id";
        $res = mysqli_query($conn, $sql);

        if(mysqli_num_rows($res) == 1){
          $row = mysqli_fetch_assoc($res);
          $title = $row['title'];
          $current_image = $row['imgName'];
        }else{
          $_SESSION['no-category-found'] = "Category not found";
          header('location:'.HOMEURL.'admin/category.php');
          exit;
        }
      }else{
        header('location:'.HOMEURL.'admin/category.php');
        exit;
      }
    ?>

    <!-- ================= FORM ================= -->
    <form action="" method="POST" class="add_food_form" enctype="multipart/form-data">
      <h3>Update Category</h3>

      <table>
        <tr>
          <td>Title:</td>
          <td>
            <input 
              type="text" 
              name="title" 
              class="box" 
              value="<?php echo $title; ?>" 
              required
            >
          </td>
        </tr>

        <tr>
          <td>Current Image:</td>
          <td>
            <?php if($current_image != ""){ ?>
              <img src="<?php echo HOMEURL; ?>img/category/<?php echo $current_image; ?>" width="35%">
            <?php }else{ echo "Image not added"; } ?>
          </td>
        </tr>

        <tr>
          <td>New Image:</td>
          <td>
            <input type="file" name="image" accept="image/png, image/jpg, image/jpeg">
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">

            <input type="submit" name="submit" value="UPDATE CATEGORY" class="add_button">
            <input type="reset" value="CANCEL" id="close-edit" class="cancel_button">
          </td>
        </tr>
      </table>
    </form>

    <?php
      // ================= UPDATE PROCESS =================
      if(isset($_POST['submit'])){
        $id = $_POST['id'];
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $current_image = $_POST['current_image'];

        // default image
        $image_name = $current_image;

        // CHECK IMAGE
        if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ""){
          $original_name = $_FILES['image']['name'];
          $ext = pathinfo($original_name, PATHINFO_EXTENSION);

          $image_name = "Food_Category_".rand(100,999).".".$ext;

          $source_path = $_FILES['image']['tmp_name'];
          $destination_path = "../img/category/".$image_name;

          // auto create folder
          if(!is_dir("../img/category")){
            mkdir("../img/category", 0777, true);
          }

          $upload = move_uploaded_file($source_path, $destination_path);

          if($upload == false){
            $_SESSION['upload'] = "Failed to upload image";
            header('location:'.HOMEURL.'admin/category.php');
            exit;
          }

          // remove old image
          if($current_image != ""){
            $remove_path = "../img/category/".$current_image;
            if(file_exists($remove_path)){
              unlink($remove_path);
            }
          }
        }

        // UPDATE QUERY
        $sql2 = "UPDATE category SET
                  title = '$title',
                  imgName = '$image_name'
                 WHERE id = $id";

        $res2 = mysqli_query($conn, $sql2);

        if($res2 == true){
          $_SESSION['update-category'] = "Category updated successfully";
        }else{
          $_SESSION['update-category'] = "Failed to update category";
        }

        header('location:'.HOMEURL.'admin/category.php');
        exit;
      }
    ?>

  </div>
</div>

<script>
document.querySelector('#close-edit').onclick = () =>{
  window.location.href = 'category.php';
};
</script>

</body>
</html>

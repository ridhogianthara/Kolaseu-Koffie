<?php
include('partial_backend/navbar.php');

if(isset($_POST['update_food']))
{
   $update_f_id = $_POST['update_f_id'];
   $update_f_name = $_POST['update_f_name'];
   $update_f_price = $_POST['update_f_price'];
   $update_f_desc = $_POST['update_f_desc']; // ✅ DESKRIPSI
   $update_f_category = $_POST['update_f_category'];

   // image handling
   if(isset($_FILES['update_f_image']['name']) && $_FILES['update_f_image']['name'] != "")
   {
      $update_f_image = $_FILES['update_f_image']['name'];
      $update_f_image_tmp_name = $_FILES['update_f_image']['tmp_name'];

      $ext = end(explode('.', $update_f_image));
      $update_f_image = "Food_".rand(100,999).".".$ext;
      $update_f_image_folder = 'upload_foodimg/'.$update_f_image;

      move_uploaded_file($update_f_image_tmp_name, $update_f_image_folder);

      $sql = "UPDATE food SET
               food_name = '$update_f_name',
               description = '$update_f_desc',
               price = '$update_f_price',
               img_name = '$update_f_image',
               category_id = '$update_f_category'
               WHERE id = '$update_f_id'";
   }
   else
   {
      // jika gambar tidak diganti
      $sql = "UPDATE food SET
               food_name = '$update_f_name',
               description = '$update_f_desc',
               price = '$update_f_price',
               category_id = '$update_f_category'
               WHERE id = '$update_f_id'";
   }

   $update_query = mysqli_query($conn, $sql);

   if($update_query == true)
   {
      $_SESSION['update'] = 'Product updated successfully';
      header('location:'.HOMEURL.'admin/food.php');
   }
   else
   {
      $_SESSION['update'] = 'Failed to update product';
      header('location:'.HOMEURL.'admin/food.php');
   }
}
?>

<!-- ================= UPDATE FORM ================= -->
<section class="edit-form-container">
<?php
if(isset($_GET['update']))
{
   $edit_id = $_GET['update'];
   $sql3 = "SELECT * FROM food WHERE id = $edit_id";
   $edit_query = mysqli_query($conn, $sql3);

   if(mysqli_num_rows($edit_query) > 0)
   {
      while($fetch_edit = mysqli_fetch_assoc($edit_query))
      {
?>
<form action="" method="post" class="add_food_form" enctype="multipart/form-data">

   <img src="upload_foodimg/<?php echo $fetch_edit['img_name']; ?>" height="200" alt="">

   <input type="hidden" name="update_f_id" value="<?php echo $fetch_edit['id']; ?>">

   <input type="text" class="box" required name="update_f_name"
          value="<?php echo $fetch_edit['food_name']; ?>">

   <!-- ✅ DESKRIPSI -->
   <textarea class="box" required name="update_f_desc"
             placeholder="Product description"><?php echo $fetch_edit['description']; ?></textarea>

   <input type="text" class="box" required name="update_f_price"
          value="<?php echo $fetch_edit['price']; ?>">

   <input type="file" class="box" name="update_f_image"
          accept="image/png, image/jpg, image/jpeg">

   <select name="update_f_category" class="box" required>
      <?php
      $sql2 = "SELECT * FROM category";
      $res2 = mysqli_query($conn,$sql2);

      while($row = mysqli_fetch_assoc($res2))
      {
         $selected = ($row['id'] == $fetch_edit['category_id']) ? "selected" : "";
         ?>
         <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
            <?php echo $row['title']; ?>
         </option>
         <?php
      }
      ?>
   </select>

   <input type="submit" value="UPDATE PRODUCT" name="update_food" class="add_button">
   <input type="reset" value="CANCEL" id="close-edit" class="cancel_button">
</form>

<?php
      }
   }
   echo "<script>
         document.querySelector('.edit-form-container').style.display = 'flex';
         </script>";
}
?>
</section>

<script>
document.querySelector('#close-edit').onclick = () =>{
   document.querySelector('.edit-form-container').style.display = 'none';
   window.location.href = 'food.php';
};
</script>

</body>
</html>

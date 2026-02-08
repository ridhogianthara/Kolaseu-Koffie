<?php 
include('partial_backend/navbar.php');
$conn = mysqli_connect('localhost','root','','food_order') or die('connection failed');

if(isset($_POST['add_food']))
{
   $f_name = $_POST['f_name'];
   $f_price = $_POST['f_price'];
   $f_category = $_POST['f_category'];
   $f_description = $_POST['f_description']; 

   if(isset($_FILES['f_image']['name']))
   {
      $image_name = $_FILES['f_image']['name'];

      if($image_name != "")
      {
         $extention = end(explode('.',$image_name));
         $image_name = "Food_".rand(100,999).'.'.$extention;

         $source_path = $_FILES['f_image']['tmp_name'];
         $destination_path = "upload_foodimg/".$image_name;

         $upload = move_uploaded_file($source_path,$destination_path);

         if($upload == false)
         {
            $_SESSION['upload'] = "Failed to upload image";
            header("location:".HOMEURL."admin/food.php");
            die();
         }
      }
   }
   else
   {
      $image_name = "";
   }

   $insert_query = mysqli_query($conn,
      "INSERT INTO food (food_name, description, price, img_name, category_id)
       VALUES ('$f_name', '$f_description', '$f_price', '$image_name', '$f_category')"
   ) or die('query failed');

   if($insert_query == true)
   {
      $_SESSION['add'] = "Product added successfully";
      header("location:".HOMEURL."admin/food.php");
   }
   else
   {
      $_SESSION['add'] = "Failed to add Product";
      header("location:".HOMEURL."admin/add_food.php");
   }
}
?>

<div class="container">
<section>
<form action="" method="post" class="add_food_form" enctype="multipart/form-data">
   <h3>ADD NEW PRODUCT</h3>

   <div class="form-grid">
      
      <!-- NAME -->
      <div class="form-group">
         <label>Product Name</label>
         <input type="text" name="f_name" placeholder="e.g: Nasi Lemak" class="box" required>
      </div>

      <!-- PRICE -->
      <div class="form-group">
         <label>Price</label>
         <input type="text" name="f_price" min="0" placeholder="e.g: 3.50" class="box" required>
      </div>

      <!-- CATEGORY -->
      <div class="form-group">
         <label>Category</label>
         <select name="f_category" required>
            <?php
            $sql2 = "SELECT * FROM category";
            $res2 = mysqli_query($conn,$sql2);
            $count = mysqli_num_rows($res2);

            if($count > 0)
            {
               while($row = mysqli_fetch_assoc($res2))
               {
                  $id = $row['id'];
                  $title = $row['title'];
                  ?>
                  <option value="<?php echo $id; ?>"><?php echo $title; ?></option>
                  <?php
               }
            }
            else
            {
               ?>
               <option value="0">No Category Found</option>
               <?php
            }
            ?>
         </select>
      </div>

       <!-- DESCRIPTION -->
       <div class="form-group full-width">
         <label>Description</label>
         <textarea name="f_description" class="box" placeholder="Product description..." required></textarea>
      </div>

      <!-- IMAGE -->
      <div class="form-group">
         <label>Product Image</label>
         <input type="file" name="f_image" accept="image/png, image/jpg, image/jpeg" class="box" required>
      </div>

   </div>

   <div class="form-actions">
      <input type="reset" value="CANCEL" id="close-edit" class="cancel_button">
      <input type="submit" value="ADD PRODUCT" name="add_food" class="add_button">
   </div>

</form>
</section>
</div>

<script>
document.querySelector('#close-edit').onclick = () =>{
   window.location.href = 'food.php';
};
</script>

</body>
</html>

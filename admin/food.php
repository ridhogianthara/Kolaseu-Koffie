<?php include('partial_backend/navbar.php'); ?>
<br /><br /><br /><br />

<div class="main-content" style="background-color:white">
	<div class="container">
		<h1>Product Page</h1>
	</div>

	<div class="container">
		<a href="add_food.php" class="btn-addAdmin" style="width:18%">
			<i class="fa fa-plus"></i> Add Product
		</a>
	</div>

	<div class="container">
		<?php
			echo "<div class='popup' style='padding-right:13%'>";

			if(isset($_SESSION['add'])){
				echo "<i class='fa fa-check fa-2x' style='color: green'></i> ";
				echo $_SESSION['add'];
				unset($_SESSION['add']);
			}

			if(isset($_SESSION['delete_food'])){
				echo "<i class='fa fa-trash fa-2x' style='color: red'></i> ";
				echo $_SESSION['delete_food'];
				unset($_SESSION['delete_food']);
			}

			if(isset($_SESSION['update'])){
				echo "<i class='fa fa-check fa-2x' style='color: green'></i> ";
				echo $_SESSION['update'];
				unset($_SESSION['update']);
			}

			if(isset($_SESSION['upload'])){
				echo "<i class='fa fa-exclamation-triangle fa-2x' style='color: red'></i> ";
				echo $_SESSION['upload'];
				unset($_SESSION['upload']);
			}

			echo "</div>";
		?>
	</div>

	<div class="display_food">
		<table>
			<thead>
				<tr>
					<th>PRODUCT NAME</th>
					<th>DESCRIPTION</th>
					<th>PRODUCT PRICE</th>
					<th>PRODUCT IMAGE</th>
					<th>ACTIONS</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$sql2 = "SELECT * FROM food";
					$select_products = mysqli_query($conn, $sql2);

					if(mysqli_num_rows($select_products) > 0){
						while($row = mysqli_fetch_assoc($select_products)){
				?>
				<tr>
					<td><?php echo $row['food_name']; ?></td>

					<!-- ✅ DESKRIPSI PRODUK -->
					<td style="max-width:250px">
						<?php echo nl2br($row['description']); ?>
					</td>

					<td>Rp <?php echo $row['price']; ?></td>

					<td>
						<img src="upload_foodimg/<?php echo $row['img_name']; ?>" height="100" alt="">
					</td>

					<td style="width:25%">
						<a href="updateFood.php?update=<?php echo $row['id']; ?>" class="update_button">
							<i class="fas fa-edit"></i> Update
						</a>

						<a href="delete_food.php?delete=<?php echo $row['id']; ?>" class="del_button">
							<i class="fas fa-trash"></i> Delete
						</a>
					</td>
				</tr>
				<?php
						}
					}
					else{
				?>
				<tr>
					<td colspan="5">No product added</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<script>
document.querySelector('#close-edit')?.addEventListener('click', () => {
	window.location.href = 'food.php';
});
</script>

</body>
</html>

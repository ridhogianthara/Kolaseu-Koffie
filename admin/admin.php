<?php include('partial_backend/navbar.php'); ?>

<div class="main-content">
  <div class="container">

    <h1>Welcome to Admin Page</h1>

    <!-- ADD ADMIN BUTTON -->
    <div class="action-bar">
      <a href="addAdmin.php" class="btn-addAdmin">
        <i class="fa fa-plus"></i> Add Admin
      </a>
    </div>

    <!-- POPUP MESSAGE -->
    <?php if(
      isset($_SESSION['add']) ||
      isset($_SESSION['delete']) ||
      isset($_SESSION['update']) ||
      isset($_SESSION['Admin_not_found']) ||
      isset($_SESSION['Password_not_match']) ||
      isset($_SESSION['update_pwd']) ||
      isset($_SESSION['update_pwd_failed'])
    ): ?>
      <div class="popup">
        <?php
          function showMsg($icon, $color, $msg){
            echo "<span class='msg $color'><i class='fa $icon'></i> $msg</span>";
          }

          if(isset($_SESSION['add'])){
            showMsg('fa-check-circle','success',$_SESSION['add']);
            unset($_SESSION['add']);
          }
          if(isset($_SESSION['delete'])){
            showMsg('fa-trash','danger',$_SESSION['delete']);
            unset($_SESSION['delete']);
          }
          if(isset($_SESSION['update'])){
            showMsg('fa-check-circle','success',$_SESSION['update']);
            unset($_SESSION['update']);
          }
          if(isset($_SESSION['Admin_not_found'])){
            showMsg('fa-exclamation-triangle','danger',$_SESSION['Admin_not_found']);
            unset($_SESSION['Admin_not_found']);
          }
          if(isset($_SESSION['Password_not_match'])){
            showMsg('fa-exclamation-triangle','danger',$_SESSION['Password_not_match']);
            unset($_SESSION['Password_not_match']);
          }
          if(isset($_SESSION['update_pwd'])){
            showMsg('fa-check-circle','success',$_SESSION['update_pwd']);
            unset($_SESSION['update_pwd']);
          }
          if(isset($_SESSION['update_pwd_failed'])){
            showMsg('fa-exclamation-triangle','danger',$_SESSION['update_pwd_failed']);
            unset($_SESSION['update_pwd_failed']);
          }
        ?>
      </div>
    <?php endif; ?>

    <!-- ADMIN TABLE -->
    <div class="display_food">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Name</th>
            <th>Username</th>
            <th>Phone No</th>
            <th>IC No</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>

        <?php
          $sql = "SELECT * FROM admin";
          $res = mysqli_query($conn,$sql);
          $sn = 1;

          if($res == TRUE){
            while($row = mysqli_fetch_assoc($res)){
        ?>
          <tr>
            <td><?php echo $sn++; ?></td>
            <td><?php echo $row['admin_name']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['phoneNumber']; ?></td>
            <td><?php echo $row['IcNumber']; ?></td>
            <td class="action-btn">
              <a href="<?php echo HOMEURL;?>admin/updatePwd.php?id=<?php echo $row['id']?>" class="change_button">
                <i class="fas fa-key"></i> Password
              </a>
              <a href="<?php echo HOMEURL;?>admin/updateAdmin.php?id=<?php echo $row['id']?>" class="update_button">
                <i class="fas fa-edit"></i> Edit
              </a>
              <a href="<?php echo HOMEURL;?>admin/deleteAdmin.php?id=<?php echo $row['id']?>" class="del_button">
                <i class="fas fa-trash"></i> Delete
              </a>
            </td>
          </tr>
        <?php }} ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<?php
include('connectDB/connect.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="../css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="admin-login">

  <!-- wrapper khusus login -->
  <div class="login-wrapper">

    <div class="login">

      <form action="" method="POST">

        <h3>Admin Login</h3>

        <p>
          Login ini khusus untuk <b>Admin</b>.<br>
          Pelanggan tidak perlu login 😄
        </p>

        <!-- NOTIFIKASI -->
        <?php
          if(isset($_SESSION['login'])){
            echo $_SESSION['login'];
            unset($_SESSION['login']);
          }

          if(isset($_SESSION['no-login-message'])){
            echo "<div class='message'>".$_SESSION['no-login-message']."</div>";
            unset($_SESSION['no-login-message']);
          }
        ?>

        <input type="text" name="username" class="box" placeholder="Username" required>

        <input type="password" name="password" class="box" placeholder="Password" required>

        <input type="submit" name="submit" value="Login" class="login_button">

        <a href="../index.php" class="login_button back-btn">
          ← Back to Home
        </a>

      </form>

    </div>

  </div>

</body>
</html>

<?php
if(isset($_POST['submit'])){
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $pwd = md5($_POST['password']);

  $sql = "SELECT * FROM admin WHERE username='$username' AND pwd='$pwd'";
  $res = mysqli_query($conn, $sql);

  if(mysqli_num_rows($res) == 1){

    $_SESSION['login'] = "
      <div class='message success'>
        <i class='fa-solid fa-circle-check'></i> Welcome, <b>$username</b>
      </div>
    ";

    $_SESSION['user'] = $username;
    header('location:'.HOMEURL.'admin/');

  }else{

    $_SESSION['login'] = "
      <div class='message error'>
        <i class='fa-solid fa-circle-xmark'></i> Username atau password salah
      </div>
    ";

    header('location:'.HOMEURL.'admin/login.php');
  }
}
?>

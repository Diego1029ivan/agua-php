<?php

include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {

  $name = $_POST['name'];
  $name
    = strip_tags($name);
  $pass = sha1($_POST['password']);
  $pass =
    strip_tags($pass);

  $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
  $select_admin->execute([$name, $pass]);

  if ($select_admin->rowCount() > 0) {
    $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
    $_SESSION['admin_id'] = $fetch_admin_id['id'];
    header('location:dashboard.php');
  } else {
    $message[] = 'Contraseña o password incorrectos!';
  }
}

?>

<!DOCTYPE html>
<html lang="zxx">

<head>
  <title>Login Form - Brave Coder</title>
  <!-- Meta tag Keywords -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="UTF-8" />
  <meta name="keywords" content="Login Form" />
  <!-- //Meta tag Keywords -->
  <!--icono de la pestaña-->
  <link rel="shortcut icon" href="../uploaded_img/logo.png" type="image/x-icon">
  <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!--/Style-CSS -->
  <link rel="stylesheet" href="../css/login.css" type="text/css" media="all" />
  <!--//Style-CSS -->

  <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>

</head>

<body>
  <?php
  if (isset($message)) {
    foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
    }
  }
  ?>


  <!-- form section start -->
  <section class="w3l-mockup-form">
    <div class="container">
      <!-- /form -->
      <div class="workinghny-form-grid">
        <div class="main-mockup">
          <div class="alert-close">
            <span class="fa fa-close"></span>
          </div>
          <div class="w3l_form align-self">
            <div class="left_grid_info">
              <img src="../images/loginadmin.svg" alt="logo">
            </div>
          </div>
          <div class="content-wthree">
            <h2>Login Administrativo</h2>
            <p>Podra acceder a realizar el control administrativo de la tienda. </p>
            <form action="" method="post">
              <input type="text" class="name" name="name" placeholder="ingrese su usuario" required oninput="this.value = this.value.replace(/\s/g, '')">
              <input type="password" class="password" name="password" placeholder="Ingrese su usuario" style="margin-bottom: 2px;" oninput="this.value = this.value.replace(/\s/g, '')" required>

              <button name="submit" name="submit" class="btn" type="submit">Login</button>
            </form>

          </div>
        </div>
      </div>
      <!-- //form -->
    </div>
  </section>
  <!-- //form section start -->



</body>

</html>
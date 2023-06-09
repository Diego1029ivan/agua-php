<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = '';
};

if (isset($_POST['submit'])) {

  $name = $_POST['name'];
  $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $email = $_POST['email'];
  $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $number = $_POST['number'];
  $number = filter_var($number, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $address = $_POST['address'];
  $address = filter_var($address, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $pass = sha1($_POST['pass']);
  $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $cpass = sha1($_POST['cpass']);
  $cpass = filter_var($cpass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
  $select_user->execute([$email, $number]);
  $row = $select_user->fetch(PDO::FETCH_ASSOC);

  if ($select_user->rowCount() > 0) {
    $message[] = "<script>
    Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: '¡email ya existe coninciden!',
        showConfirmButton: false,
        timer: 3500
    });
</script>";
  } else {
    if ($pass != $cpass) {
      $message[] = '<script>
      Swal.fire("¡No se puede!", "confirmar conicidencia de contraseñas", "warning");
    </script>';
    } else {
      $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number,address, password) VALUES(?,?,?,?,?)");
      $insert_user->execute([$name, $email, $number, $address, $cpass]);
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
      $select_user->execute([$email, $pass]);
      $row = $select_user->fetch(PDO::FETCH_ASSOC);
      if ($select_user->rowCount() > 0) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['message'] = "<script>
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: '¡La cuenta fue creada existosamente!',
          showConfirmButton: false,
          timer: 3500
          });
        </script>";
        header('location:home.php');
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>La colpa</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css">
  <!--icono de la pestaña-->
  <link rel="shortcut icon" href="uploaded_img/logo.png" type="image/x-icon">

</head>

<body>

  <!-- header section starts  -->
  <?php include 'components/user_header.php'; ?>
  <!-- header section ends -->

  <section class="form-container">

    <form action="" method="post">
      <h3>Registrarse ahora</h3>
      <input type="text" name="name" required placeholder="ingrese su nombre" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="ingrese su email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="ingrese su  number" class="box" min="0" max="9999999999" maxlength="10">
      <input type="text" name="address" required placeholder="ingrese su dirección" class="box" maxlength="50">
      <input type="password" name="pass" required placeholder="enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="confirm your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Registrarme" name="submit" class="btn">
      <p>tiene una cuenta? <a href="login.php">inrgesar ahora</a></p>
    </form>

  </section>











  <?php include 'components/footer.php'; ?>







  <!-- custom js file link  -->
  <script src="js/script.js"></script>

</body>

</html>
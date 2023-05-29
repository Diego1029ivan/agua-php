<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   }else{
      $message[] = "<script>
      Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: '¡correo o contraseña incorrecta!',
          showConfirmButton: false,
          timer: 3500
            });
      </script>";
   }

}

?>

<?php
// Configura el ID de la aplicación y la URL de redirección
// $fbAppId = '973563016980691';
// $fbRedirectUri = 'http://localhost:8012/agua%20website%20backend/login.php';

// // Genera la URL de inicio de sesión de Facebook
// $fbLoginUrl = 'https://www.facebook.com/dialog/oauth?client_id=' . $fbAppId . '&redirect_uri=' . urlencode($fbRedirectUri) . '&scope=email';

// // Crea el botón de inicio de sesión de Facebook
// echo '<a href="' . $fbLoginUrl . '"><img src="boton_facebook.png" alt="Acceder con Facebook"></a>';
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

     <!-- sweet alert-->
  <script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js
"></script>
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css
" rel="stylesheet">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>Login</h3>
      <input type="email" name="email" required placeholder="ingrese su email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" autocomplete="off">
      <input type="password" name="pass" required placeholder="ingrese su password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" autocomplete="off">
      <input type="submit" value="Ingresar" name="submit" class="btn">
      <p>no tiene cuenta? <a href="register.php">registrarse</a></p>
      <p>se olvidó su contraseña? <a href="recuperar.php">recuperar contraseña</a></p>
   </form>
   <!-- <input value="Facebook" name="submit" class="btn"> -->

</section>











<?php include 'components/footer.php'; ?>






<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
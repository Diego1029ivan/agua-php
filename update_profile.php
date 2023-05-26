<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   // $foto = $_POST['imagen'];
   // $foto = filter_var($foto, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   
   if(isset($_FILES['imagen'])){
      $nombre_foto = $_FILES['imagen']['name'];
      echo $nombre_foto;   
      $directorio = 'images/perfiles/';
      $rutaArchivo = $directorio . $nombre_foto;
      if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
         if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaArchivo)) {
            echo 'La imagen se ha subido correctamente.';
         } else {
            echo 'Error al subir la imagen.';
         }
      }else{
         echo "error al cargar la imagen";
      }
      $update_foto = $conn->prepare("UPDATE `users` SET foto_perfil = ? WHERE id = ?");
      $update_foto->execute([$nombre_foto, $user_id]);
   }
   // if (isset($_FILES['imagen'])) {
   //    $directorio = 'images/perfiles'; // Ruta de la carpeta donde se guardarán las imágenes
    
   //    // Obtener la información del archivo
   //    $nombreArchivo = $_FILES['imagen']['name'];
   //    $rutaArchivo = $directorio . $nombreArchivo;
    
   //    // Mover el archivo temporal a la ubicación deseada
   //    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaArchivo)) {
   //      echo 'La imagen se ha subido correctamente.';
   //    } else {
   //      echo 'Error al subir la imagen.';
   //    }
   //  }

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $user_id]);
   }

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_email->execute([$email]);
      if($select_email->rowCount() > 0){
         $message[] = 'este email está en uso!';
      }else{
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
      }
   }

   if(!empty($number)){
      $select_number = $conn->prepare("SELECT * FROM `users` WHERE number = ?");
      $select_number->execute([$number]);
      if($select_number->rowCount() > 0){
         $message[] = 'este número está en uso!';
      }else{
         $update_number = $conn->prepare("UPDATE `users` SET number = ? WHERE id = ?");
         $update_number->execute([$number, $user_id]);
      }
   }
   
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_prev_pass->execute([$user_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'old password not matched!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'confirm password not matched!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $user_id]);
            $message[] = 'password updated successfully!';
         }else{
            $message[] = 'please enter a new password!';
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
   <title>Actualizar perfil</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container update-form">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Actualizar perfil</h3>
      <input type="file" class="box" name="imagen"  id="input-imagen" onchange="mostrarImagen()">
      <div id="vista-previa"></div>
      <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" class="box" maxlength="50" autocomplete="off">
      <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" autocomplete="off" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" >
      <input type="number" name="number" placeholder="<?= $fetch_profile['number']; ?>" class="box" min="0" max="9999999999" maxlength="10" autocomplete="off">
      <input type="password" name="old_pass" placeholder="enter your old password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" autocomplete="off">
      <input type="password" name="new_pass" placeholder="enter your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" autocomplete="off">
      <input type="password" name="confirm_pass" placeholder="confirm your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" autocomplete="off">
      <input type="submit" value="Actualizar perfil" name="submit" class="btn">
   </form>

</section>
<script>
function mostrarImagen() {
  var archivo = document.getElementById('input-imagen').files[0];
  var vistaPrevia = document.getElementById('vista-previa');
  
  var lector = new FileReader();
  lector.onload = function(evento) {
    var imagen = document.createElement('img');
    imagen.style.borderRadius = '50%';
    imagen.style.width = '60px';
    imagen.style.height = '60px';
    imagen.src = evento.target.result;
    vistaPrevia.innerHTML = '';
    vistaPrevia.appendChild(imagen);
  }
  
  lector.readAsDataURL(archivo);
}
</script>









<?php include 'components/footer.php'; ?>






<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
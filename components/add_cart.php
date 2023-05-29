<?php

if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_cart_numbers->rowCount() > 0){
         $message[] = '<script>
         Swal.fire("¡No se puede!", "El producto ya existe", "warning");
       </script>';
      }else{
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         $message[]= "<script>
         Swal.fire({
             position: 'top-end',
             icon: 'success',
             title: '¡El producto fue registrado en el carrito de compras!',
             showConfirmButton: false,
             timer: 3500
         });
     </script>";
         
      }

   }

}

?>
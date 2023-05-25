<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:admin_login.php');
};

if (isset($_POST['add_product'])) {

  $name = $_POST['name'];
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $price = $_POST['price'];
  $price = filter_var($price, FILTER_SANITIZE_STRING);
  $category = $_POST['category'];
  $category = filter_var($category, FILTER_SANITIZE_STRING);

  $image = $_FILES['image']['name'];
  $image = filter_var($image, FILTER_SANITIZE_STRING);
  $image_size = $_FILES['image']['size'];
  $image_tmp_name = $_FILES['image']['tmp_name'];
  $image_folder = '../uploaded_img/' . $image;

  $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
  $select_products->execute([$name]);

  if ($select_products->rowCount() > 0) {
    $message[] = 'product name already exists!';
  } else {
    if ($image_size > 2000000) {
      $message[] = 'image size is too large';
    } else {
      move_uploaded_file($image_tmp_name, $image_folder);

      $insert_product = $conn->prepare("INSERT INTO `products`(name, categoria_id, price, image) VALUES(?,?,?,?)");
      $insert_product->execute([$name, $category, $price, $image]);

      $message[] = 'new product added!';
    }
  }
}

if (isset($_GET['delete'])) {

  $delete_id = $_GET['delete'];
  $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
  $delete_product_image->execute([$delete_id]);
  $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
  unlink('../uploaded_img/' . $fetch_delete_image['image']);
  $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
  $delete_product->execute([$delete_id]);
  $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
  $delete_cart->execute([$delete_id]);
  header('location:products.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>products</title>
  <!-- DataTable -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.3/css/buttons.bootstrap5.min.css" />
  <!-- Bootstrap-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <!--icono de la pestaña-->
  <link rel="shortcut icon" href="../uploaded_img/logo.png" type="image/x-icon">
  <!-- custom css file link  -->
  <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

  <?php include '../components/admin_header.php' ?>

  <!-- add products section starts  -->

  <section class="add-products">

    <form action="" method="POST" enctype="multipart/form-data">
      <h3>add product</h3>
      <input type="text" required placeholder="enter product name" name="name" maxlength="100" class="box">
      <input type="number" min="0" max="9999999999" required placeholder="enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="category" class="box" required>
        <option value="" disabled selected>select category --</option>

        <?php
        $select_categoria = $conn->prepare("SELECT * FROM `categoria`");
        $select_categoria->execute();

        while ($fetch_catehoria = $select_categoria->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <option value="<?= $fetch_catehoria['idcategoria']; ?>"><?= $fetch_catehoria['descripcion']; ?></option>
        <?php
        }
        ?>

      </select>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <input type="submit" value="add product" name="add_product" class="btn">
    </form>

  </section>

  <!-- add products section ends -->

  <!-- show products section starts  -->

  <section class="show-products" style="padding-top: 0;">

    <div class="box-container">

      <?php
      // $show_products = $conn->prepare("SELECT * FROM `products`");
      //INNER JOIN categoria ON products.categoria_id = categoria.idcategoria
      $show_products = $conn->prepare("SELECT products.*, categoria.descripcion FROM products INNER JOIN categoria ON products.categoria_id =categoria.idcategoria");
      $show_products->execute();
      if ($show_products->rowCount() > 0) {
      ?>
        <div class="row">
          <table id="example" class="table table-striped" style="width: 100%">
            <caption>
              Ejemplo de DataTable
            </caption>
            <thead>
              <tr>
                <th>#</th>
                <th>name</th>
                <th>Categoria</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody id="table_users"></tbody>
          </table>
        </div>
    </div>
  <?php
      } else {
        echo '<p class="empty">no products added yet!</p>';
      }
  ?>

  </div>

  </section>

  <!-- show products section ends -->










  <!-- custom js file link  -->
  <script src="../js/admin_script.js"></script>

  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- DataTable -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.3/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.3/js/buttons.bootstrap5.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.3/js/buttons.html5.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.3/js/buttons.print.min.js"></script>
  <!-- Bootstrap-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script src="../js/productos.js"> </script>

</body>

</html>
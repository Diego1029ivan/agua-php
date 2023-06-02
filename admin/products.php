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
  <!-- <link rel="stylesheet" href="../css/admin_style.css"> -->
  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  <!-- custom css file link  -->

  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />
  <!-- My CSS -->
  <link rel="stylesheet" href="../css/admin.css" />
  <link rel="stylesheet" href="../css/nav.css" />

</head>

<body>

  <section id="sidebar">
    <a href="dashboard.php" class="brand">
      <i class="bx bxs-smile"></i>
      <span class="text">La Colpa</span>
    </a>
    <ul class="side-menu top">
      <li>
        <a href="dashboard.php">
          <i class="bx bxs-dashboard"></i>
          <span class="text">Principal</span>
        </a>
      </li>
      <li class="active">
        <a href="products.php">
          <i class="bx bxs-shopping-bag-alt"></i>
          <span class="text">Productos</span>
        </a>
      </li>
      <li>
        <a href="placed_orders.php?pid=Pendiente&pid2=Completado">
          <i class="bx bxs-book-reader"></i>
          <span class="text">Ordenes</span>
        </a>
      </li>
      <li>
        <a href="admin_accounts.php">
          <i class="bx bxs-user-account"></i>
          <span class="text">Administradores</span>
        </a>
      </li>
      <li>
        <a href="users_accounts.php">
          <i class="bx bxs-group"></i>
          <span class="text">Usuarios</span>
        </a>
      </li>
      <li>
        <a href="messages.php">
          <i class="bx bxs-message-dots"></i>
          <span class="text">Mensajes</span>
        </a>
      </li>
    </ul>
    <ul class="side-menu">

      <li>
        <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="logout">
          <i class="bx bxs-log-out-circle"></i>
          <span class="text">Salir</span>
        </a>
      </li>
    </ul>
  </section>

  <!-- add products section starts  -->
  <?php
  $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
  $select_profile->execute([$admin_id]);
  $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
  ?>
  <!-- SIDEBAR -->
  <!-- CONTENT -->
  <section id="content">
    <!-- NAVBAR -->
    <nav>
      <i class="bx bx-menu"></i>

      <form action="#">
        <div class="form-input">
          <input type="search" placeholder="Search..." />
          <button type="submit" class="search-btn">
            <i class="bx bx-search"></i>
          </button>
        </div>
      </form>
      <input type="checkbox" id="switch-mode" hidden />
      <label for="switch-mode" class="switch-mode"></label>

      <ul class="main-links">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
        $select_profile->execute([$admin_id]);
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <li class="dropdown-li">
          <a href="#" class="profile">
            <img src="../images/people.png" />
          </a>
          <ul class="dropdown">
            <a href="update_profile.php">
              <li> <?= $fetch_profile['name']; ?></li>
            </a>
            <a href="admin_login.php" class="option-btn">
              <li>Login
              </li>
            </a>
            <a href="register_admin.php" class="option-btn">
              <li>Registrar</li>
            </a>
            <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">
              <li>Cerrar sesion</li>
            </a>
          </ul>
        </li>

      </ul>
      </li>
      </ul>


    </nav>
    <main>
      <!-- SIDEBAR -->
      <?php
      if (isset($message)) {
        foreach ($message as $message) {
          echo '
      <div style= "background-color: #3c91e6;  padding: 15px 32px;" class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
        }
      }
      ?>
      <section class="add-products">

        <form action="" method="POST" enctype="multipart/form-data">
          <h3 class="text-center">Agregar Productos</h3>
          <div class="mb-3">
            <label for="name" class="form-label">Nombre producto</label>
            <input type="text" class="form-control" id="name" aria-describedby="emailHelp" required placeholder="Nombre producto" name="name" maxlength="100">
          </div>
          <div class="mb-3">
            <label for="price" class="form-label">Precio producto</label>
            <input type="number" class="form-control" id="price" aria-describedby="emailHelp" required placeholder="Precio producto" name="price" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;">
          </div>
          <div class="mb-3">
            <select name="category" class="form-select mb-2" aria-label="Default select example">
              <option value="" disabled selected>--seleccione Categoria --</option>

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
          </div>
          <div class="mb-3">
            <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
          </div>
          <button type="submit" name="add_product" class="btn btn-primary">Agregar Producto</button>

        </form>
      </section>

      <!-- add products section ends -->

      <!-- show products section starts  -->

      <section class="show-products table-data1" style="padding-top: 0;">
        <div class="order">
          <div class="box-container ">

            <?php
            // $show_products = $conn->prepare("SELECT * FROM `products`");
            //INNER JOIN categoria ON products.categoria_id = categoria.idcategoria
            $show_products = $conn->prepare("SELECT products.*, categoria.descripcion FROM products INNER JOIN categoria ON products.categoria_id =categoria.idcategoria");
            $show_products->execute();
            if ($show_products->rowCount() > 0) {
            ?>
              <div class="row overflow">
                <table id="example" class="table table-striped" style="width: 100%">
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
              echo '<p style= "background-color: #3c91e6;  padding: 15px 32px;" class="empty">No hay Productos</p>';
            }
        ?>
        </div>
        </div>


    </main>
  </section>
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
  <script src="../js/admin.js"></script>

</body>

</html>
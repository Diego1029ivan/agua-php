<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:admin_login.php');
};

if (isset($_POST['update'])) {

  $pid = $_POST['pid'];
  $pid = filter_var($pid, FILTER_SANITIZE_STRING);
  $name = $_POST['name'];
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $price = $_POST['price'];
  $price = filter_var($price, FILTER_SANITIZE_STRING);
  $category = $_POST['category'];
  $category = filter_var($category, FILTER_SANITIZE_STRING);

  $update_product = $conn->prepare("UPDATE `products` SET name = ?, categoria_id= ?, price = ? WHERE id = ?");
  $update_product->execute([$name, $category, $price, $pid]);

  $message[] = 'product updated!';

  $old_image = $_POST['old_image'];
  $image = $_FILES['image']['name'];
  $image = filter_var($image, FILTER_SANITIZE_STRING);
  $image_size = $_FILES['image']['size'];
  $image_tmp_name = $_FILES['image']['tmp_name'];
  $image_folder = '../uploaded_img/' . $image;

  if (!empty($image)) {
    if ($image_size > 2000000) {
      $message[] = 'images size is too large!';
    } else {
      $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
      $update_image->execute([$image, $pid]);
      move_uploaded_file($image_tmp_name, $image_folder);
      unlink('../uploaded_img/' . $old_image);
      $message[] = 'image updated!';
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
  <title>update product</title>
  <!--icono de la pestaña-->
  <link rel="shortcut icon" href="../uploaded_img/logo.png" type="image/x-icon">
  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
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

  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />
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
          <span class="text">Home</span>
        </a>
      </li>
      <li class="active">
        <a href="products.php">
          <i class="bx bxs-shopping-bag-alt"></i>
          <span class="text">Productos</span>
        </a>
      </li>
      <li>
        <a href="placed_orders.php?pid=pending&completed">
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
          <span class="text">Mensages</span>
        </a>
      </li>
    </ul>
    <ul class="side-menu">

      <li>
        <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="logout">
          <i class="bx bxs-log-out-circle"></i>
          <span class="text">Logout</span>
        </a>
      </li>
    </ul>
  </section>

  <!-- update product section starts  -->

  <section class="update-product" id="content">
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
              <li>login
              </li>
            </a>
            <a href="register_admin.php" class="option-btn">
              <li>register</li>
            </a>
            <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">
              <li>logout</li>
            </a>
          </ul>
        </li>

      </ul>
      </li>
      </ul>


    </nav>
    <main>

      <section class="add-products">
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
        <h1 class="heading text-center">update product</h1>
        <?php
        $update_id = $_GET['update'];
        $show_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $show_products->execute([$update_id]);
        if ($show_products->rowCount() > 0) {
          while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
            <form action="" method="POST" enctype="multipart/form-data">

              <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">

              <div class="mb-3">
                <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
                <div class="img-updatepro">
                  <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                </div>
              </div>
              <div class="mb-3">
                <label for="name" class="form-label">update name</label>
                <input class="form-control" type="text" required placeholder="enter product name" aria-describedby="emailHelp" name="name" maxlength="100" class="box" value="<?= $fetch_products['name']; ?>">
              </div>
              <div class="mb-3">
                <label for="price" class="form-label">Precio producto</label>

                <input class="form-control" type="number" min="0" max="9999999999" required placeholder="enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box" value="<?= $fetch_products['price']; ?>">

              </div>
              <div class="mb-3">
                <label for="category" class="form-label">update category</label>
                <select name="category" class="form-select mb-2" required>
                  <option selected value="<?= $fetch_products['category']; ?>"><?= $fetch_products['category']; ?></option>
                  <?php
                  $select_categoria = $conn->prepare("SELECT * FROM `categoria`");
                  $select_categoria->execute();

                  while ($fetch_catehoria = $select_categoria->fetch(PDO::FETCH_ASSOC)) {
                    if ($fetch_catehoria['idcategoria'] == $fetch_products['categoria_id']) {


                  ?>
                      <option selected value="<?= $fetch_catehoria['idcategoria']; ?>"><?= $fetch_catehoria['descripcion']; ?></option>


                    <?php
                    } else {

                    ?>
                      <option value="<?= $fetch_catehoria['idcategoria']; ?>"><?= $fetch_catehoria['descripcion']; ?></option>
                  <?php
                    }
                  }
                  ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="file" class="form-label">update image</label>
                <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
              </div>
              <div class="flex-btn">
                <input type="submit" value="update" class="btn btn-primary" name="update">
                <a href="products.php" class="btn btn-light">Regresar</a>
              </div>
            </form>
        <?php
          }
        } else {
          echo '<p class="empty">no products added yet!</p>';
        }
        ?>
      </section>
    </main>
  </section>

  <!-- update product section ends -->










  <!-- custom js file link  -->
  <script src="../js/admin_script.js"></script>
  <script src="../js/admin.js"></script>
</body>

</html>
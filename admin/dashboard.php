<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />
  <!-- My CSS -->
  <link rel="stylesheet" href="../css/admin.css" />
  <link rel="stylesheet" href="../css/nav.css" />
  <title>La Colpa</title>
  <!--icono de la pestaña-->
  <link rel="shortcut icon" href="../uploaded_img/logo.png" type="image/x-icon">
</head>

<body>
  <!-- SIDEBAR -->
  <?php include '../components/admin_headerp.php' ?>
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
    <!-- NAVBAR -->

    <!-- MAIN -->
    <main>
      <div class="head-title">
        <div class="left">
          <h1>Dashboard</h1>
          <ul class="breadcrumb">
            <li>
              <a href="#">Dashboard</a>
            </li>
            <li><i class="bx bx-chevron-right"></i></li>
            <li>
              <a class="active" href="#">Home</a>
            </li>
          </ul>
        </div>
      </div>

      <ul class="box-info">
        <li>
          <a href="update_profile.php"><i class="bx bxs-pyramid"></i></a>
          <span class="text">
            <h3><?= $fetch_profile['name'];  ?></h3>
            <p>Actualizar Perfil</p>
          </span>
        </li>
        <li>
          <?php
          $total_pendings = 0;
          $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
          $select_pendings->execute(['pending']);
          while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
            $total_pendings += $fetch_pendings['total_price'];
          }
          ?>
          <a href="placed_orders.php"> <i class="bx bxs-checkbox-checked"></i></a>
          <span class="text">
            <h3><?= $total_pendings; ?></h3>
            <p>Total Plata en pendientes</p>
          </span>
        </li>
        <li>
          <?php
          $total_completes = 0;
          $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
          $select_completes->execute(['completed']);
          while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
            $total_completes += $fetch_completes['total_price'];
          }
          ?>
          <a href="placed_orders.php?pid=completed"><i class="bx bxs-component"></i></a>
          <span class="text">
            <h3><?= $total_completes; ?></h3>
            <p>Total Plata en completado</p>
          </span>
        </li>
        <li>
          <?php
          $select_orders = $conn->prepare("SELECT * FROM `orders`");
          $select_orders->execute();
          $numbers_of_orders = $select_orders->rowCount();
          ?>
          <a href="placed_orders.php?pid=pending&completed"> <i class="bx bxs-book-reader"></i></a>
          <span class="text">
            <h3><?= $numbers_of_orders; ?></h3>
            <p>Total Ordenes</p>
          </span>
        </li>
        <li>
          <?php
          $select_products = $conn->prepare("SELECT * FROM `products`");
          $select_products->execute();
          $numbers_of_products = $select_products->rowCount();
          ?>
          <a href="products.php"><i class="bx bxs-shopping-bag-alt"></i></a>
          <span class="text">
            <h3><?= $numbers_of_products; ?></h3>
            <p>Total Productos</p>
          </span>
        </li>
        <li>
          <?php
          $select_users = $conn->prepare("SELECT * FROM `users`");
          $select_users->execute();
          $numbers_of_users = $select_users->rowCount();
          ?>
          <a href="users_accounts.php"> <i class="bx bxs-user"></i></a>
          <span class="text">
            <h3><?= $numbers_of_users; ?></h3>
            <p>N° Usuarios</p>
          </span>
        </li>
        <li>
          <?php
          $select_admins = $conn->prepare("SELECT * FROM `admin`");
          $select_admins->execute();
          $numbers_of_admins = $select_admins->rowCount();
          ?>
          <a href="admin_accounts.php"> <i class="bx bxs-user-account"></i></a>
          <span class="text">
            <h3><?= $numbers_of_admins; ?></h3>
            <p>N° Admin</p>
          </span>
        </li>

        <li>
          <?php
          $select_messages = $conn->prepare("SELECT * FROM `messages`");
          $select_messages->execute();
          $numbers_of_messages = $select_messages->rowCount();
          ?>
          <a href="messages.php"> <i class="bx bxs-message-dots"></i></a>
          <span class="text">
            <h3><?= $numbers_of_messages; ?></h3>
            <p>N° Mensages</p>
          </span>
        </li>
      </ul>
    </main>
    <!-- MAIN -->
  </section>
  <!-- CONTENT -->

  <script src="../js/admin.js"></script>

</body>

</html>
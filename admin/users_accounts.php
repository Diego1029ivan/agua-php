<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
  $delete_users->execute([$delete_id]);
  $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
  $delete_order->execute([$delete_id]);
  $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
  $delete_cart->execute([$delete_id]);
  header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>La Colpa</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  <!-- custom css file link  -->

  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />
  <!-- My CSS -->
  <link rel="stylesheet" href="../css/admin.css" />
  <link rel="stylesheet" href="../css/nav.css" />
  <!--icono de la pestaña-->
  <link rel="shortcut icon" href="../uploaded_img/logo.png" type="image/x-icon">
  <!-- <link rel="stylesheet" href="../css/admin_style.css"> -->

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
      <li>
        <a href="products.php">
          <i class="bx bxs-shopping-bag-alt"></i>
          <span class="text">Productos</span>
        </a>
      </li>
      <li>
        <a href="placed_orders.php?pid=pending&pid2=completed">
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
      <li class="active">
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

  <?php
  $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
  $select_profile->execute([$admin_id]);
  $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
  ?>
  <!-- SIDEBAR -->

  <!-- user accounts section starts  -->

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
      <div class="table-data">
        <div class="order">
          <div class="head">
            <h3>Cuenta de Usuario</h3>
            <i class="bx bx-search"></i>
            <i class="bx bx-filter"></i>
          </div>
          <?php $select_account = $conn->prepare("SELECT * FROM `users`");
          $select_account->execute();
          if ($select_account->rowCount() > 0) {
          ?>
            <table>
              <thead>
                <tr>
                  <th>user id </th>
                  <th>Usuario</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php


                while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
                ?>
                  <tr>
                    <td>
                      <?= $fetch_accounts['id']; ?>
                    </td>
                    <td><?= $fetch_accounts['name']; ?></td>
                    <td>
                      <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete" onclick="return confirm('decea eliminar el usuario?');"><i class="fas fa-trash"></i></a>
                    </td>
                  </tr>
                <?php
                }

                ?>
              </tbody>
            </table>
          <?php } else {
            echo '<p style= "background-color: #3c91e6;  padding: 15px 32px;">No hay usuarios</p>';
          } ?>
        </div>

      </div>
    </main>

  </section>

  <!-- user accounts section ends -->

  <!-- custom js file link  -->
  <script src="../js/admin_script.js"></script>
  <script src="../js/admin.js"></script>
</body>

</html>
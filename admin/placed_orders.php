<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:admin_login.php');
};

if (isset($_POST['update_payment'])) {

  $order_id = $_POST['order_id'];
  $payment_status = $_POST['payment_status'];
  $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
  $update_status->execute([$payment_status, $order_id]);
  $message[] = '¡Estado de la orden actualizado!';
}

if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
  $delete_order->execute([$delete_id]);
  header('location:placed_orders.php');
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
      <li class="active">
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

  <?php
  $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
  $select_profile->execute([$admin_id]);
  $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
  ?>
  <!-- SIDEBAR -->

  <!-- placed orders section starts  -->

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
         <i   class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
        }
      }
      ?>
      <ul class="box-info">
        <?php
        $pid = $_GET['pid'] ?? 'pending';
        $pid2 = $_GET['pid2'] ?? 'completed';

        $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ? or payment_status =?");
        $select_orders->execute([$pid, $pid2]);
        if ($select_orders->rowCount() > 0) {
          while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
        ?>
            <li>

              <span class="text">
                <p> <span style="font-weight: 600;">Id:</span> <?= $fetch_orders['user_id']; ?> </p>
                <p><span style="font-weight: 600;">Fecha:</span> <?= $fetch_orders['placed_on']; ?> </p>
                <p><span style="font-weight: 600;"> Nombre:</span> <?= $fetch_orders['name']; ?>
              </span> </p>
              <p><span style="font-weight: 600;"> Correo:</span> <?= $fetch_orders['email']; ?> </p>
              <p><span style="font-weight: 600;"> Número:</span><?= $fetch_orders['number']; ?></p>
              <p><span style="font-weight: 600;"> Dirección:</span><?= $fetch_orders['address']; ?></p>
              <p><span style="font-weight: 600;"> Total productos:</span> <?= $fetch_orders['total_products']; ?> </p>
              <p><span style="font-weight: 600;">Precio total :</span>$<?= $fetch_orders['total_price']; ?> </p>
              <p><span style="font-weight: 600;"> Forma de pago : </span><?= $fetch_orders['method']; ?> </p>
              <form action="" method="POST">
                <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                <select class="butonpersonaliazul" name="payment_status" class="drop-down">
                  <option style="font-size: 16px;  padding: 15px 32px; display: inline-block;" value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
                  <option style="font-size: 16px;  padding: 15px 32px; display: inline-block;" value="Pendiente">Pendiente</option>
                  <option style="font-size: 16px;  padding: 15px 32px; display: inline-block;" value="Completado">Completado</option>
                </select>
                <div class="flex-btn">
                  <input type="submit" value="Modificar" id="buttonCard" class="butonpersonaliverde" name="update_payment">
                  <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="butonpersonalirojo" onclick="return confirm('decea eliminar la orden?');">Eliminar</a>
                </div>
              </form>
              </span>
            </li>

        <?php
          }
        } else {
          echo '<p  style= "background-color: #3c91e6;  padding: 15px 32px;">No hay ordenes</p>';
        }
        ?>

      </ul>
      </div>
    </main>


  </section>

  <!-- placed orders section ends -->









  <!-- custom js file link  -->
  <script src="../js/admin_script.js"></script>

  <script src="../js/admin.js"></script>

</body>

</html>
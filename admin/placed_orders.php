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
  $message[] = 'payment status updated!';
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
  <title>messages</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  <!-- custom css file link  -->

  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet" />
  <!-- My CSS -->
  <link rel="stylesheet" href="../css/admin.css" />
  <link rel="stylesheet" href="../css/nav.css" />
  <title>Admin Hub</title>
  <link rel="shortcut icon" type="image/png" href="../images/favicon.ico" />
  <!-- <link rel="stylesheet" href="../css/admin_style.css"> -->

</head>

<body>

  <!-- SIDEBAR -->
  <?php
  if (isset($message)) {
    foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
    }
  }
  ?>

  <section id="sidebar">
    <a href="dashboard.php" class="brand">
      <i class="bx bxs-smile"></i>
      <span class="text">Admin Hub</span>
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
        <a href="placed_orders.php">
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
      <ul class="box-info">
        <?php
        $select_orders = $conn->prepare("SELECT * FROM `orders`");
        $select_orders->execute();
        if ($select_orders->rowCount() > 0) {
          while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
        ?>
            <li>

              <span class="text">
                <p> user id : <span><?= $fetch_orders['user_id']; ?></span> </p>
                <p> placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
                <p> name : <span><?= $fetch_orders['name']; ?></span> </p>
                <p> email : <span><?= $fetch_orders['email']; ?></span> </p>
                <p> number : <span><?= $fetch_orders['number']; ?></span> </p>
                <p> address : <span><?= $fetch_orders['address']; ?></span> </p>
                <p> total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
                <p> total price : <span>$<?= $fetch_orders['total_price']; ?></span> </p>
                <p> payment method : <span><?= $fetch_orders['method']; ?></span> </p>
                <form action="" method="POST">
                  <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                  <select name="payment_status" class="drop-down">
                    <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
                    <option value="pending">pending</option>
                    <option value="completed">completed</option>
                  </select>
                  <div class="flex-btn">
                    <input type="submit" value="update" class="btn" name="update_payment">
                    <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
                  </div>
                </form>
              </span>
            </li>

        <?php
          }
        } else {
          echo '<p class="empty">no orders placed yet!</p>';
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
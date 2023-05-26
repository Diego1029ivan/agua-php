<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:admin_login.php');
};

if (isset($_POST['submit'])) {

  $name = $_POST['name'];
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $pass = sha1($_POST['pass']);
  $pass = filter_var($pass, FILTER_SANITIZE_STRING);
  $cpass = sha1($_POST['cpass']);
  $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

  $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
  $select_admin->execute([$name]);

  if ($select_admin->rowCount() > 0) {
    $message[] = 'username already exists!';
  } else {
    if ($pass != $cpass) {
      $message[] = 'confirm passowrd not matched!';
    } else {
      $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
      $insert_admin->execute([$name, $cpass]);
      $message[] = 'new admin registered!';
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
  <title>register</title>

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
      <li class="active">
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

  <!-- register admin section starts  -->

  <section class="form-container" id="content">
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
        <form action="" method="POST">
          <h3 class="text-center">Register Admin</h3>
          <div class="mb-3">
            <label for="name" class="form-label">Usuario</label>
            <input class="form-control" type="text" name="name" maxlength="20" autocomplete="off" required placeholder="enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input class="form-control" type="password" name="pass" maxlength="20" required placeholder="enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')" autocomplete="off">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Confirm your password</label>
            <input class="form-control" type="password" name="cpass" maxlength="20" required placeholder="confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
          </div>
          <input type="submit" value="register now" name="submit" class="btn btn-primary">
        </form>
      </section>
    </main>
  </section>

  <!-- register admin section ends -->
















  <!-- custom js file link  -->
  <script src="../js/admin_script.js"></script>
  <script src="../js/admin.js"></script>
</body>

</html>
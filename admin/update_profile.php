<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:admin_login.php');
}

if (isset($_POST['submit'])) {

  $name = $_POST['name'];
  $name = filter_var($name, FILTER_SANITIZE_STRING);

  if (!empty($name)) {
    $select_name = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
    $select_name->execute([$name]);
    if ($select_name->rowCount() > 0) {
      $message[] = 'username already taken!';
    } else {
      $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $admin_id]);
    }
  }

  $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
  $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
  $select_old_pass->execute([$admin_id]);
  $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
  $prev_pass = $fetch_prev_pass['password'];
  $old_pass = sha1($_POST['old_pass']);
  $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
  $new_pass = sha1($_POST['new_pass']);
  $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
  $confirm_pass = sha1($_POST['confirm_pass']);
  $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

  if ($old_pass != $empty_pass) {
    if ($old_pass != $prev_pass) {
      $message[] = 'old password not matched!';
    } elseif ($new_pass != $confirm_pass) {
      $message[] = 'confirm password not matched!';
    } else {
      if ($new_pass != $empty_pass) {
        $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
        $update_pass->execute([$confirm_pass, $admin_id]);
        $message[] = 'password updated successfully!';
      } else {
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
  <title>profile update</title>

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
        <a href="placed_orders.php?pid=pending&completed">
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

  <!-- admin profile update section starts  -->

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
        <form action="" method="POST">
          <h3 class="heading text-center">update profile</h3>
          <div class="mb-3">
            <label for="name" class="form-label">Usuario</label>
            <input class="form-control" type="text" name="name" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" placeholder="<?= $fetch_profile['name']; ?>">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input class="form-control" type="password" name="old_pass" maxlength="20" placeholder="enter your old password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Nuevo Passaword</label>
            <input class="form-control" type="password" name="new_pass" maxlength="20" placeholder="enter your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Confirmar Passaword</label>
            <input class="form-control" type="password" name="confirm_pass" maxlength="20" placeholder="confirm your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
          </div>
          <input type="submit" value="update now" name="submit" class="btn btn-primary">
        </form>
      </section>
    </main>
  </section>

  <!-- admin profile update section ends -->









  <!-- custom js file link  -->
  <script src="../js/admin_script.js"></script>
  <script src="../js/admin.js"></script>
</body>

</html>
<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>La Colpa</title>

  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
  <!--icono de la pestaña-->
  <link rel="shortcut icon" href="uploaded_img/logo.png" type="image/x-icon">
  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css">

</head>

<body>

  <?php include 'components/user_header.php'; ?>



  <section class="hero">

    <div class="swiper hero-slider">

      <div class="swiper-wrapper">

        <div class="swiper-slide slide">
          <div class="content">
            <span>Pide ahora</span>
            <h3>"Agua envasada, pureza en cada botella para cuidar de ti y tu familia."</h3>
            <a href="menu.html" class="btn">ver productos</a>
          </div>
          <div class="image">
            <img src="images/slider.png" alt="">
          </div>
        </div>

        <div class="swiper-slide slide">
          <div class="content">
            <span>Pide ahora</span>
            <h3>"Agua pura, calidad asegurada para tu bienestar"</h3>
            <a href="menu.html" class="btn">ver productos</a>
          </div>
          <div class="image">
            <img src="images/slider2.png" alt="">
          </div>
        </div>

        <div class="swiper-slide slide">
          <div class="content">
            <span>Pide ahora</span>
            <h3>"Elige salud, elige nuestras aguas envasadas sin aditivos ni conservantes."</h3>
            <a href="menu.html" class="btn">ver productos</a>
          </div>
          <div class="image">
            <img src="images/slider3.png" alt="">
          </div>
        </div>

      </div>

      <div class="swiper-pagination"></div>

    </div>

  </section>

  <section class="category">

    <h1 class="title">Categoría de bebidas</h1>

    <div class="box-container">

      <a href="category.php?category=1" class="box">
        <img src="images/botella.png" alt="">
        <h3>Personal</h3>
      </a>

      <a href="category.php?category=2" class="box">
        <img src="images/bidon.png" alt="">
        <h3>Bidón</h3>
      </a>

      <!-- <a href="category.php?category=3" class="box">
         <img src="images/cat-3.png" alt="">
         <h3>--</h3>
      </a>

      <a href="category.php?category=4" class="box">
         <img src="images/cat-4.png" alt="">
         <h3>--</h3>
      </a> -->

    </div>

  </section>




  <section class="products">

    <h1 class="title">Últimos productos</h1>

    <div class="box-container">

      <?php
      $select_products = $conn->prepare("SELECT * FROM `products`,`categoria`
                                             WHERE `products`.`categoria_id`=`categoria`.`idcategoria` LIMIT 6");
      $select_products->execute();
      if ($select_products->rowCount() > 0) {
        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
      ?>
          <form action="" method="post" class="box">
            <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
            <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
            <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
            <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
            <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
            <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
            <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
            <a href="category.php?category=<?= $fetch_products['descripcion']; ?>" class="cat"><?= $fetch_products['descripcion']; ?></a>
            <div class="name"><?= $fetch_products['name']; ?></div>
            <div class="flex">
              <div class="price"><span>$</span><?= $fetch_products['price']; ?></div>
              <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
            </div>
          </form>
      <?php
        }
      } else {
        echo '<p class="empty">No hay productos agregados todavía!</p>';
      }
      ?>

    </div>

    <div class="more-btn">
      <a href="menu.html" class="btn">Ver todos los productos</a>
    </div>

  </section>


















  <?php include 'components/footer.php'; ?>


  <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

  <!-- custom js file link  -->
  <script src="js/script.js"></script>

  <script>
    var swiper = new Swiper(".hero-slider", {
      loop: true,
      grabCursor: true,
      effect: "flip",
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    });
  </script>

</body>

</html>
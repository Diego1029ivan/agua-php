<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>La Colpa</title>
  <!--icono de la pestaña-->
  <link rel="shortcut icon" href="uploaded_img/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css">

</head>

<body>

  <!-- header section starts  -->
  <?php include 'components/user_header.php'; ?>
  <!-- header section ends -->

  <div class="heading">
    <h3>Nosotros</h3>
    <p><a href="home.php">Principal</a> <span> / Nosotros</span></p>
  </div>

  <!-- about section starts  -->

  <section class="about">

    <div class="row">

      <div class="image">
        <img src="images/nosotros.jpg" alt="">
      </div>

      <div class="content">
        <h3>Por qué escogernos?</h3>
        <p>La Colpa es una empresa dedicada a la producción, tratamiento y distribución de agua potable; para suplir la necesidad de agua de nuestros usuarios. Estamos ubicado en el Jr. Independencia 643 en el Distrito de Cacatachi, provincia de San Martin, Departamento de San Martin.</p>
        <a href="menu.php" class="btn">nuestros productos</a>
      </div>

    </div>

  </section>

  <!-- about section ends -->

  <!-- steps section starts  -->

  <section class="steps">

    <h1 class="title">Pasos para pedido</h1>

    <div class="box-container">

      <div class="box">
        <img src="images/iconcall.png" alt="">
        <h3>Llamar</h3>
        <p>Si deseas contactar nuestro departamento de ventas, llama 990431916 - 949804737</p>
      </div>

      <div class="box">
        <img src="images/step-2.png" alt="">
        <h3>delivery</h3>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nesciunt, dolorem.</p>
      </div>

      <div class="box">
        <img src="images/iconbotella.png" alt="">
        <h3>disfruta tu bebida</h3>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nesciunt, dolorem.</p>
      </div>

    </div>

  </section>

  <!-- steps section ends -->

  <!-- reviews section starts  -->

  <section class="reviews">

    <h1 class="title">sliders de fotos</h1>

    <div class="swiper reviews-slider">

      <div class="swiper-wrapper">

        <div class="swiper-slide slide">
          <img src="images/empresa1.png" alt="">

        </div>

        <div class="swiper-slide slide">
          <img src="images/empresa2.jpg" alt="">

        </div>

        <div class="swiper-slide slide">
          <img src="images/empresa3.jpeg" alt="">


        </div>

        <div class="swiper-slide slide">
          <img src="images/empresa4.jpg" alt="">

        </div>

        <div class="swiper-slide slide">
          <img src="images/empresa5.jpg" alt="">

        </div>



      </div>

      <div class="swiper-pagination"></div>

    </div>

  </section>

  <!-- reviews section ends -->

  <section class="about">

    <div class="row">

      <div class="image">
        <img src="images/mision.png" alt="">
      </div>

      <div class="content">
        <h3>Misión</h3>
        <p>Proporcionar con calidad y eficiencia los servicios de agua potable y saneamiento, para contribuir al bienestar, la calidad de vida y el cuidado del entorno ecológico de cada uno de nuestros usuarios apegándonos al Marco Legal.</p>

      </div>

    </div>

  </section>

  <section class="about">

    <div class="row">

      <div class="content">
        <h3>Visión</h3>
        <p>Garantizar el suministro de agua potable y el saneamiento a las próximas generaciones, satisfacer las necesidades de nuestros clientes, tener una buena vista y ser reconocidos.</p>

      </div>
      <div class="image">
        <img src="images/vision.png" alt="">
      </div>



    </div>

  </section>

















  <!-- footer section starts  -->
  <?php include 'components/footer.php'; ?>
  <!-- footer section ends -->=






  <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

  <!-- custom js file link  -->
  <script src="js/script.js"></script>

  <script>
    var swiper = new Swiper(".reviews-slider", {
      loop: true,
      grabCursor: true,
      spaceBetween: 20,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      autoplay: {
        delay: 3500,
        disableOnInteraction: true,
      },
      breakpoints: {
        0: {
          slidesPerView: 1,
        },
        700: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      },
    });
  </script>

</body>

</html>
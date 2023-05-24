<?php


include '../components/connect.php';


class Productos
{


  //implementamos nuestro constructor
  public function __construct()
  {
  }
  public function coneccion()
  {
    $db_name = 'mysql:host=localhost;dbname=food_db';
    $user_name = 'root';
    $user_password = '';

    $conn = new PDO($db_name, $user_name, $user_password);
    return $conn;
  }
  // MOSTAR PRODUCTOS
  public function mostrarProductos()
  {

    $productos =  $this->coneccion()->prepare("SELECT products.*, categoria.descripcion FROM products INNER JOIN categoria ON products.categoria_id =categoria.idcategoria");
    $productos->execute();
    $productos = $productos->fetchAll(PDO::FETCH_ASSOC);
    return $productos;
  }
}

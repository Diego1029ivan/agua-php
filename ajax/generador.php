<?php
require_once  '../model/productos.php';
$productos = new Productos();
switch ($_GET["op"]) {
  case 'listar':
    $data = array();
    $productos = $productos->mostrarProductos();
    echo json_encode($productos);
    break;
}

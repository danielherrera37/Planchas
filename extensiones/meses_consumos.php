<?php
  include_once '../conexion/conexion.php';
  if(isset($_POST['mes'])) {
    $mes	= $conexion->real_escape_string($_POST['mes']);
    $meses = mysqli_query($conexion, "SELECT consumo from `tabla_consumo$mes` as tbc inner join articulos as art on art.id_articulo = tbc.id_articulo");
  }
  $datos = mysqli_fetch_all($meses, MYSQLI_ASSOC);

  mysqli_close($conexion);

  echo json_encode($datos);
?>
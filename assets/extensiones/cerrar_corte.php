<?php
  include_once '../conexion/conexion.php';

  $fecha_activa = date("Y-m",strtotime($fecha_hoy."- 1 month,"));
  $fecha_despues = date("Y-m-06 H:i:s");
  if($fecha_mes >= $fecha_despues) {
    $cerrar_cortes = mysqli_query($conexion,"UPDATE corte_consumos set estado = 'bloqueado' where fecha_corte like '%$fecha_activa%'");
  }
?>
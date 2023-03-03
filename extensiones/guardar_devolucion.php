<?php
  include_once '../conexion/conexion.php';
  $mes_hoy 						= date("n");
  $fecha_consumo 			= date("Y-m-d H:i:s");

  if($fecha_hoy <= $fecha_cierre){ 
    $fecha_consumo = $_POST['fecha_consumo'];
  }
  
  $cantidad	    = $conexion->real_escape_string($_POST['cantidad']);
  $orden	      = $conexion->real_escape_string($_POST['orden']);
  $id_articulo	= $conexion->real_escape_string($_POST['id_articulo']);
  $descripcion	= $conexion->real_escape_string($_POST['descripcion']);
  $observacion	= $conexion->real_escape_string($_POST['observacion']);
  $ubicacion	  = $conexion->real_escape_string($_POST['ubicacion']);
  $id_usuario	  = $conexion->real_escape_string($_POST['id_consumidor']);

	if(($cantidad != "") and ($orden != "") and ($descripcion != "") and ($ubicacion != "")){
    $guardarconsumo = mysqli_query($conexion,"INSERT into consumo_planchas values (null,$id_usuario,'$orden','$id_articulo','$descripcion','$fecha_consumo','$observacion','$ubicacion','$cantidad','0')");
    $actualizarrecuento = mysqli_query($conexion, "UPDATE tabla_recuento set inventario = (inventario - $cantidad),resultado = (resultado - $cantidad) where id_articulo = $id_articulo");
    $actualizaubicaciones = mysqli_query($conexion, "UPDATE `tabla_inventario` set `$ubicacion` = (`$ubicacion` - $cantidad) where id_articulo = $id_articulo");
    $actualizameses       = mysqli_query($conexion, "UPDATE `inventario_meses` set `$meses[$mes_hoy]` = (`$meses[$mes_hoy]` - $cantidad) where id_articulo = $id_articulo");
    $actualizacorte       = mysqli_query($conexion, "UPDATE `tabla_consumo$corteEnCurso` set `consumo` = (consumo + $cantidad) where id_articulo = $id_articulo");
  }
?>
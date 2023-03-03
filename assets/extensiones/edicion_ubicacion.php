<?php
	include_once '../conexion/conexion.php';

	if((isset($_POST['id_ubicacion'])) and (isset($_POST['valor']))){
		$valor = $_POST['valor'];
		$id = $_POST['id_ubicacion'];
		$update = mysqli_query($conexion,"UPDATE ubicaciones SET estado = '$valor' WHERE id_Ubicacion = '$id'");
	}

	if(isset($_POST['actu_ubi'])){
		$ubicacion 	= $_POST['actu_ubi'];
		$id 	= $_POST['id'];

		$update 		= mysqli_query($conexion,"UPDATE ubicaciones SET ubicacion = '$ubicacion' WHERE id_Ubicacion = '$id'");
	}
?>
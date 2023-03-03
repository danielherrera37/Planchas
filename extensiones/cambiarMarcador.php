<?php
  include_once '../conexion/conexion.php';
	if(isset($_POST['valorMarcador']) != ""){
		$valorMarcador	= $conexion->real_escape_string($_POST['valorMarcador']);
    $idMarcador	= $conexion->real_escape_string($_POST['idMarcador']);
		$cambiarModificador = mysqli_query($conexion, "UPDATE consumo_planchas set marcador = '$valorMarcador' where id_consumo = '$idMarcador'");		

    if($cambiarModificador){
      echo '<script type="text/javascript">
        alert("Marcador caducado com sucesso!");
      </script>';
    }
	}
?>
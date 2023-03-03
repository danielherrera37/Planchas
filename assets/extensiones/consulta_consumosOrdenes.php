<?php
	/////// CONEXIÓN A LA BASE DE DATOS /////////
	include_once '../conexion/conexion.php';
	// error_reporting(0);
	$solo_fecha = date("Y-m-d");
	$desde 	= date('Y-m-01 00:00:00');
	$date = new DateTime('now');
	$date->modify('last day of this month');
	$hasta =  $date->format('Y-m-d');

	$maximo_total = 0;
	$consumos = "SELECT * from ordenes limit 10";	

	///////// LO QUE OCURRE AL TECLEAR SOBRE EL INPUT DE BUSQUEDA ////////////
	if(isset($_POST['valor']) != ""){
		$parametro	= $conexion->real_escape_string($_POST['valor']);
		$consumos = "SELECT * from ordenes where numero_op like '%$parametro%' limit 10";		
		$desde	= $conexion->real_escape_string($_POST['Ordendesde']);
		$hasta	= $conexion->real_escape_string($_POST['Ordenhasta']);
	}
	if((isset($_POST['trabajo']) != "") or (isset($_POST['trabajo']) != null)){
		$trabajo	= $conexion->real_escape_string($_POST['trabajo']);
		$consumos = "SELECT * from ordenes where nombre_trabajo like '%$trabajo%' limit 10";	
		$desde	= $conexion->real_escape_string($_POST['Ordendesde']);
		$hasta	= $conexion->real_escape_string($_POST['Ordenhasta']);
	}
	$consulta = mysqli_query($conexion,$consumos);
	if(mysqli_num_rows($consulta) >= 1){
?>
	<table border="0" cellspacing="10">
		<thead>
			<tr>
				<th>Numero op</th>
				<th>Descripcion trabajo</th>
				<th>Cantidad total</th>
			</tr>
		</thead>

		<tbody>
			<?php
				while ($fila 	= mysqli_fetch_array($consulta)) {
					$ordenes_consumo = mysqli_query($conexion,"SELECT sum(cantidad) as total from consumo_planchas where (fecha_consumo between '$desde' and '$hasta') and numero_op = '$fila[numero_op]' limit 20");
					while($cantidad = mysqli_fetch_array($ordenes_consumo)){
			?>
			<tr>
				<td><?php echo $fila['numero_op']; ?></td>
				<td><?php echo $fila['nombre_trabajo']; ?></td>
				<td><?php echo $cantidad['total']; ?></td>
			</tr>
			<?php
						$maximo_total = $maximo_total + $cantidad['total'];	
					}
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2">TOTAL</th><th><?php echo $maximo_total ?></th>
				</tr>
			</tfoot>
	</table>

	<?php
		}else{
	?>
	<table border="0" cellspacing="10">
		<thead>
			<tr>
				<th>Numero op</th>
				<th>Descripcion trabajo</th>
				<th>Cantidad total</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td colspan="3">No se encontro el registro</td>
			</tr>
			</tbody>
	</table>
	<?php
		}
	?>
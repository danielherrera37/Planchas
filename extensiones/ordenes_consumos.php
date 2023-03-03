<?php
	/////// CONEXIÓN A LA BASE DE DATOS /////////
	include_once '../conexion/conexion.php';

	$dia 					= date('Y-m-01');
	$consumos = false;
	$consulta	= mysqli_query($conexion,"SELECT * from ordenes limit 20");

	///////// LO QUE OCURRE AL TECLEAR SOBRE EL INPUT DE BUSQUEDA ////////////
	if(isset($_POST['ordenes'])){
		$parametro	= $conexion->real_escape_string($_POST['ordenes']);
		$consulta 	= mysqli_query($conexion,"SELECT * from ordenes WHERE numero_op LIKE '%".$parametro."%' OR nombre_trabajo LIKE '%".$parametro."%' limit 20");
		$consumos 	= mysqli_query($conexion,"SELECT * from consumo_planchas WHERE numero_op LIKE '%".$parametro."%' and (fecha_consumo between '$fecha_inicio' and '$datetime_final')");
	}
?>

<div class="contenedor_tablas">
	<h2>Ordenes, en vista <?php echo mysqli_num_rows($consulta);?></h2>
	<h4>Para ver mas ingrese una OP en el buscador del formulario</h4>

	<table border="0" cellspacing="10" class="datos_planchas" id="ordenes_consumir">
		<thead>
			<tr>
				<th>Numero op</th>
				<th>Descripcion trabajo </th>
			</tr>
		</thead>

		<tbody>
			<?php
				while ($fila = mysqli_fetch_array($consulta)) {
			?>
			<tr onclick="traerOrden(this.id)" id="op<?php echo $fila["numero_op"]; ?>">
				<td>
					<?php echo $fila['numero_op']; ?>
					<input type="hidden" value="<?php echo $fila['numero_op']; ?>" id="op<?php echo $fila['numero_op']; ?>">
				</td>
				<td align="left">
					<?php echo $fila['nombre_trabajo']; ?>
					<input type="hidden" value="<?php echo $fila['nombre_trabajo']; ?>" id="trabajo<?php echo $fila['numero_op']; ?>">
				</td>
			</tr>
			<?php
				}
			?>
		</tbody>
	</table>
</div>

<!-- POR SI ENCUENTRA CONSUMOS YA HECHOS EN EL DIA DE LA MISMA ORDEN -->
<?php
	if($consumos){
		if(mysqli_num_rows($consumos) >= 1){
?>

<table border="0" cellspacing="10" class="datos_planchas">
	<thead>
		<tr>
			<th colspan="6">Consumo de ordenes en este mes</th>
		</tr>
		<tr>
			<th>N# consumo</th>
			<th>N# OP</th>
			<th>Articulo</th>
			<th>Fecha consumo</th>
			<th>Observacion</th>
			<th>Cantidad</th>
		</tr>
	</thead>

	<tbody>
		<?php
			while ($linea 	= mysqli_fetch_array($consumos)) {
		?>
		<tr>
			<td><?php echo $linea['id_consumo']; ?></td>
			<td><?php echo $linea['numero_op']; ?></td>
			<td><?php echo $linea['descripcion']; ?></td>
			<td><?php echo $linea['fecha_consumo']; ?></td>
			<td><?php echo $linea['observacion']; ?></td>
			<td><?php echo $linea['cantidad']; ?></td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>

<?php
		}
	}
?>
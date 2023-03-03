<?php
	/////// CONEXIÓN A LA BASE DE DATOS /////////
	include_once '../conexion/conexion.php';

	//////////////// VALORES INICIALES ///////////////////////
	// error_reporting(0);
	$dia 					= date('Y-m-01');
	$consulta	= mysqli_query($conexion,"SELECT * from ordenes limit 20");

	$primer_mes_dia = date('Y-m-01 00:00:00');
?>

<div class="contenedor_tabla">
	<table border="1" cellspacing="10" class="datos_planchas">
		<thead>
			<tr>
				<th>Descripcion</th>
				<?php 
					$a = 1;
					$adicional = 0;
					$ubicaciones = mysqli_query($conexion,"SELECT * FROM ubicaciones where estado = 'activo'");
					while ($consulta = mysqli_fetch_array($ubicaciones)){
						echo "<th>$consulta[ubicacion]</th>";
						$ubicacion[$a] = $consulta['id_ubicacion'];
						$ubicacionTotal[$a] = 0;
						$a++;
					}
				?>
				<th>Total</th>
			</tr>
		</thead>

		<tbody>
			<?php
				$query =  mysqli_query($conexion, "SELECT id_articulo,descripcion from articulos");
				while ($consulta = mysqli_fetch_array($query)){
					$stop = 0;
			?>
			<tr>
				<td nowrap="2" align="left"><?php echo $consulta['descripcion']; ?></td>
				<?php
					$e = 1;
					$gran_total = 0;
					while($e < $a){
						$contador =  mysqli_query($conexion, "SELECT sum(cantidad) as cantidad from consumo_planchas where id_ubicacion = $ubicacion[$e] and id_articulo = $consulta[id_articulo]");
						if((isset($_POST['hasta']) or (isset($_POST['desde'])))){
							$hasta	= $conexion->real_escape_string($_POST['hasta']);
							$desde	= $conexion->real_escape_string($_POST['desde']);
							$contador =  mysqli_query($conexion, "SELECT sum(cantidad) as cantidad from consumo_planchas where id_ubicacion = $ubicacion[$e] and id_articulo = $consulta[id_articulo] and (fecha_consumo between '$desde' and '$hasta')");
						}
						if(mysqli_num_rows($contador) >= 1){
							while ($fila = mysqli_fetch_array($contador)){
								echo "<td>$fila[cantidad]</td>";
								$ubi = $fila['cantidad'];
							}
						}
						$ubicacionTotal[$e] = $ubi + $ubicacionTotal[$e];

						$incluir = $ubi + $stop;
						$stop = $ubi + $stop;
						$adicional = $stop;
						$e++;
					}
					$gran_total = $gran_total + $adicional;
				?>
				<td><?php  echo $stop; ?></td>
			</tr>
			<?php
				}
			?>
			<tr>
				<th>Total</th>
				<?php 
					$i = 1;
					$estatico = 0;
					while($i < $a){ 
						echo '<th>'.$ubicacionTotal[$i].'</th>';
						$total = $estatico + $ubicacionTotal[$i];
						$estatico = $ubicacionTotal[$i];
						$i++;
					}
					echo '<th>'.$gran_total.'</th>';
				?>
			</tr>
	</table>
</div>

<table border="1" id="tabla_medidas">
	<thead>
		<tr>
			<th>Medida</th>
			<th>Cantidad</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$DB_HOST = $_ENV['DB_HOST'];
		$DB_USER = $_ENV['DB_USER'];
		$DB_PASSWORD = $_ENV['DB_PASSWORD'];
		$DB_NAME = $_ENV['DB_NAME'];
		$DB_PORT = $_ENV['DB_PORT'];
	
		$conexion=mysqli_connect($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_NAME,$DB_PORT);
		$date = new DateTime('now');
		$date->modify('last day of this month');

		$solo_fecha = date('Y-m-01 00:00:00');
		$fecha_final =  $date->format('Y-m-d 23:59:59');
		
		$medidas = mysqli_query($conexion, "SELECT distinct(medida) from articulos");
		if(isset($_POST['medida'])){
			$parametro	= $conexion->real_escape_string($_POST['medida']);
			$medidas    = mysqli_query($conexion, "SELECT distinct(medida) from articulos where medida like '%$parametro%'");
		}
		$total = 0;
		if(mysqli_num_rows($medidas) >= 1){
			while($refe = mysqli_fetch_array($medidas)){
				$consumos = mysqli_query($conexion, "SELECT sum(cantidad) as cantidad,medida from consumo_planchas as consumo inner join articulos on articulos.id_articulo = consumo.id_articulo where medida like '%$refe[medida]%' and  (fecha_consumo between '$solo_fecha' and '$fecha_final') GROUP BY medida");
				while($recorrer = mysqli_fetch_array($consumos)){
					echo "<tr><td>$recorrer[medida]</td><td>$recorrer[cantidad]</td></td></tr>";
					$total = $total + $recorrer["cantidad"];
				}
			}
		}
		else{
			echo "<tr><td colspan='7'>No se encontraron registros</td></tr>";
		}
		?>
	</tbody>
	<tfoot>
		<tr>
			<th>TOTAL</th><th><?php echo $total; ?></th>
		</tr>
	</tfoot>
</table>

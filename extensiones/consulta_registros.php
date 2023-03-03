
	<div class="tabla_registros">
<br>
<table id="tabla_consumos" border="0">
	<thead>
		<tr>
			<th>#</th>
			<th>Nº consumo</th>
			<th>Consumidor</th>
			<th>OP</th>
			<th>Descripcion</th>
			<th>Fecha Consumo</th>
			<th>Observacion</th>
			<th>cantidad</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$consumos = mysqli_query($conexion, "SELECT id_consumo,nombre,numero_op,descripcion,fecha_consumo,observacion,cantidad,marcador from consumo_planchas inner join usuarios on usuarios.id_usuario = consumo_planchas.id_usuario order by id_consumo desc,fecha_consumo desc");
			while($recorrer = mysqli_fetch_array($consumos)){
				$fecha_consumo = $recorrer['fecha_consumo'];
				$mes_consumo 			= date("m",strtotime($fecha_consumo));
				$dia_consumo 			= date("d",strtotime($fecha_consumo));
				$year_consumo 			= date("Y",strtotime($fecha_consumo));
				$hora_consumo 			= date("H:i:s",strtotime($fecha_consumo));
				?>
				<tr <?php if($recorrer["marcador"] == 1){ echo "class='TRUE'"; } ?> id='fila<?php echo $recorrer["id_consumo"]?>'>
			<td><input type='checkbox' class='check_lista' name='check_lista[]' value='<?php echo $recorrer["marcador"]?>' id='<?php echo $recorrer["id_consumo"]?>' onclick='cambiarMarcador(this.id)' <?php if($recorrer["marcador"] == 1){ echo "checked"; } ?>></td>
			<td><?php echo $recorrer["id_consumo"] ?></td><td><?php echo $recorrer["nombre"] ?></td>
			<td><?php echo $recorrer["numero_op"] ?></td>
			<td nowrap='2' align='left'><?php echo $recorrer["descripcion"]?></td>
			<td><?php echo "$dia_consumo / $mes_consumo / $year_consumo - $hora_consumo" ?></td>
			<td><?php echo $recorrer["observacion"] ?></td><td><?php echo $recorrer["cantidad"] ?></td></tr>
			<?php
			}
		?>
	</tbody>
</table>
</div>
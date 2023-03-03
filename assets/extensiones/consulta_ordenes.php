
		<?php
			/////// CONEXIÓN A LA BASE DE DATOS /////////
      include_once '../conexion/conexion.php';
			//////////////// VALORES INICIALES ///////////////////////
			
			$dia 					= date('Y-m-01');
			date_default_timezone_set("America/Bogota");
			setlocale(LC_ALL,"es_ES");
			$fecha_hoy 		= date("Y-m-d H:i:s");
			$fecha_hoy_final 		= date("Y-m-d 23:59:59");
			$fecha_hoy_inicio 		= date("Y-m-d 00:00:00");
			$mes_hoy 			= date("m");
			$consulta	= mysqli_query($conexion,"SELECT * from ordenes limit 20");
      $cantidad_busqueda = mysqli_num_rows($consulta);
      $orden_totales	= mysqli_query($conexion,"SELECT count(*) as total from ordenes");
      $cantidad_ordenes = mysqli_fetch_assoc($orden_totales);
			///////// LO QUE OCURRE AL TECLEAR SOBRE EL INPUT DE BUSQUEDA ////////////
?>

      <div class="contenedor_tabla">
        <h1>Un total de <?php echo $cantidad_ordenes['total']; ?> ordenes registradas</h1>
        <table border="0" cellspacing="5" id="tabla_ordenes_ingresadas">
          <thead>
            <tr>
              <th>OP</th>
              <th>Nombre del trabajo</th>
              <th>Fecha</th>
              <th>CP</th>
              <th>Nom Centro</th>
              <th>Cantidad</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $ordenes = mysqli_query($conexion, "SELECT * from ordenes order by numero_op desc limit 20");
              if(isset($_POST['op'])){
                $parametro	= $conexion->real_escape_string($_POST['op']);
                $ordenes = mysqli_query($conexion, "SELECT * from ordenes where numero_op like '%$parametro%' or nombre_trabajo like '%$parametro%' order by numero_op desc limit 20");
              }
              while($ordenar = mysqli_fetch_array($ordenes)){
            ?>
            <tr>
              <td><?php echo $ordenar['numero_op']; ?></td>
              <td align="left"><?php echo $ordenar['nombre_trabajo']; ?></td>
              <td nowrap="2"><?php echo $ordenar['fecha']; ?></td>
              <td><?php echo $ordenar['cp']; ?></td>
              <td><?php echo $ordenar['nombre_centro']; ?></td>
              <td><?php echo $ordenar['cantidad_planeada']; ?></td>
            </tr>
            <?php
              }
            ?>
          </tbody>
        </table>
      </div>

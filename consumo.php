<?php
	include_once 'conexion/conexion.php';
	session_start();
	if(!$_SESSION){
		header('location: inventario.php');
	}
	if($_SESSION['Inventario'] != 'si'){
		header('location: inventario.php');
	}
	$id_usuario = $_SESSION['id_usuario'];
	$nombre_usuario = $_SESSION['nombre'];
	$solo_fecha = date("Y-m-d");
	$i = 1;

	/* OBTENER DATOS DE URL */
	$i = 0;
	$intervalos = mysqli_query($conexion," SELECT * FROM corte_consumos");
	while($lapso = mysqli_fetch_array($intervalos)){
		$temporada[$i] = $lapso['fecha_corte']; 
		$i++;
	}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- css -->
		<link rel="stylesheet" href="assets/css/main.css">
		<link rel="stylesheet" href="assets/css/exportar_excel.css">
		<link rel="stylesheet" type="text/css" href="assets/dataTables/css/dataTables.dataTables.css"/>
		<link rel="stylesheet" href="assets/dataTables/css/jquery.dataTables.css">
		<!-- js -->
		<script src="assets/js/jQuery/jquery-1.10.2.js"></script>

		<script src="assets/js/xlsx.full.min.js"></script>
		<link rel="shortcut icon" href="assets/imagenes/LOGO.png" />

		<title>INFORME</title>

	</head>

	<body>
		<div class="contenedor_mayor">
			<div class="nav_superior">
        <div class="hamburger">
					<div class="one"></div>
					<div class="two"></div>
					<div class="three"></div>
        </div>
        <div class="top_menu">
            <div class="logo"><?php echo $nombre_usuario ?></div>
						<div class="logo">
							<form action="cambiar_clave.php" method="POST">
								<input type="submit" name="cambiar_clave" value="Cambiar clave" class="boton-usuario">
							</form>
						</div>
						<div class="logo">
							<form action="index.php" method="POST">
								<input type="submit" name="cerrar_sesion" value="Cerrar sesion" class="boton-usuario">
							</form>
						</div>
        </div>
    </div>

    <div class="nav_lateral">
        <ul>
					<?php if($_SESSION['Consumo_planchas'] == 'si'){ ?><li><a href="principal.php" 				>Inicio</a>									</li><?php } ?>
					<?php if($_SESSION['informe_consumo']  == 'si'){ ?><li><a href="consumo.php" 					class="active">Informe de consumo</a>			</li><?php } ?>
					<?php if($_SESSION['Inventario'] 			 == 'si'){ ?><li><a href="inventario.php" 				>inventario</a>							</li><?php } ?>
					
					<?php if($_SESSION['Importar_ordenes'] == 'si'){ ?><li><a href="importar_ordenes.php" 	>Importar ordenes</a>				</li><?php } ?>
					<?php if($_SESSION['Usuarios'] 				 == 'si'){ ?><li><a href="usuarios.php" 					>Usuarios</a>								</li><?php } ?>
					<?php if($_SESSION['Corte_consumos'] 	 == 'si'){ ?><li><a href="corte_consumos.php"  	>Corte de mes</a>						</li><?php } ?>
        </ul>
    </div>

    <div class="main_container pag_consumos">
			<div class="consumo_meses">
				<header>TOTAL CONSUMOS</header>
				<p>Puede seleccionar hasta 3 meses para observar un comparativo entre las fechas seleccionadas y exportar los datos en un archivo excel</p>
					<div class="selector_meses">
						<div class="seleccion_meses">
							<div class="mes_container">
								<div class="context_menu">Seleccionar mes</div>
								<ul class="selector">
									<?php for($e = 0;$e < $i; $e++){ ?>
										<li onclick="obtenerMes1('<?php echo $temporada[$e]; ?>')"><?php echo $temporada[$e]; ?></li >
									<?php } ?>
								</ul>
							</div>

							<div class="mes_container" >
								<div class="context_menu">Seleccionar mes</div>
								<ul class="selector">
									<?php for($e = 0;$e < $i; $e++){ ?>
										<li onclick="obtenerMes2('<?php echo $temporada[$e]; ?>')"><?php echo $temporada[$e]; ?></li >
									<?php } ?>
								</ul>
							</div>

							<div class="mes_container">
								<div class="context_menu">Seleccionar mes</div>
								<ul class="selector">
								<?php for($e = 0;$e < $i; $e++){ ?>
									<li onclick="obtenerMes3('<?php echo $temporada[$e]; ?>')"><?php echo $temporada[$e]; ?></li >
								<?php } ?>
								</ul>
							</div>

						</div>
						<div class="respuesta_meses">
							<table class="datos_planchas" border="1" cellspacing="10" id="descripcion_articulos">
								<thead>
									<tr>
										<th>Articulo</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$descripcion = mysqli_query($conexion, "SELECT * from articulos");
										while($fila = mysqli_fetch_array($descripcion)) {
									?>
									<tr>
										<td><?php echo $fila['descripcion']; ?></td>
									</tr>
									<?php
										}
									?>
								</tbody>
							</table>
							<table border="1" cellspacing="10" class="datos_planchas" id="meses_1">
								<thead>
									<tr>
										<th id="consumo_1">Consumo</th>
									</tr>
								</thead>
									<tbody id="cuerpo_meses_1"></tbody>
							</table>

							<table border="1" cellspacing="10" class="datos_planchas" id="meses_2">
								<thead>
									<tr>
										<th id="consumo_2">Consumo</th>
									</tr>
								</thead>
									<tbody id="cuerpo_meses_2"></tbody>
							</table>

							<table border="1" cellspacing="10" class="datos_planchas" id="meses_3">
								<thead>
									<tr>
										<th id="consumo_3">Consumo</th>
									</tr>
								</thead>
									<tbody id="cuerpo_meses_3"></tbody>
							</table>

							<div id="exportar_excel">
								<button onclick="exportToExcel()">Exportar excel</button>
								<button onclick="cleanScreen()">Vaciar tablas</button>
							</div>

						</div>
					</div>
			</div>

			<div class="consumo_ubicaciones">
				<header>Consumos por mes y ubicacion</header>
				<h4>Ingrese una fecha para calcular los consumos dentro la fecha buscada</h4><br>
				<div class="btn_fechas">
					Desde -> <input type="date" name="consumo_fecha" id="consumo_desde" placeholder="Buscar..." value="<?php echo $fecha_inicio; ?>">
					Hasta -> <input type="date" name="consumo_fecha" id="consumo_hasta" placeholder="Buscar..." value="<?php echo $solo_fecha; ?>"><br>
				</div>
				<aside id="tabla_ubicaciones">
						<!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA -->
				</aside>
			</div>


			<div class="consumo_medidas">
				<header>Consumos por medida de articulos dentro del mes en curso</header>
				<h4>Escoga una medida para buscar consumos</h4><br>
				<select name="medidas" id="medidas">
					<option value=""  disabled selected>Seleccione una opcion</option>	
					<option value="X">Todos las medidas</option>
					<?php 
					$medidas = mysqli_query($conexion, "SELECT medida from articulos group by medida");
					while($ref = mysqli_fetch_array($medidas)){
						echo "<option value='$ref[medida]'>$ref[medida]</option>";
					}
					?>
				</select><br>
				<aside id="tabla_medidas">
						<!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA -->
				</aside>
			</div>


			<div class="consumo_ordenes">
				<header>Consumos por ordenes de acuerdo a las fechas</header>
				<div class="conOrdenesFecha">
					<div class="buscadores">
						Busque una orden: <input type="text" id="busqueda_Consumoordenes" placeholder="Buscar..." autocomplete="off" >
						<p>O busque por nombre del trabajo: <input type="text" id="busqueda_Nombretrabajo" placeholder="Buscar..." autocomplete="off" ></p>
					</div>
					<div class="fechas">
						Desde <input type="date" id="ordenes_desde" placeholder="Buscar..." value="<?php echo $fecha_inicio; ?>">
						<p>Hasta <input type="datetime-local" id="ordenes_hasta" placeholder="Buscar..." value="<?php echo $datetime_final; ?>" style="width: auto;"></p>
					</div>
				</div>
				<aside id="consumo_ordenes">
						<!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA -->
				</aside>
			</div>

			<div class="registros_consumos">
				<header>Tabla de registros de consumos de <?php echo $corteEnCurso; ?></header>
				<h4>A continuacion esta la tabla de consumo del mes en curso de las ordenes,para observar los registros de alguna solo debe presionar sobre la fila a observar</h4>
				<div class="contenedor_tabla">
					<table border="1" class="acordeon">
						<thead>
							<tr>
								<th>Nº consumo</th>
								<th>Consumidor</th>
								<th>OP</th>
								<th>Nombre del trabajo</th>
								<th>Articulo</th>
								<th>Fecha Consumo</th>
								<th>Observacion</th>
								<th>cantidad</th>
							</tr>
						</thead>

						<tbody>
							<?php
							$a = 0;
								$ordenes = mysqli_query($conexion,"SELECT numero_op from consumo_planchas where (fecha_consumo between '$dia' and '$datetime_final') group by numero_op");
								while($linea = mysqli_fetch_array($ordenes)) {
									$consumos = mysqli_query($conexion, "SELECT id_consumo, ordenes.numero_op as op, nombre_trabajo, descripcion, fecha_consumo, observacion, SUM(cantidad) as cantidad FROM consumo_planchas INNER JOIN ordenes ON ordenes.numero_op = consumo_planchas.numero_op WHERE ordenes.numero_op = '$linea[numero_op]' AND (fecha_consumo BETWEEN '$dia' AND '$datetime_final') ORDER BY id_consumo DESC");
									$cant_total = 0;
									while($recorrer = mysqli_fetch_array($consumos)){
										$recor[$a] = $linea["numero_op"];$a++;
										$recor[$a] = $recorrer["nombre_trabajo"];$a++;
										$recor[$a] = $recorrer["cantidad"];$a++;
							?>

							<tr class="General">
								<td colspan="3" align="center"><?php echo $linea["numero_op"]; ?></td>
								<td><?php echo $recorrer["nombre_trabajo"]; ?></td>
								<td colspan="3"><?php echo $recorrer["descripcion"]; ?></td>
								<td><?php echo $recorrer["cantidad"]; ?></td>
							</tr>

							<?php 
									$registros = mysqli_query($conexion, "SELECT id_consumo,nombre,ordenes.numero_op,nombre_trabajo,descripcion,fecha_consumo,observacion,cantidad from consumo_planchas inner join usuarios on usuarios.id_usuario = consumo_planchas.id_usuario inner join ordenes on ordenes.numero_op = consumo_planchas.numero_op where ordenes.numero_op = '$recorrer[op]' and (fecha_consumo between '$dia' and '$datetime_final')  order by id_consumo desc");
									while($datos = mysqli_fetch_array($registros)) {
										$fecha_consumo = $datos['fecha_consumo'];
										$mes_consumo 			= date("m",strtotime($fecha_consumo));
										$dia_consumo 			= date("d",strtotime($fecha_consumo));
										$year_consumo 			= date("Y",strtotime($fecha_consumo));
										$hora_consumo 			= date("H:i:s",strtotime($fecha_consumo));
							?>

							<tr class="Detalles">
								<td><?php echo $datos['id_consumo']; ?></td>
								<td><?php echo $datos['nombre']; ?></td>
								<td colspan="3"><?php echo $datos['descripcion']; ?></td>
								<td><?php echo $datos['fecha_consumo']; ?></td>
								<td><?php echo $datos['observacion']; ?></td>
								<td><?php echo $datos['cantidad']; ?></td>
							</tr>

						<?php
									}
									}
								}
							?>
						</tbody>
					</table>
					<button onclick="exportTableToExcel('acordeon-copy')">Exportar excel</button>
				</div>
			</div>
		</div>

		<script src="assets/js/peticion2.js"></script>

		<script>
			function exportToExcel() {
				var data = [];
				// Extraer los datos de la primera tabla
				var table1 = document.getElementById("descripcion_articulos");
				for (var i = 0; i < table1.rows.length; i++) {
					data.push([]);
					for (var j = 0; j < table1.rows[i].cells.length; j++) {
						data[i].push(table1.rows[i].cells[j].innerHTML);
					}
				}
				// Extraer los datos de la segunda tabla
				var table2 = document.getElementById("meses_1");
				for (var i = 0; i < table2.rows.length; i++) {
					data.push([]);
					for (var j = 0; j < table2.rows[i].cells.length; j++) {
						data[i].push(table2.rows[i].cells[j].innerHTML);
					}
				}
				// Extraer los datos de la segunda tabla
				var table3 = document.getElementById("meses_2");
				for (var i = 0; i < table3.rows.length; i++) {
					data.push([]);
					for (var j = 0; j < table3.rows[i].cells.length; j++) {
						data[i].push(table3.rows[i].cells[j].innerHTML);
					}
				}
				// Extraer los datos de la segunda tabla
				var table4 = document.getElementById("meses_3");
				for (var i = 0; i < table4.rows.length; i++) {
					data.push([]);
					for (var j = 0; j < table4.rows[i].cells.length; j++) {
						data[i].push(table4.rows[i].cells[j].innerHTML);
					}
				}

				// Crear un nuevo libro de trabajo en blanco
				var wb = XLSX.utils.book_new();

				// Agregar los datos a la hoja de cálculo
				var ws = XLSX.utils.aoa_to_sheet(data);
				XLSX.utils.book_append_sheet(wb, ws, "Datos");

				// Descargar el archivo Excel
				XLSX.writeFile(wb, "datos.xlsx");
			}

			function exportTableToExcel(tableID, filename = ''){
				var downloadLink;
				var dataType = 'application/vnd.ms-excel';
				var tableSelect = document.getElementById(tableID);
				var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
				
				filename = filename?filename+'.xlsx':'excel_data.xls';
				
				downloadLink = document.createElement("a");
				
				document.body.appendChild(downloadLink);
				
				if(navigator.msSaveOrOpenBlob){
					var blob = new Blob(['\ufeff', tableHTML], {
						type: dataType
					});
					navigator.msSaveOrOpenBlob( blob, filename);
				}else{
					downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
					downloadLink.download = filename;
					downloadLink.click();
				}
			}

			$(".General").click(function() {
				$(this).nextUntil(".General").toggle();
			});

			function cleanScreen() {
				for(var e = 1;e <= 3; e++){
					var cuerpoTabla = document.getElementById("cuerpo_meses_"+e);
					cuerpoTabla.innerHTML = ''; 
				}
			}

			function funcion_general(fecha,cuerpo,consumo) {
				$('#consumo_'+consumo).html('consumo '+fecha);
				$.ajax({
					url: 'extensiones/meses_consumos.php',
					type: 'POST',
					dataType: 'json',
					data: { mes: fecha },
					success: function(datos) {
						var cuerpoTabla = document.getElementById(cuerpo);
						cuerpoTabla.innerHTML = ''; // limpiar cuerpo de tabla antes de agregar los nuevos datos
						for (var i = 0; i < datos.length; i++) {
							var fila = cuerpoTabla.insertRow();
							var celdaConsumo = fila.insertCell(0);
							celdaConsumo.innerHTML = datos[i].consumo;
						}
					},
					error: function(xhr, status, error) {
						console.log('Error al obtener los datos'+error);
					}
				});
			}
			function obtenerMes1(mes) {
				var cuerpo = "cuerpo_meses_1";
				funcion_general(mes,cuerpo,1)
			}

			function obtenerMes2(mes) {
				var cuerpo = "cuerpo_meses_2";
				funcion_general(mes,cuerpo,2)
			}

			function obtenerMes3(mes) {
				var cuerpo = "cuerpo_meses_3";
				funcion_general(mes,cuerpo,3)
			}

			$(document).ready(function(){
				$(".contenedor_mayor").toggleClass("collapse");
				$(".hamburger").click(function(){
						$(".contenedor_mayor").toggleClass("collapse");
				});
			});
		</script>

	</body>
</html>








<?php
	include_once 'conexion/conexion.php';
	session_start();
	if(!$_SESSION){
		header('location: index.php');
	}
	if($_SESSION['Corte_consumos'] != 'si'){
		header('location: index.php');
	}
	$nombre_usuario = $_SESSION['nombre'];
	/* CONSULTAS */
	$ubicaciones = mysqli_query($conexion,"SELECT * FROM ubicaciones order by estado desc");
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<link rel="stylesheet" href="assets/css/main.css">
		<link rel="stylesheet" href="assets/css/mostrar-tabla.css">

		<!-- JS -->
		<script src="assets/js/jQuery/jquery-3.0.0.min.js"></script>
		<link rel="shortcut icon" href="assets/imagenes/LOGO.png" />
		<title>Corte - NOMOS</title>
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
				<?php if($_SESSION['informe_consumo']  == 'si'){ ?><li><a href="consumo.php" 					>Informe de consumo</a>			</li><?php } ?>
				<?php if($_SESSION['Inventario'] 			 == 'si'){ ?><li><a href="inventario.php" 				>inventario</a>							</li><?php } ?>
				<?php if($_SESSION['Importar_ordenes'] == 'si'){ ?><li><a href="importar_ordenes.php" 	>Importar ordenes</a>				</li><?php } ?>
				<?php if($_SESSION['Usuarios'] 				 == 'si'){ ?><li><a href="usuarios.php" 					>Usuarios</a>								</li><?php } ?>
				<?php if($_SESSION['Corte_consumos'] 	 == 'si'){ ?><li><a href="corte_consumos.php"  class="active"	>Corte de mes</a>						</li><?php } ?>
			</ul>
    </div>

    <div class="main_container pag_cortemeses">
			<div class="tabla_cortes">
					<header class="header">Listado de fechas registrados</header>
					<h4>Podra cerrar el acceso a cambiar la fecha de los consumos bloqueando los meses correspondientes</h4>
					<div id="selector_meses">
						<button id="btn_selectorMes" onclick="selectorMes()">Agregar meses</button>
						<ul id="submenu">
							<?php
								$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
								$mesActual = date("m")-1;
								$yearActual = date("Y-m");

								for ($i = 0; $i <= 12; $i++) {
									$corte = $meses[($mesActual + $i) % 12];
									$year = strtotime ( $yearActual );
									$year = date ( 'Y' , $year );
									$intervalos = mysqli_query($conexion,"SELECT fecha_corte FROM corte_consumos where fecha_corte = '$yearActual' order by fecha_corte asc");
									if(mysqli_num_rows($intervalos) > 0){
									}
									else{
										echo '<li id="'.$yearActual.'">'.$corte.' '.$year.'</li>';
									}
									$yearActual = strtotime ( '+1 month' , strtotime ( $yearActual ) ) ;
									$yearActual = date ( 'Y-m' , $yearActual );
								}
							?>
						</ul>
					</div>
					<table cellspacing = '5' border='0'>
						<thead>
							<tr>
								<th>Fecha</th>
								<th>Estado</th>
							</tr>
						</thead>

						<tbody id="lista_intervalos">
						</tbody>
					</table>
			</div>


			<div class="tabla_ubicaciones">
				<header class="header">AGREGAR O MODIFICAR UBICACIONES</header>
				<div class="ubicaciones">
					<table class="tabla-2"  cellspacing="10">
						<thead>
							<tr>
								<th class="lista-nombre"> Ubicacion   </th>
								<th class="lista-nombre"> Estado      </th>
								<th class="lista-nombre"> Acciones    </th>
							</tr>
						</thead>

						<tbody>
							<?php
								while ($fila = mysqli_fetch_array($ubicaciones)) {
									$id_ubicacion      				= $fila[0];
									$ubicacion      				= $fila[1];
									$Estado      				= $fila[2];
									
							?>

							<tr class="habilitado">
								<td class="desc-tabla"><?php echo $ubicacion;	?></td>
								<td class="desc-tabla"><div class="checkbox"><input class="tgl tgl-flip" id="ubicacion_<?php echo $id_ubicacion; ?>" value="<?php echo $Estado; ?>" type="checkbox" <?php if($Estado == "activo"){ ?>checked<?php } ?> onclick="cmb_ubicacion(this.id,value)"/><label class="tgl-btn" data-tg-off="inactivo" data-tg-on="activo" for="ubicacion_<?php echo $id_ubicacion; ?>"></label></div></td>
								<td><button id="editar_<?php echo $id_ubicacion; ?>" onclick="editar_ubi(this.id,value)" value="<?php echo $ubicacion; ?>"> Editar </button>
							</tr>

							<?php
							}
							?>

						</tbody>
					</table><br><button class="agregar"><a href="#modal-ubicacion" id="show-modal">Agregar ubicacion</a></button>
				</div>
			</div>

			<aside id="modal-ubicacion" class="modal-ubicacion">
				<div class="contenido-agregar">
					<button class="cerrar"><a href="#" class="close-modal">Cerrar</a></button>
					<h1>Agregar ubicacion</h1>
					<fieldset><form action="#" method="post">
						<p>Ingrese la nueva ubicacion<input type="text" name="nueva_ubicacion" placeholder="Ingrese nueva ubicacion"></p>
						<input type="submit" value="Añadir" name="AGREGAR_UBICACION"><input type="hidden" value="<?php echo $id_ubicacion; ?>" name="Numero_ubicacion">
						</form></fieldset>
				</div>
			</aside>

			<div class="modificar_ubi">
				<div class="header">Editar<br></div>
				<fieldset>
				<form action="#" method="post" enctype="multipart/form-data">
					<p>ubicacion      </p>
					<input type="hidden" name="recep_id">
					<input name="ubicacion_ingreso" type="text" placeholder="Ingrese nuevo nombre"  required>
					<input type="submit" name="actualizar_ubicacion" value="ACTUALIZAR DATOS" class="boton-enviar">		
				</form>
				</fieldset>
			</div>


				<?php
					if(isset($_POST['AGREGAR_UBICACION'])){
						$Numero_ubicacion = $_POST['Numero_ubicacion'];
						$Numero_ubicacion = $Numero_ubicacion + 1;
						$nueva_ubicacion = $_POST['nueva_ubicacion'];

						$ingresar_Datos = mysqli_query($conexion,"INSERT into ubicaciones (id_ubicacion,ubicacion) values ('$Numero_ubicacion','$nueva_ubicacion')");
						$ingresar_ubicacion = mysqli_query($conexion,"ALTER TABLE `tabla_inventario` ADD `$Numero_ubicacion` INT(11) NOT NULL default 0");

						echo "<script>
										alert ('ubicacion añadida con exito');
										window.location.href ='corte_consumos.php';
									</script> ";
					}
				?>

		<script>
			$(obtener_intervalos());

			function obtener_intervalos() {
				$.ajax({
					url : 'extensiones/cmb_corte.php',
					type : 'POST',
					dataType : 'html',
				})

				.done(function(resultado){
					$("#lista_intervalos").html(resultado);
				})
			}
			function cmb_estado(id,valor) {
				var id_corte = id.replace("Mes_", '');
				if(valor == "disponible"){
					valor = "inactivo";
					$("#"+id).val('bloqueado');
				}
				else{
					valor = "disponible";
					$("#"+id).val('disponible');
				}
				$.ajax({
					url : 'extensiones/cmb_corte.php',
					type : 'POST',
					dataType : 'html',
					data : { id_corte: id_corte, valor: valor },
				})
			}

			$("#submenu li").click(function(e) {
				e.preventDefault();
				var selectedMonth = $(this).text();
				var selectedMonth = selectedMonth.replace(" ", '_');
				var id_corte = e.target.id;
				$("#"+id_corte).remove();
				$.ajax({
					url: "extensiones/cmb_corte.php",
					method: "post",
					data: {intervalo: selectedMonth, id_corte: id_corte}
				})
				.done(function(response) {
					$(obtener_intervalos());
				});
			});


			/*funcion para activar/inactivar ubicacion */
			function cmb_ubicacion(id,valor) {
				var id_ubicacion = id.replace("ubicacion_", '');

				console.log(valor);
				if(valor == "activo"){
					valor = "inactivo";
					$("#"+id).val('inactivo');
				}
				else{
					valor = "activo";
					$("#"+id).val('activo');
				}
				const data = {id_ubicacion, valor};
				$.ajax({ url : 'extensiones/edicion_ubicacion.php', type : 'POST', dataType : 'html', data })
			}

			function editar_ubi(id,ubicacion) {
				var id_ubi = id.replace("editar_", '');
				$("input[name=ubicacion_ingreso").val(ubicacion);
				$("input[name=recep_id").val(id_ubi);
				$(".modificar_ubi").addClass("show_modiUbi");
				console.log(id_ubi+''+ubicacion);
			}

			$("input[name=actualizar_ubicacion]").click(function(e) {
				e.preventDefault();
				var actu_ubi = $("input[name=ubicacion_ingreso").val();
				var id = $("input[name=recep_id").val();
				const data = {actu_ubi,id}
				$.ajax({
					url: "extensiones/edicion_ubicacion.php",
					method: "post",
					data
				})
				.done(function(response) {
					$(".modificar_ubi").removeClass("show_modiUbi");
				});
			});

			/* PARA INGRESO DE CORTE DE MES */

			function selectorMes() {
				$("#submenu").toggleClass("show_submenu");
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
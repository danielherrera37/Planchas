<?php
	include_once 'conexion/conexion.php';
	session_start();
	if($_SESSION['Consumo_planchas'] != 'si'){
		header('location: consumo.php');
	}
	$id_usuario = $_SESSION['id_usuario'];
	$nombre_usuario = $_SESSION['nombre'];

	// consultas
	$fecha_maxima = date('Y-m-d');
	$lugares = mysqli_query($conexion,"SELECT * FROM ubicaciones where estado = 'activo'");
	$fecha_activa = date("Y-m",strtotime($fecha_hoy."- 1 month,"));
	$derivado = mysqli_query($conexion, "SELECT fecha_corte FROM corte_consumos where fecha_corte like '%$fecha_activa%' and estado = 'disponible'");
	if(mysqli_num_rows($derivado) > 0) {
		$fecha_minima = date("Y-m-01",strtotime($fecha_hoy."- 1 month,"));
	}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<!-- CSS -->
		<link rel="stylesheet" href="assets/css/main.css">
		
		<!-- JS -->
		<script src="assets/js/jQuery/jquery-3.0.0.min.js"></script>
		<script src="assets/js/peticion2.js"></script>
		<link rel="shortcut icon" href="assets/imagenes/LOGO.png" />
		
		<title>Principal - NOMOS</title>

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
					<div class="logo"><?php echo $fecha_hoy; ?></div>
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
					<?php if($_SESSION['Consumo_planchas'] == 'si'){ ?><li><a href="principal.php" class="active">Inicio</a>									</li><?php } ?>
					<?php if($_SESSION['informe_consumo']  == 'si'){ ?><li><a href="consumo.php" 								>Informe de consumo</a>			</li><?php } ?>
					<?php if($_SESSION['Inventario'] 			 == 'si'){ ?><li><a href="inventario.php" 						>inventario</a>							</li><?php } ?>
					<?php if($_SESSION['Importar_ordenes'] == 'si'){ ?><li><a href="importar_ordenes.php" 			>Importar ordenes</a>				</li><?php } ?>
					<?php if($_SESSION['Usuarios'] 				 == 'si'){ ?><li><a href="usuarios.php" 							>Usuarios</a>								</li><?php } ?>
					<?php if($_SESSION['Corte_consumos'] 	 == 'si'){ ?><li><a href="corte_consumos.php"  				>Corte de mes</a>						</li><?php } ?>
				</ul>
			</div>

			<div class="main_container">
				<div class="msgConsumoHecho">
					Se ha registrado correctamente
				</div>

				<div>
					<form id="formConsumo" method="post">
						<header>Formulario</header>
						<div class="formulario">
							<div class="titular">Para seleccionar un articulo debe seleccionarlo de la tabla en la derecha</div>
							<div class="op">
								<input type="hidden" name="id_articulo" id="articulo">
								<input type="hidden" name="id_usuario"  id="id_usuario" value="<?php echo $id_usuario; ?>">
								Busque una orden *: 
								<input type="text" name="busqueda_OP" id="busqueda_OP" placeholder="Buscar..." autocomplete="off" required><br>
								Nombre del trabajo: <br>
								<textarea type="text" name="nombre_trabajo" id="nombre_trabajo" placeholder="Nombre del trabajo" readonly required class="readonly"></textarea>
							</div>

							<div class="ubicacion">
								Ubicacion *:
								<select name="ubicacion" required class="btn-llenar">
									<option value="" disabled selected>Seleccione una ubicacion</option>
									<?php 
										while ($consulta = mysqli_fetch_array($lugares)){
											echo '<option value="'.$consulta['id_ubicacion'].'">'.$consulta['ubicacion'].'</option>';
										}
									?>
								</select>
								<?php
									echo "<p>Puede seleccionar la fecha de consumo con fecha minima el mes pasado si esta habilitado</p><input type='date' name='fecha_consumo' value='$fecha_mes' min='$fecha_minima' max='$fecha_maxima' style='margin: 10px 0;'>";
								?>
							</div>
							
							<div class="medida"			>Medida: 					<input 		type="text" 			name="medida" readonly required class="readonly" placeholder="Numero articulo" ></div>
							<div class="descripcion">Descripcion: <br><textarea name="descripcionArticulo" readonly required class="readonly btn-descripcion" placeholder="Descripcion"></textarea></div>
							<div class="cantidad"		>Cantidad *: 			<input 		type="number" 		name="cantidad" id="cantidad" required class="btn-llenar"></div>
							<div class="inventario"	>En Inventario: 	<input 		type="text" 			name="disponibles" id="disponibles" readonly class="readonly"></div>
							<div class="observacion">Observacion: <br><textarea name="observacion" class="btn-llenar" placeholder="Ingrese su observacion"></textarea></div>
							<div class="acciones"><input type="reset" value="Limpiar"> || <input type="submit" value="Guardar" id="guardarConsumo" class="guardar"></div>
						</div>
					</form>
				</div>

				<div id="tabla_ordenes" class="tabla_ordenes">
					<!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA CONSUMOS -->
				</div>

				<div class="planchas">
					<header>Articulos</header>
					<div class="contenedor_tablas" id="tabla_articulos">
						<!-- AQUI SE DESPLEGARA NUESTRA TABLA DE CONSULTA INVENTARIO -->
					</div>
				</div>

				<div class="Confirmacion">
					<aside class="header"><b>CONFIRMACION</b></aside><br>Usted va a hacer registro de consumo de: <br><br>
					<table>
						<input type="hidden"  	name="confirmaubi" 	value="">
						<input type="hidden" 	name="confirmaartic" 	value="">
						<tr><td>Orden:			</td><td><input type="text" 	name="confirmaorden" 	value="" readonly></td></tr>
						<tr><td>Cantidad:		</td><td><input type="text" 	name="confirmacant" 	value="" readonly></td></tr>
						<tr><td>Descripcion:</td><td><input type="text" 	name="confirmadesc" 	value="" readonly></td></tr>
						<tr><td>Observacion:</td><td><input type="text" 	name="confirmaobser" 	value="" minlength="5" required ></td></tr>
						<tr><td style="text-align:center"><input type="submit" value="Confirmar" id="confirmar_consumo" class="confirmar"></td><td style="text-align:right"><a href="principal.php">Cancelar</a></td></tr>
					</table>
				</div>


			</div>
		</div>

		<script src="assets/js/principal.js"></script>
	</body>
</html>
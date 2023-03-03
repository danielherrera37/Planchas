<?php
	include_once 'conexion/conexion.php';
	session_start();
	if(!$_SESSION){
		header('location: usuarios.php');
	}
	if($_SESSION['Importar_ordenes'] != 'si'){
		header('location: usuarios.php');
	}
	$nombre_usuario = $_SESSION['nombre'];
	$result = "SELECT COUNT(*) as total_ordenes FROM ordenes";
	$NUM_ITEMS_BY_PAGE = 15;
	$resultado = $conexion->query($result);
	$row = $resultado -> fetch_assoc();
	$num_total_rows = $row['total_ordenes'];
?>
<!DOCTYPE html>
<html lang="es">
	<head>
	<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> -->
		<link rel="stylesheet" href="assets/css/main.css">
		<link rel="stylesheet" type="text/css" href="assets/dataTables/css/dataTables.dataTables.css"/>
		<link rel="stylesheet" href="assets/dataTables/css/jquery.dataTables.css">
		<!-- js -->
		<script src="assets/js/jQuery/jquery-3.0.0.min.js"></script>
		<script src="assets/dataTables/js/jquery.dataTables.js"></script>
		<link rel="shortcut icon" href="assets/imagenes/LOGO.png" />
		<title>Ordenes - NOMOS</title>
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
					<?php if($_SESSION['Importar_ordenes'] == 'si'){ ?><li><a href="importar_ordenes.php" 	class="active">Importar ordenes</a>				</li><?php } ?>
					<?php if($_SESSION['Usuarios'] 				 == 'si'){ ?><li><a href="usuarios.php" 					>Usuarios</a>								</li><?php } ?>
					<?php if($_SESSION['Corte_consumos'] 	 == 'si'){ ?><li><a href="corte_consumos.php"  	>Corte de mes</a>						</li><?php } ?>
        </ul>
    </div>

    <div class="main_container pag_ordenes">
    	<div class="importar">
				<header>IMPORTAR ORDENES DESDE ARCHIVO EXCEL</header>
				<aside class="importacion">
					<form id='formImport' method="POST" enctype="multipart/form-data">
						<h1>Para importar ordenes con excel</h1>
							<input  type="file" name="dataCliente" id="file-input" accept=".xlsx" required>
							<input type="submit" id="btn-enviar" name="submit" class="btn-enviar" value="Subir Excel"/><br><br>
					</form>
					<div class="contenedor_manejar_ordenes" style="padding: 10px;border-top: solid 4px #24bf77;background-color: #9da4a1;">
					<h1 style="font-size: 16px; box-shadow: rgb(50 50 93) 0px 2px 5px -1px, rgb(0 0 0 / 79%) 0px 1px 3px -1px;">--- &gt; Como importar correctamente un archivo excel para subir ordenes de produccion &lt; ---</h1>
						<aside class="importacion">
							<ol>
								<li style="line-height: 25px; font-size: 15px; text-align: justify;">El archivo excel debe contener 6 columnas las cuales son <b>Numero de OP,Nombre del trabajo</b>,<b>la fecha ,</b>el <b>codigo</b>, <b> nombre del centro </b> y la <b>cantidad planeada</b><b></b> y se debe dejar una fila de encabezado de la tabla</li><br>
							</ol>
							<br><img src="assets/imagenes/formato.jpg" alt="imagen de ejemplo para subir excel" width="100%">
						</aside>
					</div>
				</aside>
				<aside class="agregar_ordenes">
					<header>Agregar OP</header>
					<fieldset><form action="#" method="post">
						<p>Ingrese el numero de la orden <input type="text" name="nuevo_numero" placeholder="Ingrese el numero de la OP"></p>
						<p>Ingrese el nombre del trabajo <input type="text" name="nueva_orden" placeholder="Ingrese la orden de produccion"></p>
						<p>Ingrese la cantidad planeada de la orden <input type="text" name="cantidad_planeada" placeholder="Ingrese la cantidad planeada"></p>
						<p>Ingrese el codigo CP <input type="text" name="codigo_cp" placeholder="Ingrese el codigo de la maquina"></p>
						<p>Ingrese el nombre del centro <input type="text" name="nombre_centro" placeholder="Ingrese el nombre del centro perteneciente"></p>
						<input type="submit" value="Añadir" name="AGREGAR_OP">
					</form></fieldset>
				</aside>
			</div>
			<div class="ordenes">
				<header>Paginación de ordenes</header>
				<h1>Puede buscar por numero de OP o puede buscar por fecha, maximo apareceran 20 datos</h1>
				<input type="text" id="busqueda_ordenes" placeholder="Busqueda de ordenes">
				<div class="paginacion_ordenes" id="paginacion_ordenes">
					<!-- peticion 2 ordenes -->
				</div>
			</div>
		</div>

		<div id="content_import" class="loader_import"><a href="#" aria-busy="true" onclick="event.preventDefault()" id="cmb_import">Importando datos del archivo, Por favor espere</a></div>
        		

		<?php
		if(isset($_POST['submit'])){
			$file = $_FILES['file'];
			$fileName = $_FILES['file']['name'];
			$fileTmpName = $_FILES['file']['tmp_name'];
			$fileSize = $_FILES['file']['size'];
			$fileError = $_FILES['file']['error'];
			$fileType = $_FILES['file']['type'];
		
			$fileExt = explode('.', $fileName);
			$fileActualExt = strtolower(end($fileExt));
		
			$allowed = array('xlsx', 'xls');
		
			if(in_array($fileActualExt, $allowed)){
				if($fileError === 0){
					if($fileSize < 1000000){
						$fileNameNew = uniqid('', true).".".$fileActualExt;
						$fileDestination = 'recibir_excel/'.$fileNameNew;
						move_uploaded_file($fileTmpName, $fileDestination);
						echo "Success";
					} else {
						echo "File size is too big";
					}
				} else {
					echo "There was an error uploading the file";
				}
			} else {
				echo "You cannot upload files of this type";
			}
		}
			if(isset($_POST['AGREGAR_OP'])){
				$nuevo_numero = $_POST['nuevo_numero'];
				$nueva_orden = $_POST['nueva_orden'];
				$cantidad = $_POST['cantidad_planeada'];
				$cp = $_POST['codigo_cp'];
				$nombre_centro = $_POST['nombre_centro'];

				$buscar_orden = mysqli_query($conexion,"SELECT * from ordenes where numero_op = $nuevo_numero or nombre_trabajo = '$nueva_orden'");
				if(mysqli_num_rows($buscar_orden) > 0){
					while ($lista = mysqli_fetch_array($buscar_orden)){
						$numero = $lista[0];
						$orden = $lista[1];
						$cantidad_planeada = $lista[3];
					}
					if(($cantidad > $cantidad_planeada) or ($nueva_orden != $orden)){
						$actualizar_orden = mysqli_query($conexion,"UPDATE ordenes set orden = '$nueva_orden',Cantidad_planeada = $cantidad,cp = '$cp',nombre_centro = '$nombre_centro' where numero_op = $nuevo_numero");
						if ($actualizar_orden){
							echo "<script>
											alert ('Se actualizo la OP con la informacion dada debido a que ya se encontraba una orden registrada');
											window.location.href ='importar_ordenes.php';
										</script> ";
						}
						else{
							echo "<script>
											alert ('No se pudo actualizar');
											window.location.href ='importar_ordenes.php';
										</script>";
						}
					}
				}
				else{
					$ingresar_Datos = mysqli_query($conexion,"INSERT into ordenes values ('$nuevo_numero','$nueva_orden','$fecha_hoy','$cp','$nombre_centro','$cantidad')");

					if ($ingresar_Datos){
						echo "<script>
										alert ('OP añadido con exito');
										window.location.href ='importar_ordenes.php';
									</script> ";
					}
					else{
						echo "<script>
										alert ('no se pudo guardar');
										window.location.href ='importar_ordenes.php';
									</script>";
					}
				}
			}
		?>
		<script>
			/* PARA ORDENES */
			$(obtener_op());

			function obtener_op(op)
			{
				$.ajax({
					url : 'extensiones/consulta_ordenes.php',
					type : 'POST',
					dataType : 'html',
					data : { op: op },
					})

				.done(function(resultado){
					$("#paginacion_ordenes").html(resultado);
				})
			}

			$(document).on('keyup', '#busqueda_ordenes', function()
			{
				var valor=$(this).val();
				if (valor!="")
				{
					obtener_op(valor);
				}
				else
					{
						obtener_op();
					}
			});


			$(document).on('click', '#btn-enviar', function() {
				$(".loader_import").addClass("open_loader_import");
			});
			$(document).ready(function(){
				$(".contenedor_mayor").toggleClass("collapse");
				$(".hamburger").click(function(){
						$(".contenedor_mayor").toggleClass("collapse");
				});

			});

			$('#formImport').on('submit', importarExcel);

			function importarExcel(event){
				// Evitar que el formulario se procese normalmente
				event.preventDefault();
				
				

				var formData = new FormData($(this)[0]);

				const btnfile = $('#btn-enviar');
				
				$(btnfile).prop('disabled', true);

						$.ajax({
							url : 'recibir_excel/recibir.php', 
							type : 'POST', 
							data: formData,
							async: false,
							success: function (data) {

								if(data == 1){
									$("#cmb_import").text("Completado");
								}

								setTimeout(function() {
									$("#file-input").val("");	
									$(btnfile).prop('disabled', false);
									$(".loader_import").removeClass("open_loader_import");
								}, 8000);
							},
							cache: false,
							contentType: false,
							processData: false
						});
						return false;
			};
		</script>
	</body>
</html>
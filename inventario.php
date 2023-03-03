<?php
  include_once 'conexion/conexion.php';
  session_start();
  if(!$_SESSION){
		header('location: importar_ordenes.php');
	}
  if($_SESSION['Inventario'] != 'si'){
    header('location: importar_ordenes.php');
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
  <link rel="stylesheet" href="assets/css/mostrar-tabla.css">
  <link rel="stylesheet" type="text/css" href="assets/dataTables/css/dataTables.dataTables.css"/>
  <link rel="stylesheet" href="assets/dataTables/css/jquery.dataTables.css">

  <!-- js -->
  <script src="assets/js/jQuery/jquery-3.0.0.min.js"></script>
  <script src="assets/dataTables/js/jquery.dataTables.js"></script>
  <link rel="shortcut icon" href="assets/imagenes/LOGO.png" />
  <title>INVENTARIO</title>

  <?php
    $fecha_hoy = date("Y-m-d H:i:s");
    $id_usuario = $_SESSION['id_usuario'];
    $nombre_usuario = $_SESSION['nombre'];

    /* CONSULTAS */
    $articulos = mysqli_query($conexion,"SELECT * from articulos order by estado");
    $selector = mysqli_query($conexion,"SELECT * FROM ubicaciones");
    $estados['activo'] = 'desactivar';$estados['inactivo'] = 'activar';
    $i = 1;

    /* OBTENER DATOS DE URL */
    if(isset($_GET['inactivo'])){
      $id_articulo = $_GET['inactivo'];
      $cambiar = mysqli_query($conexion,"UPDATE articulos set estado = 'habilitado' where id_articulo = $id_articulo");
      if($cambiar){
        echo "<script>
        window.location.href ='inventario.php';
      </script> ";
      }
    }
    if(isset($_GET['habilitado'])){
      $id_articulo = $_GET['habilitado'];
      $cambiar = mysqli_query($conexion,"UPDATE articulos set estado = 'inactivo' where Numero_Articulo = $id_articulo");
      if($cambiar){
        echo "<script>
        window.location.href ='inventario.php';
      </script> ";
      }
    }
  ?>
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
        <?php if($_SESSION['Inventario'] 			 == 'si'){ ?><li><a href="inventario.php" 			class="active"	>inventario</a>							</li><?php } ?>
        <?php if($_SESSION['Importar_ordenes'] == 'si'){ ?><li><a href="importar_ordenes.php" 	>Importar ordenes</a>				</li><?php } ?>
        <?php if($_SESSION['Usuarios'] 				 == 'si'){ ?><li><a href="usuarios.php" 					>Usuarios</a>								</li><?php } ?>
        <?php if($_SESSION['Corte_consumos'] 	 == 'si'){ ?><li><a href="corte_consumos.php"  	>Corte de mes</a>						</li><?php } ?>
      </ul>
    </div>

    <div class="main_container pag_inventario">
      <div class="tabla_inventario">
      <header>INGRESO DE INVENTARIO</header>
        <?php echo $fecha; ?>
        <div class="contenedor_tabla">
        <table border="1" class="table-habilitar">
          <thead>
            <tr>
              <th>Agregar inventario</th>
              <th>Articulo</th>
              <th>Descripcion</th>
              <th>Medida</th>
              <th>Cantidad actual</th>
              <th>Estado</th>
            </tr>
          </thead>

          <tbody>
            <?php
              $articulos = mysqli_query($conexion,"SELECT * from articulos order by estado");
              if(mysqli_num_rows($articulos) >= 1){
                while ($filas=mysqli_fetch_array($articulos)) {
                  $id      	= $filas[0];
                  $codigo      	= $filas[3];
                  $descripcion   = $filas[1];
                  $medida   = $filas[2];
                  $estado      	= $filas[4];
                  $ultimo = mysqli_num_rows($articulos);
            ?>
            <tr class="<?php echo $estado; ?>">
              <td>
                <a href="#modal-ubicacion" id="show-modal"><input type="button" onclick="agregar(this.id)" id="<?php echo $id; ?>" data-id="<?php echo $id; ?>" value="Agregar"></a>
                <input type="hidden" name="ultimo_articulo" value="<?php echo $id; ?>" class="ultimo_articulo" id="<?php echo $ultimo; ?>">    
              </td>
              <td><?php echo $codigo; ?></td>
              <td nowrap="2" align="left">
                <?php echo $descripcion ?>
                <input type='hidden' id='descripcion<?php echo $id; ?>' value='<?php echo $descripcion; ?>'>
              </td>
              <td nowrap="2"><?php echo $medida ?></td>
              <?php
              $ubicacion = "";
                $ubicaciones = mysqli_query($conexion,"SELECT * FROM ubicaciones where estado = 'activo'");
                  while ($linea = mysqli_fetch_array($ubicaciones)){
                    $ubicacion.= "`".$linea["id_ubicacion"]."`+";
                  }
                  $ubicacion = substr($ubicacion, 0, -1);
                  $suma_ubicacion = mysqli_query($conexion,"SELECT sum($ubicacion) as total FROM tabla_inventario where id_articulo = '$id'");
                    while ($consulta = mysqli_fetch_array($suma_ubicacion)){
                      $total = $consulta["total"];
                    echo "<td> $total <input type='hidden' id='cantidad$id' value='$total'></td>";
                    }
              ?>
              <td nowrap="2">
                <!-- <img src="assets/imagenes/<?php echo $estado; ?>.png" alt="" width="20px" style="margin-bottom: -5px;"> -->
                <a href='inventario.php?<?php echo $estado."=".$id; ?>'><button><?php echo $estados[$estado]; ?></button></a>
              </td>
            </tr>
            <?php
              $i++;
                }
              }
              else{
                echo "
                <tr>
                  <td colspan='5'>
                    <input type='hidden' name='ultimo_articulo' value='1' class='ultimo_articulo' id='0'>    
                    Aun no hay articulos
                  </td>
                </tr>";
              }
            ?>
          </tbody>
        </table>
        </div>
        <a href="#modal_nuevo_articulo" id="show-modal"><input type="submit" value="Incluir un nuevo articulo" name="subir_nuevo"></a>
      </div>  

        <div id="modal_nuevo_articulo" class="modal-ubicacion">
          <div class="contenido-agregar">
            <a href="#" class="close-modal"><button class="cerrar">Cerrar</button></a>
            <header>Agregar un nuevo articulo</header>
            <fieldset>
            <form action="" method="post">
              Ubicacion de ingreso:        							
              <select name="new_ubicacion" required class="btn-llenar">
                <option value="" disabled selected>Seleccione una ubicacion</option>
                <?php 
                  $ordenes = mysqli_query($conexion,"SELECT * FROM ubicaciones where estado = 'activo'");
                  while ($consulta = mysqli_fetch_array($ordenes)){
                    echo '<option value="'.$consulta['id_ubicacion'].'">'.$consulta['ubicacion'].'</option>';
                  }
                ?>
              </select>
              <input type="hidden" name="new_id" value="">
              <p>Ingrese el codigo del articulo: <input type="text" name="new_codigo"        autocomplete="off"        placeholder="Ingrese el numero del articulo" required></p>
              <p>Ingrese la descripcion:      <input type="text" name="new_descripcion"           placeholder="Ingrese la descripcion del articulo" required></p>
              <p>Ingrese la medida:       <input type="text" name="new_medida"            placeholder="Ingrese la medida del articulo" required></p>
              <p>Cantidad que va a ingresar:  <input type="number"  name="new_cantidad" 	    placeholder="Ingrese la cantidad del articulo" value="0" min="0" required></p>
              <p>Observacion:                 <input type="text" name="new_observacion"  placeholder="Ingreso opcional de observacion"></p>            
              <input type="submit" value="Añadir" name="NUEVO_ARTICULO">
            </form>
            </fieldset>
          </div>
        </div>




        <div id="modal-ubicacion" class="modal-ubicacion">
          <div class="contenido-agregar">
            <a href="#" class="close-modal"><button class="cerrar">Cerrar</button></a>
            <header>Agregar cantidad al inventario</header>
            <fieldset>
              <form action="#" method="post">
                <p>Descripcion del articulo
                  <input type="text" name="cant_descripcion"  placeholder="Descripcion del articulo" required readonly></p>
                <p>Ubicacion *:
                  <select name="cant_ubicacion" required class="btn-llenar">
                    <option value="" disabled selected>Seleccione una ubicacion</option>

                    <?php 
                      $ordenes = "SELECT * FROM ubicaciones where estado = 'activo'";
                      $resultado = mysqli_query($conexion,$ordenes);
                      while ($consulta = mysqli_fetch_array($resultado)){
                        echo '<option value="'.$consulta['id_ubicacion'].'">'.$consulta['ubicacion'].'</option>';
                      }
                    ?>
                  </select></p>
                  <input type="hidden" name="cant_id" value="" placeholder="id de articulo">
                <input type="hidden" name="cant_actual" value="" placeholder="cantidad de articulo">
                <p>Cantidad que va a ingresar:  <input type="number"  name="cant_cantidad" 	  placeholder="Ingrese la cantidad del articulo"	  minlength="0" requiered></p>
                <p>Observacion:                 <textarea type="text" name="cant_observacion" placeholder="Ingreso opcional de observacion"></textarea></p>            
                <input type="submit" value="Añadir" name="AGREGAR_CANTIDAD"><input type="reset" value="Limpiar">
              </form>
            </fieldset>
          </div>
        </div>




    <div class="registros_inventario">
      <header>Tabla de registros </header>
      <div class="contenedor_tabla">
				<table border="1" id="tabla_inventario">
          <thead>
					<tr>
            <th> Articulo						</th>
						<th> Medida		</th>
            <th> Descripcion				</th>
						<th> Ubicacion					</th>
            <th> Fecha </th>
            <th> Cantidad						</th>
            <th> nota								</th>
					</tr>
          </thead>
          <tbody>
          <?php
          $query="SELECT id_inventario,fecha_ingreso,medida,articulos.descripcion,codigo_articulo,ubicacion,cantidad,nota from inventario inner join ubicaciones on inventario.id_ubicacion = ubicaciones.id_ubicacion inner join articulos on articulos.id_articulo = inventario.id_articulo order by ubicacion";
          $buscarAlumnos=$conexion->query($query);
			  while($filaAlumnos = $buscarAlumnos->fetch_array()) {
        ?>
					<tr>
            <td><?php echo $filaAlumnos['codigo_articulo']; ?>								 </td>
						<td><?php echo $filaAlumnos['medida']; ?>										 </td>
						<td nowrap="2" align="left"><?php echo $filaAlumnos['descripcion']; ?>											 </td>
						<td><?php echo $filaAlumnos['ubicacion']; ?></td>
            <td nowrap="2"><?php echo $filaAlumnos['fecha_ingreso']; ?>													 </td>
						<td><?php echo $filaAlumnos['cantidad']; ?>													 </td>
            <td><?php if($filaAlumnos['nota'] == ""){ echo "-"; }else {echo $filaAlumnos['nota']; } ?>													 </td>
				</tr>
        <?php
			}
      ?>
      </tbody>
		</table>
    </div>

    </div>
    
</body>

      <!-- ACCIONES PHP DE FORMULARIOS  -->

      <!-- ACCION PARA GUARDAR UN NUEVO ARTICULO -->
      <?php
        if(isset($_POST['NUEVO_ARTICULO'])){
          if(($_POST['new_descripcion'] != "") and ($_POST['new_descripcion'] != "") and ($_POST['new_medida'] != "")){
            $new_id = $_POST['new_id'];
            $new_codigo    = $_POST['new_codigo'];
            $new_ubicacion    = $_POST['new_ubicacion'];
            $new_descripcion          = $_POST['new_descripcion'];
            $new_medida           = $_POST['new_medida'];
            $new_cantidad     = $_POST['new_cantidad'];
            $new_observacion = $_POST['new_observacion'];
            
            $nuevo_articulo = mysqli_query($conexion,"INSERT INTO articulos values ($new_id,'$new_descripcion','$new_medida','$new_codigo','habilitado')");
            $inventario = mysqli_query($conexion,"INSERT INTO inventario values (null,'$new_ubicacion','$new_id','$fecha_hoy','$new_descripcion','$new_observacion',$new_cantidad)");
            $tabla_recuento = mysqli_query($conexion,"INSERT INTO tabla_recuento (id_articulo,consumo,inventario,resultado) values ($new_id,0,$new_cantidad,$new_cantidad)");
            $tabla_consumo = mysqli_query($conexion,"INSERT INTO tabla_consumo (id_articulo) values ($new_id)");
            $tabla_ubicaciones = mysqli_query($conexion,"INSERT INTO `tabla_ubicaciones` (`id_articulo`, `$new_ubicacion`) values ('$new_id','$new_cantidad')");
            
            echo "<script>
                    window.location.href ='inventario.php';
                  </script> ";
          }
        }
      ?>

      <!-- ACCION PARA AGREGAR AL INVENTARIO -->
      <?php
        if(isset($_POST['AGREGAR_CANTIDAD'])){
          if(($_POST['cant_descripcion'] != "") and ($_POST['cant_cantidad'] != "")){
            $cant_id = $_POST['cant_id'];
            $cant_descripcion = $_POST['cant_descripcion'];
            $cant_ubicacion = $_POST['cant_ubicacion'];
            $cant_cantidad = $_POST['cant_cantidad'];
            $cant_observacion = $_POST['cant_observacion'];
            $cant_actual = $_POST['cant_actual'];
            $cant_total = $cant_actual + $cant_cantidad;

            $ingresar_cantidad = mysqli_query($conexion,"INSERT into inventario (id_ubicacion,id_articulo,fecha_ingreso,descripcion,nota,cantidad) values ($cant_ubicacion,'$cant_id','$fecha_hoy','$cant_descripcion','$cant_observacion','$cant_cantidad')");
            $tabla_recuento = mysqli_query($conexion,"UPDATE tabla_recuento set inventario =  (inventario + $cant_cantidad) where id_articulo = $cant_id");
            $tabla_ubicaciones = mysqli_query($conexion,"UPDATE tabla_inventario set `$cant_ubicacion` =  (`$cant_ubicacion` + $cant_cantidad) where id_articulo = $cant_id");

            if ($ingresar_cantidad){
              echo "<script>
                      window.location.href ='inventario.php';
                    </script> ";
            }
          }
        }
      ?>


      <script>      
          const ultimo = document.querySelector('.ultimo_articulo').id;
          if(ultimo != ''){ var suma = ultimo+1; }
          else{ suma = 1; }
          $('input[name=new_id').val(suma);

          function agregar(articulo){
            $('input[name=cant_id').val(articulo);
            var cantidad = document.getElementById("cantidad"+articulo).value;
            var descripcion = document.getElementById("descripcion"+articulo).value;
            $('input[name=cant_actual').val(cantidad);
            $('input[name=cant_descripcion').val(descripcion);
          };

          $(document).ready(function(){
						$(".contenedor_mayor").toggleClass("collapse");
						$(".hamburger").click(function(){
								$(".contenedor_mayor").toggleClass("collapse");
						});

            $('#tabla_inventario').dataTable( {
                "language": {"sProcessing":     "Procesando...","sLengthMenu":     "Mostrar _MENU_ registros","sZeroRecords":    "No se encontraron resultados","sEmptyTable":     "Ningún dato disponible en esta tabla","sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros","sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros","sInfoFiltered":   "(filtrado de un total de _MAX_ registros)","sInfoPostFix":    "","sSearch":         "Buscar registro: ","sUrl":            "","sInfoThousands":  ",","sLoadingRecords": "Cargando...","oPaginate": {"sFirst":    "Primero","sLast":     "Último","sNext":     "Siguiente","sPrevious": "Anterior" },"oAria": { "sSortAscending":  ": Activar para ordenar la columna de manera ascendente", "sSortDescending": ": Activar para ordenar la columna de manera descendente" }}
            });
        });
      </script>
  </body>
</html>
<?php
  include_once 'conexion/conexion.php';
  // error_reporting(0);
  session_start();
  if(!$_SESSION){
		header('location: corte_consumos.php');
	}
  if($_SESSION['Usuarios'] != 'si'){
    header('location: index.php');
  }
  $nombre_usuario = $_SESSION['nombre'];
  $consultar_usuarios = mysqli_query($conexion,"SELECT * from usuarios");

  $a = 0;
  while ($lista = mysqli_fetch_array($consultar_usuarios)){
    $info[$a] = $lista['id_usuario'];
    $infoN[$a] = $lista['nombre'];
    $infoC[$a] = $lista['correo'];
    $permiCON[$a] = $lista['consumo_planchas'];
    $permiUBI[$a] = $lista['ubicaciones'];
    $permiINV[$a] = $lista['inventario'];
    $permiINF[$a] = $lista['informe_consumo'];
    $permiUSU[$a] = $lista['usuarios'];
    $permiIMP[$a] = $lista['importar_ordenes'];
    $permiCOR[$a] = $lista['corte_consumos'];
    $a++;
  }
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
		<title>Usuarios - NOMOS</title>
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

      <div class="pag_usuarios">
			  <div class="total_usuarios">
          <header>
            Usuarios en el sistema
          </header>

          <div class="contenedor_tablas" style="display: block;">
            <table id="tabla_usuarios" cellspacing = '5' border='0'>
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Correo</th>
                  <th>Consumo</th>
                  <th>Ubicaciones</th>
                  <th>Inventario</th>
                  <th>Informe de consumo</th>
                  <th>Ver usuarios</th>
                  <th>Importar ordenes</th>
                  <th>Corte de mes</th>
                  <th>Editar</th>
                </tr>
              </thead>

              <tbody>
                <?php for($i = 0; $i < $a; $i++){ ?>

                <input type="hidden" id="nombre<?php echo $info[$i]; ?>" value="<?php echo $info[$i]; ?>">
                <input type="hidden" id="correo<?php echo $info[$i]; ?>" value="<?php echo $info[$i]; ?>">

                <tr>
                  <td><?php echo $infoN[$i]; ?></td>
                  <td><?php echo $infoC[$i]; ?></td>
                  <td><?php echo $permiCON[$i]; ?></td>
                  <td><?php echo $permiUBI[$i]; ?></td>
                  <td><?php echo $permiINV[$i]; ?></td>
                  <td><?php echo $permiINF[$i]; ?></td>
                  <td><?php echo $permiUSU[$i]; ?></td>
                  <td><?php echo $permiIMP[$i]; ?></td>
                  <td><?php echo $permiCOR[$i]; ?></td>
                  <td><button onclick="editar_usuario(this.id)" id="<?php echo $info[$i]; ?>">Modificar</button></td>
                </tr>

                <?php } ?>
              </tbody>
            </table>
          </div>
          <button onclick="show_agregar()">Agregar usuario</button>
        </div>

        <aside class="display_usuario" id="display_usuario">
				</aside>
      </div>
        
      <script>
        $(document).ready(function(){
          $(".contenedor_mayor").toggleClass("collapse");
          $(".hamburger").click(function(){
              $(".contenedor_mayor").toggleClass("collapse");
          });
          
          $('#tabla_usuarios').dataTable( {
            "language": {"sProcessing":     "Procesando...","sLengthMenu":     "Mostrar _MENU_ registros","sZeroRecords":    "No se encontraron resultados","sEmptyTable":     "Ningún dato disponible en esta tabla","sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros","sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros","sInfoFiltered":   "(filtrado de un total de _MAX_ registros)","sInfoPostFix":    "","sSearch":         "Buscar registro: ","sUrl":            "","sInfoThousands":  ",","sLoadingRecords": "Cargando...","oPaginate": {"sFirst":    "Primero","sLast":     "Último","sNext":     "Siguiente","sPrevious": "Anterior" },"oAria": { "sSortAscending":  ": Activar para ordenar la columna de manera ascendente", "sSortDescending": ": Activar para ordenar la columna de manera descendente" }}
          } );
        });
        
        function editar_usuario(id_usuario) {
          const data = {id_usuario}

          $.ajax({
            url: "extensiones/actu_usuario.php",
            method: "post",
            data
          })
          .done(function(response) {
            $("#display_usuario").addClass("show_usuario");
            $("#display_usuario").html(response);
          });
        }

        function show_agregar() {
          $.ajax({
            url: "extensiones/actu_usuario.php",
            method: "post",
            data: {nuevo: 'nuevo'}
          })
          .done(function(response) {
            $("#display_usuario").addClass("show_usuario");
            $("#display_usuario").html(response);
          });
          $("#display_usuario").addClass("show_usuario");
        }


        function quitarMod() {
          $(".display_usuario").removeClass("show_usuario");
        }
        document.getElementById("display_usuario").addEventListener('click', function(e) {
            /*2. Si el div con id clickbox contiene a e. target*/
            if (document.getElementById('agregar_usuario').contains(e.target)) {
            } else {
              $(".display_usuario").removeClass("show_usuario");
            }
        })
      </script>
	</body>
</html>
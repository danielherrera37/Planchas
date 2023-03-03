<?php
  include_once '../conexion/conexion.php';
	if(isset($_POST['valor']) != ""){
    $estado = $_POST['valor'];
    $id_corte = $_POST['id_corte'];
		$cambiarModificador = mysqli_query($conexion, "UPDATE corte_consumos set estado = '$estado' where id_corte = '$id_corte'");		
	}
  if((isset($_POST['intervalo'])) and isset($_POST['id_corte'])) {
    $fecha = $_POST['intervalo'];
    $id_corte = $_POST['id_corte'];
    echo $fecha;
    $foranea = $id_corte.'_ibfk_1';
    $ingresarTabla = mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS `tabla_consumo$id_corte` (`id_articulo` int(11) not null,`consumo` int(11) NOT NULL default '0') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    $activarTabla = mysqli_query($conexion,"ALTER TABLE `tabla_consumo$id_corte` ADD KEY `id_articulo` (`id_articulo`)");
    $activarForanea = mysqli_query($conexion,"ALTER TABLE `tabla_consumo$id_corte` ADD CONSTRAINT `tabla_consumo$foranea` FOREIGN KEY (`id_articulo`) REFERENCES articulos (id_articulo)");
    $ingresarDatos = mysqli_query($conexion, "INSERT INTO `tabla_consumo$id_corte` VALUES
        (1, 0),(2, 0),(3, 0),(4, 0),(5, 0),(6, 0),(7, 0),(8, 0),(9, 0),(10, 0)");
    if($ingresarTabla){
      $buscarIgual = mysqli_query($conexion,"SELECT fecha_corte FROM corte_consumos where fecha_corte = '$id_corte'");
      if(mysqli_num_rows($buscarIgual) <= 0){
        $ingresarCorte = mysqli_query($conexion, "INSERT INTO corte_consumos values (null,'$id_corte','disponible')");		
      }
    }
  }
?>

<?php 
	$cortes = mysqli_query($conexion,"SELECT * from corte_consumos");
  while ($lista = mysqli_fetch_array($cortes)){
    $id_corte = $lista[0];
    $fecha = $lista[1];
    $estado = $lista[2];
    $mes = substr($fecha, 5);
    if($mes <= 9){
      $mes = substr($mes, 1);
    }
?>
<tr>
  <td nowrap="2" style="text-align: left;padding:5px"><?php echo $fecha.' '.$meses[$mes]; ?></td>
  <td nowrap="2">
    <div class="checkbox"><input class="tgl tgl-flip" id="Mes_<?php echo $id_corte; ?>" value="disponible" type="checkbox" <?php if($lista[2] == "disponible"){ ?>checked<?php } ?> onclick="cmb_estado(this.id,value)"/><label class="tgl-btn" data-tg-off="Cerrado" data-tg-on="Abierto" for="Mes_<?php echo $id_corte; ?>"></label></div>
  </td>
</tr>
<?php
  }
?>
<?php
  include_once '../conexion/conexion.php';
  $selector = mysqli_query($conexion,"SELECT * FROM ubicaciones where estado = 'activo'");
  $cant_articulos = mysqli_query($conexion,"SELECT * from articulos");
?>
  <table border="0" cellspacing="10" class="datos_planchas">
    <thead>
      <tr>
        <th>Medida</th>
        <th nowrap>Descripcion</th>
        <?php
          while ($consulta = mysqli_fetch_array($selector)){
              echo '<th>En '.$consulta['ubicacion'].'</th>';
          }
        ?>
        <th>Total disponible</th>
      </tr>
    </thead>

    <tbody>
      <?php
        while ($fila 	= mysqli_fetch_array($cant_articulos)) {
          $id_articulo = $fila["id_articulo"];
      ?>
      <tr onclick="traerinfo(this.id)" id="<?php echo $fila["id_articulo"]; ?>">
        <td>
          <?php echo $fila['medida']; ?>
          <input type="hidden" value="<?php echo $fila['medida']; ?>" id="medida<?php echo $fila['id_articulo']; ?>">
        </td>
        <td nowrap="2" align="left">
          <?php echo $fila['descripcion']; ?>
          <input type="hidden" value="<?php echo $fila['descripcion']; ?>" id="descripcion<?php echo $fila['id_articulo']; ?>">
        </td>

        <?php
          $totales = 0;
          $selector = mysqli_query($conexion,"SELECT * FROM ubicaciones where estado = 'activo'");
          while ($consulta = mysqli_fetch_array($selector)){
            $ubicaciones = $consulta["id_ubicacion"];
            $inventario = mysqli_query($conexion,"SELECT `$ubicaciones` FROM tabla_inventario where id_articulo = '$fila[id_articulo]'");
            while ($linea 	= mysqli_fetch_array($inventario)) {
              $enUbicacion = $linea[$ubicaciones];
              echo "<td>$enUbicacion</td>";
              $totales = $totales + $enUbicacion;
            }
          }
            echo "<td>
                    $totales
                    <input type='hidden' value='$totales' id='total$fila[id_articulo]' >
                  </td>";
          }
        ?>
      </tr>
    </tbody>
  </table>
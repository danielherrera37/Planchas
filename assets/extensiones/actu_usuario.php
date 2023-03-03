<div class="agregar_usuario" id="agregar_usuario">
<?php
  include_once '../conexion/conexion.php';
  if(isset($_POST['id_usuario'])) {
    $usuario = mysqli_query($conexion,"SELECT * from usuarios where id_usuario = '$_POST[id_usuario]'");

    while($row = mysqli_fetch_array($usuario)){
      $info = $row['id_usuario'];
      $infoN = $row['nombre'];
      $infoC = $row['correo'];
      $permiCON = $row['consumo_planchas'];
      $permiUBI = $row['ubicaciones'];
      $permiINV = $row['inventario'];
      $permiINF = $row['informe_consumo'];
      $permiUSU = $row['usuarios'];
      $permiIMP = $row['importar_ordenes'];
      $permiCOR = $row['corte_consumos'];
    }
?>

  <form method="post" id="modifico_usuario">
    <div class="header">Modificar usuario</div>

      <div class="contenido">
        <div class="info">
          <input type="hidden" value="<?php echo $info; ?>" id="modi_id">
          <input type="text" id="modi_usuario" placeholder="Nombre del usuario" value="<?php echo $infoN; ?>">
          <input type="text" id="modi_correo" placeholder="Correo del usuario" value="<?php echo $infoC; ?>">
        </div>
        
        <div class="password">
          <input type="text" id="modi_clave" placeholder="Contraseña del usuario">
          <input type="text" id="confir_clave" placeholder="Repita la contraseña">
        </div>
      <div class="permisos">
        <header>Permisos de usuario</header>

        <div class="display">
          <div class="checkbox"><input class="tgl tgl-flip" id="consumo1" type="checkbox"  value="<?php echo $permiCON; ?>" <?php if($permiCON == "si"){ echo "checked"; }?>/><label class="tgl-btn" data-tg-off="Consumo" data-tg-on="Consumo" for="consumo1"></label></div>
          <div class="checkbox"><input class="tgl tgl-flip" id="ubicaciones1" type="checkbox" value="<?php echo $permiUBI; ?>" <?php if($permiUBI == "si"){ echo "checked"; }?>/><label class="tgl-btn" data-tg-off="Ubicaciones" data-tg-on="Ubicaciones" for="ubicaciones1"></label></div>
          <div class="checkbox"><input class="tgl tgl-flip" id="inventario1" type="checkbox" value="<?php echo $permiINV; ?>" <?php if($permiINV == "si"){ echo "checked"; }?>/><label class="tgl-btn" data-tg-off="Inventario" data-tg-on="Inventario" for="inventario1"></label></div>
          <div class="checkbox"><input class="tgl tgl-flip" id="informe1" type="checkbox" value="<?php echo $permiINF; ?>" <?php if($permiINF == "si"){ echo "checked"; }?> checked/><label class="tgl-btn" data-tg-off="Informe de consumo" data-tg-on="Informe de consumo" for="informe1"></label></div>
          <div class="checkbox"><input class="tgl tgl-flip" id="usuarios1" type="checkbox" value="<?php echo $permiUSU; ?>" <?php if($permiUSU == "si"){ echo "checked"; }?>/><label class="tgl-btn" data-tg-off="Usuarios" data-tg-on="Usuarios" for="usuarios1"></label></div>
          <div class="checkbox"><input class="tgl tgl-flip" id="importar1" type="checkbox" value="<?php echo $permiIMP; ?>" <?php if($permiIMP == "si"){ echo "checked"; }?>/><label class="tgl-btn" data-tg-off="Importar ordenes" data-tg-on="Importar ordenes" for="importar1"></label></div>
          <div class="checkbox"><input class="tgl tgl-flip" id="corte1" type="checkbox" value="<?php echo $permiCOR; ?>" <?php if($permiCOR == "si"){ echo "checked"; }?>/><label class="tgl-btn" data-tg-off="Corte de mes" data-tg-on="Corte de mes" for="corte1"></label></div>
        </div>

      </div>
      <input type="submit" value="Modificar" name="MODIFICAR_USUARIO"><input type="button" value="Cancelar" onclick="quitarMod()">
    </div>
  </form>	
  
<?php
  }
  if(isset($_POST['nuevo'])) {
?>
  <form method="post" id="ingreso_usuario">
    <div class="header">Agregar usuario</div>

    <div class="contenido">
      <div class="info">
        <input type="text" id="nuevo_usuario" placeholder="Nombre del usuario">
        <input type="text" id="nuevo_correo" placeholder="Correo del usuario">
      </div>
      
      <div class="password">
        <input type="password" id="nueva_clave" placeholder="Contraseña del usuario">
        <input type="password" id="nueva_repita" placeholder="Repita la contraseña">
      </div>

      <div class="permisos">
        <header>Permisos de usuario</header>

        <div class="display">
          <div class="checkbox"><input class="tgl tgl-flip" id="consumo2" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="Consumo" data-tg-on="Consumo" for="consumo2"></label></div>
          <div class="checkbox"><input class="tgl tgl-flip" id="ubicaciones2" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="Ubicaciones" data-tg-on="Ubicaciones" for="ubicaciones2"></label></div>
          <div class="checkbox"><input class="tgl tgl-flip" id="inventario2" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="Inventario" data-tg-on="Inventario" for="inventario2"></label></div>
          <div class="checkbox"><input class="tgl tgl-flip" id="informe2" type="checkbox" value="si" checked/><label class="tgl-btn" data-tg-off="Informe de consumo" data-tg-on="Informe de consumo" for="informe2"></label></div>
          <div class="checkbox"><input class="tgl tgl-flip" id="usuarios2" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="Usuarios" data-tg-on="Usuarios" for="usuarios2"></label></div>
          <div class="checkbox"><input class="tgl tgl-flip" id="importar2" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="Importar ordenes" data-tg-on="Importar ordenes" for="importar2"></label></div>
          <div class="checkbox"><input class="tgl tgl-flip" id="corte2" type="checkbox" value="si"/><label class="tgl-btn" data-tg-off="Corte de mes" data-tg-on="Corte de mes" for="corte2"></label></div>
        </div>

      </div>
    </div>
    <input type="submit" value="Añadir">
  </form>
<?php
  }
?>
</div>

<?php
if(isset($_POST['incluir'])){
  $nuevo_nombre = $_POST['nombre'];
  $nuevo_correo = $_POST['correo'];
  $nueva_clave = $_POST['clave'];
  $nueva_clave = password_hash($nueva_clave, PASSWORD_DEFAULT);  
  $P_consumo = $_POST['consumo'];
  $P_ubicaciones = $_POST['ubicaciones'];
  $P_inventario = $_POST['inventario'];
  $P_informe = $_POST['informe'];
  $P_usuarios = $_POST['usuarios'];
  $P_importar = $_POST['importar'];
  $P_corte = $_POST['corte'];
  $subir_usuario = mysqli_query($conexion,"INSERT INTO usuarios (correo,nombre,clave,consumo_planchas,ubicaciones,inventario,informe_consumo,usuarios,importar_ordenes,Corte_consumos)
  values ('$nuevo_correo','$nuevo_nombre','$nueva_clave','$P_consumo','$P_ubicaciones','$P_inventario','$P_informe','$P_usuarios','$P_importar','$P_corte')");

}

if(isset($_POST['modificar'])){
  $id_editar = $_POST['id_usu'];
  $nuevo_nombre = $_POST['nombre'];
  $nuevo_correo = $_POST['correo'];
  $nueva_clave = $_POST['clave'];
  $nueva_clave = password_hash($nueva_clave, PASSWORD_DEFAULT);  
  $P_consumo = $_POST['consumo'];
  $P_ubicaciones = $_POST['ubicacion'];
  $P_inventario = $_POST['inventario'];
  $P_informe = $_POST['informe'];
  $P_usuarios = $_POST['usuarios'];
  $P_importar = $_POST['importar'];
  $P_corte = $_POST['corte'];

  $consulta = "UPDATE usuarios set ";
  if($nuevo_nombre != ""){
    $consulta .= "nombre = '$nuevo_nombre', ";
  }
  if($nuevo_correo != ""){
    $consulta .= "correo = '$nuevo_correo', ";
  }
  if($P_consumo != ""){
    $consulta .= "consumo_planchas = '$P_consumo', ";
  }
  if($P_ubicaciones != ""){
    $consulta .= "ubicaciones = '$P_ubicaciones', ";
  }
  if($P_inventario != ""){
    $consulta .= "inventario = '$P_inventario', ";
  }
  if($P_informe != ""){
    $consulta .= "informe_consumo = '$P_informe', ";
  }
  if($P_usuarios != ""){
    $consulta .= "usuarios = '$P_usuarios',";
  }
  if($P_importar != ""){
    $consulta .= "importar_ordenes = '$P_importar',";
  }
  if($P_corte != ""){
    $consulta .= "Corte_consumos = '$P_corte' ";
  }
  
  $consulta .= " where id_usuario = $id_editar";
  mysqli_query($conexion,$consulta);
}
?>

<script>
  $('#ingreso_usuario').on('submit', registrarUsuario);
  function registrarUsuario(event){
    event.preventDefault();

    var nombre      = $("#nuevo_usuario").val();
    var correo      = $("#nuevo_correo").val();
    var clave       = $("#nueva_clave").val();
    var repetir     = $("#nueva_repita").val();
    var consumo     = $("#consumo2").val();
    var ubicaciones = $("#ubicaciones2").val();
    var inventario  = $("#inventario2").val();
    var informe     = $("#informe2").val();
    var usuarios    = $("#usuarios2").val();
    var importar    = $("#importar2").val();
    var corte       = $("#corte2").val();

    if(clave != repetir){
      alert("No coinciden las constraseñas");
    }
    else{
      var incluir = "correcto";
      const data = {nombre, correo, clave, repetir, consumo, ubicaciones, inventario, informe, usuarios, importar, corte, incluir};
      $.ajax({
        url: "extensiones/actu_usuario.php",
        type : 'POST',
        dataType : 'html',
        data
      })
      .done(function(response) {
        $(".display_usuario").removeClass("show_usuario");
        alert("Usuario agregado correctamente");
        window.location.href = 'usuarios.php';
      });
    }
  }
    
  $('#modifico_usuario').on('submit', modificarUsuario);
  function modificarUsuario(event){
    event.preventDefault();

    var id_usu = $("#modi_id").val();
    var nombre      = $("#modi_usuario").val();
    var correo      = $("#modi_correo").val();
    var clave       = $("#modi_clave").val();
    var repetir     = $("#confir_clave").val();
    var consumo     = $("#consumo1").val();
    var ubicacion = $("#ubicaciones1").val();
    var inventario  = $("#inventario1").val();
    var informe     = $("#informe1").val();
    var usuarios    = $("#usuarios1").val();
    var importar    = $("#importar1").val();
    var corte       = $("#corte1").val();

    if(clave != repetir){
      alert("No coinciden las constraseñas");
    }
    else{
      var modificar = "correcto";
      const data = {id_usu, nombre, correo, clave, repetir, consumo, ubicacion, inventario, informe, usuarios, importar, corte, modificar};
      $.ajax({
        url: "extensiones/actu_usuario.php",
        type : 'POST',
        dataType : 'html',
        data
      })
      .done(function(response) {
        $(".display_usuario").removeClass("show_usuario");
        alert("Usuario modificado correctamente");
        window.location.href = 'usuarios.php';
      });
    }
  }
</script>
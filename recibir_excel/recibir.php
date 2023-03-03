<?php
  include_once '../conexion/conexion.php';
  
  require 'convertir_excel/vendor/autoload.php';

  use PhpOffice\PhpSpreadsheet\IOFactory;

  $nombre = $_FILES['dataCliente']['name'];

  $destino = $nombre;

  copy($_FILES['dataCliente']['tmp_name'],$destino);
  // Leer el archivo de Excel
  $spreadsheet = IOFactory::load($_FILES['dataCliente']['name']);

  // Seleccionar la hoja de cálculo activa
  $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

  $i = 0;
  // Recorrer la matriz y hacer algo con los datos
  foreach ($sheetData as $row) {
    if($i != 0){

      if($row['C'] != ""){
        $row['C'] = date("Y-m-d", strtotime($row['C']));
      }

      $duplicados = mysqli_query($conexion,"SELECT * FROM Ordenes WHERE numero_op = '$row[A]'");
      /* EN CASO DE QUE HAYA DUPLICADO */

        if(mysqli_num_rows($duplicados) > 0 ) { 
            // $insertarRepetidas = mysqli_query($conexion,"INSERT INTO ordenes_duplicadas VALUES('$row[A]','$row[B]','$row[C]','$row[D]','$row[E]','$row[F]')");
            // $actualizados++;
        } 
        else{
          $insertarData   = mysqli_query($conexion,"INSERT INTO ordenes VALUES('$row[A]','$row[B]','$row[C]','$row[D]','$row[E]','$row[F]')");
          // $ingreso_ordenes++;
        }
      // echo $row['A'] . " <br>";
    }
    $i++;
  }
  if($i >= 1){
    echo true;
  }
?>

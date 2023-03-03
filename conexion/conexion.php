<?php
	class Database{
		private $servidorlocal;
		private $basededatos;
		private $nombre;
		private $password;
		private $charset;

		public function __construct(){
				$this->servidorlocal = $_ENV['DB_HOST'];
				$this->basededatos 	 = $_ENV['DB_NAME'];
				$this->nombre       = $_ENV['DB_USER'];
				$this->password         = $_ENV['DB_PASSWORD'];
				$this->charset    = 'utf8';
		}
		function connect(){
			try{
				$conexion = "mysql:host=".$this->servidorlocal.";dbname=".$this->basededatos.";charset=".$this->charset;
				$opciones = [
				PDO::ATTR_ERRMODE 		    => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_EMULATE_PREPARES  => false, ];
				$pdo = new PDO($conexion, $this->nombre, $this->password,$opciones);
				return $pdo;
			}
			catch(PDOException $e){
				print_r('Error en la conexion:  '.$e->getMessage());
			}
		}
	}

	$DB_HOST = $_ENV['DB_HOST'];
	$DB_USER = $_ENV['DB_USER'];
	$DB_PASSWORD = $_ENV['DB_PASSWORD'];
	$DB_NAME = $_ENV['DB_NAME'];
	$DB_PORT = $_ENV['DB_PORT'];

  	$conexion=mysqli_connect($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_NAME,$DB_PORT);

	date_default_timezone_set("America/Bogota");
	setlocale(LC_ALL,"es_ES");

	$corteEnCurso = date("Y-m");

	$fecha_mes = date("Y-m-d");
	$fecha_hoy 					= date("Y-m-d H:i:s");
	$mes_hoy 						= date("m");
	$dia 								= date('Y-m-01 00:00:00');
	$fecha_cierre 			= date('Y-m-05 00:00:00');
	$fecha 							= date("Y-m-d H:i:s");
	$solofecha_cero 		= date("Y-m-d 00:00:00");

	$meses = ['Default','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];

	/* principal */
	$fecha_minima 	= date('Y-m-01 00:00:00');
	$mes_anterior 	= date("m",strtotime($dia."- 1 month,"));
	$final = new DateTime('now');
	$final->modify('last day of this month');
	$fecha_maxima =  $final->format('Y-m-d 23:59:59');

	/* ordenes_consumos */
	
	$fecha_hoy_final 		= date("Y-m-d 23:59:59");
	$fecha_hoy_inicio 		= date("Y-m-d 00:00:00");
	$fecha_inicio = date("Y-m-01");
	$date = new DateTime('now');
	$date->modify('last day of this month');
	$fecha_final =  $date->format('Y-m-d');
	$datetime_final =  $date->format('Y-m-d 23:59:59');

	/* extension consulta_medidas */
	$solo_fecha = date('Y-m-01 00:00:00');

	$buscar_cortes = mysqli_query($conexion, "SELECT * from corte_consumos");
?>
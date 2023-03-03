$(actualizar_inventario());

function actualizar_inventario(){
	$.ajax({
		url : 'extensiones/inventario.php',
		type : 'POST',
		dataType : 'html',
		})

	.done(function(resultado){
		$("#tabla_articulos").html(resultado);
	})
}



$(document).ready(function(){
	$(".contenedor_mayor").toggleClass("collapse");
	$(".hamburger").click(function(){
			$(".contenedor_mayor").toggleClass("collapse");
	});
});

$('#formConsumo').on('submit', registrarConsumo);

function registrarConsumo(event){
	// Evitar que el formulario se procese normalmente
	event.preventDefault();

	const medida 			= $('input[name="medida"]').val();
	const cantidad 		= $('input[name="cantidad"]').val();
	const ubicacion 	= $('select[name="ubicacion"]').val();
	const id_consumidor = $('input[name="id_usuario"]').val();
	const disponibles  = $('input[name="disponibles"]').val();
	const orden 	    = $('input[name="busqueda_OP"]').val();
	const id_articulo  = $('input[name="id_articulo"]').val();
	const descripcion  = $('textarea[name="descripcionArticulo"]').val();
	const observacion  = $('textarea[name="observacion"]').val();

	const fecha_consumo  = $('input[name="fecha_consumo"]').val();

   if (cantidad >= 1 && descripcion != "" && ubicacion != "") {

      const data = {id_consumidor, cantidad, disponibles, orden, id_articulo, descripcion, observacion, ubicacion, medida, fecha_consumo};

      $.ajax({url : 'extensiones/guardar_consumo.php', type : 'POST', dataType : 'html', data})

      .done(function(resultado){
				$('input[name="medida"]').val("");
				$('input[name="cantidad"]').val("");
				$('input[name="disponibles"]').val("");
				$('input[name="id_articulo"]').val("");
				$('textarea[name="descripcionArticulo"]').val("");
				$('textarea[name="observacion"]').val("");
				$('input[name="busqueda_OP"]').val("");
				actualizar_inventario();
			});

      

      const $btnGuardar = $('#guardarConsumo');

      $btnGuardar.prop('disabled', true);

			$(actualizar_inventario());

      setTimeout(function() {$btnGuardar.prop('disabled', false); $('.msgConsumoHecho').toggleClass("show_msgConsumoHecho"); $(actualizar_inventario());}, 4000);

      $('.msgConsumoHecho').toggleClass("show_msgConsumoHecho");

   } 
	 else {
		if (cantidad == ""){
			alert("Tiene que ingresar una cantidad de consumo")
			$('input[name="cantidad"]').focus()
		}
		if ((cantidad < 0) && (cantidad != "")){
			$('.Confirmacion').toggleClass("show_Confirmacion");

			$('input[name="confirmaorden"]').val(orden);
			$('input[name="confirmacant"]').val(cantidad);
			$('input[name="confirmadesc"]').val(descripcion);
			$('input[name="confirmaobser"]').val(observacion);
		}
		if (descripcion == ""){
			alert("Tiene que escoger un articulo")
			$('textarea[name="descripcionArticulo"]').focus()
		}
		if(ubicacion == ""){
			alert("Tiene que eligir una ubicacion")
			$('select[name="ubicacion"]').focus()
		}
	}
};


$("#confirmar_consumo").click(function(){
	const ubicacion 		= $('select[name="ubicacion"]').val();
	const id_consumidor = $('input[name="id_usuario"]').val();
	const id_articulo 	= $('input[name="id_articulo"]').val();
	const descripcion 	= $('input[name="confirmadesc"]').val();
	const cantidad 			= $('input[name="confirmacant"]').val();
	const orden 				= $('input[name="confirmaorden"]').val();
	const observacion 	= $('input[name="confirmaobser"]').val();

	const fecha_consumo  = $('input[name="fecha_consumo"]').val();

	const data = {id_consumidor, cantidad, orden, id_articulo, descripcion, observacion, ubicacion,  fecha_consumo};

	if ((descripcion != "") && (observacion != "")){
		$.ajax({
			url : 'extensiones/guardar_devolucion.php',
			type : 'POST',
			dataType : 'html',
			data
		})

		.done(function(resultado){})

		actualizar_inventario();

		const $btnGuardar = $('#guardarConsumo');

		$btnGuardar.prop('disabled', true);

		setTimeout(function() {
			$btnGuardar.prop('disabled', false);
			$('.msgConsumoHecho').toggleClass("show_msgConsumoHecho");
			$(actualizar_inventario());
		}, 3500);

		$('input[name="medida"]').val("");
		$('input[name="cantidad"]').val("");
		$('input[name="disponibles"]').val("");
		$('input[name="id_articulo"]').val("");
		$('textarea[name="descripcionArticulo"]').val("");
		$('textarea[name="observacion"]').val("");
		$('input[name="busqueda_OP"]').val("");

		$('.msgConsumoHecho').toggleClass("show_msgConsumoHecho");
		$('.Confirmacion').toggleClass("show_Confirmacion");
	}
	else{
		if ($('input[name="confirmaobser"]').val() == ""){
			alert("Tiene que ingresar una nota")
			$('input[name="confirmaobser"]').focus()
		}
	}
});


function traerinfo(id){
	var descripcion = document.getElementById("descripcion"+id).value;
	var cantidad = document.getElementById("total"+id).value;
	var medida = document.getElementById("medida"+id).value;
	$('textarea[name="descripcionArticulo"').val(descripcion);
	$('input[name=disponibles').val(cantidad);
	$('input[name=id_articulo').val(id);
	$('input[name=medida').val(medida);
	
	const Inicial = document.getElementById('disponibles')
	const Final  = document.getElementById('cantidad')

	Final.addEventListener('change',()=>{
			Final.setAttribute('max',Inicial.value)
	})
}

/* PARA TRAER INFO ORDEN */
function traerOrden(op) {
	op = op.replace('op', '');;console.log(op);
	
	var nombre_trabajo = document.getElementById("trabajo"+op).value;
	$('textarea[name=nombre_trabajo').val(nombre_trabajo);
	$('input[name=busqueda_OP').val(op);
}
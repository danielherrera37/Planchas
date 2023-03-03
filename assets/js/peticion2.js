$(obtener_ordenes());

function obtener_ordenes(ordenes)
{
	$.ajax({
		url : 'extensiones/ordenes_consumos.php',
		type : 'POST',
		dataType : 'html',
		data : { ordenes: ordenes },
		})

	.done(function(resultado){
		$("#tabla_ordenes").html(resultado);	
	})
	return new Promise(function(resolve, reject) {
	resolve();
	});
}

$(document).on('keyup', '#busqueda_OP', function(){
	var valorBusqueda=$(this).val();
	console.log(valorBusqueda);
	if (valorBusqueda!=""){
		obtener_ordenes(valorBusqueda);
		setTimeout(function(){
			var name = $('#trabajo'+valorBusqueda).val();
			$('#nombre_trabajo').val(name);
			console.log(name);
		},500);
	}
	else{
		obtener_ordenes();
	}
});



/* PARA CONSUMOS (DESDE HASTA)*/
$(obtener_ubicaciones());

function obtener_ubicaciones(desde, hasta)
{
	$.ajax({
		url : 'extensiones/consulta_ubicaciones.php',
		type : 'POST',
		dataType : 'html',
		data : { desde: desde, hasta: hasta },
		})

	.done(function(result){
		$("#tabla_ubicaciones").html(result);
	})
}

$(document).on('change', '#consumo_desde', function()
{
	var valorDesde=$(this).val();
	var valorHasta=$("#consumo_hasta").val();
	if (valorDesde!="")
	{
		obtener_ubicaciones(valorDesde, valorHasta);
	}
});


$(document).on('change', '#consumo_hasta', function()
{
	var valorHasta=$(this).val();
	var valorDesde=$("#consumo_desde").val();
	if (valorDesde!="")
	{
		obtener_ubicaciones(valorDesde, valorHasta);
	}
});





/* PARA MEDIDAS */
$(obtener_medida());

function obtener_medida(medida)
{
	$.ajax({
		url : 'extensiones/consulta_medidas.php',
		type : 'POST',
		dataType : 'html',
		data : { medida: medida },
		})

	.done(function(resultado){
		$("#tabla_medidas").html(resultado);
	})
}

$(document).on('change', '#medidas', function()
{
	var valor=$(this).val();
	if (valor!="")
	{
		obtener_medida(valor);
	}
	else
		{
			obtener_medida();
		}
});



/* PARA consumo de ordenes */
$(obtener_consumoOrdenes());

function obtener_consumoOrdenes(valor, Ordendesde, Ordenhasta, trabajo)
{
	$.ajax({
		url : 'extensiones/consulta_consumosOrdenes.php',
		type : 'POST',
		dataType : 'html',
		data : { valor: valor, Ordendesde: Ordendesde, Ordenhasta: Ordenhasta, trabajo:trabajo },
		})

	.done(function(resultado){
		$("#consumo_ordenes").html(resultado);
	})
}

$(document).on('keyup', '#busqueda_Consumoordenes', function()
{
	var valor=$(this).val();
	var ordenDesde=$("#ordenes_desde").val();
	var ordenHasta=$("#ordenes_hasta").val();
	if (valor!="")
	{
		obtener_consumoOrdenes(valor, ordenDesde, ordenHasta);
	}
});
$(document).on('keyup', '#busqueda_Nombretrabajo', function()
{
	var trabajo=$(this).val();
	var ordenDesde=$("#ordenes_desde").val();
	var ordenHasta=$("#ordenes_hasta").val();
	if (trabajo!="")
	{
		obtener_consumoOrdenes(null, ordenDesde, ordenHasta, trabajo);
	}
	else
		{
			obtener_consumoOrdenes();
		}
});
$(document).on('change', '#ordenes_desde', function()
{
	var ordenDesde=$(this).val();
	var trabajo=$("#busqueda_Nombretrabajo").val();
	var valor=$("#busqueda_Consumoordenes").val();
	var ordenHasta=$("#ordenes_hasta").val();
	if (valor!="")
	{
		obtener_consumoOrdenes(valor, ordenDesde, ordenHasta);
	}
	else
		{
			obtener_consumoOrdenes(ordenDesde, ordenHasta, trabajo);
		}
});
$(document).on('change', '#ordenes_hasta', function()
{
	var ordenHasta=$(this).val();
	var trabajo=$("#busqueda_Nombretrabajo").val();
	var ordenDesde=$("#ordenes_desde").val();
	var valor=$("#busqueda_Consumoordenes").val();
	if (valor!="")
	{
		obtener_consumoOrdenes(valor, ordenDesde, ordenHasta);
	}
	else
		{
			obtener_consumoOrdenes(ordenDesde, ordenHasta, trabajo);
		}
});

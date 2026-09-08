ruta_adjunto = '';

$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

$(document).on('select2:open', function (e) {
    let searchField = document.querySelector('.select2-container--open .select2-search__field');
    if (searchField) {
        searchField.focus();
    }
});

$('#id_tipo_usuario').select2({
    language: {
        noResults: function(){
            return 'No se encontraron resultados';
        }
    }
});

$('#id_servicio').select2({
    language: {
        noResults: function(){
            return 'No se encontraron resultados';
        }
    }
});


$('#id_universidad').select2({
    language: {
        noResults: function(){
            return 'No se encontraron resultados';
        }
    }
});

//DROPZONE----------------------------------------------------------------------------------------------------------
function DZ_inicializar(formulario,modo){

	ruta_adjunto = '';

	Dropzone.autoDiscover = false;



    // Obtener el elemento del formulario
	if (Dropzone.instances.length > 0) {
		Dropzone.instances.forEach(function (dz) {
			if (dz.element.id === formulario) {
                // Limpia solo del cliente sin ejecutar removedfile ni eliminar del servidor
				dz.files.forEach(file => {
					if (file.previewElement) {
                        file.previewElement.remove(); // remueve la vista previa
                    }
                });
                dz.files = []; // limpia la lista de archivos sin tocar el servidor
                dz.element.classList.remove("dz-started"); // limpia clase visual
            }
        });
	}

	document.addEventListener("DOMContentLoaded", function () {
		const miDropzone = new Dropzone("#"+ formulario, {
			url: dropzoneSubir,
			paramName: "file",
            maxFilesize: 3, // en MB
            maxFiles: 1, //  solo un archivo permitido
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf",
            dictDefaultMessage: "",
            dictFileTooBig: "El archivo excede el tamaño permitido ({{filesize}}MB). Máximo: {{maxFilesize}}MB.",
            dictInvalidFileType: "Este tipo de archivo no está permitido. Por favor, selecciona un formato válido.",
            dictResponseError: "El servidor respondió con un error.",
            dictCancelUpload: "Cancelar subida",
            dictRemoveFile: "Eliminar archivo",
            dictMaxFilesExceeded: "Solo se permite subir un archivo.",
            addRemoveLinks: true,
            dictRemoveFile: "Eliminar",
            headers: {
            	'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            params: {
            	modo: modo
            },
            success: function(file, response) {
            	file.nombreServidor = response.nombre;
            	ruta_adjunto = response.ruta;
            	console.log("Subido con éxito:", response);
            },
            removedfile: function(file) {

            	if (file.nombreServidor) {
            		fetch(dropzoneEliminar, {
            			method: "POST",
            			headers: {
            				"Content-Type": "application/json",
            				"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            			},
            			body: JSON.stringify({ 
            				nombre: file.nombreServidor,
            				modo: modo
            			})
            		}).then(res => res.json())
            		.then(data => console.log("Archivo eliminado:", data))
            		.catch(error => console.error("Error al eliminar:", error));
            	}
            	if (file.previewElement) {
            		file.previewElement.remove();
            	}
            },
            init: function () {
			    this.on("maxfilesexceeded", function(file) {
			        this.removeAllFiles(); // elimina el anterior
			        this.addFile(file);    // agrega el nuevo
			    });

			   
			},
			accept: function(file, done) {
			    // Validar tipo de archivo
			    const tiposPermitidos = this.options.acceptedFiles.split(',');
			    const esValido = tiposPermitidos.some(tipo => file.type.includes(tipo) || file.name.toLowerCase().endsWith(tipo.trim()));

			    if (!esValido) {
			        done("Tipo de archivo no permitido.");
			        return;
			    }

			    // Si todo está bien
			    done();
			
           
            },
            
        });
    });
}
//DROPZONE----------------------------------------------------------------------------------------------------------


DZ_inicializar('dz_Adjunto_evidencia',1);


$('#fecha').on('keydown', function (e) {
        // Solo permite Enter
	if (e.key !== "Enter") {
		e.preventDefault();
	}
});


$('#hora').on('keydown', function (e) {
        // Prevenir teclas que podrían borrar (Backspace, Delete)
	if (e.key === 'Backspace' || e.key === 'Delete') {
		e.preventDefault();
	}
});

    // Validar si el input queda vacío y corregir a 00:00
$('#hora').on('blur change', function () {
	if (!$(this).val()) {
		$(this).val('00:00');
	}
});



function inputNumerico(campo){

	$('#'+campo).on('input', function () {
	    	this.value = this.value.replace(/\D/g, ''); // Elimina todo lo que no sea dígito
	    });
}

function validarEmail(campo) {
	const regex = /^[^@]+@[^@]+$/;
	return regex.test(campo);
}

inputNumerico('telefono');
inputNumerico('numero_documento');


$('#numero_documento').blur(function () {

    consultarDni();
});



function consultarDni() {

    const dni = $('#numero_documento').val().trim();
    const url = `http://45.231.72.245/apirenieccarrion/public/api/dni/${dni}/FRM-PAUS/1/172.17.5.245/5/libro_reclamaciones/libro_reclamaciones`;

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/xml' // o text/xml si es SOAP
        },
        body: '' // si la API lo requiere, puedes poner aquí un cuerpo XML
    })
    .then(response => response.text())
    .then(data => {
        console.log("Respuesta:", data);

        // Si es XML, puedes parsearlo así:
        const parser = new DOMParser();
        const xml = parser.parseFromString(data, "application/xml");
        const values = xml.getElementsByTagName("string");
        
        $('#txt_razon_social').val(values[5].textContent +' '+ values[2].textContent+ ' ' +values[3].textContent);
        $('#txt_domicilio').val(values[18].textContent +', '+ values[16].textContent);
    })
    .catch(error => {
        console.error("Error:", error);
    });
}



$('#btn_guardar').click(function (e) {

	msm = '';
	msm_input = '';

	fecha = $('#fecha').val().trim();
	hora = $('#hora').val().trim();
	numero_documento = $('#numero_documento').val().trim();
	nombres_apellidos = $('#nombres_apellidos').val().trim();
	email = $('#email').val().trim();
	telefono = $('#telefono').val().trim();
	id_servicio = $('#id_servicio').val();
	id_universidad = $('#id_universidad').val();
	id_reporte = $('input[name="id_reporte"]:checked').val();
	detalle_reporte = $('#detalle_reporte').val().trim();
	not_reclamo = $('#not_reclamo').is(':checked') ? 1 : 0;
	id_tipo_usuario = $('#id_tipo_usuario').val();


	//-----------------------------------------------------
	//detalle
	if ($('#detalle_reporte').val().length <= 0) {

		msm = 'Ingrese la descripción del reclamo'; 
		$('#detalle_reporte').css('border', '2px solid red');
		msm_input = 'detalle_reporte';
		//$('#lbl_txt_reclamo').text(msm);
	}
	else{
		$('#detalle_reporte').css('border', '');
		//$('#lbl_detalle_reporte').text('');
	}


	//telefono
	if (!/^[9][0-9]{8}$/.test(telefono)) {
		msm = 'Ingrese un número de celular válido';
		$('#telefono').css('border', '2px solid red');
		msm_input = 'telefono';
			//$('#lbl_telefono').text(msm);
	}
	else {
		$('#telefono').css('border', '');
			//$('#lbl_txt_telefono').text('');
	}

	//-----------------------------------------------------
	//email
	if (!validarEmail(email)) {
		msm = 'Correo inválido'; 
		$('#email').css('border', '2px solid red');
		msm_input = 'email';
			//$('#lbl_txt_email').text(msm);
	}
	else{ 
		$('#email').css('border', '');
			//$('#lbl_txt_email').text('');
	}


	if (nombres_apellidos.length <= 0) {
		msm = 'Ingrese nombres y apellidos';
		$('#nombres_apellidos').css('border', '2px solid red');
		msm_input = 'nombres_apellidos';
			//$('#lbl_txt_domicilio').text(msm);
	}
	else{
		$('#nombres_apellidos').css('border', '');
			//$('#lbl_txt_domicilio').text('');
	}

	//---------------------------------------------------------

	if (numero_documento.length != 8) {
		msm = 'Número de documento incorrecto'; 
		$('#numero_documento').css('border', '2px solid red');
		msm_input = 'numero_documento';
		//$('#numero_documento').text(msm);
	}
	else{ 
		$('#numero_documento').css('border', '');
		//$('#lbl_txt_numero_documento').text('');
	}

		//_________________________________________________________________

	if (hora.length <= 0) {
		msm = 'Ingrese hora'; 
		$('#hora').css('border', '2px solid red');
		msm_input = 'hora';
		//$('#lbl_txt_hora').text(msm);
	}
	else{
		$('#hora').css('border', '');
		//$('#lbl_txt_hora').text('');
	}

		//-----------------------------------------------------

	if (fecha.length <= 0) {
		msm = 'Ingrese fecha'; 
		$('#fecha').css('border', '2px solid red');
		msm_input = 'fecha';
		//$('#lbl_txt_fecha').text(msm);
	}
	else{
		$('#fecha').css('border', '');
		//$('#lbl_txt_fecha').text('');
	}


	if (msm != '') {


		$('#'+msm_input).focus();
	}
	else{

		$.LoadingOverlay("show");

		let formData = new FormData();
		formData.append('hora', hora);
		formData.append('fecha', fecha);
		formData.append('numero_documento', numero_documento);
		formData.append('nombres_apellidos', nombres_apellidos);
		formData.append('email', email);
		formData.append('telefono', telefono);
		formData.append('id_servicio', id_servicio);
		formData.append('id_universidad', id_universidad);
		formData.append('not_reclamo', not_reclamo);
		formData.append('id_reporte', id_reporte);
		formData.append('detalle_reporte', detalle_reporte);
		formData.append('id_tipo_usuario', id_tipo_usuario);
		formData.append('ruta_evidencia', ruta_adjunto);



		$('#txt_hora').val('');
		$('#txt_fecha').val('');
		$('#select_tipo_documento').val(0);
		$('#txt_numero_documento').val('');
		$('#txt_razon_social').val('');
		$('#txt_email').val('');
		$('#txt_domicilio').val('');
		$('#txt_telefono').val('');

		$('#select_reclamo').val(0);

		$('#select_tipo_documento_2').val(0);
		$('#txt_numero_documento_2').val('');
		$('#txt_razon_social_2').val('');
		$('#txt_email_2').val('');
		$('#txt_domicilio_2').val('');
		$('#txt_telefono_2').val('');

		$('#observaciones').val('');
		$('input[name="notificacion_"]:checked').val(1);
		$('#ruta_adjunto').val('');

		DZ_inicializar('dz_Adjunto_evidencia',1);

		$.ajax({
			type: 'POST',
			url: saveUrl,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: formData,
			processData: false, // 
			contentType: false, // 
			success: function (response) {

			    $.LoadingOverlay("hide");

			    Swal.fire({
			    	title: "Correcto",
			    	text: "Su reclamo "+ response.correlativo +" ha sido registrado con éxito. Nos pondremos en contacto con usted.",
			    	icon: "success",
			    	confirmButtonText: 'Aceptar',
			    	confirmButtonColor: '#4ba917',

			    }).then((result) => {

			    	if (result.isConfirmed || result.isDismissed) {

			    		window.location.reload();
			    	}
			    	});
			    },
			   	error: function (xhr, status, error) {
			    	$.LoadingOverlay("hide");

			    	Swal.fire({
			    		title: "Ocurrió un problema",
			    		text: "Ocurrió un problema interno en el sistema",
			    		icon: "error",
			    		confirmButtonText: 'Aceptar',
			    		confirmButtonColor: '#d33',
			    });
			}
		});
 	}
});

$(document).ready(function() {



   const cboDepartamento = document.getElementById('cboDepartamento');
   const cboProvincia = document.getElementById('cboProvincia');
   const cboDistrito = document.getElementById('cboDistrito');
   const form = document.getElementById('ubigeoForm');

                // Cargar departamentos desde el backend
   async function cargarDepartamentos() {
    try {
        const response = await fetch('/api/ubigeo/departamentos');
        const departamentos = await response.json();

        cboDepartamento.innerHTML = '<option value="">- Seleccione -</option>';
        departamentos.forEach(depto => {
            const option = document.createElement('option');
            option.value = depto.id;
            option.textContent = depto.nombre;
            cboDepartamento.appendChild(option);
        });
    } catch (error) {
        console.error('Error cargando departamentos:', error);
    }
}

                // Cargar provincias desde el backend
async function cargarProvincias(departamentoId) {
    if (!departamentoId) return;

    try {
        const response = await fetch(`/api/ubigeo/${departamentoId}/provincias`);
        const provincias = await response.json();

        cboProvincia.innerHTML = '<option value="">- Seleccione -</option>';
        provincias.forEach(prov => {
            const option = document.createElement('option');
            option.value = prov.id;
            option.textContent = prov.nombre;
            cboProvincia.appendChild(option);
        });
    } catch (error) {
        console.error('Error cargando provincias:', error);
    }
}

                // Cargar distritos desde el backend
async function cargarDistritos(provinciaId) {
    if (!provinciaId) return;

    try {
        const response = await fetch(`/api/ubigeo/${provinciaId}/distritos`);
        const distritos = await response.json();

        cboDistrito.innerHTML = '<option value="">- Seleccione -</option>';
        distritos.forEach(dist => {
            const option = document.createElement('option');
            option.value = dist.id;
            option.textContent = dist.nombre;
            cboDistrito.appendChild(option);
        });
    } catch (error) {
        console.error('Error cargando distritos:', error);
    }
}

                // Validar con el backend antes de enviar el formulario
async function validarUbigeo(departamentoId, provinciaId, distritoId) {
    try {
        const response = await fetch('/api/ubigeo/validar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                departamento_id: departamentoId,
                provincia_id: provinciaId,
                distrito_id: distritoId
            })
        });

        const data = await response.json();
        return data.valid;
    } catch (error) {
        console.error('Error validando ubigeo:', error);
        return false;
    }
}

                // Event Listeners
cboDepartamento.addEventListener('change', async (e) => {
    await cargarProvincias(e.target.value);
    cboDistrito.innerHTML = '<option value="">- Seleccione -</option>';
});

cboProvincia.addEventListener('change', async (e) => {
    await cargarDistritos(e.target.value);
});

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const departamentoId = cboDepartamento.value;
    const provinciaId = cboProvincia.value;
    const distritoId = cboDistrito.value;

                    // Validación frontend básica
    if (!departamentoId || !provinciaId || !distritoId) {
        alert('Por favor complete todos los campos del ubigeo');
        return;
    }

                    // Validación con el backend
    const esValido = await validarUbigeo(departamentoId, provinciaId, distritoId);

    if (esValido) {
        form.submit();
    } else {
        alert('La combinación de departamento, provincia y distrito no es válida');
    }
});

                // Inicialización
document.addEventListener('DOMContentLoaded', async () => {
    await cargarDepartamentos();

                    // Opcional: Seleccionar Lima por defecto
    cboDepartamento.value = '15';
    await cargarProvincias('15');

    setTimeout(async () => {
        cboProvincia.value = '1501';
        await cargarDistritos('1501');
    }, 100);
});

document.addEventListener('DOMContentLoaded', function() {
    const nombresInput = document.getElementById('grid-nombres-razon-social');
    const errorMessage = document.getElementById('error-nombres-razon-social');

    function validarNombres(event) {
        let cursorPos = nombresInput.selectionStart;
        let valor = nombresInput.value.toUpperCase();

                                        // Permite solo letras, espacios intermedios y caracteres específicos
        let valorFiltrado = valor.replace(/[^A-ZÁÉÍÓÚÜÑ\s.,&-]/g, '');

                                        // Evita caracteres especiales consecutivos como ".." o "&&"
        if (/[\s.,&-]{2,}/.test(valorFiltrado)) {
            errorMessage.textContent = "No se permiten caracteres especiales consecutivos.";
            nombresInput.classList.add('border-red-500');
            errorMessage.classList.remove('hidden');
            return;
        }

                                        // No permite que el nombre inicie con un carácter especial
        if (/^[.,&-\s]/.test(valorFiltrado)) {
            errorMessage.textContent = "Debe comenzar con una letra.";
            nombresInput.classList.add('border-red-500');
            errorMessage.classList.remove('hidden');
            return;
        }

                                        // Validación de longitud máxima
        if (valorFiltrado.length > 40) {
            errorMessage.textContent = "El campo no puede exceder los 40 caracteres.";
            nombresInput.classList.add('border-red-500');
            errorMessage.classList.remove('hidden');
            return;
        }

                                        // Si todo está bien, oculta el mensaje de error
        errorMessage.classList.add('hidden');
        nombresInput.classList.remove('border-red-500');

                                        // Solo actualiza el valor si cambió
        if (nombresInput.value !== valorFiltrado) {
            nombresInput.value = valorFiltrado;
            nombresInput.setSelectionRange(cursorPos, cursorPos);
        }
    }

    nombresInput.addEventListener('input', validarNombres);
    document.querySelector('form').addEventListener('submit', function(event) {
        validarNombres();
        if (!errorMessage.classList.contains('hidden')) {
                                            event.preventDefault(); // Evita el envío si hay error
                                        }
                                    });
});




const tipoDocumentoInputs = document.querySelectorAll('input[name="tipo_documento"]');
const documentoIdentidadInput = document.getElementById('grid-documento-identidad');
const errorMessage = document.getElementById('error-documento-identidad');

                                // Configuración de validaciones por tipo de documento
const validaciones = {
    DNI: {
        regex: /^\d{8}$/,
        max: 8,
        mensaje: "El DNI debe tener exactamente 8 dígitos."
    },
    LM: {
        regex: /^\d{9}$/,
        max: 9,
        mensaje: "La Libreta Militar debe tener exactamente 9 dígitos."
    },
    RUC: {
        regex: /^\d{11}$/,
        max: 11,
        mensaje: "El RUC debe tener exactamente 11 dígitos."
    },
    CE: {
        regex: /^\d{9}$/,
        max: 9,
        mensaje: "El Carné de Extranjería debe tener exactamente 9 dígitos."
    },
    OTRO: {
        regex: /^[A-Za-z0-9]{1,15}$/,
        max: 15,
        mensaje: "El documento debe tener entre 1 y 15 caracteres alfanuméricos."
    }
};
                                // Función para validar la entrada
function validateDocumentoIdentidad(value, tipoDocumento) {
    const {
        regex,
        mensaje
    } = validaciones[tipoDocumento] || {};
    const isValid = regex ? regex.test(value) : false;

    if (!isValid) {
        errorMessage.textContent = mensaje;
        documentoIdentidadInput.classList.add('border-red-500');
        errorMessage.classList.remove('hidden');
    } else {
        errorMessage.classList.add('hidden');
        documentoIdentidadInput.classList.remove('border-red-500');
    }

    return isValid;
}

                                // Función para restringir la entrada de caracteres y longitud
function enforceInputRestrictions(e) {
    const tipoDocumento = document.querySelector('input[name="tipo_documento"]:checked').value;
    const {
        max
    } = validaciones[tipoDocumento];

    if (tipoDocumento === "OTRO") {
                                        // Permitir solo caracteres alfanuméricos
        e.target.value = e.target.value.replace(/[^A-Za-z0-9]/g, '');
    } else {
                                        // Permitir solo números
        e.target.value = e.target.value.replace(/\D/g, '');
    }

                                    // Limitar la cantidad de caracteres al máximo permitido
    if (e.target.value.length > max) {
        e.target.value = e.target.value.slice(0, max);
    }

    validateDocumentoIdentidad(e.target.value, tipoDocumento);
}

                                // Evento para validar en tiempo real mientras el usuario escribe
documentoIdentidadInput.addEventListener('input', enforceInputRestrictions);

                                // Evento para limpiar y ajustar restricciones al cambiar el tipo de documento
tipoDocumentoInputs.forEach(input => {
    input.addEventListener('change', function() {
                                        documentoIdentidadInput.value = ''; // Limpiar el campo
                                        enforceInputRestrictions({
                                            target: documentoIdentidadInput
                                        }); // Aplicar restricciones
                                    });
});

                                // Validación al enviar el formulario
document.querySelector('form').addEventListener('submit', function(event) {
    const tipoDocumento = document.querySelector('input[name="tipo_documento"]:checked').value;
    const documentoIdentidad = documentoIdentidadInput.value;

    if (!validateDocumentoIdentidad(documentoIdentidad, tipoDocumento)) {
                                        event.preventDefault(); // Evitar el envío del formulario
                                    }
                                });

document.querySelector('form').addEventListener('submit', function(event) {
    const selectedSexo = document.querySelector('input[name="sexo"]:checked').value;
    const errorMessage = document.getElementById('error-sexo');

    if (selectedSexo === "N") {
        errorMessage.classList.remove('hidden');
                                        event.preventDefault(); // Evita el envío del formulario
                                    } else {
                                        errorMessage.classList.add('hidden');
                                    }
                                });


document.addEventListener('DOMContentLoaded', function() {
    const edadInput = document.getElementById('grid-edad');
    const errorMessage = document.getElementById('error-edad');

    function validarEdad() {
                                        let valor = edadInput.value.replace(/\D/g, ''); // Eliminar caracteres no numéricos
                                        edadInput.value = valor.slice(0, 3); // Limitar a 3 dígitos

                                        const edad = parseInt(edadInput.value, 10);

                                        if (edadInput.value === "" || isNaN(edad) || edad < 0 || edad > 120) {
                                            errorMessage.classList.remove('hidden'); // Mostrar mensaje de error
                                            edadInput.classList.add('border-red-500'); // Agregar borde rojo
                                        } else {
                                            errorMessage.classList.add('hidden'); // Ocultar mensaje de error
                                            edadInput.classList.remove('border-red-500'); // Remover borde rojo
                                        }
                                    }

                                    edadInput.addEventListener('input', validarEdad);

                                    document.querySelector('form').addEventListener('submit', function(event) {
                                        validarEdad();
                                        if (!errorMessage.classList.contains('hidden')) {
                                            event.preventDefault(); // Evitar el envío del formulario si hay errores
                                        }
                                    });
                                });
document.querySelector('form').addEventListener('submit', function(event) {
    const selectElement = document.getElementById('grid-autoidentificacion-etnica');
    const errorMessage = document.getElementById('error-autoidentificacion-etnica');

    if (selectElement.value === "") {
                                        selectElement.classList.add('border-red-500'); // Resalta el borde en rojo
                                        errorMessage.classList.remove('hidden'); // Muestra el mensaje de error
                                        event.preventDefault(); // Evita el envío del formulario
                                    } else {
                                        selectElement.classList.remove('border-red-500'); // Quita el resaltado
                                        errorMessage.classList.add('hidden'); // Oculta el mensaje de error
                                    }
                                });

document.querySelector('form').addEventListener('submit', function(event) {
    const discapacidad = document.querySelector('input[name="discapacidad"]:checked');
    const errorMessage = document.getElementById('error-discapacidad');

    if (!discapacidad) {
                                        errorMessage.classList.remove('hidden'); // Muestra el mensaje de error
                                        event.preventDefault(); // Evita el envío del formulario
                                    } else {
                                        errorMessage.classList.add('hidden'); // Oculta el mensaje de error
                                    }
                                });


const inputLengua = document.getElementById('grid-lengua-materna');
const errorMsg = document.getElementById('error-lengua-materna');

                                // Bloquear caracteres inválidos al escribir y convertir a mayúsculas automáticamente
inputLengua.addEventListener('keypress', function(e) {
    const char = String.fromCharCode(e.keyCode).toUpperCase();
    if (!/^[A-ZÁÉÍÓÚÑ]$/.test(char)) {
        e.preventDefault();
    }
});

                                // Filtrar caracteres inválidos y convertir todo a mayúsculas en tiempo real
inputLengua.addEventListener('input', function(e) {
                                    let value = e.target.value.toUpperCase(); // Convertir a mayúsculas automáticamente

                                    // Filtrar caracteres inválidos
                                    value = value.replace(/[^A-ZÁÉÍÓÚÑ]/g, '').substring(0, 15);

                                    // Aplicar cambios al campo
                                    e.target.value = value;

                                    // Validación de error
                                    if (value === '') {
                                        showError("Este campo es obligatorio.");
                                    } else {
                                        hideError();
                                    }
                                });

function showError(message) {
    errorMsg.textContent = message;
    errorMsg.classList.remove('hidden');
    inputLengua.classList.add('border-red-500');
}

function hideError() {
    errorMsg.classList.add('hidden');
    inputLengua.classList.remove('border-red-500');
}

                                // Evitar el envío del formulario si hay errores
document.querySelector('form').addEventListener('submit', function(event) {
    if (!/^[A-ZÁÉÍÓÚÑ]{1,15}$/.test(inputLengua.value)) {
        showError("Solo se permite una palabra de máximo 15 letras, sin números ni caracteres especiales.");
        event.preventDefault();
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('grid-area-geografica');
    const errorMessage = document.getElementById('error-area-geografica');

    input.addEventListener('input', function(e) {
        let value = e.target.value;

                                        // Convertir a mayúsculas
        value = value.toUpperCase();

                                        // Eliminar caracteres no permitidos (solo letras, guion "-" y espacio)
        value = value.replace(/[^A-ZÁÉÍÓÚÑ\s-]/g, '');

                                        // Asegurar que solo haya un espacio entre palabras
                                        value = value.replace(/\s{2,}/g, ' '); // Reemplaza múltiples espacios con uno solo

                                        // Limitar a 25 caracteres
                                        value = value.substring(0, 25);

                                        // Evitar espacios al inicio o al final
                                        value = value.trimStart();

                                        // Aplicar cambios al campo
                                        e.target.value = value;

                                        // Contar palabras válidas (mínimo 4 letras)
                                        let words = value.split(" ").filter(word => word.length >= 4);

                                        // Validaciones en tiempo real
                                        if (value === '') {
                                            showError("Este campo es obligatorio.");
                                        } else if (words.length > 2) {
                                            showError("Solo se permiten hasta 2 palabras de mínimo 4 letras cada una.");
                                        } else {
                                            hideError();
                                        }
                                    });

    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.classList.remove('hidden');
        input.classList.add('border-red-500');
    }

    function hideError() {
        errorMessage.classList.add('hidden');
        input.classList.remove('border-red-500');
    }

                                    // Evitar el envío del formulario si hay errores
    document.querySelector('form').addEventListener('submit', function(event) {
        if (!errorMessage.classList.contains('hidden')) {
            event.preventDefault();
        }
    });
});



$(document).ready(function() {
            // Cargar provincias cuando se selecciona un departamento
    $('#cboDepartamento').change(function() {
        const departamentoId = $(this).val();
        if (departamentoId) {
            $.ajax({
                url: "{{ route('get.provincias') }}",
                method: 'GET',
                data: {
                    departamento_id: departamentoId
                },
                success: function(data) {
                    $('#cboProvincia').html(data);
                    $('#cboDistrito').html('<option value=""> - Seleccione- </option>');
                }
            });
        } else {
            $('#cboProvincia').html('<option value=""> - Seleccione- </option>');
            $('#cboDistrito').html('<option value=""> - Seleccione- </option>');
        }
    });

            // Cargar distritos cuando se selecciona una provincia
    $('#cboProvincia').change(function() {
        const provinciaId = $(this).val();
        if (provinciaId) {
            $.ajax({
                url: "{{ route('get.distritos') }}",
                method: 'GET',
                data: {
                    provincia_id: provinciaId
                },
                success: function(data) {
                    $('#cboDistrito').html(data);
                }
            });
        } else {
            $('#cboDistrito').html('<option value=""> - Seleccione- </option>');
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
            // Limpiar el almacenamiento de sesión al cargar el formulario
    if (performance.navigation.type === 2) {
                // Si viene de atrás/navegación
                window.location.reload(true); // Recarga forzada del servidor
            }

            // Limpiar campos si hay un indicador de recarga
            if (sessionStorage.getItem('form_reloaded') === 'true') {
                document.querySelector('form').reset();
                sessionStorage.removeItem('form_reloaded');
            }
        });

window.addEventListener('beforeunload', function() {
            // Marcar que la página se está recargando
    sessionStorage.setItem('form_reloaded', 'true');
});


document.querySelector('form').addEventListener('submit', function(event) {
    let hasError = false;

    function validarCampoTexto(idCampo, idError) {
        const campo = document.getElementById(idCampo);
        const error = document.getElementById(idError);

                        if (!campo.value.trim()) { // Evita campos vacíos o con solo espacios
                            campo.classList.add('border-red-500');
                            error.classList.remove('hidden');
                            hasError = true;
                        } else {
                            campo.classList.remove('border-red-500');
                            error.classList.add('hidden');
                        }
                    }

                    function validarSeleccion(idCampo, idError) {
                        const campo = document.getElementById(idCampo);
                        const error = document.getElementById(idError);

                        if (!campo.value) { // Evita valores vacíos en el <select>
                            campo.classList.add('border-red-500');
                            error.classList.remove('hidden');
                            hasError = true;
                        } else {
                            campo.classList.remove('border-red-500');
                            error.classList.add('hidden');
                        }
                    }

                    // Validar los campos
                    validarCampoTexto('grid-informacion-solicitada', 'error-informacion-solicitada');
                    validarSeleccion('grid-area-id', 'error-area-id');
                    validarCampoTexto('grid-observaciones', 'error-observaciones');

                    if (hasError) {
                        event.preventDefault();
                        alert('Por favor, complete todos los campos obligatorios antes de enviar el formulario.');
                    }
                });

document.getElementById('submitBtn').addEventListener('click', function(e) {
                    // Bloquear el botón
    this.disabled = true;

                    // Cambiar el texto y apariencia (opcional)
    this.innerHTML = `
                        <i class="ki-filled ki-loading"></i>
                        <span class="ml-2">Procesando...</span>
    `;

                    // Opcional: También puedes cambiar las clases para un estilo diferente
    this.classList.remove('hover:bg-blue-700');
    this.classList.add('bg-gray-400', 'cursor-not-allowed');

                    // Continuar con el envío del formulario
    this.form.submit();
});





function validarEntrada(input, errorElement, maxLength) {
                            const regex = /^[A-Z0-9\s\/\-\.]+$/; // Solo letras, números, espacios y / - .
                            let valor = input.value.toUpperCase(); // Convertir a mayúsculas

                            // Remover caracteres inválidos
                            if (!regex.test(valor) && valor !== "") {
                                valor = valor.replace(/[^A-Z0-9\s\/\-\.]/g, ''); // Eliminar caracteres inválidos
                                errorElement.textContent = "Solo se permiten letras, números y los caracteres / - .";
                                errorElement.classList.remove("hidden");
                                input.classList.add("border-red-500");
                            } else if (valor.length > maxLength) {
                                errorElement.textContent = `Máximo ${maxLength} caracteres permitidos.`;
                                errorElement.classList.remove("hidden");
                                input.classList.add("border-red-500");
                            } else {
                                errorElement.classList.add("hidden");
                                input.classList.remove("border-red-500");
                            }

                            input.value = valor; // Aplicar transformación corregida


                            function validarCorreo() {
                                const correo = document.getElementById("grid-correo-electronico");
                                const errorCorreo = document.getElementById("error-correo-electronico");

                            // Expresión regular que permite solo caracteres válidos en emails
                                const regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                            // Elimina caracteres inválidos (espacios y otros no permitidos)
                                correo.value = correo.value.replace(/[^a-zA-Z0-9._%+-@]/g, '');

                                if (!regexCorreo.test(correo.value)) {
                                    errorCorreo.textContent = "Ingrese un correo electrónico válido.";
                                    errorCorreo.classList.remove("hidden");
                                    correo.classList.add("border-red-500");
                                } else {
                                    errorCorreo.classList.add("hidden");
                                    correo.classList.remove("border-red-500");
                                }
                            }
                            document.getElementById("grid-correo-electronico").addEventListener("input", validarCorreo);

                            function validarTelefono() {
                                const telefono = document.getElementById("grid-telefono");
                                const errorTelefono = document.getElementById("error-telefono");

                            // Solo números, exactamente 9 dígitos
                                const regexTelefono = /^[0-9]{9}$/;
                            let valor = telefono.value.replace(/\D/g, ''); // Elimina caracteres no numéricos
                            telefono.value = valor; // Aplica solo los números

                            if (!regexTelefono.test(valor)) {
                                errorTelefono.textContent = "El teléfono debe contener exactamente 9 dígitos numéricos.";
                                errorTelefono.classList.remove("hidden");
                                telefono.classList.add("border-red-500");
                            } else {
                                errorTelefono.classList.add("hidden");
                                telefono.classList.remove("border-red-500");
                            }
                        }

                        // Evitar que se escriban espacios en el correo en tiempo real
                        document.getElementById("grid-correo-electronico").addEventListener("input", function(event) {
                            this.value = this.value.replace(/\s/g, ''); // Elimina espacios al escribir
                            validarCorreo();
                        });

                        document.getElementById("grid-telefono").addEventListener("input", validarTelefono);

                        // Validación al enviar el formulario
                        document.querySelector("form").addEventListener("submit", function(event) {
                            validarCorreo();
                            validarTelefono();

                            const errorCorreo = document.getElementById("error-correo-electronico");
                            const errorTelefono = document.getElementById("error-telefono");

                            if (!errorCorreo.classList.contains("hidden") || !errorTelefono.classList.contains("hidden")) {
                                event.preventDefault();
                                alert("Corrija los errores en los campos antes de enviar el formulario.");
                            }
                        });

                        document.querySelector('form').addEventListener('submit', function(event) {
                            const departamento = document.getElementById('cboDepartamento');
                            const provincia = document.getElementById('cboProvincia');
                            const distrito = document.getElementById('cboDistrito');
                            const correoElectronico = document.getElementById('grid-correo-electronico');
                            const telefono = document.getElementById('grid-telefono');
                            const errorDepartamento = document.getElementById('error-departamento');
                            const errorProvincia = document.getElementById('error-provincia');
                            const errorDistrito = document.getElementById('error-distrito');
                            const errorCorreo = document.getElementById('error-correo-electronico');
                            const errorTelefono = document.getElementById('error-telefono');

                            // Validar departamento
                            if (!departamento.value) {
                                departamento.classList.add('border-red-500');
                                errorDepartamento.classList.remove('hidden');
                                event.preventDefault();
                            } else {
                                departamento.classList.remove('border-red-500');
                                errorDepartamento.classList.add('hidden');
                            }

                            // Validar provincia
                            if (!provincia.value) {
                                provincia.classList.add('border-red-500');
                                errorProvincia.classList.remove('hidden');
                                event.preventDefault();
                            } else {
                                provincia.classList.remove('border-red-500');
                                errorProvincia.classList.add('hidden');
                            }

                            // Validar distrito
                            if (!distrito.value) {
                                distrito.classList.add('border-red-500');
                                errorDistrito.classList.remove('hidden');
                                event.preventDefault();
                            } else {
                                distrito.classList.remove('border-red-500');
                                errorDistrito.classList.add('hidden');
                            }

                            // Validar correo electrónico
                            if (!correoElectronico.value || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correoElectronico.value)) {
                                correoElectronico.classList.add('border-red-500');
                                errorCorreo.textContent = "Por favor, ingrese un correo electrónico válido.";
                                errorCorreo.classList.remove('hidden');
                                event.preventDefault();
                            } else {
                                correoElectronico.classList.remove('border-red-500');
                                errorCorreo.classList.add('hidden');
                            }

                            // Validar teléfono
                            if (!telefono.value || !/^\d{9}$/.test(telefono.value)) {
                                telefono.classList.add('border-red-500');
                                errorTelefono.textContent = "Por favor, ingrese un teléfono válido (9 dígitos).";
                                errorTelefono.classList.remove('hidden');
                                event.preventDefault();
                            } else {
                                telefono.classList.remove('border-red-500');
                                errorTelefono.classList.add('hidden');
                            }
                        });

                        document.addEventListener("DOMContentLoaded", function() {
                            const areaSelect = document.getElementById("grid-area-id");
                            const errorMessage = document.getElementById("error-area-id");

                            function validarSeleccion() {
                                if (areaSelect.value === "") {
                                    areaSelect.value = [...areaSelect.options].find(option =>
                                        option.textContent.trim() === "HOSPITAL NACIONAL DANIEL ALCIDES CARRION"
                                        )?.value || "";
                                }

                                if (areaSelect.value === "") {
                                    errorMessage.classList.remove("hidden");
                                } else {
                                    errorMessage.classList.add("hidden");
                                }
                            }

                    // Validación cuando cambia la selección
                            areaSelect.addEventListener("change", validarSeleccion);

                    // Validación antes de enviar el formulario
                            document.querySelector("form").addEventListener("submit", function(event) {
                                validarSeleccion();
                                if (areaSelect.value === "") {
                            event.preventDefault(); // Evita el envío si sigue vacío
                        }
                    });

                    // Validación inicial en caso de que el usuario no seleccione nada
                            validarSeleccion();
                        });

                        

                        

                        
                    }

                        // Eventos en tiempo real para validar
                    document.getElementById("grid-domicilio").addEventListener("input", function() {
                        validarEntrada(this, document.getElementById("error-domicilio"), 100);
                    });

                    document.getElementById("grid-numero-urbanizacion").addEventListener("input", function() {
                        validarEntrada(this, document.getElementById("error-numero-urbanizacion"), 20);
                    });

                        // Validación al enviar el formulario
                    document.querySelector("form").addEventListener("submit", function(event) {
                        const domicilio = document.getElementById("grid-domicilio");
                        const numeroUrbanizacion = document.getElementById("grid-numero-urbanizacion");

                        if (domicilio.value.trim() === "" || numeroUrbanizacion.value.trim() === "") {
                            event.preventDefault();
                            alert("Por favor, complete todos los campos obligatorios.");
                        }
                    });
                    
                });
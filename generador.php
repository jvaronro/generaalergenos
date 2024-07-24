<!DOCTYPE html>
<html>
<head>
    <title>Seleccionar alérgenos</title> <!-- Título de la página --> 
    <link rel="stylesheet" type="text/css" href="estilogenera.css"> <!-- Enlace al archivo CSS de estilo -->
    <!-- Incluye la biblioteca QRCode.js -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
</head>
<body>
    <div class="container">
        <h4>El proyecto ha sido desarrollado como parte del Trabajo de Fin de Grado en Ingeniería Informática de UNIR</h4> <!-- Aviso UNIR -->
        <h1>Seleccionar alérgenos</h1> <!-- Título principal -->
        
        <!-- Formulario para seleccionar alérgenos creando un checkbox por cada alérgeno, al seleccionar lo toma el valor del carácter -->
        <form method="POST" action="">
            <label><input type="checkbox" name="alergenos[]" value="a"> Cereales con gluten</label>
            <label><input type="checkbox" name="alergenos[]" value="b"> Crustáceos</label>
            <label><input type="checkbox" name="alergenos[]" value="c"> Huevo</label>
            <label><input type="checkbox" name="alergenos[]" value="d"> Pescado</label>
            <label><input type="checkbox" name="alergenos[]" value="e"> Cacahuetes</label>
            <label><input type="checkbox" name="alergenos[]" value="f"> Soja</label>
            <label><input type="checkbox" name="alergenos[]" value="g"> Leche</label>
            <label><input type="checkbox" name="alergenos[]" value="h"> Frutos de cáscara</label>
            <label><input type="checkbox" name="alergenos[]" value="i"> Apio</label>
            <label><input type="checkbox" name="alergenos[]" value="j"> Mostaza</label>
            <label><input type="checkbox" name="alergenos[]" value="k"> Granos de sésamo</label>
            <label><input type="checkbox" name="alergenos[]" value="l"> Dióxido de Azufre y Sulfitos</label>
            <label><input type="checkbox" name="alergenos[]" value="m"> Altramuces</label>
            <label><input type="checkbox" name="alergenos[]" value="n"> Moluscos</label>
            <input type="submit" value="Generar URL"> <!-- Creamos el botón para enviar la información del formulario -->
        </form>

        <?php
        // Este código se encarga de procesar los alérgenos seleccionados en el formulario, generar una URL con esos alérgenos  
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['alergenos'])) { //se asegura que se ha seleccionado algún alérgeno, si no no sigue, se queda esperando
                $alergenosseleccionados = $_POST['alergenos'];

                $resultado = ''; // Se inicializa la variable $resultado como una cadena vacía, se utilizará para almacenar los alérgenos seleccionados concatenados

                $urlgenerada = 'https://www.alergenosdejorge.com/procesa.php?alergenos='; // Url inicial sin alérgenos, se le añadirán después del proceso
                foreach ($alergenosseleccionados as $alergeno) {
                    $resultado .= $alergeno;
                    $urlgenerada .= $alergeno;
                }
                // con la URL generada, ya podemos usar los botones de copiar URL, generar el código QR o generar un código QR sin alérgenos 
                echo '<div class="resultado">';
                echo '<div>Seleccionado: ' . $resultado . '</div>';
                echo '<div class="url-generada">';
                echo 'URL Generada: <input type="text" value="' . $urlgenerada . '" id="urlGeneradaInput" readonly>';
                echo '<div class="button-container">';
                echo '<button onclick="copyURL()" class="green-button">Copiar URL</button>'; // botón copiar URL
                echo '<button onclick="handleGenerateQR()" class="green-button">Generar QR</button>'; // botón generar QR
                echo '<button onclick="generateQRSinAlergenos()" class="green-button">Generar QR SIN alérgenos</button>'; // botón generar QR sin alérgenos
                echo '</div>';
                echo '</div>';
                echo '<div class="alerta" id="alertaCopia">URL copiada al portapapeles</div>'; // aviso de URL copiado al portapapeles
                echo '<div class="alerta" id="alertaQR"></div>'; // alerta creada cuando se genera sin alérgenos
                echo '<div id="codigoQR"></div>';
                echo '</div>';
            }
        }
        ?>

        <script>
            function copyURL() {
                var urlGeneradaInput = document.getElementById('urlGeneradaInput'); // busca y selecciona el elemento HTML con el identificador 'urlGeneradaInput'
                urlGeneradaInput.select(); // selecciona el contenido del campo de entrada de texto, para copiar URL
                urlGeneradaInput.setSelectionRange(0, 99999); // permite selección completa del texto para dispositivos móviles
                document.execCommand('copy'); // ejecuta el comando de copiar
                var alertaCopia = document.getElementById('alertaCopia'); // mensaje de alerta que se muestra cuando la URL se copia correctamente
                alertaCopia.style.display = 'block'; // Cambia la propiedad 'display' a 'block', lo que hace que el elemento sea visible en la página.
                setTimeout(function() { // temporizador para ocultar el aviso de copiado
                    alertaCopia.style.display = 'none';
                }, 2000);
            }

            function handleGenerateQR() {
                var urlGeneradaInput = document.getElementById('urlGeneradaInput');
                var urlGenerada = urlGeneradaInput.value; // almacena la URL generada

                // Obtiene los checkboxes seleccionados
                var checkboxes = document.querySelectorAll('input[name="alergenos[]"]:checked');
                
                // Si hay checkboxes seleccionados
                if (checkboxes.length > 0) {
                    // Borra el contenido del campo de texto
                    urlGeneradaInput.value = '';

                    // Genera la URL basada en los checkboxes seleccionados
                    urlGenerada = 'https://www.alergenosdejorge.com/procesa.php?alergenos=';
                    checkboxes.forEach(function(checkbox) {
                        urlGenerada += checkbox.value;
                    });

                    // Muestra la URL generada en el campo de texto
                    urlGeneradaInput.value = urlGenerada;
                }
                
                // Genera el código QR basado en la URL (ya sea nueva o existente)
                generateQR(urlGenerada);
            }

            function generateQR(urlGenerada) { // genera código QR de una URL generada
                var codigoQRDiv = document.getElementById('codigoQR'); // la usaremos como contenedor para mostrar el código QR
                var alertaQR = document.getElementById('alertaQR'); // se utilizará para mostrar un mensaje de alerta relacionado con la generación del código QR
                codigoQRDiv.innerHTML = ''; // inicializamos
                alertaQR.innerHTML = ''; // inicializamos 

                QRCode.toDataURL(urlGenerada, function (err, url) {
                    if (err) {
                        alertaQR.style.display = 'block'; // cambia la propiedad display a 'block', lo que hace que el elemento sea visible en la página
                        alertaQR.innerHTML = 'Error al generar el código QR'; // mensaje que indica que hubo un error
                        alertaQR.style.backgroundColor = 'red';
                        alertaQR.style.color = '#ffffff';
                        return;
                    }
                    var qrCodeImg = document.createElement('img'); // almacenamos la imagen QR
                    qrCodeImg.src = url; // fuente de la imagen anterior 
                    qrCodeImg.alt = 'Código QR'; // proporciona un texto alternativo que se muestra si la imagen no se carga correctamente
                    codigoQRDiv.appendChild(qrCodeImg); // inserta la imagen del código QR dentro del contenedor codigoQRDiv

                    alertaQR.style.display = 'block'; // cambia la propiedad display a 'block', lo que hace que el elemento sea visible en la página
                    alertaQR.innerHTML = 'Código QR generado'; // mensaje que indica que el código QR se ha generado
                    alertaQR.style.backgroundColor = '#4CAF50'; // establece el color de fondo
                    alertaQR.style.color = '#ffffff'; // establecemos el color del texto
                });
            }

            function generateQRSinAlergenos() {
                var urlSinAlergenos = 'https://www.alergenosdejorge.com/procesa.php?alergenos='; // URL base para generar el código QR sin alérgenos
                var codigoQRDiv = document.getElementById('codigoQR'); // obtenemos el contenedor del código QR y el elemento de alerta
                var alertaQR = document.getElementById('alertaQR'); 
                codigoQRDiv.innerHTML = ''; // Limpiamos el contenido del contenedor del código QR 
                alertaQR.innerHTML = ''; // Limpiamos el contenido del contenedor de la alerta

                QRCode.toDataURL(urlSinAlergenos, function (err, url) {
                    if (err) {
                        alertaQR.style.display = 'block'; // cambia la propiedad display a 'block', lo que hace que el elemento sea visible en la página
                        alertaQR.innerHTML = 'Error al generar el código QR'; // mensaje que indica que hubo un error
                        alertaQR.style.backgroundColor = 'red';
                        alertaQR.style.color = '#ffffff';
                        return;
                    }
                    var qrCodeImg = document.createElement('img'); // Creamos un elemento de imagen para mostrar el código QR sin alérgenos
                    qrCodeImg.src = url;
                    qrCodeImg.alt = 'Código QR sin alérgenos';
                    codigoQRDiv.appendChild(qrCodeImg); // Agregamos la imagen del código QR al contenedor

                    alertaQR.style.display = 'block'; // Mostramos la alerta indicando que el código QR sin alérgenos ha sido generado
                    alertaQR.innerHTML = 'Código QR sin alérgenos generado';
                    alertaQR.style.backgroundColor = 'red';
                    alertaQR.style.color = '#ffffff';
                });
            }
        </script>
    </div>
</body>
</html>

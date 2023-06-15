<!-- El proyecto ha sido desarrollado como parte del Trabajo de Fin de Grado en Ingeniería Informática de UNIR -->
<!-- Alumno: Jorge Varón Rodríguez -->

<!DOCTYPE html>
<html>
<head>
    <title>Procesado de alérgenos</title> <!-- título de la página --> 
    <link rel="stylesheet" type="text/css" href="estiloprocesa.css"> <!-- archivo CSS  "estiloprocesa.css" para el formato -->
    

</head>
<body>
    <div class="container"> <!-- definimos el contendor --> 
        <?php
        // Procesamos variable que llega por URL
        $alergenos = $_GET['alergenos'];
        
        // definimos un array llamado "alergenosMensajes" que asociamos códigos de alérgenos con los mensajes asociados
        $alergenosMensajes = [
            'a' => 'Cereales con gluten',
            'b' => 'Crustáceos',
            'c' => 'Huevo',
            'd' => 'Pescado',
            'e' => 'Cacahuetes',
            'f' => 'Soja',
            'g' => 'Leche',
            'h' => 'Frutos de cáscara',
            'i' => 'Apio',
            'j' => 'Mostaza',
            'k' => 'Granos de sésamo',
            'l' => 'Dióxido de azufre y sulfitos',
            'm' => 'Altramuces',
            'n' => 'Moluscos'
        ];

        $contienealergenos = false; // inicializamos la variable a false, para comprobar después si hay o no alérgenos

        if (!empty($alergenos)) { // comprobamos si hay o no alergenos en la variable, si hay aparece la imagen de peligro y el mensaje que contine alergenos
            echo '<img class="peligro-img" src="img/peligro.jpeg" alt="Peligro">';
            echo '<div class="result error">¡Alerta! Este producto contiene alérgenos.</div>';
        } else { // si no hay alergennos, aparece una cara sonriente y el mensaje de que no hay alergenos
            echo '<img class="peligro-img" src="img/sonriente.jpeg" alt="Sonriente">';
            echo '<div class="result success">El producto no contiene alérgenos.</div>';
        }

        // vamos comprobando si el elemento del array está en la cadena de alergenos, si está muestra el mensaje del alergeno
        foreach ($alergenosMensajes as $alergeno => $mensaje) {
            if (strpos($alergenos, $alergeno) !== false) {
                echo '<div class="result success">' . $mensaje . '</div>';
                $contienealergenos = true;
            }
        }

        ?>
    </div>
</body>
</html>

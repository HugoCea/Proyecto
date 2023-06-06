<!DOCTYPE html>
<html>
<head>
    <title>Carpeta Compartida NAS</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f1f1f1;
        margin: 20px;
    }

    h1 {
        color: #333333;
    }

    form {
        margin-bottom: 20px;
    }

    input[type=file] {
        margin-right: 10px;
    }

    a {
        display: block;
        margin-bottom: 10px;
        text-decoration: none;
        color: #333333;
        background-color: #ffffff;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    a:hover {
        background-color: #f1f1f1;
    }
	
    a.folder:hover,
    a.file:hover {
        background-color: #ffff99;
    }
	
	a.back-button:hover {
        background-color: #FF5C5C;
    }
	
    p.success {
        color: #008000;
        font-weight: bold;
    }
    
    .file {
        background-color: #D4E8FF;
    }
    
    .folder {
        background-color: #D9FFD4;
    }
    
    .back-button {
        background-color: #FFD4D4;
        padding: 5px 10px;
        color: #333;
        text-decoration: none;
        display: inline-block;
        border-radius: 3px;
    }
	
	input[type="submit"] {
            width: 16%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

    input[type="submit"]:hover {
        background-color: #45a049;
    }
	
	.corner-image {
        position: fixed;
        top: 10px;
        right: 10px;
        width: 400px;
        height: 80px;
    }
	
	.corner-text {
            position: fixed;
            top: 120px;
            right: 10px;
            font-weight: bold;
        }
	
	.color-box {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            border-radius: 3px;
        }

        .color-green {
            background-color: #D9FFD4;
        }

        .color-blue {
            background-color: #D4E8FF;
		}
	
</style>
</head>
<body>
    <h1>Carpeta de Proyecto</h1>
    <br><br>
    <!-- Formulario para cargar archivos -->
    <form action="/Proyecto/index.php" method="POST" enctype="multipart/form-data">
		<h4>Seleccionar archivo para subir</h4>
        <input type="file" name="file" id="file">
        <input type="submit" name="submit" value="Subir archivo">
		<br><br>
    </form>

	<!-- Formulario para crear una carpeta nueva -->
    <form action="create_folder.php" method="POST" enctype="multipart/form-data">
		<h4>Nueva carpeta</h4>
        <input type="text" name="folderName" placeholder="Nombre de la carpeta">
        <input type="submit" value="Crear carpeta">
		<br><br>
    </form>
	
	
	<!-- Leyenda -->
    <div>
        <span class="color-box color-green"></span>
        <span>Carpetas</span>
    </div>
    <div>
        <span class="color-box color-blue"></span>
        <span>Archivos</span>
    </div>
    <br><br>
	<!-- Imagen en la esquina superior derecha -->
    <img class="corner-image" src="https://www.danielcastelao.org/wp-content/uploads/2019/07/logo-daniel-castelao.png" alt="Imagen de esquina">
   
   <!-- Texto en negrita debajo de la imagen -->
    <p class="corner-text">Hugo Cea Mariño</p>
    
	
<?php
    // Obtener la ruta base del servidor
$basePath = 'C:/xampp/htdocs';

// Ruta de la carpeta a compartir
$sharedFolder = '/NAS';
$folderPath = $basePath . '/NAS'; // Ruta de la carpeta a compartir
// Obtener el directorio actual basado en la URL
$currentDirectory = isset($_GET['path']) ? urldecode($_GET['path']) : '/Viper';

// Ruta completa de la carpeta actual
$currentPath = $basePath . $sharedFolder . $currentDirectory;

// Verificar si se ha enviado un archivo
if (isset($_FILES['file'])) {
    $uploadedFile = $_FILES['file']['tmp_name'];
    $filename = $_FILES['file']['name'];

    // Ruta de destino del archivo
    $uploadPath = $currentPath . '/' . $filename;

    // Mover el archivo a la carpeta de destino
    move_uploaded_file($uploadedFile, $uploadPath);

    // Mensaje de éxito
    echo "<p class='success'>Archivo subido exitosamente.</p>";
	echo $currentPath. '<br>';
	echo $uploadPath. '<br>';
    // Redireccionar a la página principal
    header("Refresh: 3; url=/Proyecto/index.php");
    exit();
}
	echo $currentDirectory . '<br>';
	echo $folderPath . '<br>';
	echo $basePath. '<br>';
	echo $currentPath. '<br>';
    // Obtener la ruta actual
    $currentPath = isset($_GET['path']) ? $_GET['path'] : '';
    
    // Construir la ruta completa
    $fullPath = $folderPath . '/' . $currentPath;
    
    // Verificar si la ruta es una carpeta
    if (is_dir($fullPath)) {
        // Obtener la lista de archivos y carpetas en la carpeta
        $entries = array_diff(scandir($fullPath), ['.', '..']);
        
        // Mostrar el botón de retroceso si no estamos en la carpeta principal
        if ($currentPath !== '') {
            // Obtener la ruta de la carpeta anterior
            $parentPath = dirname($currentPath);
            
        }

        // Verificar si se ha enviado la acción de eliminación
        if (isset($_POST['delete'])) {
            $filesToDelete = $_POST['delete'];

            foreach ($filesToDelete as $itemToDelete) {
                $deletePath = $fullPath . '/' . $itemToDelete;

                // Verificar si el archivo existe antes de eliminarlo
                if (is_file($deletePath)) {
                    // Eliminar el archivo
                    if (unlink($deletePath)) {
                        // Mensaje de éxito
                        echo "<p class='success'>Archivo '$itemToDelete' eliminado exitosamente.</p>";
                    } else {
                        // Mostrar mensaje de error si no se pudo eliminar el archivo
                        echo "<p class='error'>No se pudo eliminar el archivo '$itemToDelete'.</p>";
                    }
                } elseif (is_dir($deletePath)) {
                    // Es una carpeta
                    if (removeDirectory($deletePath)) {
                        // Mensaje de éxito
                        echo "<p class='success'>Carpeta '$itemToDelete' y su contenido eliminados exitosamente.</p>";
                    } else {
                        // Mostrar mensaje de error si no se pudo eliminar la carpeta
                        echo "<p class='error'>No se pudo eliminar la carpeta '$itemToDelete' y su contenido.</p>";
                    }
                }
            }
            // Redireccionar a la página principal
			header("Refresh: 3; url=/Proyecto/index.php");
            exit();
        }
		
		// Ordenar las entradas por orden alfabético, dando prioridad a las carpetas
        usort($entries, function ($a, $b) use ($fullPath) {
            $pathA = $fullPath . '/' . $a;
            $pathB = $fullPath . '/' . $b;

            $isDirectoryA = is_dir($pathA);
            $isDirectoryB = is_dir($pathB);

            if ($isDirectoryA && !$isDirectoryB) {
                return -1; // $a es una carpeta, $b es un archivo
            } elseif (!$isDirectoryA && $isDirectoryB) {
                return 1; // $a es un archivo, $b es una carpeta
            } else {
                // Ambos son carpetas o ambos son archivos, ordenar alfabéticamente
                return strcasecmp($a, $b);
            }
        });

        // Mostrar los archivos y carpetas como enlaces para descarga o acceso
        echo "<form action='/Proyecto/index.php?path=" . urlencode($currentPath) . "' method='POST'>";
        
        foreach ($entries as $entry) {
            $entryPath = $fullPath . '/' . $entry;
            
            if (is_file($entryPath)) {
                // Es un archivo
                $fileUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/NAS/' . ltrim($currentPath . '/' . $entry, '/');
                echo " <a href='$fileUrl' class='file' download><input type='checkbox' name='delete[]' value='$entry'>$entry</a><br>";
            } else if (is_dir($entryPath)) {
                // Es una carpeta
                $folderUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?path=' . urlencode($currentPath . '/' . $entry);
                echo "<a href='$folderUrl' class='folder'><input type='checkbox' name='delete[]' value='$entry/'>$entry</a><br>";
            }
        }
        echo "<br>";
        echo "<input type='submit' value='Eliminar archivos seleccionados'>";
        echo "</form>";

    } else {
        // Mostrar mensaje de error si la ruta no es una carpeta válida
        echo "<p class='error'>La carpeta seleccionada no existe.</p>";
    }
	
	// Función para eliminar una carpeta y su contenido
    function removeDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            
            if (!removeDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        
        return rmdir($dir);
    }
	
	
    ?>
	<!-- Botón de retroceso al final de la página -->
    <?php
    if ($currentPath !== '') {
		$parentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?path=' . urlencode($parentPath);
        echo "<a href='$parentUrl' class='back-button'>&larr; Retroceder</a>";
    }
    ?>
	

</body>
</html>

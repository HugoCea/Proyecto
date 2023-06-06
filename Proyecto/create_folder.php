<?php
$basePath = 'C:/xampp/htdocs'; // Ruta base de la carpeta compartida
$folderPath = $basePath . '/NAS'; // Ruta de la carpeta a compartir

// Obtener la ruta actual
$currentPath = isset($_GET['path']) ? $_GET['path'] : '';

// Obtener el nombre de la carpeta ingresado por el usuario
$folderName = isset($_POST['folderName']) ? $_POST['folderName'] : '';

// Validar que el nombre de la carpeta no esté vacío
if (!empty($folderName)) {
    // Construir la ruta completa de la nueva carpeta
    $newFolderPath = $folderPath . '/' . $currentPath . '/' . $folderName;

    // Crear la carpeta
    if (mkdir($newFolderPath)) {
        echo "<p>Carpeta '$folderName' creada exitosamente.</p>";
    } else {
        echo "<p>Error al crear la carpeta '$folderName'.</p>";
    }
} else {
    echo "<p>El nombre de la carpeta no puede estar vacío.</p>";
}

// Redireccionar a la página principal
echo "<script>window.location.href = '/Proyecto/index.php';</script>";
exit();
?>

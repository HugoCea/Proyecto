<?php
header("Content-Type: text/html;charset=utf-8");


//Pedimos los valores al html
$user=$_REQUEST['user'];
$pswd=$_REQUEST['pswd'];

//Comprobación de los campos en blanco
if ($user == 'admin' && $pswd == 'oracle123') {
	echo "Iniciando sesión";
	echo "<script>window.location.href = '/Proyecto/index.php';</script>";
	exit();
} else {
	echo "Datos incorrectos"; 
	header("Refresh: 3; url=/Proyecto");
	//echo "<script>window.location.href = '/Proyecto';</script>";
	exit();
}

?>

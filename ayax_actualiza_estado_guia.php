<?php
	// carga rutinas de acceso a base de datos
	include "include/header.php";
	$conn=opendblocal();
	//
	$_id = $_REQUEST["p0"];
	$_modo = $_REQUEST["p1"];
	if ($_id!="") {
		$query = "UPDATE guias_iansa SET estado = ".trim($_modo)." WHERE id =".trim($_id).";";
		$result = mysqli_query($conn, $query); 
	}
	return;
?>
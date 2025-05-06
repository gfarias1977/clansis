<?php
	// carga rutinas de acceso a base de datos
	include "include/header.php";
	$conn=opendblocal();
	//
	$_id = $_REQUEST["p0"];
	$_modo = $_REQUEST["p1"];
	if ($_id!="") {
		if ($_modo == 1) {	//desasignar
			$query = "UPDATE cargas_otras_faenas SET estado = 0 WHERE id =".trim($_id).";";
		} else {
			$hoy="'".date("Y-m-d")."'";
			$query = "UPDATE cargas_otras_faenas SET estado = 1, fecha_recepcion = ".trim($hoy)." WHERE id =".trim($_id).";";
		}
		$result = mysqli_query($conn,$query); 
	}
	return;
?>
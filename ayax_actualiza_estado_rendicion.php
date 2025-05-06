<?php
	// carga rutinas de acceso a base de datos
	include "include/header.php";
	$conn=opendblocal();
	//
	$_id = $_REQUEST["p0"];
	$_modo = $_REQUEST["p1"];
	if ($_id!="") {
		if ($_modo == 1) {	
			$query = "UPDATE cargas_otras_faenas SET preselec = 0 WHERE id =".trim($_id).";";
		} else {
			$query = "UPDATE cargas_otras_faenas SET preselec = 1 WHERE id =".trim($_id).";";
		}
		$result = mysqli_query($conn,$query); 
	}
	return;
?>
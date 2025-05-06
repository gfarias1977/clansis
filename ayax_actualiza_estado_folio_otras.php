<?php
	// carga rutinas de acceso a base de datos
	include "include/header.php";
	$conn=opendblocal();
	//
	$_folio = $_REQUEST["p0"];
	$_modo = $_REQUEST["p1"];
	if ($_folio!="") {
		if ($_modo == 1) {	//desasignar
			$query = "UPDATE cargas_otras_faenas SET archivado = 0 WHERE folio =".trim($_folio).";";
		} else {
			$query = "UPDATE cargas_otras_faenas SET archivado = 1 WHERE folio =".trim($_folio).";";
		}
		$result = mysqli_query($conn,$query); 
	}
	return;
?>
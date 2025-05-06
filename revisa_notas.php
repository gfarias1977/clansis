<?php
	// carga rutinas de acceso a base de datos
	include "include/header.php";
	$conn=opendblocal();
	//
	$salida = 0;
	$_id= $_REQUEST["p1"];
	$query = "SELECT A.* FROM guias_iansa A WHERE A.id =".trim($_id);
	$result = mysqli_query($conn,$query); 
	while ($row = mysqli_fetch_array($result))
	{
		if ( (is_null($row["CHEP"])) && (is_null($row["IANSA"])) ) {
			$salida = 0;
		} else {
			$salida = 1;
		}
	}
	mysqli_free_result($result);
	echo $salida;
?>
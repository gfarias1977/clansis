<?php
	// carga rutinas de acceso a base de datos
	include "include/header.php";
	$conn=opendblocal();
	//
	$i = 0;
	$_modo= $_REQUEST["p1"];
	$_patente= $_REQUEST["p2"];
	if ($_modo!=0) {
		$condicion = " WHERE A.estado = ".trim($_modo)." AND A.estadoRC = 0 ";
		//
		$query = "SELECT DISTINCT B.patente, C.id AS 'ID_PATENTE' FROM guias_iansa A LEFT JOIN cargas_iansa B ON A.asignacion_id=B.id LEFT JOIN patentes C ON B.patente=C.patente ".trim($condicion)." ORDER BY B.patente ASC;";
		$result = mysqli_query($conn,$query);
		echo "<OPTION>PATENTE</OPTION>";
		while ($row = mysqli_fetch_array($result))
		{
			if ($_patente==$row["patente"]) {
				echo "<OPTION VALUE=".trim($row["patente"])." selected>".trim($row["patente"])."</OPTION>";
			} else {
				echo "<OPTION VALUE=".trim($row["patente"]).">".trim($row["patente"])."</OPTION>";
			}
		}
		mysqli_free_result($result);
	} else {
		echo "<OPTION value=0>PATENTE</OPTION>";
	}
?>
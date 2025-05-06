<?php
session_start();
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=$_GET[name]");
//
include "include/header.php";
include "include/funciones.php";
$conn=opendblocal();
//$estado=opendb();
date_default_timezone_set("Chile/Continental");	
//
$hoy=trim(date("Y/m/d"));
//
echo "<table>\n";
echo "<tr>\n";
echo "<td>N.Documento</td><td>Descripci&oacute;nn</td><td>Geocerca Origen (Opcional)</td><td>Geocerca Destino (Opcional)</td><td>PATENTE</td><td>Rut Chofer</td><td>Fecha Entrega (YYYY/MM/DD)</td><td>C&oacute;digo Item</td><td>Descripcion Item</td>\n";
echo "</tr>\n";
//
// Imformaci�n base
//
$query = "SELECT A.*,DATE_FORMAT(A.fecha_registro, '%d/%m/%Y') AS 'FechaRegistro' from logisclick A WHERE A.estado = 0 ORDER BY A.documento ASC ";
$result = mysqli_query($conn,$query);
if ($result) {
	while ($row = mysqli_fetch_array($result))
	{
	    $documento = trim($row["documento"]);
	    if (!is_null($row["descripcion"])) {
		    $descripcion = utf8_encode(trim($row["descripcion"]));
	    } else {
		    $descripcion = "";
	    }
		//	    
		echo "<tr>\n";
		echo "<td valign=top>".$documento."</td>\n";
		echo "<td valign=top>".$descripcion."</td>\n";
		echo "<td valign=top></td>\n";
		echo "<td valign=top></td>\n";
		echo "<td valign=top>".trim($row["patente"])."</td>\n";
		echo "<td valign=top>".trim($row["rut_chofer"])."</td>\n";
		echo "<td valign=top>=TEXTO(\"".$hoy."\";\"yyyy/mm/dd\")</td>\n";
		echo "<td valign=top>0</td>\n";
		echo "<td valign=top>0</td>\n";
		echo "</tr>\n";
	}
	mysqli_free_result($result);
}
?>
</table>
</body>
</html>



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
$fec_hoy = FechaActual_Server();
$hor_hoy = HoraActual_Server();
$des_hoy = "[ " . trim($fec_hoy) . " ] [ " . trim($hor_hoy) . " ]";
//
$fec1 = "";
$fec2 = "";
if ($inicio <> "") {
	$fec1 = f_retorna_fecha_mysql( $inicio );
	if ($termino <> "") {
		$fec2 = f_retorna_fecha_mysql( $termino );
	}
}
// 
echo "<table>\n";
echo "<tr>\n";
if ($fec1 != "" && $fec2 != "") {
	echo "<td  colspan=12 align=center>REPORTE INFORMACION ASIGNACIONES - PERIODO: ".trim($inicio)." - ".trim($termino)."</td>";
} else {
	echo "<td  colspan=12 align=center>REPORTE INFORMACION ASIGNACIONES</td>";
}
echo "</tr>\n";
echo "<tr><td colspan=12>&nbsp;</td></tr>\n";
echo "<tr>\n";
echo "<td>GUIA</td><td>DESCRIPCION</td><td>PATENTE</td><td>CHOFER</td><td>CANT.GUIAS</td><td>FAENA</td><td>FECHA REGISTRO</td><td>FECHA RECEPCION</td><td>FECHA RENDICION</td><td>FOLIO</td><td>ARCHIVADO</td><td>ESTADO</td>\n";
echo "</tr>\n";
//
if ($fec1 != "" && $fec2 != "") {
	$query = "select a.*, b.patente as 'des_patente', c.nombre, c.apellidos, d.descripcion as 'des_faena', DATE_FORMAT( a.fecha_registro, '%Y-%m-%d' ) AS 'FechaRegistro', DATE_FORMAT( a.fecha_recepcion, '%Y-%m-%d' ) AS 'FechaRecepcion', DATE_FORMAT( a.fecha_rendicion, '%Y-%m-%d' ) AS 'FechaRendicion' from cargas_otras_faenas a left join patentes b on a.patente=b.id left join choferes c on a.chofer=c.id left join faenas d on a.faena=d.id where ( DATE_FORMAT( a.fecha_registro, '%Y-%m-%d' ) >= ".$fec1." AND DATE_FORMAT( a.fecha_registro, '%Y-%m-%d' ) <= ".$fec2." ) order by a.fecha_registro, a.guia ";
} else {
	$query = "select a.*, b.patente as 'des_patente', c.nombre, c.apellidos, d.descripcion as 'des_faena', DATE_FORMAT( a.fecha_registro, '%Y-%m-%d' ) AS 'FechaRegistro', DATE_FORMAT( a.fecha_recepcion, '%Y-%m-%d' ) AS 'FechaRecepcion', DATE_FORMAT( a.fecha_rendicion, '%Y-%m-%d' ) AS 'FechaRendicion' from cargas_otras_faenas a left join patentes b on a.patente=b.id left join choferes c on a.chofer=c.id left join faenas d on a.faena=d.id order by a.fecha_registro, a.guia ";
}
echo "<tr><td colspan=25>".trim($query)."</td></tr>\n";

$result = mysqli_query($conn,$query);
if ($result) {
	while ($row = mysqli_fetch_array($result))
	{	
		$chofer="";
		if (!is_null($row["nombre"])) {
			$chofer=$chofer . utf8_decode(trim($row["nombre"]));
		}
		if (!is_null($row["apellidos"])) {
			$chofer=$chofer . " " . utf8_decode(trim($row["apellidos"]));
		}
		echo "<tr>\n";
		echo "<td valign=top>".trim($row["guia"])."</td>\n";
		echo "<td valign=top>".utf8_decode(trim($row["descripcion"]))."</td>\n";
		echo "<td valign=top>".trim($row["des_patente"])."</td>\n";
		echo "<td valign=top>".trim($chofer)."</td>\n";
		echo "<td valign=top>".trim($row["cantidad_guias"])."</td>\n";
		echo "<td valign=top>".trim($row["des_faena"])."</td>\n";
		echo "<td valign=top>".trim($row["FechaRegistro"])."</td>\n";
		echo "<td valign=top>".trim($row["FechaRecepcion"])."</td>\n";
		echo "<td valign=top>".trim($row["FechaRendicion"])."</td>\n";
		echo "<td valign=top>".trim($row["folio"])."</td>\n";
		if ($row["archivado"]==0) {
			echo "<td valign=top>NO ARCHIVADO</td>\n";
		} else {
			echo "<td valign=top>ARCHIVADO</td>\n";
		}
		if ($row["estado"]==2) {
			echo "<td valign=top>RENDIDA</td>\n";
		} else {
			if ($row["estado"]==1) {
				echo "<td valign=top>RECEPCIONADA</td>\n";
			} else {
				echo "<td valign=top>&nbsp;</td>\n";
			}
		}
		echo "</tr>\n";
	}
	mysqli_free_result($result);
}
?>
</table>
</body>
</html>



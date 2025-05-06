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
	echo "<td  colspan=26 align=center>REPORTE INFORMACION ASIGNACIONES - PERIODO: ".trim($inicio)." - ".trim($termino)."</td>";
} else {
	echo "<td  colspan=26 align=center>REPORTE INFORMACION ASIGNACIONES</td>";
}
echo "</tr>\n";
echo "<tr><td colspan=26>&nbsp;</td></tr>\n";
echo "<tr>\n";
echo "<td>FECHA</td><td>RUTA</td><td>DESTINATARIO</td><td>DESCRIPCION</td><td>COMUNA</td><td>TOTAL</td><td>TIPO</td><td>TRANSPORTE</td><td>RUT</td><td>CHOFER</td><td>PATENTE</td><td>OC</td><td>NEGOCIO</td><td>ESTADO INICIAL</td><td>CAUSANTE</td><td>GUIA</td><td>OBSERVACIONES</td><td>PALLETS</td><td>ESTADO RENDICION INTERNA</td><td>ESTADO RENDICION CLIENTE</td><td>FECHA RENDICION CLIENTE</td><td>FOLIO</td><td>COMENTARIOS</td><td>VOUCHER</td><td>ARCHIVADO</td>\n";
echo "</tr>\n";
//
if ($fec1 != "" && $fec2 != "") {
	$query = "select a.*, a.estado as 'estado_asig', b.*, b.estado as 'estado_ri', b.causante as 'causante_', DATE_FORMAT( b.fecha, '%Y-%m-%d' ) AS 'FechaAsignacion', DATE_FORMAT( a.fechaRC, '%Y-%m-%d' ) AS 'FechaRC_' from guias_iansa a left join cargas_iansa b on a.asignacion_id=b.id where ( DATE_FORMAT( b.fecha, '%Y-%m-%d' ) >= ".$fec1." AND DATE_FORMAT( b.fecha, '%Y-%m-%d' ) <= ".$fec2." ) order by b.fecha, b.causante, a.guia ";
} else {
	$query = "select a.*, a.estado as 'estado_asig', b.*, b.estado as 'estado_ri', b.causante as 'causante_', DATE_FORMAT( b.fecha, '%Y-%m-%d' ) AS 'FechaAsignacion', DATE_FORMAT( a.fechaRC, '%Y-%m-%d' ) AS 'FechaRC_' from guias_iansa a left join cargas_iansa b on a.asignacion_id=b.id order by b.fecha, b.causante, a.guia ";
}
$result = mysqli_query($conn,$query);
//echo "<tr><td colspan=25>".trim($query)."</td></tr>\n";
if ($result) {
	while ($row = mysqli_fetch_array($result))
	{	
		$chep="";
		$iansa="";
		$voucher="";
		if (!is_null($row["CHEP"])) {
			$chep=trim($row["CHEP"]);
		}
		if (!is_null($row["IANSA"])) {
			$iansa=trim($row["IANSA"]);
		}
		if (!is_null($row["voucher"])) {
			$voucher=trim($row["voucher"]);
		}
		echo "<tr>\n";
		echo "<td valign=top>".trim($row["FechaAsignacion"])."</td>\n";
		echo "<td valign=top>".trim($row["ruta"])."</td>\n";
		echo "<td valign=top>".trim($row["destinatario"])."</td>\n";
		echo "<td valign=top>".utf8_decode(trim($row["nombre_destinatario"]))."</td>\n";
		echo "<td valign=top>".utf8_decode(trim($row["comuna"]))."</td>\n";
		echo "<td valign=top>".trim($row["total"])."</td>\n";
		echo "<td valign=top>".trim($row["tipo"])."</td>\n";
		echo "<td valign=top>".trim($row["transporte"])."</td>\n";
		echo "<td valign=top>".trim($row["rut_chofer"])."</td>\n";
		echo "<td valign=top>".utf8_decode(trim($row["nombre_chofer"]))."</td>\n";
		echo "<td valign=top>".trim($row["patente"])."</td>\n";
		echo "<td valign=top>".trim($row["oc"])."</td>\n";
		echo "<td valign=top>".trim($row["negocio"])."</td>\n";
		if ($row["estado_asig"]==0) {
			echo "<td valign=top>NO ASIGNADO</td>\n";
		} else {
			echo "<td valign=top>&nbsp;</td>\n";
		}
		echo "<td valign=top>".trim($row["causante_"])."</td>\n";
		echo "<td valign=top>".trim($row["guia"])."</td>\n";
		echo "<td valign=top>".trim($row["comentario"])."</td>\n";
		echo "<td valign=top>".$iansa."</td>\n";
		if ($row["estado_ri"]==1) {
			echo "<td valign=top>RENDIDA COMPLETA</td>\n";
		} else {
			if ($row["estado_ri"]==2) {
				echo "<td valign=top>RENDIDA CON PENDIENTES</td>\n";
			} else {
				echo "<td valign=top>&nbsp;</td>\n";
			}
		}
		if ($row["estadoRC"]==0) {
			echo "<td valign=top>NO RENDIDO</td>\n";
		} else {
			echo "<td valign=top>RENDIDO</td>\n";
		}
		echo "<td valign=top>".trim($row["FechaRC_"])."</td>\n";
		echo "<td valign=top>".trim($row["folio"])."</td>\n";
		echo "<td valign=top>".trim($row["comentarioRC"])."</td>\n";
		echo "<td valign=top>".$voucher."</td>\n";
		if ($row["archivado"]==0) {
			echo "<td valign=top>NO ARCHIVADO</td>\n";
		} else {
			echo "<td valign=top>ARCHIVADO</td>\n";
		}
		echo "</tr>\n";
	}
	mysqli_free_result($result);
}
?>
</table>
</body>
</html>



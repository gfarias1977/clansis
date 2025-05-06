<?php
function GeneraReporte( $name, $folio ) {
define('FPDF_FONTPATH','fpdf17/font/');	
require "fpdf17/fpdf.php";
set_time_limit(300);	// 5 min para tiempo proceso

include "include/header.php";
$conn=opendblocal();

$fec_hoy = FechaActual_Server();
$hor_hoy = HoraActual_Server();

$pdf=new FPDF('L','pt','Letter');
$des_hoy = "[ " . trim($fec_hoy) . " ] [ " . trim($hor_hoy) . " ]";
//
$pagina = 0;
$lineas = 46;
$items = 0;
$query = "SELECT A.*, B.patente, B.ruta, B.nombre_destinatario, B.rut_chofer, B.nombre_chofer, B.causante FROM guias_iansa A LEFT JOIN cargas_iansa B ON A.asignacion_id=B.id WHERE (A.estado = 1 AND A.folio = ".trim($folio).") ORDER BY A.guia ASC; "; 
//echo $query;
$result = mysqli_query($conn,$query);
if ($result) {
	while ($row = mysqli_fetch_array($result))
	{
		if ($lineas > 45) {
			$pagina = $pagina + 1;
			$pdf->AddPage();
			$pdf->Image("images/logo1.png",30,16,130,0,'');
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(0,0,"RENDICION DE DOCUMENTOS - FOLIO: ".trim($folio),0,1,'C');
			$pdf->Ln(18);
			// Line(x1,y1,x2,y2) x1=col1 x2=col2 y1=row1 y2=row2
			$pdf->Line(30,45,760,45);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(12,12,'Fecha-hora Reporte: '.$des_hoy,0,0,'L');
			$pdf->Cell(720,12,'Pagina: '.$pagina,0,1,'R');
			$pdf->Ln(10);
			//
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(3,11," ",0,0,'L');
			$pdf->Cell(25,11,"Ruta",1,0,'C');
			$pdf->Cell(150,11,"Destinatario",1,0,'L');
			$pdf->Cell(60,11,"Rut Chofer",1,0,'L');
			$pdf->Cell(110,11,"Nombre Chofer",1,0,'L');
			$pdf->Cell(50,11,"Patente",1,0,'C');
			$pdf->Cell(60,11,"Causante",1,0,'C');
			$pdf->Cell(60,11,"Guia",1,0,'C');
			$pdf->Cell(40,11,"# Pallets",1,1,'C');
			$pdf->Ln(1);
			$lineas = 10;
		}
		$items++;
	    $pallets="";
	    if (!is_null($row["IANSA"])) {
		    $pallets=trim($row["IANSA"]);
	    }
	    $ruta=trim($row["ruta"]);
	    $destinatario=trim($row["nombre_destinatario"]);
	    $rut_chofer=trim($row["rut_chofer"]);
	    $nombre_chofer=trim($row["nombre_chofer"]);
	    $patente=trim($row["patente"]);
	    $causante=trim($row["causante"]);
	    $guia=trim($row["guia"]);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(3,11," ",0,0,'L');
	    $pdf->Cell(25,11,trim($ruta),'LB',0,'C');
	    $pdf->Cell(150,11,trim($destinatario),'LB',0,'L');
	    $pdf->Cell(60,11,trim($rut_chofer),'LB',0,'L');
	    $pdf->Cell(110,11,trim($nombre_chofer),'LB',0,'L');
	    $pdf->Cell(50,11,trim($patente),'LB',0,'C');
	    $pdf->Cell(60,11,trim($causante),'LB',0,'C');
	    $pdf->Cell(60,11,trim($guia),'LBR',0,'C');
	    $pdf->Cell(40,11,trim($pallets),'LBR',1,'C');
	    $pdf->Ln(1);
	    $lineas = $lineas + 1;
	}
	mysqli_free_result($result);
}		
$lineas = 46;
$items = 0;
$query = "SELECT A.*, B.patente, B.ruta, B.nombre_destinatario, B.rut_chofer, B.nombre_chofer, B.causante FROM guias_iansa A LEFT JOIN cargas_iansa B ON A.asignacion_id=B.id WHERE (A.estado = 2 AND A.folio = ".trim($folio).") ORDER BY A.guia ASC; "; 
//echo $query;
$result = mysqli_query($conn,$query);
if ($result) {
	while ($row = mysqli_fetch_array($result))
	{
		if ($lineas > 45) {
			$pagina = $pagina + 1;
			$pdf->AddPage();
			$pdf->Image("images/logo1.png",30,16,130,0,'');
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(0,0,"RENDICION DE DOCUMENTOS [CON PENDIENTES] - FOLIO: ".trim($folio),0,1,'C');
			$pdf->Ln(18);
			// Line(x1,y1,x2,y2) x1=col1 x2=col2 y1=row1 y2=row2
			$pdf->Line(30,45,760,45);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(12,12,'Fecha-hora Reporte: '.$des_hoy,0,0,'L');
			$pdf->Cell(720,12,'Pagina: '.$pagina,0,1,'R');
			$pdf->Ln(10);
			//
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(3,11," ",0,0,'L');
			$pdf->Cell(25,11,"Ruta",1,0,'C');
			$pdf->Cell(130,11,"Destinatario",1,0,'L');
			$pdf->Cell(60,11,"Rut Chofer",1,0,'L');
			$pdf->Cell(100,11,"Nombre Chofer",1,0,'L');
			$pdf->Cell(50,11,"Patente",1,0,'C');
			$pdf->Cell(60,11,"Causante",1,0,'C');
			$pdf->Cell(60,11,"Guia",1,0,'C');
			$pdf->Cell(40,11,"# Pallets",1,0,'C');
			$pdf->Cell(205,11,"Comentarios",1,1,'L');
			$pdf->Ln(1);
			$lineas = 10;
		}
		$items++;
	    $pallets="";
	    if (!is_null($row["IANSA"])) {
		    $pallets=trim($row["IANSA"]);
	    }
	    $ruta=trim($row["ruta"]);
	    $destinatario=trim($row["nombre_destinatario"]);
	    $rut_chofer=trim($row["rut_chofer"]);
	    $nombre_chofer=trim($row["nombre_chofer"]);
	    $patente=trim($row["patente"]);
	    $causante=trim($row["causante"]);
	    $guia=trim($row["guia"]);
	    $comentario=utf8_decode(trim($row["comentario"]));
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(3,11," ",0,0,'L');
	    $pdf->Cell(25,11,trim($ruta),'LB',0,'C');
	    $pdf->Cell(130,11,trim($destinatario),'LB',0,'L');
	    $pdf->Cell(60,11,trim($rut_chofer),'LB',0,'L');
	    $pdf->Cell(100,11,trim($nombre_chofer),'LB',0,'L');
	    $pdf->Cell(50,11,trim($patente),'LB',0,'C');
	    $pdf->Cell(60,11,trim($causante),'LB',0,'C');
	    $pdf->Cell(60,11,trim($guia),'LBR',0,'C');
	    $pdf->Cell(40,11,trim($pallets),'LBR',0,'C');
	    $pdf->Cell(205,12,trim($comentario),'LBR',1,'L');
	    $pdf->Ln(1);
	    $lineas = $lineas + 1;
	}
	mysqli_free_result($result);
}		
//
$pdf->Output($name);
return true;
}
?> 	

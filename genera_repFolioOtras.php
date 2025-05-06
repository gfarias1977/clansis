<?php
function GeneraReporte( $name, $folio ) {
define('FPDF_FONTPATH','fpdf17/font/');	
require "fpdf17/fpdf.php";
set_time_limit(300);	// 5 min para tiempo proceso

include "include/header.php";
$conn=opendblocal();

$fec_hoy = FechaActual_Server();
$hor_hoy = HoraActual_Server();

$pdf=new FPDF('P','pt','Letter');
$des_hoy = "[ " . trim($fec_hoy) . " ] [ " . trim($hor_hoy) . " ]";
//
$pagina = 0;
$lineas = 56;
$items = 0;
//
	$query = "SELECT A.*, B.descripcion AS 'DES_FAENA', C.patente AS 'DES_PATENTE', D.nombre, D.apellidos FROM cargas_otras_faenas A LEFT JOIN faenas B ON A.faena=B.id LEFT JOIN patentes C ON A.patente=C.id LEFT JOIN choferes D ON A.chofer=D.id WHERE A.folio = ".trim($folio)." ORDER BY A.guia ASC; "; 
	//echo $query;
	$result = mysqli_query($conn,$query);
	if ($result) {
		while ($row = mysqli_fetch_array($result))
		{
			if ($lineas > 55) {
				$pagina = $pagina + 1;
				$pdf->AddPage();
				$pdf->Image("images/logo1.png",30,16,130,0,'');
				$pdf->SetFont('Arial','B',11);
				$pdf->Cell(0,0,"RENDICION DE DOCUMENTOS OTRAS FAENAS - FOLIO: ".trim($folio)."                         ",0,1,'R');
				$pdf->Ln(18);
				// Line(x1,y1,x2,y2) x1=col1 x2=col2 y1=row1 y2=row2
				$pdf->Line(30,45,580,45);
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(12,12,'Fecha-hora Reporte: '.$des_hoy,0,0,'L');
				$pdf->Cell(540,12,'Pagina: '.$pagina,0,1,'R');
				$pdf->Ln(10);
				//
				$pdf->SetFont('Arial','',8);
				$pdf->Cell(3,12," ",0,0,'L');
				$pdf->Cell(60,12,"DOCUMENTO",1,0,'C');
				$pdf->Cell(160,12,"FAENA",1,0,'C');
				$pdf->Cell(60,12,"PATENTE",1,0,'C');
				$pdf->Cell(160,12,"CHOFER",1,0,'C');
				$pdf->Cell(100,12,"NRO.GUIA",1,1,'C');
				$pdf->Ln(2);
				$lineas = 10;
			}
			$items++;
		    $chofer = "";
		    if (!is_null($row["nombre"])) {
    		    $chofer = trim($row["nombre"]);
		    }
		    if (!is_null($row["apellidos"])) {
    		    $chofer = $chofer . " " . trim($row["apellidos"]);
		    }
		    $patente=trim($row["DES_PATENTE"]);
		    $faena=trim($row["DES_FAENA"]);
		    $guia=trim($row["guia"]);
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(3,12," ",0,0,'L');
		    $pdf->Cell(60,12,trim($items),'LB',0,'C');
		    $pdf->Cell(160,12,trim($faena),'LB',0,'C');
		    $pdf->Cell(60,12,trim($patente),'LB',0,'C');
		    $pdf->Cell(160,12,trim($chofer),'LB',0,'C');
		    $pdf->Cell(100,12,trim($guia),'LBR',1,'C');
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

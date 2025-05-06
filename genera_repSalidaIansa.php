<?php
function GeneraReporte( $name, $inicio, $termino ) {
define('FPDF_FONTPATH','fpdf17/font/');	
require "fpdf17/fpdf.php";

include "include/header.php";
$conn=opendblocal();

$fec_hoy = FechaActual_Server();
$hor_hoy = HoraActual_Server();

$lineas = 50;
$pagina = 0;
$fec1 = "";
$fec2 = "";
if ($inicio <> "") {
	$fec1 = f_retorna_fecha_mysql( $inicio );
	if ($termino <> "") {
		$fec2 = f_retorna_fecha_mysql( $termino );
	}
}
$pdf=new FPDF('L','pt','Letter');
$des_hoy = "[ " . trim($fec_hoy) . " ] [ " . trim($hor_hoy) . " ]";
//
if ($fec1 != "" && $fec2 != "") {
	$query = "select a.*, b.* from guias_iansa a left join cargas_iansa b on a.asigancion_id=b.id where ( DATE_FORMAT( b.fecha, '%Y-%m-%d' ) >= ".$fec1." AND DATE_FORMAT( b.fecha, '%Y-%m-%d' ) <= ".$fec2." ) order by b.fecha, b.causante, a.guia ";
} else {
	$query = "select a.*, b.* from guias_iansa a left join cargas_iansa b on a.asigancion_id=b.id order by b.fecha, b.causante, a.guia ";
}
$result = mysqli_query($conn,$query);
//
if ($lineas > 49) {
	$pagina = $pagina + 1;
	$pdf->AddPage();
	$pdf->Image("images/logo1.png",20,10,100,0,'');
	$pdf->SetFont('Arial','B',8);
	$titulo = "REPORTE INFORMACION ASIGNACIONES - PERIODO: ".$inicio." - ".$termino;
	$pdf->Cell(0,0,$titulo,0,1,'C');
	$pdf->Ln(16);
	// Line(x1,y1,x2,y2) x1=col1 x2=col2 y1=row1 y2=row2
	$pdf->Line(30,45,760,45);
	$pdf->SetFont('Arial','',6);
	$pdf->Cell(12,12,'Fecha-hora Reporte: '.$des_hoy,0,0,'L');
	$pdf->Cell(720,12,'Pagina: '.$pagina,0,1,'R');
	$pdf->Ln(10);
	//
	$pdf->Cell(2,12," ",0,0,'L');
	$pdf->Cell(40,12,"FECHA",1,0,'C');
	$pdf->Cell(70,12,"MOVIMIENTO",1,0,'L');
	$pdf->Cell(50,12,"DOCUMENTO",1,0,'L');
	$pdf->Cell(50,12,"ORIGEN",1,0,'L');
	$pdf->Cell(210,12,"PROVEEDOR / CLIENTE",1,0,'L');
	$pdf->Cell(40,12,"ENTRADAS",1,0,'R');
	$pdf->Cell(100,12,"BODEGA",1,0,'L');
	$pdf->Cell(40,12,"SALIDAS",1,0,'R');
	$pdf->Cell(100,12,"BODEGA",1,0,'L');
	$pdf->Cell(30,12,"SALDO",1,1,'R');
	$pdf->Ln(4);
	$lineas = 10;
}
//	
if ($result) {
	while ($row = mysqli_fetch_array($result))
	{
		if ($lineas > 49) {
			$pagina = $pagina + 1;
			$pdf->AddPage();
			$pdf->Image("images/logo1.png",20,10,100,0,'');
			$pdf->SetFont('Arial','B',8);
			$titulo = "REPORTE HOJA DE VIDA PRODUCTOS [PROVALTEC] - CODIGO: ".strtoupper(trim($producto));
			if ($fec1 != "") {
				$titulo = $titulo . " - DESDE: ".trim($inicio);
				if ($fec2 != "") {
					$titulo = $titulo . " HASTA: ".trim($termmino);
				}
			}
			$pdf->Cell(0,0,$titulo,0,1,'C');
			$pdf->Ln(16);
			// Line(x1,y1,x2,y2) x1=col1 x2=col2 y1=row1 y2=row2
			$pdf->Line(30,45,760,45);
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(12,12,'Fecha-hora Reporte: '.$des_hoy,0,0,'L');
			$pdf->Cell(720,12,'Pagina: '.$pagina,0,1,'R');
			$pdf->Ln(10);
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(0,0,"SALDO INICIAL CARTOLA: ".number_format($saldo_inicial_cartola,0,",","."),0,1,'L');
			$pdf->SetFont('Arial','',6);
			$pdf->Ln(10);
			//
			$pdf->Cell(2,12," ",0,0,'L');
			$pdf->Cell(40,12,"FECHA",1,0,'C');
			$pdf->Cell(70,12,"MOVIMIENTO",1,0,'L');
			$pdf->Cell(50,12,"DOCUMENTO",1,0,'L');
			$pdf->Cell(50,12,"ORIGEN",1,0,'L');
			$pdf->Cell(210,12,"PROVEEDOR / CLIENTE",1,0,'L');
			$pdf->Cell(40,12,"ENTRADAS",1,0,'R');
			$pdf->Cell(100,12,"BODEGA",1,0,'L');
			$pdf->Cell(40,12,"SALIDAS",1,0,'R');
			$pdf->Cell(100,12,"BODEGA",1,0,'L');
			$pdf->Cell(30,12,"SALDO",1,1,'R');
			$pdf->Ln(4);
			$lineas = 10;
		}
		$afecta_cantidad = true;
		// Verifica si cambio involucra cantidad (en casos de control de cambio)
		if ($row["origen"]==11 || $row["origen"]==12 || $row["origen"]==21 || $row["origen"]==22 || $row["origen"]==41 || $row["origen"]==42 || $row["origen"]==51 || $row["origen"]==52) {
			$query = "select a.*, b.cantidad as 'cantidad_original' from cambios_det a left join cambios_backup_det b on a.cambio_id=b.cambio_id and a.producto_id=b.producto_id where a.cambio_id = ".trim($row["id"])." and a.producto_id = ".trim($propio)." order by a.id ";
			$result2 = mysqli_query($conn,$query);
			if ($result2) {
				while ($row2 = mysqli_fetch_array($result2))
				{
					if (is_null($row2["cantidad_original"])) {
						$afecta_cantidad = false;
					} else {
						$cant2 = ($row2["cantidad_original"] - $row2["cantidad"]);
						if ($cant2 == 0) {
							$afecta_cantidad = false;
						}
					}
				}		    		
				mysqli_free_result($result2);
	    	} else {
		    	$afecta_cantidad = false;
	    	}
		}			
		//
		if ($afecta_cantidad == true) {
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(2,12," ",0,0,'L');
		    $pdf->Cell(40,9,trim($row["fec_docto"]),0,0,'C');
		    $pdf->Cell(70,9,trim($row["movimiento"]),0,0,'L');
		    $pdf->Cell(50,9,trim($row["documento"]),0,0,'L');
		    if ($row["origen"]==0) {
			    $pdf->Cell(50,9,"NACIONAL",0,0,'L');
		    } else {
			    if ($row["origen"]==1) {
				    $pdf->Cell(50,9,"IMPORTADA",0,0,'L');
		    	} else {
				    if ($row["origen"]==8) {
					    $pdf->Cell(50,9,"MOTIVO: ",0,0,'L');
			    	} else {
					    if ($row["origen"]==11 || $row["origen"]==21 || $row["origen"]==41 || $row["origen"]==51) {
						    $pdf->Cell(50,9,"[ ELIMINAR ]",0,0,'L');
				    	} else {
						    if ($row["origen"]==12 || $row["origen"]==22 || $row["origen"]==42 || $row["origen"]==52) {
							    $pdf->Cell(50,9,"[ MODIFICAR ]",0,0,'L');
					    	} else {
							    if ($row["origen"]!=2) {
								    $pdf->Cell(50,9,trim($row["origen"]),0,0,'L');
							    } else {
								    $pdf->Cell(50,9,"",0,0,'L');
							    }
						    }
					    }
			    	}
		    	}
	    	}
			if ($row["origen"]==11 || $row["origen"]==12 || $row["origen"]==21 || $row["origen"]==22 || $row["origen"]==41 || $row["origen"]==42 || $row["origen"]==51 || $row["origen"]==52) {
				$sufijo="";
				if (f_len(trim($row["referido"])) > 50) {
					$sufijo=" ... ";
				}
				$referido=substr(trim($row["referido"]), 0, 50).$sufijo;
    		} else {
	    		$referido=trim($row["referido"]);
    		}
		    $pdf->Cell(210,9,$referido,0,0,'L');
		    
		    if ($row["movimiento"] == "TRASPASO") {
			    // DETALLE
			    $vuelta = 0;
				$query = "select a.*, c.descripcion as 'bodega_salida', d.descripcion as 'bodega_entrada' from traspasos_det a left join traspasos b on a.traspaso_id=b.id left join bodegas c on b.bodega1_id=c.id join bodegas d on b.bodega2_id=d.id where a.traspaso_id = ".trim($row["id"])." and a.producto_id = ".trim($propio)." order by a.id ";
				$result2 = mysqli_query($conn,$query);
				if ($result2) {
					while ($row2 = mysqli_fetch_array($result2))
					{
			    		if ($vuelta==0) {
						    $pdf->Cell(40,9,number_format($row2["cantidad"],0,",","."),0,0,'R');
						    $pdf->Cell(100,9,trim($row2["bodega_entrada"]),0,0,'L');
						    $pdf->Cell(40,9,number_format($row2["cantidad"],0,",","."),0,0,'R');
						    $pdf->Cell(100,9,trim($row2["bodega_salida"]),0,0,'L');
						    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
				    		$vuelta = 1;
						    $pdf->Ln(1);
						}
					}		    		
					mysqli_free_result($result2);
		    	}
	    	} else {
			    if ($row["movimiento"] == "COMPRA") {
				    // DETALLE
				    $vuelta = 0;
					$query = "select a.*, c.descripcion as 'bodega' from compras_det a left join compras b on a.compra_id=b.id left join bodegas c on b.bodega_id=c.id where a.compra_id = ".trim($row["id"])." and a.producto_id = ".trim($propio)." order by a.id ";
					$result2 = mysqli_query($conn,$query);
					if ($result2) {
						while ($row2 = mysqli_fetch_array($result2))
						{
				    		if ($vuelta==0) {
							    $pdf->Cell(40,9,number_format($row2["cantidad"],0,",","."),0,0,'R');
							    $pdf->Cell(100,9,trim($row2["bodega"]),0,0,'L');
							    $pdf->Cell(140,9,"",0,0,'L');
					    		$saldo_inicial_cartola = $saldo_inicial_cartola + $row2["cantidad"];
							    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
					    		$vuelta = 1;
							    $pdf->Ln(1);
				    		} else {
							    $pdf->Cell(422,9,"",0,0,'L');
							    $pdf->Cell(40,9,number_format($row2["cantidad"],0,",","."),0,0,'R');
							    $pdf->Cell(100,9,trim($row2["bodega"]),0,0,'L');
							    $pdf->Cell(140,9,"",0,0,'L');
					    		$saldo_inicial_cartola = $saldo_inicial_cartola + $row2["cantidad"];
							    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
							    $pdf->Ln(1);
							}
						}		    		
						mysqli_free_result($result2);
			    	}
		    	} else {
				    if ($row["movimiento"] == "COMPRA CONSIG") {
					    // DETALLE
					    $vuelta = 0;
						$query = "select a.*, c.descripcion as 'bodega1', d.descripcion as 'bodega2'  from compras_consig_det a left join compras_consig b on a.compra_consig_id=b.id left join bodegas c on b.bodega2_id=c.id left join bodegas d on b.bodega1_id=d.id where a.compra_consig_id = ".trim($row["id"])." and a.producto_id = ".trim($clon)." order by a.id ";
						$result2 = mysqli_query($conn,$query);
						if ($result2) {
							while ($row2 = mysqli_fetch_array($result2))
							{
					    		if ($vuelta==0) {
								    $pdf->Cell(40,9,number_format($row2["cantidad"],0,",","."),0,0,'R');
								    $pdf->Cell(100,9,trim($row2["bodega1"]),0,0,'L');
								    $pdf->Cell(40,9,number_format($row2["cantidad"],0,",","."),0,0,'R');
								    $pdf->Cell(100,9,trim($row2["bodega2"]),0,0,'L');
						    		$saldo_inicial_cartola = $saldo_inicial_cartola + $row2["cantidad"];
								    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
						    		$vuelta = 1;
								    $pdf->Ln(1);
					    		} else {
								    $pdf->Cell(422,9,"",0,0,'L');
								    $pdf->Cell(40,9,number_format($row2["cantidad"],0,",","."),0,0,'R');
								    $pdf->Cell(100,9,trim($row2["bodega1"]),0,0,'L');
								    $pdf->Cell(40,9,number_format($row2["cantidad"],0,",","."),0,0,'R');
								    $pdf->Cell(100,9,trim($row2["bodega2"]),0,0,'L');
						    		$saldo_inicial_cartola = $saldo_inicial_cartola + $row2["cantidad"];
								    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
								    $pdf->Ln(1);
								}
							}		    		
							mysqli_free_result($result2);
				    	}
			    	} else {
					    if ($row["movimiento"] == "VENTA") {
						    // DETALLE
						    $vuelta = 0;
							$query = "select a.*, c.descripcion as 'bodega' from ventas_det a left join bodegas c on a.bodega_id=c.id where a.venta_id = ".trim($row["id"])." and a.producto_id = ".trim($propio)." order by a.id ";
							$result2 = mysqli_query($conn,$query);
							if ($result2) {
								while ($row2 = mysqli_fetch_array($result2))
								{
						    		if ($vuelta==0) {
									    $pdf->Cell(140,9,"",0,0,'L');
									    $pdf->Cell(40,9,number_format($row2["cantidad"],0,",","."),0,0,'R');
									    $pdf->Cell(100,9,trim($row2["bodega"]),0,0,'L');
							    		$saldo_inicial_cartola = $saldo_inicial_cartola - $row2["cantidad"];
									    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
							    		$vuelta = 1;
									    $pdf->Ln(1);
						    		} else {
									    $pdf->Cell(422,9,"",0,0,'L');
									    $pdf->Cell(140,9,"",0,0,'L');
									    $pdf->Cell(40,9,number_format($row2["cantidad"],0,",","."),0,0,'R');
									    $pdf->Cell(100,9,trim($row2["bodega"]),0,0,'L');
							    		$saldo_inicial_cartola = $saldo_inicial_cartola - $row2["cantidad"];
									    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
									    $pdf->Ln(1);
									}
								}		    		
								mysqli_free_result($result2);
					    	}
			    		} else {
						    if ($row["movimiento"] == "DEVOLUCION") {
							    // DETALLE
							    $vuelta = 0;
								$query = "select a.*, c.descripcion as 'bodega' from devoluciones_det a left join bodegas c on a.bodega_id=c.id where a.devolucion_id = ".trim($row["id"])." and a.consignacion='N' and a.producto_id = ".trim($propio)." order by a.id ";
								$result2 = mysqli_query($conn,$query);
								if ($result2) {
									while ($row2 = mysqli_fetch_array($result2))
									{
							    		if ($vuelta==0) {
										    $pdf->Cell(40,9,number_format($row2["cantidad"],0,",","."),0,0,'R');
										    $pdf->Cell(100,9,trim($row2["bodega"]),0,0,'L');
										    $pdf->Cell(140,9,"",0,0,'L');
								    		$saldo_inicial_cartola = $saldo_inicial_cartola + $row2["cantidad"];
										    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
								    		$vuelta = 1;
										    $pdf->Ln(1);
							    		} else {
										    $pdf->Cell(422,9,"",0,0,'L');
										    $pdf->Cell(40,9,number_format($row2["cantidad"],0,",","."),0,0,'R');
										    $pdf->Cell(100,9,trim($row2["bodega"]),0,0,'L');
										    $pdf->Cell(140,9,"",0,0,'L');
								    		$saldo_inicial_cartola = $saldo_inicial_cartola + $row2["cantidad"];
										    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
										    $pdf->Ln(1);
										}
									}		    		
									mysqli_free_result($result2);
						    	}
						    } else {
							    if ($row["movimiento"] == "CC: AJUSTE") {
								    // DETALLE
								    $vuelta = 0;
									$query = "select a.*, c.descripcion as 'bodega' from cambios_det a left join bodegas c on a.bodega_id=c.id where a.cambio_id = ".trim($row["id"])." and a.producto_id = ".trim($propio)." order by a.id ";
									$result2 = mysqli_query($conn,$query);
									if ($result2) {
										while ($row2 = mysqli_fetch_array($result2))
										{
								    		if ($vuelta==0) {
											    $pdf->Cell(40,9,"",0,0,'R');
											    $pdf->Cell(100,9,trim($row2["bodega"]),0,0,'L');
											    $pdf->Cell(140,9,"",0,0,'L');
											    $pdf->Cell(30,9,number_format($row2["cantidad"],0,",","."),0,1,'R');
									    		$saldo_inicial_cartola = $row2["cantidad"];
									    		$vuelta = 1;
											    $pdf->Ln(1);
								    		} else {
											    $pdf->Cell(422,9,"",0,0,'L');
											    $pdf->Cell(40,9,"",0,0,'R');
											    $pdf->Cell(100,9,trim($row2["bodega"]),0,0,'L');
											    $pdf->Cell(140,9,"",0,0,'L');
											    $pdf->Cell(30,9,number_format($row2["cantidad"],0,",","."),0,1,'R');
									    		$saldo_inicial_cartola = $row2["cantidad"];
											    $pdf->Ln(1);
											}
										}		    		
										mysqli_free_result($result2);
							    	}
						    	} else {
								    if ($row["movimiento"] == "CC: VENTAS") {
									    // DETALLE
									    $vuelta = 0;
										$query = "select a.*, b.cantidad as 'cantidad_original' from cambios_det a left join cambios_backup_det b on a.cambio_id=b.cambio_id and a.producto_id=b.producto_id where a.cambio_id = ".trim($row["id"])." and a.producto_id = ".trim($propio)." order by a.id ";
										$result2 = mysqli_query($conn,$query);
										if ($result2) {
											while ($row2 = mysqli_fetch_array($result2))
											{
												if ($row["origen"]==52) {
													$detalle = "NUEVA CANTIDAD: ".trim($row2["cantidad"]);
												} else {
													$detalle = "";
												}
									    		if ($vuelta==0) {
												    $pdf->Cell(40,9,"",0,0,'R');
												    $pdf->Cell(100,9,$detalle,0,0,'L');
												    $pdf->Cell(140,9,"",0,0,'L');
												    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
										    		$vuelta = 1;
												    $pdf->Ln(1);
									    		} else {
												    $pdf->Cell(422,9,"",0,0,'L');
												    $pdf->Cell(40,9,"",0,0,'R');
												    $pdf->Cell(100,9,$detalle,0,0,'L');
												    $pdf->Cell(140,9,"",0,0,'L');
												    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
												    $pdf->Ln(1);
												}
											}		    		
											mysqli_free_result($result2);
								    	}
							    	} else {
									    if ($row["movimiento"] == "CC: COMPRAS") {
										    // DETALLE
										    $vuelta = 0;
											$query = "select a.*, b.cantidad as 'cantidad_original' from cambios_det a left join cambios_backup_det b on a.cambio_id=b.cambio_id and a.producto_id=b.producto_id where a.cambio_id = ".trim($row["id"])." and a.producto_id = ".trim($propio)." order by a.id ";
											$result2 = mysqli_query($conn,$query);
											if ($result2) {
												while ($row2 = mysqli_fetch_array($result2))
												{
													if ($row["origen"]==12 || $row["origen"]==22) {
														$detalle = "NUEVA CANTIDAD: ".trim($row2["cantidad"]);
													} else {
														$detalle = "";
													}
										    		if ($vuelta==0) {
													    $pdf->Cell(40,9,"",0,0,'R');
													    $pdf->Cell(100,9,$detalle,0,0,'L');
													    $pdf->Cell(140,9,"",0,0,'L');
													    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
											    		$vuelta = 1;
													    $pdf->Ln(1);
										    		} else {
													    $pdf->Cell(422,9,"",0,0,'L');
													    $pdf->Cell(40,9,"",0,0,'R');
													    $pdf->Cell(100,9,$detalle,0,0,'L');
													    $pdf->Cell(140,9,"",0,0,'L');
													    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
													    $pdf->Ln(1);
													}
												}		    		
												mysqli_free_result($result2);
									    	}
								    	} else {
										    if ($row["movimiento"] == "CC: COM_CONSIG") {
											    // DETALLE
											    $vuelta = 0;
												$query = "select a.*, b.cantidad as 'cantidad_original' from cambios_det a left join cambios_backup_det b on a.cambio_id=b.cambio_id and a.producto_id=b.producto_id where a.cambio_id = ".trim($row["id"])." and a.producto_id = ".trim($clon)." order by a.id ";
												$result2 = mysqli_query($conn,$query);
												if ($result2) {
													while ($row2 = mysqli_fetch_array($result2))
													{
														if ($row["origen"]==42) {
															$detalle = "NUEVA CANTIDAD: ".trim($row2["cantidad"]);
														} else {
															$detalle = "";
														}
											    		if ($vuelta==0) {
														    $pdf->Cell(40,9,"",0,0,'R');
														    $pdf->Cell(100,9,$detalle,0,0,'L');
														    $pdf->Cell(140,9,"",0,0,'L');
														    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
												    		$vuelta = 1;
														    $pdf->Ln(1);
											    		} else {
														    $pdf->Cell(422,9,"",0,0,'L');
														    $pdf->Cell(40,9,"",0,0,'R');
														    $pdf->Cell(100,9,$detalle,0,0,'L');
														    $pdf->Cell(140,9,"",0,0,'L');
														    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
														    $pdf->Ln(1);
														}
													}		    		
													mysqli_free_result($result2);
										    	}
									    	} else {
											    $pdf->Cell(310,9,"",0,1,'L');
										    }
									    }
								    }
							    }
						    }
				    	}
				    }
			    }
		    }
		    $lineas = $lineas + 1;
	    }
	}
	mysqli_free_result($result);
	//
	$inventario = 0;
	$query = "select sum(a.stock) as 'inventario' from stock a left join bodegas b on a.bodega_id=b.id where a.producto_id = ".trim($propio)." and b.tipo=0 group by a.producto_id ";
	$result2 = mysqli_query($conn, $query);
	if ($result2) {
		while ($row2 = mysqli_fetch_array($result2))
		{
			$inventario = $row2["inventario"];
		}		    		
		mysqli_free_result($result2);
	}
	
	$pdf->Line(30,$pdf->GetY(),760,$pdf->GetY());
    $pdf->Cell(702,9,"SALDO FINAL CARTOLA : ",0,0,'R');
    $pdf->Cell(30,9,number_format($saldo_inicial_cartola,0,",","."),0,1,'R');
	$pdf->Ln(1);
    $pdf->Cell(702,9,"SALDO INVENTARIO : ",0,0,'R');
    $pdf->Cell(30,9,number_format($inventario,0,",","."),0,1,'R');
	$pdf->Ln(1);
	$pdf->Line(30,$pdf->GetY(),760,$pdf->GetY());
}		
//
$pdf->Output($name);
return true;
}

?> 	

<?php
	session_start();
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             	// fecha pasada...
  	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 	// ultima modificacion
  	header("Cache-Control: no-cache, must-revalidate");           	// HTTP/1.1
  	header("Pragma: no-cache");
	header("Content-type: text/html; charset=utf8");
	if (!isset($_SESSION['idUsuario'])) {
		include "no_autorizado.html";
	} else {
		// carga rutinas de acceso a base de datos
		require_once 'include/excel_reader2.php';
		include "include/header.php";
		include "include/funciones.php";
		// Reporte
		$prefijo = date("YmdHis");
		$name = "temp/".trim($prefijo).".pdf";
		$name2 = "temp/".trim($prefijo).".xls";
		$ok = false;
		//
		$conn=opendblocal();
		$nom_usuario = 	$_SESSION["nombreUsuario"];
		$rol_usuario = $_SESSION["rolUsuario"];
		$id_usuario = $_SESSION["idUsuario"];
		date_default_timezone_set("Chile/Continental");		
		$menu="IANSA";
		$opcion="ASIGNACIONES";
		$hoy=date("d/m/Y");
		$ayer=date("d/m/Y",strtotime("+1 day"));
		if ($fecha=="") {
			$fecha=$ayer;
		}
		$sw_error=0;
		//
		$mensaje="";
		if ($oper==0) {	
			if ($status==4) {	// Procesar Datos cargados
				$fecha_ = f_retorna_fecha_mysql($fecha);
				$num_reg=0;
				$query = "SELECT * from cargas_iansa_paso Where usuario_id = ".$id_usuario.";";
				$result = mysqli_query($conn,$query);
				//echo $query;
				while ($row = mysqli_fetch_array($result))
				{
					$query = "INSERT INTO cargas_iansa VALUES(NULL,".$fecha_.",'".trim($row["ruta"])."','".trim($row["destinatario"])."','".trim($row["nombre_destinatario"])."','".trim($row["comuna"])."','".trim($row["total"])."','".trim($row["tipo"])."','".trim($row["transporte"])."','".trim($row["nombre_chofer"])."','".trim($row["rut_chofer"])."','".trim($row["patente"])."','".trim($row["causante"])."','".trim($row["oc"])."','".trim($row["negocio"])."',NULL,NULL, 1);";
					$result2 = mysqli_query($conn,$query);
					$num_reg++;
				}
				mysqli_free_result($result);
				//
				$query = "DELETE FROM cargas_iansa_paso WHERE usuario_id = ".trim($id_usuario).";";
				$result2 = mysqli_query($conn,$query);
				//				
				$mensaje = "Fueron procesadas ".trim($num_reg)." asignaciones.";
				$status=0;
			}
			if ($status==1) {
				$fecha_ = f_retorna_fecha_mysql($fecha);
				if ($_FILES[asignacion][name]!="") {
					if ($_FILES[asignacion][size] > $maximo) {
						$sw_error=1;
					} else {
						if ($_FILES[asignacion][type]!="application/octet-stream" && $_FILES[asignacion][type]!="application/vnd-ms-excel" && $_FILES[asignacion][type]!="application/vnd.ms-excel" && $_FILES[asignacion][type]!="application/x-msexcel" && $_FILES[asignacion][type]!="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
							$sw_error=2;
						}
					}
				}
				if ($sw_error==0) {
					if ($_FILES[asignacion][name]!="") {
					    $path1 = "temp/";          
					    $ext1 = strtolower(right($_FILES[asignacion][name], 4));
					    $source1 = $_FILES[asignacion][tmp_name];
					    $source_name1 = $_FILES[asignacion][name];
					    //$source="none";
					    if(($source1 <> "none")&&($source1 <> "")){
				           $dest1 = $path1.$source_name1;
				           if(copy($source1,$dest1)){
				                $newname1 = $path1;
				                $namefile1 = time();
				                $newname1 .= $namefile1.$ext1;
				                copy($dest1,$newname1);
				                unlink ($dest1);
					       }
				        }
			        }
			        //
			        //echo "newname1: ".trim($newname1)."<br>";
					$oka = true;
					$res = "";
					$filaIni = 1;
					$coluIni = 1;
					$data = new Spreadsheet_Excel_Reader();
					$data->setOutputEncoding('CP1251');
					if ($newname1!="") {
						$data->read($newname1);
						$filaIni = 2;
						if ($data->sheets[0]['numCols'] != 13) {
							$sw_error=3;
							$res = "NUMERO COLUMNAS PLANILLA NO CORRESPONDE. COLUMNAS ESPERADAS: 13.";
							$oka = false;
						} else {
							$num_filas = $data->sheets[0]['numRows'];
							$num_columnas = $data->sheets[0]['numCols'];
							$registros = array();
							$errores = array();
							$ind_error = 0;
							for ($i = $filaIni; $i <= $num_filas; $i++) {
								for ($j = 1; $j <= $num_columnas; $j++) {
									if ($j==1) {	// RUTA
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$dato = "";
										} else {
											$dato = trim($data->sheets[0]['cells'][$i][$j]);
										}
										$registros[$i][$j] = $dato;
									}
									if ($j==2) {	// DESTINATARIO
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$dato = "";
										} else {
											$dato = trim($data->sheets[0]['cells'][$i][$j]);
										}
										$registros[$i][$j] = $dato;
									}
									if ($j==3) {	// NOMBRE
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$ind_error++;
											$errores[$ind_error][0]=$i;
											$errores[$ind_error][1]=$j;
											$errores[$ind_error][2]="NOMBRE";
											$errores[$ind_error][3]="NO SE ENCUENTRA DATO";
										} else {
											$registros[$i][$j] = utf8_encode(addslashes(limpiaCadena(str_replace("\xA0", ' ',trim($data->sheets[0]['cells'][$i][$j])))));
										}
									}
									if ($j==4) {	// COMUNA
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$ind_error++;
											$errores[$ind_error][0]=$i;
											$errores[$ind_error][1]=$j;
											$errores[$ind_error][2]="COMUNA";
											$errores[$ind_error][3]="NO SE ENCUENTRA DATO";
										} else {
											$registros[$i][$j] = utf8_encode(addslashes(limpiaCadena(trim($data->sheets[0]['cells'][$i][$j]))));
										}
									}
									if ($j==5) {	// TOTAL
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$ind_error++;
											$errores[$ind_error][0]=$i;
											$errores[$ind_error][1]=$j;
											$errores[$ind_error][2]="TOTAL";
											$errores[$ind_error][3]="NO SE ENCUENTRA DATO";
										} else {
											$registros[$i][$j] = str_replace(",",".",(trim($data->sheets[0]['cells'][$i][$j])));
										}
									}
									if ($j==6) {	// TIPO
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$dato = "";
										} else {
											$dato = trim($data->sheets[0]['cells'][$i][$j]);
										}
										$registros[$i][$j] = $dato;
									}
									if ($j==7) {	// TRANSPORTE
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$dato = "";
										} else {
											$dato = trim($data->sheets[0]['cells'][$i][$j]);
										}
										$registros[$i][$j] = $dato;
									}
									if ($j==8) {	// NOMBRE CHOFER
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$dato = "";
										} else {
											/*
											if ($i==30) {
												$cadena = trim($data->sheets[0]['cells'][$i][$j]);
												for ($c=0;$c<strlen($cadena);$c++) {
													$caracter = substr($cadena,$c,1);
													echo $caracter." - ".ord($caracter);
												}	
											}
											*/
											$dato = limpiaCadena(str_replace("\xA0", ' ',trim($data->sheets[0]['cells'][$i][$j])));
										}
										$registros[$i][$j] = $dato;
									}
									if ($j==9) {	// RUT CHOFER
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$ind_error++;
											$errores[$ind_error][0]=$i;
											$errores[$ind_error][1]=$j;
											$errores[$ind_error][2]="RUT CHOFER";
											$errores[$ind_error][3]="NO SE ENCUENTRA DATO";
										} else {
											// Verifica si existe en BD TGD
											$dato = str_replace(".","",(trim($data->sheets[0]['cells'][$i][$j])));
											$existe_chofer = false;
											$query = "SELECT * from choferes Where rut = '".$dato."';";
											$result2 = mysqli_query($conn,$query);
											while ($row2 = mysqli_fetch_array($result2))
											{
												$existe_chofer = true;
											}
											mysqli_free_result($result2);
											//
											if ($existe_chofer == false) {
												$ind_error++;
												$errores[$ind_error][0]=$i;
												$errores[$ind_error][1]=$j;
												$errores[$ind_error][2]="RUT CHOFER";
												$errores[$ind_error][3]="NO EXISTE EN BD TGD. RUT: ".trim($data->sheets[0]['cells'][$i][$j]);
											} else {
												$registros[$i][$j] = str_replace(".","",(trim($data->sheets[0]['cells'][$i][$j])));
											}
										}
									}
									if ($j==10) {	// PATENTE
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$ind_error++;
											$errores[$ind_error][0]=$i;
											$errores[$ind_error][1]=$j;
											$errores[$ind_error][2]="PATENTE";
											$errores[$ind_error][3]="NO SE ENCUENTRA DATO";
										} else {
											// Verifica si existe en BD TGD
											$dato = trim($data->sheets[0]['cells'][$i][$j]);
											$existe_patente = false;
											$query = "SELECT * from patentes Where patente = '".$dato."';";
											$result2 = mysqli_query($conn,$query);
											while ($row2 = mysqli_fetch_array($result2))
											{
												$existe_patente = true;
											}
											mysqli_free_result($result2);
											//
											if ($existe_patente == false) {
												$ind_error++;
												$errores[$ind_error][0]=$i;
												$errores[$ind_error][1]=$j;
												$errores[$ind_error][2]="PATENTE";
												$errores[$ind_error][3]="NO EXISTE EN BD TGD. PATENTE: ".trim($data->sheets[0]['cells'][$i][$j]);
											} else {
												$registros[$i][$j] = trim($data->sheets[0]['cells'][$i][$j]);
											}
										}
									}
									if ($j==11) {	// CAUSANTE
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$ind_error++;
											$errores[$ind_error][0]=$i;
											$errores[$ind_error][1]=$j;
											$errores[$ind_error][2]="CAUSANTE";
											$errores[$ind_error][3]="NO SE ENCUENTRA DATO";
										} else {
											$registros[$i][$j] = addslashes(trim($data->sheets[0]['cells'][$i][$j]));
										}
									}
									if ($j==12) {	// OC
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$ind_error++;
											$errores[$ind_error][0]=$i;
											$errores[$ind_error][1]=$j;
											$errores[$ind_error][2]="OC";
											$errores[$ind_error][3]="NO SE ENCUENTRA DATO";
										} else {
											$registros[$i][$j] = addslashes(trim($data->sheets[0]['cells'][$i][$j]));
										}
									}
									if ($j==13) {	// NEGOCIO
										if (trim($data->sheets[0]['cells'][$i][$j])=="") {
											$ind_error++;
											$errores[$ind_error][0]=$i;
											$errores[$ind_error][1]=$j;
											$errores[$ind_error][2]="NEGOCIO";
											$errores[$ind_error][3]="NO SE ENCUENTRA DATO";
										} else {
											$registros[$i][$j] = addslashes(trim($data->sheets[0]['cells'][$i][$j]));
										}
									}
								}
							}
							$status = 0;
							if ($ind_error==0) {
								$query = "DELETE FROM cargas_iansa_paso WHERE usuario_id = ".trim($id_usuario).";";
								$result2 = mysqli_query($conn,$query);
								for ($i = $filaIni; $i <= $num_filas; $i++) {
									$existe_asignacion = false;
									$query = "SELECT * from cargas_iansa Where fecha = ".$fecha_." and destinatario = '".$registros[$i][2]."' and causante = '".$registros[$i][11]."' and oc = '".$registros[$i][12]."';";
									$result2 = mysqli_query($conn,$query);
									//echo $query;
									while ($row2 = mysqli_fetch_array($result2))
									{
										$existe_asignacion = true;
									}
									mysqli_free_result($result2);
									//
									if ($existe_asignacion == false) {
										$query = "INSERT INTO cargas_iansa_paso VALUES(NULL,".trim($id_usuario).",".$fecha_.",'".trim($registros[$i][1])."','".trim($registros[$i][2])."','".trim($registros[$i][3])."','".trim($registros[$i][4])."','".trim($registros[$i][5])."','".trim($registros[$i][6])."','".trim($registros[$i][7])."','".trim($registros[$i][8])."','".trim($registros[$i][9])."','".trim($registros[$i][10])."','".trim($registros[$i][11])."','".trim($registros[$i][12])."','".trim($registros[$i][13])."');";
										$result2 = mysqli_query($conn,$query);
										$status = 2;	// Se procesaron algunas asignaciones
										//echo $query."<br>";
									}
								}
							} else {
								$query = "DELETE FROM errores;";
								$result2 = mysqli_query($conn,$query);
								//
								for ($i = 1; $i <= $ind_error; $i++) {
									$query = "INSERT INTO errores VALUES(NULL,".trim($errores[$i][0]).",".trim($errores[$i][1]).",'".trim($errores[$i][2])."','".trim($errores[$i][3])."');";
									$result2 = mysqli_query($conn,$query);
									$status = 3;	// Existieron errores
								}
							}
						}
					}
				}
			}
		}
		if ($oper==0) {	
			if ($status==10) {
				$ok = GeneraReporte( $name, $f_categoria, $f_origen, $f_proveedor );
				$status=0;
			}
		}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    
    <title><?php echo $_SESSION["titulo"];?></title>
    <meta name="author" content="SuggeElson" />
    <meta name="description" content="Supr admin template - new premium responsive admin template. This template is designed to help you build the site administration without losing valuable time.Template contains all the important functions which must have one backend system.Build on great twitter boostrap framework" />
    <meta name="keywords" content="admin, admin template, admin theme, responsive, responsive admin, responsive admin template, responsive theme, themeforest, 960 grid system, grid, grid theme, liquid, masonry, jquery, administration, administration template, administration theme, mobile, touch , responsive layout, boostrap, twitter boostrap" />
    <meta name="application-name" content="Supr admin template" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Le styles -->
    <!-- Use new way for google web fonts 
    http://www.smashingmagazine.com/2012/07/11/avoiding-faux-weights-styles-google-web-fonts -->
    <!-- Headings -->
    <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css' />  -->
    <!-- Text -->
    <!-- <link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css' /> --> 
    <!--[if lt IE 9]>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400" rel="stylesheet" type="text/css" />
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet" type="text/css" />
    <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400" rel="stylesheet" type="text/css" />
    <link href="http://fonts.googleapis.com/css?family=Droid+Sans:700" rel="stylesheet" type="text/css" />
    <![endif]-->

    <link href="css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
    <link href="css/supr-theme/jquery.ui.supr.css" rel="stylesheet" type="text/css" />
    <link href="css/icons.css" rel="stylesheet" type="text/css" />
    <!-- Plugin stylesheets -->
    <link href="plugins/qtip/jquery.qtip.css" rel="stylesheet" type="text/css" />
    <link href="plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
    <link href="plugins/jpages/jPages.css" rel="stylesheet" type="text/css" />
    <link href="plugins/prettify/prettify.css" type="text/css" rel="stylesheet" />
    <link href="plugins/inputlimiter/jquery.inputlimiter.css" type="text/css" rel="stylesheet" />
    <link href="plugins/ibutton/jquery.ibutton.css" type="text/css" rel="stylesheet" />
    <link href="plugins/uniform/uniform.default.css" type="text/css" rel="stylesheet" />
    <link href="plugins/color-picker/color-picker.css" type="text/css" rel="stylesheet" />
    <link href="plugins/select/select2.css" type="text/css" rel="stylesheet" />
    <link href="plugins/validate/validate.css" type="text/css" rel="stylesheet" />
    <link href="plugins/pnotify/jquery.pnotify.default.css" type="text/css" rel="stylesheet" />
    <link href="plugins/smartWizzard/smart_wizard.css" type="text/css" rel="stylesheet" />
    <link href="plugins/dataTables/jquery.dataTables.css" type="text/css" rel="stylesheet" />
    <link href="plugins/elfinder/elfinder.css" type="text/css" rel="stylesheet" />
    <link href="plugins/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" rel="stylesheet" />
    <link href="plugins/search/tipuesearch.css" type="text/css" rel="stylesheet" />

    <!-- Main stylesheets -->
    <link href="css/main.css" rel="stylesheet" type="text/css" /> 

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le javascript
    ================================================== -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>  
    <script type="text/javascript" src="js/jquery_cookie.js"></script>
    <script type="text/javascript" src="js/jquery.mousewheel.js"></script>

    <!-- Load plugins -->
    <script type="text/javascript" src="plugins/qtip/jquery.qtip.min.js"></script>
    <script type="text/javascript" src="plugins/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="plugins/flot/jquery.flot.grow.js"></script>
    <script type="text/javascript" src="plugins/flot/jquery.flot.pie.js"></script>
    <script type="text/javascript" src="plugins/flot/jquery.flot.resize.js"></script>
    <script type="text/javascript" src="plugins/flot/jquery.flot.tooltip_0.4.4.js"></script>
    <script type="text/javascript" src="plugins/flot/jquery.flot.orderBars.js"></script>

    <script type="text/javascript" src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <script type="text/javascript" src="plugins/knob/jquery.knob.js"></script>
    <script type="text/javascript" src="plugins/fullcalendar/fullcalendar.min.js"></script>
    <script type="text/javascript" src="plugins/prettify/prettify.js"></script>

    <script type="text/javascript" src="plugins/watermark/jquery.watermark.min.js"></script>
    <script type="text/javascript" src="plugins/elastic/jquery.elastic.js"></script>
    <script type="text/javascript" src="plugins/inputlimiter/jquery.inputlimiter.1.3.min.js"></script>
    <script type="text/javascript" src="plugins/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript" src="plugins/ibutton/jquery.ibutton.min.js"></script>
    <script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="plugins/stepper/ui.stepper.js"></script>
    <script type="text/javascript" src="plugins/color-picker/colorpicker.js"></script>
    <script type="text/javascript" src="plugins/timeentry/jquery.timeentry.min.js"></script>
    <script type="text/javascript" src="plugins/select/select2.min.js"></script>
    <script type="text/javascript" src="plugins/dualselect/jquery.dualListBox-1.3.min.js"></script>
    <script type="text/javascript" src="plugins/tiny_mce/jquery.tinymce.js"></script>
    <script type="text/javascript" src="plugins/validate/jquery.validate.min.js"></script>
    <script type="text/javascript" src="plugins/search/tipuesearch_set.js"></script>
    <script type="text/javascript" src="plugins/search/tipuesearch_data.js"></script><!-- JSON for searched results -->
    <script type="text/javascript" src="plugins/search/tipuesearch.js"></script>

    <script type="text/javascript" src="plugins/animated-progress-bar/jquery.progressbar.js"></script>
    <script type="text/javascript" src="plugins/pnotify/jquery.pnotify.min.js"></script>
    <script type="text/javascript" src="plugins/lazy-load/jquery.lazyload.min.js"></script>
    <script type="text/javascript" src="plugins/jpages/jPages.min.js"></script>
    <script type="text/javascript" src="plugins/pretty-photo/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="plugins/smartWizzard/jquery.smartWizard-2.0.min.js"></script>

    <script type="text/javascript" src="plugins/ios-fix/ios-orientationchange-fix.js"></script>

    <script type="text/javascript" src="plugins/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="plugins/elfinder/elfinder.min.js"></script>
    <script type="text/javascript" src="plugins/plupload/plupload.js"></script>
    <script type="text/javascript" src="plugins/plupload/plupload.html4.js"></script>
    <script type="text/javascript" src="plugins/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>

    <!-- Init plugins -->
    <script type="text/javascript" src="js/statistic.js"></script><!-- Control graphs ( chart, pies and etc) -->

    <!-- Important Place before main.js  -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
    <script type="text/javascript" src="plugins/touch-punch/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/basic.js"></script>
    
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/apple-touch-icon-144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png" />
    
    <script type="text/javascript">
        //adding load class to body and hide page
        document.documentElement.className += 'loadstate';
    </script>
	
    <script>

 	<?php
 	if ($sw_error==1) { ?>
	 	$(document).ready(function(){
			$.pnotify({
				type: 'error',
			    title: 'ERROR CARGA ARCHIVO!',
	    		text: '\nPlanilla excede m\341ximo permitido:\n [800KBytes].',
			    icon: 'picon icon24 typ-icon-cancel white',
			    opacity: 0.95,
			    history: false,
			    sticker: false
			});
		});
	<?php } ?>
	<?php
 	if ($sw_error==2) { ?>
	 	$(document).ready(function(){
			$.pnotify({
				type: 'error',
			    title: 'ERROR CARGA ARCHIVO!',
	    		text: '\nFormato Planilla inv\341lido. Opciones v\341lidas: \n [Libro Excel 97-2013 *.xls].',
			    icon: 'picon icon24 typ-icon-cancel white',
			    opacity: 0.95,
			    history: false,
			    sticker: false
			});
		});
	<?php } ?>
	<?php
 	if ($sw_error==3) { ?>
	 	$(document).ready(function(){
			$.pnotify({
				type: 'error',
			    title: 'ERROR CARGA ARCHIVO!',
	    		text: '<?php echo $res;?>',
			    icon: 'picon icon24 typ-icon-cancel white',
			    opacity: 0.95,
			    history: false,
			    sticker: false
			});
		});
	<?php } ?>
        
    $(function() {
	    $( "#dp_f_asignacion" ).datepicker({ dateFormat: "dd/mm/yy", dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"], monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"] });
	  });
	
    function inicio() 
    {
		$("#anima").hide();		
    }
    
	function cargar_planilla() {
		var ret=0;
		if (document.cargas.asignacion.value=="") {
			ret=1;
			alert("Debe seleccionar Planilla.");
			document.cargas.asignacion.focus();
		} else {
			if (document.cargas.fecha.value=="") {
				ret=1;
				alert("Debe indicar FECHA Asignacion.");
				document.cargas.fecha.focus();
			}
		}
		if (ret==0) {		
			$('#carga_planilla').addClass('disabled');
			$("#anima").show();		
			document.cargas.oper.value=0;
			document.cargas.status.value=1;
			document.cargas.submit();
		}
	}
	
	function procesar() {
		var ret=0;
		if (ret==0) {		
			$('#procesar_datos').addClass('disabled');
			$("#anima").show();		
			document.cargas.oper.value=0;
			document.cargas.status.value=4;
			document.cargas.submit();
		}
	}
    </script>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
      
    <body onLoad="inicio();">
    <form method="post" action="carga_iansa.php" target="_self" enctype="multipart/form-data" name="cargas" id="cargas" onSubmit="return false;">
    <!-- loading animation -->
    <div id="qLoverlay"></div>
    <div id="qLbar"></div>
    
    <div id="header">

        <div class="navbar">
          <div class="mylogo">
            <div class="navbar-inner">
              <div class="container-fluid">
                <div class="nav-no-collapse">
                    <ul class="nav pull-right usernav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
                                <img src="images/avatar.jpg" alt="" class="image" /> 
                                <span class="txt"><?php echo $nom_usuario;?></span>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="menu">
                                    <ul>
                                        <li>
                                            <a href="#"><span class="icon16 icomoon-icon-user-3"></span>Editar Perfil</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="index.php"><span class="icon16 icomoon-icon-exit"></span> Salida</a></li>
                    </ul>
                </div><!-- /.nav-collapse -->
              </div>
            </div><!-- /navbar-inner -->
            </div>
          </div><!-- /navbar --> 

    </div><!-- End #header -->

    <div id="wrapper">

        <!--Responsive navigation button-->  
        <div class="resBtn">
            <a href="#"><span class="icon16 silk-icon-icons"></span></a>
        </div>
        
        <!--Sidebar collapse button-->  
        <div class="collapseBtn">
             <a href="#" class="tipR" title="Ocultar Menu"><span class="icon12 minia-icon-layout"></span></a>
        </div>

        <!--Sidebar background-->
        <div id="sidebarbg"></div>
        <!--Sidebar content-->
        <div id="sidebar">


            <div class="sidenav">

                <div class="sidebar-widget" style="margin: -1px 0 0 0;">
                    <h4 class="title" style="margin-bottom:0;">Menu Navegaci&oacute;n</h4>
                </div><!-- End .sidenav-widget -->

                <div class="mainnav">
                	<?php 	include "menu.php";?>
                </div>
                
            </div><!-- End sidenav -->


        </div><!-- End #sidebar -->

        <div id="content" class="clearfix" style="min-height: 530px;">
        <!--Body content-->
        <?php
		if ($oper==0) {	// Visualizar
		?>
            <div class="contentwrapper"><!--Content wrapper-->

                <div class="heading">

                    <ul class="breadcrumb">
                        <li style="color: white;">Ahora estas aqu&iacute;:</li>
                        <li>
                            <a href="inicio.php" class="tip" title="voler a Inicio">
                                <span class="icon16 white icomoon-icon-screen"></span>
                            </a> 
                            <span class="divider">
                                <span class="icon16 white icomoon-icon-arrow-right"></span>
                            </span>
                        </li>
                        <li class="active" style="color: white;">Carga Asignaci&oacute;n IANSA</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                    <div class="row-fluid">

                        <div class="span12">
                            
                            <div class="box">

                                <div class="title">
                                    <h4>
                                        <span class="icon16 brocco-icon-file"></span>
                                        <span>Carga Asignaci&oacute;n IANSA</span>
                                    </h4>
                                </div>
                                <div class="content noPad" style="height: 50px;">
	                                <div class="form-row row-fluid" style="border: 0px red dotted;">
                                        <span class="span2" style="text-align: left; border: 0px yellow dotted; margin-left: 10px; margin-top: 10px;">
                                        <input type="file" name="asignacion" id="asignacion" />
                                        </span>
                                        
                                        <span class="span2" style="text-align: left; border: 0px yellow dotted; margin-left: 60px; margin-top: 10px;">
                                        <input id="dp_f_asignacion" name="fecha" type="text" value="<?php echo $fecha;?>" maxlength="10" style="width: 90px;" />
                                        </span>
                                                
		                                <div class="btn-group span6" style="text-align: center; border: 0px blue dotted; margin-left: 0px; margin-top: 10px;">
		                                	<label class="form-label span3" for="normal"><span style="color:red;"><?php echo $mensaje;?></span></label>
	                                    </div>
	                                    
		                                <?php if ($status!=2) { ?> 
	                                    	<span class="span2" style="margin-left: 10px; text-align: left; border: 0px green dotted; margin-top: 10px;">
	                                    	<button type="button" class="btn btn-primary" tabindex="0" name="carga_planilla" id="carga_planilla" onClick="cargar_planilla();"><span class="icon16 icomoon-icon-upload-2 white"></span> Cargar</button><div name="anima" id="anima" class="margin padding left" style="display: none;"><img src="images/019.gif" alt="" /></div>
	                                    	</span>
	                                    <?php } else { ?>	
	                                    	<span class="span2" style="margin-left: 10px; text-align: left; border: 0px green dotted; margin-top: 10px;">
	                                    	<button type="button" class="btn btn-success" tabindex="0" name="procesar_datos" id="procesar_datos" onClick="procesar();"><span class="icon16 minia-icon-checked white"></span> Procesar</button><div name="anima" id="anima" class="margin padding left" style="display: none;"><img src="images/019.gif" alt="" /></div>
	                                    	</span>
	                                    <?php } ?>	
	                                </div>
                                </div>

                            </div><!-- End .box -->

                        </div><!-- End .span6 -->

                    </div><!-- End .row-fluid -->

            </div><!-- End contentwrapper -->
    	<?php } 
		if ($status==3) {	// Errores
			?>
            <!-- Build page from here: -->
            <div class="row-fluid">

                <div class="span12">
                    
                    <div class="box">

                        <div class="title">
                            <h4>
                                <span class="icon16 brocco-icon-file"></span>
                                <span>Errores en Planilla Carga Asignaci&oacute;n IANSA</span>
                            </h4>
                        </div>
                        <div class="content noPad">
                            <table class="responsive table table-bordered">
                                <thead>
                                  <tr>
                                    <th style="text-align: center; width: 3%;">#</th>
                                    <th style="text-align: left; width: 50px;">Columna</th>
                                    <th style="text-align: left; width: 50px;">Fila</th>
                                    <th style="text-align: left;">Dato</th>
                                    <th style="text-align: left;">Tipo de Error</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php
								$contador=0;
								$query = "SELECT A.* FROM errores A ORDER BY A.id ASC ";
								$result = mysqli_query($conn,$query);
								while ($row = mysqli_fetch_array($result))
								{
                        		    echo "<TR>\n";
                        		    $contador=$contador+1;
									?>
                                      <tr>
                                        <td style="text-align: center;"><?php echo $contador;?></td>
                                        <td style="text-align: left;"><?php echo $row["columna"];?></td>
                                        <td style="text-align: left;"><?php echo $row["fila"];?></td>
                                        <td style="text-align: left;"><?php echo $row["nom_columna"];?></td>
                                        <td style="text-align: left;"><?php echo $row["descripcion"];?></td>
                                      </tr>
                                <?php      
								}
								mysqli_free_result($result);
								?>
                                </tbody>
                            </table>
                        </div>

                    </div><!-- End .box -->

                </div><!-- End .span6 -->

            </div><!-- End .row-fluid -->
    	<?php } 
		if ($status==2) {	// Listado Carga
			if(isset($_POST['page'])){
			    $page= $_POST['page'];
			}else{
			    $page=1;
			}	
			$rows_per_page= 100;
			$query = "SELECT * from cargas_iansa_paso ";
			$result = mysqli_query($conn,$query);
			$num_rows=mysqli_num_rows($result);	
			$lastpage= ceil($num_rows / $rows_per_page);
			//COMPRUEBO QUE EL VALOR DE LA P�GINA SEA CORRECTO Y SI ES LA ULTIMA P�GINA
			$page=(int)$page;
			if($page > $lastpage){
			    $page= $lastpage;
			}
			if($page < 1){
			    $page=1;
			}	
			//CREO LA SENTENCIA LIMIT PARA A�ADIR A LA CONSULTA QUE DEFINITIVA
			$limit= 'LIMIT '. ($page -1) * $rows_per_page . ',' .$rows_per_page;
			?>
	        <!-- Build page from here: -->
            <div class="row-fluid">

                <div class="span12">
                    
                    <div class="box">

                        <div class="title">
                            <h4>
                                <span class="icon16 icomoon-icon-cube"></span>
                                <span>Listado de Carga Asignaci&oacute;n IANSA</span>
                            </h4>
                        </div>
                        <div class="content noPad">
                            <table class="responsive table table-bordered">
                                <thead>
                                  <tr>
                                    <th style="text-align: center; width: 3%;">#</th>
                                    <th style="text-align: center;">Ruta</th>
                                    <th style="text-align: left;">Destinatario</th>
                                    <th style="text-align: left;">Nombre</th>
                                    <th style="text-align: left;">Comuna</th>
                                    <th style="text-align: right;">Total</th>
                                    <!--<th style="text-align: center; width: 30px;">Tipo</th>
                                    <th style="text-align: left; width: 90px;">Transporte</th>-->
                                    <th style="text-align: left;">Nombre Chofer</th>
                                    <th style="text-align: left;">Rut Chofer</th>
                                    <th style="text-align: left;">Patente</th>
                                    <th style="text-align: left;">Causante</th>
                                    <th style="text-align: left;">OC</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php
								$contador=(($page-1)*($rows_per_page));
								$query = "SELECT A.* FROM cargas_iansa_paso A ORDER BY A.id ASC ";
								$query .= " $limit";
								$result = mysqli_query($conn,$query);
								while ($row = mysqli_fetch_array($result))
								{
                        		    echo "<TR>\n";
                        		    $contador=$contador+1;
									?>
                                      <tr>
                                        <td style="text-align: center;"><?php echo $contador;?></td>
                                        <td style="text-align: center;"><?php echo $row["ruta"];?></td>
                                        <td style="text-align: left;"><?php echo $row["destinatario"];?></td>
                                        <td style="text-align: left;"><?php echo $row["nombre_destinatario"];?></td>
                                        <td style="text-align: left;"><?php echo $row["comuna"];?></td>
                                        <td style="text-align: right;"><?php echo $row["total"];?></td>
                                        <!--<td style="text-align: center;"><?php echo $row["tipo"];?></td>
                                        <td style="text-align: left;"><?php echo $row["transporte"];?></td>-->
                                        <td style="text-align: left;"><?php echo $row["nombre_chofer"];?></td>
                                        <td style="text-align: left;"><?php echo $row["rut_chofer"];?></td>
                                        <td style="text-align: left;"><?php echo $row["patente"];?></td>
                                        <td style="text-align: left;"><?php echo $row["causante"];?></td>
                                        <td style="text-align: left;"><?php echo $row["oc"];?></td>
                                      </tr>
                                <?php      
								}
								mysqli_free_result($result);
								?>
                                </tbody>
                            </table>
                        </div>
						<?php
						//UNA VEZ Q MUESTRO LOS DATOS TENGO Q MOSTRAR EL BLOQUE DE PAGINACI�N SIEMPRE Y CUANDO HAYA M�S DE UNA P�GINA
						if($num_rows != 0){
							$nextpage= $page +1;
							$prevpage= $page -1;
							?>
                            <div class="pagination">
							<table border=0 cellspacing=0 cellpadding=0 align="center">
							<tr>
							<td>	
								<ul id="pagination-digg">
								<?php
								//SI ES LA PRIMERA P�GINA DESHABILITO EL BOTON DE PREVIOUS, MUESTRO EL 1 COMO ACTIVO Y MUESTRO EL RESTO DE P�GINAS
								if ($page == 1) {
								?>
									<li><a href="#"><span class="icon12 minia-icon-arrow-left-3"></span></a></li>											
									<li class="active"><a href="#">1</a></li> 
								<?php
									for($i= $page+1; $i<= $lastpage ; $i++){
								?>
										<li><a href="#" onClick="enlacePaginacion(<?php echo trim($i);?>);"><?php echo $i;?></a></li>
								<?php 
							 		}
								//Y SI LA ULTIMA P�GINA ES MAYOR QUE LA ACTUAL MUESTRO EL BOTON NEXT O LO DESHABILITO
									if($lastpage >$page ){
								?>		
										<li><a href="#" onClick="enlacePaginacion(<?php echo trim($nextpage);?>);"><span class="icon12 minia-icon-arrow-right-3"></span></a></li>
								<?php
									}else{
								?>
										<li><a href="#"><span class="icon12 minia-icon-arrow-right-3"></span></a></li>
								<?php	
									}
								} else {
								//EN CAMBIO SI NO ESTAMOS EN LA P�GINA UNO HABILITO EL BOTON DE PREVIUS Y MUESTRO LAS DEM�S
								?>
									<li><a href="#" onClick="enlacePaginacion(<?php echo trim($prevpage);?>);"><span class="icon12 minia-icon-arrow-left-3"></span></a></li>											
								<?php
									for($i= 1; $i<= $lastpage ; $i++){
										//COMPRUEBO SI ES LA P�GINA ACTIVA O NO
										if($page == $i){
								?>	
											<li class="active"><a href="#"><?php echo $i;?></a></li>
								<?php
										}else{
								?>	
											<li><a href="#" onClick="enlacePaginacion(<?php echo trim($i);?>);"><?php echo $i;?></a></li>
								<?php
										}
									}
									//Y SI NO ES LA �LTIMA P�GINA ACTIVO EL BOTON NEXT		
									if($lastpage >$page ){	
								?>	
										<li><a href="#" onClick="enlacePaginacion(<?php echo trim($nextpage);?>);"><span class="icon12 minia-icon-arrow-right-3"></span></a></li>
								<?php
									}else{
								?> 
										<li><a href="#"><span class="icon12 minia-icon-arrow-right-3"></span></a></li>
								<?php
									}
								}	  
								?>
								</ul>
							</td>
							</tr>
							</table>
							</div>
						<?php
						} ?>			

                    </div><!-- End .box -->

                </div><!-- End .span6 -->

            </div><!-- End .row-fluid -->

    	<?php } ?>
        </div><!-- End #content -->
    </div><!-- End #wrapper -->
	<input type="hidden" name="oper" value="<?php echo $oper;?>" />
	<input type="hidden" name="planilla" value="<?php echo $newname1;?>" />
	<input type="hidden" name="status" value="<?php echo $status;?>" />
	<input type="hidden" name="max_file_size" value="800000">
	<input type="hidden" name="maximo" value="800000">
	<input type="hidden" name="lbl_maximo" value="[8MBytes]">
    </form>
    </body>
</html>
<?php } ?>
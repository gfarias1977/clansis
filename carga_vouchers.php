<?php
	session_start();
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             	// fecha pasada...
  	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 	// ultima modificacion
  	header("Cache-Control: no-cache, must-revalidate");           	// HTTP/1.1
  	header("Pragma: no-cache");
	header("Content-type: text/html; charset=utf8");
    set_time_limit(0);	
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
		$opcion="VOUCHERS";
		$hoy=date("d/m/Y");
		$ayer=date("d/m/Y",strtotime("+1 day"));
		if ($fecha=="") {
			$fecha=$ayer;
		}
		$sw_error=0;
		//
		$mensaje="";
		if ($oper==0) {	
			if ($status==0) {
				$query = "UPDATE guias_iansa SET v_en_transito = NULL WHERE  estadoRC = 1 AND voucher IS NULL;";
				$result = mysqli_query($conn,$query); 
			}
			if ($status==4) {	// Procesar Datos cargados
				$num_reg=0;
				$query = "SELECT * from guias_iansa Where estadoRC = 1 AND v_en_transito IS NOT NULL;";
				$result = mysqli_query($conn,$query);
				//echo $query;
				while ($row = mysqli_fetch_array($result))
				{
					$query = "UPDATE guias_iansa SET voucher = '".trim($row["v_en_transito"])."' WHERE id = ".trim($row["id"]).";";
					$result2 = mysqli_query($conn,$query);
					$num_reg++;
				}
				mysqli_free_result($result);
				//
				$query = "UPDATE guias_iansa SET v_en_transito = NULL WHERE v_en_transito IS NOT NULL;";
				$result2 = mysqli_query($conn,$query);
				//				
				$mensaje = "Fueron procesados ".trim($num_reg)." documentos.";
				$status=0;
			}
			if ($status==1) {
				if ($_FILES[vouchers][name]!="") {
					if ($_FILES[vouchers][size] > $maximo) {
						$sw_error=1;
					} else {
						if ($_FILES[vouchers][type]!="application/octet-stream" && $_FILES[vouchers][type]!="application/vnd-ms-excel" && $_FILES[vouchers][type]!="application/vnd.ms-excel" && $_FILES[vouchers][type]!="application/x-msexcel" && $_FILES[vouchers][type]!="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
							$sw_error=2;
						}
					}
				}
				if ($sw_error==0) {
					if ($_FILES[vouchers][name]!="") {
					    $path1 = "temp/";          
					    $ext1 = strtolower(right($_FILES[vouchers][name], 4));
					    $source1 = $_FILES[vouchers][tmp_name];
					    $source_name1 = $_FILES[vouchers][name];
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
			        $arr_facturas = array();
			        $ind_facturas = 0;
					$res = "";
					$filaIni = 1;
					$coluIni = 1;
					$data = new Spreadsheet_Excel_Reader();
					$data->setOutputEncoding('CP1251');
					if ($newname1!="") {
						$data->read($newname1);
						$filaIni = 2;
						if ($data->sheets[0]['numCols'] != 4) {
							$sw_error=3;
							$res = "NUMERO COLUMNAS PLANILLA NO CORRESPONDE. COLUMNAS ESPERADAS: 4.";
						} else {
							$num_filas = $data->sheets[0]['numRows'];
							$num_columnas = $data->sheets[0]['numCols'];
							$ind_error = 0;
							for ($i = $filaIni; $i <= $num_filas; $i++) {
								$_causante = "";
								$_guia = "";
								$_voucher = "";
								$_factura = "";
								//
								if (trim($data->sheets[0]['cells'][$i][1])=="") {
									$_causante = "";
								} else {
									$_causante = trim($data->sheets[0]['cells'][$i][1]);
								}
								if (trim($data->sheets[0]['cells'][$i][2])=="") {
									$_guia = "";
								} else {
									$_guia = trim($data->sheets[0]['cells'][$i][2]);
								}
								if ( (substr($_guia, 0, 3)=="525") || (substr($_guia, 0, 3)=="526") || (substr($_guia, 0, 3)=="527") ) {
									$guia_real = substr($_guia, 3, strlen($_guia)-3);
								} else {
									$guia_real = $_guia;
								}
								$guia_real = trim(intval($guia_real));
								if (trim($data->sheets[0]['cells'][$i][3])=="") {
									$_voucher = "";
								} else {
									$_voucher = trim($data->sheets[0]['cells'][$i][3]);
								}
								if (trim($data->sheets[0]['cells'][$i][4])=="") {
									$_factura = "";
								} else {
									$_ref = trim($data->sheets[0]['cells'][$i][4]);
									if (strtoupper(substr($_ref,0,1))=="F") {
										$_factura = trim($data->sheets[0]['cells'][$i][4]);
									}
								}
								//
								if ($_causante != "" && $guia_real != "" && $_voucher != "") {
									$query = "SELECT * from guias_iansa Where causante = '".$_causante."' and guia = '".$guia_real."';";
									$result2 = mysqli_query($conn,$query);
									$id_guia = 0;
									$est_rc = 0;
									while ($row2 = mysqli_fetch_array($result2))
									{
										$id_guia = $row2["id"];
										$est_rc = $row2["estadoRC"];
									}
									mysqli_free_result($result2);
									// 
									if ($id_guia != 0) {
										if ($est_rc == 1) {
											$query = "UPDATE guias_iansa SET v_en_transito = '".trim($_voucher)."' WHERE id =".trim($id_guia).";";
											//echo $query;
											$result2 = mysqli_query($conn,$query); 
											$status = 2;	// Se procesaron algunas guias
										}
									} else {
										//echo "::FACTURA::<br>";
										if ($_factura != "") {
											//echo "::2:: ".trim($_factura);
											$query = "SELECT * from guias_iansa Where estadoRC = 1 and causante = '".$_causante."' and guia = '".$_factura."';";
											$result2 = mysqli_query($conn,$query);
											//echo $query;
											$id_reg = 0;
											while ($row2 = mysqli_fetch_array($result2))
											{
												$existe_factura = false;
												for ($i2=1; $i2 <= $ind_facturas; $i2++) {
													if ($arr_facturas[$i2]==$_factura) {
														$existe_factura = true;
														break;
													}	
												}
												if ($existe_factura==false) {
													$ind_facturas++;
													$arr_facturas[$ind_facturas]=$_factura;
												}
												//
												$id_reg = $row2["id"];
												$asignacion_id = $row2["asignacion_id"];
												$causante = $row2["causante"];
												$comentario = $row2["comentario"];
												if (is_null($row2["CHEP"])) {
													$CHEP = "NULL";
												} else {		
													$CHEP = $row2["CHEP"];
												}
												if (is_null($row2["IANSA"])) {
													$IANSA = "NULL";
												} else {		
													$IANSA = $row2["IANSA"];
												}
												$estado = $row2["estado"];
												$preselec = $row2["preselec"];
												$folio = $row2["folio"];
												$fechaRC = $row2["fechaRC"];
												$comentarioRC = $row2["comentarioRC"];
												$estadoRC = $row2["estadoRC"];
											}
											mysqli_free_result($result2);
											// 
											if ($id_reg != 0) {
												$query = "INSERT INTO guias_iansa VALUES(NULL,".trim($asignacion_id).",'".trim($causante)."','".trim($guia_real)."','".trim($comentario)."', ".trim($CHEP).", ".trim($IANSA).", ".trim($estado).", ".trim($preselec).", ".trim($folio).", '".trim($fechaRC)."', '".trim($comentarioRC)."', ".trim($estadoRC).", NULL, '".trim($_voucher)."', '".trim($_factura)."', 0);";
												//echo $query;
												$result2 = mysqli_query($conn,$query); 
												$status = 2;	// Se procesaron algunas guias
											}
										}
									}
								}
							}
							if ($ind_facturas > 0) {
								for ($i2=1; $i2 <= $ind_facturas; $i2++) {
									$query = "DELETE FROM guias_iansa WHERE guia ='".trim($arr_facturas[$i2])."';";
									//echo $query;
									$result2 = mysqli_query($conn,$query); 
								}
							}
						}
					}
				}
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
	    		text: '\nPlanilla excede m\341ximo permitido:\n [2MBytes].',
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
        
    function inicio() 
    {
		$("#anima").hide();		
    }
    
	function cargar_planilla() {
		var ret=0;
		if (document.cargas.vouchers.value=="") {
			ret=1;
			alert("Debe seleccionar Planilla.");
			document.cargas.vouchers.focus();
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
    <form method="post" action="carga_vouchers.php" target="_self" enctype="multipart/form-data" name="cargas" id="cargas" onSubmit="return false;">
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
                        <li class="active" style="color: white;">Carga Vouchers</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                    <div class="row-fluid">

                        <div class="span12">
                            
                            <div class="box">

                                <div class="title">
                                    <h4>
                                        <span class="icon16 icomoon-icon-vimeo-2"></span>
                                        <span>Carga Vouchers</span>
                                    </h4>
                                </div>
                                <div class="content noPad" style="height: 50px;">
	                                <div class="form-row row-fluid" style="border: 0px red dotted;">
                                        <span class="span2" style="text-align: left; border: 0px yellow dotted; margin-left: 10px; margin-top: 10px;">
                                        <input type="file" name="vouchers" id="vouchers" />
                                        </span>
		                                <div class="btn-group span5" style="text-align: center; border: 0px blue dotted; margin-left: 50px; margin-top: 10px;">
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
                                    <table class="responsive table table-bordered" style="margin-top: 0px;">
                                        <thead>
                                          <tr>
                                            <th style="text-align: center; width: 3%;">#</th>
                                            <th style="text-align: left; width: 90px;">Patente</th>
                                            <th style="text-align: left; width: 150px;">Causante</th>
                                            <th style="text-align: left; width: 100px;">Guia</th>
                                            <!--<th style="text-align: center; width: 50px;">CHEP</th>-->
                                            <th style="text-align: center; width: 50px;">PALLETS</th>
                                            <th style="text-align: left;">OBSERVACIONES</th>
                                            <th style="text-align: left; width: 70px;">Voucher</th>
                                            <th width="20" align='center'>&nbsp;</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        <?php
										$contador=0;
										$query = "SELECT A.*, B.patente from guias_iansa A LEFT JOIN cargas_iansa B ON A.asignacion_id=B.id WHERE A.estadoRC = 1 AND A.voucher IS NULL ORDER BY A.guia ASC ";
										//echo $query;
										$result = mysqli_query($conn,$query);
										while ($row = mysqli_fetch_array($result))
										{
                                		    $contador=$contador+1;
	                                		    $guia = trim($row["guia"]);
	                                		    $preselec = trim($row["preselec"]);
	                                		    if ($modo==2) {
		                                		    if (!is_null($row["comentario"])) {
			                                		    $com_pendientes = utf8_encode(trim($row["comentario"]));
		                                		    } else {
			                                		    $com_pendientes = "";
		                                		    }
	                                		    } else {
		                                		    $com_pendientes = "";
	                                		    }
												?>
		                                          <tr>
		                                            <td style="text-align: center;"><?php echo $contador;?></td>
		                                            <td style="text-align: left;"><?php echo trim($row["patente"]);?></td>
		                                            <td style="text-align: left;"><?php echo trim($row["causante"]);?></td>
		                                            <td style="text-align: left;"><?php echo trim($row["guia"]);?></td>
		                                            <!--<td style="text-align: center;"><?php echo trim($row["CHEP"]);?></td>-->
		                                            <td style="text-align: center;"><?php echo trim($row["IANSA"]);?></td>
		                                            <td style="text-align: left;"><?php echo $com_pendientes;?></td>
		                                            <td style="text-align: left;"><?php echo trim($row["v_en_transito"]);?></td>
		                                            <?php 
		                                            if (is_null($row["v_en_transito"])) { ?>
			                                            <td style="text-align: center;"><img name="img_<?php echo trim($contador);?>" id="img_<?php echo trim($contador);?>" src="images/no.png"></td>
			                                        <?php } else { ?>
			                                            <td style="text-align: center;"><img name="img_<?php echo trim($contador);?>" id="img_<?php echo trim($contador);?>" src="images/si.png"></td>
			                                        <?php } ?>    
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

            </div><!-- End contentwrapper -->
    	<?php } ?>
        </div><!-- End #content -->
    </div><!-- End #wrapper -->
	<input type="hidden" name="oper" value="<?php echo $oper;?>" />
	<input type="hidden" name="planilla" value="<?php echo $newname1;?>" />
	<input type="hidden" name="status" value="<?php echo $status;?>" />
	<input type="hidden" name="max_file_size" value="2000000">
	<input type="hidden" name="maximo" value="2000000">
	<input type="hidden" name="lbl_maximo" value="[2MBytes]">
    </form>
    </body>
</html>
<?php } ?>
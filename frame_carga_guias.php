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
		include "include/header.php";
		$conn=opendblocal();
		$nom_usuario = 	$_SESSION["nombreUsuario"];
		$rol_usuario = $_SESSION["rolUsuario"];
		$id_usuario = $_SESSION["idUsuario"];
		date_default_timezone_set("Chile/Continental");		
		$hoy=date("d/m/Y");
		$sw_error=0;
		//
		if ($lectura!="") {
			$lcadena = strlen($lectura);
			$causante = "";
			$guia = "";
			$buffer = "";
			$caracter = "";
			$inicia_OV = false;
			$inicia_NG = false;
			for ($i=0; $i<$lcadena; $i++) {
				$caracter = substr($lectura, $i, 1);
				if ($caracter==";") {
					$buffer = "";
				} else {
					$buffer = $buffer . $caracter;
					if ($inicia_OV==true) {
						if ($caracter=="-") {
							$inicia_OV = false;
						} else {
							$causante = $causante . $caracter;
						}
					}
					if ($inicia_NG==true) {
						if ($caracter=="-") {
							$inicia_NG = false;
						} else {
							$guia = $guia . $caracter;
						}
					}
				}
				if ($buffer=="OV:") {
					$inicia_OV = true;
				}
				if ($buffer=="NG:") {
					$inicia_NG = true;
				}
			}
			if (substr($guia, 0, 3)=="525" || substr($guia, 0, 3)=="527") {
				$guia_real = substr($guia, 3, strlen($lectura)-3);
			} else {
				$guia_real = $guia;
			}
			$guia_real = trim(intval($guia_real));
			//
            $asignacion_id=0;
			$query = "SELECT A.* FROM cargas_iansa A WHERE causante = '".trim($causante)."' AND DATE_FORMAT(A.fecha, '%d/%m/%Y') = '".strtoupper(trim($f_carga))."';";
			$result2 = mysqli_query($conn,$query);
			while ($row2 = mysqli_fetch_array($result2))
			{
                $asignacion_id=$row2["id"];
			}
			mysqli_free_result($result2);
			//			
			if ($asignacion_id != 0) {
	            $existe_guia=false;
				$query = "SELECT A.* FROM guias_iansa A WHERE asignacion_id=".trim($asignacion_id)." AND causante = '".trim($causante)."' AND A.guia='".trim($guia_real)."';";
				$result2 = mysqli_query($conn,$query);
				while ($row2 = mysqli_fetch_array($result2))
				{
	                $existe_guia=true;
				}
				mysqli_free_result($result2);
				//
				if ($existe_guia==false) {
					$query = "INSERT INTO guias_iansa VALUES(NULL,".trim($asignacion_id).",'".trim($causante)."','".trim($guia_real)."','', NULL, NULL, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0);";
					//echo $query;
					$result = mysqli_query($conn,$query);
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
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
      
    <body onLoad="inicio();">
    <form method="post" action="frame_carga_guias.php" target="_self" name="listado" id="listado" onSubmit="return false;">
    <div id="wrapper" style="margin-top: 6px;">

        <!--Body content-->
        <?php
		if ($oper==0 && $f_carga != "") {	// Visualizar
			$tiene_filtro = false;
			if ($f_carga <> "") {
				if ($tiene_filtro==false) {
					$tiene_filtro = true;
					$condicion = " WHERE DATE_FORMAT(A.fecha, '%d/%m/%Y') = '".strtoupper(trim($f_carga))."'";
				} else {
					$condicion = $condicion . " AND DATE_FORMAT(A.fecha, '%d/%m/%Y') = '".strtoupper(trim($f_carga))."'";
				}
			}	
			?>
            <div class="contentwrapper" style="margin-top: 0px;"><!--Content wrapper-->


                <!-- Build page from here: -->
                    <div class="row-fluid" style="margin-top: 0px;">

                        <div class="span12" style="margin-top: 0px;">
                            
                            <div class="box" style="margin-top: 0px;">

                                <div class="content noPad" style="margin-top: 0px;">
                                
                                    <table class="responsive table table-bordered" style="margin-top: 0px;">
                                        <thead>
                                          <tr>
                                            <th style="text-align: center; width: 3%;">#</th>
                                            <th style="text-align: center; width: 35px;">Ruta</th>
                                            <th style="text-align: left;">Destinatario</th>
                                            <th style="text-align: left; width: 80px;">Rut</th>
                                            <th style="text-align: left; width: 150px;">Nombre Chofer</th>
                                            <th style="text-align: left; width: 100px;">Patente</th>
                                            <th style="text-align: left; width: 110px;">Causante</th>
                                            <th style="text-align: left;">Guias</th>
                                            <th width="20" style="text-align: center;">&nbsp;</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        <?php
										$contador=0;
										$query = "SELECT A.* FROM cargas_iansa A ".$condicion." ORDER BY A.id ASC ";
										//echo $query;
										$result = mysqli_query($conn,$query);
										while ($row = mysqli_fetch_array($result))
										{
                                		    echo "<TR>\n";
                                		    $contador=$contador+1;
                                		    if (!is_null($row["detalle"])) {
	                                		    $detalle_des = utf8_encode(trim($row["detalle"]));
                                		    } else {
	                                		    $detalle_des = "";
                                		    }
											?>
	                                          <tr>
	                                            <td style="text-align: center;"><?php echo $contador;?></td>
	                                            <td style="text-align: center;"><?php echo trim($row["ruta"]);?></td>
	                                            <td style="text-align: left;"><?php echo trim($row["nombre_destinatario"]);?></td>
	                                            <td style="text-align: left;"><?php echo trim($row["rut_chofer"]);?></td>
	                                            <td style="text-align: left;"><?php echo trim($row["nombre_chofer"]);?></td>
	                                            <td style="text-align: left;"><?php echo trim($row["patente"]);?></td>
	                                            <td style="text-align: left;"><?php echo trim($row["causante"]);?></td>
	                                            <td style="text-align: left;">
	                                            <?php
	                                            $tiene_guias=false;
												$query = "SELECT A.* FROM guias_iansa A WHERE A.asignacion_id=".trim($row["id"])." ORDER BY A.id ASC ";
												$result2 = mysqli_query($conn,$query);
												while ($row2 = mysqli_fetch_array($result2))
												{
	                                            	echo trim($row2["guia"])."<br>";
		                                            $tiene_guias=true;
												}
												mysqli_free_result($result2);
												?>
	                                            </td>
	                                            <?php
	                                            if ($tiene_guias==true) { ?>
		                                            <td style="text-align: center;"><img src="images/si.png" title="Con Guia"></td>
		                                        <?php } else { ?>
		                                            <td style="text-align: center;"><img src="images/tick_no.png" title="Sin Guia"></td>
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
    	<?php } 
    	?>
    </div><!-- End #wrapper -->
	<input type="hidden" name="oper" value="<?php echo $oper;?>" />
    </form>
    </body>
</html>
<?php } ?>
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
		if ($status==1) {
			if ($faena==0) {
				$query = "UPDATE cargas_otras_faenas A SET A.preselec = 1 WHERE A.estado = 1;";
			} else {
				$query = "UPDATE cargas_otras_faenas A SET A.preselec = 1 WHERE A.estado = 1 AND A.faena =".trim($faena).";";
			}
			//echo $query;
			$result = mysqli_query($conn,$query); 
			$status=0;			
		}
		if ($status==2) {
			if ($faena==0) {
				$query = "UPDATE cargas_otras_faenas A SET A.preselec = 0 WHERE A.estado = 1;";
			} else {
				$query = "UPDATE cargas_otras_faenas A SET A.preselec = 0 WHERE A.estado = 1 AND A.faena =".trim($faena).";";
			}
			//echo $query;
			$result = mysqli_query($conn, $query); 
			$status=0;			
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
    <script>
    function inicio() {
    }
    function todo_si() {
		document.listado.status.value=1;
		document.listado.submit();
    }
    function todo_no() {
		document.listado.status.value=2;
		document.listado.submit();
    }
	function seleccionar( imagen, id )
	{
		fuente = document.getElementById(imagen).src;
		n = fuente.length;
		name = fuente.substr(n-6, n);
		if (name=="si.png") {
			modo = 1
		} else {
			modo = 0
		}
		p0 = id;
		p1 = modo;
		$.post("ayax_actualiza_estado_rendicion.php",{ p0: p0, p1: p1 },function(data){})
		//
		if (modo==1) {
			document.getElementById(imagen).src	= "images/no.png";
		} else {
			document.getElementById(imagen).src	= "images/si.png";
		}	
		document.listado.id_procesado.value=id;
	}
    </script>
    <body onLoad="inicio();">
    <form method="post" action="frame_rendicion.php" target="_self" name="listado" id="listado" onSubmit="return false;">
    <div id="wrapper" style="margin-top: 6px;">

        <!--Body content-->
        <?php
		if ($oper==0) {	// Visualizar
			$tiene_filtro = true;
			$query = "SELECT A.* from cargas_otras_faenas A ";
			$condicion = " WHERE A.estado = 1 ";
			if ($faena <> "0" && $faena <> "") {
				if ($tiene_filtro==false) {
					$tiene_filtro = true;
					$condicion = " WHERE A.faena = ".trim($faena)." ";
				} else {
					$condicion = $condicion . " AND A.faena = ".trim($faena)." ";
				}
			}	
			?>
            <div class="contentwrapper" style="margin-top: 0px;"><!--Content wrapper-->


                <!-- Build page from here: -->
                    <div class="row-fluid" style="margin-top: 0px;">

                        <div class="span12" style="margin-top: 0px;">
                            
                            <div class="box" style="margin-top: 0px;">

                                <div class="content noPad" style="margin-top: 0px;">
                                
                                    <table class="responsive table table-bordered">
                                        <thead>
                                          <tr>
                                            <th style="text-align: center; width: 3%;">#</th>
                                            <th style="text-align: left; width: 70px;">Guia</th>
                                            <th style="text-align: left;">Descfipci&oacute;n</th>
                                            <th style="text-align: left; width: 150px;">Faena</th>
                                            <th style="text-align: left; width: 80px;">Patente</th>
                                            <th style="text-align: left; width: 150px;">Chofer</th>
                                            <th width="40" align='center'><a href="#" onClick="todo_si();"><img name="img_si_all" id="img_si_all" src="images/si.png" style="cursor: pointer;" title="Selecciona Todo"></a>&nbsp;<a href="#" onClick="todo_no();"><img name="img_no_all" id="img_no_all" src="images/no.png" style="cursor: pointer;" title="Deselecciona Todo"></a></th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        <?php
										$contador=(($page-1)*($rows_per_page));
										$query = "SELECT A.*, B.descripcion AS 'DES_FAENA', C.patente AS 'DES_PATENTE', D.nombre, D.apellidos FROM cargas_otras_faenas A LEFT JOIN faenas B ON A.faena=B.id LEFT JOIN patentes C ON A.patente=C.id LEFT JOIN choferes D ON A.chofer=D.id ".$condicion." ORDER BY A.patente, A.guia ASC ";
										$query .= " $limit";
										//echo "<tr><td colspan=6>".$query."</td></tr>\n";
										$result = mysqli_query($conn,$query);
										while ($row = mysqli_fetch_array($result))
										{
                                		    echo "<TR>\n";
                                		    $contador=$contador+1;
                                		    $preselec = trim($row["preselec"]);
                                		    if (!is_null($row["descripcion"])) {
	                                		    $detalle_des = utf8_encode(trim($row["descripcion"]));
                                		    } else {
	                                		    $detalle_des = "";
                                		    }
                                		    $chofer = "";
                                		    if (!is_null($row["nombre"])) {
	                                		    $chofer = trim($row["nombre"]);
                                		    }
                                		    if (!is_null($row["apellidos"])) {
	                                		    $chofer = $chofer . " " . trim($row["apellidos"]);
                                		    }
											?>
	                                          <tr>
	                                            <td style="text-align: center; vertical-align: middle; font-size: 100%;"><?php echo $contador;?></td>
	                                            <td style="text-align: left; vertical-align: middle;"><?php echo strtoupper(trim($row["guia"]));?></td>
	                                            <td style="text-align: left; vertical-align: middle;"><?php echo $detalle_des;?></td>
	                                            <td style="text-align: left; vertical-align: middle; font-size: 100%;"><?php echo strtoupper(trim($row["DES_FAENA"]));?></td>
	                                            <td style="text-align: left; vertical-align: middle; font-size: 110%; font-weight: bold;"><?php echo strtoupper(trim($row["DES_PATENTE"]));?></td>
	                                            <td style="text-align: left; vertical-align: middle; font-size: 100%;"><?php echo strtoupper(trim($chofer));?></td>
		                                            <?php 
		                                            if ($preselec==1) { ?>
			                                            <td style="text-align: center;"><a href="#" onClick="seleccionar('img_<?php echo trim($contador);?>', <?php echo trim($row['id']);?>);"><img name="img_<?php echo trim($contador);?>" id="img_<?php echo trim($contador);?>" src="images/si.png" style="cursor: pointer;"></a></td>
			                                        <?php } else { ?>
			                                            <td style="text-align: center;"><a href="#" onClick="seleccionar('img_<?php echo trim($contador);?>', <?php echo trim($row['id']);?>);"><img name="img_<?php echo trim($contador);?>" id="img_<?php echo trim($contador);?>" src="images/no.png" style="cursor: pointer;"></a></td>
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
	<input type="hidden" name="status" value="<?php echo $status;?>" />
	<input type="hidden" name="modo" value="<?php echo $modo;?>" />
	<input type="hidden" name="faena" value="<?php echo $faena;?>" />
	<input type="hidden" name="id_procesado" value="" />
	<input type="hidden" name="id_guia" value="<?php echo $id_guia;?>" />
    </form>
    </body>
</html>
<?php } ?>
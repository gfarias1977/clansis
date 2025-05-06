<?php
	session_start();
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             	// fecha pasada...
  	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 	// ultima modificacion
  	header("Cache-Control: no-cache, must-revalidate");           	// HTTP/1.1
  	header("Pragma: no-cache");
	header("Content-type: text/html; charset=utf8");
	
	// carga rutinas de acceso a base de datos
	include "include/header.php";
	include "include/funciones.php";
	$conn=opendblocal();
	$hoy=date("d/m/Y");

	$salir=0;	
	$sw_error=0;
	if ($oper==0) {	
		if ($status==1) {
			$grabar=1;
			//
			// Graba Registro Imagen
			//
			$patente_real_="NULL";
			$observaciones_="NULL";
			if ($patente_real != "") {
				$patente_real_ = "'".strtoupper(trim($patente_real))."'";
			}
			if ($observaciones != "") {
				$observaciones_ = "'".strtoupper(trim($observaciones))."'";
			}
			$query = "UPDATE cargas_iansa SET patente_real = ".trim($patente_real_).", observaciones = ".trim($observaciones_)." WHERE id = ".trim($id_asig);
			$result = mysqli_query($conn,$query);
			//
			$oper=0;
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

    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />
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
    <link href="plugins/pretty-photo/prettyPhoto.css" type="text/css" rel="stylesheet" />
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
    
    <!-- <style>
    Anula Scroll Vertical 
	html, body {margin: 0; height: 100%; overflow: hidden}    
	</style>-->

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
    <script type="text/javascript" src="plugins/qtip/jquery.qtip.min.js"></script>
    <script type="text/javascript" src="js/statistic.js"></script><!-- Control graphs ( chart, pies and etc) -->

    <!-- Important Place before main.js  -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
    <script type="text/javascript" src="plugins/touch-punch/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    
    <script>
	function inicio() {
		<?php if ($grabar==1) { 
			echo "parent.cancelar_dat(0);\n";
		}?>
	}
 	<?php
 	if ($sw_error==1) { ?>
	 	$(document).ready(function(){
			$.pnotify({
				type: 'error',
			    title: 'ERROR GRABACION!',
	    		text: '\nDocumento ya existe para esta Causante.',
			    icon: 'picon icon24 typ-icon-cancel white',
			    opacity: 0.95,
			    history: false,
			    sticker: false
			});
		});
	<?php } ?>
	function guardar()
	{
		var ret=0;
		if (document.datos.patente_real.value=="" && document.datos.observaciones.value=="") {
			alert("No ha indicado datos nuevos para esta asignacion.");
			document.datos.patente_real.focus();
			ret=1;
		}
		if (ret==0) {
			document.datos.status.value=1;
			document.datos.submit();
		}
	}
	function salir() {
		parent.cancelar_dat(0);
	}
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
      
    <body onLoad="inicio();">
    <form method="post" action="agregar_datos_asignacion.php" target="_self" name="datos" id="datos">

	<?php
	if ($oper==0) { 
		$query = "SELECT A.* FROM cargas_iansa A WHERE A.id=".trim($id_asig);
		//echo $query;
		$result = mysqli_query($conn,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$chofer = $row["nombre_chofer"];
			$patente = $row["patente"];
			$causante = $row["causante"];
			$patente_real = $row["patente_real"];
			$observaciones = $row["observaciones"];
		}
		mysqli_free_result($result);
		//
		$error=0;
		?>
		<div class="row-fluid">
        <div class="span12">
        <div class="box">

    		<div class='title'>
            </div>
            <div class='content' style="height: 360px;">
                <div class='form-row row-fluid' style="margin-top: 0px;">
                    <div class='span12'>
                    	<!--
                        <div class='row-fluid' style="margin-left: 0px; margin-top: 0px;">
                            <span class="span3"><label class="form-label" for="normal">CHOFER</label></span>
	                        <input id="chofer" name="chofer" type="text" value="<?php echo $chofer;?>" maxlength="30"  disabled="disabled" style="width: 240px;" />
                        </div>
                        <div class="row-fluid">
                            <span class="span3"><label class="form-label" for="normal">PATENTE</label></span>
	                        <input id="patente" name="patente" type="text" value="<?php echo $patente;?>" maxlength="12"  disabled="disabled" style="width: 80px;" />
                        </div>
                        -->
                        <div class="row-fluid">
                            <span class="span3"><label class="form-label" for="normal">CAUSANTE</label></span>
	                        <input id="causante" name="causante" type="text" value="<?php echo $causante;?>" maxlength="12"  disabled="disabled" style="width: 120px;" />
                        </div>
                        <div class="row-fluid">
                            <span class="span3"><label class="form-label" for="normal">PATENTE REAL</label></span>
                            <input class="span4 mayuscula" style="width: 128px;" id="patente_real" name="patente_real" type="text" maxlength="12" value="<?php echo $patente_real;?>" />
                        </div>
                        <div class="row-fluid">
                            <span class="span3"><label class="form-label" for="normal">OBSERVACIONES</label></span>
                            <textarea class="span4 mayuscula" id="observaciones" name="observaciones" rows="3" cols="80"><?php echo $observaciones;?></textarea>
                        </div>
                        <div class='row-fluid' style="margin-top: 40px; text-align: right; margin-right: 20px; margin-bottom: 0px;">
                            <a href='#' onClick="salir();" target='_blank'><span class='btn btn-danger'>Cerrar Ventana</span></a>
                            <a href='#' onClick="guardar();" target='_blank'><span class='btn btn-success'>Guardar Cambios</span></a>
                        </div>
                        
                    </div>
				</div>
			</div>
			
		</div>
		</div>
		</div>
		<?php
	}

?>
	<input type="hidden" name="id_asig" value="<?php echo $id_asig;?>" />
	<input type="hidden" name="causante2" value="<?php echo $causante;?>" />
	<input type="hidden" name="oper" value="<?php echo $oper;?>" />
	<input type="hidden" name="status" value="0" />
	<input type="hidden" name="recargar" value="<?php echo $recargar;?>" />
    </form>
    </body>
</html>

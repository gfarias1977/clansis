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
		include "include/funciones.php";
		include "genera_repRC.php";
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
		$opcion="RENDICION2";
		date_default_timezone_set("Chile/Continental");		
		$hoy=date("Y-m-d");
		//
		$patente_sel = $f_patente;
		$sw_error=0;
		if ($oper==0) {	
			// Limpia Pre-Seleccion
			if (!isset($primera)) {
			   	$query = "UPDATE guias_iansa SET preselec = 0 WHERE (estado = 1 OR estado = 2) AND (estadoRC = 0) ; ";
				$result = mysqli_query($conn,$query);
			}
			//
			$folio = 0;
			$query = "SELECT MAX(folio) as 'MAX_FOLIO' from guias_iansa ;";
			$result = mysqli_query($conn,$query);
			while ($row = mysqli_fetch_array($result))
			{
				if (!is_null($row["MAX_FOLIO"])) {
					$folio = trim($row["MAX_FOLIO"]);
				}
			}
			mysqli_free_result($result);
			$folio++;
			//
			if ($status==0) {
				$ok = false;
			}
			if ($status==1) {
				$ok = GeneraReporte( $name, $f_modo, $folio );
				$status=0;
			}
			if ($status==2) {
				if ($f_modo == 1) {
				   	$query = "UPDATE guias_iansa SET estadoRC = 1, folio = ".trim($folio).", fechaRC = '".trim($hoy)."' WHERE (estado = 1 AND estadoRC = 0) AND preselec = 1; ";
			   	} 
				if ($f_modo == 2) {
				   	$query = "UPDATE guias_iansa SET estadoRC = 1, folio = ".trim($folio).", fechaRC = '".trim($hoy)."' WHERE (estado = 2 AND estadoRC = 0) AND preselec = 1; ";
			   	} 
			   	//echo $query;
				$result = mysqli_query($conn,$query);
				$status=0;
				$f_modo = 0;
				$ok = false;
			}
		}	
		$conn=opendblocal();
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
	$(document).ready(function(){
		p1 = document.reporte.f_modo.value;
		p2 = document.reporte.patente_sel.value;
    	if (p1 != 0) {
			$.post("carga_patentes.php",{ p1: p1, p2: p2 },function(data){$("#f_patente").html(data);})
    	}
	})
	
    function inicio() 
    {
		document.reporte.target = "_self";
		var lectura = "<?php echo $f_guia;?>";
		var modo = document.reporte.f_modo.value;
		var patente = document.reporte.f_patente.value;
		if (modo != 0) {
			window.frames['ifrm1'].location = "frame_rcliente.php?oper=0&modo="+modo+"&patente="+patente+"&lectura="+lectura+"#punto";
		}
		setTimeout(function(){
		    $(document).find('#f_guia').focus();
		}, 1000);				

    }
	function genReporte() {
		var ret=0;
		if (document.reporte.f_modo.selectedIndex==0) {
			ret=1;
			alert("Debe indicar Tipo Rendicion.");
			document.reporte.f_modo.focus();
		}
		if (ret==0) {
			document.reporte.oper.value=0;
			document.reporte.status.value=1;
			document.reporte.target = "_self";
			document.reporte.submit();
		}
	}
	function verPDF() {
		window.open("<?php echo $name;?>", "_blank");
	}
	function procesar() {
		var ret=0;
		if (document.reporte.f_modo.selectedIndex==0) {
			ret=1;
			alert("Debe indicar Tipo Rendicion.");
			document.reporte.f_modo.focus();
		}
		if (ret==0) {
			document.reporte.oper.value=0;
			document.reporte.status.value=2;
			document.reporte.submit();
		}
	}
	function busqueda()
	{
		var ret=0;
		if (document.reporte.f_modo.selectedIndex!=0) {
			document.reporte.status.value=0;
			document.reporte.f_guia.value = "";
			document.reporte.submit();
		}
	}
	function cargar_patente() 
	{
		p1 = document.reporte.f_modo.value;
		//if (p1!=0) {
			$.post("carga_patentes.php",{ p1: p1, p2: 0 },function(data){$("#f_patente").html(data);})
		//}
		window.frames['ifrm1'].location = "blank.html";
	}
	function Escanear()
	{
		var ret=0;
		if (ret==0) {
			var lectura = document.reporte.f_guia.value;
			var modo= document.reporte.f_modo.value;
			var patente= document.reporte.f_patente.value;
			document.reporte.f_guia.value = "";
			window.frames['ifrm1'].location = "frame_rcliente.php?oper=0&modo="+modo+"&patente="+patente+"&lectura="+lectura+"#punto";
			setTimeout(function(){
			    $(document).find('#f_guia').focus();
			}, 1000);				
		}
	}
    </script>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
      
    <body onLoad="inicio();">
    <form method="post" action="rendicion_cliente.php" target="_self" name="reporte" id="reporte" onSubmit="return false;">
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
                        <li class="active" style="color: white;">Rendici&oacute;n Cliente</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                    <div class="row-fluid">

                        <div class="span12">
                            
                            <div class="box">

                                <div class="title">
                                    <h4>
                                        <span class="icon16 entypo-icon-upload"></span>
                                        <span>Rendici&oacute;n Cliente</span>
                                    </h4>
                                </div>
                                <div class="content noPad" style="height: 48px;">
                                        <span class="span1" style="margin-left: 10px; margin-top: 8px; border: 0px dotted blue;"><button class="btn tipR" title="Filtrar Asignaciones" href="#" onClick="busqueda();"><span class="icon16 brocco-icon-filter"></span></button></span>
                                        <span class="span2" style="margin-left: 0px; margin-top: 8px; border: 0px dotted red;">
                                        <select name="f_modo" id="f_modo" class="nostyle mayuscula" onChange="cargar_patente();" style="width: 140px; min-width: 140px; height: 29px;">
											<OPTION value="0"></OPTION>
											<?php
											if ($f_modo=="1") {
												echo "<OPTION value='1' selected>COMPLETAS</OPTION>\n";
											} else {
												echo "<OPTION value='1'>COMPLETAS</OPTION>\n";
											}
											if ($f_modo=="2") {
												echo "<OPTION value='2' selected>PENDIENTES</OPTION>\n";
											} else {
												echo "<OPTION value='2'>PENDIENTES</OPTION>\n";
											}
											?>
                                        </select>
                                        </span>
                                        <span class="span2" style="margin-left: 0px; margin-top: 8px; border: 0px dotted red;">
                                        <select name="f_patente" id="f_patente" class="nostyle mayuscula" style="width: 140px; min-width: 140px; height: 29px;">
											<OPTION>PATENTE</OPTION>
                                        </select>
                                        </span>
										<span class="span3 right" style="margin-left: 0px; margin-top: 8px; border: 0px dotted red;"><input class="form-control" id="f_guia" name="f_guia" type="text" value="" maxlength="255" placeholder="Escaneo Guia" style="width: 180px;" />
                                        &nbsp;<button class="btn btn-info" href="#" onClick="Escanear();" style="margin-top:-3px;"><span class="icon16 white icon-qrcode"></span></button></span>
										<?php
										if ($ok==true) { ?>
			                                <div class="btn-group span1" style="text-align: left; margin-left: 20px; border: 0px blue dotted; margin-top: 10px;">
		                                        <input type="button" class="nostyle"  id="reportar" name="reportar" value="Ver PDF" onClick="verPDF();">
		                                    </div>
	                                    	<span class="span2" style="margin-left: 10px; text-align: center; border: 0px red dotted; margin-top: 10px;"><input type="button" class="nostyle"  id="reportar" name="reportar" value="Generar Reporte" onClick="genReporte();"></span>
	                                    	<span class="span2" style="margin-left: 10px; text-align: center; border: 0px red dotted; margin-top: 10px;"><input type="button" class="nostyle"  id="reportar" name="reportar" value="Generar Rendici&oacute;n" onClick="procesar();"></span>
		                                <?php } else { ?>
			                                <div class="btn-group span1" style="text-align: center; border: 0px blue dotted; margin-top: 10px;">
			                                	&nbsp;
		                                    </div>
	                                    	<!--<span class="span2" style="margin-left: 10px; text-align: right; border: 0px red dotted; margin-top: 10px;"><button class="btn btn-inverse" href="#" onClick="genReporte();"><span class="icon16 white entypo-icon-printer"></span> Reporte</button></span>-->
	                                    	<span class="span2" style="margin-left: 10px; text-align: center; border: 0px red dotted; margin-top: 10px;"><input type="button" class="nostyle"  id="reportar" name="reportar" value="Generar Reporte" onClick="genReporte();"></span>
		                                <?php } ?>    
                                </div>
                                <!--<div id="ifrm1">
								</div>-->                                
	                           	<iframe name="ifrm1" id="ifrm1" src="blank.html" width="100%" height="400" scrolling="auto" frameborder="0">Sorry, your browser doesn't support iframes.</iframe>

                            </div><!-- End .box -->

                        </div><!-- End .span6 -->

                    </div><!-- End .row-fluid -->

            </div><!-- End contentwrapper -->
    	<?php } 
    	?>
        </div><!-- End #content -->
    </div><!-- End #wrapper -->
	<input type="hidden" name="oper" value="<?php echo $oper;?>" />
	<input type="hidden" name="status" value="0" />
	<input type="hidden" name="folio" value="<?php echo $folio;?>" />
	<input type="hidden" name="patente_sel" value="<?php echo $patente_sel;?>" />
	<input type="hidden" name="primera" value="1" />
	<input type="hidden" name="correcto" value="0">
    </form>
    </body>
</html>
<?php } ?>
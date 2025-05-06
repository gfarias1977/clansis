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
		date_default_timezone_set("Chile/Continental");		
		$menu="";
		$opcion="";
		$hoydia="'".date("Y-m-d")."'";
		// Guias con Voucher 
		$guias_con_voucher = 0;
		$query = "select count(*) as 'guias_con_voucher' from guias_iansa where voucher IS NOT NULL;";
		$result = mysqli_query($conn,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$guias_con_voucher = $row["guias_con_voucher"];
		}
		mysqli_free_result($result);
		// Guias sin Voucher 
		$guias_sin_voucher = 0;
		$query = "select count(*) as 'guias_sin_voucher' from guias_iansa where voucher IS NULL;";
		$result = mysqli_query($conn,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$guias_sin_voucher = $row["guias_sin_voucher"];
		}
		mysqli_free_result($result);
		// Guias Asignadas
		$guias_asignadas = 0;
		$query = "select count(*) as 'guias_asignadas' from guias_iansa;";
		$result = mysqli_query($conn,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$guias_asignadas = $row["guias_asignadas"];
		}
		mysqli_free_result($result);
		// Vouchers Pendientes 
		$vouchers_pendientes = 0;
		$query = "select count(*) as 'vouchers_pendientes' from guias_iansa where estadoRC = 1 and voucher IS NULL;";
		$result = mysqli_query($conn,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$vouchers_pendientes = $row["vouchers_pendientes"];
		}
		mysqli_free_result($result);
		// Rendida Cliente
		$rendida_cliente = 0;
		$query = "select count(*) as 'rendida_cliente' from guias_iansa where estadoRC = 1 and voucher IS NULL;";
		$result = mysqli_query($conn,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$rendida_cliente = $row["rendida_cliente"];
		}
		mysqli_free_result($result);
		// Rendida Interna
		$rendida_interna = 0;
		$query = "select count(*) as 'rendida_interna' from guias_iansa where estado > 0 and estadoRC = 0;";
		$result = mysqli_query($conn,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$rendida_interna = $row["rendida_interna"];
		}
		mysqli_free_result($result);
		// En Ruta
		$en_ruta = 0;
		$query = "select count(*) as 'en_ruta' from guias_iansa where estado = 0;";
		$result = mysqli_query($conn,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$en_ruta = $row["en_ruta"];
		}
		mysqli_free_result($result);
		// No Asignadas 
		$no_asignadas = 0;
		$query = "select count(*) as 'no_asignadas' from cargas_iansa where estado = 0;";
		$result = mysqli_query($conn,$query);
		while ($row = mysqli_fetch_array($result))
		{
			$no_asignadas = $row["no_asignadas"];
		}
		mysqli_free_result($result);
		//
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
    <link class="include" rel="stylesheet" type="text/css" href="css/jquery.jqplot.css" />
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
    <script class="include" type="text/javascript" src="js/jquery.jqplot.js"></script>

    <!-- Load plugins -->
	<script class="include" type="text/javascript" src="plugins/jqplot/jqplot.barRenderer.js"></script>
	<script class="include" type="text/javascript" src="plugins/jqplot/jqplot.pieRenderer.js"></script>
	<script class="include" type="text/javascript" src="plugins/jqplot/jqplot.categoryAxisRenderer.js"></script>
	<script class="include" type="text/javascript" src="plugins/jqplot/jqplot.pointLabels.js"></script>
    
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
    
    <script type="text/javascript">
        //adding load class to body and hide page
        document.documentElement.className += 'loadstate';
        
       function en_proceso() {
	       alert("Opcion esta en etapa de desarrollo.");
       } 
    </script>
    
    <script>
	$(document).ready(function(){
		$("#f_categoria").click(function(){
			if (document.frm_inicio.f_categoria.selectedIndex > 0) {
				$.post("carga_estructura.php",{ p1:$(this).val() },function(data){$("#contenido").html(data);})
			}
		});
	})
	</script>    

	<script>
	$(document).ready(function() {
	
		var divElement = $('div'); //log all div elements
	    if (divElement.hasClass('donut1')) {
			$(function () {
				var data = [
				    { label: "C/Voucher",  data: <?php echo $guias_con_voucher;?>, color: "#88bbc8"},
				    { label: "Pendiente",  data: <?php echo $guias_sin_voucher;?>, color: "#ed7a53"}
				];
		
			    $.plot($(".donut1"), data, 
				{
					series: {
						pie: { 
							show: true,
							innerRadius: 0.4,
							highlight: {
								opacity: 0.1
							},
							radius: 1,
							stroke: {
								color: '#fff',
								width: 8
							},
							startAngle: 2,
						    combine: {
			                    color: '#353535',
			                    threshold: 0.05
			                },
			                label: {
			                    show: true,
			                    radius: 1,
			                    formatter: function(label, series){
			                        return '<div class="pie-chart-label">'+label+'&nbsp;'+Math.round(series.percent)+'%</div>';
			                    }
			                }
						},
						grow: {	active: false}
					},
					legend:{show:false},
					grid: {
			            hoverable: false,
			            clickable: true
			        },
			        tooltip: true, //activate tooltip
					tooltipOpts: {
						content: "%s : %y.1"+"%",
						shifts: {
							x: -30,
							y: -50
						}
					}
				});
			});
		}
		
	});
	</script>
	
	<script>
	$(document).ready(function() {
		var divElement = $('div'); //log all div elements
	    if (divElement.hasClass('donut2')) {
			$(function () {
				var data = [
				    { label: "RC",  data: <?php echo $rendida_cliente;?>, color: "#ed7a53"},
				    { label: "RI",  data: <?php echo $rendida_interna;?>, color: "#bbdce3"},
				    { label: "RUTA",  data: <?php echo $en_ruta;?>, color: "#5a8022"}
				];
		
			    $.plot($(".donut2"), data, 
				{
					series: {
						pie: { 
							show: true,
							innerRadius: 0.4,
							highlight: {
								opacity: 0.1
							},
							radius: 1,
							stroke: {
								color: '#fff',
								width: 8
							},
							startAngle: 2,
						    combine: {
			                    color: '#353535',
			                    threshold: 0.05
			                },
			                label: {
			                    show: true,
			                    radius: 1,
			                    formatter: function(label, series){
			                        return '<div class="pie-chart-label">'+label+'&nbsp;'+Math.round(series.percent)+'%</div>';
			                    }
			                }
						},
						grow: {	active: false}
					},
					legend:{show:false},
					grid: {
			            hoverable: false,
			            clickable: true
			        },
			        tooltip: true, //activate tooltip
					tooltipOpts: {
						content: "%s : %y.1"+"%",
						shifts: {
							x: -30,
							y: -50
						}
					}
				});
			});
		}
		
	});
	</script>
	
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
      
    <body>
    <form name="frm_inicio" id="frm_inicio" onSubmit="return false;">
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

                <div class="mainnav" style="height: 522px;">
                	<?php 	include "menu.php";?>
                </div>
                
            </div><!-- End sidenav -->


        </div><!-- End #sidebar -->

        <!--Body content-->
        <div id="content" class="clearfix">
        
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
                        <li class="active" style="color: white;">Inicio</li>
                    </ul>

                </div><!-- End .heading-->
				
                <!-- Build page from here: -->
                
                <div class="row-fluid">

                    <div class="span4">

                        <div class="box">

                            <div class="title" style="background-image: none; background-color: #1e497c; color: white;">
                                <h4>
                                    <span>CONTROL CEDIBLES IANSA</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad">
                                <table class="table">
                                  <thead>
                                    <tr>
                                      <th>Estado</th>
                                      <th>%</th>
                                      <th>Guias</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr class="success">
                                      <td>Guias con Voucher</td>
                                      <td><?php echo number_format( (($guias_con_voucher*100) / $guias_asignadas), 2, ",", "." );?>%</td>
                                      <td><?php echo number_format( $guias_con_voucher, 0, ",", "." );?></td>
                                    </tr>
                                    <!--
                                    <tr class="error">
                                      <td>Voucher pendientes</td>
                                      <td><?php echo number_format( (($vouchers_pendientes*100) / $guias_asignadas), 2, ",", "." );?>%</td>
                                      <td><?php echo number_format( $vouchers_pendientes, 0, ",", "." );?></td>
                                    </tr>
                                    -->
                                    <tr class="error">
                                      <td>Rendida Cliente</td>
                                      <td><?php echo number_format( (($rendida_cliente*100) / $guias_asignadas), 2, ",", "." );?>%</td>
                                      <td><?php echo number_format( $rendida_cliente, 0, ",", "." );?></td>
                                    </tr>
                                    <tr class="error">
                                      <td>Rendida Interno</td>
                                      <td><?php echo number_format( (($rendida_interna*100) / $guias_asignadas), 2, ",", "." );?>%</td>
                                      <td><?php echo number_format( $rendida_interna, 0, ",", "." );?></td>
                                    </tr>
                                    <tr class="error">
                                      <td>En Ruta</td>
                                      <td><?php echo number_format( (($en_ruta*100) / $guias_asignadas), 2, ",", "." );?>%</td>
                                      <td><?php echo number_format( $en_ruta, 0, ",", "." );?></td>
                                    </tr>
                                    <tr style="background-color: #1e497c; color: white;">
                                      <td>TOTAL</td>
                                      <td>&nbsp;</td>
                                      <td><?php echo number_format( ($guias_con_voucher + $rendida_cliente + $rendida_interna + $en_ruta) , 0, ",", "." );?></td>
                                    </tr>
                                  </tbody>
                                </table>
                            </div>

                            <div class="title" style="background-image: none; background-color: #1e497c; color: white;">
                                <h4>
                                    <span>ASIGNACION DE GUIAS</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad">
                                <table class="table">
                                  <tbody>
                                    <tr class="success">
                                      <td>ASIGNADAS</td>
                                      <td>&nbsp;</td>
                                      <td><?php echo $guias_asignadas;?></td>
                                    </tr>
                                    <tr class="error">
                                      <td>NO ASIGNADAS</td>
                                      <td>&nbsp;</td>
                                      <td><?php echo $no_asignadas;?></td>
                                    </tr>
                                  </tbody>
                                </table>
                            </div>
                            
                        </div><!-- End .box -->

                    </div><!-- End .span3 -->
                    
                        <div class="span4">

                            <div class="box chart">

	                            <div class="title" style="background-image: none; background-color: #1e497c; color: white;">

                                    <h4>
	                                    <span>CUMPLIMIENTO CEDIBLES IANSA</span>
                                    </h4>
                                    <a href="#" class="minimize">Minimize</a>
                                </div>
                                <div class="content">
                                   <div class="donut1" style="height: 284px;width:100%;">

                                    </div>
                                </div>

                            </div><!-- End .box -->
                        
                        </div><!-- End .span6 -->
                    
                        <div class="span4">

                            <div class="box chart">

	                            <div class="title" style="background-image: none; background-color: #1e497c; color: white;">

                                    <h4>
                                        <span>DETALLE PENDIENTES</span>
                                </h4>
                                    <a href="#" class="minimize">Minimize</a>
                                </div>
                                <div class="content">
                                   <div class="donut2" style="height: 284px;width:100%;">

                                    </div>
                                </div>

                            </div><!-- End .box -->
                        
                        </div><!-- End .span6 -->
                    
                </div><!-- End .rowfluid -->

                <div class="row-fluid">

                    <div class="span4">

                        <div class="box">

                            <div class="title" style="background-image: none; background-color: #1e497c; color: white;">
                                <h4>
                                    <span>CONTROL CEDIBLES FAENAS</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad">
                                <table class="table">
                                  <thead>
                                    <tr>
                                      <th>Faena</th>
                                      <th>Anterior</th>
                                      <th>Del D&iacute;a</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                        <?php
										$query = "SELECT a.id, a.descripcion AS 'DES_FAENA', (select count(*) from cargas_otras_faenas c1 where c1.faena=a.id and c1.fecha_registro < ".trim($hoydia).") AS 'Anterior', (select count(*) from cargas_otras_faenas c2 where c2.faena=a.id and c2.fecha_registro = ".trim($hoydia).") AS 'Dia' FROM faenas a order by a.descripcion ";
										$result = mysqli_query($conn,$query);
										while ($row = mysqli_fetch_array($result))
										{
											?>
	                                          <tr>
	                                            <td style="text-align: left;" class="mayuscula"><?php echo strtoupper($row["DES_FAENA"]);?></td>
	                                            <td style="text-align: center;"><?php echo number_format( $row["Anterior"], 0, ",", "." );?></td>
	                                            <td style="text-align: center;"><?php echo number_format( $row["Dia"], 0, ",", "." );?></td>
	                                          </tr>
	                                    <?php      
										}
										mysqli_free_result($result);
										?>
                                  </tbody>
                                </table>
                            </div>

                        </div><!-- End .box -->

                    </div><!-- End .span3 -->

  <script class="code" type="text/javascript">
	  	<?php
		// Otras Faenas
		$actividad = 0;
		$serie1 = "var s1 = [";
		$serie2 = "var s2 = [";
		$tickets = "var ticks = [";
		$query = "SELECT a.id, a.descripcion AS 'DES_FAENA', (select count(*) from cargas_otras_faenas c1 where c1.faena=a.id and c1.fecha_registro < ".trim($hoydia)." and c1.estado < 2) AS 'Anterior', (select count(*) from cargas_otras_faenas c2 where c2.faena=a.id and c2.fecha_registro = ".trim($hoydia)." and c2.estado < 2) AS 'Dia' FROM faenas a order by a.descripcion ";
		//echo $query;
		$result = mysqli_query($conn,$query);
		while ($row = mysqli_fetch_array($result))
		{
			if ($row["Anterior"]+$row["Dia"]>0) {
				$actividad = 1;
				$serie1 = $serie1 . trim($row["Anterior"]) . ",";
				$serie2 = $serie2 . trim($row["Dia"]) . ",";
				$tickets = $tickets . "'" . trim($row["DES_FAENA"]) . "',";
			}
		}
		mysqli_free_result($result);
		//
		if ($serie1 != "var s1 = [") {
			$serie1 = substr($serie1, 0, -1);
		}
		if ($serie2 != "var s2 = [") {
			$serie2 = substr($serie2, 0, -1);
		}
		if ($tickets != "var ticks = [") {
			$tickets = substr($tickets, 0, -1);
		}
	  	$serie1 = $serie1 . "];\n";
	  	$serie2 = $serie2 . "];\n";
	  	$tickets = $tickets . "];\n";
	  	
	  	echo $serie1;
	  	echo $serie2;
	  	echo $tickets;
	  	
	  	if ($actividad==1) { 
		?>	  	
	$(document).ready(function(){        
        plot2 = $.jqplot('chart2', [s1, s2], {
            stackSeries: false,
            captureRightClick: true,
            seriesDefaults: {
                renderer:$.jqplot.BarRenderer,
                rendererOptions: {
                    highlightMouseDown: true    
                },
                pointLabels: { show: true }
            },
            legend: {
	            labels: ["Anterior", "Del Dia"],
                show: true,
                location: 'ne',
                placement: 'inside'
            },      
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                }
            }
        });
    
    });
    <?php } ?>
    </script>
                                        
                        <div class="span8">

                            <div class="box chart">

	                            <div class="title" style="background-image: none; background-color: #1e497c; color: white;">

                                    <h4>
                                        <span>CONTROL DOCUMENTOS PENDIENTES POR FAENA</span>
                                    </h4>
                                    <a href="#" class="minimize">Minimize</a>
                                </div>
                                <div class="content">
									<div id="chart2" style="margin-top:20px; margin-left:20px; width:90%; height:300px;"></div>
								</div>

                            </div><!-- End .box -->
                        
                        </div><!-- End .span8 -->
                    
                    
                </div><!-- End .rowfluid -->
                                                    
            </div><!-- End contentwrapper -->
            
        </div><!-- End #content -->
    
    </div><!-- End #wrapper -->
    
	<input type="hidden" name="oper" value="0" />
	<input type="hidden" name="orden" value="0" />
	<input type="hidden" name="status" value="0" />
	</form>
    </body>
</html>
<?php } ?>
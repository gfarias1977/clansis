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
		include "genera_repFolio.php";
		// Reporte
		$prefijo = date("YmdHis");
		$name = "temp/".trim($prefijo).".pdf";
		$ok = false;
		$conn=opendblocal();
		$nom_usuario = 	$_SESSION["nombreUsuario"];
		$rol_usuario = $_SESSION["rolUsuario"];
		$id_usuario = $_SESSION["idUsuario"];
		date_default_timezone_set("Chile/Continental");		
		$menu="CONSULTA";
		$opcion="";
		$hoy=date("d/m/Y");
		if ($fecha=="") {
			$fecha=$hoy;
		}
		//
		$sw_error=0;
		$tipo_guia=0;
		if ($oper==1) {
			if ($f_guia!="") {
				$id_asig = 0;
				$id_guia = 0;
				$query = "SELECT * from guias_iansa WHERE guia = '".trim($f_guia)."';";
				//echo $query;
				$result = mysqli_query($conn,$query);
				while ($row = mysqli_fetch_array($result))
				{
					$id_asig = trim($row["asignacion_id"]);
					$id_guia = trim($row["id"]);
					$tipo_guia=1;
				}
				mysqli_free_result($result);
				//
				if ($tipo_guia==0) {
					$id_reg = 0;
					$query = "SELECT * from cargas_otras_faenas WHERE guia = '".trim($f_guia)."';";
					//echo $query;
					$result = mysqli_query($conn,$query);
					while ($row = mysqli_fetch_array($result))
					{
						$id_reg = trim($row["id"]);
						$tipo_guia=2;
					}
					mysqli_free_result($result);
				}
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
    function inicio() 
    {
    }
	function busqueda()
	{
		var ret=0;
		if (document.consulta.f_guia.value=="") {
			ret=1;
			alert("Debe indicar GUIA a consultar.");
			document.consulta.f_guia.focus();
		}
		if (ret==0) {	    
			document.consulta.oper.value=1;
			document.consulta.submit();
		}
	}
	function val_numeros(objeto){
		if(objeto.value != ""){
			if(check_number(objeto.value)){
			}
			else{
				alert("Debe ingresar un valor num\351ricos.");
				//objeto.select();
				//objeto.focus();
				objeto.value="";
				return;
			}
		}
	}
    </script>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
      
    <body onLoad="inicio();">
    <form method="post" action="consulta_guia.php" target="_self" name="consulta" id="consulta" onSubmit="return false;">
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
                        <li class="active" style="color: white;">Consulta GUIA</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                    <div class="row-fluid">

                        <div class="span12">
                            
                            <div class="box">

                                <div class="title">
                                    <h4>
                                        <span class="icon16 entypo-icon-info-circle"></span>
                                        <span>Consulta GUIA</span>
                                    </h4>
                                </div>
                                <div class="content noPad">
	                                <div class="form-row row-fluid" style="text-align: left;">
	                                    <div class="span12">
	                                        <div class="row-fluid" style="height: 40px;">
	                                            <span class="span1" style="margin-left: 10px; margin-top: 6px; border: 0px dotted blue;"><button class="btn tipR" title="Consultar Guia" href="#" onClick="busqueda();"><span class="icon16 brocco-icon-filter"></span></button></span>
	                                        	<span class="span2" style="margin-left: 0px; margin-top: 6px; border: 0px dotted blue;"><input id="f_guia" name="f_guia" type="text" value="<?php echo $f_guia;?>" maxlength="10" placeholder="GUIA..." style="width: 120px;" onBlur="val_numeros( this );" /></span>
	                                        </div>
	                                    </div>
	                                </div>
							        <?php if ($oper==1 && $tipo_guia==1) { ?>
							                <table class="responsive table table-bordered">
							                    <thead>
							                      <tr>
							                        <th style="text-align: center; width: 60px;">Fecha</th>
							                        <th style="text-align: center; width: 30px;">Ruta</th>
							                        <th style="text-align: left;">Destinatario</th>
							                        <th style="text-align: left; width: 70px;">Rut</th>
							                        <th style="text-align: left; width: 150px;">Nombre Chofer</th>
							                        <th style="text-align: left; width: 70px;">Patente</th>
							                        <th style="text-align: left; width: 70px;">Pat.Real</th>
							                        <th style="text-align: left; width: 90px;">Causante</th>
							                        <th style="text-align: left; width: 90px;">O.C.</th>
							                        <th style="text-align: left; width: 90px;">Negocio</th>
							                      </tr>
							                    </thead>
							                    <tbody>
							                    <?php
												$query = "SELECT A.*, DATE_FORMAT(A.fecha, '%d/%m/%Y') AS 'FechaAsignacion' FROM cargas_iansa A WHERE id=".trim($id_asig).";";
												//echo "<tr><td colspan=6>".$query."</td></tr>\n";
												$result = mysqli_query($conn,$query);
												while ($row = mysqli_fetch_array($result))
												{
							            		    $patente_real = "";
							            		    if (!is_null($row["patente_real"])) {
								            		    $patente_real = trim($row["patente_real"]);
							            		    }
													?>
							                          <tr>
							                            <td style="text-align: center;"><?php echo trim($row["FechaAsignacion"]);?></td>
							                            <td style="text-align: center;"><?php echo trim($row["ruta"]);?></td>
							                            <td style="text-align: left;"><?php echo trim($row["nombre_destinatario"]);?></td>
							                            <td style="text-align: left;"><?php echo trim($row["rut_chofer"]);?></td>
							                            <td style="text-align: left;"><?php echo trim($row["nombre_chofer"]);?></td>
							                            <td style="text-align: left;"><?php echo trim($row["patente"]);?></td>
							                            <td style="text-align: left;"><?php echo trim($patente_real);?></td>
							                            <td style="text-align: left;"><?php echo trim($row["causante"]);?></td>
							                            <td style="text-align: left;"><?php echo trim($row["oc"]);?></td>
							                            <td style="text-align: left;"><?php echo trim($row["negocio"]);?></td>
							                          </tr>
							                    <?php      
												}
												mysqli_free_result($result);
												?>
							                    </tbody>
							                </table>
							                <table class="responsive table table-bordered" style="margin-top: 15px;">
							                    <thead>
							                      <tr>
							                        <th style="text-align: center; width: 250px;">Rendici&oacute;n Interna</th>
							                        <th style="text-align: left;">Comentarios</th>
							                        <th style="text-align: left; width: 70px;">CHEP</th>
							                        <th style="text-align: left; width: 70px;">IANSA</th>
							                      </tr>
							                    </thead>
							                    <tbody>
							                    <?php
												$query = "SELECT A.* FROM guias_iansa A WHERE id=".trim($id_guia).";";
												//echo "<tr><td colspan=6>".$query."</td></tr>\n";
												$result = mysqli_query($conn,$query);
												while ($row = mysqli_fetch_array($result))
												{
							            		    $comentario = "";
							            		    if (!is_null($row["comentario"])) {
								            		    $comentario = utf8_decode(trim($row["comentario"]));
							            		    }
							            		    $chep = "";
							            		    if (!is_null($row["CHEP"])) {
								            		    $chep = trim($row["CHEP"]);
							            		    }
							            		    $iansa = "";
							            		    if (!is_null($row["IANSA"])) {
								            		    $iansa = trim($row["IANSA"]);
							            		    }
							            		    $estado_ri = "NO RENDIDO INTERNAMENTE";
							            		    if ($row["estado"]==1) {
								            		    $estado_ri = "RENDIDO INTERNAMENTE";
							            		    }
							            		    if ($row["estado"]==2) {
								            		    $estado_ri = "RENDIDO CON PENDIENTES";
							            		    }
													?>
							                          <tr>
							                            <td style="text-align: center;"><?php echo trim($estado_ri);?></td>
							                            <td style="text-align: left;"><?php echo trim($comentario);?></td>
							                            <td style="text-align: center;"><?php echo trim($chep);?></td>
							                            <td style="text-align: center;"><?php echo trim($iansa);?></td>
							                          </tr>
							                    <?php      
												}
												mysqli_free_result($result);
												?>
							                    </tbody>
							               </table>
							                <table class="responsive table table-bordered" style="margin-top: 15px;">
							                    <thead>
							                      <tr>
							                        <th style="text-align: center; width: 250px;">Rendici&oacute;n Cliente</th>
							                        <th style="text-align: center; width: 60px;">Fecha</th>
							                        <th style="text-align: left;">Comentarios</th>
							                        <th style="text-align: center; width: 70px;">FOLIO</th>
							                        <th style="text-align: center; width: 70px;">VOUCHER</th>
							                        <th style="text-align: center; width: 120px;">ARCHIVADO</th>
							                      </tr>
							                    </thead>
							                    <tbody>
							                    <?php
												$query = "SELECT A.*, DATE_FORMAT(A.fechaRC, '%d/%m/%Y') AS 'FechaRendicion' FROM guias_iansa A WHERE id=".trim($id_guia).";";
												//echo "<tr><td colspan=6>".$query."</td></tr>\n";
												$result = mysqli_query($conn,$query);
												while ($row = mysqli_fetch_array($result))
												{
							            		    $fechaRC = "";
							            		    if (!is_null($row["fechaRC"])) {
								            		    $fechaRC = trim($row["FechaRendicion"]);
							            		    }
							            		    $comentarioRC = "";
							            		    if (!is_null($row["comentarioRC"])) {
								            		    $comentarioRC = utf8_decode(trim($row["comentarioRC"]));
							            		    }
							            		    $folio = "";
							            		    if (!is_null($row["folio"])) {
								            		    $folio = trim($row["folio"]);
							            		    }
							            		    $voucher = "";
							            		    if (!is_null($row["voucher"])) {
								            		    $voucher = trim($row["voucher"]);
							            		    }
							            		    $estado_rc = "NO RENDIDO A CLIENTE";
							            		    if ($row["estadoRC"]==1) {
								            		    $estado_rc = "RENDIDO A CLIENTE";
							            		    }
							            		    $archivado = "NO ARCHIVADO";
							            		    if ($row["archivado"]==1) {
								            		    $archivado = "ARCHIVADO";
							            		    }
													?>
							                          <tr>
							                            <td style="text-align: center;"><?php echo trim($estado_rc);?></td>
							                            <td style="text-align: center;"><?php echo trim($fechaRC);?></td>
							                            <td style="text-align: left;"><?php echo trim($comentarioRC);?></td>
							                            <td style="text-align: center;"><?php echo trim($folio);?></td>
							                            <td style="text-align: center;"><?php echo trim($voucher);?></td>
							                            <td style="text-align: center;"><?php echo trim($archivado);?></td>
							                          </tr>
							                    <?php      
												}
												mysqli_free_result($result);
												?>
							                    </tbody>
							               </table>
							        <?php } ?>
	                                
							        <?php if ($oper==1 && $tipo_guia==2) { ?>
							                <table class="responsive table table-bordered">
							                    <thead>
							                      <tr>
							                        <th style="text-align: center; width: 120px;">Fecha Registro</th>
							                        <th style="text-align: left;">Descripci&oacute;n</th>
							                        <th style="text-align: left; width: 70px;">Patente</th>
							                        <th style="text-align: left; width: 150px;">Nombre Chofer</th>
							                        <th style="text-align: left; width: 180px;">Faena</th>
							                        <th style="text-align: center; width: 70px;">Cant.Guias</th>
							                      </tr>
							                    </thead>
							                    <tbody>
							                    <?php
												$query = "SELECT A.*, DATE_FORMAT( A.fecha_registro, '%d-%m-%Y' ) AS 'FechaRegistro', B.descripcion AS 'DES_FAENA', C.patente AS 'DES_PATENTE', D.nombre, D.apellidos FROM cargas_otras_faenas A LEFT JOIN faenas B ON A.faena=B.id LEFT JOIN patentes C ON A.patente=C.id LEFT JOIN choferes D ON A.chofer=D.id WHERE A.id=".trim($id_reg)." ;";
												//echo "<tr><td colspan=6>".$query."</td></tr>\n";
												$result = mysqli_query($conn,$query);
												while ($row = mysqli_fetch_array($result))
												{
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
							                            <td style="text-align: center;"><?php echo trim($row["FechaRegistro"]);?></td>
							                            <td style="text-align: left;"><?php echo trim($detalle_des);?></td>
							                            <td style="text-align: left;"><?php echo trim($row["DES_PATENTE"]);?></td>
							                            <td style="text-align: left;"><?php echo trim($chofer);?></td>
							                            <td style="text-align: left;"><?php echo trim($row["DES_FAENA"]);?></td>
							                            <td style="text-align: center;"><?php echo trim($row["cantidad_guias"]);?></td>
							                          </tr>
							                    <?php      
												}
												mysqli_free_result($result);
												?>
							                    </tbody>
							                </table>
							                <table class="responsive table table-bordered" style="margin-top: 15px;">
							                    <thead>
							                      <tr>
							                        <th style="text-align: center; width: 120px;">Recepcionado</th>
							                        <th style="text-align: center; width: 120px;">Fecha Recepci&oacute;n</th>
							                        <th style="text-align: center; width: 120px;">Rendido</th>
							                        <th style="text-align: center; width: 120px;">Fecha Rendici&oacute;n</th>
							                        <th style="text-align: center; width: 60px;">FOLIO</th>
							                        <th style="text-align: center; width: 120px;">Archivado</th>
							                        <th style="text-align: center;">&nbsp;</th>
							                      </tr>
							                    </thead>
							                    <tbody>
							                    <?php
												$query = "SELECT A.*, DATE_FORMAT( A.fecha_registro, '%d-%m-%Y' ) AS 'FechaRegistro', DATE_FORMAT( A.fecha_recepcion, '%d-%m-%Y' ) AS 'FechaRecepcion', DATE_FORMAT( A.fecha_rendicion, '%d-%m-%Y' ) AS 'FechaRendicion', B.descripcion AS 'DES_FAENA', C.patente AS 'DES_PATENTE', D.nombre, D.apellidos FROM cargas_otras_faenas A LEFT JOIN faenas B ON A.faena=B.id LEFT JOIN patentes C ON A.patente=C.id LEFT JOIN choferes D ON A.chofer=D.id WHERE A.id=".trim($id_reg)." ;";
												//echo "<tr><td colspan=6>".$query."</td></tr>\n";
												$result = mysqli_query($conn,$query);
												while ($row = mysqli_fetch_array($result))
												{
							            		    $estado_rec = "NO";
							            		    if ($row["estado"]>0) {
								            		    $estado_rec = "SI";
							            		    }
							            		    $estado_ren = "NO";
							            		    if ($row["estado"]==2) {
								            		    $estado_ren = "SI";
							            		    }
							            		    $estado_ar = "NO";
							            		    if ($row["archivado"]==1) {
								            		    $estado_ar = "SI";
							            		    }
													?>
							                          <tr>
							                            <td style="text-align: center;"><?php echo trim($estado_rec);?></td>
							                            <td style="text-align: center;"><?php echo trim($row["FechaRecepcion"]);?></td>
							                            <td style="text-align: center;"><?php echo trim($estado_ren);?></td>
							                            <td style="text-align: center;"><?php echo trim($row["FechaRendicion"]);?></td>
							                            <td style="text-align: center;"><?php echo trim($row["folio"]);?></td>
							                            <td style="text-align: center;"><?php echo trim($estado_ren);?></td>
							                            <td style="text-align: center;">&nbsp;</td>
							                          </tr>
							                    <?php      
												}
												mysqli_free_result($result);
												?>
							                    </tbody>
							               </table>
							        <?php } ?>
							        
	                            </div>
                            </div><!-- End .box -->

                        </div><!-- End .span6 -->

                    </div><!-- End .row-fluid -->

            </div><!-- End contentwrapper -->
            
        </div><!-- End #content -->
    </div><!-- End #wrapper -->
    
	<input type="hidden" name="oper" value="<?php echo $oper;?>" />
	<input type="hidden" name="id" value="<?php echo $id;?>" />
	<input type="hidden" name="status" value="0" />
	<input type="hidden" name="page" value="<?php echo $page;?>">
	<input type="hidden" name="orden" value="<?php echo $orden;?>">
    </form>
    </body>
</html>
<?php } ?>
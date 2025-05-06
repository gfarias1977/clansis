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
	    if ($oper==9) { //salida
			session_destroy();			
			header('Location: index.php');
    	}
		$nom_usuario = 	$_SESSION["nombreUsuario"];
		$rol_usuario = $_SESSION["rolUsuario"];
		date_default_timezone_set("Chile/Continental");		
		$menu="MANTENEDORES";
		$opcion="CHOFERES";
		$hoy=date("d/m/Y");
		if ($fecha=="") {
			$fecha=$hoy;
		}
		//
		$sw_error=0;
		if ($oper==1) {	//agregar
			if ($status==1) {
				if ($rut!="") {
					// Validar Consistencia
					$query = "SELECT * from choferes Where rut='".trim($rut)."';";
					$result = mysqli_query($conn,$query);
					while ($row = mysqli_fetch_array($result))
					{
						$sw_error=1;
					}
					mysqli_free_result($result);
				}
				if ($sw_error==0) {
					if ($_FILES[foto][name]!="") {
						if ($_FILES[foto][size] > $maximo) {
							$sw_error=10;
						} else {
							if ($_FILES[foto][type]!="image/png" && $_FILES[foto][type]!="image/jpeg" && $_FILES[foto][type]!="image/gif") {
								$sw_error=11;
							}
						}
					}
					if ($sw_error==0) {
						//
						// Procesa archivo adjunto
						//
						$interno="NULL";
						$externo="NULL";
						if ($_FILES[foto][name]!="") {
						    $path1 = "multimedia/";          
						    $ext1 = strtolower(right($_FILES[foto][name], 4));
						    $source1 = $_FILES[foto][tmp_name];
						    $source_name1 = $_FILES[foto][name];
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
							$interno="'".$namefile1.$ext1."'";
							$externo="'".$_FILES[foto][name]."'";
				        }
						//				    
						$rut_ = "'".strtoupper($rut)."'";    
						if ($nombre!="") {
							$nombre_ = "'".strtoupper($nombre)."'";
						} else {
							$nombre_ = "NULL";
						}
						if ($apellidos!="") {
							$apellidos_ = "'".strtoupper($apellidos)."'";
						} else {
							$apellidos_ = "NULL";
						}
						if ($movil_contacto!="") {
							$movil_contacto_ = "'".$movil_contacto."'";
						} else {
							$movil_contacto_ = "NULL";
						}
						if ($email!="") {
							$email_ = "'".$email."'";
						} else {
							$email_ = "NULL";
						}
						if ($direccion!="") {
							$direccion_ = "'".strtoupper($direccion)."'";
						} else {
							$direccion_ = "NULL";
						}
						if ($situacion=="on") {
							$situacion_ = "0";
						} else {
							$situacion_ = "1";
						}
						$query3 = "INSERT INTO choferes VALUES(NULL,".$rut_.",".$nombre_.",".$apellidos_.",".$email_.",".$direccion_.",".$movil_contacto_.",".$interno.",".$externo.",".$situacion_.");";
						$result = mysqli_query($conn,$query3);
						$id_nuevo = mysqli_insert_id($conn);
						$oper=0;
						$id=$id_nuevo;
						$status=0;
					}
				}
			}
		}
		if ($oper==2) {	//actualizar
			if ($status==1) {
				if ($sw_error==0) {
					if ($_FILES[foto][name]!="") {
						if ($_FILES[foto][size] > $maximo) {
							$sw_error=10;
						} else {
							if ($_FILES[foto][type]!="image/png" && $_FILES[foto][type]!="image/jpeg" && $_FILES[foto][type]!="image/gif") {
								$sw_error=11;
							}
						}
					}
					if ($sw_error==0) {
						//
						// Procesa archivo adjunto
						//
						$interno="NULL";
						$externo="NULL";
						if ($_FILES[foto][name]!="") {
						    $path1 = "multimedia/";          
						    $ext1 = strtolower(right($_FILES[foto][name], 4));
						    $source1 = $_FILES[foto][tmp_name];
						    $source_name1 = $_FILES[foto][name];
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
							$interno="'".$namefile1.$ext1."'";
							$externo="'".$_FILES[foto][name]."'";
				        }
						//				        
						if ($nombre!="") {
							$nombre_ = "'".strtoupper($nombre)."'";
						} else {
							$nombre_ = "NULL";
						}
						if ($apellidos!="") {
							$apellidos_ = "'".strtoupper($apellidos)."'";
						} else {
							$apellidos_ = "NULL";
						}
						if ($movil_contacto!="") {
							$movil_contacto_ = "'".$movil_contacto."'";
						} else {
							$movil_contacto_ = "NULL";
						}
						if ($email!="") {
							$email_ = "'".$email."'";
						} else {
							$email_ = "NULL";
						}
						if ($direccion!="") {
							$direccion_ = "'".strtoupper($direccion)."'";
						} else {
							$direccion_ = "NULL";
						}
						if ($situacion=="on") {
							$situacion_ = "0";
						} else {
							$situacion_ = "1";
						}
						if ($interno!="NULL") {
							$query3 = "UPDATE choferes SET nombre=".$nombre_.", apellidos=".$apellidos_.", movil_contacto=".$movil_contacto_.", email=".$email_.", direccion=".$direccion_.", interno=".$interno.", externo=".$externo.", estado=".$situacion_." WHERE id=".$id.";";
						} else {
							$query3 = "UPDATE choferes SET nombre=".$nombre_.", apellidos=".$apellidos_.", movil_contacto=".$movil_contacto_.", email=".$email_.", direccion=".$direccion_.", estado=".$situacion_." WHERE id=".$id.";";
						}
						$result = mysqli_query($conn,$query3);
						//echo $query3;
						$oper=0;
						$status=0;
					}
				}
			}
		}
		if ($oper==3) {	//borrar
			$sw1=false;
			// Verificaci�n consistencia
			// 
			if ($sw1==false) {
				$query = "DELETE FROM choferes WHERE id=".$id.";";
				$result = mysqli_query($conn,$query);
			} else {
				$sw_error=2;
			}
			$oper=0;
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
			    title: 'ERROR DE INGRESO!',
	    		text: 'RUT ya existe en Base de Datos.',
			    icon: 'picon icon24 typ-icon-cancel white',
			    opacity: 0.95,
			    history: false,
			    sticker: false
			});
		});
	<?php } ?>
 	<?php if ($sw_error==2) { ?>
	 	$(document).ready(function(){
			$.pnotify({
				type: 'error',
			    title: 'ERROR DE BORRADO!',
	    		text: '\n Chofer tiene actividad asociada.',
			    icon: 'picon icon24 typ-icon-cancel white',
			    opacity: 0.95,
			    history: false,
			    sticker: false
			});
		});
	<?php } ?>
 	<?php if ($sw_error==10) { ?>
	 	$(document).ready(function(){
			$.pnotify({
				type: 'error',
			    title: 'ERROR CARGA ARCHIVO!',
	    		text: '\nFoto excede m\341ximo permitido:\n [120KBytes].',
			    icon: 'picon icon24 typ-icon-cancel white',
			    opacity: 0.95,
			    history: false,
			    sticker: false
			});
		});
	<?php } ?>
	<?php if ($sw_error==11) { ?>
	 	$(document).ready(function(){
			$.pnotify({
				type: 'error',
			    title: 'ERROR CARGA ARCHIVO!',
	    		text: '\nFormato Foto inv\341lido. Opciones v\341lidas: \n [JPG, GIF, PNG].',
			    icon: 'picon icon24 typ-icon-cancel white',
			    opacity: 0.95,
			    history: false,
			    sticker: false
			});
		});
	<?php } ?>

    function inicio() 
    {
    }
	function agregar()
	{
		document.choferes.oper.value=1;
		document.choferes.submit();
	}
	function cancelar()
	{
		document.choferes.oper.value=0;
		document.choferes.submit();
	}
	function editar( id )
	{
		document.choferes.oper.value=2;
		document.choferes.id.value=id;
		document.choferes.submit();
	}
	function guardar()
	{
		var ret=0;
		if (document.choferes.rut.value=="") {
			ret=1;
			alert("Debe indicar RUT Chofer.");
			document.choferes.rut.focus();
		} else {
			if (valida_rut(document.choferes.rut.value)!=0) {
				ret=1;
				alert("RUT Chofer no es v\341lido.");
				document.choferes.rut.focus();
				// \277 � \341 � \351 � \355 � \363 � \372 � \361 � 
			}	
		}
		if (ret==0) {
			if (document.choferes.nombre.value=="") {
				ret=1;
				alert("Debe indicar Nombre.");
				document.choferes.nombre.focus();
			} else {
				if (document.choferes.apellidos.value=="") {
					ret=1;
					alert("Debe indicar Apellidos.");
					document.choferes.apellidos.focus();
				}
			}
		}
		/*
		if (ret==0) {
			if (document.choferes.email.value=="") {
				ret=1;
				alert("Debe indicar Correo Electronico.");
				document.choferes.email.focus();
			} else {				
				var email = document.choferes.email.value;
				expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if ( !expr.test(email) ) {
					ret=1;
					alert("Email ingresado no es correcto.");
					document.choferes.email.focus();
				}
			}
		}
		if (ret==0) {
			if (document.choferes.movil_contacto.value=="") {
				ret=1;
				alert("Debe indicar Tel\351fono de Contacto.");
				document.choferes.movil_contacto.focus();
				// \341 � \351 � \355 � \363 � \372 � \361 � 
			}
		}
		*/
		if (ret==0) {
			document.choferes.status.value=1;
			document.choferes.submit();
		}
	}
	function actualizar()
	{
		var ret=0;
		if (document.choferes.nombre.value=="") {
			ret=1;
			alert("Debe indicar Nombre.");
			document.choferes.nombre.focus();
		} else {
			if (document.choferes.apellidos.value=="") {
				ret=1;
				alert("Debe indicar Apellidos.");
				document.choferes.apellidos.focus();
			}
		}
		/*
		if (ret==0) {
			if (document.choferes.email.value=="") {
				ret=1;
				alert("Debe indicar Correo Electronico.");
				document.choferes.email.focus();
			} else {				
				var email = document.choferes.email.value;
				expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if ( !expr.test(email) ) {
					ret=1;
					alert("Email ingresado no es correcto.");
					document.choferes.email.focus();
				}
			}
		}
		if (ret==0) {
			if (document.choferes.movil_contacto.value=="") {
				ret=1;
				alert("Debe indicar Tel\351fono de Contacto.");
				document.choferes.movil_contacto.focus();
				// \341 � \351 � \355 � \363 � \372 � \361 � 
			}
		}
		*/
		if (ret==0) {
			document.choferes.status.value=1;
			document.choferes.submit();
		}
	}
	function borrar( nombre, id )
	{
		if (confirm("\277 Confirma la eliminaci\363n de este Chofer: \n" + nombre + " ?")) {
			document.choferes.id.value = id;
			document.choferes.oper.value = 3;
			document.choferes.submit();
		}
	}
	function enlacePaginacion( pagina ) 
	{
		document.choferes.page.value=pagina;
		document.choferes.submit();
	}
	function salida() {
		document.choferes.oper.value=9;
		document.choferes.submit();
	}
   function en_proceso() {
       alert("Opcion esta en etapa de desarrollo.");
   } 
	
    </script>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
      
    <body onLoad="inicio();">
    <form method="post" action="choferes.php" target="_self" enctype="multipart/form-data" name="choferes" id="choferes" onSubmit="return true;">
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
                        <li><a href="#" onClick="salida();"><span class="icon16 icomoon-icon-exit"></span> Salida</a></li>
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
			if(isset($_POST['page'])){
			    $page= $_POST['page'];
			}else{
			    $page=1;
			}	
			$rows_per_page= 20;
			$query = "SELECT * from choferes A ";
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
                        <li class="active" style="color: white;">Listado Choferes</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                    <div class="row-fluid">

                        <div class="span12">
                            
                            <div class="box">

                                <div class="title">
                                    <h4>
                                        <span class="icon16 wpzoom-user-2"></span>
                                        <span>Listado Choferes</span>
                                        <a class="btn btn-mini tipL marginR10 marginB10 right" title="Agregar Nuevo Chofer" href="#" onClick="agregar();">
                                            <span class="icon16 entypo-icon-contact"></span>
                                        </a>
                                    </h4>
                                </div>
                                <div class="content noPad">
                                    <table class="responsive table table-bordered">
                                        <thead>
                                          <tr>
                                            <th style="text-align: center; width: 20px;">#</th>
                                            <th style="text-align: left; width: 8%;">Rut</th>
                                            <th style="text-align: left;">Nombre Chofer</th>
                                            <th style="text-align: left;">Correo Eletr&oacute;nico</th>
                                            <th style="text-align: left;">Tel&eacute;fono Contacto</th>
                                            <th width="40" style="text-align: center;">Estado</th>
                                            <th width="70">Acciones</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        <?php
										$contador=(($page-1)*($rows_per_page));
										$query = "select a.* from choferes a order by a.apellidos, a.nombre ";
										$query .= " $limit";
										//echo $query;
										$result = mysqli_query($conn,$query);
										while ($row = mysqli_fetch_array($result))
										{
                                		    echo "<TR>\n";
                                		    $contador=$contador+1;
                                		    $nombre_chofer = $row["nombre"];
                                		    $email_chofer = $row["email"];
                                		    $telefono_chofer = $row["movil_contacto"];
                                		    if (!is_null($row["apellidos"])) {
	                                		    $nombre_chofer = $nombre_chofer . " " . $row["apellidos"];
                                		    } 
											?>
	                                          <tr>
	                                            <td style="text-align: center;"><?php echo $contador;?></td>
	                                            <td style="text-align: left;"><?php echo $row["rut"];?></td>
	                                            <td style="text-align: left;"><?php echo $nombre_chofer;?></td>
	                                            <td style="text-align: left;"><?php echo $email_chofer;?></td>
	                                            <td style="text-align: left;"><?php echo $telefono_chofer;?></td>
	                                            <?php if ($row["estado"]==0) { ?>
		                                            <td style="text-align: center;"><code class="btn-success tipL left" title="Chofer Activo"><span class="icon10 icomoon-icon-checkmark white"></code></td>
		                                        <?php } else { ?>
		                                            <td style="text-align: center;"><code class="btn-danger tipL left" title="Chofer Suspendido"><span class="icon10 icomoon-icon-cancel-2 white"></code></td>
		                                        <?php } ?>    
	                                            <td>
	                                                <div class="controls center">
	                                                    <a href="#" title="Editar" class="tip" onClick="editar(<?php echo trim($row['id']);?>);"><span class="icon12 icomoon-icon-pencil"></span></a>
	                                                    <a href="#" title="Borrar" class="tip" onClick="borrar('<?php echo trim($nombre_chofer);?>', <?php echo trim($row['id']);?>);"><span class="icon12 icomoon-icon-remove"></span></a>
	                                                </div>
	                                            </td>
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

            </div><!-- End contentwrapper -->
    	<?php } 
    	if ($oper==1) {
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
                        <li class="active" style="color: white;">Agregar Choferes</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                   <div class="row-fluid">

                        <div class="span12">

                            <div class="box">

                                <div class="title">

                                    <h4>
                                        <span class="icon16 wpzoom-user-2"></span>
                                        <span>Nuevo Chofer</span>
                                    </h4>
                                    
                                </div>
                                <div class="content">
                                   
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <span class="span3"><label class="form-label" for="rut">Rut&nbsp;<span style="color: red; font-size: 14pt;font-weight: bold;">*</span></label></span>
                                                    <input class="span2 mayuscula" id="rut" name="rut" type="text" value="<?php echo $rut;?>" maxlength="10" placeholder="Formato: NNNNNNNN-D" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <span class="span3"><label class="form-label" for="normal">Nombre Chofer&nbsp;<span style="color: red; font-size: 14pt;font-weight: bold;">*</span></label></span>
                                                    <input class="span4 mayuscula" id="nombre" name="nombre" type="text" maxlength="50" value="<?php echo $nombre;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <span class="span3"><label class="form-label" for="normal">Apellidos&nbsp;<span style="color: red; font-size: 14pt;font-weight: bold;">*</span></label></span>
                                                    <input class="span4 mayuscula" id="apellidos" name="apellidos" type="text" maxlength="50" value="<?php echo $apellidos;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <span class="span3"><label class="form-label" for="email">Correo Electr&oacute;nico</label></span>
                                                    <input class="span4" id="email" name="email" type="text" maxlength="70" value="<?php echo $email;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <span class="span3"><label class="form-label" for="normal">Direcci&oacute;n Particular</label></span>
                                                    <input class="span4 mayuscula" id="direccion" name="direccion" type="text" maxlength="100" value="<?php echo $direccion;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <span class="span3"><label class="form-label" for="email">Celular Contacto</label></span>
                                                    <input class="span3" id="movil_contacto" name="movil_contacto" type="text" maxlength="20" value="<?php echo $movil_contacto;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid" style="margin-bottom: 15px;">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="normal">Foto (200x160 px aprox.)</label>
                                                    <input type="file" name="foto" id="foto" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">

                                                    <label class="form-label span3" for="checkboxes">Situaci&oacute;n</label>
                                                    
                                                    <div class="span6 controls">
                                                        
                                                        <div class="left marginR10">
                                                        	<?php if ($situacion==0) { ?>
	                                                            <input type="checkbox" id="situacion" name="situacion" checked="checked" class="ibutton1" /> 
	                                                        <?php } else { ?>
	                                                            <input type="checkbox" id="situacion" name="situacion" class="ibutton1" /> 
	                                                        <?php } ?>    
                                                        </div>
                                                    
                                                    </div>
                                                    
                                                </div>
                                            </div> 
                                        </div>
										<div class="form-actions">
                                           <button type="button" class="btn btn-info" onClick="guardar();">Guardar Cambios</button>
                                           <button type="button" class="btn" onClick="cancelar();">Cancelar</button>
                                        </div>
                                 
                                </div>

                            </div><!-- End .box -->

                        </div><!-- End .span12 -->
	    	
                    </div><!-- End .row-fluid -->

            </div><!-- End contentwrapper -->
    	<?php }
    	if ($oper==2) {
			$query = "SELECT * from choferes Where id=".trim($id).";";
			$result = mysqli_query($conn,$query);
			while ($row = mysqli_fetch_array($result))
			{
				$rut = trim($row["rut"]);
				if (is_null($row["nombre"])) {
					$nombre = "";
				} else {
					$nombre = trim($row["nombre"]);
				}
				if (is_null($row["apellidos"])) {
					$apellidos = "";
				} else {
					$apellidos = trim($row["apellidos"]);
				}
				if (is_null($row["movil_contacto"])) {
					$movil_contacto = "";
				} else {
					$movil_contacto = trim($row["movil_contacto"]);
				}
				if (is_null($row["email"])) {
					$email = "";
				} else {
					$email = trim($row["email"]);
				}
				if (is_null($row["direccion"])) {
					$direccion = "";
				} else {
					$direccion = trim($row["direccion"]);
				}
				if (is_null($row["interno"])) {
					$interno = "";
					$ruta = "";
				} else {
					$interno = $row["interno"];
					$ruta = "multimedia/".$interno;
				}
				if (is_null($row["externo"])) {
					$externo = "";
				} else {
					$externo = trim($row["externo"]);
				}
				if (is_null($row["estado"])) {
					$situacion = "0";
				} else {
					$situacion = $row["estado"];
				}
			}
			mysqli_free_result($result);
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
                        <li class="active" style="color: white;">Editar Ficha Chofer</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                   <div class="row-fluid">

                        <div class="span12">

                            <div class="box">

                                <div class="title">

                                    <h4>
                                        <span class="icon16 entypo-icon-contact"></span>
                                        <span>Editar Ficha Chofer</span>
                                    </h4>
                                    
                                </div>
                                <div class="content">
                                   
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="rut">Rut</label>
                                                    <input class="span2 mayuscula" id="rut" name="rut" type="text" readonly="readonly" value="<?php echo $rut;?>" maxlength="10" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <span class="span3"><label class="form-label" for="normal">Nombre Chofer&nbsp;<span style="color: red; font-size: 14pt;font-weight: bold;">*</span></label></span>
                                                    <input class="span4 mayuscula" id="nombre" name="nombre" type="text" maxlength="50" value="<?php echo $nombre;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <span class="span3"><label class="form-label" for="normal">Apellidos&nbsp;<span style="color: red; font-size: 14pt;font-weight: bold;">*</span></label></span>
                                                    <input class="span4 mayuscula" id="apellidos" name="apellidos" type="text" maxlength="50" value="<?php echo $apellidos;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <span class="span3"><label class="form-label" for="email">Correo Electr&oacute;nico</label></span>
                                                    <input class="span4" id="email" name="email" type="text" maxlength="70" value="<?php echo $email;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <span class="span3"><label class="form-label" for="normal">Direcci&oacute;n Particular</label></span>
                                                    <input class="span4 mayuscula" id="direccion" name="direccion" type="text" maxlength="100" value="<?php echo $direccion;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <span class="span3"><label class="form-label" for="email">Celular Contacto</label></span>
                                                    <input class="span3" id="movil_contacto" name="movil_contacto" type="text" maxlength="20" value="<?php echo $movil_contacto;?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid" style="margin-bottom: 15px;">
                                            <div class="span12">
                                                <div class="row-fluid">
	                                                <div class="span3" style="border: 0px dotted red;">
                                                    <label class="form-label" for="normal">Foto (100x80 aprox.)</label>
                                                    </div>
	                                                <div class="span3" style="border: 0px dotted red; margin-left: 0px;">
                                                    <input style="vertical-align: top;" type="file" name="foto" id="foto" />
                                                    </div>
	                                                <?php
	                                                if ($interno != "") { ?>
	                                                <div class="span3" style="border: 0px dotted red;">
	                                                	<img src="<?php echo $ruta;?>" width="50" style="border: 4px ridge #CCCCCC;" border="1">
	                                                </div>	
	                                                <?php } ?>    
                                                </div>
                                            </div>
                                            <!--
                                            <div class="span4">
                                                <?php
                                                if ($interno != "") { ?>
                                                	<img src="<?php echo $ruta;?>" width="100" style="border: 4px ridge #CCCCCC;" border="1">
                                                <?php } ?>    
                                            </div>-->
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">

                                                    <label class="form-label span3" for="checkboxes">Situaci&oacute;n</label>
                                                    
                                                    <div class="span6 controls">
                                                        
                                                        <div class="left marginR10">
                                                        	<?php if ($situacion==0) { ?>
	                                                            <input type="checkbox" id="situacion" name="situacion" checked="checked" class="ibutton1" /> 
	                                                        <?php } else { ?>
	                                                            <input type="checkbox" id="situacion" name="situacion" class="ibutton1" /> 
	                                                        <?php } ?>    
                                                        </div>
                                                    
                                                    </div>
                                                    
                                                </div>
                                            </div> 
                                        </div>
										<div class="form-actions">
                                           <button type="button" class="btn btn-info" onClick="actualizar();">Guardar Cambios</button>
                                           <button type="button" class="btn" onClick="cancelar();">Cancelar</button>
                                        </div>
                                 
                                </div>


                            </div><!-- End .box -->

                        </div><!-- End .span12 -->
	    	
                    </div><!-- End .row-fluid -->

            </div><!-- End contentwrapper -->
    	<?php }
    	?>
        </div><!-- End #content -->
    </div><!-- End #wrapper -->
	<input type="hidden" name="oper" value="<?php echo $oper;?>" />
	<input type="hidden" name="id" value="<?php echo $id;?>" />
	<input type="hidden" name="status" value="0" />
	<input type="hidden" name="page" value="<?php echo $page;?>">
	<input type="hidden" name="orden" value="<?php echo $orden;?>">
	<input type="hidden" name="max_file_size" value="120000">
	<input type="hidden" name="maximo" value="120000">
	<input type="hidden" name="lbl_maximo" value="[120KBytes]">
    </form>
    </body>
</html>
<?php } ?>
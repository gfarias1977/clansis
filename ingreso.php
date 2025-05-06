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
		$menu="OTRAS";
		$opcion="ASIGNACIONES";
		//
		$sw_error=0;
		if ($oper==1) {	//agregar
			if ($status==1) {
				if ($guia!="") {
					// Validar Consistencia
					$query = "SELECT * from cargas_otras_faenas Where guia='".trim($guia)."';";
					$result = mysqli_query($conn,$query);
					while ($row = mysqli_fetch_array($result))
					{
						$sw_error=1;
					}
					mysqli_free_result($result);
				}
				if ($sw_error==0) {
					$hoy="'".date("Y-m-d")."'";
					$guia_ = "'".strtoupper($guia)."'";
					$descripcion_ = "'".strtoupper($descripcion)."'";
					if ($faena!="") {
						$faena_ = trim($faena);
					} else {
						$faena_ = "NULL";
					}
					if ($patente!="") {
						$patente_ = trim($patente);
					} else {
						$patente_ = "NULL";
					}
					if ($chofer!="") {
						$chofer_ = trim($chofer);
					} else {
						$chofer_ = "NULL";
					}
					if ($cantidad_guias!="") {
						$cantidad_guias_ = trim($cantidad_guias);
					} else {
						$cantidad_guias_ = "0";
					}
					$query3 = "INSERT INTO cargas_otras_faenas VALUES(NULL,".strtoupper($guia_).",".$descripcion_.",".$patente_.",".$chofer_.",".$cantidad_guias_.",".$faena_.",".$hoy.", NULL, NULL, NULL, 0, 0, 0);";
					$result = mysqli_query($conn,$query3);
					$id_nuevo = mysqli_insert_id($conn);
					//
					$patente_dato = "";
					$rut_chofer_dato = "";
					$query = "SELECT * from patentes Where id=".trim($patente).";";
					$result = mysqli_query($conn,$query);
					while ($row = mysqli_fetch_array($result))
					{
						$patente_dato = trim($row["patente"]);;
					}
					mysqli_free_result($result);
					//
					$query = "SELECT * from choferes Where id=".trim($chofer).";";
					$result = mysqli_query($conn,$query);
					while ($row = mysqli_fetch_array($result))
					{
						$rut_chofer_dato = trim($row["rut"]);;
					}
					mysqli_free_result($result);
					//
					$query3 = "INSERT INTO logisclick VALUES(NULL,".strtoupper($id_nuevo).",".strtoupper($guia_).",".$descripcion_.",'".$patente_dato."','".$rut_chofer_dato."',".$hoy.", NULL, 0);";
					$result = mysqli_query($conn,$query3);
					if ($accion != 3) {
						$oper=0;
					}
					$id=$id_nuevo;
					$status=0;
				}
			}
		}
		if ($oper==2) {	//acualizar
			if ($status==1) {
				$descripcion_ = "'".strtoupper($descripcion)."'";
				if ($faena!="") {
					$faena_ = trim($faena);
				} else {
					$faena_ = "NULL";
				}
				if ($patente!="") {
					$patente_ = trim($patente);
				} else {
					$patente_ = "NULL";
				}
				if ($chofer!="") {
					$chofer_ = trim($chofer);
				} else {
					$chofer_ = "NULL";
				}
				if ($cantidad_guias!="") {
					$cantidad_guias_ = trim($cantidad_guias);
				} else {
					$cantidad_guias_ = "0";
				}
				$query3 = "UPDATE cargas_otras_faenas SET descripcion=".$descripcion_.", patente=".$patente_.", faena=".$faena_.", chofer=".$chofer_.", cantidad_guias=".$cantidad_guias_." WHERE id=".$id.";";
				$result = mysqli_query($conn,$query3);
				//
				$patente_dato = "";
				$rut_chofer_dato = "";
				$query = "SELECT * from patentes Where id=".trim($patente).";";
				$result = mysqli_query($conn,$query);
				while ($row = mysqli_fetch_array($result))
				{
					$patente_dato = trim($row["patente"]);;
				}
				mysqli_free_result($result);
				//
				$query = "SELECT * from choferes Where id=".trim($chofer).";";
				$result = mysqli_query($conn,$query);
				while ($row = mysqli_fetch_array($result))
				{
					$rut_chofer_dato = trim($row["rut"]);;
				}
				mysqli_free_result($result);
				//
				$query3 = "UPDATE logisclick SET descripcion=".$descripcion_.", patente='".$patente_dato."', chofer='".$rut_chofer_dato."' WHERE ingreso_id=".$id.";";
				$result = mysqli_query($conn,$query3);
				//
				$oper=0;
				$status=0;
			}
		}
		if ($oper==3) {	//borrar
			$sw1=false;
			// Verificaci�n consistencia
			// 
			if ($sw1==false) {
				$query = "DELETE FROM cargas_otras_faenas WHERE id=".$id.";";
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
	<script type="text/javascript" src="js/jquery.searchabledropdown-1.0.8.min.js"></script>

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
	    		text: 'GUIA ya existe en Base de Datos.',
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
			    title: 'ERROR DE BORRADO!',
	    		text: '\nAsignacion no puede ser borrada. Existen datos asociadas.',
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
	function agregar( modo )
	{
		document.ingreso.oper.value=1;
		document.ingreso.accion.value=modo;
		document.ingreso.submit();
	}
	function cancelar()
	{
		document.ingreso.oper.value=0;
		document.ingreso.submit();
	}
	function editar( id )
	{
		document.ingreso.oper.value=2;
		document.ingreso.id.value=id;
		document.ingreso.submit();
	}
	function guardar()
	{
		var ret=0;
		if (document.ingreso.guia.value=="") {
			ret=1;
			alert("Debe indicar Guia.");
			document.ingreso.guia.focus();
		} else {
			if (document.ingreso.descripcion.value=="") {
				ret=1;
				alert("Debe ingresar Descripci\363n.");
				document.ingreso.descripcion.focus();
			} else {
				if (document.ingreso.faena.selectedIndex==0) {
					ret=1;
					alert("Debe Indicar Faena.");
					document.ingreso.faena.focus();
				} else {
					if (document.ingreso.patente.selectedIndex==0) {
						ret=1;
						alert("Debe Indicar Patente.");
						document.ingreso.patente.focus();
					} else {
						if (document.ingreso.chofer.selectedIndex==0) {
							ret=1;
							alert("Debe Indicar Chofer.");
							document.ingreso.chofer.focus();
						} else {
							if (document.ingreso.cantidad_guias.value=="") {
								ret=1;
								alert("Debe ingresar Cantidad de Guias.");
								document.ingreso.cantidad_guias.focus();
							} 
						}
					}
				}
			}
		}	
		if (ret==0) {
			document.ingreso.status.value=1;
			document.ingreso.accion.value=1;
			document.ingreso.submit();
		}
	}
	function repetir()
	{
		var ret=0;
		if (document.ingreso.guia.value=="") {
			ret=1;
			alert("Debe indicar Guia.");
			document.ingreso.guia.focus();
		} else {
			if (document.ingreso.descripcion.value=="") {
				ret=1;
				alert("Debe ingresar Descripci\363n.");
				document.ingreso.descripcion.focus();
			} else {
				if (document.ingreso.faena.selectedIndex==0) {
					ret=1;
					alert("Debe Indicar Faena.");
					document.ingreso.faena.focus();
				} else {
					if (document.ingreso.patente.selectedIndex==0) {
						ret=1;
						alert("Debe Indicar Patente.");
						document.ingreso.patente.focus();
					} else {
						if (document.ingreso.chofer.selectedIndex==0) {
							ret=1;
							alert("Debe Indicar Chofer.");
							document.ingreso.chofer.focus();
						} else {
							if (document.ingreso.cantidad_guias.value=="") {
								ret=1;
								alert("Debe ingresar Cantidad de Guias.");
								document.ingreso.cantidad_guias.focus();
							} 
						}
					}
				}
			}
		}	
		if (ret==0) {
			document.ingreso.status.value=1;
			document.ingreso.accion.value=3;
			document.ingreso.submit();
		}
	}
	function actualizar()
	{
		var ret=0;
		if (document.ingreso.descripcion.value=="") {
			ret=1;
			alert("Debe ingresar Descripci\363n.");
			document.ingreso.descripcion.focus();
		} else {
			if (document.ingreso.faena.selectedIndex==0) {
				ret=1;
				alert("Debe Indicar Faena.");
				document.ingreso.faena.focus();
			} else {
				if (document.ingreso.patente.selectedIndex==0) {
					ret=1;
					alert("Debe Indicar Patente.");
					document.ingreso.patente.focus();
				} else {
					if (document.ingreso.chofer.selectedIndex==0) {
						ret=1;
						alert("Debe Indicar Chofer.");
						document.ingreso.chofer.focus();
					} else {
						if (document.ingreso.cantidad_guias.value=="") {
							ret=1;
							alert("Debe ingresar Cantidad de Guias.");
							document.ingreso.cantidad_guias.focus();
						} 
					}
				}
			}
		}
		if (ret==0) {
			document.ingreso.status.value=1;
			document.ingreso.submit();
		}
	}
	function borrar( nombre, id )
	{
		if (confirm("\277 Confirma la eliminaci\363n de esta Asignacion: \n" + nombre + " ?")) {
			document.ingreso.id.value = id;
			document.ingreso.oper.value = 3;
			document.ingreso.submit();
		}
	}
	function enlacePaginacion( pagina ) 
	{
		document.ingreso.page.value=pagina;
		document.ingreso.submit();
	}
	function busqueda()
	{
		document.ingreso.submit();
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
    
	<script type="text/javascript">
		$(document).ready(function() {
			$("select").searchable();
		});
    </script>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
      
    <body onLoad="inicio();">
    <form method="post" action="ingreso.php" target="_self" name="ingreso" id="ingreso" onSubmit="return true;">
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
			if(isset($_POST['page'])){
			    $page= $_POST['page'];
			}else{
			    $page=1;
			}	
			$rows_per_page= 70;
			$tiene_filtro = false;
			$query = "SELECT * from cargas_otras_faenas A WHERE A.estado = 1;";
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
                        <li class="active" style="color: white;">Ingreso Manual Asignaciones</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                    <div class="row-fluid">

                        <div class="span12">
                            
                            <div class="box">

                                <div class="title">
                                    <h4>
                                        <span class="icon16 brocco-icon-paperclip"></span>
                                        <span>Ingreso de Asignaciones</span> 
                                        <a class="btn btn-mini tipL marginR10 marginB10 right" title="Agregar con Pre-Carga" href="#" onClick="agregar(2);">
                                            <span class="icon16 silk-icon-checklist"></span>
                                        </a>
                                        <a class="btn btn-mini tipL marginR10 marginB10 right" title="Agregar Nueva Asignaci&oacute;n" href="#" onClick="agregar(1);">
                                            <span class="icon16 icomoon-icon-paper"></span>
                                        </a>
                                    </h4>
                                </div>
                                <div class="content noPad">
                                    <table class="responsive table table-bordered">
                                        <thead>
                                          <tr>
                                            <th style="text-align: center; width: 3%;">#</th>
                                            <!--<th style="text-align: center; width: 70px;">Fecha</th>-->
                                            <th style="text-align: left; width: 70px;">Guia</th>
                                            <th style="text-align: left;">Descripci&oacute;n</th>
                                            <!--<th style="text-align: left; width: 150px;">Cliente</th>-->
                                            <th style="text-align: left; width: 150px;">Faena</th>
                                            <th style="text-align: center; width: 70px;">Patente</th>
                                            <th style="text-align: left; width: 180px;">Chofer</th>
                                            <th style="text-align: right; width: 50px;"># Guias</th>
                                            <th width="70">Acciones</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        <?php
										$contador=(($page-1)*($rows_per_page));
										$query = "SELECT A.*, B.patente AS 'DES_PATENTE', C.nombre, C.apellidos, D.descripcion AS 'DES_FAENA' FROM cargas_otras_faenas A LEFT JOIN patentes B ON A.patente=B.id LEFT JOIN choferes C ON A.chofer=C.id LEFT JOIN faenas D ON A.faena=D.id WHERE A.estado = 0 ORDER BY A.fecha_registro ";
										$query .= " $limit";
										$result = mysqli_query($conn,$query);
										//echo $query;
										while ($row = mysqli_fetch_array($result))
										{
                                		    echo "<TR>\n";
                                		    $contador=$contador+1;
                                		    $chofer = $row["nombre"];
                                		    if (!is_null($row["apellidos"])) {
	                                		    $chofer = $chofer . " " . $row["apellidos"];
                                		    }
											?>
	                                          <tr>
	                                            <td style="text-align: center;"><?php echo $contador;?></td>
	                                            <td style="text-align: left;"><?php echo $row["guia"];?></td>
	                                            <td style="text-align: left;"><?php echo $row["descripcion"];?></td>
	                                            <td style="text-align: left;"><?php echo $row["DES_FAENA"];?></td>
	                                            <td style="text-align: center;"><?php echo $row["DES_PATENTE"];?></td>
	                                            <td style="text-align: left;"><?php echo $chofer;?></td>
	                                            <td style="text-align: right;"><?php echo $row["cantidad_guias"];?></td>
	                                            <td>
	                                                <div class="controls center">
	                                                    <a href="#" title="Editar" class="tip" onClick="editar(<?php echo trim($row['id']);?>);"><span class="icon12 icomoon-icon-pencil"></span></a>
	                                                    <a href="#" title="Borrar" class="tip" onClick="borrar('<?php echo trim($row['descripcion']);?>', <?php echo trim($row['id']);?>);"><span class="icon12 icomoon-icon-remove"></span></a>
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
	    	if ($accion==2 || $accion==3) {
		    	if ($id!=0) {
					$query = "SELECT * from cargas_otras_faenas Where id=".trim($id).";";
					$result = mysqli_query($conn,$query);
					while ($row = mysqli_fetch_array($result))
					{
						$guia = "";
						$descripcion = trim($row["descripcion"]);
						if (is_null($row["faena"])) {
							$faena = "";
						} else {
							$faena = trim($row["faena"]);
						}
						if (is_null($row["patente"])) {
							$patente = "";
						} else {
							$patente = trim($row["patente"]);
						}
						if (is_null($row["chofer"])) {
							$chofer = "";
						} else {
							$chofer = trim($row["chofer"]);
						}	
						if (is_null($row["cantidad_guias"])) {
							$cantidad_guias = "0";
						} else {
							$cantidad_guias = trim($row["cantidad_guias"]);
						}	
					}
					mysqli_free_result($result);
				} else {
					$query = "SELECT * from cargas_otras_faenas Order by id desc;";
					$result = mysqli_query($conn,$query);
					while ($row = mysqli_fetch_array($result))
					{
						$guia = "";
						$descripcion = trim($row["descripcion"]);
						if (is_null($row["faena"])) {
							$faena = "";
						} else {
							$faena = trim($row["faena"]);
						}
						if (is_null($row["patente"])) {
							$patente = "";
						} else {
							$patente = trim($row["patente"]);
						}
						if (is_null($row["chofer"])) {
							$chofer = "";
						} else {
							$chofer = trim($row["chofer"]);
						}	
						if (is_null($row["cantidad_guias"])) {
							$cantidad_guias = "0";
						} else {
							$cantidad_guias = trim($row["cantidad_guias"]);
						}	
						break;
					}
					mysqli_free_result($result);
				}
    		}
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
                        <li class="active" style="color: white;">Agregar Asignaci&oacute;n</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                   <div class="row-fluid">

                        <div class="span12">

                            <div class="box">

                                <div class="title">

                                    <h4>
                                        <span class="icon16 brocco-icon-paperclip"></span>
                                        <span>Nuevo Asignaci&oacute;n</span>
                                    </h4>
                                    
                                </div>
                                <div class="content">
                                   
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="guia">Guia</label>
                                                    <input class="span2 mayuscula" id="guia" name="guia" type="text" value="<?php echo $guia;?>" maxlength="10" onBlur="val_numeros( this );" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="normal">Descripci&oacute;n</label>
                                                    <input class="span6 mayuscula" id="descripcion" name="descripcion" type="text" value="<?php echo $descripcion;?>" maxlength="70" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="checkboxes">Faena</label>
                                                    <div class="span4 controls">   
                                                        <select name="faena" id="faena" class="nostyle mayuscula" style="width: 300px; min-width: 300px; height: 29px;">
															<OPTION></OPTION>
															<?php
																$query = "SELECT * from faenas order by descripcion";
																$result = mysqli_query($conn,$query);
																while ($row = mysqli_fetch_array($result))
																{
																	if ($faena==$row["id"]) {
																		echo "<OPTION value=".$row["id"]." selected>".$row["descripcion"]."</OPTION>\n";
																	} else {
																		echo "<OPTION value=".$row["id"].">".$row["descripcion"]."</OPTION>\n";
																	}
																}
																mysqli_free_result($result);
															?>
                                                        </select>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="checkboxes">Patente</label>
                                                    <div class="span4 controls">   
                                                        <select name="patente" id="patente" class="nostyle mayuscula" style="width: 120px; min-width: 120px; height: 29px;">
															<OPTION></OPTION>
															<?php
																$query = "SELECT * from patentes where estado = 0 order by patente";
																$result = mysqli_query($conn,$query);
																while ($row = mysqli_fetch_array($result))
																{
																	if ($patente==$row["id"]) {
																		echo "<OPTION value=".$row["id"]." selected>".$row["patente"]."</OPTION>\n";
																	} else {
																		echo "<OPTION value=".$row["id"].">".$row["patente"]."</OPTION>\n";
																	}
																}
																mysqli_free_result($result);
															?>
                                                        </select>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="checkboxes">Chofer</label>
                                                    <div class="span4 controls">   
                                                        <select name="chofer" id="chofer" class="nostyle mayuscula" style="width: 300px; min-width: 300px; height: 29px;">
															<OPTION></OPTION>
															<?php
																$query = "SELECT * from choferes order by apellidos, nombre";
																$result = mysqli_query($conn,$query);
																while ($row = mysqli_fetch_array($result))
																{
																	if (!is_null($row["apellidos"])) {
																		$nom_chofer = $row["apellidos"];
																	}
																	if ($nom_chofer != "") {
																		$nom_chofer = $nom_chofer . " " . $row["nombre"];
																	} else {
																		$nom_chofer = $row["nombre"];
																	}
																	if ($chofer==$row["id"]) {
																		echo "<OPTION value=".$row["id"]." selected>".$nom_chofer."</OPTION>\n";
																	} else {
																		echo "<OPTION value=".$row["id"].">".$nom_chofer."</OPTION>\n";
																	}
																}
																mysqli_free_result($result);
															?>
                                                        </select>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="guia">Cantidad de Guias</label>
                                                    <input class="span1 mayuscula" id="cantidad_guias" name="cantidad_guias" type="text" value="<?php echo $cantidad_guias;?>" maxlength="3" onBlur="val_numeros( this );" />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-actions">
                                           <span class="span4"><button type="button" class="btn btn-info" onClick="guardar();">Guardar Cambios</button>
                                           <button type="button" class="btn" onClick="cancelar();">Cancelar</button></span>
                                           <span class="span8 right" style="text-align: right;"><button type="button" class="btn btn-success span4" onClick="repetir();">Guia Asociada >> </button>
                                        </div>
                                 
                                </div>

                            </div><!-- End .box -->

                        </div><!-- End .span12 -->
	    	
                    </div><!-- End .row-fluid -->

            </div><!-- End contentwrapper -->
    	<?php }
    	if ($oper==2) {
			$query = "SELECT * from cargas_otras_faenas Where id=".trim($id).";";
			$result = mysqli_query($conn,$query);
			while ($row = mysqli_fetch_array($result))
			{
				$guia = trim($row["guia"]);
				$descripcion = trim($row["descripcion"]);
				if (is_null($row["faena"])) {
					$faena = "";
				} else {
					$faena = trim($row["faena"]);
				}
				if (is_null($row["patente"])) {
					$patente = "";
				} else {
					$patente = trim($row["patente"]);
				}
				if (is_null($row["chofer"])) {
					$chofer = "";
				} else {
					$chofer = trim($row["chofer"]);
				}	
				if (is_null($row["cantidad_guias"])) {
					$cantidad_guias = 0;
				} else {
					$cantidad_guias = $row["cantidad_guias"];
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
                        <li class="active" style="color: white;">Editar Asignaci&oacute;n</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                   <div class="row-fluid">

                        <div class="span12">

                            <div class="box">

                                <div class="title">

                                    <h4>
                                        <span class="icon16 brocco-icon-paperclip"></span>
                                        <span>Editar Asignaci&oacute;n</span>
                                    </h4>
                                    
                                </div>
                                <div class="content">
                                   
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="guia">Guia</label>
                                                    <input class="span2 mayuscula" id="guia" name="guia" type="text" readonly="readonly" value="<?php echo $guia;?>" maxlength="10" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="normal">Descripci&oacute;n</label>
                                                    <input class="span6 mayuscula" id="descripcion" name="descripcion" type="text" value="<?php echo $descripcion;?>" maxlength="70" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="checkboxes">Faena</label>
                                                    <div class="span4 controls">   
                                                        <select name="faena" id="faena" class="nostyle mayuscula" style="width: 300px; min-width: 300px; height: 29px;">
															<OPTION></OPTION>
															<?php
																$query = "SELECT * from faenas order by descripcion";
																$result = mysqli_query($conn,$query);
																while ($row = mysqli_fetch_array($result))
																{
																	if ($faena==$row["id"]) {
																		echo "<OPTION value=".$row["id"]." selected>".$row["descripcion"]."</OPTION>\n";
																	} else {
																		echo "<OPTION value=".$row["id"].">".$row["descripcion"]."</OPTION>\n";
																	}
																}
																mysqli_free_result($result);
															?>
                                                        </select>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="checkboxes">Patente</label>
                                                    <div class="span4 controls">   
                                                        <select name="patente" id="patente" class="nostyle mayuscula" style="width: 120px; min-width: 120px; height: 29px;">
															<OPTION></OPTION>
															<?php
																$query = "SELECT * from patentes where estado = 0 order by patente";
																$result = mysqli_query($conn,$query);
																while ($row = mysqli_fetch_array($result))
																{
																	if ($patente==$row["id"]) {
																		echo "<OPTION value=".$row["id"]." selected>".$row["patente"]."</OPTION>\n";
																	} else {
																		echo "<OPTION value=".$row["id"].">".$row["patente"]."</OPTION>\n";
																	}
																}
																mysqli_free_result($result);
															?>
                                                        </select>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="checkboxes">Chofer</label>
                                                    <div class="span4 controls">   
                                                        <select name="chofer" id="chofer" class="nostyle mayuscula" style="width: 300px; min-width: 300px; height: 29px;">
															<OPTION></OPTION>
															<?php
																$query = "SELECT * from choferes order by apellidos, nombre";
																$result = mysqli_query($conn,$query);
																while ($row = mysqli_fetch_array($result))
																{
																	if (!is_null($row["apellidos"])) {
																		$nom_chofer = $row["apellidos"];
																	}
																	if ($nom_chofer != "") {
																		$nom_chofer = $nom_chofer . " " . $row["nombre"];
																	} else {
																		$nom_chofer = $row["nombre"];
																	}
																	if ($chofer==$row["id"]) {
																		echo "<OPTION value=".$row["id"]." selected>".$nom_chofer."</OPTION>\n";
																	} else {
																		echo "<OPTION value=".$row["id"].">".$nom_chofer."</OPTION>\n";
																	}
																}
																mysqli_free_result($result);
															?>
                                                        </select>
                                                    </div> 
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="guia">Cantidad de Guias</label>
                                                    <input class="span1 mayuscula" id="cantidad_guias" name="cantidad_guias" type="text" value="<?php echo $cantidad_guias;?>" maxlength="3" onBlur="val_numeros( this );" />
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
	<input type="hidden" name="accion" value="<?php echo $accion;?>">
    </form>


    </body>
</html>
<?php } ?>
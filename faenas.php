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
		$menu="MANTENEDORES";
		$opcion="FAENAS";
		//
		$sw_error=0;
		$recarga=0;
		If (!isset($fase)) {
			$fase=0;
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
	$(document).ready(function(){
		var	p0 = 0;
		var	p1 = document.faenas.oper.value;
		var	p2 = document.faenas.fase.value;
		$.post("faenas_clientes.php",{ p0: p0, p1: p1, p2: p2 },function(data){$("#datos_clientes").html(data);})
		$("#btn_add_faena").click(function(){
			p1 = document.faenas.cliente_id.value;
			p2 = document.faenas.oper.value;
			p3 = document.faenas.fase2.value;
			$.post("faenas_man_faenas.php",{ p0: p0, p1: p1, p2: p2, p3: p3 },function(data){$("#datos_faenas").html(data);})
		});
		$("#btn_canc_faena").click(function(){
			p1 = document.faenas.cliente_id.value;
			p2 = document.faenas.oper.value;
			p3 = document.faenas.fase2.value;
			$.post("faenas_man_faenas.php",{ p0: p0, p1: p1, p2: p2, p3: p3 },function(data){$("#datos_faenas").html(data);})
		});
		$("#btn_save_faena").click(function(){
			if (document.faenas.descripcion.value=="") {
			} else {
				p0 = document.faenas.faena_id.value;
				p1 = document.faenas.cliente_id.value;
				p2 = document.faenas.oper.value;
				p3 = document.faenas.fase2.value;
				p4 = document.faenas.descripcion.value;
				p5 = document.faenas.modo.value;
				$.post("faenas_man_faenas.php",{ p0: p0, p1: p1, p2: p2, p3: p3, p4: p4, p5: p5 },function(data){$("#datos_faenas").html(data);})
				$("#btn_val_agregar").show();
				$("#btn_val_grabar").hide();
				$("#btn_val_cancelar").hide();
				//setTimeout(function(){
				//    $.post("faenas_clientes.php",{ p0: 0, p1: p1, p2: p2, p3: 0 },function(data){$("#datos_clientes").html(data);})
				//}, 1000);				
			}
		});
	})
	</script>
    
    <script>
    function inicio() 
    {
    }
	function sub_faenas( des_cliente, id_cliente )
	{
		document.faenas.fase2.value=0;
		document.faenas.cliente_id.value = id_cliente;
		document.faenas.titulo_var.value = des_cliente;
		$("#btn_val_agregar").show();
		$("#btn_val_grabar").hide();
		$("#btn_val_cancelar").hide();
		//
		p0 = 0;
		p1 = document.faenas.cliente_id.value;
		p2 = document.faenas.oper.value;
		p3 = document.faenas.fase2.value;
		$.post("faenas_man_faenas.php",{ p0: p0, p1: p1, p2: p2, p3: p3 },function(data){$("#datos_faenas").html(data);})
	}
	function agregar_faena()
	{
		document.faenas.fase2.value=1;
		$("#btn_val_agregar").hide();
		$("#btn_val_grabar").show();
		$("#btn_val_cancelar").show();
	}
	function editar_faena( id_faena )
	{
		document.faenas.fase2.value=2;
		document.faenas.faena_id.value = id_faena;
		$("#btn_val_agregar").hide();
		$("#btn_val_grabar").show();
		$("#btn_val_cancelar").show();
		//
		p0 = document.faenas.faena_id.value;
		p1 = document.faenas.cliente_id.value;
		p2 = document.faenas.oper.value;
		p3 = document.faenas.fase2.value;
		$.post("faenas_man_faenas.php",{ p0: p0, p1: p1, p2: p2, p3: p3 },function(data){$("#datos_faenas").html(data);})
	}
	function cancelar_faena()
	{
		document.faenas.fase2.value=0;
		$("#btn_val_agregar").show();
		$("#btn_val_grabar").hide();
		$("#btn_val_cancelar").hide();
	}
	function guardar_faena()
	{
		var ret=0;
		if (document.faenas.descripcion.value=="") {
			ret=1;
			alert("Debe indicar nombre Faena.");
			document.faenas.descripcion.focus();
		}
		if (ret==0) {
			// insertar
			if (document.faenas.fase2.value==1) {
				document.faenas.modo.value=0;
			}
			// actualizar
			if (document.faenas.fase2.value==2) {
				document.faenas.modo.value=1;
			}
			document.faenas.fase2.value=3;
			$("#btn_var_agregar").show();
			$("#btn_var_grabar").hide();
			$("#btn_var_cancelar").hide();
		}
	}
	function borrar_faena( nombre, id )
	{
		if (confirm("\277 Confirma descartar esta Faena: \n" + nombre + " ?")) {
			document.faenas.faena_id.value = id;
			document.faenas.fase2.value = 4;
			$("#btn_val_agregar").show();
			$("#btn_val_grabar").hide();
			$("#btn_val_cancelar").hide();
			//
			p0 = document.faenas.faena_id.value;
			p1 = document.faenas.cliente_id.value;
			p2 = document.faenas.oper.value;
			p3 = document.faenas.fase2.value;
			$.post("faenas_man_faenas.php",{ p0: p0, p1: p1, p2: p2, p3: p3 },function(data){$("#datos_faenas").html(data);})
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
	function salida() {
		document.faenas.oper.value=9;
		document.faenas.submit();
	}
    </script>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
      
    <body onLoad="inicio();">
    <form method="post" action="faenas.php" target="_self" name="faenas" id="faenas" onSubmit="return true;">
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
    	if ($oper==0) {
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
                        <li class="active" style="color: white;">Mantenedor de Faenas</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="title">
                                <h4>&nbsp;&nbsp;LISTADO DE CLIENTES</h4>
                            </div>
                            <div class="box">
                                <div id="datos_clientes">
                                </div>
                            </div><!-- End .box -->
                        </div><!-- End .span6 -->
                        <div class="span6">
                            <div class="title">
                                <h4>&nbsp;&nbsp;FAENAS ASOCIADAS</h4>
                            </div>
                            <div class="box">
								<div class='title' style="height: 37px; background-color: #FFFFFF;">
				                    <h4>
				                        <span id='btn_val_grabar' class='left' style='margin-left: 0px; display: none;'><button type='button' id='btn_save_faena' class='btn btn-success btn-mini' onClick='guardar_faena();'>Guardar</button></span>
				                        <span id='btn_val_cancelar' class='left' style='margin-left: 10px; display: none;'><button type='button' id='btn_canc_faena' class='btn btn-danger btn-mini' onClick='cancelar_faena();'>Cancelar</button></span>
				                    	<span id='btn_titulo_var' class='left' style='margin-left: 10px; background: transparent; border: none; font-weight: bold;'><input type='text' name='titulo_var' id='titulo_var' class='nostyle' style="border: 0px; background: transparent; color: red;" value=''></span>
				                        <span id='btn_val_agregar' class='right' style='margin-right: 10px; display: none;'><button type='button' id='btn_add_faena' class='btn btn-info btn-mini' onClick='agregar_faena();'>Agregar</button></span>
				                    </h4>
				                </div>
                                <div id="datos_faenas" style="background-color: #EEEEEE;">
                                </div>
                            </div><!-- End .box -->
                        </div><!-- End .span6 -->
                    </div><!-- End .row-fluid -->

            </div><!-- End contentwrapper -->
		<?php } ?>    	
        </div><!-- End #content -->
    </div><!-- End #wrapper -->
	<input type="hidden" name="oper" value="<?php echo $oper;?>" />
	<input type="hidden" name="id" value="<?php echo $id;?>" />
	<input type="hidden" name="cliente_id" value="" />
	<input type="hidden" name="faena_id" value="" />
	<input type="hidden" name="valor_id" value="" />
	<input type="hidden" name="status" value="0" />
	<input type="hidden" name="fase" value="<?php echo $fase;?>" />
	<input type="hidden" name="fase2" value="<?php echo $fase2;?>" />
	<input type="hidden" name="fase3" value="<?php echo $fase3;?>" />
	<input type="hidden" name="modo" value="" />
	<input type="hidden" name="page" value="<?php echo $page;?>">
	<input type="hidden" name="orden" value="<?php echo $orden;?>">
	<input type="hidden" name="recarga" value="<?php echo $recarga;?>">
    </form>


    </body>
</html>
<?php } ?>
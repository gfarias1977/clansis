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
		$opcion="COMUNAS";
		//
		$sw_error=0;
		$recarga=0;
		if ($oper==1) {	//agregar
			if ($status==1) {
				if ($sw_error==0) {
					if ($descripcion!="") {
						$descripcion_ = "'".strtoupper($descripcion)."'";
					} else {
						$descripcion_ = "NULL";
					}
					if ($situacion=="on") {
						$situacion_ = "0";
					} else {
						$situacion_ = "1";
					}
					$query3 = "INSERT INTO comunas VALUES(NULL,".$descripcion_.",".$situacion_.");";
					$result = mysqli_query($conn,$query3);
					$id_nuevo = mysqli_insert_id($conn);
					$oper=0;
					$id=$id_nuevo;
					$status=0;
				}
			}
		}
		if ($oper==2) {	//acualizar
			if ($status==1) {
				if ($sw_error==0) {
					if ($descripcion!="") {
						$descripcion_ = "'".strtoupper($descripcion)."'";
					} else {
						$descripcion_ = "NULL";
					}
					if ($situacion=="on") {
						$situacion_ = "0";
					} else {
						$situacion_ = "1";
					}
					$query3 = "UPDATE comunas SET descripcion=".$descripcion_.", estado=".$situacion_." WHERE id=".$id.";";
					$result = mysqli_query($conn,$query3);
					$oper=0;
					$status=0;
				}
			}
		}
		if ($oper==3) {	//borrar
			$sw1=false;
			// Verificaci�n consistencia
			$query = "SELECT * from clientes Where comuna=".trim($id);
			$result = mysqli_query($conn,$query);
			while ($row = mysqli_fetch_array($result))
			{
				$sw1=true;
			}
			// 
			if ($sw1==false) {
				$query = "DELETE FROM comunas WHERE id=".$id.";";
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
	    		text: 'Mensaje de Error.',
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
	    		text: '\nComuna no puede ser borrada. Esta siendo usada actualmente.',
			    icon: 'picon icon24 typ-icon-cancel white',
			    opacity: 0.95,
			    history: false,
			    sticker: false
			});
		});
	<?php } ?>
    function inicio() 
    {
	 	//alert("<?php echo $query3;?>");   
    }
	function agregar()
	{
		document.comunas.oper.value=1;
		document.comunas.submit();
	}
	function cancelar()
	{
		document.comunas.oper.value=0;
		document.comunas.submit();
	}
	function editar( id )
	{
		document.comunas.oper.value=2;
		document.comunas.id.value=id;
		document.comunas.submit();
	}
	function guardar()
	{
		var ret=0;
		if (document.comunas.descripcion.value=="") {
			ret=1;
			alert("Debe indicar Nombre Comuna.");
			document.comunas.descripcion.focus();
		}
		if (ret==0) {
			document.comunas.status.value=1;
			document.comunas.submit();
		}
	}
	function actualizar()
	{
		var ret=0;
		if (document.comunas.descripcion.value=="") {
			ret=1;
			alert("Debe indicar Nombre Comuna.");
			document.comunas.descripcion.focus();
		}
		if (ret==0) {
			document.comunas.status.value=1;
			document.comunas.submit();
		}
	}
	function borrar( nombre, id )
	{
		if (confirm("\277 Confirma la eliminaci\363n de esta Comuna: \n" + nombre + " ?")) {
			document.comunas.id.value = id;
			document.comunas.oper.value = 3;
			document.comunas.submit();
		}
	}
	function enlacePaginacion( pagina ) 
	{
		document.comunas.page.value=pagina;
		document.comunas.submit();
	}
	function ordenar( orden )
	{
		document.comunas.orden.value = orden;
		document.comunas.submit();
	}
   function en_proceso() {
       alert("Opcion esta en etapa de desarrollo.");
   } 
    </script>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
      
    <body onLoad="inicio();">
    <form method="post" action="comunas.php" target="_self" name="comunas" id="comunas" onSubmit="return true;">
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
			$rows_per_page= 30;
			$query = "SELECT * from comunas ";
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
                        <li class="active" style="color: white;">Listado Comunas</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                    <div class="row-fluid">

                        <div class="span12">
                            
                            <div class="box">

                                <div class="title">
                                    <h4>
                                        <span class="icon16 brocco-icon-location"></span>
                                        <span>Listado de Comunas</span>
                                        <a class="btn btn-mini tipL marginR10 marginB10 right" title="Agregar Nueva Comuna" href="#" onClick="agregar();">
                                            <span class="icon16 minia-icon-plus-2"></span>
                                        </a>
                                    </h4>
                                </div>
                                <div class="content noPad">
                                    <table class="responsive table table-bordered">
                                        <thead>
                                          <tr>
                                            <th style="text-align: center; width: 3%;">#</th>
                                            <th style="text-align: left;">Nombre Comuna</th>
                                            <th width="40" style="text-align: center;">Estado</th>
                                            <th width="10%">Acciones</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        <?php
										$contador=(($page-1)*($rows_per_page));
										$query = "SELECT A.* FROM comunas A ORDER BY A.descripcion ASC ";
										$query .= " $limit";
										$result = mysqli_query($conn,$query);
										while ($row = mysqli_fetch_array($result))
										{
                                		    echo "<TR>\n";
                                		    $contador=$contador+1;
											?>
	                                          <tr>
	                                            <td style="text-align: center;"><?php echo $contador;?></td>
	                                            <td style="text-align: left;" class="mayuscula"><?php echo $row["descripcion"];?></td>
	                                            <?php
	                                            if ($row["estado"]==0) { ?>
		                                            <td style="text-align: center;"><code class="btn-success tipL left" title="Comuna Activa"><span class="icon10 icomoon-icon-checkmark white"></code></td>
		                                        <?php } else { ?>
		                                            <td style="text-align: center;"><code class="btn-danger tipL left" title="Comuna Inactiva"><span class="icon10 icomoon-icon-cancel-2 white"></code></td>
		                                        <?php } ?>    
	                                            <td>
	                                                <div class="controls center">
	                                                    <a href="#" title="Editar Comuna" class="tip" onClick="editar(<?php echo trim($row['id']);?>);"><span class="icon12 icomoon-icon-pencil"></span></a>
	                                                    <a href="#" title="Borrar Comuna" class="tip" onClick="borrar('<?php echo trim($row['descripcion']);?>', <?php echo trim($row['id']);?>);"><span class="icon12 icomoon-icon-remove"></span></a>
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
                        <li class="active" style="color: white;">Agregar Comuna</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                   <div class="row-fluid">

                        <div class="span12">

                            <div class="box">

                                <div class="title">

                                    <h4>
                                        <span class="icon16 brocco-icon-location"></span>
                                        <span>Nueva Comuna</span>
                                    </h4>
                                    
                                </div>
                                <div class="content">
                                   
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="normal">Nombre Comuna</label>
                                                    <input class="span6 mayuscula" id="descripcion" name="descripcion" type="text" value="<?php echo $descripcion;?>" maxlength="70" />
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
	    	if ($recarga==0) {
				$query = "SELECT * from comunas Where id=".trim($id).";";
				$result = mysqli_query($conn,$query);
				while ($row = mysqli_fetch_array($result))
				{
					if (is_null($row["descripcion"])) {
						$descripcion = "";
					} else {
						$descripcion = trim($row["descripcion"]);
					}
					if (is_null($row["estado"])) {
						$situacion = "0";
					} else {
						$situacion = $row["estado"];
					}
				}
				mysqli_free_result($result);
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
                        <li class="active" style="color: white;">Editar Ficha Comuna</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                   <div class="row-fluid">

                        <div class="span12">

                            <div class="box">

                                <div class="title">

                                    <h4>
                                        <span class="icon16 brocco-icon-location"></span>
                                        <span>Editar Ficha Comuna</span>
                                    </h4>
                                    
                                </div>
                                <div class="content">
                                   
                                        <div class="form-row row-fluid">
                                            <div class="span12">
                                                <div class="row-fluid">
                                                    <label class="form-label span3" for="normal">Nombre Comuna</label>
                                                    <input class="span6 mayuscula" id="descripcion" name="descripcion" type="text" value="<?php echo $descripcion;?>" maxlength="70" />
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
	<input type="hidden" name="recarga" value="<?php echo $recarga;?>">
    </form>


    </body>
</html>
<?php } ?>
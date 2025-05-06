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
		include "genera_repFolioOtras.php";
		// Reporte
		$prefijo = date("YmdHis");
		$name = "temp/".trim($prefijo).".pdf";
		$ok = false;
		$conn=opendblocal();
		$nom_usuario = 	$_SESSION["nombreUsuario"];
		$rol_usuario = $_SESSION["rolUsuario"];
		$id_usuario = $_SESSION["idUsuario"];
		date_default_timezone_set("Chile/Continental");		
		$menu="OTRAS";
		$opcion="FOLIOS";
		$hoy=date("d/m/Y");
		if ($fecha=="") {
			$fecha=$hoy;
		}
		//
		$sw_error=0;
		if ($status==0) {
			if (!isset($f_folio)) {
				$f_folio = "";
				$query = "SELECT MAX(folio) as 'MAX_FOLIO' from cargas_otras_faenas ;";
				$result = mysqli_query($conn,$query);
				while ($row = mysqli_fetch_array($result))
				{
					if (!is_null($row["MAX_FOLIO"])) {
						$f_folio = trim($row["MAX_FOLIO"]);
					}
				}
				mysqli_free_result($result);
			}
		}
		if ($status==1) {
			$ok = GeneraReporte( $name, $f_folio );
			$status=0;
		}
		if ($status==2) {	//reversar
		   	$query = "UPDATE cargas_otras_faenas SET estado = 1, folio = NULL, fecha_rendicion = NULL WHERE id=".trim($id)."; ";
			$result = mysqli_query($conn,$query);
			$oper=0;
			$status=0;
		}
		$estado=opendblocal();
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
	function enlacePaginacion( pagina ) 
	{
		document.folios.page.value=pagina;
		document.folios.submit();
	}
	function ordenar( orden )
	{
		document.folios.orden.value = orden;
		document.folios.submit();
	}
	function busqueda()
	{
		document.folios.submit();
	}
	function reversar( id, guia )
	{
		if (confirm("La Gu\355a " + guia + " ser\341 devuelta a la etapa de Recepci\363n de Documentos. \277 Confirma esta acci\363n ?")) {
			document.folios.id.value = id;
			document.folios.status.value = 2;
			document.folios.submit();
		}
	}
	function genReporte() {
		var ret=0;
		if (ret==0) {
			document.folios.oper.value=0;
			document.folios.status.value=1;
			document.folios.target = "_self";
			document.folios.submit();
		}
	}
	function verPDF() {
		window.open("<?php echo $name;?>", "_blank");
	}
    </script>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
      
    <body onLoad="inicio();">
    <form method="post" action="listado_folios_otras.php" target="_self" name="folios" id="folios" onSubmit="return false;">
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
			$rows_per_page= 100;
			$query_master = "";
			$num_rows=0;
			if ($f_folio <> "") {
				$query_master = "SELECT A.* from cargas_otras_faenas A ";
				$condicion = $condicion . " WHERE A.folio = ".trim($f_folio);
				$query_master = $query_master . $condicion;
				$result = mysqli_query($conn,$query_master);
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
                        <li class="active" style="color: white;">Listado FOLIOS Otras Faenas</li>
                    </ul>

                </div><!-- End .heading-->

                <!-- Build page from here: -->
                    <div class="row-fluid">

                        <div class="span12">
                            
                            <div class="box">

                                <div class="title">
                                    <h4>
                                        <span class="icon16 entypo-icon-list"></span>
                                        <span>Listado FOLIOS Otras Faenas</span>
                                    </h4>
                                </div>
                                <div class="content noPad">
	                                <div class="form-row row-fluid" style="text-align: left;">
	                                    <div class="span12">
	                                        <div class="row-fluid" style="height: 40px;">
	                                            <span class="span1" style="margin-left: 10px; margin-top: 6px; border: 0px dotted blue;"><button class="btn tipR" title="Filtrar Folios" href="#" onClick="busqueda();"><span class="icon16 brocco-icon-filter"></span></button></span>
	                                        	<span class="span2" style="margin-left: 0px; margin-top: 6px; border: 0px dotted blue;"><input id="f_folio" name="f_folio" type="text" value="<?php echo $f_folio;?>" maxlength="10" placeholder="FOLIO..." style="width: 90px;" /></span>
												<?php
												if ($ok==true) { ?>
					                                <div class="btn-group span2" style="text-align: left; margin-left: 20px; border: 0px blue dotted; margin-top: 5px;">
				                                        <button class="btn btn-danger" title="Exportar a PDF" onClick="verPDF();"><span class="icon16 white icomoon-icon-file-pdf"></span>PDF</button>
				                                    </div>
			                                    	<span class="span3" style="margin-left: 10px; text-align: right; border: 0px red dotted; margin-top: 5px;"><button class="btn btn-inverse" href="#" onClick="genReporte();"><span class="icon16 white entypo-icon-printer"></span> Imprimir Rendici&oacute;n</button></span>
				                                <?php } else { 
					                                if ($num_rows > 0) { ?>
						                                <div class="btn-group span2" style="text-align: center; border: 0px blue dotted; margin-top: 5px;">
						                                	&nbsp;
					                                    </div>
				                                    	<span class="span3" style="margin-left: 10px; text-align: right; border: 0px red dotted; margin-top: 5px;"><button class="btn btn-inverse" href="#" onClick="genReporte();"><span class="icon16 white entypo-icon-printer"></span> Imprimir Rendici&oacute;n</button></span>
				                                <?php }  } ?>    
	                                        </div>
	                                    </div>
	                                </div>
                                    <table class="responsive table table-bordered">
                                        <thead>
                                          <tr>
                                            <th style="text-align: center; width: 3%;">#</th>
                                            <th style="text-align: center; width: 70px;">Fecha</th>
                                            <th style="text-align: left; width: 90px;">Guia&nbsp;&nbsp;<a href="#" onClick="ordenar(11);"><span class="icon-chevron-up" title="Ascendente"></span></a>&nbsp;<a href="#" onClick="ordenar(12);"><span class="icon-chevron-down" title="Descendente"></span></a></th>
                                            <th style="text-align: left; width: 90px;">Patente&nbsp;&nbsp;<a href="#" onClick="ordenar(21);"><span class="icon-chevron-up" title="Ascendente"></span></a>&nbsp;<a href="#" onClick="ordenar(22);"><span class="icon-chevron-down" title="Descendente"></span></a></th>
                                            <th style="text-align: left;">Chofer&nbsp;&nbsp;<a href="#" onClick="ordenar(31);"><span class="icon-chevron-up" title="Ascendente"></span></a>&nbsp;<a href="#" onClick="ordenar(32);"><span class="icon-chevron-down" title="Descendente"></span></a></th>
                                            <th style="text-align: left; width: 150px;">Faena&nbsp;&nbsp;<a href="#" onClick="ordenar(41);"><span class="icon-chevron-up" title="Ascendente"></span></a>&nbsp;<a href="#" onClick="ordenar(42);"><span class="icon-chevron-down" title="Descendente"></span></a></th>
                                            <th width="60">Acciones</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if ($query_master!="") {
											$contador=(($page-1)*($rows_per_page));
											if ($orden==0) {
												$query = "SELECT A.*,DATE_FORMAT(A.fecha_rendicion, '%d/%m/%Y') AS 'FechaRendicion', B.patente AS 'DES_PATENTE', C.nombre, C.apellidos, D.descripcion AS 'DES_FAENA' from cargas_otras_faenas A LEFT JOIN patentes B ON A.patente=B.id LEFT JOIN choferes C ON A.chofer=C.id LEFT JOIN faenas D ON A.faena=D.id ".$condicion." ORDER BY A.guia ASC ";
											}
											if ($orden==11) {
												$query = "SELECT A.*,DATE_FORMAT(A.fecha_rendicion, '%d/%m/%Y') AS 'FechaRendicion', B.patente AS 'DES_PATENTE', C.nombre, C.apellidos, D.descripcion AS 'DES_FAENA' from cargas_otras_faenas A LEFT JOIN patentes B ON A.patente=B.id LEFT JOIN choferes C ON A.chofer=C.id LEFT JOIN faenas D ON A.faena=D.id ".$condicion." ORDER BY A.guia ASC ";
											}
											if ($orden==12) {
												$query = "SELECT A.*,DATE_FORMAT(A.fecha_rendicion, '%d/%m/%Y') AS 'FechaRendicion', B.patente AS 'DES_PATENTE', C.nombre, C.apellidos, D.descripcion AS 'DES_FAENA' from cargas_otras_faenas A LEFT JOIN patentes B ON A.patente=B.id LEFT JOIN choferes C ON A.chofer=C.id LEFT JOIN faenas D ON A.faena=D.id ".$condicion." ORDER BY A.guia DESC ";
											}
											if ($orden==21) {
												$query = "SELECT A.*,DATE_FORMAT(A.fecha_rendicion, '%d/%m/%Y') AS 'FechaRendicion', B.patente AS 'DES_PATENTE', C.nombre, C.apellidos, D.descripcion AS 'DES_FAENA' from cargas_otras_faenas A LEFT JOIN patentes B ON A.patente=B.id LEFT JOIN choferes C ON A.chofer=C.id LEFT JOIN faenas D ON A.faena=D.id ".$condicion." ORDER BY B.patente ASC, A.guia ASC ";
											}
											if ($orden==22) {
												$query = "SELECT A.*,DATE_FORMAT(A.fecha_rendicion, '%d/%m/%Y') AS 'FechaRendicion', B.patente AS 'DES_PATENTE', C.nombre, C.apellidos, D.descripcion AS 'DES_FAENA' from cargas_otras_faenas A LEFT JOIN patentes B ON A.patente=B.id LEFT JOIN choferes C ON A.chofer=C.id LEFT JOIN faenas D ON A.faena=D.id ".$condicion." ORDER BY B.patente DESC, A.guia ASC ";
											}
											if ($orden==31) {
												$query = "SELECT A.*,DATE_FORMAT(A.fecha_rendicion, '%d/%m/%Y') AS 'FechaRendicion', B.patente AS 'DES_PATENTE', C.nombre, C.apellidos, D.descripcion AS 'DES_FAENA' from cargas_otras_faenas A LEFT JOIN patentes B ON A.patente=B.id LEFT JOIN choferes C ON A.chofer=C.id LEFT JOIN faenas D ON A.faena=D.id ".$condicion." ORDER BY C.apellidos, C.nombre, B.patente, A.guia ASC ";
											}
											if ($orden==32) {
												$query = "SELECT A.*,DATE_FORMAT(A.fecha_rendicion, '%d/%m/%Y') AS 'FechaRendicion', B.patente AS 'DES_PATENTE', C.nombre, C.apellidos, D.descripcion AS 'DES_FAENA' from cargas_otras_faenas A LEFT JOIN patentes B ON A.patente=B.id LEFT JOIN choferes C ON A.chofer=C.id LEFT JOIN faenas D ON A.faena=D.id ".$condicion." ORDER BY C.apellidos DESC, C.nombre DESC, B.patente, A.guia ASC ";
											}
											if ($orden==41) {
												$query = "SELECT A.*,DATE_FORMAT(A.fecha_rendicion, '%d/%m/%Y') AS 'FechaRendicion', B.patente AS 'DES_PATENTE', C.nombre, C.apellidos, D.descripcion AS 'DES_FAENA' from cargas_otras_faenas A LEFT JOIN patentes B ON A.patente=B.id LEFT JOIN choferes C ON A.chofer=C.id LEFT JOIN faenas D ON A.faena=D.id ".$condicion." ORDER BY D.descripcion, B.patente, A.guia ASC ";
											}
											if ($orden==42) {
												$query = "SELECT A.*,DATE_FORMAT(A.fecha_rendicion, '%d/%m/%Y') AS 'FechaRendicion', B.patente AS 'DES_PATENTE', C.nombre, C.apellidos, D.descripcion AS 'DES_FAENA' from cargas_otras_faenas A LEFT JOIN patentes B ON A.patente=B.id LEFT JOIN choferes C ON A.chofer=C.id LEFT JOIN faenas D ON A.faena=D.id ".$condicion." ORDER BY D.descripcion DESC, B.patente, A.guia ASC ";
											}
											$query .= " $limit";
											//echo "<tr><td colspan=6>".$query."</td></tr>\n";
											$result = mysqli_query($conn,$query);
											while ($row = mysqli_fetch_array($result))
											{
	                                		    echo "<TR>\n";
	                                		    $contador=$contador+1;
	                                		    $guia = trim($row["guia"]);
	                                		    $chofer = "";
	                                		    if (!is_null($row["nombre"])) {
		                                		    $chofer = trim($row["nombre"]);
	                                		    }
	                                		    if (!is_null($row["apellidos"])) {
		                                		    $chofer = $chofer . " " . trim($row["apellidos"]);
	                                		    }
	                                		    $estadoRI = "";
	                                		    if ($row["estado"]==1) {
		                                		    $estadoRI = "COMPLETO";
	                                		    }
	                                		    if ($row["estado"]==2) {
		                                		    $estadoRI = "C/PENDIENTES";
	                                		    }
	                                		    $estadoRC = "";
	                                		    if ($row["estadoRC"]==1) {
		                                		    $estadoRC = "RENDIDO";
	                                		    }
												?>
		                                          <tr>
		                                            <td style="text-align: center;"><?php echo $contador;?></td>
		                                            <td style="text-align: center;"><?php echo trim($row["FechaRendicion"]);?></td>
		                                            <td style="text-align: left;"><?php echo trim($row["guia"]);?></td>
		                                            <td style="text-align: left;"><?php echo trim($row["DES_PATENTE"]);?></td>
		                                            <td style="text-align: left;"><?php echo $chofer;?></td>
		                                            <td style="text-align: center;"><?php echo trim($row["DES_FAENA"]);?></td>
		                                            <td>
		                                                <div class="controls center">
		                                                	<?php
		                                                	if ($estadoGuia==0) { ?>
			                                                    <a href="#" title="Regresar a Recepcion" class="tip" onClick="reversar(<?php echo trim($row['id']);?>, '<?php echo $guia;?>');"><span class="icon12 entypo-icon-reply"></span></a>
			                                                <?php } ?>    
		                                                </div>
		                                            </td>
		                                          </tr>
		                                    <?php      
											}
											mysqli_free_result($result);
										}
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
    	?>
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
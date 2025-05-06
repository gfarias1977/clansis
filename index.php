<?php
	session_start();
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             	// fecha pasada...
  	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 	// ultima modificacion
  	header("Cache-Control: no-cache, must-revalidate");           	// HTTP/1.1
  	header("Pragma: no-cache");
	header("Content-type: text/html; charset=utf8");
	include "include/header.php";
	$conn=opendblocal();
	date_default_timezone_set("Chile/Continental");		
	$_SESSION["idUsuario"];
	$_SESSION["nombreUsuario"];
	$_SESSION["rolUsuario"];
	$_SESSION["desRolUsuario"];
	$_SESSION["titulo"]="TRANSGAMBOA";
	$codError=0;
	if (isset($btn_entrar)) {
		if ($conn->errno==0) {
			$codError=1;
			$query = "select a.*, b.descripcion as 'des_rol' from usuarios a left join roles b on a.rol=b.id where a.password = PASSWORD(lower('".$password."')) and a.clave = lower('".$username."')";
			$result = mysqli_query($conn,$query);
			if ($result) {
				if (mysqli_num_rows($result)) {
					$codError=8;
					while ($row = mysqli_fetch_array($result))
					{
						$codError=7;
						$_SESSION['rolUsuario'] = $row["rol"];
						$rolusu = $row["rol"];
						if (!empty($row["des_rol"])) {
							$_SESSION['desRolUsuario'] = $row["des_rol"];
						}
						$clave_usuario = $row["clave"];
						$_SESSION['nombreUsuario'] = $row["nombre"];
						$codError=0;
						$_SESSION['idUsuario'] = $row["id"];
						if ($row["estado"]==1) {
							$codError=2;
						}
					}
				}
			}
			mysqli_free_result($result);
	    	//
	    	if ($codError > 0) {
		    	$oper=0;
	    	} else {
		    	$oper=1;
	    	}
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
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="css/supr-theme/jquery.ui.supr.css" rel="stylesheet" type="text/css" />
    <link href="css/icons.css" rel="stylesheet" type="text/css" />
    <link href="plugins/uniform/uniform.default.css" type="text/css" rel="stylesheet" />

    <!-- Main stylesheets -->
    <link href="css/main.css" rel="stylesheet" type="text/css" /> 

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
        <link type="text/css" href="css/ie.css" rel="stylesheet" />
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/apple-touch-icon-144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png" />

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

	<script type="text/javascript">
	function inicio() {
		<?php 
			if ($oper==1) { 	
				echo "document.location.href=\"inicio.php\";\n";
			} else { 
				echo "document.loginForm.username.focus();\n";
			}
		?>	
	}
	</script>	
          
    <body class="loginPage" onload="inicio();">

    <div class="container-fluid">

        <div id="header">

            <div class="row-fluid">

                <div class="navbar">
                  <div class="mylogo">
                    <div class="navbar-inner">
                      <div class="container">
                      </div>
                    </div><!-- /navbar-inner -->
                  </div>
                  </div><!-- /navbar -->
                

            </div><!-- End .row-fluid -->

        </div><!-- End #header -->

    </div><!-- End .container-fluid -->    

    <div class="container-fluid">

        <div class="loginContainer">
            <form class="form-horizontal" action="index.php" method="post" id="loginForm" name="loginForm" />
                <div class="form-row row-fluid">
                    <div class="span12">
                        <div class="row-fluid">
                            <label class="form-label span12" for="username">
                                <span style="color: white; font-weight: bold;">Cuenta Usuario:</span>
                                <span class="icon16 icomoon-icon-user-2 right gray marginR10"></span>
                            </label>
                            <input class="span12" id="username" type="text" name="username" value="" />
                        </div>
                    </div>
                </div>

                <div class="form-row row-fluid">
                    <div class="span12">
                        <div class="row-fluid">
                            <label class="form-label span12" for="password">
                                <span style="color: white; font-weight: bold;">Contrase&ntilde;a:</span>
                                <span class="icon16 icomoon-icon-locked right gray marginR10"></span>
                            </label>
                            <input class="span12" id="password" type="password" name="password" value="" />
                        </div>
                    </div>
                </div>
                <div class="form-row row-fluid">                       
                    <div class="span12">
                        <div class="row-fluid">
                            <div class="form-actions" style="background: #5b8fa8;">
                            <div class="span12 controls">
                                <?php 
                                	$advertencia="";
                                	if ($codError==1) {
	                                	$advertencia="<strong>Error! </strong>Datos ingresados no son correctos.";
                                	}
                                	if ($codError==2) {
	                                	$advertencia="<strong>Error! </strong>Usuario se encuentra suspendido.";
                                	}
                                	if ($codError==3) {
	                                	$advertencia="<strong>Error! </strong>Base de Datos no encontrada.";
                                	}
                                ?>
                                <?php
                                	if ($codError > 0) { ?>
			                            <div class="alert alert-error">
			                                <button class="close" data-dismiss="alert"></button>
			                                <?php echo $advertencia;?>
			                            </div>
			                    <?php } ?>    
                                <button type="submit" class="btn btn-primary right" name="btn_entrar" id="btn_entrar"><span class="icon16 icomoon-icon-enter white"></span> Acceso</button>
                            </div>
                            </div>
                        </div>
                    </div> 
                </div>

            </form>
        </div>

    </div><!-- End .container-fluid -->

    <!-- Le javascript
    ================================================== -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>  
    <script type="text/javascript" src="plugins/touch-punch/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="plugins/ios-fix/ios-orientationchange-fix.js"></script>
    <script type="text/javascript" src="plugins/validate/jquery.validate.min.js"></script>
    <script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>

     <script type="text/javascript">
        // document ready function
        $(document).ready(function() {
            $("input, textarea, select").not('.nostyle').uniform();
            $("#loginForm").validate({
                rules: {
                    username: {
                        required: true,
                    },
                    password: {
                        required: true,
                    }  
                },
                messages: {
                    username: {
                        required: "<span style='color: #07152c;'>Favor ingresar Clave</span>",
                        minlength: "My name is bigger"
                    },
                    password: {
                        required: "<span style='color: #07152c;'>Favor ingresar password</span>",
                    }
                }   
            });
        });
    </script>

    </body>
</html>

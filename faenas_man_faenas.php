<?php
	// carga rutinas de acceso a base de datos
	include "include/header.php";
	$conn=opendblocal();
	//
	$_id_faena= $_REQUEST["p0"];
	$_id_cliente= $_REQUEST["p1"];
	$_oper= $_REQUEST["p2"];
	$_fase2= $_REQUEST["p3"];
	$_descripcion= $_REQUEST["p4"];
	$_modo= $_REQUEST["p5"];
	$script = "";
	$mensaje="";
	if ($_oper==0) { 
		if ($_fase2==3) { 
			if ($_descripcion!="") {
				$descripcion_ = "'".strtoupper(trim($_descripcion))."'";
				if ($_modo=="0") {
					$query3 = "INSERT INTO faenas VALUES(NULL,".trim($_id_cliente).",".$descripcion_.", 0);";
				} else {
					$query3 = "UPDATE faenas SET descripcion=".$descripcion_." WHERE id=".trim($_id_faena).";";
				}
				//$script = $query3;
				$result = mysqli_query($conn,$query3);
			}
			$_fase2=0;
		}
		if ($_fase2==4) { 
			if ($_id_faena!="") {
				$mensaje="";
				/*
				$query = "SELECT * FROM familias_com A WHERE categoria_com_id=".trim($_id_cat)." AND subcategoria_com_id=".trim($_id_sub).";";
				$result = mysql_query($query);
				while ($row = mysql_fetch_array($result))
				{
					$mensaje="Item no puede ser borrado.  Tiene dependencias asociadas.";
					break;
				}
				mysql_free_result($result);
				*/
				if ($mensaje=="") {
					$query3 = "DELETE FROM faenas WHERE id=".trim($_id_faena).";";
					$result = mysqli_query($conn,$query3);
				}
			}
			$_fase2=0;
		}
		if ($_fase2==0) { 
            $script = $script . "<div class='content noPad'>
                    <table class='responsive table table-bordered'>
                        <thead>
                          <tr>
                            <th width='20' style='text-align: center;'>#</th>
                            <th style='text-align: left;'>Nombre Faena</th>
                            <th width='80'>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>";
			$contador=0;
			$query = "SELECT A.* FROM faenas A WHERE A.estado=0 AND A.cliente_id=".trim($_id_cliente)." ORDER BY A.descripcion;";
			$result = mysqli_query($conn,$query);
			while ($row = mysqli_fetch_array($result))
			{
			    $contador=$contador+1;
			    $des_faena = $row["descripcion"];
				$script = $script . "<tr>
					<td style='text-align: center;'>".trim($contador)."</td>
					<td style='text-align: left;'>".$des_faena."</td>
				<td>
				    <div class='controls center'>
                        <a href='#' title='Editar Faena' class='tip' onClick=\"editar_faena(".trim($row['id']).");\"><span class='icon12 icomoon-icon-pencil'></span></a>
				        <a href='#' title='Descartar Faena' class='tip' onClick=\"borrar_faena('".trim($des_faena)."', ".trim($row['id']).");\"><span class='icon12 icomoon-icon-remove'></span></a>
				    </div>
				</td>
				</tr>";
			}
			mysqli_free_result($result);
			if ($contador==0) {
				$script = $script . "<tr><td colspan=3 style='color: red;'>&nbsp;NO EXISTEN FAENAS REGISTRADAS.</td></tr>";
			} else {
				$script = $script . "<tr><td colspan=3 style='color: red;'>&nbsp;".trim($mensaje)."</td></tr>";
			}
			$script = $script . "</tbody></table></div>";
			//$script = $query;
		} 
		if ($_fase2==1) {
			$script = $script . "<div class='form-row row-fluid' style='background-color: white; border-left: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-bottom: 1px solid #CCCCCC; '>
                                    <div class='span12'>
                                        <div class='row-fluid' style='margin-top: 15px; margin-bottom: 10px;'>
                                            <label class='form-label span5' for='normal'>Nombre Faena</label>
                                            <input class='span6 mayuscula' id='descripcion' name='descripcion' type='text' value='' maxlength='30' />
                                        </div>
                                    </div>
                                </div>";
			
		}
		if ($_fase2==2) {
	    	if ($_id_faena!=0) {
				$query = "SELECT * from faenas Where id=".trim($_id_faena).";";
				$result = mysqli_query($conn,$query);
				while ($row = mysqli_fetch_array($result))
				{
					$descripcion = trim($row["descripcion"]);
				}
				mysqli_free_result($result);
			}
			
			$script = $script . "<div class='form-row row-fluid' style='background-color: white; border-left: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>
                                    <div class='span12'>
                                        <div class='row-fluid' style='margin-top: 15px; margin-bottom: 10px;'>
                                            <label class='form-label span5' for='normal'>Nombre Faena</label>
                                            <input class='span6 mayuscula' id='descripcion' name='descripcion' type='text' value='".trim($descripcion)."' maxlength='30' />
                                        </div>
                                    </div>
                                </div>";
			
		}
	}
	echo $script;
?>
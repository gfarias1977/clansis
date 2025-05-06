<?php
	// carga rutinas de acceso a base de datos
	include "include/header.php";
	$conn=opendblocal();
	//
	$_oper= $_REQUEST["p1"];
	$_fase= $_REQUEST["p2"];
	$script = "";
	if ($_oper==0) { 
		if ($_fase==0) { 
            $script = $script . "<div class='content noPad'>
                    <table class='responsive table table-bordered'>
                        <thead>
                          <tr>
                            <th width='20' style='text-align: center;'>#</th>
                            <th style='text-align: left;'>Raz&oacute;n Social Cliente</th>
                            <th width='20'>&nbsp;</th>
                          </tr>
                        </thead>
                        <tbody>";
			$contador=0;
			$query = "SELECT A.* FROM clientes A WHERE A.estado=0 ORDER BY A.razon_social;";
			$result = mysqli_query($conn,$query);
			while ($row = mysqli_fetch_array($result))
			{
			    $contador=$contador+1;
			    $des_cliente = $row["razon_social"];
				$script = $script . "<tr>
					<td style='text-align: center;'>".trim($contador)."</td>
					<td style='text-align: left;'>".$des_cliente."</td>";
				$script = $script . "<td>
				    <div class='controls center'>
				        <a href='#' title='Faenas Asociadas' class='tip' onClick=\"sub_faenas('".trim($des_cliente)."', ".trim($row['id']).");\"><span class='icon12 brocco-icon-play'></span></a>
				    </div>
				</td>
				</tr>";
			}
			mysqli_free_result($result);
			if ($contador==0) {
				$script = $script . "<tr><td colspan=3 style='color: red;'>&nbsp;NO EXISTEN ITEMS REGISTRADOS.</td></tr>";
			}
			$script = $script . "</tbody></table></div>";
		} 
	}
	echo $script;
?>
<?php
	echo "<ul>\n";
	if ($menu=="CONSULTA") {
		echo "<li><a href=\"consulta_guia.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 entypo-icon-info-circle\"></span>Consulta Guia</a></li>\n";
	} else {
		echo "<li><a href=\"consulta_guia.php?oper=0&status=0\"><span class=\"icon16 entypo-icon-info-circle\"></span>Consulta Guia</a></li>\n";
	}
	echo "<li>\n";
	if ($menu=="IANSA") {
		echo "<a href=\"#\" class=\"actual\"><span class=\"icon16 brocco-icon-bookmark-2\"></span>Faena IANSA</a>\n";
	} else {
		echo "<a href=\"#\"><span class=\"icon16 brocco-icon-bookmark-2\"></span>Faena IANSA</a>\n";
	}
	echo "<ul class=\"sub\">\n";
		if ($opcion=="ASIGNACIONES") {
			echo "<li><a href=\"carga_iansa.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 brocco-icon-calendar\"></span>Carga Asignaciones</a></li>\n";
		} else {
			echo "<li><a href=\"carga_iansa.php?oper=0&status=0\"><span class=\"icon16 brocco-icon-calendar\"></span>Carga Asignaciones</a></li>\n";
		}	
		if ($opcion=="GUIAS") {
			echo "<li><a href=\"carga_guias.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 silk-icon-checklist\"></span>Carga de Guias</a></li>\n";
		} else {
			echo "<li><a href=\"carga_guias.php?oper=0&status=0\"><span class=\"icon16 silk-icon-checklist\"></span>Carga de Guias</a></li>\n";
		}	
		if ($opcion=="LISTADO") {
			echo "<li><a href=\"listado_iansa.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 entypo-icon-list\"></span>Listado Asignaciones</a></li>\n";
		} else {
			echo "<li><a href=\"listado_iansa.php?oper=0&status=0\"><span class=\"icon16 entypo-icon-list\"></span>Listado Asignaciones</a></li>\n";
		}	
		if ($opcion=="RENDICION1") {
			echo "<li><a href=\"rendicion_interna.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 entypo-icon-download\"></span>Rendici&oacute;n Interna</a></li>\n";
		} else {
			echo "<li><a href=\"rendicion_interna.php?oper=0&status=0\"><span class=\"icon16 entypo-icon-download\"></span>Rendici&oacute;n Interna</a></li>\n";
		}	
		if ($opcion=="RENDICION2") {
			echo "<li><a href=\"rendicion_cliente.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 entypo-icon-upload\"></span>Rendici&oacute;n Cliente</a></li>\n";
		} else {
			echo "<li><a href=\"rendicion_cliente.php?oper=0&status=0\"><span class=\"icon16 entypo-icon-upload\"></span>Rendici&oacute;n Cliente</a></li>\n";
		}	
		if ($opcion=="FOLIOS") {
			echo "<li><a href=\"listado_folios.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 entypo-icon-list\"></span>Listado Folios</a></li>\n";
		} else {
			echo "<li><a href=\"listado_folios.php?oper=0&status=0\"><span class=\"icon16 entypo-icon-list\"></span>Listado Folios</a></li>\n";
		}	
		if ($opcion=="VOUCHERS") {
			echo "<li><a href=\"carga_vouchers.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 icomoon-icon-vimeo-2\"></span>Carga de Vouchers</a></li>\n";
		} else {
			echo "<li><a href=\"carga_vouchers.php?oper=0&status=0\"><span class=\"icon16 icomoon-icon-vimeo-2\"></span>Carga de Vouchers</a></li>\n";
		}	
		if ($opcion=="ARCHIVAR") {
			echo "<li><a href=\"archivar_folios.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 entypo-icon-locked\"></span>Archivar Folios</a></li>\n";
		} else {
			echo "<li><a href=\"archivar_folios.php?oper=0&status=0\"><span class=\"icon16 entypo-icon-locked\"></span>Archivar Folios</a></li>\n";
		}	
		if ($opcion=="SALIDA") {
			echo "<li><a href=\"rep_salida_iansa.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 icomoon-icon-file-excel\"></span>Salida Excel</a></li>\n";
		} else {
			echo "<li><a href=\"rep_salida_iansa.php?oper=0&status=0\"><span class=\"icon16 icomoon-icon-file-excel\"></span>Salida Excel</a></li>\n";
		}	
		echo "</ul>\n";
	echo "</li>\n";
	echo "<li>\n";
	if ($menu=="OTRAS") {
		echo "<a href=\"#\" class=\"actual\"><span class=\"icon16 brocco-icon-bookmark-2\"></span>Otras Faenas</a>\n";
	} else {
		echo "<a href=\"#\"><span class=\"icon16 brocco-icon-bookmark-2\"></span>Otras Faenas</a>\n";
	}
	echo "<ul class=\"sub\">\n";
		if ($opcion=="ASIGNACIONES") {
			echo "<li><a href=\"ingreso.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 brocco-icon-keyboard\"></span>Ingreso Asignaciones</a></li>\n";
		} else {
			echo "<li><a href=\"ingreso.php?oper=0&status=0\"><span class=\"icon16 brocco-icon-keyboard\"></span>Ingreso Asignaciones</a></li>\n";
		}	
		if ($opcion=="LOGISCLICK") {
			echo "<li><a href=\"rep_salida_logis.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 icomoon-icon-file-excel\"></span>Archivo LogisClick</a></li>\n";
		} else {
			echo "<li><a href=\"rep_salida_logis.php?oper=0&status=0\"><span class=\"icon16 icomoon-icon-file-excel\"></span>Archivo LogisClick</a></li>\n";
		}	
		if ($opcion=="RECEPCION") {
			echo "<li><a href=\"recepcion_doc.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 entypo-icon-download\"></span>Recepci&oacute;n Documentos</a></li>\n";
		} else {
			echo "<li><a href=\"recepcion_doc.php?oper=0&status=0\"><span class=\"icon16 entypo-icon-download\"></span>Recepci&oacute;n Documentos</a></li>\n";
		}	
		if ($opcion=="RENDICION") {
			echo "<li><a href=\"rendicion_documentos.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 entypo-icon-upload\"></span>Rendici&oacute;n Documentos</a></li>\n";
		} else {
			echo "<li><a href=\"rendicion_documentos.php?oper=0&status=0\"><span class=\"icon16 entypo-icon-upload\"></span>Rendici&oacute;n Documentos</a></li>\n";
		}	
		if ($opcion=="FOLIOS") {
			echo "<li><a href=\"listado_folios_otras.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 entypo-icon-list\"></span>Listado Folios</a></li>\n";
		} else {
			echo "<li><a href=\"listado_folios_otras.php?oper=0&status=0\"><span class=\"icon16 entypo-icon-list\"></span>Listado Folios</a></li>\n";
		}	
		if ($opcion=="ARCHIVAR") {
			echo "<li><a href=\"archivar_folios_otras.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 entypo-icon-locked\"></span>Archivar Folios</a></li>\n";
		} else {
			echo "<li><a href=\"archivar_folios_otras.php?oper=0&status=0\"><span class=\"icon16 entypo-icon-locked\"></span>Archivar Folios</a></li>\n";
		}	
		if ($opcion=="SALIDA") {
			echo "<li><a href=\"rep_salida_otras.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 icomoon-icon-file-excel\"></span>Salida Excel</a></li>\n";
		} else {
			echo "<li><a href=\"rep_salida_otras.php?oper=0&status=0\"><span class=\"icon16 icomoon-icon-file-excel\"></span>Salida Excel</a></li>\n";
		}	
		echo "</ul>\n";
	echo "</li>\n";
	echo "<li>\n";
	if ($menu=="MANTENEDORES") {
		echo "<a href=\"#\" class=\"actual\"><span class=\"icon16 icomoon-icon-briefcase-2\"></span>Mantenedores</a>\n";
	} else {
		echo "<a href=\"#\"><span class=\"icon16 icomoon-icon-briefcase-2\"></span>Mantenedores</a>\n";
	}
	echo "<ul class=\"sub\">\n";
		if ($opcion=="CLIENTES") {
			echo "<li><a href=\"clientes.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 brocco-icon-bookmark\"></span>Clientes</a></li>\n";
		} else {
			echo "<li><a href=\"clientes.php?oper=0&status=0\"><span class=\"icon16 brocco-icon-bookmark\"></span>Clientes</a></li>\n";
		}	
		if ($opcion=="FAENAS") {
			echo "<li><a href=\"faenas.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 brocco-icon-location\"></span>Faenas</a></li>\n";
		} else {
			echo "<li><a href=\"faenas.php?oper=0&status=0\"><span class=\"icon16 brocco-icon-location\"></span>Faenas</a></li>\n";
		}	
		if ($opcion=="PATENTES") {
			echo "<li><a href=\"patentes.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 icomoon-icon-bus\"></span>Patentes</a></li>\n";
		} else {
			echo "<li><a href=\"patentes.php?oper=0&status=0\"><span class=\"icon16 icomoon-icon-bus\"></span>Patentes</a></li>\n";
		}	
		if ($opcion=="CHOFERES") {
			echo "<li><a href=\"choferes.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 icomoon-icon-user-3\"></span>Choferes</a></li>\n";
		} else {
			echo "<li><a href=\"choferes.php?oper=0&status=0\"><span class=\"icon16 icomoon-icon-user-3\"></span>Choferes</a></li>\n";
		}	
		if ($opcion=="COMUNAS") {
			echo "<li><a href=\"comunas.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 entypo-icon-map\"></span>Comunas</a></li>\n";
		} else {
			echo "<li><a href=\"comunas.php?oper=0&status=0\"><span class=\"icon16 entypo-icon-map\"></span>Comunas</a></li>\n";
		}	
		if ($opcion=="USUARIOS") {
			echo "<li><a href=\"usuarios.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 entypo-icon-users\"></span>Usuarios</a></li>\n";
		} else {
			echo "<li><a href=\"usuarios.php?oper=0&status=0\"><span class=\"icon16 entypo-icon-users\"></span>Usuarios</a></li>\n";
		}	
		echo "</ul>\n";
	echo "</li>\n";
	/*
	echo "<li>\n";
	if ($menu=="PARAMETROS") {
		echo "<a href=\"#\" class=\"actual\"><span class=\"icon16 brocco-icon-cog\"></span>Par&aacute;metros</a>\n";
	} else {
		echo "<a href=\"#\"><span class=\"icon16 brocco-icon-cog\"></span>Par&aacute;metros</a>\n";
	}
	echo "<ul class=\"sub\">\n";
		if ($opcion=="COMUNAS") {
			echo "<li><a href=\"comunas.php?oper=0&status=0\" class=\"actual\"><span class=\"icon16 brocco-icon-location\"></span>Comunas</a></li>\n";
		} else {
			echo "<li><a href=\"comunas.php?oper=0&status=0\"><span class=\"icon16 brocco-icon-location\"></span>Comunas</a></li>\n";
		}	
		if ($opcion=="GENERAL") {
			echo "<li><a href=\"#0\" class=\"actual\" onClick=\"en_proceso();\"><span class=\"icon16 silk-icon-popout\"></span>Indices & Valores</a></li>\n";
		} else {
			echo "<li><a href=\"#\" onClick=\"en_proceso();\"><span class=\"icon16 silk-icon-popout\"></span>Indices & Valores</a></li>\n";
		}	
		echo "</ul>\n";
	echo "</li>\n";
	if ($menu=="CONSULTAS") {
		echo "<li><a href=\"#\" class=\"actual\" onClick=\"en_proceso();\"><span class=\"icon16 brocco-icon-monitor\"></span>Consultas</a></li>\n";
	} else {
		echo "<li><a href=\"#\" onClick=\"en_proceso();\"><span class=\"icon16 brocco-icon-monitor\"></span>Consultas</a></li>\n";
	}
	if ($menu=="REPORTES") {
		echo "<li><a href=\"#\" class=\"actual\" onClick=\"en_proceso();\"><span class=\"icon16 entypo-icon-printer\"></span>Panel Reportes</a></li>\n";
	} else {
		echo "<li><a href=\"#\" onClick=\"en_proceso();\"><span class=\"icon16 entypo-icon-printer\"></span>Panel Reportes</a></li>\n";
	}
	*/
	echo "</ul>\n";
?>

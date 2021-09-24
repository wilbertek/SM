<?php
include_once("webservice-class.php");

// Debug input by writing to a log
//$body = @file_get_contents('php://input');
//$handle=fopen('log.txt','a');
//fwrite($handle,$body,strlen($body));
//fclose($handle);
// Webservice trigger
	/*$json ='{"metodo":"addReport","reporte": {"idUsuario": "","categoria": "1","comentario": "Arbol caido","ubicacion": {"calle": "16","numero": "100","cruzamientos": "17 y 19","colonia": "San miguel","referencia": "casa verde","coordenadas": "5465446"},"evidencias": []}}';

$ws = new WebserviceBoxito();
	$ws->solicitud(stripslashes($json));
*/
if(isset($_GET['solicitud']) && $_GET['solicitud']!='')
{
	
	//echo json_encode($da);
	$ws = new WebserviceBoxito();
	$ws->solicitud(stripslashes($_GET['solicitud']));
}
else
{
		$response = array('metodo'=>'','status'=>'error','mensaje'=>'Falta el parametro solicitud');
		echo json_encode($da);
}




?>


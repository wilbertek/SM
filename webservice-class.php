<?php
class WebserviceBoxito {

	
    // Atributos
    var $error = "";
    var $metodo = "";
    var $productos_actualizados = "";
	
    
    // Constructor
    function __construct() {}
    
    // Métodos
    // solicitud: recibe un parámetro y detona el metodo solicitado

	
    function solicitud($json_string){
		include_once('conexion.php');
		  $json = $this->json_validate($json_string);
        if($json == NULL){
			$this->error.='Error: JSON invalido';
		}
		else {
		    if(isset($json->metodo)) {
		        $this->metodo = $json->metodo;
		        switch($json->metodo) {
                   
				case 'Noticias':
					$Noti = array();
					$consulta="SELECT * from Noticias ";
					$resultado = mysqli_query( $con, $consulta ) or die ( "Error: 001"); 
					while ($columna = mysqli_fetch_array( $resultado ))
					{
						$Noti[]= $columna["Title"];
					}
					
					$response=$Noti;
				
				break;
		
				case 'addReport':
						
						$ok = $this->add_report($json);						
						$response = array('metodo' => $this->metodo,'status' => $ok);			

				break;
						
				case 'addUser':
						
						$ok = $this->add_user($json);
						$response = array('metodo' => $this->metodo,'status' => $ok);			
				break;
				
				case 'getReports':
						
						echo '{"metodo":"getReports", "status": "successfully","Reportes":[';
						$ok = $this->get_rep($json);
						echo ']}';
						die();
				
				break;
				
				default:
				$this->error = 'Este metodo no es valido';
		        
				}
		    }
		    else {
				$this->error = 'Método no definido';
			}
		}
		// print response
		if($this->error != ''){
			$response = array('metodo' => $this->metodo,'status' => 'error','mensaje' => $this->error);
		}
		echo json_encode($response);
    }
    
  
    function add_report($json) 
	{
		
		$val="";
		if(isset($json->reporte))
			{
				
				$con = mysqli_connect('localhost', 'admini', '5Chwh83~','admin_K');
				if (!$con) 
				{
				  die("Connection failed: " . mysqli_connect_error());
				}

				$sql = "INSERT INTO Reportes (idUsuario,categoria,comentario,calle,numero,cruzamientos,colonia,referencia,coordenadas,evidencias,fecha_inicio,status) values (".$json->reporte->idUsuario.",".$json->reporte->categoria.",'".$json->reporte->comentario."','".$json->reporte->ubicacion->calle."','".$json->reporte->ubicacion->numero."','".$json->reporte->ubicacion->cruzamientos."','".$json->reporte->ubicacion->colonia."','".$json->reporte->ubicacion->referencia."','".$json->reporte->ubicacion->coordenadas."','','".date("Y-m-d")."', '1')";

				if (mysqli_query($con, $sql)) {
				  $val = "successfully";
				} else {
				  $val = "Error: " . $sql . " " . mysqli_error($con);
				}
				
			
			}
		
		return $val;
		    
    }
	
	
  function add_user($json) 
	{
		
		$val="";
		if(isset($json->usuario))
			{
				
				$con = mysqli_connect('localhost', 'admini', '5Chwh83~','admin_K');
				if (!$con) 
				{
				  die("Connection failed: " . mysqli_connect_error());
				}

				$sql = "INSERT INTO Usuarios (Nombre,a_paterno,a_materno,f_nacimiento,calle,numero,cruzamientos,colonia,correo,telefono,password) values ('".$json->usuario->Nombre."','".$json->usuario->a_paterno."','".$json->usuario->a_materno."','".$json->usuario->f_nacimiento."','".$json->usuario->calle."','".$json->usuario->numero."','".$json->usuario->cruzamientos."','".$json->usuario->colonia."','".$json->usuario->correo."','".$json->usuario->telefono."','".$json->usuario->password."')";
				
				if (mysqli_query($con, $sql)) {
				  $val = "successfully";
				} else {
				  $val = "Error: " . $sql . " " . mysqli_error($con);
				}
				
			}
		
		return $val;
		    
    }

	function get_rep($json)
	{
		$myObj = new stdClass();
		if(isset($json->usuario))
		{
			$con = mysqli_connect('localhost', 'admini', '5Chwh83~','admin_K');
				if (!$con) 
				{
				  die("Connection failed: " . mysqli_connect_error());
				}
			
			$consulta="SELECT * from Reportes where idUsuario =  ".$json->usuario->idUsuario."";
			$resultado = mysqli_query( $con, $consulta ) or die ( "Error: 001"); 
			$row_cnt = $resultado->num_rows;
			$c = 0;
			while ($columna = mysqli_fetch_array( $resultado ))
				{
					$c++;
					echo '{"categoria":"' .$columna["categoria"]. '","comentario":"' .$columna["comentario"].'","calle": "'.$columna["calle"].'","numero": "'.$columna["cruzamientos"].'", "colonia": "'.$columna["colonia"].'", "referencia": "'.$columna["referencia"].'","coordenadas": "'.$columna["coordenadas"].'", "evidencias": "'.$columna["evidencias"].'", "fecha_inicio": "'.$columna['fecha_inicio'].'", "status": "'.$columna["status"].'" }';
					
					if($c < $row_cnt)
					{
						echo ',';
					}
				}
		}
		
	}
   
    // Funciones auxiliares
    // json_validate: recibe una cadena json y valida que no contenga ningún error
    function json_validate($string) {
		// decode the JSON data
		$result = json_decode($string);
		// switch and check possible JSON errors
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				$this->error = ''; // JSON is valid // No error has occurred
				break;
			case JSON_ERROR_DEPTH:
				$this->error = 'The maximum stack depth has been exceeded.';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$this->error = 'Invalid or malformed JSON.';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$this->error = 'Control character error, possibly incorrectly encoded.';
				break;
			case JSON_ERROR_SYNTAX:
				$error = 'Syntax error, malformed JSON.';
				break;
			// PHP >= 5.3.3
			case JSON_ERROR_UTF8:
				$this->error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
				break;
			// PHP >= 5.5.0
			case JSON_ERROR_RECURSION:
				$this->error = 'One or more recursive references in the value to be encoded.';
				break;
			// PHP >= 5.5.0
			case JSON_ERROR_INF_OR_NAN:
				$this->error = 'One or more NAN or INF values in the value to be encoded.';
				break;
			case JSON_ERROR_UNSUPPORTED_TYPE:
				$this->error = 'A value of a type that cannot be encoded was given.';
				break;
			default:
				$this->error = 'Unknown JSON error occured.';
				break;
		}
		return $result;
	}
	// curlpost: función para enviar una cadena JSON a un URL
	function curlpost($url,$post_string){
		$headers = array(
		"Host: marconi.dnsalias.com",
		"Content-type: application/x-www-form-urlencoded",
		"Cache-Control: no-cache",
		"Pragma: no-cache",
		"Content-length: ".strlen($post_string) );
		// PHP cURL  for https connection with auth
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// En caso de que necesiten usuario y contraseña
		//curl_setopt($ch, CURLOPT_USERPWD, $User.":".$Password); // username and password
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// converting
		$response = curl_exec($ch); 
		curl_close($ch);
		// para debug
		//$response='La url es '.$url.' y el post es '.$post_string;		
		return $response;
	}
}
?>
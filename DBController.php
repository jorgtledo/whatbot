<?php
public class DBController
{
private $conexion;
	
 public function __construct() {
$conexion=conectarDB();
mysqli_set_charset($conexion, "utf8");
}


private  function conectarDB(){
require_once 'config/config_db.php';	
   $conexion = mysqli_connect($_s_db_host, $_s_db_user, $_s_db_pw, $_s_db_dbn);
    if(!$conexion){
        die ('errordb');
    }   
    return $conexion;
}


private function desconectarDB($conexion){
 
    $close = mysqli_close($conexion);   
    return $close;
}

}

public function set_encuesta($descripccion, $id_encuesta){
$sql='INSERT INTO encuestas(encuestas_id,descripcion) values("$id_encuesta","$descripccion");';
}
?>
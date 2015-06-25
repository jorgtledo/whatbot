<?php

class Pegunta
{
    private $pregunta;
    private $respuestas=array();
 
    public function __construct($s_pregunta, $s_respuestas) {
        $this->pregunta = $s_pregunta;
        $this->respuestas = $s_respuestas;
    }
	
	public function get_pregunta()
	{
	return $this->pregunta;
	}
	
	public function get_respuestas()
	{
	return $this->respuestas;
	}
}

class Encuestaloader {
private $encuesta;
private $enc;

public function __construct($encuestan)
{
include_once 'encuesta.php';
$encuesta = new Encuesta();
$s_jsonencuesta = file_get_contents("encuestas/".$encuestan.".json") or die("La encuesta no existe!\nCompruebe el archivo");
$a_jsonencuesta = json_decode($s_jsonencuesta,true);
$i_index=0;
foreach($a_jsonencuesta['Preguntas'] as $pregunta) {
	$respuestas=array(null);
	foreach ($pregunta['respuestas'] as $resp)
	{
	array_push($respuestas,$resp);
	}
	$encuesta->addP(new Pegunta($pregunta['Pregunta'], $respuestas),$i_index);
	$i_index++;
}
$this->enc=$encuesta;	
}

public function get_encuesta()
{
return $this->enc;
}
}
?>
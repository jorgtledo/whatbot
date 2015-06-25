<?php

class Encuesta {

private $a_preguntas;

public function __construct()
{
$a_preguntas=array(null);
}

public function addP($obj, $key = null) {
	if ($key == null) {
        $this->a_preguntas[] = $obj;
    }
    else {
        if (isset($this->a_preguntas[$key])) {
            throw new KeyHasUseException("Key $key en uso.");
        }
        else {
            $this->a_preguntas[$key] = $obj;
        }
    }
    
}
 
public function delP($key) {
if (isset($this->a_preguntas[$key])) {
        unset($this->a_preguntas[$key]);
    }
    else {
        throw new KeyInvalidException("key invalida -> $key.");
    }
	
}
 
public function getP($key) {
if (isset($this->a_preguntas[$key])) {
        return $this->a_preguntas[$key];
    }
    else {
        throw new KeyInvalidException("key invalida -> $key.");
    }	
}

public function keys() {
    return array_keys($this->a_preguntas);
}

public function tam() {
    return count($this->a_preguntas);
}

public function existeKey($key) {
    return isset($this->a_preguntas[$key]);
}

}
?>
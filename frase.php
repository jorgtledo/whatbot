<?php
class Frase {

public function __construct()
{
}

public function get_stiempo($orden)
{
(int)$orden;
include 'strings.php';	
$i_hora = date('h');
$s_horat = date('A');
$s_saludo="";
if ($s_horat=="AM" && $i_hora<=12)
{
if ($orden==0)
$s_saludo=$s_dia;
if ($orden==1)
$s_saludo=$s_diaf;	
}
if ($s_horat=="PM" && $i_hora<=8)
{
if ($orden==0)
$s_saludo=$s_tarde;
if ($orden==1)
$s_saludo=$s_tardef;	
}
if (($s_horat=="PM" && ($i_hora<=12 && $i_hora>=8)) or ($s_horat=="AM" && ($i_hora>=1 &&  $i_hora<=5)))
{
if ($orden==0)
$s_saludo=$s_noche;
if ($orden==1)
$s_saludo=$s_nochef;	
}
return $s_saludo;
}
	
}
?>
	<?php
	error_reporting(1);
	require_once 'lib/WhatsAPI/whatsprot.class.php';
	//require_once 'node.php';
	$s_uso="Usar--> main.php <numero> <encuesta>";
	$s_phone = '549';
	$s_phone = $s_phone . $_SERVER['argv'][1];
	$s_encuestan = $_SERVER['argv'][2];
	if (is_numeric($s_phone) && strlen($s_phone) == 13 && !empty($s_encuestan))
	{
	start($s_phone,$s_encuestan);	
	}
	else
	{
	die ($s_uso);	
	}

	function start ($s_num,$s_encuestan)
	{
	require 'config/config_w.php';
	require 'strings.php';
	require 'frase.php';
	require_once'encuestaloader.php';
	require_once 'encuesta.php';
	$encuestaloader= new Encuestaloader($s_encuestan);
	$encuesta = $encuestaloader->get_encuesta();
	$s_saludo= new Frase();
	echo "Enviar a--> $s_num\n";
	echo "Cantidad de preguntas: ".$encuesta->tam()."\n\n";
	$wa = new WhatsProt($s_sender, $s_nickname, false);
			$wa->connect();
			$wa->loginWithPassword($s_password);
			echo "Presentando...\n";
			envio($wa,$s_saludo->get_stiempo(0).$s_msg_hi,$s_num);
			$i_contfail=0;
			do{
			$respa = getresp($wa,$s_num);
			$resp= $respa[0];
			$resp= trim($resp);
			$nombre= $respa[1];
			if (strcasecmp($resp,"Si")==0 or strcasecmp($resp,"Sí")==0)
			{
			echo "Acepto!!\n";
			envio($wa,$s_msg_start,$s_num);
			encuesta_start($wa,$s_num,$encuesta,$nombre);
			}
			elseif (strcasecmp($resp,"No")==0)
			{
			echo "No Acepto.\nDespidiendo...\n";
			envio($wa,$nombre.' '.$s_msg_sory.$s_saludo->get_stiempo(1),$s_num);
			exit(0);		
			}
			else
			{
			echo "Reenvio...\n";
			envio($wa,$nombre.' '.$s_msg_retry,$s_num);
			}
			$i_contfail++;
			}while($i_contfail<3);
			envio($wa,$nombre.' '.$s_msg_sory.$s_saludo->get_stiempo(1),$s_num);
			
	}

	function encuesta_start($wa,$s_num,$encuesta,$nombre)
	{
	require 'strings.php';
	require 'DBController.php';
	$s_saludo= new Frase();
	$tam_e=$encuesta->tam();
	if ($tam_e==1)
	{
	envio($wa,$nombre." responde la siguiente pregunta",$s_num);
	}
	else
	{
	envio($wa,$nombre." responde las siguientes ".$tam_e." preguntas",$s_num);	
	}
	$condb = new DBController();
	for ($i=1;$i<=$tam_e;$i++)
	{
	echo "Enviando pregunta $i de ".$tam_e."\n";
	$a_respuestas= $encuesta->getP($i-1)->get_respuestas();
	$s_enc_pregunta = $encuesta->getP($i-1)->get_pregunta()."\n";
	$fix=0;
	foreach ($a_respuestas as $s_respuestas) {
	if($fix!=0)
	{
	$s_enc_pregunta=$s_enc_pregunta."- ".$s_respuestas."\n";
	}
	$fix++;
	}
	envio($wa,$s_enc_pregunta,$s_num);
	$respa = getresp($wa,$s_num);
	$resp= trim($respa[0]);
	echo "Respuesta -->$resp\n";
	while(!checkresp($resp,$a_respuestas))
	{
	envio($wa,$s_enc_pregunta,$s_num);
	$respa = getresp($wa,$s_num);
	$resp= trim($respa[0]);	
	}
	/*
	///////////ALMACENAR RESULTADOS////////////////////////
	*/
	condb->conectarDB();
		}
	envio($wa,$nombre.' '.$s_msg_bye.$s_saludo->get_stiempo(1),$s_num);
	exit(0);	
	}

	function checkresp($resp,$a_respuestas)
	{
	$check=false;
	foreach ($a_respuestas as $r) {
	if (strcasecmp($resp,$r)==0)
	{
	$check=true;	
	}
	}	
	return $check;
	}

	function envio($wa,$text,$s_num)
	{
	$wa->sendMessage($s_num , $text);
	$wa->pollMessage();
	$wa->getMessages();	
	}

	function getresp ($wa,$s_num)
	{
			echo "Esperando Respuesta...\n";
			$respuesta=false;
			while (!$respuesta){
			$wa->pollMessage();
			$msgs = $wa->getMessages();
			foreach($msgs as $message)
			{
				$from = $message->getAttribute("from");
				$id = $message->getAttribute("id");
				$nick = $message->getAttribute("notify");
				if ($from=="$s_num@s.whatsapp.net")
				{
				print "$nick Respondio!\n";
				$rnode= $message->getChild('body');			
				//$p1 = strpos($rnode, '>');
				//$p2 = strpos($rnode, '</enc>');			
				//$rebody= substr($rnode, -$p1, $p2);
				$rebody = array($rnode->getData(),$nick);
				$respuesta=true;
				}
			}
			}
	return $rebody;		
	}	
		
	?>
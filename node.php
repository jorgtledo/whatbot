<?php

class ProcessNode
{
protected $wp = false;
protected $target = false;
public function __construct($wp, $target)
{
$this->wp = $wp;
$this->target = $target;
}
/**
* @param ProtocolNode $node
*/
public function process($node)
{
// Example of process function, you have to guess a number (psss it's 5)
// If you guess it right you get a gift
$text = $node->getChild('body');
$text = $text->getData();
if ($text && ($text == "5" || trim($text) == "5")) {
$this->wp->sendMessageImage($this->target, "https://s3.amazonaws.com/f.cl.ly/items/2F3U0A1K2o051q1q1e1G/baby-nailed-it.jpg");
$this->wp->sendMessage($this->target, "Congratulations you guessed the right number!");
}
elseif (ctype_digit($text)) {
if( (int)$text != "5")
$this->wp->sendMessage($this->target, "I'm sorry, try again!");
}
$text = $node->getChild('body');
$text = $text->getData();
$notify = $node->getAttribute("notify");
echo "\n- ".$notify.": ".$text." ".date('H:i')."\n";
}
}
?>
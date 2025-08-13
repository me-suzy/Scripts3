<?php
/*
Author: Carl Heaton
Date: 2001-05-11 02:58:18
Subject: RE: file sending mail
http://www.phpbuilder.com/forum/archives/2/2001/5/2/135825
*/
class mime_mail 
{ 
var $parts; 
var $to; 
var $from; 
var $headers; 
var $subject; 
var $body; 

function mime_mail() 
{ 
$this->parts = array(); 
$this->to = ""; 
$this->from = ""; 
$this->subject = ""; 
$this->body = ""; 
$this->headers = ""; 
} 


function add_attachment($message, $name = "", $ctype = "application/octet-stream") 
{ 
$this->parts[] = array ( 
"ctype" => $ctype, 
"message" => $message, 
"encode" => $encode, 
"name" => $name 
); 
} 

/* 
* void build_message(array part= 
* Build message parts of an multipart mail 
*/ 
function build_message($part) 
{ 
$message = $part["message"]; 
$message = chunk_split(base64_encode($message)); 
$encoding = "base64"; 
return "Content-Type: ".$part["ctype"]. 
($part["name"]?"; name = \"".$part["name"]."\"" : ""). 
"\nContent-Transfer-Encoding: $encoding\n\n$message\n"; 
} 


function build_multipart() 
{ 
$boundary = "b".md5(uniqid(time())); 
$multipart = "Content-Type: multipart/mixed; boundary = $boundary\n\nThis is a MIME encoded message.\n\n--$boundary"; 

for($i = sizeof($this->parts)-1; $i >= 0; $i--) 
{ 
$multipart .= "\n".$this->build_message($this->parts[$i])."--$boundary"; 
} 
return $multipart.= "--\n"; 
} 


function send() 
{ 
$mime = ""; 
if (!empty($this->from)) 
$mime .= "From: ".$this->from."\n"; 
if (!empty($this->headers)) 
$mime .= $this->headers."\n"; 

if (!empty($this->body)) 
$this->add_attachment($this->body, "", "text/plain"); 
$mime .= "MIME-Version: 1.0\n".$this->build_multipart(); 
mail($this->to, $this->subject, "", $mime); 
} 
}; // end of class
?>
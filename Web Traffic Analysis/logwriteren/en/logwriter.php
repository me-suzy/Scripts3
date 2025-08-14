<?php

//settings

$logwriter_logformat = "combined"; // log format,combined or common
$logwriter_logdir = "C:/apache/htdocs/logwriter/"; // physical path where your log file located
$logwriter_logfilename = "access.log"; // your log file's filename
$logwriter_timezone = "+0800"; // your server's time zone. +0800 means GMT+8



function logwriter_writelog($logstring){
    
    global $logwriter_logdir,$logwriter_logfilename;
    $fullpathfilename = $logwriter_logdir.$logwriter_logfilename;
    
    if (!is_file($fullpathfilename)) {
    	print "Log file doesn't exist or file is corrupt.";
    	return;
    }
    
    if (!is_writeable($fullpathfilename)) {
    	print "Log file is not writable,please change its permission.";
    	return;
    }
    
    if($fp = @fopen($fullpathfilename, "a")) {
	       flock($fp, 2);
               fputs($fp, $logstring);               
               fclose($fp);               
     }
}

function logwriter_handlevar($varname,$defaultvalue) {
    $tempvar = getenv($varname);
    if(!empty($tempvar)) {
    return $tempvar;
    } else {
    return $defaultvalue;
    }
}

if (!empty($REMOTE_HOST)) {
$logwriter_remote_vistor = $REMOTE_HOST;
}else{
$logwriter_remote_vistor = logwriter_handlevar("REMOTE_ADDR","-");
}

$logwriter_remote_ident = logwriter_handlevar("REMOTE_IDENT","-");
$logwriter_remote_user = logwriter_handlevar("REMOTE_USER","-");
$logwriter_date = date("d/M/Y:H:i:s");

$logwriter_server_port = logwriter_handlevar("SERVER_PORT","80");
if($logwriter_server_port!="80") {
$logwriter_server_port =  ":".$logwriter_server_port;
}else{
$logwriter_server_port =  "";
}

$logwriter_request_method = logwriter_handlevar("REQUEST_METHOD","GET");
$logwriter_request_uri = logwriter_handlevar("REQUEST_URI","");
$logwriter_server_protocol = logwriter_handlevar("SERVER_PROTOCOL","HTTP/1.1");

if ($logwriter_logformat=="common") {
$logwriter_logstring = "$logwriter_remote_vistor $logwriter_remote_ident $logwriter_remote_user [$logwriter_date $logwriter_timezone] \"$logwriter_request_method $logwriter_request_uri $logwriter_server_protocol\" 200 -\n";
}else{

$logwriter_http_referer = logwriter_handlevar("HTTP_REFERER","-");
$logwriter_http_user_agent = logwriter_handlevar("HTTP_USER_AGENT","");

$logwriter_logstring = "$logwriter_remote_vistor $logwriter_remote_ident $logwriter_remote_user [$logwriter_date $logwriter_timezone] \"$logwriter_request_method $logwriter_request_uri $logwriter_server_protocol\" 200 - \"$logwriter_http_referer\" \"$logwriter_http_user_agent\"\n";

}

logwriter_writelog($logwriter_logstring);

?>
<?

$config_default=array(
  "mysqlhost" => "localhost", 
  "mysqldb" => "stickybeak", 
  "mysqltable" => "stickybeak_1_0",    
  "mysqluser" => "", 
  "mysqlpass" => "", 
  
  "usecookie" => true, 
  "cookiename" => "ID",  
  "cookienumchars" => 32, 
  "cookiealphabet" => "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789",  
  "p3pURL" => false,
  
 
  "pixel" => false, 
  "colour" => false,

  "image" => false

);



class stickybeak{

	var $usecookie;
	var $useimage;

	function stickybeak($config=null){
		global $config_default;
		$this->config=$config_default;
		if($config){
			foreach ($config as $var=>$val){
				$this->config[$var]=$val;
			} 
		}

		


		$this->init();
		if($this->config[usecookie]){
			$this->cookie();
		}
		if($this->config[pixel]){
			$this->pixel();
		}
 		if($this->config[image]){
			$this->image();
		}   
	}




	function init(){
		$this->time=time();
		$this->mysqltime= $this->timestamp_to_mysql($this->time);
		$this->logday=floor($this->getlogday($this->mysqltime));
		$this->logmonth=floor($this->getlogmonth($this->mysqltime));
		$this->logyear=floor($this->getlogyear($this->mysqltime));
		$this->loghour=floor($this->getloghour($this->mysqltime));
		$this->logminute=floor($this->getlogminute($this->mysqltime));
		$this->logsecond=floor($this->getlogsecond($this->mysqltime));
		$this->remote_host_name = gethostbyaddr($_ENV[REMOTE_ADDR]); 
	}





	function cookie(){
		
		$cookiename=$this->config["cookiename"];
		if(isset($_COOKIE[$cookiename])){
			$this->sessionID=$_COOKIE[$cookiename];
		}else{
			$this->sessionID=$this->get_unique_id();
			$this->newsession=true;
		}
		
		
		if(!isset($_COOKIE[$this->config["cookiename"]])){
			setcookie ($this->config["cookiename"], $this->sessionID ,time()+315360000);
      			if(!$_GET['redirect'] AND ($this->config["image"] or $this->config["pixel"])){
      				header("HTTP/1.0 302 Redirect");
      				$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&redirect=true"; 
      				header("Location: $url");
      				exit;
      			}
		}
	}


	function log($page=false,$identifier=false){

		if ( isset($_GET['h']) && !empty($_GET['h'])) {
			$this->host=base64_decode($_GET['h']);
			$this->uri=base64_decode($_GET['u']);
			$this->document_url = "http://".$this->host.$this->uri;
		}else if (isset($_SERVER['HTTP_REFERER']) and ($this->config[image] or $this->config[pixel])) {
			$this->document_url = $_SERVER['HTTP_REFERER'];
			$urlparts=parse_url($this->document_url);
			$this->host=$urlparts[host];
			$this->uri=$urlparts[path];
		}else{
      			$this->document_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
      			if($_SERVER['QUERY_STRING']){
				$this->document_url.="?".$_SERVER['QUERY_STRING'];
			}
      
			$urlparts=parse_url($this->document_url);
		  

			$this->host=$urlparts[host];
			$this->uri=$urlparts[path];
		//	$this->host=base64_decode($_GET['h']);
		//	$this->uri=base64_decode($_GET['u']);
		}


      		if($page){
			$this->page=$page;
		}else{
  			$this->page=base64_decode($_GET['p']);    
		}
		if($identifier){
			$this->identifier=$identifier;
		}else{
			$this->identifier=base64_decode($_GET['i']);   
		}   
      
		$this->querystring=base64_decode($_GET['q']);

		if ( isset($_GET['r']) && !empty($_GET['r'])) {
			$this->referer =base64_decode($_GET['r']);
		  
		}elseif(!$this->config[pixel] and !$this->config[image]){
			$this->referer =$_SERVER['HTTP_REFERER'];
		}


//		$this->referer   = isset($_GET['r'])   ? base64_decode($_GET['r']) : '';


		$mysqlconnect = mysql_connect($this->config["mysqlhost"], $this->config["mysqluser"], $this->config["mysqlpass"]);
		mysql_select_db($this->config["mysqldb"]);

		$query = "INSERT INTO ".$this->config[mysqltable]." (ID,sessionID,`LOGYEAR`,`LOGMONTH`,`LOGDAY`,`LOGHOUR`,`LOGMINUTE`,`LOGSECOND`,REMOTE_ADDR,HTTP_REFERER,REQUEST_METHOD,unixtime,mysqltime,HTTP_USER_AGENT,REMOTE_PORT,SCRIPT_NAME,HTTP_CONNECTION,HTTP_ACCEPT_LANGUAGE,HTTP_ACCEPT,QUERY_STRING,SERVER_PROTOCOL,GATEWAY_INTERFACE,REMOTE_HOST_NAME,DOCUMENT_URL,HTTP_HOST,REQUEST_URI,page,identifier) VALUES ('','$this->sessionID',$this->logyear,$this->logmonth,$this->logday,$this->loghour,$this->logminute,$this->logsecond,'$_ENV[REMOTE_ADDR]','$this->referer','$_ENV[REQUEST_METHOD]',$this->time,$this->mysqltime,'$_ENV[HTTP_USER_AGENT]','$_ENV[REMOTE_PORT]','$_ENV[SCRIPT_NAME]','$_ENV[HTTP_CONNECTION]','$_ENV[HTTP_ACCEPT_LANGUAGE]','$_ENV[HTTP_ACCEPT]','$this->querystring','$_ENV[SERVER_PROTOCOL]','$_ENV[GATEWAY_INTERFACE]','$this->remote_host_name','$this->document_url','$this->host','$this->uri','$this->page','$this->identifier');";

		$result = mysql_query ($query) 
				or die ();
				
		mysql_close($mysqlconnect);
				
	}




	function pixel(){

		header('Content-type: image/gif');
		if($this->config[p3pURL]){
			header("P3P: policyref=\"".$this->config[p3pURL]."\",  CP=\"NOI CURa ADMa DEVa TAIa OUR BUS IND UNI COM NAV INT\"");
		}
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Expires: Wed, 11 Nov 1998 11:11:11 GMT'); 
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');	
			

		if($this->config[colour]){
			$rgb = str_replace("#", "", $this->config[colour]);  
			$r = hexdec(substr ($rgb, 0,2)); 
			$g = hexdec(substr ($rgb, 2,2)); 
			$b = hexdec(substr ($rgb, 4,2)); 
			printf ("%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c", 
			71,73,70,56,57,97,1,0,1,0,128,0,0,$r,$g,$b,0,0,0,44,0,0,0,0,1,0,1,0,0,2,2,68,1,0,59);  
		}else{ 
			printf ("%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%c%", 
			71,73,70,56,57,97,1,0,1,0,128,255,0,192,192,192,0,0,0,33,249,4,1,0,0,0,0,44,0,0,0,0,1,0,1,0,0,2,2,68,1,0,59);  
		} 
	}

	function image(){
		if($this->config[p3pURL]){
			header("P3P: policyref=\"".$this->config[p3pURL]."\",  CP=\"NOI CURa ADMa DEVa TAIa OUR BUS IND UNI COM NAV INT\"");
		}
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Expires: Wed, 11 Nov 1998 11:11:11 GMT'); 
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');	
		
		if($this->config[imagetype]=="gif"){
			header('Content-type: image/gif');
			$im  = imagecreatefromgif($this->config["image"]); 
			imagegif($im); 
			imagedestroy($im);  
		}
		if($this->config[imagetype]=="jpg"){
			header('Content-type: image/jpg');
			$im     = imagecreatefromjpeg($this->config["image"]); 
			imagejpeg($im); 
			imagedestroy($im); 
		}
		if($this->config[imagetype]=="png"){
			header('Content-type: image/png');
			$im     = imagecreatefrompng($this->config["image"]); 
			imagejpeg($im); 
			imagedestroy($im); 
		}
	}


	function get_unique_id(){ 
		$pool=$this->config["cookiealphabet"]; 
		mt_srand ((double) microtime() * 1000000); 
		$unique_id = ""; 
		for ($index = 0; $index < $this->config["cookienumchars"]; $index++) { 
			$unique_id .= substr($pool, (mt_rand()%(strlen($pool))), 1); 
		}
		return($unique_id); 
	}

	function logtable(){
		$text="";
		$text.="<table border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">\r\n";
		$text.="<tr><td><strong>Variable</strong></td><td><strong>Value</strong></td></tr>\r\n";
//		$text.="<tr><td>ID</td><td>".$this->ID."</td></tr>\r\n";
		$text.="<tr><td>sessionID </td><td>".$this->sessionID."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>LOGYEAR </td><td>".$this->logyear."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>LOGMONTH </td><td>".$this->logmonth."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>LOGDAY </td><td>".$this->logday."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>LOGHOUR </td><td>".$this->loghour."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>LOGMINUTE </td><td>".$this->logminute."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>LOGSECOND </td><td>".$this->logsecond."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>REMOTE_ADDR </td><td>".$_ENV[REMOTE_ADDR]."&nbsp;</td></tr>\r\n";
		if($this->referer){
			$text.="<tr><td>HTTP_REFERER </td><td>".$this->referer."&nbsp;</td></tr>\r\n";
		}else{
			$text.="<tr><td>HTTP_REFERER </td><td>".$_ENV[REFERER]."&nbsp;</td></tr>\r\n";		
		}
		$text.="<tr><td>REQUEST_METHOD </td><td>".$_ENV[REQUEST_METHOD]."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>unixtime </td><td>".$this->time."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>mysqltime </td><td>".$this->mysqltime."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>HTTP_USER_AGENT </td><td>".$_ENV[HTTP_USER_AGENT]."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>REMOTE_PORT </td><td>".$_ENV[REMOTE_PORT]."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>SCRIPT_NAME </td><td>".$_ENV[SCRIPT_NAME]."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>HTTP_CONNECTION </td><td>".$_ENV[HTTP_CONNECTION]."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>HTTP_ACCEPT_LANGUAGE </td><td>".$_ENV[HTTP_ACCEPT_LANGUAGE]."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>HTTP_ACCEPT </td><td>".$_ENV[HTTP_ACCEPT]."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>QUERY_STRING </td><td>".$this->querystring."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>SERVER_PROTOCOL </td><td>".$_ENV[SERVER_PROTOCOL]."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>GATEWAY_INTERFACE </td><td>".$_ENV[GATEWAY_INTERFACE]."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>REMOTE_HOST_NAME </td><td>".$this->remote_host_name."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>DOCUMENT_URL </td><td>".$this->document_url."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>HTTP_HOST </td><td>".$this->host."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>REQUEST_URI </td><td>".$this->uri."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>page </td><td>".$this->page."&nbsp;</td></tr>\r\n";
		$text.="<tr><td>identifier </td><td>".$this->identifier."&nbsp;</td></tr>\r\n";
		$text.="</table>\r\n";

		return $text;
	}



	function timestamp_to_mysql($ts) { 
		$logdate=getdate($ts); 
		$yr=$logdate["year"]; 
		$mo=$logdate["mon"]; 
		$da=$logdate["mday"]; 
		$hr=$logdate["hours"]; 
		$mi=$logdate["minutes"]; 
		$se=$logdate["seconds"]; 
		return sprintf("%04d%02d%02d%02d%02d%02d",$yr,$mo,$da,$hr,$mi,$se); 
	}

	function getlogyear($dt) { 
		$yr=strval(substr($dt,0,4));
		return $yr; 
	} 

	function getlogmonth($dt) { 
		$mo=strval(substr($dt,4,2)); 
		return $mo; 
	} 

	function getlogday($dt) { 
		$da=strval(substr($dt,6,2));
		return $da; 
	} 

	function getloghour($dt) { 
		$hr=strval(substr($dt,8,2)); 
		return $hr; 
	} 

	function getlogminute($dt) { 
		$mi=strval(substr($dt,10,2));
		return $mi; 
	} 

	function getlogsecond($dt) { 
		$se=strval(substr($dt,12,2)); 
		return $se; 
	}


}


?>

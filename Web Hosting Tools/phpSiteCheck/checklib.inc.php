<?php
/*********************************************************************************
 *       Filename: checklib.php
 *       Version 1.0
 *       Copyrights 2004-2005 (c) phpSiteTools.com
 *       Powered by ServiceUptime.com
 *       Last modified: 08.24.2005
 *********************************************************************************/


function pscValidateHost($host) {
    if(preg_match("/^((([0-9]{1,3}\.){3}[0-9]{1,3})|([0-9a-z-.]{0,61})?\.[a-z]{2,4})$/i", $host)) 
        return 1;
    else
        return 0;
}

function pscDoCheck($host, $service, $port='', $timeout=20) {
    $host = strtolower($host);

    switch($service) {
         case 'http': 
            $Request = "HEAD / HTTP/1.0\r\nUser-Agent: phpSiteCheck 1.0. Powered by ServiceUptime.com\r\nHost: $host\r\n\r\n";
            $OkResults = array("200\D+OK", "200\D+Document\s+Follows", "302", "301");
            if(!is_numeric($port)) $port = 80;
            break;

         case 'ftp': 
            $OkResults = array("220");
            $Request = '';
            if(!is_numeric($port)) $port = 21;
            break;

         case 'smtp': 
            $OkResults = array("220");
            $Request = '';
            if(!is_numeric($port)) $port = 25;
            break;

         case 'pop3': 
            $OkResults = array("\\+OK");
            $Request = '';
            if(!is_numeric($port)) $port = 110;
            break;
    }

      list($MSec, $Sec) = explode(" ", microtime());
      $TimeBegin = (double) $MSec + (double) $Sec;

      $Socket = @fsockopen($host, $port, $error_number, $error, $timeout);

      list($MSec, $Sec) = explode(" ", microtime());
      $TimeEnd = (double) $MSec + (double) $Sec;
	  $Time = number_format($TimeEnd - $TimeBegin, 3);
      // Check port

	  if (is_resource($Socket))
      {
             if ($Request != "") { fputs($Socket, $Request); }
             if (!feof($Socket)) { $Response = fgets($Socket, 4096); }
             
             $Result = "Failed";
             $Error  = $Response;

             foreach($OkResults as $exp_result) {
                if (preg_match("/$exp_result/",$Response)) {
                   $Error = "";
                   $Result = "Ok";
                }
             }
         fclose($Socket);
      }
      else 
	  { 
          $Result = "Failed";
		  $Error = ((!$error) ? "Time out" : $error);
	  }

      return array(
            'host'   => $host,
            'service'=> $service,
            'port'   => $port,
            'result' => $Result,
            'time'   => $Time,
            'error'  => $Error
          );

}
?>
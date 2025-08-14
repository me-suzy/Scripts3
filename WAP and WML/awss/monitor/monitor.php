<? 
	######################################################################################
	##							Another Wap Server Status								##
	##									$AWSS	 										##
	##																					##
	##		Version:		1.0															##
	##		Documento:		monitor.php													##												##
	##		Autor:			Manlio Fabio Tapia Trigos (mtapia@acquamexico.com)			##
	##		Creacion:		29/Mayo/2005												##
	##		Ultima Modi:	29/Mayo/2005												##
	##		Nombre:			Manlio Fabio Tapia Trigos (mtapia@acquamexico.com)			##
	##		Licencia:		GNU GENERAL PUBLIC LICENSE 									##
	##																					##
	##																					##		
	######################################################################################

header("Content-type:text/vnd.wap.wml;charset=UTF-8");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s"). " GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache"); 
print "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n
<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">\n
<wml>\n";
?>
<card title="Monitor" newcontext="true">
<p>
<table columns="2"> 
	<tr> 
       <td><b>IP</b></td> 
       <td><b>Status</b></td> 
	</tr> 
<?
$timeout=2;
	$fp = fopen("ip.txt", "r");   
   		while ($linea= fgets($fp,1024))
  	{	
   	$ip  = explode ("|", $linea);
   		   $host = "$ip[0]";
   		   $port = "$ip[1]";
$handle=fsockopen($host, $port, $errno, $errstr, $timeout);
		if (!$handle){
echo "
	<tr> 
       <td><em>$host</em></td> 
       <td>Abajo</td> 
	</tr> ";
     		
		}else {
echo "
	<tr> 
       <td><small>$host</small></td> 
       <td><small>Arriba</small></td> 
	</tr> ";
		}
	}
?> 
</table> 
<br/>
<a href="../index.php">Regresar</a>
</p>
</card>
</wml>

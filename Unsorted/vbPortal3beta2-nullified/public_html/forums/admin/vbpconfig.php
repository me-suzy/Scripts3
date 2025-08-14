<?php

// Upload this script to your forums/admin directory to run


echo "\n<html>\n";
echo "<head>\n";
echo "<title>vbPortal Path Config</title>\n";
echo "</head>\n\n";
		
		
$page = split("/", getenv(SCRIPT_NAME)); 
	$n = count($page)-1; 
	$page = $page[$n]; 
	$page = split("\.", $page, 2); 
	$page = $page[0];
	
	$arr_basename	= explode(".",getenv(SCRIPT_NAME));
	$extension	= strtolower($arr_basename[1]);
	
	$script 	= "$page.$extension";
	
	$directory 	= ereg_replace("/admin/$script",'',$HTTP_SERVER_VARS[PHP_SELF]);
	$base_url 	= "http://".$SERVER_NAME;
	$base_url_forums	= "$base_url$directory";
	
	if( ereg(":\\\\",$PATH_TRANSLATED) )
	{
	// NT
	    $therest= ereg_replace("/",'',$directory);
        $base_path = ereg_replace("\\\\admin", '', $HTTP_SERVER_VARS[PATH_TRANSLATED]);
		$base_path = ereg_replace("\\\\\\\\\\\\$script", '', $base_path);
		$base_path = ereg_replace("\\\\\\\\$therest", '', $base_path);
        $base_path_forums = ereg_replace("\\\\admin", '', $HTTP_SERVER_VARS[PATH_TRANSLATED]);
		$base_path_forums = ereg_replace("\\\\\\\\\\\\$script", '', $base_path_forums);
	}
	else
	{
	// UNIX
		$base_path = ereg_replace("$directory/admin/$script", '', "$HTTP_SERVER_VARS[PATH_TRANSLATED]");
		$base_path_forums = ereg_replace("/admin/$script", '', "$HTTP_SERVER_VARS[PATH_TRANSLATED]");
	}
    
?>
	
	<table width="98%" border="1" cellspacing="0" cellpadding="2" align="left"></p>
	<tr>
		<th colspan="2">vbPortal Server Path/URL Variables</th>
	</tr>
	<tr>
		<td>$nukurl = </td>
		<td><?php echo "$base_url"; ?><br>
		<b>Complete URL for your site *WITHOUT final trailing slash*</b></td>
	</tr>
	<tr>
		<td>$nukepath = </td>
		<td><?php echo "$base_path"; ?><br>
		<b>System path to your site *WITHOUT final trailing slash*</b></td>
	</tr>
	<tr>
		<td>$bburl = </td>
		<td><?php echo "$base_url_forums"; ?><br>
		<b>Complete URL to vbulletin *WITHOUT final trailing slash*</b></td>
	</tr>
	<tr>
		<td>$vbpath = </td>
		<td><?php echo "$base_path_forums"; ?><br>
		<b>System path to your vBulletin sub-directory *WITHOUT final trailing slash*</b></td>
	</tr>
	</table>
	
</body>
</html>
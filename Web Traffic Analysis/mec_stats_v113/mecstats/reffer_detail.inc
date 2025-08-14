<h1>Refferer Data</h1>

<?
//Written by Matt Toigo

$logfile = fopen($SETTINGS['logfile'], 'r');

$ref_hit = array();

//Reads logfile
while(!feof($logfile))
{
	$line = fgets($logfile, 1024);
	$ld = explode('|', $line);
	if($_GET['dom']==get_ref_domain($ld[3]))
	{
		$ref_hit[$ld[3]]++;
		$total++;
	}
}
fclose($logfile);

//Include archived files, only works with one archive for now
if(file_exists('arch.txt'))
{
	$archivefile = fopen('arch.txt', 'r');
	while(!feof($archivefile))
	{
		$line = fgets($archivefile, 1024);
		$ld = explode('|', $line);
		
		//Make sure were not looking at a blank line
		if($ld[0]!=NULL)
		{
			//Are we looking at refferers yet?
			if($ld[0]==$_GET['dom'])
			{
				$total = $total + $ld[1];
			}
		}
	}
}




arsort($ref_hit);
?>
<b><?=$total;?> hits from <a href="http://<?=$_GET[dom];?>"><?=$_GET[dom];?></a></b><br><br>

<table class="pages" cellpadding="5" cellspacing="0"><tr class="head"><td>Page</td><td>Hits</td></tr>
<?
$position = 1;
$size = count($ref_hit);
foreach($ref_hit as $key=>$ele)
{	
	?><tr<?if($size==$position){?> class="bottom"<?}?>><td><a href="<?=$key;?>"><?=$key;?></a></td><td><?=$ele;?></td><?
	$position++;
}
?>
</table>


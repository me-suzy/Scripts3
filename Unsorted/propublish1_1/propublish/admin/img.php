<? 
$req_level = 2;
$override = 1;
include "inc_t.php";
$limit = "47000";
$slett = htmlspecialchars($slett);

print("<h3>$la_picupload $navn</h3>");
print "<a href=\"javascript:window.close();\"><b>$la_return</b></a><p>";

if ($show_tip)
{
	print "Bildeplassering default:&nbsp; &lt;image&gt;bildenummer&lt;/image&gt;<br>";
	print "Bildeplassering venstre: &lt;image_left&gt;bildenummer&lt;/image&gt;<br>";
	print "Bildeplassering høyre:&nbsp;&nbsp;  &lt;image_right&gt;bildenummer&lt;/image&gt;<br><p>";
}

print "<a href='img.php?show_tip=1&name=$name&artid=$artid'>Vis tips for bildeplassering</a>";

print "<table border='1' cellspacing='0'><tr><td>";

?>

<table>
<tr>
	<td>
	<form method="post" action="img.php" enctype="multipart/form-data">
	<INPUT TYPE="hidden" name="artikkelid" value="<? echo $artid ?>">
	<INPUT TYPE="hidden" name="artid" value="<? echo $artid ?>">
	<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="47000">
	<br><input type="file" name="form_data" size="40"><br>
	<input type="submit" name="submit" value="<? echo $la_pic_up; ?>">
	
	<?

	if ($submit AND ($form_data_size <> 0))
	{
		if ($form_data_size < 47000)
 		{
 	     		$data = addslashes(fread(fopen($form_data,  "r"), filesize($form_data)));
      			$result=MYSQL_QUERY( "INSERT INTO newspicture_news (artikkelid,bin_data,filename,filesize,filetype) ".
			"VALUES ($artikkelid,'$data','$form_data_name','$form_data_size','$form_data_type')");
		        $id = mysql_insert_id();

        		print("<p>$la_pic_success1 &lt;image&gt;$id&lt;/image&gt; $la_pic_success2");
        		
      		
 		}
 		elseif ($form_data_size > $limit)
 		{
        		print("<p><p>$la_upload_error");
 		}
	}

print("<p><p></td></tr></table><p>");




print "<b><u>$la_img_already</u></b><br>";
print "<table border='2' cellspacing='5' bordercolor='white'>";
if ($slett)
{

	$sql = "DELETE from newspicture_news where id = $slett";
	$result = mysql_query($sql);

 	if ($result)
 	{
 		print "<b>$la_del_img $slett</b>";
 	}
}
print "</td></tr></table>";


print "<table border='2' cellspacing='5' bordercolor='white' class='articlebody'>";
$query = "select * from newspicture_news where artikkelid = $artid";
$result = MYSQL_QUERY($query);

$num_res = mysql_num_rows($result);

for ($i=0; $i<$num_res; $i++)
{
        $myrow = mysql_fetch_array($result);
	$bildeid = $myrow["id"];
	print("<tr><td>$la_img &lt;image&gt;$bildeid&lt;/image&gt; <a 
href='?slett=$bildeid&artid=$artid'>$la_del</a><hr><img src='img_get.php?id=$bildeid'><p></td></tr><tr><td><p><p></td></tr>");

}
print "</table>";


print "</td></tr></table>";
?>





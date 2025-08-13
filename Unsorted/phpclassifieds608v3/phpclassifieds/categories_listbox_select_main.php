     
<form method="post" action="add_ad.php">
<input type="hidden" name="checkpass" value="<? echo $checkpass ?>">
<?
if ($checkpass AND $checkuserid)
{
 	 print("<input type='hidden' name='userid' value='$checkuserid'>");
	 print("<input type='hidden' name='password' value='$checkpass'>");
}

?>

<?
if ($sisteid AND $pass)
{
?>
<input type="hidden" name="userid" value="<?php echo $sisteid ?>">
<input type="hidden" name="password" value="<? echo $pass ?>">
<?
}

?>
<select size="1" name="catid">
                     <option selected><? echo $choose_cat ?></option>

<?

$sql_select = "select * from $cat_tbl where catfatherid = 0 order by catname";
$result = mysql_query ($sql_select);

while ($row = mysql_fetch_array($result)) {

        $catid = $row["catid"];
        $catfatherid = $row["catfatherid"];
        $catname = $row["catname"];
        $catdescription = $row["catdescription"];
	 
 
print("<option value='$catid'>");
	print("$catname");

print("</option>");



};


?>
</select><br />
 <input type="submit" value="<? echo $la_cat_next ?> --&gt;" name="registrer" style="font-size: 8pt; font-family: Verdana">
</form>

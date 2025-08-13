<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
if (!$special_mode)
{ print("$menu_ordinary<p />"); }
print("<h3>$name_of_site</h3>");
include_once("member_header.php");
check_valid_user();

?>
<script language="JavaScript">
        function openWin(URL) { aWindow=window.open(URL,"Large","toolbar=no,width=400,height=300,status=no,scrollbars=no,resize=no,menubars=no");
        }
        function openWin2(URL) {
aWindow=window.open(URL,"Large","toolbar=no,width=400,height=350,status=no,scrollbars=no,resize=no,menubars=no");
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         }
        </script>
<table border="1" cellspacing="1" width="100%" height="100%">
<tr>
    <td width="100%" valign="top">
        <h2><? echo $la_pic_upload ?></h2>


<?
print " $la_upload_limit " . floor($piclimit / 1024) . " kb. <p />";
if (!$submit)
{
        print " $upload_picture_text ";
}

$sq = "select id from $pic_tbl where pictures_siteid = $pictures_siteid";
$result2 = MYSQL_QUERY($sq);
$mysql_img = mysql_num_rows($result2);

if ($maxpic > $mysql_img)
{
	print "<form method='post' action='upload_new.php' enctype='multipart/form-data'>";
}
else 
{
	print "<p /> <b>$la_no_morepic</b> <p />";
}

?>
<INPUT TYPE="hidden" name="pictures_siteid" value="<? echo $pictures_siteid ?>">
<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="<? echo $piclimit ?>">

<?

if ($submit AND ($form_data_size <> 0))
{
	if ($form_data_size < $piclimit)
 	{


	$data = addslashes(fread(fopen($form_data,  "r"), filesize($form_data)));
	require("admin/config/general.inc.php");
	require("admin/db.php");
	$imagehw = GetImageSize($form_data);
   	$imagew =  $imagehw[0];
    $imageh = $imagehw[1];

	$sq = "INSERT INTO $pic_tbl (pictures_siteid,bin_data,filename,filesize,filetype,imagew,imageh) VALUES ('$pictures_siteid','$data','$form_data_name','$form_data_size','$form_data_type','$imagew','$imageh')";
	$result=MYSQL_QUERY($sq);
	$id = mysql_insert_id();
       
    if (mysql_affected_rows())
    {        
    	print("<p /> $la_upload_success ");
    	$sq = "UPDATE $ads_tbl set picture = picture+1 where siteid=$pictures_siteid";
    	$r = mysql_query($sq);
    }
    
    
	           
    print "<br /> <a href='member.php'>$la_add_another</a> <br />";
    
    
	
 }
 elseif ($form_data_size > $limit)
 {
        print("<p /><p />$la_upload_error2");
 }
}
?>
<p />
 <? echo $la_filename ?> <br /><input type="file" name="form_data" size="40" /><br />
<input type="submit" name="submit" value="<? echo $la_upload_button ?>" />
<?

print "<p />";

// Show pictures
if ($delete)
{
	$sql_delete = "delete from $pic_tbl where id = $delete";	
	$result = mysql_query($sql_delete);
	
	if ($result)
	{
		print "<p /> <b>$la_del_img</b> <p />";	
		$sq = "UPDATE $ads_tbl set picture = picture-1 where siteid=$pictures_siteid";
    	$result = mysql_query($sq);
	}
}

$query = "select id,imageh,imagew from $pic_tbl where pictures_siteid=$pictures_siteid order by id desc";
$sql_result = mysql_query ($query);
$num_pictures =  mysql_num_rows($sql_result);

for ($i=0; $i<$num_pictures; $i++)
	{
      
    $row = mysql_fetch_array($sql_result);  

	$w = $row["imagew"];
	$h = $row["imageh"];
	$id = $row["id"];
	
	setImageSize("get.php?id=$picture"); 
  	print("<br /><a href=\"javascript:openWin('large_picture.php?id=$id')\"><img border=\"0\" alt=\"$la_large_pic\" src=\"get.php?id=$id\"  width='$imgSizeArray[0]' height='$imgSizeArray[1]' valign=\"left\" alt='' /></a><br /><a href='upload_new.php?pictures_siteid=$pictures_siteid&amp;delete=$id'>$la_del_img1</a><p />");
}
print("</td></tr></table>");
include_once ("member_footer.php");
require("admin/config/footer.inc.php");
?>

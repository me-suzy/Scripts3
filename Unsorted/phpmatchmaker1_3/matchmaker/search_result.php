<?
require_once("php_inc.php"); 
include "webmaster/settings_inc.php";
session_start();

if (!$listmode)
{
	include("header_inc.php");
}
db_connect();

if (session_is_registered("valid_user") AND !$listmode)
{
	member_menu(); 
	do_html_heading("Search");
}

$debug = 0;
$sex_s = trim($sex_s);
$place_s = trim($place_s);

if (empty($offset))
{
 $offset=0;
}
$limit = 10;

if ($pic)
{
	$pic_string = "AND image <> 'NULL'";
}
if ($search AND $sex_s AND $place_s AND $age_from AND $age_to)
{

     $numresults = mysql_query("select * from user where sex like '$sex_s%' AND place like '$place_s%' AND (age between $age_from AND $age_to $pic_string)");
     $numrows=mysql_num_rows($numresults);
     $string = "select * from user where sex like '$sex_s%' AND place like '$place_s%' AND (age between $age_from AND $age_to) $pic_string limit $offset,$limit";
     $result=mysql_query($string);
     if ($debug)
     {
      print "<hr>$string<hr>";
     }
     $URLNEXT = "&sex_s=$sex_s&age_from=$age_from&age_to=$age_to&place_s=$place_s&search=1&place=$place&sex=$sex&pic=$pic";
}
elseif ($gsearch AND $psearch)
{
     //print "Gender and Place search";
     $numresults = mysql_query("select * from user where place like '$place' AND sex = '$sex'");
     $numrows=mysql_num_rows($numresults);
     $string = "select * from user where place like '$place' AND sex = '$sex' limit $offset,$limit";
     $result=mysql_query($string);
     $URLNEXT = "&gsearch=1&psearch=1&sex=$sex&place=$place&pic=$pic";
}
else
{

 $numresults = mysql_query("select * from user");
 $numrows=mysql_num_rows($numresults);
 $result=mysql_query("select * from user order by registered desc limit $offset,$limit");
 $URLNEXT = "&sex_s=$sex_s&age_from=$age_from&age_to=$age_to&place_s=$place_s&search=1&place=$place&sex=$sex&pic=$pic";
}

if ($listmode)
{
print "<table bgcolor='#E8E8EE'><tr><td valign='top' colspan='6' bgcolor='#E8E8E8'><font face=arial size=2>Searchresult<br></font><font face=arial size=1><a href='search.php'>Search again</a><p></font></td></tr>";
while ($row=mysql_fetch_array($result)) {

 $username = $row[username];
 $sex = $row[sex];
 $age = $row[age];
 $marital= $row[marital];
 $height = $row[height];
 $weight = $row[weight];
 $build = $row[build];
 $hair = $row[hair];
 $eye = $row[eye];
 $place = $row[place];
 $occupation = $row[occupation];
 $religion = $row[religion];
 $education = $row[education];
 $children = $row[children];
 $about = $row[about];
 $i1 = $row[i1];
 $sex = trim($sex);

 if ($sex == 'Male')
 {
         $s = 'M';
 }
 elseif ($sex == 'Female')
 {
         $s = 'F';
 }
 elseif ($sex == 'Couple')
 {
          $s = 'C';
 }
 else
 {
          $s = 'U';
 }
 print "<tr>";
 print "<td><font face=arial size=1><a href='detail.php?profile=$username$URLNEXT'>$username</a></font></td><td valign=top><font face=arial size=1>$s</font></td><td><font face=arial size=1>($age)</font></td>";
 print "</tr>";
}
print "</table><br>";
} // End listmode
else 
{
print "<table width='400'><tr bgcolor='#FF9966'><td>&nbsp;Search Results &nbsp;&nbsp;&nbsp;<b>$numrows</b> match(s) / $limit match(s) per page</td></tr><tr><td>";
while ($row=mysql_fetch_array($result)) {

 $username = $row[username];
 $sex = $row[sex];
 $age = $row[age];
 $marital= $row[marital];
 $height = $row[height];
 $weight = $row[weight];
 $build = $row[build];
 $hair = $row[hair];
 $eye = $row[eye];
 $place = $row[place];
 $occupation = $row[occupation];
 $religion = $row[religion];
 $education = $row[education];
 $children = $row[children];
 $about = $row[about];
 $i1 = $row[i1];
 $image = $row[image];
 
 if (!$image)
 {
 	$image = "noimage.gif";
 }
 
 
 $sex = trim($sex);

 if ($sex == 'Male')
 {
         $s = 'M';
 }
 elseif ($sex == 'Female')
 {
         $s = 'F';
 }
 elseif ($sex == 'Couple')
 {
          $s = 'C';
 }
 else
 {
          $s = 'U';
 }
 print "<tr>";
 
 $filename = "templates/list.htm";
 $fd = fopen ($filename, "r");
 $file= fread ($fd, filesize ($filename));
 $file = str_replace("{USERNAME}", "$username", $file);
 $file = str_replace("{PLACE}", "$place", $file);
 $file = str_replace("{SEX}", "$sex", $file);
 $file = str_replace("{AGE}", "$age", $file);
 $file = str_replace("{MARITAL}", "$marital", $file);
 $file = str_replace("{ABOUT}", "$about", $file);
 
 if ($set_magic AND !ereg("^img",$image) AND $image<>"noimage.gif")
 {
	$ext=substr($image,-4);
	$file_without_ext=substr($image,0,-4);
 	$image = $rand . $file_without_ext . "_small" . $ext;
 	
 	$file = str_replace("{IMAGE}", "<img border='0' src='upload_images/$image' align='right'>", $file);
 }
 else 
 {
 	$wi = split("x",$set_thmbsize);
 	$wi = $wi[0];
 	$file = str_replace("{IMAGE}", "<img border='0' src='upload_images/$image' width='$wi' align='right'>", $file);
 }
 
                                           
 print $file;
  
}
print "</td></td></table><br>";
} // End NOT listmode



if (!$listmode)
{	

	if ($offset==1) { // bypass PREV link if offset is 0
	    $prevoffset=$offset-10;
	    print "<font face=arial size=1><a href=\"$PHP_SELF?offset=$prevoffset&profile=$profile$URLNEXT\">PREV</a> </font>\n";
	}
}	
	// calculate number of pages needing links
	$pages=intval($numrows/$limit);
	
	// $pages now contains int of pages needed unless there is a remainder from division
	if ($numrows%$limit) {
	    // has remainder so add one page
	    $pages++;
	}
	
if (!$listmode)
{
	for ($i=1;$i<=$pages;$i++) { // loop thru
	    $newoffset=$limit*($i-1);
	    print "<font face=arial size=1><a href=\"$PHP_SELF?offset=$newoffset&profile=$profile$URLNEXT\">$i</a> </font>\n";
	}
}	
	// check to see if last page
	if (!(($offset/$limit)==$pages) && $pages!=1) {
	    // not last page so give NEXT link
	    $newoffset=$offset+$limit;
	    print "<font face=arial size=1><a href=\"$PHP_SELF?offset=$newoffset&profile=$profile$URLNEXT\">NEXT</a></font><p>\n";
	}

if (!$listmode)
{
	include("footer_inc.php");
}
?> 

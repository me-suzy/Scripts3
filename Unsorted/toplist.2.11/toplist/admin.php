<?

// Admin action logging function


function adminlog($action,$ip){
        $plik = fopen("admin_action.log", "a+");
        $date = date("Y-m-d H:i:s");
        $content = "\n" . $date . " - " . $ip . " - Action:" . $action;
        fwrite($plik, $content) or die('File error!');
}

//

if($log == "in"){

        setcookie("cookiepass",md5($pass));
        header("Location: admin.php");

}

include "config.php";
include "db/db.php";
db_options();
include "style/" . $option['style'] . ".php";
include "languages/" . $language . ".php";


if($cookiepass <> $option['confpass']){

        echo "<center>Please login:<br><br><form method=post action=admin.php?log=in><br><br>Password: <input type=password name=pass><br><input type=submit value=" . $lang['submit'] . "></form>";

} else {

        echo "<center><table width=600><tr><td width=20%>Menu:
        <br><br>
        <br>Sites options:<br>
        - <a href=admin.php?akcja=authorize>Authorize sites</a><br>
        - <a href=admin.php?akcja=del>Delete sites</a><br>
        <br>News:<br>
        - <a href=admin.php?akcja=news>Write a news</a><br>
        - <a href=admin.php?akcja=deletenews>Delete news</a><br>
        - <a href=admin.php?akcja=editnews>Edit news</a><br>
        <br>Reset list:<br>
        - <a href=admin.php?akcja=reset>Reset list</a><br>
        - <a href=admin.php?akcja=autoreset>Set up automatic reset</a><br>
        <br>Misc. options:<br>
        - <a href=admin.php?akcja=options>Change options</a><br>
        - <a href=admin.php?akcja=ban>Ban IP</a><br>
        - <a href=admin.php?akcja=remban>Unban IP</a><br>
        - <a href=admin.php?akcja=viewstats>View stats</a><br>
        - <a href=admin.php?akcja=functions>Functions enable/disable</a><br>
        - <a href=admin.php?akcja=mailing>Mailing list</a><br>
        - <a href=admin.php?akcja=inactive>Delete inactive sites</a><br>
        - <a href=admin.php?akcja=newver>Check for new version</a><br>
        - <a href=admin.php?akcja=backupdb>Database Backup</a><br>
        <br>Layout options:<br>
        - <a href=admin.php?akcja=editstyle>Edit style</a><br>
        - <a href=admin.php?akcja=menueditor>Menu editor</a><br>
        <br>Categories options:<br>
        - <a href=admin.php?akcja=addcat>Add category</a><br>
        - <a href=admin.php?akcja=editcat>Edit category</a><br>
        - <a href=admin.php?akcja=remcat>Remove category</a><br>
        </td><td>";

if($akcja == ""){

        echo "<br><br><br><center>Welcome in Best Top List $version Administration Panel.";

}

if($akcja == "authorize"){
        $wyn = "SELECT * FROM toplista WHERE active='n'";
        $wykonaj = mysql_query($wyn);
        $znaleziono = mysql_num_rows($wykonaj);
        echo "<br><br>Sites waiting for authorization:<br><br>";
        while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++) print $tab[0]." - ".$tab[1]." - <a href=".$tab[3]." target=_blank>Banner</a> - <a href=admin.php?akcja=accept&id=".$tab[7].">Accept</a> - <a href=admin.php?akcja=delete&id=".$tab[7].">Reject</a><br>";
}


if($akcja == "news"){
        echo "<br><br>News posting:<br>";
        echo "<form method=POST action=admin.php?akcja=addnews>Your nick:<br><input type=input name=newsman><br>News<br><textarea rows=10 cols=30 name=news></textarea><br><input type=submit value=" . $lang['submit'] . "></form>";
};
if($akcja == "accept"){
        $c = "UPDATE toplista SET active='y' WHERE id='$id'";
        $cc = mysql_query($c);
        $wyn = "SELECT * FROM toplista WHERE id='$id'";
        $wykonaj = mysql_query($wyn);
        $tab = mysql_fetch_row($wykonaj);
        $email = $tab[4];
        mail($email,"Your site has been accepted!","Hello!\n\nYour site has been accepted  and added to the " . $option['nameoflist'] . ".\n\nRegards,\nTeam " . $option['nameoflist'] . "");
        echo "<br><br>Site accepted!";
        adminlog("Accept site with id: " . $id, $REMOTE_ADDR);
};
if($akcja == "delete"){
        $wyn = "SELECT * FROM toplista WHERE id='$id'";
        $wykonaj = mysql_query($wyn);
        $tab = mysql_fetch_row($wykonaj);
        $email = $tab[4];
        mail($email,"Your site has been rejected!","Hello!\n\nYour site has been rejected and doesnt added to the " . $option['nameoflist'] . ".\n\nRegards,\nTeam " . $option['nameoflist'] . "");
        $c = "DELETE FROM toplista WHERE id='$id'";
        $cc = mysql_query($c);
        echo "<br><br>Site rejected";
        adminlog("Reject site with id: " . $id, $REMOTE_ADDR);
}

if($akcja == "deletesite"){
        $c = "DELETE FROM toplista WHERE id='$id'";
        $cc = mysql_query($c);
        echo "<br><br>Site deleted!";
        adminlog("Delete site with id: " . $id, $REMOTE_ADDR);
};

if($akcja == "addnews"){
        $data = date("F j, Y | H:i");
        $wpisz = "INSERT INTO toplista_news VALUES('$news', '$newsman', '$data', '')";
        $wpiszb = mysql_query($wpisz);
        echo "<br><br>News added!";
        adminlog("Add news", $REMOTE_ADDR);
};
if($akcja == "del"){
        echo "<br><br>Sites actually accepted:<br><br>";
        $wyn = "SELECT * FROM toplista WHERE active='y'";
        $wykonaj = mysql_query($wyn);
        $znaleziono = mysql_num_rows($wykonaj);
        while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++) print $tab[0]." - ".$tab[1]." - <a href=admin.php?akcja=delete&id=".$tab[7].">Delete</a><br>";
}

if($akcja == "reset"){
        echo "<br><br>Please wait ... ";
        $wyn = "SELECT * FROM toplista WHERE active='y'";
        $wykonaj = mysql_query($wyn);
        $znaleziono = mysql_num_rows($wykonaj);
        while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
        $id = $tab[7];
        $upd1 = mysql_query("UPDATE toplista SET wejsica='0' WHERE id='$id'") or die('<font color=red>Error!</font>');
        $upd2 = mysql_query("UPDATE toplista SET wyjscia='0' WHERE id='$id'") or die('<font color=red>Error!</font>');
        $upd3 = mysql_query("UPDATE toplista SET rating='0' WHERE id='$id'") or die('<font color=red>Error!</font>');
        $upd4 = mysql_query("UPDATE toplista SET votes='0' WHERE id='$id'") or die('<font color=red>Error!</font>');
        }
        echo "<font color=red>Done!</font>";
        adminlog("Reset list", $REMOTE_ADDR);
}

if($akcja == "options"){
echo "<form method=post action=admin.php?akcja=optionssubmit><table>
  <tr>
    <td>Name of list:</td>
    <td><input type=text name=nameoflist value=\"" . $option['nameoflist'] . "\"></td>
  </tr>
  <tr>
    <td>Style:</td>
    <td><input type=text name=style value=\"" . $option['style'] . "\"></td>
  </tr>
  <tr>
    <td>Top List URL:</td>
    <td><input type=text name=siteurl value=\"" . $option['siteurl'] . "\"></td>
  </tr>
  <tr>
    <td>Admin mail:</td>
    <td><input type=text name=adminmail value=\"" . $option['adminmail'] . "\"></td>
  </tr>
  <tr>
    <td>Cookie domain:</td>
    <td><input type=text name=cookiedomain value=\"" . $option['cookiedomain'] . "\"></td>
  </tr>
  <tr>
    <td>Cookie time (in seconds):</td>
    <td><input type=text name=cookietime value=\"" . $option['cookietime'] . "\"></td>
  </tr>
  <tr>
    <td>Max banner height:</td>
    <td><input type=text name=maxheight value=" . $option['maxheight'] . "></td>
  </tr>
  <tr>
    <td>Max banner width:</td>
    <td><input type=text name=maxwidth value=" . $option['maxwidth'] . "></td>
  </tr>
    <tr>
    <td>Max visible banners (top xx):</td>
    <td><input type=text name=maxbanners value=" . $option['maxbanners'] . "></td>
  </tr>
  <tr>
    <td></td>
    <td><input type=hidden name=updateperday value=" . $option['updperday'] . "><input type=hidden name=nextupdate value=" . $option['nextupd'] . "><input type=submit value=" . $lang['submit'] . " name=submit></td>
  </tr>
</table></form>";
}
if($akcja == "optionssubmit"){
$ct = $option['cookietime'];
$del = mysql_query("DELETE FROM toplista_options WHERE cookietime=$ct");
echo "<br><br>Update ...";
$confpass = $cookiepass;
$create = "INSERT INTO toplista_options VALUES('$nameoflist', '$style', '$siteurl', '$adminmail', '$confpass', '$cookiedomain', '$cookietime','$nextupdate','$updateperday', '$maxheight', '$maxwidth', '$maxbanners')";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>Done</font><br><br><a href=admin.php?akcja=updatetimer>Click here to update timer!</a>";
adminlog("Change options", $REMOTE_ADDR);
}

if($akcja == "updatetimer"){
        $days = $option['updperday'];
        $nextupdate = $days * 86400 + time();
        $upd1 = mysql_query("UPDATE toplista_options SET nextupd='$nextupdate'") or die('<font color=red>Error!</font>');
        echo "<center><br><br>Timer updated!<br><br>";
        adminlog("Update timer", $REMOTE_ADDR);
}

if($akcja == "ban"){
        echo "<center><br><br>Here you can ban IP with 3 levels of ban. Levels: 1 - user can't add site 2 - user can't vote 3 - total ban :-)<br><br><form method=post action=admin.php?akcja=ban&submit=yes>IP: <input type=text name=ip><br><br>Level: <input type=radio name=level value=1> 1 <input type=radio name=level value=2> 2 <input type=radio name=level value=3> 3 <br><br><input type=submit value=" . $lang['submit'] . "></form>";

        if($submit == "yes"){
        $sqlquery = "INSERT INTO toplista_banned VALUES('$ip', '$level', '')";
        $sql = mysql_query ($sqlquery);
        adminlog("Ban IP: " . $ip, $REMOTE_ADDR);
        }

}


if($akcja == "remban"){

        $wyn = "SELECT * FROM toplista_banned";
        $wykonaj = mysql_query($wyn);
        $znaleziono = mysql_num_rows($wykonaj);
        echo "<center><br><br>Here you can remove ban from users<br><br><form method=post action=admin.php?akcja=remban&submit=yes>IP: <select name=ip>";
        while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
        echo "<option value=" . $tab[2] . ">" . $tab[0] . "</option>";
        }
        echo "</select><br><br><input type=submit value=" . $lang['submit'] . "></form><br><br>";

        if($submit == "yes"){
        $sqlquery = "DELETE FROM toplista_banned WHERE id=$ip";
        $sql = mysql_query ($sqlquery);
        echo "Users unbanned";
        adminlog("Unban IP: " . $ip, $REMOTE_ADDR);
        }

}

if($akcja == "editstyle"){

echo "<br><br><center>Style editor v.1.01. for Best Top List by Szymon Kosok<br><br><form method=post action=admin.php?akcja=stylesubmit>
Name of style:        <input type=text name=nameofstyle><br><br>
Your name:            <input type=text name=author><br><br>
Name of file:         <input type=text name=filename>.php<br><br>
Background color:     <input type=text name=bgcolor><br><br>
Default font color:   <input type=text name=fontcolor><br><br>
Default font family:  <input type=text name=fontfamily><br><br>
Default font size:    <input type=text name=fontsize><br><br>
Link color:           <input type=text name=linkcolor><br><br>
Hover link color:     <input type=text name=hlinkcolor><br><br>
Visited link color:   <input type=text name=vlinkcolor><br><br>
Link font family:     <input type=text name=linkfont><br><br>
Link font size:       <input type=text name=linksize><br><br>
Table border color:   <input type=text name=tblborder><br><br>
Table header color:   <input type=text name=tblhead><br><br>
Table body color:     <input type=text name=tblbody><br><br>
Table header font color: <input type=text name=tblheadfont><br><br>
<input type=checkbox name=sendtobtl value=yes> Check if you wanna send this style to Best Top List Team<br><br>
<input type=checkbox name=tlstyle value=yes> Check if you wanna use this style on your top list<br><br>
<input type=submit value=" . $lang['submit'] . "></form>";

}


if($akcja == "stylesubmit"){

        $plik = fopen("style/$filename.php", "w");
        $content="<?

// $nameofstyle style for Best Top List by $author - http://www.best-scripts.tk
// Support: support@best-scripts.tk
//
// Copyright 2002 (c) Szymon Kosok

echo \"<style>

body{background:" . $bgcolor . ";color:" . $fontcolor . ";font-family:" . $fontfamily . ";font-size:" . $fontsize . "pt;}
a{color:" . $linkcolor . ";font-family:" . $linkfont . ";font-size:" . $linksize . "pt;}
a:visited{color:" . $vlinkcolor . ";font-family:" . $linkfont . ";font-size:" . $linksize . "pt;}
a:hover{color:" . $hlinkcolor . ";font-family:" . $linkfont . ";font-size:" . $linksize ."pt;}
td{font-family:" . $fontfamily . ";font-size:" . $fontsize . "pt;}
input{font-family:" . $fontfamily . ";font-size:" . $fontsize . "pt;}
textarea{font-family:" . $fontfamily . ";font-size:" . $fontsize . "pt;}
</style>\";

" . chr(36) . "tableheader = \"$tblhead\";
" . chr(36) . "tablebody = \"$tblbody\";
" . chr(36) . "tableheadfont = \"$tblheadfont\";
" . chr(36) . "tableborder = \"$tblborder\";
?>";
        fwrite($plik, $content) or die("<br><br><br>Something wrong with write function, maybe you can't change style folder permission to 777?");
echo "<br><br>Style added! :)";

        if($sendtobtl == "yes"){
        mail("nookie@xtina.pl",$nameofstyle . " - " . $author,$content . "\n\n\n" . $filename . ".php", "Reply-to: " . $option['adminmail']);
        }

        if($tlstyle == "yes"){
        $actstyle = $option['style'];
        $sql = "UPDATE toplista_options SET style='$filename' WHERE style='$actstyle'";
        $sqlgo = mysql_query($sql) or die("<br><br><br><center>MySQL Error");
        }
}

if($akcja == "menueditor"){

echo "<br><br><br><center>Menu editor:<br><br><form method=post action=admin.php?akcja=menusubmit>
Add a site: <input type=checkbox name=addsite value=1><br><br>
Edit site: <input type=checkbox name=editsite value=1><br><br>
Lost password: <input type=checkbox name=lostpass value=1><br><br>
Contact: <input type=checkbox name=contact value=1><br><br>
Tell a friend: <input type=checkbox name=taf value=1><br><br>
Rules: <input type=checkbox name=rules value=1><br><br>
Lost HTML: <input type=checkbox name=losthtml value=1><br><br>
Banner hosting: <input type=checkbox name=bannerhosting value=1><br><br>
News: <input type=checkbox name=news value=1><br><br>
View stats: <input type=checkbox name=viewstats value=1><br><br>
Home: <input type=checkbox name=home value=1><br><br>
<input type=submit value=" . $lang['submit'] . "></form>";
}

if($akcja == "menusubmit"){

$del = mysql_query("DELETE FROM toplista_menu");
echo "<br><br>Update ...";
$confpass = md5($pass);
$create = "INSERT INTO toplista_menu VALUES('$addsite', '$editsite', '$lostpass', '$contact', '$taf', '$rules', '$losthtml', '$bannerhosting', '$news', '$viewstats', '$home')";
$utworz = mysql_query($create) or die('<font color=red>error!</font>');
echo "<font color=red>Done</font>";
adminlog("Changed menu", $REMOTE_ADDR);

}


if($akcja == "viewstats"){

echo "<br><br><center>Stats viewer.<br><br><form method=post action=admin.php?akcja=viewstatnow&submit=yes&pass=$pass>Site ID: <input type=text name=id><br>No. of records to display: <input type=text name=records><br><input type=radio name=what value=in> In stats <input type=radio name=what value=out> Out stats<br><input type=submit value=" . $lang['submit'] . "></form>";
}

if($akcja == "viewstatnow"){
        $wyn = "SELECT * FROM toplista_stats WHERE siteid='$id' AND inout='$what' ORDER BY id ASC LIMIT 0,$records";
        $wykonaj = mysql_query($wyn) or die('Database error');
        $znaleziono = mysql_num_rows($wykonaj);
        echo "<center><br><br>\"$what\" stats (last $records):<br><br><table border=1 cellpadding=3 cellspacing=0 border-collapse: collapse bordercolor=#111111>
  <tr>
    <td>IP</td>
    <td>Date:</td>
  </tr>";
        while($row = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
        }
  echo "</table>";
  adminlog("View stats of site: " . $id, $REMOTE_ADDR);
        }


if($akcja == "functions"){

echo "<br><br><br><center>Functions editor:<br><br><form method=post action=admin.php?akcja=funcsubmit>
Users can rate site: <input type=checkbox name=rating value=1><br><br>
Anit-cheat gate: <input type=checkbox name=anticheat value=1><br><br>
Cheat attempts logging: <input type=checkbox name=logcheat value=1><br><br>
Search function: <input type=checkbox name=searchfunc value=1><br><br>
Statistic function: <input type=checkbox name=statsfunc value=1><br><br>
Auto accept sites: <input type=checkbox name=aas value=1><br><br>
Mail notification about new sites: <input type=checkbox name=mailnotif value=1><br><br>
Users can comment site: <input type=checkbox name=comments value=1><br><br>
Banner size checking: <input type=checkbox name=bannersize value=1><br><br>
Enable categories: <input type=checkbox name=categories value=1><br><br>
<input type=submit value=" . $lang['submit'] . "></form>";

}

if($akcja == "funcsubmit"){

$del = mysql_query("DELETE FROM toplista_functions");
echo "<br><br>Update ...";
$create = "INSERT INTO toplista_functions VALUES('$rating', '$anticheat', '$logcheat', '$searchfunc', '$statsfunc', '$aas', '', '$mailnotif', '$comments', '$bannersize', '$categories')";
$utworz = mysql_query($create) or die('<font color=red>error! - ' . mysql_error() . '</font>');
echo "<font color=red>Done</font>";
adminlog("Enable/Disable functions", $REMOTE_ADDR);

}

if($akcja == "mailing"){

echo "<center><br><br>Mailing List<br><br><br><form method=post action=admin.php?akcja=sendmail>Subject of mail:<br><input type=text name=subject><br><br>Content of mail:<br><textarea name=content rows=10 cols=20></textarea><br><br><input type=radio name=type value=text> Plain text<br><input type=radio name=type value=html> HTML mail<br><br><input type=submit value=" . $lang['submit'] . "></form>";

}

if($akcja == "sendmail"){
echo "<center><br><br>Please wait, sending ...";
$wyn = "SELECT * FROM toplista";
        $wykonaj = mysql_query($wyn) or die('Database error');
        $znaleziono = mysql_num_rows($wykonaj);
        while($row = mysql_fetch_array($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
        if($type == "text"){
        mail($row['email'],$subject,$content . "\n\n________________\nMailing list module for Best Top List\nby Szymon Kosok - http://www.best-scripts.tk", "X-Mailer: Best Top List " . $version);
        }
        if($type == "html"){
        mail($row['email'],$subject,$content . "<br><br>________________<br>Mailing list module for Best Top List<br>by Szymon Kosok - http://www.best-scripts.tk", "X-Mailer: Best Top List " . $version . "\n\rContent-type: text/html");
        }

        }
echo "<font color=red>done!</font>";
adminlog("Sent mail", $REMOTE_ADDR);
}

if($akcja == "inactive"){

        echo "<center><br><br>Please select timeframe:<br><br>Search for all sites which didn't get any votes in <form method=post action=admin.php?akcja=inactivesubmit><input type=text size=2 name=votedays> days and which registered not earlier than <input type=text size=2 name=regdate> days ago.<br><br><input type=submit value=" . $lang['submit'] . "></form>";

}

if($akcja == "inactivesubmit"){

        $daysreg = time() - 86400 * $regdate;
        $daysvot = time() - 86400 * $votedays;
        $sql = mysql_query("SELECT * FROM toplista WHERE regdate < $daysreg && lastvote < $daysvot");
        $znaleziono = mysql_num_rows($sql);
        while($row = mysql_fetch_array($sql)) for($i=0;$i<count($znaleziono);$i++){
        echo $row[0] . " - <a href=" . $tab[2] . ">Visit this site - " . $tab[1] . " - <a href=admin.php?akcja=deletesite&id=" . $tab[7] . ">Delete</a><br><br>";
        }
        adminlog("Delete inactive sites", $REMOTE_ADDR);

}

if($akcja == "autoreset"){
        echo "<center>Automatic list reset (0 - off) (in days):<br><br><form method=post action=admin.php?akcja=autoresetsubmit><input type=text name=updperday value=" . $option['updperday'] . "><input type=submit value=" . $lang['submit'] . "></form>";
}

if($akcja == "autoresetsubmit"){

echo "Updating ... ";
$upd1 = mysql_query("UPDATE toplista_options SET updperday='$updperday'") or die('<font color=red>Error!</font>');
echo "<font color=red> ok!<br><br><center><a href=admin.php?akcja=updatetimer>Click here to update timer</a></center>";
adminlog("Update autoreset", $REMOTE_ADDR);
}

if($akcja == "newver"){
header("Location: http://www.besttoplist.sourceforge.net/html");
}

if($akcja == "deletenews"){

echo "<center><br><br>Click 'Delete' link to remove news.</center>";
$wyn = "SELECT * FROM toplista_news";
      $wykonaj = mysql_query($wyn);
      $znaleziono = mysql_num_rows($wykonaj);

while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
echo "<center>";
print "<br>".$tab[0]."<br><br><p align=right>" . $lang['posted'] . ": ".$tab[1]." (".$tab[2].")<br><a href=\"admin.php?akcja=deletenewsgo&id=" . $tab[3] . "\">Delete</a><br><br>";
}
}

if($akcja == "deletenewsgo"){

$del = mysql_query("DELETE FROM toplista_news WHERE id='$id'");
echo "<center>News deleted!</center>";
adminlog("Delete news", $REMOTE_ADDR);
}

if($akcja == "editnews"){

$wyn = "SELECT * FROM toplista_news";
$wykonaj = mysql_query($wyn);
$znaleziono = mysql_num_rows($wykonaj);
while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
echo "<br><br>" . $tab[0] . "<br><p align=right><a href=admin.php?akcja=editnewsb&id=" . $tab[3] . ">Edit</a></p>";
}
}

if($akcja == "editnewsb"){
$wyn = "SELECT * FROM toplista_news WHERE id=$id";
$wykonaj = mysql_query($wyn);
$tab = mysql_fetch_row($wykonaj);
echo "<center>Edit news:<br><br><form method=post action=admin.php?akcja=editnewsc&id=$id><textarea cols=40 rows=10 name=news>" . $tab[0] . "</textarea><br><input type=submit value=" . $lang['submit'] . "></form>";

}

if($akcja == "editnewsc"){
$sql = "UPDATE toplista_news SET news='$news' WHERE id='$id'";
$sqlgo = mysql_query($sql) or die("<br><br><br><center>MySQL Error");
echo "<center>Done!</center>";
adminlog("Edit news", $REMOTE_ADDR);
}

if($akcja == "addcat"){

echo "Add a category:<br><br><form method=post action=admin.php?akcja=addcatb>Name of category: <input type=text name=catname><br><br><input type=submit value=" . $lang['submit'] . "></form>";

}

if($akcja == "addcatb"){

$sql = mysql_query("INSERT INTO toplista_categories VALUES('$catname', '')");

echo "Category added!";
adminlog("Add category", $REMOTE_ADDR);
}

if($akcja == "editcat"){

$wyn = "SELECT * FROM toplista_categories";
$wykonaj = mysql_query($wyn);
echo "Categories:<br><br>";
$znaleziono = mysql_num_rows($wykonaj);
while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
        echo $tab[0] . " - <a href=admin.php?akcja=editcatb&id=" . $tab[1] . ">Edit</a><br><br>";
        }
}

if($akcja == "editcatb"){
$wyn = "SELECT * FROM toplista_categories WHERE id=$id";
$wykonaj = mysql_query($wyn);
$tab = mysql_fetch_row($wykonaj);
echo "Edit category:<br><br><form method=post action=admin.php?akcja=editcatc&id=$id>Name of category: <input type=text name=catname value=" . $tab[0] ."><br><br><input type=submit value=" . $lang['submit'] . "></form>";

}

if($akcja == "editcatc"){
$sql = "UPDATE toplista_categories SET name='$catname' WHERE id='$id'";
$sqlgo = mysql_query($sql) or die("<br><br><br><center>MySQL Error");
echo "<center>Done!</center>";
adminlog("Edit category", $REMOTE_ADDR);
}

if($akcja == "remcat"){
$wyn = "SELECT * FROM toplista_categories";
$wykonaj = mysql_query($wyn);
echo "Categories:<br><br>";
$znaleziono = mysql_num_rows($wykonaj);
        while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
        echo $tab[0] . " - <a href=admin.php?akcja=delcatb&id=" . $tab[1] . ">Delete</a><br><br>";
        }
}

if($akcja == "delcatb"){

if($id == "1"){
echo "You cannot delete this category! (you can edit name of this category)";
} else {
$sql = mysql_query("DELETE FROM toplista_categories WHERE id=$id");
echo "Category deleted";
adminlog("Delete category", $REMOTE_ADDR);
}
}

if($akcja == "backupdb"){

echo "This function backup your database to .sql file. <a href=admin.php?akcja=backupdbgo>Click here to backup</a>.";

}

if($akcja == "backupdbgo"){

$plik = fopen("backup/toplist.sql", "w");
$wykonaj = mysql_query("SELECT * FROM toplista");
$znaleziono = mysql_num_rows($wykonaj);
while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
$content = "INSERT INTO toplista VALUES('" . $tab[0] . "','" . $tab[1] . "','" . $tab[2] . "','" . $tab[3] . "','" . $tab[4] . "','" . $tab[5] . "','" . $tab[6] . "','" . $tab[7] . "','" . $tab[8] . "','" . $tab[9] . "','" . $tab[10] . "','" . $tab[11] . "','" . $tab[12] . "','" . $tab[13] . "','" . $tab[14] . "','" . $tab[15] . "')\n\n";
fwrite($plik, $content) or die('File error!');
}

$wykonaj = mysql_query("SELECT * FROM toplista_news");
$znaleziono = mysql_num_rows($wykonaj);
while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
$content = "INSERT INTO toplista_news VALUES('" . $tab[0] . "','" . $tab[1] . "','" . $tab[2] . "','" . $tab[3] . "')\n\n";
fwrite($plik, $content) or die('File error!');
}

$wykonaj = mysql_query("SELECT * FROM toplista_options");
$znaleziono = mysql_num_rows($wykonaj);
while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
$content = "INSERT INTO toplista_options VALUES('" . $tab[0] . "','" . $tab[1] . "','" . $tab[2] . "','" . $tab[3] . "','" . $tab[4] . "','" . $tab[5] . "','" . $tab[6] . "','" . $tab[7] . "','" . $tab[8] . "','" . $tab[9] . "','" . $tab[10] . "','" . $tab[11] . "')\n\n";
fwrite($plik, $content) or die('File error!');
}

$wykonaj = mysql_query("SELECT * FROM toplista_menu");
$znaleziono = mysql_num_rows($wykonaj);
while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
$content = "INSERT INTO toplista_menu VALUES('" . $tab[0] . "','" . $tab[1] . "','" . $tab[2] . "','" . $tab[3] . "','" . $tab[4] . "','" . $tab[5] . "','" . $tab[6] . "','" . $tab[7] . "','" . $tab[8] . "','" . $tab[9] . "','" . $tab[10] . "')\n\n";
fwrite($plik, $content) or die('File error!');
}

$wykonaj = mysql_query("SELECT * FROM toplista_stats");
$znaleziono = mysql_num_rows($wykonaj);
while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
$content = "INSERT INTO toplista_stats VALUES('" . $tab[0] . "','" . $tab[1] . "','" . $tab[2] . "','" . $tab[3] . "')\n\n";
fwrite($plik, $content) or die('File error!');
}

$wykonaj = mysql_query("SELECT * FROM toplista_functions");
$znaleziono = mysql_num_rows($wykonaj);
while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
$content = "INSERT INTO toplista_functions VALUES('" . $tab[0] . "','" . $tab[1] . "','" . $tab[2] . "','" . $tab[3] . "','" . $tab[4] . "','" . $tab[5] . "','" . $tab[6] . "','" . $tab[7] . "','" . $tab[8] . "','" . $tab[9] . "','" . $tab[10] . "')\n\n";
fwrite($plik, $content) or die('File error!');
}

$wykonaj = mysql_query("SELECT * FROM toplista_comments");
$znaleziono = mysql_num_rows($wykonaj);
while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
$content = "INSERT INTO toplista_comments VALUES('" . $tab[0] . "','" . $tab[1] . "','" . $tab[2] . "','" . $tab[3] . "','" . $tab[4] . "','" . $tab[5] . "')\n\n";
fwrite($plik, $content) or die('File error!');
}

$wykonaj = mysql_query("SELECT * FROM toplista_categories");
$znaleziono = mysql_num_rows($wykonaj);
while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
$content = "INSERT INTO toplista_categories VALUES('" . $tab[0] . "','" . $tab[1] . "')\n\n";
fwrite($plik, $content) or die('File error!');
}
$zamknij = fclose($plik);
adminlog("DB backup", $REMOTE_ADDR);
echo "<meta http-equiv=\"refresh\" content=\"1; url=backup/toplist.sql\">";

}

echo "</td></tr></table><br><br><br>Best Top List version $version runned on " . $SERVER_SOFTWARE . ". If you have problems <a href=http://besttoplist.sourceforge.net/phpBB2/index.php>click here</a>.<br><br><a href=http://besttoplist.sourceforge.net/phpBB2/index.php>Support Forum</a> | <a href=http://besttoplist.sourceforge.net/phpBB2/viewforum.php?f=2>Submit bugs</a> | <a href=http://besttoplist.sourceforge.net/phpBB2/viewforum.php?f=1>Feature request</a> | <a href=http://besttoplist.sourceforge.net/html>News about Best Top List</a><br><br><form action=http://www.hotscripts.com/cgi-bin/rate.cgi method=POST><input type=hidden name=ID value=15501><table BORDER=0 CELLSPACING=0 bgcolor=#E2E2E2><tr><td align=center><font face=arial, verdana size=2><b>Rate Our Program</b><br><a href=http://www.hotscripts.com></a></font></td></tr><tr><td align=center><table border=0 cellspacing=0 width=100% bgcolor=#FFFFEA><tr><td><input type=radio value=5 name=ex_rate></td><td><font face=arial, verdana size=2>Excellent!</font></td></tr><tr><td><input type=radio value=4 name=ex_rate></td><td><font face=arial, verdana size=2>Very Good</font></td></tr><tr><td><input type=radio value=3 name=ex_rate></td><td><font face=arial, verdana size=2>Good</font></td></tr><tr><td><input type=radio value=2 name=ex_rate></td><td><font face=arial, verdana size=2>Fair</font></td></tr><tr><td><input type=radio value=1 name=ex_rate></td><td><font face=arial, verdana size=2>Poor</font></td></tr></table></td></tr><tr><td align=center><input type=submit value=Cast My Vote!></td></tr></table></form>";
}
?>

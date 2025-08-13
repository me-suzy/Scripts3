<?
include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";

if($step == ""){
include "style/" . $option['style'] . ".php";
include "header.php";
echo "<center>
<form method=POST action=edit.php?step=2>
  <table border=0 cellpadding=5 cellspacing=0 style=border-collapse: collapse bordercolor=#111111 width=350>
    <tr>
      <td align=right width=183>" . $lang['user'] . ":</td>
      <td width=167><input type=text name=username size=20></td>
    </tr>
    <tr>
      <td align=right width=183>" . $lang['password'] . ":</td>
      <td width=167><input type=password name=password size=20></td>
    </tr>
        <tr>
      <td align=right width=350 colspan=2>
      <p align=center><input type=submit value=" . $lang['submit'] . " name=B1></td>
    </tr>
</table>";
};

if($step == "2"){
setcookie("editcookiepass",md5($password));
setcookie("usernamecookie",$username);
$mdpass = md5($password);
include "style/" . $option['style'] . ".php";
include "header.php";
$wyn = "SELECT * FROM toplista WHERE user='$username'";
$wykonaj = mysql_query($wyn);
$tab = mysql_fetch_row($wykonaj);
$znaleziono = mysql_num_rows($wykonaj);
if($znaleziono == "0"){
    echo "<center>" . $lang['baduser'] . "";
};
if($mdpass == $tab[9]){
$wyn = "SELECT * FROM toplista WHERE user='$username'";
$wykonaj = mysql_query($wyn);
$tab = mysql_fetch_row($wykonaj);
echo "<div align=center>
  <center>
<form method=POST action=edit.php?step=3>
  <table border=0 cellpadding=5 cellspacing=0 style=border-collapse: collapse bordercolor=#111111 width=350>
    <tr>
      <td align=right width=183>" . $lang['sitename'] . ":</td>
      <td width=167><input type=text name=nazwa size=20 value=\"".$tab[0]."\"></td>
    </tr>
    <tr>
      <td align=right width=183>" . $lang['description'] . ":</td>
      <td width=167><textarea rows=2 name=opis cols=20>".$tab[2]."</textarea></td>
    </tr>
    <tr>
      <td align=right width=183>" . $lang['bannerurl'] . ":</td>
      <td width=167><input type=text name=banner size=20 value=".$tab[3]."></td>
    </tr>
    <tr>
      <td align=right width=183>" . $lang['yourmail'] . ":</td>
      <td width=167><input type=text name=email size=20 value=".$tab[4]."></td>
    </tr>
    <tr>
      <td align=right width=183>" . $lang['url'] . ":</td>
      <td width=167><input type=text name=url size=20 value=".$tab[1]."></td>
    </tr>
    <td align=right width=183>" . $lang['category'] . ":</td>
    <td width=167><select name=category>";
        $wyn = "SELECT * FROM toplista_categories";
        $wykonaj = mysql_query($wyn);
        $znaleziono = mysql_num_rows($wykonaj);
        while($tab2 = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
        echo "<option value=" . $tab2[1] . " ";
        if($tab[13] == $tab2[1]){ echo "selected"; }
        echo ">" . $tab2[0] . "</option>";
        }
    echo "</select></td></tr>
    <tr>
      <td align=right width=183>" . $lang['newpass'] . ":</td>
      <td width=167><input type=password name=newpass size=20></td>
    </tr>
    <tr>
      <td align=right width=183>" . $lang['newpassr'] . ":</td>
      <td width=167><input type=password name=newpassr size=20></td>
    </tr>
    <tr>
      <td align=right width=350 colspan=2>
      <input type=hidden name=password value=$password>
      <p align=center><input type=submit value=" . $lang['submit'] . " name=B1></td>
    </tr>
  </table>
  </center>
  </form>
</div>";
} else {
    echo "<center>" . $lang['badpass'] . "";
    die();
};
};

if($step == "3"){
include "style/" . $option['style'] . ".php";
include "header.php";
if($nazwa == ""){
echo "<center><br><br>" . $lang['error_forgot'];
die();
}

if($url == ""){
echo "<center><br><br>" . $lang['error_forgot'];
die();
}

if($opis == ""){
echo "<center><br><br>" . $lang['error_forgot'];
die();
}

if($email == ""){
echo "<center><br><br>" . $lang['error_forgot'];
die();
}

// checking size
if($function['checkingsize'] == "1"){
        if($banner <> ""){
                $imagesize = getimagesize($banner);

                $width = $imagesize[0];
                $height = $imagesize[1];

                if($width > $option['maxwidth']){
                echo $lang['bannerbig'];
                exit();
        }

        if($height > $option['maxheight']){
                echo $lang['bannerbig'];
                exit();
        }
}
}
// EOF
$wyn = "SELECT * FROM toplista WHERE user='$usernamecookie'";
$wykonaj = mysql_query($wyn);
$tab = mysql_fetch_row($wykonaj);
if($editcookiepass == $tab[9]){
$nazwa = mysql_escape_string($nazwa);
$opis = mysql_escape_string($opis);
$c = "UPDATE toplista SET nazwa='$nazwa' WHERE user='$usernamecookie'";
$cc = mysql_query($c);

$c = "UPDATE toplista SET url='$url' WHERE user='$usernamecookie'";
$cc = mysql_query($c);

$c = "UPDATE toplista SET opis='$opis' WHERE user='$usernamecookie'";
$cc = mysql_query($c);

$c = "UPDATE toplista SET banner='$banner' WHERE user='$usernamecookie'";
$cc = mysql_query($c);

$c = "UPDATE toplista SET email='$email' WHERE user='$usernamecookie'";
$cc = mysql_query($c);

$c = "UPDATE toplista SET category='$category' WHERE user='$usernamecookie'";
$cc = mysql_query($c);

if($newpass <> ""){
        if($newpass == $newpassr){
        $newpass = md5($newpass);
        $c = "UPDATE toplista SET haslo='$newpass' WHERE user='$usernamecookie'";
        $cc = mysql_query($c);
        } else {
        echo "<center>" . $lang['passmatch'];
        exit();
        }

}

echo "<center>" . $lang['editdone'] . "";
die();
};
};
include "footer.php";
?>

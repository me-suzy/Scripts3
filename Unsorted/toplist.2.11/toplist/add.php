<?

//////////////////////////// BEST TOP LIST by Szymon Kosok (c) 2002 /////////////////////////////////
//                                         Add a site function                                                      //
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

include "config.php";
include "db/db.php";
db_options();
banned(1);
include "languages/$language.php";
include "style/" . $option['style'] . ".php";
include "header.php";

echo "<p align=center>" . $option['nameoflist'] . "</p>
          <p align=center>" . $lang['addasite'] . ":</p>";

if($step == "2"){

        if($category == ""){
        $category = "1";
        }
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

        if($username == ""){
        echo "<center><br><br>" . $lang['error_forgot'];
        die();
        }

        if($haslo == ""){
        echo "<center><br><br>" . $lang['error_forgot'];
        die();
        }

        $wyn = "SELECT * FROM toplista WHERE user='$username'";
        $wykonaj = mysql_query($wyn);
        $znaleziono = mysql_num_rows($wykonaj);
        if($znaleziono <> "0"){
        echo "<center><br>". $lang['userexist'] . "</center>";
        exit();
        }
// checking banner size
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


if($function['autoaccept'] == "1"){
        $mdhaslo = md5($haslo);
        $nazwa = mysql_escape_string($nazwa);
        $opis = mysql_escape_string($opis);
        $wpisz = "INSERT INTO toplista VALUES('$nazwa', '$url', '$opis', '$banner', '$email', '0', '0', '$id', 'y', '$mdhaslo', '0', '0', '$username', '$category', '$date', '$date')";
        $wpiszb = mysql_query($wpisz) or die($lang['error_db_hou']);
        $wyn = "SELECT * FROM toplista WHERE user='$username'";
        $wykonaj = mysql_query($wyn);
        $tab = mysql_fetch_row($wykonaj);
        mail($email,$lang['mail_title1'],$lang['mail_bodyacc1'] . $tab[7] . $lang['mail_bodyacc2'],"From: " . $option['adminmail']);
        mail($option['adminmail'],$lang['notifsub'],$lang['notifbody'], "From: " . $email);
        echo "<center>" . $lang['site_addedacc1'] . $tab[7] . $lang['site_addedacc2'] . "</b><br><br>";
        } else {
        $mdhaslo = md5($haslo);
        $nazwa = mysql_escape_string($nazwa);
        $opis = mysql_escape_string($opis);
        $date = time();
        $wpisz = "INSERT INTO toplista VALUES('$nazwa', '$url', '$opis', '$banner', '$email', '0', '0', '$id', 'n', '$mdhaslo', '0', '0', '$username', '$category', '$date', '$date')";
        $wpiszb = mysql_query($wpisz) or die($lang['error_db_hou']);
        $wyn = "SELECT * FROM toplista WHERE user='$username'";
        $wykonaj = mysql_query($wyn);
        $tab = mysql_fetch_row($wykonaj);
        mail($email,$lang['mail_title1'],$lang['mail_body1'] . $tab[7] . $lang['mail_body2'],"From: " . $option['adminmail']);
        mail($option['adminmail'],$lang['notifsub'],$lang['notifbody'], "From: " . $email);
        echo "<center>" . $lang['site_added1'] . $tab[7] . $lang['site_added2'] . "</b><br><br>";
        }
} else {
        echo "<div align=center>
        <center>
        <form method=POST action=add.php?step=2>
        <table border=0 cellpadding=5 cellspacing=0 style=border-collapse: collapse bordercolor=#111111 width=350>
        <tr>
        <td align=right width=183>" . $lang['sitename'] . ":</td>
        <td width=167><input type=text name=nazwa size=20></td>
        </tr>
        <tr>
        <td align=right width=183>" . $lang['description'] . ":</td>
        <td width=167><textarea rows=2 name=opis cols=20></textarea></td>
        </tr>
        <tr>
        <td align=right width=183>" . $lang['bannerurl'] . ":</td>
        <td width=167><input type=text name=banner size=20></td>
        </tr>
        <tr>
        <td align=right width=183>" . $lang['yourmail'] . ":</td>
        <td width=167><input type=text name=email size=20></td>
        </tr>
        <tr>
        <td align=right width=183>" . $lang['siteurl'] . ":</td>
        <td width=167><input type=text name=url size=20 value=http://></td>
        </tr>
        <tr>
        <td align=right width=183>" . $lang['user'] . ":</td>
        <td width=167><input type=text name=username size=20></td>
        </tr>
        <tr>
        <td align=right width=183>" . $lang['password'] . ":</td>
        <td width=167><input type=password name=haslo size=20></td>
        </tr>";
        if($function['categories'] == "1"){
        echo "<tr>
        <td align=right width=183>" . $lang['category'] . ":</td>
        <td width=167><select name=category>";
        $wyn = "SELECT * FROM toplista_categories";
        $wykonaj = mysql_query($wyn);
        $znaleziono = mysql_num_rows($wykonaj);
        while($tab = mysql_fetch_row($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
        echo "<option value=" . $tab[1] . ">" . $tab[0] . "</option>";
        }
        echo "</select></td></tr>";
        }

        echo "<tr>
        <td align=right width=350 colspan=2>
        <p align=center><input type=submit value=" . $lang['submit'] . " name=B1></td>
        </tr>
        </table>
        </center>
        </form>
        </div>";
};

include "footer.php";

?>


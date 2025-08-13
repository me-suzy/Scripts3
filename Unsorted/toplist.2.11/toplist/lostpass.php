<?
include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";
include "style/" . $option['style'] . ".php";
include "header.php";
if($action == ""){
    echo "<p align=center>" . $lang['lp'] . ":</p><br><center><form method=POST action=lostpass.php?action=go>
  <table border=0 cellpadding=5 cellspacing=0 style=border-collapse: collapse bordercolor=#111111 width=350>
    <tr>
      <td align=right width=183>" . $lang['user'] . ":</td>
      <td width=167><input type=text name=user size=20></td>
    </tr>
    <tr>
      <td align=right width=350 colspan=2>
      <p align=center><input type=submit value=" . $lang['submit'] . " name=B1></td>
    </tr>
  </table>
  </center>
  </form>";
};

if($action == "go"){

    srand((double)microtime()*1000000);
    $newpass = rand();
    $mdpass = md5($newpass);
    $wyn = "UPDATE toplista SET haslo='$mdpass' WHERE user='$user'";
    $wyn2 = mysql_query($wyn);
    $mailik = "SELECT * FROM toplista WHERE user='$user'";
    $mailik2 = mysql_query($mailik);
    $tab = mysql_fetch_row($mailik2);
    mail($tab[4],$lang['lp'] . "!",$lang['lpmail'] . $newpass . $lang['lpmail2']);
    echo "<center>". $lang['lpmailed'];
};
include "footer.php";
?>
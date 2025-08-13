<?
if($aid == "yes"){
$cookie = $HTTP_COOKIE_VARS["toplistatellfrienda"];
if($cookie <> ""){
include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";
include "style/" . $option['style'] . ".php";
include "header.php";
echo "<center>" . $lang['antispam'];
die();
};
      if (!isset($cookie)) {
		srand((double)microtime()*1000000);
		$randval = rand();
		setcookie("toplistatellfrienda",$randval,time()+$option['cookietime'],"/","." . $option['cookiedomain'] . "",0);
	}
};
 ?>
 <?

 if($send == ""){

include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";
include "style/" . $option['style'] . ".php";
include "header.php";
echo "<p align=center>Tell a friend:</p>
<center><form method=POST action=tell.php?send=yes&aid=yes>
  <table border=0 cellpadding=5 cellspacing=0 style=border-collapse: collapse bordercolor=#111111 width=350>
    <tr>
      <td align=right width=183>" . $lang['tfym'] . ":</td>
      <td width=167><input type=text name=mail1 size=20></td>
    </tr>
    <tr>
      <td align=right width=183>" . $lang['tffm'] . ":</td>
      <td width=167><input type=text name=mail2 size=20></td>
    </tr>
        <tr>
      <td align=right width=350 colspan=2>
      <p align=center><input type=submit value=" . $lang['submit'] . "></td>
    </tr>
  </table>
  </center>
  </form>";
};

  if($send == "yes"){
  include "config.php";
  include "db/db.php";
  db_options();
  include "languages/$language.php";
  include "style/" . $option['style'] . ".php";
  include "header.php";
        mail($mail2,"" . $lang['invite'] . "!","" . $lang['tfymtxt'] . "","From: " . $mail1);
      echo "<p align=center>" . $lang['tf'] . ":</p><center><br><br><b>" . $lang['tfyms'] . "";
  };

  include "footer.php";
  ?>


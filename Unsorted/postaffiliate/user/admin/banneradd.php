<?
  session_start();

  include "../../affconfig.php";
  include "../lang/$language";

  if(!aff_admin_check_security())
  {
    aff_redirect('index.php');
    exit;
  }
  
  if($_POST['commited'] == 'yes')
  {
	  mysql_connect($server, $db_user, $db_pass) 
      or die ("Database CONNECT Error (line 11)"); 
	  
	  mysql_db_query($database, "INSERT INTO banners VALUES ('', '".$_POST['bannername']."', '".$_POST['bannerurl']."', '".$_POST['bannerdesc']."')") 
      or die("Database INSERT Error"); 
      
    aff_redirect('banners.php');
  }
  
  include "header.php"; 
?>
  <br>
  <p align=center><?=AFF_B_ADDBANNER?></p>
  <form method=post action=banneradd.php><table width=500 border=0 cellspacing=0 cellpadding=0>
    <tr valign=top> 
      <td><font face=Arial, Helvetica, sans-serif size=2><?=AFF_B_BANNERNAME?></font></td>
      <td> <font face=Arial, Helvetica, sans-serif size=2> 
        <input type=text name=bannername size=40>
        </font></td>
    </tr>
    <tr valign=top> 
      <td><font face=Arial, Helvetica, sans-serif size=2><?=AFF_B_BANNERURL?></font></td>
      <td><font face=Arial, Helvetica, sans-serif size=1>http://<?=$domain?>/images/</font><font face=Arial, Helvetica, sans-serif size=2> 
        <input type=text name=bannerurl value=image.gif>
        </font></td>
    </tr>
    <tr valign=top> 
      <td><font face=Arial, Helvetica, sans-serif size=2><?=AFF_B_BANNERDESCRIPTION?></font></td>
      <td> <font face=Arial, Helvetica, sans-serif size=2> 
        <textarea name=bannerdesc cols=40 rows=6></textarea>
        </font></td>
    </tr>
    <tr valign=top> 
      <td><font face=Arial, Helvetica, sans-serif size=2></font></td>
      <td><font face=Arial, Helvetica, sans-serif size=2></font></td>
    </tr>
    <tr valign=top> 
      <td colspan=2>
        <div align=center><font face=Arial, Helvetica, sans-serif size=2></font><font face=Arial, Helvetica, sans-serif size=2>
          <input type=hidden name=commited value=yes>
          <input type=submit name=Submit value=Add&nbsp;Banner&nbsp;Info>
          </font></div>
      </td>
    </tr>
    <tr valign=top> 
      <td><font face=Arial, Helvetica, sans-serif size=2></font></td>
      <td><font face=Arial, Helvetica, sans-serif size=2></font></td>
    </tr>
  </table></form>
    	
<? include "footer.php"; ?>

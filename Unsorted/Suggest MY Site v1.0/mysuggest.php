<?
/*
MY SITE SUGGESTION Version 1.00
Copyright (C) 2001 Mert Yaldýz - mert@myphpscripts.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

include("inc/header.php");
include("inc/config.inc.php");
// check the language pack
if($l=="t")
{
	include("inc/turk.lang.inc.php");
}
elseif($l=="e")
{
	include("inc/eng.lang.inc.php");
}


// check the form submission
if(isset($submit))
{

function msg($messagetype,$m)
{
	global $l,$goback;
	?>
		<br class="bodytext"><table width="50%" align="center" class="bodytext" cellspacing="0" cellpadding="2">
<tr bgcolor="ffcc99">
	<td><span class="headerred"><? echo $messagetype ?></span></td>
</tr>
<tr bgcolor="ffffcc">
	<td>
	<?
	echo "<li>".$m."</li><P>";
	echo"<center><br><input type=\"button\" value=\"".$goback."\" class=\"button\" onclick=\"parent.location='javascript:history.go(-1)'\"></center>  ";
	?></td>
</tr>
<tr bgcolor="ffcc99">
	<td></td>
</tr>
</table>
		<p>
		          <center class="bodytextblue">
                MY SITE SUGGESTION v1.0 <br>By MERT YALDIZ<br><a href="http://www.myphpscripts.com">www.myphpscripts.com</a>
              </center>
	<?
	exit;
}
/*==========================================*/
/* YOU DO NOT NEED TO MODIFY ANYTHING BELOW */
/*==========================================*/

// Checks the form submission

/*---------------------------------------------------------------------------------------*/
/* function checks the empty fields */
function check_fields($field,$fieldname,$errormessage)
{
         if (empty ($field))
                 {
                 msg("$errormessage","$fieldname");
                 
                 }
}

check_fields ("$sname","$yourname","$emptyfield");
check_fields ("$rname","$recipientname","$emptyfield");


/* Function checks the validity of the email addresses */
function check_email($email,$mailtype,$errmessage)
{
global $emptymail1,$emptymail2;
         if (!empty($email))
         {
         $mail_test= trim($email);
                 // this line does not belong to me. Thanks to person who wrote this !!
                 if (!eregi("^[_\\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\\.)+[a-z]{2,3}$", $mail_test))
                   {
                   msg( "$errmessage","$mail_test");
                   
                   }
         }
         else
         {
         msg( "$emptymail1","$mailtype");
         
         }
}

check_email("$smail","$yourmail","$invalidmail");
check_email("$rmail","$recipientmail","$invalidmail");

/* building the mail body */
$mailbody="$suggestionfor ".$rname."\n\n";
$mailbody.=$siteownermessage."\n";
$mailbody.=$url."\n\n";
$mailbody.="$from   ".$sname."\n";
$mailbody.="$sendersmail  ".$smail."\n\n";

if (!empty($message))
         {
         $mailbody.=$sname." $wrote\n";
         $mailbody.="-----------------------------------\n";
         $mailbody.=$message;
         }
$mailbody.="\n-----------------------------------\n";
$mailbody.=$author;

$mailheaders="From: $sitename<$adminmail>\n";

/* building the body of the mail for the sender */
if ($mailtosender=="1")
         {
         $smailbody=$sname.",\n";
         $smailbody.=$thankyoumessage."\n";
         $smailbody.="\n-----------------------------------\n";
         $smailbody.=$author;
         $smailheaders="From: $sitename<$adminmail>\n";
         mail($smail,$thankyousubject,$smailbody,$smailheaders);
         }
/* sends the mail */
if (@mail($rmail,$mailsubject,$mailbody,$mailheaders))
         {
         msg( "$mailsent","$rmail");
         }
         else
         {
         msg ("$error","$mailproblem");
         }
// displays the form if no submission
}
else
{
	?>
<form  method="post" action="suggest.php">
<input type="hidden" name="l" value="<? echo $l ?>">
  <table width="100%" border="0" cellspacing="0" cellpadding="20">
    <tr>
      <td>
        <table width="500" border="0" cellspacing="0" cellpadding="5" class="bodytextblue">
          <tr> 
            <td colspan="2"> <b> </b> 
              <p><b><font color="#FF0000">
                <? echo $suggest_header ?>
                </font></b> 
            </td>
          </tr>
          <tr> 
            <td colspan="2" height="2" bgcolor="009966"	></td>
          </tr>
          <tr> 
            <td colspan="2"> <b><font color="#FF0000"> </font> 
              <? echo $suggest_exp ?>
              <font color="#FF0000"> </font></b> <p></td>
          </tr>
          <tr> 
            <td width="137"> <b> 
              <? echo $yourname ?>
              : </b></td>
            <td width="277"> 
              <input type="text" name="sname" size="40">
            </td>
          </tr>
          <tr> 
            <td width="137"> <b> 
              <? echo $yourmail ?>
              : </b></td>
            <td width="277"> 
              <input type="text" name="smail" size="40">
            </td>
          </tr>
          <tr> 
            <td width="137"> <b> 
              <? echo $recipientname ?>
              : </b></td>
            <td width="277"> 
              <input type="text" name="rname" size="40">
            </td>
          </tr>
          <tr> 
            <td width="137"> <b> 
              <? echo $recipientmail ?>
              : </b></td>
            <td width="277"> 
              <input type="text" name="rmail" size="40">
            </td>
          </tr>
          <tr> 
            <td width="137"> <b> 
              <? echo $yourmessage ?>
              : </b></td>
            <td width="277"> 
              <textarea name="message" cols="32" rows="3"></textarea>
            </td>
          </tr>
          <tr> 
            <td width="137">&nbsp;</td>
            <td width="277"> 
              <div align="left"> 
                <input type="submit" name="submit" value="<? echo $submitbutton ?>" class="button">
              </div>
            </td>
          </tr>
          <tr> 
            <td colspan="2">
              	<hr class="hrstyle">
		          <center>
                MY SITE SUGGESTION v1.0 <br>By MERT YALDIZ<br><a href="http://www.myphpscripts.com">www.myphpscripts.com</a>
              </center>
            </td>
          </tr>
        </table>
	
      </td>
    </tr>
  </table>
  </form>
<? 
}
include("inc/footer.php");
?>
<form method="post" action="<?php echo $PHP_SELF?>">
<input type="hidden" name="userid" value="<?php echo $userid ?>">
<input type="hidden" name="mode" value="<?php echo $mode ?>">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="50%" valign="top"> <? echo $add_user_name ?> </td>
    <td width="50%" valign="top">
<input class="txt" type="text" name="name" size="38" value="<?php print("$name"); ?>">
<font color="#FF0000">* 

      

    </td>
  </tr>
  <tr>
    <td width="50%" valign="top"> <? echo $add_user_adressfield1 ?> </td>
    <td width="50%" valign="top">
<input type="text" name="adressfield1" size="38" class="txt" value="<?php echo $adressfield1 ?>">


      

    </td>
  </tr>
  <tr>
    <td width="50%" valign="top"> <? echo $add_user_adressfield2 ?> </td>
    <td width="50%" valign="top">
<input type="text" name="adressfield2" size="38" class="txt" value="<?php echo $adressfield2 ?>">


      

    </td>
  </tr>
  
  <tr>
    <td width="50%" valign="top"> <? echo $add_user_phone ?> </td>
    <td width="50%" valign="top">
<input type="text" name="phone" size="38" class="txt" value="<?php echo $phone ?>">


      

    </td>
  </tr>

  <tr>
    <td width="50%" valign="top"> <? echo $add_user_email ?> </td>
    <td width="50%" valign="top">
<input type="text" name="email" size="38" class="txt" value="<?php echo $email ?>">
<font color="#FF0000">*  

      

    </td>
  </tr>
<?  
if (!$mode)
{
?>	
	<tr>
    <td width="50%" valign="top"> <? echo $add_user_pass ?> </td>
    <td width="50%" valign="top">
		<input type="text" name="pass" size="15" class="txt">
		<font color="#FF0000">*  
    </td>
  </tr>
	
	<tr>
    <td width="50%" valign="top"> Type password again </td>
    <td width="50%" valign="top">
		<input type="text" name="pass_two" size="15" class="txt">
		<font color="#FF0000">*  
		</td>
  </tr>
<?
}
?>	
	
</table>
   
<input type="submit" name="submit" class="submit" value="<?php echo $submit_button ?>">
<p>
 <input type="checkbox" name="emelding" value="1"><? echo $la_no_email_please ?> <br />
 <input type="checkbox" name="hide_email" value="1"><? echo $la_hide_email ?> 
</p>

<br />
<a href="javascript:openWin('conditions.php')"><? echo $la_conditions ?></a>
</form>

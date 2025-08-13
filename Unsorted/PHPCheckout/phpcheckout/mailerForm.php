
<form name="mailNotesToSubscribersForm" method="post" action="<?php echo PHP_SELF;?>">
<table class="form" border="0">
   <tr>
      <th colspan="2">phpcheckout Mailer</th>
   </tr>
   <tr>
      <td>Mode</td>
      <td>
         <input type="radio" name="mode" value="Test" CHECKED> Test 
         <input type="radio" name="mode" value="Live"> Live 
      </td>
   </tr>
      <td>&nbsp;</td>
      <td>
         Copy to 
         <input type="text" name="extraRecipient" size=40 value="<?php echo TECHNICALSUPPORT;?>"> 
      </td>
   </tr>

   <tr>
      <td>Subject:</td>
      <td>
         <input type="text" name="subject" value="<?php echo COMPANY;?>" size="80" maxlength="80">
      </td>
   </tr>
   <tr>
      <td>Comments:</td>
      <td>
         <textarea name="comments" cols="70" rows="15" wrap="soft">
Hello,
You are receiving this note because you voluntarily subscribed to our newsletter. Unsubscribe details are at the bottom of this note. The remainder of this note provides information and news you requested.

         </textarea>
      </td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td><input class="input" type="submit" name="submit" value="Launch Newsletter Mailer">
  	       <input type="hidden" name="task" value="Launch Newsletter Mailer">
	       <input type="hidden" name="formuser" value="<?php echo ADMINUSER;?>">
	       <input type="hidden" name="formpassword" value="<?php echo ADMINPASSWORD;?>">	
          <input type="reset" name="reset">
      </td>
   </tr>
</table>
</form>
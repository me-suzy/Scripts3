<?

function getfield($type,$fieldname,$filen,$length,$mand,$value)
{
 if ($type == 'Option')
 {
        $options = file("options/$filen");
        $num_options =  count($options);
        for ($i=0; $i<$num_options; $i++)
        {

                print "<input type='radio' value='$options[$i]' name='$fieldname'";
                if (trim($value) == trim($options[$i])) { print " checked";  }
                print " />$options[$i]";
                print "&nbsp;&nbsp;&nbsp;";
        }
 }
 if ($type == 'Text')
 {
         print("<input type=\"text\" name=\"$fieldname\" size=\"$length\" maxlength=\"$length\" class='txt' value='$value' />");
 }

 if ($type == 'Textarea')
 {
         $cols = $length - 1;
         print("<textarea rows=\"4\" cols=\"30\" name=\"$fieldname\" maxlength=\"$length\" class='txt'\">$fieldid[$field]</textarea>");
 }


 if ($type == 'Checkbox')
 {
        $options = file("options/$filen");
        $num_options =  count($options);
        for ($i=0; $i<$num_options; $i++)
        {
				$c++;
                print "<input type='checkbox' value='$options[$i]' name='$fieldname" . "[]'";
                if (preg_match("/$options[$i]/", "$value")) { print " checked";  } print ">$options[$i]</option>";
                print "&nbsp;&nbsp;&nbsp;";

        }
 }



 if ($type == 'Dropdown')
 {
  		$options = file("options/$filen");
        $num_options =  count($options);
		
        
        
        print "\n\n<select size='1' name='$fieldname'><option value='$toptions[$i]' selected='selected'>$options[$i]</option>";
        for ($i=0; $i<$num_options; $i++)
        {
            $options[$i] = trim($options[$i]);
        	if ($value == $options[$i])
            {    
        		print "<option value='$options[$i]' selected='selected'>$options[$i]</option>";
            }
            else
            {    
        		print "<option value='$options[$i]'>$options[$i]</option>";
            }
            
        }
        print "</select>&nbsp;&nbsp;&nbsp;";

 }
}
?> 

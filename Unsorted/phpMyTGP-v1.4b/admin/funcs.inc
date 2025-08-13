<?
// DON'T CHANGE ANYTHING BELOW THIS LINE !!!!
//------------------------------------------------------------------------------
//                       0           1             2                3                  4           5                  6             7           8               9
$templates_all = array("new.tpl", "default.tpl", "terms.tpl", "categories.tpl", "mailout.tpl", "mailwelcome.tpl", "post.tpl", "link.tpl", "maildelete.tpl", "vip.tpl");

function show_error_msg ($msg)
{
 echo "<p align=center><font color=Red><b>ERROR!</b></font><br>\n";
 echo $msg."<br><a href=javascript:history.go(-1);>Back</a></p>";
}

function update_lastupdate ()
{
// update time in lastupdate
   global $sql_uin;
   $settime = time();
   $query = "update mytgp_set set LASTUPDATE='$settime' where UIN='$sql_uin'";
   $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
   return $result;
}

function unix_to_date ($time, $format = 1)
//$format: 1 - Day-Mon-YEAR, 2 - Day-Mon-Year, HH:MM:SS
{
    if ($format == 2) return $time = date("d-M-Y, h:i:s",$time);
    else return $time = date("d-M-Y",$time);
}

$phpMyTGP_ver = "v0.4";
$phpMyTGP_link = "Powered by <a target=_blank href=http://www.adultbussiness.com/scripts/>phpMyTGP</a> ".$phpMyTGP_ver;
$phpMyTGP_Author_mail = "phpmytgp@adultbussiness.com";

function build_template_link ($instring, $link, $viptpl)
// $instring - line from templates link.tpl with template fields
// $link - array ["date", "images", "url", "desc", "catname", "postvip"]
//                  0   ,     1   ,   2  ,    3  ,     4    ,     5    
{
   global $msetstr;
   $instring = str_replace ("<!--date-->", unix_to_date($link[0]) ,$instring);
   $instring = str_replace ("<!--images-->", $link[1] ,$instring);
   $instring = str_replace ("<!--url-->", $link[2] ,$instring);
   $instring = str_replace ("<!--desc-->", $link[3] ,$instring);
   $instring = str_replace ("<!--catname-->", $link[4] ,$instring);
   if (($msetstr["USEVIP"]=="YES")&&($link[5]=="YES"))
   $instring = str_replace ("<!--vip-->", $viptpl ,$instring);
   else    $instring = str_replace ("<!--vip-->", "" ,$instring);

return $instring;
}

function build_template_join ($instring)
{
   global $msetstr;
   $instring = str_replace ("<!--join-->", "<a href='".$msetstr["MAINURL"]."add.php'>Add gallery</a>" ,$instring);
return $instring;
}

function build_template_terms ($instring)
{
   global $msetstr;
   $instring = str_replace ("<!--terms-->", "<a href='".$msetstr["MAINURL"]."add.php?action=Agree'>Add gallery</a>" ,$instring);
return $instring;
}

function build_template_cats ($instring)
{
   global $msetstr, $global_extension, $link, $sql_uin;
   $navbox = "";
   $navbox .= "<SCRIPT type=\"text/javascript\">\n";
   $navbox .= "function GoUrl(s)\n";
   $navbox .= "{       var d = s.options[s.selectedIndex].value\n";
   $navbox .= "  window.top.location.href = d\n";
   $navbox .= "    s.selectedIndex=0\n";
   $navbox .= "}\n";
   $navbox .= "</script>\n";
   $navbox .= "<FORM>\n";
   $navbox .= "<SELECT NAME=target onchange=\"f=0;GoUrl(this);return true;\">\n";
   $navbox .= "<OPTION VALUE= SELECTED>---> S E L E C T     C A T E G O R Y <---</OPTION>\n";
   $navbox .= "<OPTION VALUE=".$msetstr["TGPMAINURL"].">TOP OVERALL</OPTION>\n";
   $query = "select * from mytgp_cats where UIN='$sql_uin' order by NAME";
   $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_array($result))
      {
       $name = strtolower($row["NAME"]);
       $navbox .= "<option value=".$name.".".$global_extension.">".$row["NAME"]."</option>\n";
      }
   $navbox .= "</select></form>\n";
  $instring = str_replace ("<!--navbox-->", $navbox ,$instring);
  mysql_free_result ($result);
  return $instring;
}

function build_template_list ($where, $instring)
// $where - ALL or category name
{
    global $msetstr, $link, $templates_all, $sql_uin;
    if ($file = fopen($msetstr['PROGSDIR']."templates/".$templates_all[7],"r"))
      {
      $template_link = "";
      while (!feof($file)) 
       {
       $buffer = fgets($file, 4096);
       $template_link .= $buffer;
       }
      fclose($file);
      } else show_error_msg ("Can't Open template file: ".$templates_all[7]);
    if ($msetstr["USEVIP"]=="YES")
     if ($file = fopen($msetstr['PROGSDIR']."templates/".$templates_all[9],"r"))
      {
      $viptpl = "";
      while (!feof($file)) 
       {
       $buffer = fgets($file, 4096);
       $viptpl .= $buffer;
       }
      fclose($file);
      } else show_error_msg ("Can't Open template file: ".$templates_all[9]);
    if ($where=="ALL") $query = "select POSTDATE,POSTNUM,POSTURL,POSTDESCR,POSTCAT,POSTVIP from mytgp_post where VALIDATED='YES' and UIN='$sql_uin' order by ";
    else $query = "select POSTDATE,POSTNUM,POSTURL,POSTDESCR,POSTCAT,POSTVIP from mytgp_post where VALIDATED='YES' and POSTCAT='$where' and UIN='$sql_uin' order by ";

    if (($msetstr["USEVIP"]=="YES")&&($msetstr["USEVIPFIRST"]=="YES")) $query.= "POSTVIP DESC, ";
    $query.="POSTDATE DESC";

    $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
    $col=0; $template_list="";
    while (($row = mysql_fetch_row($result)) and ($col<$msetstr["MAXLIST"]))
      {
      $col++;
      $template_list .= build_template_link ($template_link, $row, $viptpl)."\n";
      }
   $instring = str_replace ("<!--list-->", $template_list ,$instring);
   mysql_free_result ($result);
   return $instring;
}

function build_template_list_category ($instring)
// template example: <!--list|AMATEURS|10|-->
{
    global $msetstr, $link, $templates_all, $sql_uin;
    // Find '<!--list' in $instring
    $first = strpos($instring, "<!--list|");
    if ($last = strpos($instring, "|-->", $first+9))
      {
       $len = $last - $first;
       // $temp_str = AMATEURS|10
       $temp_str = substr ($instring, $first+9, $len-9);
       list ($cat_name,$num_col) = explode ('|', $temp_str);
       // replace <!--list|AMATEURS|10|--> to <!--list||-->
       $len = strlen($cat_name.$num_col)+2;
       $instring = substr_replace ($instring, '', $first+9, $len);
		// open link.tpl
	    if ($file = fopen($msetstr['PROGSDIR']."templates/".$templates_all[7],"r"))
	      {
	      $template_link = "";
	      while (!feof($file)) 
	       {
	       $buffer = fgets($file, 4096);
	       $template_link .= $buffer;
	       }
	      fclose($file);
	      } else show_error_msg ("Can't Open template file: ".$templates_all[7]);
    if ($msetstr["USEVIP"]=="YES")
     if ($file = fopen($msetstr['PROGSDIR']."templates/".$templates_all[9],"r"))
      {
      $viptpl = "";
      while (!feof($file)) 
       {
       $buffer = fgets($file, 4096);
       $viptpl .= $buffer;
       }
      fclose($file);
      } else show_error_msg ("Can't Open template file: ".$templates_all[9]);

	    $query = "select POSTDATE,POSTNUM,POSTURL,POSTDESCR,POSTCAT,POSTVIP from mytgp_post where VALIDATED='YES' and POSTCAT='$cat_name' and UIN='$sql_uin' order by ";
	    if (($msetstr["USEVIP"]=="YES")&&($msetstr["USEVIPFIRST"]=="YES")) $query.= "POSTVIP DESC, ";
	    $query.="POSTDATE DESC";

	    $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
	    $col=0; $template_list="";
	    while (($row = mysql_fetch_row($result)) and ($col<$num_col))
	      {
	      $col++;
	      $template_list .= build_template_link ($template_link, $row, $viptpl)."\n";
	      }

	   $out_string = str_replace ("<!--list|-->", $template_list , $instring);
	   mysql_free_result ($result);
	   return $out_string;
      }
}

function update_templates ()
{
global $templates_all, $msetstr, $global_extension, $phpMyTGP_link, $sql_uin;
// BUILD MAININDEX
// Open default.tpl
//    echo "Open <b>".$templates_all[1]."</b>.<br>";
    if ($file = fopen($msetstr['PROGSDIR']."templates/".$templates_all[1],"r"))
      {
      $template_default = "";
      while (!feof($file)) $template_default .= fgets($file, 4096);
      fclose($file);
      } else show_error_msg ("Can't Open template file: ".$templates_all[1]);
   while (strpos($template_default, "!--list|")) $template_default = build_template_list_category($template_default);

   if (strpos($template_default, "!--navbox-->")) $template_default = build_template_cats($template_default);   
   if (strpos($template_default, "!--terms-->")) $template_default = build_template_terms($template_default);
   if (strpos($template_default, "!--join-->")) $template_default = build_template_join($template_default);
   if (strpos($template_default, "!--list-->")) $template_default = build_template_list("ALL",$template_default);
  
   $query = "select POSTDATE,POSTNUM,POSTURL,POSTDESCR,POSTCAT,POSTVIP from mytgp_post where VALIDATED='YES' and UIN='$sql_uin' order by POSTDATE DESC";
   $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
    if ($file = fopen($msetstr['PROGSDIR']."templates/".$templates_all[7],"r"))
      {
      $template_link = "";
      while (!feof($file)) 
       {
       $buffer = fgets($file, 4096);
       $template_link .= $buffer;
       }
      fclose($file);
      } else show_error_msg ("Can't Open template file: ".$templates_all[7]);

    if ($msetstr["USEVIP"]=="YES")
     if ($file = fopen($msetstr['PROGSDIR']."templates/".$templates_all[9],"r"))
      {
      $viptpl = "";
      while (!feof($file)) 
       {
       $buffer = fgets($file, 4096);
       $viptpl .= $buffer;
       }
      fclose($file);
      } else show_error_msg ("Can't Open template file: ".$templates_all[9]);

   $firstpos = strpos($template_default,"<!--link-->");
   while (($row = mysql_fetch_array($result)) and ($firstpos !== FALSE))
   {
        $temp = build_template_link ($template_link, $row, $viptpl);
        $template_default = substr_replace($template_default, $temp, $firstpos, strlen("<!--link-->") );
        $firstpos = strpos($template_default,"<!--link-->");
   }
   $template_default .= "<BR><BR>".$phpMyTGP_link;
     if ($file = fopen( $msetstr["TGPMAINDIR"].$msetstr["TGPMAINFILE"], "w+" ))
     {
     $len_gen = strlen( $template_default );
     fwrite( $file, StripSlashes($template_default), $len_gen);
     fclose( $file );
     } else show_error_msg ("Can't write to template file: ".$templates_all[1]);
//     echo $len_gen." bytes write success.<br>";
  mysql_free_result($result);


// BUILD CATEGORY
// Open categories.tpl
//    echo "Open <b>".$templates_all[3]."</b>.<br>";
    if ($file = @fopen($msetstr['PROGSDIR']."templates/".$templates_all[3],"r"))
      {
      $template_cat = "";
      while (!feof($file)) 
       {
       $buffer = fgets($file, 4096);
       $template_cat .= $buffer;
       }
      fclose($file);
      } else show_error_msg ("Can't Open template file: ".$templates_all[3]);

   $query = "select NAME from mytgp_cats where UIN='$sql_uin' order by NAME";
   $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");

while ($row = mysql_fetch_array($result))
{
    $name = strtolower($row["NAME"]);
   //if exist category with same name
    if ($file = @fopen($msetstr['PROGSDIR']."templates/".$name.".tpl","r"))
      {
      $template_categories = "";
      while (!feof($file)) 
       {
       $buffer = fgets($file, 4096);
       $template_categories .= $buffer;
       }
      fclose($file);
     } else $template_categories = $template_cat;

   $template_categories = build_template_cats($template_categories);   
   $template_categories = build_template_terms($template_categories);
   $template_categories = build_template_join($template_categories);
   $template_categories = build_template_list($name,$template_categories);
   $template_categories = str_replace("<!--catname-->", $row["NAME"], $template_categories);

   $query1 = "select POSTDATE,POSTNUM,POSTURL,POSTDESCR,POSTCAT,POSTVIP from mytgp_post where VALIDATED='YES' and POSTCAT='$name' and UIN='$sql_uin' order by POSTDATE DESC";
   $result1 = mysql_query($query1) or die ($lang_error_1.": ".$query1."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
    if ($file = fopen($msetstr['PROGSDIR']."templates/".$templates_all[7],"r"))
      {
      $template_link = "";
      while (!feof($file)) 
       {
       $buffer = fgets($file, 4096);
       $template_link .= $buffer;
       }
      fclose($file);
      } else show_error_msg ("Can't Open template file: ".$templates_all[7]);

    if ($msetstr["USEVIP"]=="YES")
     if ($file = fopen($msetstr['PROGSDIR']."templates/".$templates_all[9],"r"))
      {
      $viptpl = "";
      while (!feof($file)) 
       {
       $buffer = fgets($file, 4096);
       $viptpl .= $buffer;
       }
      fclose($file);
      } else show_error_msg ("Can't Open template file: ".$templates_all[9]);

   $firstpos = strpos($template_categories,"<!--link-->");
   while (($row1 = mysql_fetch_array($result1)) and ($firstpos !== FALSE))
       {
        $temp = build_template_link ($template_link, $row1, $viptpl);
        $template_categories = substr_replace($template_categories, $temp, $firstpos, strlen("<!--link-->") );
        $firstpos = strpos($template_categories,"<!--link-->");
       }

     if ($file = fopen( $msetstr["TGPMAINDIR"].$name.".".$global_extension, "w+" ))
     {
     $len_gen = strlen( $template_categories );
     fwrite( $file, StripSlashes($template_categories), $len_gen);
     fclose( $file );
     } else show_error_msg ("Can't write to template file: ".$templates_all[3]);
     //echo $name.".tpl - ".$len_gen." bytes write success.<br>";
}
}

function update_posts()
{
   global $msetstr, $sql_uin;

  if ($msetstr["ROTATEDAYS"]>0)
     $checktime = time() - $msetstr["ROTATEDAYS"]*86400;
  else $checktime = 0;

  if (($msetstr["USEVIP"]=="YES")&&($msetstr["ROTATEDAYSVIP"]>0))
     $checktimevip = time() - $msetstr["ROTATEDAYSVIP"]*86400;
  else $checktimevip = 0;

  if ($msetstr["SENDEMAIL"]=='YES')
  {
   $query = "select * from mytgp_post where POSTDATE < '$checktime' and POSTVIP<>'YES' and UIN='$sql_uin'";
   $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
   $row = mysql_fetch_array($result);
   while ($row) send_mail_rotate($row['POSTEMAIL'], $row['POSTNUM'], $row['POSTURL'], $row['POSTDESCR'], $row['POSTCAT']);

   $query = "select * from mytgp_post where POSTDATE < '$checktimevip' and POSTVIP='YES' and UIN='$sql_uin'";
   $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
   $row = mysql_fetch_array($result);
   while ($row) send_mail_rotate($row['POSTEMAIL'], $row['POSTNUM'], $row['POSTURL'], $row['POSTDESCR'], $row['POSTCAT']);
  }
   $query = "delete from mytgp_post where POSTDATE < '$checktime' and POSTVIP<>'YES' and UIN='$sql_uin'";
   $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
   $query = "delete from mytgp_post where POSTDATE < '$checktimevip' and POSTVIP='YES' and UIN='$sql_uin'";
   $result = mysql_query($query) or die ($lang_error_1.": ".$query."<BR>ERROR ".mysql_errno().": ".mysql_error()."<BR>");
}

function validateEmail ($email) {
return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email));
}

function send_mail ($to, $subj, $message, $mass = 0, $check = FALSE)
// $to - recepient address in array
// $subj - subject 
// $message 
// $mass - if 1 - mass mail -> all recepients in bcc: field of mail
{
   global $msetstr, $phpMyTGP_link;
   if ($mailer == "temp") $mailer = $msetstr['TGPNAME'];
   $from = trim($from);

$m= new Mail; // create the mail
	$m->From( $msetstr['ADMINMAIL'] );
	if (!$mass)	$m->To( $to );
	else 	$m->Bcc( $to);
	$m->ReplyTo( $msetstr['ADMINMAIL'] );
	$m->Organization( $msetstr['TGPNAME'] );
	$m->Subject( $subj );
	$m->Body( $message."\n\n".$phpMyTGP_link );	// set the body
	$m->autoCheck( $check ); 
//	$m->Cc( "someone@somewhere.fr");
//	$m->Priority(4) ;	// set the priority to Low
//	$m->Attach( "/home/leo/toto.gif", "image/gif", "inline" ) ;	// attach a file of type image/gif to be displayed in the message if possible
	$m->Send();	// send the mail
//	echo "Mail was sent:<br><pre>", $m->Get(), "</pre>";
   return $m->Get();
}

function ValidEmail($address)
{
	if( ereg( ".*<(.+)>", $address, $regs ) ) {
		$address = $regs[1];
	}
 	if(ereg("^[_\\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\\.)+[a-z]{2,3}$",$address) ) 
 		return true;
 	else
 		return false;
}

function send_mail_welcome($email, $pics, $url, $desc, $catname)
{
global $msetstr, $templates_all, $phpMyTGP_link;
    if ($file = @fopen($msetstr['PROGSDIR']."templates/".$templates_all[5],"r"))
      {
	   $filecontent = "";
	   while (!feof($file)) 
	    {
	     $buffer = fgets($file, 4096);
	     $filecontent .= $buffer;
	    }
	   fclose($file);
	   $filecontent = str_replace ("<!--catname-->",$catname,$filecontent);
	   $filecontent = str_replace ("<!--email-->",$email,$filecontent);
	   $filecontent = str_replace ("<!--pics-->",$pics,$filecontent);
	   $filecontent = str_replace ("<!--url-->",$url,$filecontent);
	   $filecontent = str_replace ("<!--desc-->",$desc,$filecontent);
	   $filecontent = str_replace ("<!--tgpname-->",$msetstr['TGPNAME'],$filecontent);
	// <!--link-->
	   $filecontent = str_replace ("<!--link-->",$link,$filecontent);
	   $filecontent .= "\n\n".$phpMyTGP_link;
	   send_mail($email,"Welcome to".$msetstr['TGPNAME'],$filecontent);
      } else show_error_msg ("Can't Open template file: ".$templates_all[5]);
}

function send_mail_delete($email, $pics, $url, $desc, $catname)
{
global $msetstr, $templates_all, $phpMyTGP_link;
    if ($file = @fopen($msetstr['PROGSDIR']."templates/".$templates_all[8],"r"))
      {
	   $filecontent = "";
	   while (!feof($file)) 
	    {
	     $buffer = fgets($file, 4096);
	     $filecontent .= $buffer;
	    }
	   fclose($file);
	   $filecontent = str_replace ("<!--catname-->",$catname,$filecontent);
	   $filecontent = str_replace ("<!--email-->",$email,$filecontent);
	   $filecontent = str_replace ("<!--pics-->",$pics,$filecontent);
	   $filecontent = str_replace ("<!--url-->",$url,$filecontent);
	   $filecontent = str_replace ("<!--desc-->",$desc,$filecontent);
	   $filecontent = str_replace ("<!--tgpname-->",$msetstr['TGPNAME'],$filecontent);
	// <!--link-->
	   $filecontent = str_replace ("<!--link-->",$link,$filecontent);
	   $filecontent .= "\n\n".$phpMyTGP_link;
	   send_mail($email,"Mail from ".$msetstr['TGPNAME'],$filecontent, 0, FALSE);
      } else show_error_msg ("Can't Open template file: ".$templates_all[8]);
}

function send_mail_rotate($email, $pics, $url, $desc, $catname)
{
global $msetstr, $templates_all, $phpMyTGP_link;
    if ($file = @fopen($msetstr['PROGSDIR']."templates/".$templates_all[4],"r"))
      {
	   $filecontent = "";
	   while (!feof($file)) 
	    {
	     $buffer = fgets($file, 4096);
	     $filecontent .= $buffer;
	    }
	   fclose($file);
	   $filecontent = str_replace ("<!--catname-->",$catname,$filecontent);
	   $filecontent = str_replace ("<!--email-->",$email,$filecontent);
	   $filecontent = str_replace ("<!--pics-->",$pics,$filecontent);
	   $filecontent = str_replace ("<!--url-->",$url,$filecontent);
	   $filecontent = str_replace ("<!--desc-->",$desc,$filecontent);
	   $filecontent = str_replace ("<!--tgpname-->",$msetstr['TGPNAME'],$filecontent);
	// <!--link-->
	   $filecontent = str_replace ("<!--link-->",$link,$filecontent);
	   $filecontent .= "\n\n".$phpMyTGP_link;
	   send_mail($email,"Mail from ".$msetstr['TGPNAME'],$filecontent);
      } else show_error_msg ("Can't Open template file: ".$templates_all[4]);
}

function precheck_posting($url)
{
global $msetstr;
	$snoopy = new Snoopy;
	$snoopy->maxredirs = 0;
	$snoopy->agent = "(compatible; MSIE 4.01; MSN 2.5; AOL 4.0; Windows 98)".$msetstr["TGPNAME"]." link checker";
	$snoopy->read_timeout = 0;

       	if($snoopy->fetch($url))
	{
                $code = $snoopy->response_code;
		list ($head, $code, $text) = split (" ", $code);
		if ($code<"300")
		{
			$found=0;
			while(list($key,$val) = each($snoopy->headers))
			 {		
		           if (($msetstr["CHECKBEFORE"]=="YES") && stristr(htmlspecialchars($snoopy->results), $msetstr["CHECKFRASE"])) {$found=1;break;}
		           if (($msetstr["CHECKWIN"]=="YES") && stristr(htmlspecialchars($snoopy->results), "window.open")) {$found=2;break;}
		           if (($msetstr["CHECKSTATUS"]=="YES") && stristr(htmlspecialchars($snoopy->results), "status=")) {$found=3;break;}
		           if (($msetstr["CHECKONMOUSE"]=="YES") && stristr(htmlspecialchars($snoopy->results), "status=")) {$found=4;break;}
		           if (($msetstr["CHECKONCLICK"]=="YES") && stristr(htmlspecialchars($snoopy->results), "status=")) {$found=5;break;}
		         }
		         if ($found==1)  return "FOUND";
			 elseif ($found==2)  return "WINDOW";
			 elseif ($found==3)  return "STATUS";
			 elseif ($found==4)  return "ONMOUSE";
			 elseif ($found==5)  return "ONCLICK";
                         else return "NOT FOUND";
		} else return $code;

        }
	else
		return "ERR";
}

function show_admin_header ()
{
global $phpMyTGP_ver, $lang_admin_2, $msetstr, $phpMyTGP_link;
echo "<html><head>\n";
echo "<title>phpMyTGP ".$phpMyTGP_ver." ".$lang_admin_2." ".$msetstr['TGPNAME']."</title>\n";
echo "<style type=\"text/css\">\n";
echo "<!--\n";
echo "BODY{font-size:xx-small;font-family:arial;font-weight:bold;color:black;background-color:white;}\n";
echo " a { color:black;text-decoration:underline;font-weight:bold}\n";
echo " -->\n";
echo "</style></head>\n";
echo "<body bgcolor=white text=black link=black>\n";
echo "<table align=center bgcolor=silver border=1 cellpadding=5 bordercolor=black width=90%>\n";
echo "<tr><td align=center width=80%>\n";
echo "$phpMyTGP_link\n";
echo "</td></tr>";
}
?>


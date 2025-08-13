<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	require('./includes/messages.inc.php');
	require('./includes/config.inc.php');

	  if(!isset($HTTP_POST_VARS[action]) &&
		   !isset($HTTP_POST_VARS[creategallery]) &&
			 !isset($HTTP_POST_VARS[uploadpicture]))
		{
				#// Create the tmp directory if does not exists
				if(!file_exists($image_upload_path.session_id()))
				{
						mkdir($image_upload_path.session_id(),0755);
				}

				#// Move existing gallery files into the tmp directory
				reset($EDIT_UPLOADED_PICTURES);
				while(list($k,$v) = each($EDIT_UPLOADED_PICTURES))
				{
						copy($image_upload_path.$EDIT_GALLERY_DIR."/".$v,$image_upload_path.session_id()."/".$v);
				}
    }

    #// Process delete
    if($HTTP_GET_VARS[action] == "delete")
    {
        unlink($image_upload_path.session_id()."/".$EDIT_UPLOADED_PICTURES[$img]);
        
        unset($EDIT_UPLOADED_PICTURES[$img]);
        unset($EDIT_UPLOADED_PICTURES_SIZE[$img]);
        session_name($SESSION_NAME);
        
        session_register(EDIT_UPLOADED_PICTURES,EDIT_UPLOADED_PICTURES_SIZE);
    }

    #// Create Gallery
    #// PROCESS UPLOADED FILE
    if(!empty($HTTP_POST_VARS[creategallery]))
    {
        #//
        $EDIT_GALLERY_UPDATED = TRUE;
        session_name($SESSION_NAME);
        session_register(EDIT_GALLERY_UPDATED);
        
        print "<SCRIPT Language=Javascript>window.close()</SCRIPT>";
        exit;
    }
    
    #// PROCESS UPLOADED FILE
    if($HTTP_POST_VARS[uploadpicture] == $MSG_681)
    {
        if(!empty($HTTP_POST_FILES[userfile][tmp_name]) && $HTTP_POST_FILES[userfile][tmp_name] != "none")
        {
            if($HTTP_POST_FILES[userfile][size] > ($SETTINGS[maxpicturesize] * 1024))
            {
                $ERR = $ERR_709."&nbsp;".$SETTINGS[maxpicturesize]."&nbsp;Kbytes";
            }
            elseif(!strpos($HTTP_POST_FILES[userfile][type],"gif") &&
                   !strpos($HTTP_POST_FILES[userfile][type],"png") &&
                   !strpos($HTTP_POST_FILES[userfile][type],"jpeg"))
            {
                $ERR = $ERR_710."(".$HTTP_POST_FILES[userfile][type].")";
            }
            else
            {
                #// Create a TMP directory for this session (if not already created)
                if(!file_exists($image_upload_path.session_id()))
                {
                    mkdir($image_upload_path.session_id(),0777);
                }
                #// Move uploaded file into TMP directory
                move_uploaded_file($HTTP_POST_FILES[userfile][tmp_name],
                                   $image_upload_path.session_id()."/".$HTTP_POST_FILES[userfile][name]);

								#//Populate arrays
                $EDIT_UPLOADED_PICTURES[] = $HTTP_POST_FILES[userfile][name];
                $EDIT_UPLOADED_PICTURES_SIZE[] = $HTTP_POST_FILES[userfile][size];
                
                session_name($SESSION_NAME);
                session_register(EDIT_UPLOADED_PICTURES,EDIT_UPLOADED_PICTURES_SIZE);
            }
        }
    }

?>
<HTML>
<HEAD>
<TITLE><? print $SETTINGS[sitename]?></TITLE>
</HEAD>

<BODY BGCOLOR="#FFFFFF"  LINK="<?=$FONTCOLOR[$SETTINGS[linkscolor]]?>" ALINK="<?=$FONTCOLOR[$SETTINGS[vlinkscolor]]?>" VLINK="<?=$FONTCOLOR[$SETTINGS[vlinkscolor]]?>">
<FORM NAME=upload ACTION=<?=basename($PHP_SELF)?> METHOD=POST  ENCTYPE="multipart/form-data">
<TABLE CELLPADDING=3 CELLSPACING=0 BORDER=0 ALIGN=CENTER WIDTH=370>
<TR>
  <TD BGCOLOR="<?=$FONTCOLOR[$SETTINGS[headercolor]]?>" colspan=2>
      <?=$std_font?><B><?=$MSG_663?></B></FONT>
  </TD>
</TR>
<TR>
  <TD COLSPAN=2>
      <?
        print $sml_font.$MSG_697."&nbsp;".$HTTP_SESSION_VARS[EXISTING_PICTURES]."&nbsp;".$MSG_698;
      ?>
  </TD>
</TR>
<?
  if(isset($ERR))
  {
?>
  <TR>
    <TD ALIGN=CENTER>
        <?=$err_font?><?=$ERR?></FONT>
    </TD>
  </TR>
<?
  }
?>

<?
  if(count($EDIT_UPLOADED_PICTURES) < $HTTP_SESSION_VARS[EXISTING_PICTURES])
  {
?>
    <TR>
      <TD>
          <?=$std_font?>1.<?=$MSG_680?></FONT>
          <BR>
          <INPUT TYPE=FILE NAME=userfile SIZE=15>
      </TD>
    </TR>
    <TR>
      <TD>
          <?=$std_font?>2.<?=$MSG_681?></FONT>
          <BR>
          <INPUT TYPE=SUBMIT NAME="uploadpicture" VALUE="<?=$MSG_681?>">
      </TD>
    </TR>
    <TR>
      <TD>
          <?=$std_font?><?=$MSG_695?></FONT>
      </TD>
    </TR>

<?
  }
  else
  {
?>  
  <TR>
    <TD ALIGN=CENTER>
        <?=$err_font?><?=$MSG_688."&nbsp;".$HTTP_SESSION_VARS[EXISTING_PICTURES]."&nbsp;".$MSG_689?></FONT>
    </TD>
  </TR>
<?
  }
?>

</TABLE>
<BR>
<BR>
<CENTER>
<?=$std_font?><B><?=$MSG_687?></B></FONT>
</CENTER>
<TABLE CELLPADDING=3 CELLSPACING=0 BORDER=0 ALIGN=CENTER WIDTH=370>
<TR BGCOLOR="<?=$FONTCOLOR[$SETTINGS[headercolor]]?>">
  <TD WIDTH=55%>
      <?=$sml_font?><B><?=$MSG_684?></B></FONT>
  </TD>
  <TD WIDTH=35%>
      <?=$sml_font?><B><?=$MSG_685?></B></FONT>
  </TD>
  <TD WIDTH=10% ALIGN=CENTER>
      <?=$sml_font?><B><?=$MSG_686?></B></FONT>
  </TD>
</TR>
<?
  reset($EDIT_UPLOADED_PICTURES);
  if(is_array($EDIT_UPLOADED_PICTURES))
  {
    while(list($k,$v) = each($EDIT_UPLOADED_PICTURES))
    {
  ?>
    <TD WIDTH=55%>
        <?=$sml_font?><?=$v?></FONT>
    </TD>
    <TD WIDTH=35%>
        <?=$sml_font?><?=$EDIT_UPLOADED_PICTURES_SIZE[$k]?></FONT>
    </TD>
    <TD WIDTH=10% ALIGN=CENTER>
        <A HREF="<?=basename($PHP_SELF)?>?action=delete&img=<?=$k?>"><IMG SRC="images/trash.png" BORDER=0></A>
    </TD>
  </TR>
<?
    }
  }
?>
</TABLE>
<BR><BR>
<CENTER>
          <INPUT TYPE=SUBMIT NAME="creategallery" VALUE="<?=$MSG_693?>">
</CENTER>
</FORM>
<BR><BR>
<center>
<A HREF="javascript: window.close()"><?=$std_font.$MSG_678?></A>
<BR>
</CENTER>
</BODY>
</HTML>



<?php
/***************************************************************************
 *   script               : vCard PRO
 *   copyright            : (C) 2001-2002 Belchior Foundry
 *   website              : www.belchiorfoundry.com
 *
 *   This program is commercial software; you can´t redistribute it under
 *   any circumstance without explicit authorization from Belchior Foundry.
 *   http://www.belchiorfoundry.com/
 *
 ***************************************************************************/
define('IN_VCARD', true);
$templatesused = 'uploadmain,uploadcontinue';
require('./lib.inc.php');

@header ("Pragma: no-cache");

make_error_page($user_upload_allow == 0,$MsgFileUploadNotAllowed);

if ($HTTP_POST_VARS['mode'] == 'upload_file')
{
	$filetype = $HTTP_POST_FILES['attachment']['type'];
	$MaxSize = $user_upload_maxsize * 1024;
	$attachment_name 	= strtolower($HTTP_POST_FILES['attachment']['name']);
	if ($HTTP_POST_FILES['attachment']['size'] > $MaxSize)
	{
		make_error_page(1,"<p>$MsgFileBiggerThan $user_upload_maxsize Kilobytes!<p>");
	}
	$extension	= get_file_extension($attachment_name);
	// check extension.
	if (!empty($extension)) // images only
	{
		// $allExt = array("gif","jpg","jpeg","swf","ram","mov","rm,"rpm","ra,""");
		if ($extension == 'gif' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'swf' || $extension == 'png')
		{
			$denied = 0;
		}else{
			$denied = 1;
		}
	}
	else
	{
		$denied = 1;
	}
	make_error_page($denied,"<p>$MsgErrorFileExtension<p>");
	
	if ($safeupload == 1)
	{
		if (function_exists("is_uploaded_file"))
		{
			$path = "$site_image_path/$attachment_name";
			// copy file
			if (is_uploaded_file($HTTP_POST_FILES['attachment']['tmp_name']))
			{
				if (move_uploaded_file($HTTP_POST_FILES['attachment']['tmp_name'],$path))
				{
					// read file
					$filesize	= filesize($path);
					$filenum	= fopen($path,"r");
					$filestuff	= fread($filenum,$filesize);
					//echo "$filestuff";
					fclose($filenum);
					// add to db
					$query ="INSERT INTO vcard_attach (attach_id,messageid,time,filedata,filename,filesize) VALUES (NULL,'',CURDATE(),'".addslashes($filestuff)."','".addslashes($HTTP_POST_FILES['attachment']['name'])."','$filesize')";
					$result 	= $DB_site->query($query);
					$attach_id 	= $DB_site->insert_id();
					unlink($path);
				}
			}
		}
	}else{
		// read file
		$filesize	= @filesize($HTTP_POST_FILES['attachment']['tmp_name']);
		$filenum	= @fopen($HTTP_POST_FILES['attachment']['tmp_name'],"r");
		$filestuff	= @fread($filenum,$filesize);
		@fclose($filenum);
		@unlink($HTTP_POST_FILES['attachment']['tmp_name']);
		// add to db
		$query ="INSERT INTO vcard_attach 
		(attach_id,messageid,time,filedata,filename,filesize)
		VALUES
		(NULL,'$message_id',CURDATE(),'".addslashes($filestuff)."','".addslashes($HTTP_POST_FILES[attachment][name])."','$filesize')";
		$result = $DB_site->query($query);
		$attach_id = $DB_site->insert_id();
	}
	$cardlink = "<a href=\"create.php?uploaded=$attach_id&file=file.$extension\">". get_html_image($site_prog_url,"attachment.php?id=$attach_id&file=file.$extension",$user_flash_width,$user_flash_height) . "<p>$MsgCreateCard</a>";
	eval("make_output(\"".get_template("uploadcontinue")."\");");
}

if (empty($HTTP_POST_VARS['mode']))
{
	$outputform = "<form method=\"post\" action=\"upload.php\" enctype=\"multipart/form-data\">
	<input type=\"hidden\" name=\"mode\" value=\"upload_file\">
	<input type=\"file\" name=\"attachment\" size=\"25\">";
	$buttonsubmit		= "<input type=\"submit\" name=\"submit\" value=\"$MsgFileSend\" width=\"150\"></form>";
	$form			= $outputform;
	eval("make_output(\"".get_template("uploadmain")."\");");
}

if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>
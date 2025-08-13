<?
session_start();
require_once("php_inc.php");
include("header_inc.php");
db_connect();

if (session_is_registered("valid_user"))
{
 do_html_heading("Image upload");
 member_menu();
 ?> 
	<h2>Upload image</h2>
	        
	
	<?
	print "<font class='text'>Upload limit is " . floor($set_piclimit / 1024) . " kb.</font><p />";
	
	if (!$submit)
	{
	        print "From here you can upload a picture to your profile. Please note that the image
	        can be no larger than " . floor($set_piclimit / 1024) . " in size, and will be scaled
	        down to fit as thumbnail.";
	}
	
	print "<form method='post' action='upload_image.php' enctype='multipart/form-data'>";
	?>
	
	<INPUT TYPE="hidden" name="pictures_siteid" value="<? echo $pictures_siteid ?>" />
	<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="<? echo $set_piclimit ?>" />
	
	<?
	if ($submit)
	{
	
		if($photo_size)
		{
			// Get file extension
			$ext=substr($photo_name,-4);
			$file_without_ext=substr($photo_name,0,-4);
			
			
			// Allowed file takes
			if (strcasecmp($ext,".jpg") || strcasecmp($ext,".gif"))
			{
				if(is_uploaded_file($photo))
				{
					$rand = rand(200,19999999);
					$photo_name = $rand . $photo_name;
					$photo_name_large = $rand . $file_without_ext . "_large" . $ext;
					$photo_name_small = $rand . $file_without_ext . "_small" . $ext;
					move_uploaded_file($photo,"upload_images/$photo_name");	
					
					
					// If imagemagic is installed and activated from cp
					if ($set_magic)
					{
						
						// IMAGEMAGICK PATH IS SET BELOW. Change C:\Programfiler\ImageMagick-5.4.9-Q16\convert.exe to
						// your own path in the strings below. On Linux, you can normally just type convert
						
						
						$command = "C:\Programfiler\ImageMagick-5.4.9-Q16\convert.exe -quality $set_magic_q -size $set_orgsize -scale $set_orgsize upload_images/$photo_name upload_images/$photo_name";
						$res = exec($command);
						
						
						$command = "C:\Programfiler\ImageMagick-5.4.9-Q16\convert.exe -quality $set_magic_q -size $set_thmbsize -scale $set_thmbsize upload_images/$photo_name upload_images/$photo_name_small";
						$res = exec($command);
						
					}
					
					
					// Tell the database that picture is uploaded
					$string = "UPDATE user set image = '$photo_name' where username = '$valid_user'";
	                $result_done=MYSQL_QUERY($string);
	       			
					
	    			if ($result_done)
	    			{        
	    				print("<p />Done!<br \>The image is uploaded !<p />");
	    				print("<img src='upload_images/$photo_name'>");
	    			}
	    			
				}
			}
			else
			{
				die("Please, only .gif or .jpg images.");
			}
		}
		else if($photo_name != "" && $photo_size == "0") 
		{
			die("The picture is 0 kb.");
		}
		else 
		{
			print "No picture choosen for upload.";	
		}
	 
	
	}
	?>
	<p />
	<br />
	Image file (click Browse to look on your computer for image):<br />
	<input name="photo" type="file" /><br />
	<input type="submit" name="submit" value="Upload image" />


<?
}
require("footer_inc.php");
?>

<?php
// *****************************************************************************
//         START of FILE UPLOAD for insert and update modules PHP 4.2.0
// *****************************************************************************
$originalFile = $_FILES['userfile']['name'];
if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
	$pathToLogosFolder = DOWNLOADFOLDER . "/";
	/* Set a new name - this name combines the unique database contact key 
	   with the image extension, example "23.gif". If an image exists for 
		the user then it will have the same name as the item key named $productnumber.
	*/
	$destination = $pathToLogosFolder . $originalFile;
	if(move_uploaded_file($_FILES['userfile']['tmp_name'], $destination)) {
		// is the total file size too big?
		$userfile_size = $_FILES['userfile']['size'];
		if($userfile_size > MAX_FILE_SIZE ) {
			$systemMessage .= "<h2>File Size Too Big</h2>";
	   	$systemMessage .= "Filesize of $userfile_size exceeds maximum allowed file size of " . MAX_FILE_SIZE . " bytes.<br>";
			$systemMessage .= "Please choose a smaller size or reduce the size of your file.";
			$badData = "true";
		}
		if($badData == "true") { // nuke it, there was a problem of some sort ...
			unlink($destination);
	   	$systemMessage .= "<h3>Bad Data</h3>";
	   	$systemMessage .= "<br>Unlinked $destination";
			break;
		}else{

			// delete any pre existing old file
			$preExistingOldFile = $pathToLogosFolder . $url; // it's not really a url
			if(file_exists($preExistingOldFile)) {
				if(filesize ($preExistingOldFile) > 0) {
					if(unlink($preExistingOldFile)) {
						$systemMessage .= "<br>Pre-existing old file called $url removed";	
					}else{
						$systemMessage .= "<br>Problem removing pre-existing old file called $url. <br>Check folder write permissions are set to CHMOD 777";
					}
				}
			}




			// rename the moved file from a name to a number
			// get the extension
			$dotExtension = stristr ( $originalFile, ".");
			$newName = $productnumber . $dotExtension;
			if($url != $newName) { // old field called 'url' is not same as $newName
				// only rename if the name is 
				if( @rename ($destination, $pathToLogosFolder . $newName)) {
					$systemMessage .= "<h3>Renamed $originalFile as '$newName'</h3>";
		 			$systemMessage .= "Filesize: $userfile_size bytes";
				}else{
					$systemMessage .= "<br>Problem renaming $originalFile as '$newName'. File may already exist.";
				}
			}



			// set the url field for future updates - we have to know if a file has been uploaded for any future updates
			$url = $newName;
			$query = "UPDATE " . TABLEITEMS . " SET url='$url' WHERE productnumber=$productnumber";
			// pconnect, select and query
			if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
				if ( mysql_select_db(DBNAME, $link_identifier)) {
					// run the query
		   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
					$rows = mysql_affected_rows($link_identifier); // get # of rows
					if ( $rows == 1 ) {
						$systemMessage .= "<br>URL field updated to '$url'";
					}elseif($rows == 0 ) {
						$systemMessage .= "<br>URL field NOT updated.";
					}elseif( $rows > 1 ) {
						$systemMessage .= "<br>$rows rows affected.";
					}
				}else{ // select
					echo mysql_error();
				}
			}else{ //pconnect
				echo mysql_error();
			}		
		}
	} //if(move_uploaded_file(
	// show the results
	$systemMessage .= "<h3>Uploaded $originalFile</h3>";
}else{
	$systemMessage .= "<br>No file was uploaded.";
} // is uploaded
if (version_compare( phpversion(), "4.2.0") < 0) { // this reporting available 4.2.0 or higher only
	// give full PHP reporting about what went down with a file upload, regardless of result
	$fileUploadError = $_FILES['userfile']['error'];
	switch($fileUploadError) {
		case 0:
			$uploadHeading = "<h4>UPLOAD_ERR_OK</h4>";
			$uploadMessage = "Value: $fileUploadError; There is no error, the file uploaded with success.";
			break;
		case 1:
			$uploadHeading = "<h4>UPLOAD_ERR_INI_SIZE</h4>";
			$uploadMessage = "Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.";
			break;
		case 2:
			$uploadHeading = "<h4>UPLOAD_ERR_FORM_SIZE</h4>";
			$uploadMessage = "Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form.";
			break;
		case 3:
			$uploadHeading =  "<h4>UPLOAD_ERR_PARTIAL</h4>";
			$uploadMessage = "Value: 3; The uploaded file was only partially uploaded.";
			break;
		case 4:
			$uploadHeading = "<h4>UPLOAD_ERR_NO_FILE</h4>";
			$uploadMessage = "Value: 4; No file was uploaded.";
			break;
		default:
			$systemMessage .= "default upload error here.";
	}
	$systemMessage .= "<h2>Internal PHP Report about file upload</h2>";
	$systemMessage .= $uploadHeading . $uploadMessage;
}
// END - THIS CODE INSERTS OR UPDATES THE CUSTOMER FILE USING php4.2.0 reporting
// *****************************************************************************
//          END of FILE UPLOAD for insert and update modules PHP 4.2.0
// *****************************************************************************
?>
<?php

        error_reporting(E_WARNING);
        
        require_once("config.php");
        require_once("includes/php/variables.php");
        require_once("includes/php/security.php");

        $strMethod = @$_GET["strMethod"];
        
        if($strMethod == "")
			$strMethod = @$_POST["strMethod"];

        /*
                Firstly, we will make sure that the user is a content modifier
                (has an IsLoggedIn value of 2 or more). If they arent, we will redirect them
                to the main page.
        */

        $userStatus = IsLoggedIn();

                if($userStatus == 0)
                        die();
        function NoAccess()
                {
                        ?>
                                <html>
                                <head>
                                <title> :: Image Manager :: </title>
                                <link rel="stylesheet" type="text/css" href="styles/style.css">
                                </head>
                                <body bgcolor="#ffffff">

                                        <table width="520" align="center" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                        <td width="100%" valign="bottom"><a href="imagebank.php?strMethod=showMain"><img border="0" src="ib1_on.gif"></a><a href="imagebank.php?strMethod=showUpload"><img border="0" src="ib2_off.gif"></a></td>
                                                </tr>
                                                <tr>
                                                        <td align="right">
                                                                <table width="100%" border="1" bordercolor="#42AE42" bgcolor="#C6E7C6" cellspacing="0" cellpadding="0">
                                                                        <tr>
                                                                                <td width="100%" height="440" valign="top">
                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                <br><span class="BodyHeading">Unauthorised</span><br><br>
                                                                                                You do not have sufficient login privledges to access this
                                                                                                part of the site. This breach has been logged.<br><br>
                                                                                                <a href="javascript:window.close()">Close Window</a>
                                                                                        </p>
                                                                                </td>
                                                                        </tr>
                                                                </table>
                                                        </td>
                                                </tr>
                                        </table>
                                </body>
                                </html>
                        <?php

                                // Don't process the rest of the script

                                global $PHP_SELF;

                                $usrCredentials = new usrCredentials();
                                $strPage = $PHP_SELF;
                                $strUser = $usrCredentials->GetData();
                                $strLog = "Unauthorised: {$strUser[0]}, $strPage, $REMOTE_ADDR, " . date("d/m/Y h:i:m A");

                                AppendToLog($strLog);
                                include("templates/dev_bottom.php");
                                exit;
                }

        switch($strMethod)
        {
                case "showList":
                {
                        ShowImageList();
                        break;
                }
                case "showUpload":
                {
                         if($userStatus < 3)
                                if(in_array("add_images", $publisherAccess))
                                        ShowUpload();
                                else
                                        NoAccess();
                         else
                                ShowUpload();
                        break;
                }
                case "getUpload":
                {
                        GetImages();
                        break;
                }
                case "showImage":
                {
                        ShowImage(@$_GET["imageId"]);
                        break;
                }
                case "deleteImage":
                {
                         if($userStatus < 3)
                                if(in_array("delete_images", $publisherAccess))
                                        DeleteImage($_GET["imageId"]);
                                else
                                        NoAccess();
                         else
                                DeleteImage($_GET["imageId"]);
                        break;
                }
                default:
                {
                         if($userStatus < 3)
                                if(in_array("view_images", $publisherAccess))
                                        ShowMain();
                                else
                                        NoAccess();
                         else
                                ShowMain();
                        break;
                }
        }

        function ShowMain()
        {
        ?>
                <html>
                <head>
                <title> :: Image Manager :: </title>
                <link rel="stylesheet" type="text/css" href="styles/style.css">
                </head>
                <body bgcolor="#ffffff">

                        <table width="520" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                        <td width="100%" valign="bottom"><a href="imagebank.php?strMethod=showMain"><img border="0" src="ib1_on.gif"></a><a href="imagebank.php?strMethod=showUpload"><img border="0" src="ib2_off.gif"></a></td>
                                </tr>
                                <tr>
                                        <td align="right">
                                                <table width="100%" border="1" bordercolor="#42AE42" bgcolor="#C6E7C6" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                                <td width="100%" height="440" valign="top">
                                                                        <p style="margin-left:10; margin-top:10; margin-right:10">
                                                                        <span class="BodyHeading">Image Bank</span>
                                                                        <span class="BodyText">
                                                                                <br><br>To add an image to your text, click on the insert link beneath the
                                                                                image shown below. You can view an image in full size by clicking on its view link, and
                                                                                you can also delete an image by clicking on its remove link.<br><br>
                                                                        </span>
                                                                        <iframe name="iImages" src="imagebank.php?strMethod=showList" border="1" width="495" height="330"></iframe>

                                                                </td>
                                                        </tr>
                                                </table>
                                        </td>
                                </tr>
                        </table>
                </body>
                </html>
        <?php
        }

        function ShowImageList()
        {
                global $siteURL;
                global $publisherAccess;

                $dbVars = new dbVars();
                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
                ?>
                        <link rel="stylesheet" type="text/css" href="styles/style.css">
                <?php

                include("includes/jscript/imagebank.js");

                if($svrConn)
                {
                        $dbConn = @mysql_select_db($dbVars->strDb, $svrConn);

                        if($dbConn)
                        {
                                $iResult = mysql_query("select * from tbl_Images order by iName asc");
                                $iCounter = 0;
                                ?>
                                        <table width="98%" align="center" cellspacing="0" cellpadding="0">
                                        <tr>
                                <?php

                                if(mysql_num_rows($iResult) == 0)
                                {
                                ?>
                                        <span class="BodyText">
                                                No images have been uploaded yet. Please click on the upload images tab
                                                above to start uploading images.
                                        </span>
                                <?php
                                }
                                else
                                {
                                ?>
									<table width="100%" border="1" bordercolor="#FFFFFF" cellspacing="0" cellpadding="0">
										<tr>
											<td width="35%" bgcolor="#42AE42">
												&nbsp;<span class="WhiteHeader">Image Name</span>
											</td>
											<td width="20%" bgcolor="#42AE42">
												&nbsp;<span class="WhiteHeader">Size</span>
											</td>
											<td width="15%" bgcolor="#42AE42">
												&nbsp;<span class="WhiteHeader">View</span>
											</td>
											<td width="15%" bgcolor="#42AE42">
												&nbsp;<span class="WhiteHeader">Insert</span>
											</td>
											<td width="15%" bgcolor="#42AE42">
												&nbsp;<span class="WhiteHeader">Delete</span>
											</td>
										</tr>
                                <?php
                                }

                                while($iRow = mysql_fetch_array($iResult))
                                {
                                ?>
									<tr>
										<td width="35%">
											&nbsp;<span class="BodyText"><?php echo $iRow["iName"]; ?></span>
										</td>
										<td width="20%">
											&nbsp;&nbsp;<span class="BodyText"><?php echo $iRow["iSize"]; ?> Bytes</span>
										</td>
										<td width="15">
											&nbsp;&nbsp;<a target="_blank" href="imagebank.php?strMethod=showImage&imageId=<?php echo $iRow["pk_iId"]; ?>">View</a><br>
										</td>
										<td width="15">
											&nbsp;&nbsp;<a href="javascript:insertImage(<?php echo $iRow["pk_iId"] . ",'" . $siteURL . "'"; ?>)">Insert</a>
										</td>
										<td width="15%">&nbsp;
										<?php
                                         if($userStatus < 3)
                                         {
                                                if(in_array("delete_images", $publisherAccess))
                                                {
                                                ?>
                                                        <a href="javascript:ConfirmDelImage(<?php echo $iRow["pk_iId"]; ?>)">Delete</a><br>
                                                <?php
                                                }
                                         }
                                         else
                                         {
                                                ?>
                                                        <a href="javascript:ConfirmDelImage(<?php echo $iRow["pk_iId"]; ?>)">Delete</a><br>
                                        <?php
                                        }
                                        ?>
										</td>
									</tr>
                                <?php
                                }
                                echo "</table>";
                        }
                        else
                        {


                        }
                }
                else
                {


                }
        }

        function ShowUpload()
        {
        ?>
                <html>
                <head>
                <title> :: Image Manager :: </title>
                <link rel="stylesheet" type="text/css" href="styles/style.css">
                </head>
                <body bgcolor="#ffffff">
                  <form name="frmUpload" enctype="multipart/form-data" action="imagebank.php?strMethod=getUpload" method="post">
                        <table width="520" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                        <td width="100%" valign="bottom"><a href="imagebank.php?strMethod=showMain"><img border="0" src="ib1_off.gif"></a><a href="imagebank.php?strMethod=showUpload"><img border="0" src="ib2_on.gif"></a></td>
                                </tr>
                                <tr>
                                        <td align="right">
                                                <table width="100%" border="1" bordercolor="#42AE42" bgcolor="#C6E7C6" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                                <td width="100%" height="440" valign="top">
                                                                        <span class="BodyText">
                                                                        <p style="margin-left:10; margin-top:10; margin-right:20">
                                                                                <span class="BodyHeading">Update Images</span>
                                                                                <br><br>Use the form below to upload one or more images. You'll need to choose both an image AND a category for that image. Once you've uploaded the
                                                                                images, click on the image bank tab above to view and insert them into your text.<br><br>
                                                                        </span>
                                                                        <table width="100%" border="0">
                                                                                <tr>
                                                                                        <td width="15%" align="right">
                                                                                                <span class="BodyText">Image #1:</span>
                                                                                        </td>
                                                                                        <td width="85%">
                                                                                                <input type="file" name="image1" style="width:350">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="15%" align="right">
                                                                                                <span class="BodyText">Image #2:</span>
                                                                                        </td>
                                                                                        <td width="85%">
                                                                                                <input type="file" name="image2" style="width:350">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="15%" align="right">
                                                                                                <span class="BodyText">Image #3:</span>
                                                                                        </td>
                                                                                        <td width="85%">
                                                                                                <input type="file" name="image3" style="width:350">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="15%" align="right">
                                                                                                <span class="BodyText">Image #4:</span>
                                                                                        </td>
                                                                                        <td width="85%">
                                                                                                <input type="file" name="image4" style="width:350">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="15%" align="right">
                                                                                                <span class="BodyText">Image #5:</span>
                                                                                        </td>
                                                                                        <td width="85%">
                                                                                                <input type="file" name="image5" style="width:350">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="15%" align="right">
                                                                                                <span class="BodyText">Image #6:</span>
                                                                                        </td>
                                                                                        <td width="85%">
                                                                                                <input type="file" name="image6" style="width:350">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="15%" align="right">
                                                                                                <span class="BodyText">Image #7:</span>
                                                                                        </td>
                                                                                        <td width="85%">
                                                                                                <input type="file" name="image7" style="width:350">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="15%" align="right">
                                                                                                <span class="BodyText">Image #8:</span>
                                                                                        </td>
                                                                                        <td width="85%">
                                                                                                <input type="file" name="image8" style="width:350">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="15%" align="right">
                                                                                                <span class="BodyText">Image #9:</span>
                                                                                        </td>
                                                                                        <td width="85%">
                                                                                                <input type="file" name="image9" style="width:350">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="15%" align="right">
                                                                                                <span class="BodyText">Image #10:</span>
                                                                                        </td>
                                                                                        <td width="85%">
                                                                                                <input type="file" name="image10" style="width:350">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="15%" align="right">
                                                                                                &nbsp;
                                                                                        </td>
                                                                                        <td width="85%">
                                                                                                <input type="button" value="Cancel" onClick="history.go(-1)" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                                                <input name="cmdSubmit" type="submit" value="Upload Images »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                                        </td>
                                                                                </tr>
                                                                        </table>
                                                                </td>
                                                        </tr>
                                                </table>
                                        </td>
                                </tr>
                        </table>
                  </form>
                </body>
                </html>
        <?php
        }

        function GetImages()
        {
                // This function will grab the upload images and store
                // them in the MySQL database. If any errors occur then
                // they are displayed. If not, the user is re-directed back
                // to the image bank where they can view their new images.

                $dbVars = new dbVars();
                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                if($svrConn)
                {
                        $dbConn = @mysql_select_db($dbVars->strDb, $svrConn);

                        if($dbConn)
                        {
                                $err = "<ul>";
                                $numImagesTotal = 0;

                                $err .= AddImage($_FILES["image1"]["tmp_name"], $_FILES["image1"]["name"], $_FILES["image1"]["type"], $_FILES["image1"]["size"], $numImagesTotal);
                                $err .= AddImage($_FILES["image2"]["tmp_name"], $_FILES["image2"]["name"], $_FILES["image2"]["type"], $_FILES["image2"]["size"], $numImagesTotal);
                                $err .= AddImage($_FILES["image3"]["tmp_name"], $_FILES["image3"]["name"], $_FILES["image3"]["type"], $_FILES["image3"]["size"], $numImagesTotal);
                                $err .= AddImage($_FILES["image4"]["tmp_name"], $_FILES["image4"]["name"], $_FILES["image4"]["type"], $_FILES["image4"]["size"], $numImagesTotal);
                                $err .= AddImage($_FILES["image5"]["tmp_name"], $_FILES["image5"]["name"], $_FILES["image5"]["type"], $_FILES["image5"]["size"], $numImagesTotal);
                                $err .= AddImage($_FILES["image6"]["tmp_name"], $_FILES["image6"]["name"], $_FILES["image6"]["type"], $_FILES["image6"]["size"], $numImagesTotal);
                                $err .= AddImage($_FILES["image7"]["tmp_name"], $_FILES["image7"]["name"], $_FILES["image7"]["type"], $_FILES["image7"]["size"], $numImagesTotal);
                                $err .= AddImage($_FILES["image8"]["tmp_name"], $_FILES["image8"]["name"], $_FILES["image8"]["type"], $_FILES["image8"]["size"], $numImagesTotal);
                                $err .= AddImage($_FILES["image9"]["tmp_name"], $_FILES["image9"]["name"], $_FILES["image9"]["type"], $_FILES["image9"]["size"], $numImagesTotal);
                                $err .= AddImage($_FILES["image10"]["tmp_name"], $_FILES["image10"]["name"], $_FILES["image10"]["type"], $_FILES["image10"]["size"], $numImagesTotal);

                                $err .= "</ul>";

                                ?>

                                        <html>
                                        <head>
                                        <title> :: Image Manager :: </title>
                                        <link rel="stylesheet" type="text/css" href="styles/style.css">
                                        </head>
                                        <body bgcolor="#ffffff">
                                          <form name="frmUpload" enctype="multipart/form-data" action="imagebank.php?strMethod=getUpload" method="post">
                                                <table width="520" align="center" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                                <td width="100%" valign="bottom"><a href="imagebank.php?strMethod=showMain"><img border="0" src="ib1_off.gif"></a><img border="0" src="ib2_on.gif"></td>
                                                        </tr>
                                                        <tr>
                                                                <td align="right">
                                                                        <table width="100%" border="1" bordercolor="#42AE42" bgcolor="#C6E7C6" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                        <td width="100%" height="440" valign="top">
                                                                                                <p style="margin-left:10; margin-top:10; margin-right:10">
                                <?php
                                
                                if($numImagesTotal > 0)
                                {
                                        if($err != "<ul></ul>")
                                        {
                                                // Some errors occured
                                                ?>
                                                        <span class="BodyHeading">Image Upload Failed</span>
                                                        <span class="BodyText">
                                                                <br><br>Some errors occured while trying to add the selected images to the database. These errors
                                                                are shown below. Please review them and click the link to go back and fix them up:
                                                                <span class="Error"><?php echo $err; ?></span>
                                                                <p style="margin-left:10">
                                                                <a href="javascript:history.go(-1)">&lt;&lt; Try Again</a>
                                                <?php
                                        }
                                        else
                                        {
                                                // No errors occured
                                                ?>
                                                        <span class="BodyHeading">Image Upload Successful!</span>
                                                        <span class="BodyText">
                                                                <br><br>The selected images have been successfully uploaded to the database. Please click on the
                                                                link below to continue.<br><br>
                                                                <a href="imagebank.php?strMethod=showMain">Continue &gt;&gt;</a>
                                                <?php
                                        }
                                }
                                else
                                {
                                // No images uploaded
                                ?>
                                        <span class="BodyHeading">No Images Chosen</span>
                                        <span class="BodyText">
                                                <br><br>You didn't select any images to upload. Please click on the
                                                link below to go back.<br><br>
                                                <a href="javascript:history.go(-1)">&lt;&lt; Go Back</a>
                                <?php
                                }
                                        ?>
                                                                                                </span>
                                                                                        </td>
                                                                                </tr>
                                                                        </table>
                                                                </td>
                                                        </tr>
                                                </table>
                                          </form>
                                        </body>
                                        </html>
                                <?php
                        }
                        else
                        {
                                die("Couldn't connect to database server");
                        }
                }
                else
                {
                        die("Couldn't connect to database server");
                }
        }

        function AddImage($ImageFile, $ImageName, $ImageType, $ImageSize, &$NumImagesTotal)
        {
                global $ibMaxFileSize;
                global $ibTypes;
                
                if($ImageName != "")
                {
                        // Increment the number of images through the referenced variable $NumImagesTotal
                        $NumImagesTotal++;

                        // Check the image and make sure it's valid, etc
                        if(!in_array($ImageType, $ibTypes))
                                return("<li>$ImageName is an invalid image type</li>");

                        if($ImageSize > $ibMaxFileSize)
                                return("<li>$ImageName is greater than the maximum image size of $ibMaxFileSize bytes</li>");

                        // If the function is at this point then the image is valid
                        @$theImage = addslashes(fread(fopen($ImageFile, "rb"), $ImageSize));
                        $query = "insert into tbl_Images values(0, '$ImageName', '$ImageType', '$theImage', $ImageSize)";

                        if(@mysql_query($query))
                        {
                                // No errors, return no error msg
                                return "";
                        }
                        else
                        {
                                // An error occured
                                return "<li>An error occured while trying to save $ImageName to the database</li>";
                        }
                }
        }

        function ShowImage($ImageId)
        {
                $dbVars = new dbVars();
                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                if($svrConn)
                {
                        $dbConn = @mysql_select_db($dbVars->strDb, $svrConn);

                        if($dbConn)
                        {
                                $iResult = mysql_query("select iType, iBlob from tbl_Images where pk_iId = '$ImageId'");

                                if($iRow = mysql_fetch_array($iResult))
                                {
                                        header("Content-type: {$iRow["iType"]}");
                                        echo $iRow["iBlob"];
                                }
                        }
                }
        }

        function DeleteImage($ImageId)
        {
                $dbVars = new dbVars();
                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                if($svrConn)
                {
                        $dbConn = @mysql_select_db($dbVars->strDb, $svrConn);

                        if($dbConn)
                        {
                                @mysql_query("delete from tbl_Images where pk_iId = '$ImageId'");
                                header("location: imagebank.php?strMethod=showList");
                        }
                        else
                        {
                          die("The deletion of image $ImageId failed");
                        }
          }
          else
          {
                die("The deletion of image $ImageId failed");
          }
        }

?>
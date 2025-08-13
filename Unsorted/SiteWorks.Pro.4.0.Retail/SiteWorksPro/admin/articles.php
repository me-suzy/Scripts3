<?php include("templates/dev_top.php"); ?>
<?php

        /*
                Firstly, we will make sure that the user is a content modifier
                (has an IsLoggedIn value of 2 or more). If they arent, we will redirect them
                to the main page.
        */
        
        $userStatus = IsLoggedIn();

        if($userStatus == 0)
                header("Location: $siteURL/admin/index.php");

        function NoAccess()
                {
                        ?>
                                <p style="margin-left:15; margin-right:10" class="BodyText">
                                        <span class="BodyHeading">Unauthorised</span><br><br>
                                        You do not have sufficient login privledges to access this
                                        part of the site. This breach has been logged.<br><br>
                                        <a href="index.php">Return Home</a>
                                </p>

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

        $strMethod = @$_GET["strMethod"];
        
        if(strMethod == "")
			$strMethod = @$_POST["strMethod"];
        
        switch($strMethod)
                {
                        case "addnew":
                        {
							 if($userStatus < 3)
								if(in_array("add_articles", $publisherAccess))
							        ProcessNew();
								else
							        NoAccess();
							 else
						        ProcessNew();
							break;
                        }
                        case "addnew1":
                                ProcessNewContent();
                                break;
                        case "addnew2":
                                AddNew();
                                break;
                        case "del":
                        {
                                 if($userStatus < 3)
                                        if(in_array("delete_articles", $publisherAccess))
                                                DelArticle();
                                        else
                                                NoAccess();
                                 else
                                        DelArticle();
                                break;
                        }
                        case "update":
                        {
                                 if($userStatus < 3)
                                        if(in_array("edit_articles", $publisherAccess))
                                                ShowUpdateForm();
                                        else
                                                NoAccess();
                                 else
                                        ShowUpdateForm();
                                break;
                        }
                        case "update1":
                                ProcessUpdateContent();
                                break;
                        case "update2":
                                ProcessUpdateFinal();
                                break;
                        default:
                        {
                                 if($userStatus < 3)
                                        if(in_array("view_articles", $publisherAccess))
                                                ShowArticleList();
                                        else
                                                NoAccess();
                                 else
                                        ShowArticleList();
                                break;
                        }
                }

        function ProcessUpdateFinal()
                {

                        /*
                                This function will actually update our article in the tbl_Articles and tbl_ArticlePages
                                tables.
                        */

                         $aId = @$_POST["aId"];
                         $strTitle1 = @$_POST["strTitle1"];
                         $strTitle2 = @$_POST["strTitle2"];
                         $strTitle3 = @$_POST["strTitle3"];
                         $strTitle4 = @$_POST["strTitle4"];
                         $strTitle5 = @$_POST["strTitle5"];
                         $strTitle6 = @$_POST["strTitle6"];
                         $strTitle7 = @$_POST["strTitle7"];
                         $strTitle8 = @$_POST["strTitle8"];
                         $strTitle9 = @$_POST["strTitle9"];
                         $strTitle10 = @$_POST["strTitle10"];
                         $strTitle11 = @$_POST["strTitle11"];
                         $strTitle12 = @$_POST["strTitle12"];
                         $strTitle13 = @$_POST["strTitle13"];
                         $strTitle14 = @$_POST["strTitle14"];
                         $strTitle15 = @$_POST["strTitle15"];
                         $strTitle16 = @$_POST["strTitle16"];
                         $strTitle17 = @$_POST["strTitle17"];
                         $strTitle18 = @$_POST["strTitle18"];
                         $strTitle19 = @$_POST["strTitle19"];
                         $strTitle20 = @$_POST["strTitle20"];
                         $strContent1 = @$_POST["strContent1"];
                         $strContent2 = @$_POST["strContent2"];
                         $strContent3 = @$_POST["strContent3"];
                         $strContent4 = @$_POST["strContent4"];
                         $strContent5 = @$_POST["strContent5"];
                         $strContent6 = @$_POST["strContent6"];
                         $strContent7 = @$_POST["strContent7"];
                         $strContent8 = @$_POST["strContent8"];
                         $strContent9 = @$_POST["strContent9"];
                         $strContent10 = @$_POST["strContent10"];
                         $strContent11 = @$_POST["strContent11"];
                         $strContent12 = @$_POST["strContent12"];
                         $strContent13 = @$_POST["strContent13"];
                         $strContent14 = @$_POST["strContent14"];
                         $strContent15 = @$_POST["strContent15"];
                         $strContent16 = @$_POST["strContent16"];
                         $strContent17 = @$_POST["strContent17"];
                         $strContent18 = @$_POST["strContent18"];
                         $strContent19 = @$_POST["strContent19"];
                         $strContent20 = @$_POST["strContent20"];

                         $strTitle = @$_POST["strTitle"];
                         $intType = @$_POST["intType"];
                         $intAuthorId = @$_POST["intAuthorId"];
                         $strSummary = @$_POST["strSummary"];
                         $strTopicIds = @$_POST["strTopicIds"];
                         $strArticleIds = @$_POST["strArticleIds"];
                         $strBookIds = @$_POST["strBookIds"];
                         $strForumLink = @$_POST["strForumLink"];
                         $strRelLink1 = @$_POST["strRelLink1"];
                         $strRelLink2 = @$_POST["strRelLink2"];
                         $strRelLink3 = @$_POST["strRelLink3"];
                         $blnVisible = @$_POST["blnVisible"];
                         $intStatus = @$_POST["intStatus"];
                         $intPubMonth = @$_POST["intPubMonth"];
                         $intPubDay = @$_POST["intPubDay"];
                         $intPubYear = @$_POST["intPubYear"];
                         $intZipId = @$_POST["intZipId"];
                         $strZip = @$_FILES["strZip"]["tmp_name"];
                         $strZip_name = @$_FILES["strZip"]["name"];
                         $strZip_type = @$_FILES["strZip"]["type"];
                         $strZip_size = @$_FILES["strZip"]["size"];

                        $strTitle = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strTitle));
                        $strSummary = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strSummary));
                        $strForumLink = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strForumLink));
                        $strRelLink1 = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strRelLink1));
                        $strRelLink2 = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strRelLink2));
                        $strRelLink3 = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strRelLink3));

                        // We will do some basic error checking...

                        $err = "<ul>";

                        if(!isset($strTitle))
                                $err .= "<li>You must enter a title</li>";

                        if(!isset($intType))
                                $err .= "<li>You must select the type for this article</li>";

                        if(!isset($intAuthorId))
                                $err .= "<li>You must select an author for this article</li>";

                        if(!isset($strSummary))
                                $err .= "<li>You must enter a summary for this article</li>";

                        if(!isset($strTopicIds))
                                $err .= "<li>You must select at least one topic for this article</li>";

                        if(!isset($blnVisible))
                                $err .= "<li>You must select whether this article is visible</li>";

                        if(!isset($intStatus))
                                $err .= "<li>You must select whether this article is a draft/final copy</li>";

                        $err .= "</ul>";

                        if($err != "<ul></ul>")
                                {
                                        ?>
                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                        An error occured while trying to update this article.
                                                        Please review the errors below and then
                                                        click on the Go Back link below to go back and correct them.
                                                        <span class="Error">
                                                                <?php echo $err; ?>
                                                        </span>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <a href="javascript:history.go(-2)">Go Back</a>
                                                </p>
                                        <?php
                                                        include("templates/dev_bottom.php");
                                                        exit;
                                }

                        // We will now check to make sure that there is at least one page for the article
                        if(!isset($strTitle1))
                                {
                                        ?>
                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                        An error occured while trying to update this article.
                                                        Please review the errors below and then
                                                        click on the Go Back link below to go back and correct them.
                                                        <span class="Error">
                                                                <ul>
                                                                        <li>You must add at least one page to this article</li>
                                                                </ul>
                                                        </span>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                </p>
                                        <?php
                                                        include("templates/dev_bottom.php");
                                                        exit;
                                }

                        /*
                                If this script has gotten to this point then it means that all of the details
                                that the user has entered for this article are Ok. We will start by updating the
                                actual article in the tbl_Articles table and then we will update the pages.
                        */

                        $intArticleId = @$_GET["aId"];
                        
                        if($intArticleId == "")
							$intArticleId = @$_POST["aId"];

                        // Make sure that the published date is valid
                        if(strlen($intPubMonth) == 1)
							$intPubMonth = "0" . $intPubMonth;

                        if(strlen($intPubDay) == 1)
							$intPubDay = "0" . $intPubDay;

                        $pubDate = $intPubYear . $intPubMonth . $intPubDay . "000000";

                        $dbVars = new dbVars();
                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                        if($svrConn)
                                {
                                        $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                        if($dbConn)
                                                {
                                                        $strQuery  = "update tbl_Articles ";
                                                        $strQuery .= "set aTitle = '$strTitle', ";
                                                        $strQuery .= "    aDocType = $intType, ";
                                                        $strQuery .= "    aAuthorId = $intAuthorId, ";
                                                        $strQuery .= "    aSummary = '$strSummary', ";
                                                        $strQuery .= "    aTopicIds = '$strTopicIds', ";
                                                        $strQuery .= "    aArticleIds = '$strArticleIds', ";
                                                        $strQuery .= "    aBookIds = '$strBookIds', ";
                                                        $strQuery .= "    aForumLink = '$strForumLink', ";
                                                        $strQuery .= "    aLink1 = '$strRelLink1', ";
                                                        $strQuery .= "    aLink2 = '$strRelLink2', ";
                                                        $strQuery .= "    aLink3 = '$strRelLink3', ";
                                                        $strQuery .= "    aActive = $blnVisible, ";
                                                        $strQuery .= "    aDateCreated = '$pubDate', ";
                                                        $strQuery .= "    aStatus = $intStatus ";

                                                        if($intZipId > 0)
                                                                $strQuery .= "    , aSupportFile = $intZipId ";

                                                        $strQuery .= "where pk_aId = $intArticleId";

                                                        mysql_query($strQuery);

                                                        /*
                                                                We will now delete all of the old pages for this article and add
                                                                the new pages to the article.
                                                        */

                                                        $strQuery = "delete from tbl_ArticlePages where apArticleId = $intArticleId";
                                                        mysql_query($strQuery);

                                                        // We've now got an article id and we can add the pages for this article.
                                                        if($strTitle1 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle1', 1, '$strContent1')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle2 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle2', 2, '$strContent2')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle3 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle3', 3, '$strContent3')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle4 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle4', 4, '$strContent4')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle5 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle5', 5, '$strContent5')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle6 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle6', 6, '$strContent6')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle7 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle7', 7, '$strContent7')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle8 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle8', 8, '$strContent8')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle9 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle9', 9, '$strContent9')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle10 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle10', 10, '$strContent10')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle11 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle1', 11, '$strContent11')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle12 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle2', 12, '$strContent12')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle13 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle3', 13, '$strContent13')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle14 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle4', 14, '$strContent14')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle15 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle5', 15, '$strContent15')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle16 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle6', 16, '$strContent16')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle17 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle7', 17, '$strContent17')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle18 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle8', 18, '$strContent18')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle19 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle9', 19, '$strContent19')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle20 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle10', 20, '$strContent20')";
                                                                  mysql_query($strQuery);
                                                                }

                                                        /*
                                                                The article has been updated successfully. We will inform the user that it has been updated
                                                                and then return to the article list.
                                                        */
                                                        ?>
                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                        <span class="BodyHeading">Article Updated Successfully!</span><br><br>
                                                                        You have successfully updated the article entitled "<i><?php echo $strTitle; ?></i>".
                                                                        Please click on the continue link below to return to the list of articles.<br><br>
                                                                        <a href="articles.php">Continue</a>
                                                                </p>
                                                        <?php
                                                }
                                        else
                                                {
                                                        // Couldn't connect to the database
                                                        ?>
                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                                        An error occured while trying to connect to the
                                                                        database. Please click on the refresh button above to try
                                                                        and load this page again.
                                                                </p>
                                                        <?php
                                                }
                                }
                        else
                                {
                                        // Couldn't connect to the database server
                                        ?>
                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                        An error occured while trying to connect to the
                                                        database server. Please click on the refresh button above to try
                                                        and load this page again.
                                                </p>
                                        <?php
                                }
                }

        function ProcessUpdateContent()
                {
                        /*
                                This function will allow the user to update "pages" in the current article.
                                All values from the previous form will be added to this form.
                        */

                         $aId = @$_GET["aId"];
                         
                         if($aId == "")
							$aId = @$_POST["aId"];
                         
                         $strTitle = @$_POST["strTitle"];
                         $intType = @$_POST["intType"];
                         $intAuthorId = @$_POST["intAuthorId"];
                         $strSummary = @$_POST["strSummary"];
                         $strTopicIds = @$_POST["strTopicIds"];
                         $strArticleIds = @$_POST["strArticleIds"];
                         $strBookIds = @$_POST["strBookIds"];
                         $strForumLink = @$_POST["strForumLink"];
                         $strRelLink1 = @$_POST["strRelLink1"];
                         $strRelLink2 = @$_POST["strRelLink2"];
                         $strRelLink3 = @$_POST["strRelLink3"];
                         $blnVisible = @$_POST["blnVisible"];
                         $intStatus = @$_POST["intStatus"];
                         $intPubMonth = @$_POST["intPubMonth"];
                         $intPubDay = @$_POST["intPubDay"];
                         $intPubYear = @$_POST["intPubYear"];
                         $intZipId = @$_POST["intZipId"];
                         $strZip = @$_FILES["strZip"]["tmp_name"];
                         $strZip_name = @$_FILES["strZip"]["name"];
                         $strZip_type = @$_FILES["strZip"]["type"];
                         $strZip_size = @$_FILES["strZip"]["size"];
                         $blnUseZip = @$_POST["blnUseZip"];                         
                         $counter = 0;

                        if($blnVisible != "1")
                        {
                                $_POST["blnVisible"] = 0;
                                $blnVisible = 0;
                        }

                        $strErr = "<ul>";

                        if($strTitle == "")
                                { $strErr .= "<li>You must enter a title</li>"; }
                        if($intType == "")
                                { $strErr .= "<li>You must select a type</li>"; }
                        if($intAuthorId == "")
                                { $strErr .= "<li>You must select an author</li>"; }
                        if($strSummary == "")
                                { $strErr .= "<li>You must enter a summary</li>"; }
                        if(!is_array($strTopicIds))
                                { $strErr .= "<li>You must select at least one topic</li>"; }
                        if( $strZip_size > 0 && ($strZip_type != "application/x-zip-compressed" || $strZip_size > 5000000))
                                { $strErr .= "<li>You must select a valid zip file</li>"; }
                        if( $strZip_size == 0 && $blnUseZip == "")
                                { $strErr .= "<li>You must select a valid zip file</li>"; }
                        if($intStatus == "")
                                { $strErr .= "<li>You must select whether this article is a draft/full copy</li>"; }

                        $strErr .= "</ul>";

                        if($strErr != "<ul></ul>")
                                {
                                        ?>
                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                        One or more fields were not completed. Please review the list of
                                                        errors below and click on the link below to go back and correct them.<br><br>
                                                        <span class="Error">
                                                                <?php echo $strErr; ?>
                                                        </span>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                </p>

                                        <?php
                                                        include("templates/dev_bottom.php");
                                                        exit;
                                }

                        /*
                                If the user has gotten to this point then all of the values that they have
                                entered are OK...we can upload the binary file if it exists and add all of
                                the parsed variables to the form on this page
                        */

                        if($strZip_size > 0 && $blnUseZip != "ON")
                                {
                                        // The user has uploaded a support file, we will temporarily store
                                        // it in the tbl_TempBlob table and then write it to our tbl_Articles table

                                        $dbVars = new dbVars();
                                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                        if($svrConn)
                                                {
                                                        $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                        if($dbConn)
                                                                {
                                                                        $strBinPic = addslashes(fread(fopen($strZip, "rb"), filesize($strZip)));
                                                                        $strQuery = "insert into tbl_TempZips values(0, '$strBinPic')";
                                                                        $result = mysql_query($strQuery);

                                                                        if(!$result)
                                                                                {
                                                                                        ?>
                                                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                                        <span class="BodyHeading">Couldn't Save Zip File</span><br><br>
                                                                                                        The zip file that you have chosen couldn't be saved to the
                                                                                                        database. Please use the link below to go back and
                                                                                                        select another zip file.<br><br>
                                                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                                                </span>
                                                                                        <?php
                                                                                                        include("templates/dev_bottom.php");
                                                                                                        exit;
                                                                                }
                                                                        $intZipId = mysql_insert_id();
                                                                }
                                                        else
                                                                {
                                                                        // Couldnt connect to the database
                                                                        ?>
                                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                        The zip file that you have chosen couldn't be saved to the
                                                                                        database. Please use the link below to go back and
                                                                                        try again.<br><br>
                                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                                </span>
                                                                        <?php
                                                                                        include("templates/dev_bottom.php");
                                                                                        exit;
                                                                }
                                                }
                                        else
                                                {
                                                        // Couldnt connect to the database server
                                                                        ?>
                                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                        The zip file that you have chosen couldn't be saved to the
                                                                                        database. Please use the link below to go back and
                                                                                        try again.<br><br>
                                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                                </span>
                                                                        <?php
                                                                                        include("templates/dev_bottom.php");
                                                                                        exit;
                                                }
                                }
                        else
                                { $intZipId = 0; }

                        /*
                                We've now got the value of the zip file's id (if one was supplied). We will create
                                the new form and add all of the previous form values as hidden form values.
                        */

                        ?>

                                <? include("includes/jscript/articles.js"); ?>

                                <script language="JavaScript">

                                        // We will setup the arrays for the page details
                                        var arrTitles = new Array();
                                        var arrContents = new Array();

                                </script>

                                <form onSubmit="return CheckAddContent()" enctype="multipart/form-data" name="frmAddContent" action="articles.php?strMethod=update2" method="post">
                                        <input type="hidden" name="intZipId" value="<?php echo $intZipId; ?>">
                                        <input type="hidden" name="strTitle1" value="">
                                        <input type="hidden" name="strTitle2" value="">
                                        <input type="hidden" name="strTitle3" value="">
                                        <input type="hidden" name="strTitle4" value="">
                                        <input type="hidden" name="strTitle5" value="">
                                        <input type="hidden" name="strTitle6" value="">
                                        <input type="hidden" name="strTitle7" value="">
                                        <input type="hidden" name="strTitle8" value="">
                                        <input type="hidden" name="strTitle9" value="">
                                        <input type="hidden" name="strTitle10" value="">
                                        <input type="hidden" name="strTitle11" value="">
                                        <input type="hidden" name="strTitle12" value="">
                                        <input type="hidden" name="strTitle13" value="">
                                        <input type="hidden" name="strTitle14" value="">
                                        <input type="hidden" name="strTitle15" value="">
                                        <input type="hidden" name="strTitle16" value="">
                                        <input type="hidden" name="strTitle17" value="">
                                        <input type="hidden" name="strTitle18" value="">
                                        <input type="hidden" name="strTitle19" value="">
                                        <input type="hidden" name="strTitle20" value="">

                                        <input type="hidden" name="strContent1" value="">
                                        <input type="hidden" name="strContent2" value="">
                                        <input type="hidden" name="strContent3" value="">
                                        <input type="hidden" name="strContent4" value="">
                                        <input type="hidden" name="strContent5" value="">
                                        <input type="hidden" name="strContent6" value="">
                                        <input type="hidden" name="strContent7" value="">
                                        <input type="hidden" name="strContent8" value="">
                                        <input type="hidden" name="strContent9" value="">
                                        <input type="hidden" name="strContent10" value="">
                                        <input type="hidden" name="strContent11" value="">
                                        <input type="hidden" name="strContent12" value="">
                                        <input type="hidden" name="strContent13" value="">
                                        <input type="hidden" name="strContent14" value="">
                                        <input type="hidden" name="strContent15" value="">
                                        <input type="hidden" name="strContent16" value="">
                                        <input type="hidden" name="strContent17" value="">
                                        <input type="hidden" name="strContent18" value="">
                                        <input type="hidden" name="strContent19" value="">
                                        <input type="hidden" name="strContent20" value="">

                                        <?php

                                                $locKeys = array_keys($_POST);

                                                for($counter=0; $counter<sizeof($locKeys); $counter++)
                                                        {
                                                                $thisName = "";
                                                                $thisValue = "";
                                                                $thisName = $locKeys[$counter];

                                                                if(is_array($_POST[$locKeys[$counter]]))
                                                                        {
                                                                                for($aCounter=0; $aCounter<count($_POST[$locKeys[$counter]]); $aCounter++)
                                                                                        { $thisValue .= $_POST[$locKeys[$counter]][$aCounter] . ","; }

                                                                                if(substr($thisValue, strlen($thisValue)-1, 1) == ",")
                                                                                        { $thisValue = stripslashes(substr($thisValue, 0, strlen($thisValue)-1)); }
                                                                        }
                                                                else
                                                                        { $thisValue = stripslashes($_POST[$locKeys[$counter]]); }

                                                                echo "<input type='hidden' name='$thisName' value='$thisValue'>";
                                                        }
                                        ?>

                                        <div align="center">
                                        <table width="95%" cellspacing="0" cellpadding="0" border="0">
                                                <tr>
                                                        <td width="100%" colspan="2">
                                                                <p class="BodyText">
                                                                        <span class="BodyHeading">Update Article Content [Step 2/2]</span><br><br>
                                                                        You are half way through updating the selected article. Use
                                                                        the form and buttons below to update the content of this article. You can add a maximum
                                                                        of twenty pages.<br><br>
                                                                </p>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="100%" colspan="2" background="doth.gif">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td valign="top" width="100%" colspan="2">
                                                                <span class="ParaHeading">Article Pages:</span><br>
                                                                <select onChange="LoadPage()" name="txtPages" style="width:545; height:80" size="5">
                                                                </select><br>
                                                                <input onClick="ClearPages()" name="cmdClear" title="Click here to remove all pages from this article" type="button" value="Remove All Pages" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input onClick="MoveUp()" name="cmdUp" title="Click here to move the selected page up one spot" type="button" value=" « " style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input onClick="MoveDown()" name="cmdDown" title="Click here to move the selected page down one spot" type="button" value=" » " style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input onClick="DelPage()" name="cmdDel" title="Click here to delete the selected page" type="button" value=" x " style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="100%" colspan="2" background="doth.gif">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td valign="top" width="100%" colspan="2">
                                                                <span class="ParaHeading">Current Page Title:</span><br>
                                                                <input name="txtTitle" type="text" style="width:540" maxlength="100"><br>
                                                                <?php PrintWYSIWYGTable("", 540, 300); ?>
                                                                <input type="hidden" name="txtContent"><br>
                                                                <input onClick="AddPage()" name="cmdAdd" title="Click here to add the current content to a new page" type="button" value="Add Page" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input onClick="UpdatePage()" name="cmdUpdate" title="Click here to update the current page" type="button" value="Update Page" style="font-size: 8pt; font-family: Verdana; font-weight: normal" DISABLED>
                                                                <input onClick="ClearPage()" name="cmdClearPage" title="Click here to clear the details of the current page" type="button" value="Clear Content" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input onClick="Cancel()" name="cmdCancel" title="Click here to cancel the current operation" type="button" value=" New Page " style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="100%" colspan="2">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="100%" colspan="2" background="doth.gif">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="100%" colspan="2">
                                                                <div align="right">
                                                                        <input type="button" value="Cancel" onClick="history.go(-2)" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                        <input name="cmdSubmit" type="submit" value="Update Article »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                </div>
                                                        </td>
                                                </tr>
                                        </table>
                                        </div>
                                </form>



                                <? include("includes/jscript/articles.js"); ?>

                                <script language="JavaScript">

                                        // We will setup the arrays for the page details.
                                        // Hidden PHP script will also add the details of the current pages to the array

                                        var arrTitles = new Array();
                                        var arrContents = new Array();

                                        <?php

                                        $dbVars = new dbVars();
                                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                        if($svrConn)
                                                {
                                                        $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                        if($dbConn)
                                                                {
                                                                        $strQuery = "select * from tbl_ArticlePages where apArticleId = $aId order by apPage asc";
                                                                        $results = mysql_query($strQuery);
                                                                        $counter = 0;

                                                                        while($row = mysql_fetch_row($results))
                                                                                {
                                                                                        ?>
                                                                                                arrTitles.length++;
                                                                                                arrContents.length++;
                                                                                                arrTitles[<?php echo $counter; ?>] = "<?php echo addslashes($row[2]); ?>";
                                                                                                arrContents[<?php echo $counter; ?>] = "<?php echo str_replace("\r\n", "\\r\\n", addslashes($row[4])); ?>";
                                                                                        <?php
                                                                                        $counter++;
                                                                                }
                                                                }
                                                }

                                        ?>

                                        ReloadPages();

                                </script>

                        <?php
                }

        function ShowUpdateForm()
                {
                        /*
                                This form will allow the user to firstly update the initial details of the article
                                and then the page contents of the article. If we just want to update some aspects
                                of the article then we can.
                        */

                        $aId = @$_GET["aId"];
                        global $publisherAccess;

                        $dbVars = new dbVars();
                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                        // We will make sure that we have a valid numerical article identifier to work with

                        if(!is_numeric($aId))
                                {
                                        ?>
                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <span class="BodyHeading">Invalid Article Id</span><br><br>
                                                        The article id that you have selected is invalid. Please use the Go Back
                                                        link below to go back and select a proper article to update.<br><br>
                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                </p>
                                        <?php
                                                        include("templates/dev_bottom.php");
                                                        exit;
                                }

                        if($svrConn)
                                {
                                        // Connected to the database server OK
                                        $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                        if($dbConn)
                                                {
                                                        //Connected to the database OK
                                                        $strQuery = "select * from tbl_Articles where pk_aId = $aId";
                                                        $results = mysql_query($strQuery);

                                                        if($aRow = mysql_fetch_array($results))
                                                                {
                                                                        // The article exists, setup the date
                                                                        $todaysMonth = substr($aRow["aDateCreated"], 4, 2);
                                                                        $todaysDay = substr($aRow["aDateCreated"], 6, 2);
                                                                        $todaysYear = substr($aRow["aDateCreated"], 0, 4);
                                                                        ?>

                                                                        <?php include("includes/jscript/articles.js"); ?>

                                                                        <form onSubmit="return CheckUpdateArticle()" enctype="multipart/form-data" name="frmAddArticle" action="articles.php?strMethod=update1" method="post">
                                                                        <input type="hidden" name="aId" value="<?php echo $aId; ?>">
                                                                        <div align="center">
                                                                        <table border="0" width="95%" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                        <td colspan="2" width="100%">
                                                                                                <p class="BodyText">
                                                                                                        <span class="BodyHeading">Update Article [Step 1/2]</span><br><br>
                                                                                                        Use the form below to modify the selected article.
                                                                                                        Be sure to select all related articles, books and URL links for this article.
                                                                                                        <br><br>
                                                                                                </p>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="2" width="100%" background="doth.gif">
                                                                                                &nbsp;
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Title:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <input type="text" name="strTitle" maxlength="100" size="60" value="<?php echo $aRow["aTitle"]; ?>">
                                                                                                <span class="Error">*</span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Article Type:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <select name="intType" style="width:385">
                                                                                                        <option value="NULL">Select Article Type</option>
                                                                                                        <option <?php if($aRow["aDocType"] == 1) echo " SELECTED " ?> value="1">Tutorial</option>
                                                                                                        <option <?php if($aRow["aDocType"] == 2) echo " SELECTED " ?> value="2">Review</option>
                                                                                                        <option <?php if($aRow["aDocType"] == 3) echo " SELECTED " ?> value="3">Summary</option>
                                                                                                        <option <?php if($aRow["aDocType"] == 4) echo " SELECTED " ?> value="4">Tip</option>
                                                                                                        <option <?php if($aRow["aDocType"] == 5) echo " SELECTED " ?> value="5">Interview</option>
                                                                                                </select>
                                                                                                <span class="Error">*</span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Author:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <select name="intAuthorId" style="width:385">
                                                                                                        <option selected value="NULL">Select Author</option>
                                                                                                        <?php

                                                                                                                // We will get a list of authors from the tbl_AdminLogins table
                                                                                                                $strQuery = "select pk_alId, alFName, alLName from tbl_AdminLogins order by alFName asc, alLName asc";
                                                                                                                $results = mysql_query($strQuery);

                                                                                                                while($row = mysql_fetch_array($results))
                                                                                                                        { echo "<option ";
                                                                                                                          if($aRow["aAuthorId"] == $row["pk_alId"])
                                                                                                                                { echo " SELECTED "; }
                                                                                                                          echo "value='{$row["pk_alId"]}'>{$row["alFName"]} {$row["alLName"]}</option>";
                                                                                                                        }
                                                                                                        ?>
                                                                                                </select>
                                                                                                <span class="Error">*</span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%" valign="top">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Summary:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <textarea name="strSummary" style="width:385;height:110"><?php echo $aRow["aSummary"]; ?></textarea>
                                                                                                <span class="Error">*</span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%" valign="top">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Topics:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <select name="strTopicIds[]" multiple style="width:385">
                                                                                                        <?php

                                                                                                                /*
                                                                                                                        Firstly, we will get the current topic id's for this
                                                                                                                        article and stored them in an array, next, if they are matched
                                                                                                                        by the topics in the tbl_Topics table then we will display
                                                                                                                        them as selected.
                                                                                                                */

                                                                                                                $arrTopics = explode(",", $aRow["aTopicIds"]);

                                                                                                                /*
                                                                                                                        We will get a list of topics from the tbl_Topics
                                                                                                                        table and display them in a select box
                                                                                                                */

                                                                                                                $strQuery = "select * from tbl_Topics order by tName asc";
                                                                                                                $results = mysql_query($strQuery);

                                                                                                                while($row = mysql_fetch_array($results))
                                                                                                                        { echo "<option ";
                                                                                                                          for($counter=0; $counter<sizeof($arrTopics); $counter++)
                                                                                                                                {
                                                                                                                                        if($arrTopics[$counter] == $row["pk_tId"])
                                                                                                                                                { echo " SELECTED "; }
                                                                                                                                }
                                                                                                                          echo "value='{$row["pk_tId"]}'>{$row["tName"]}</option>";
                                                                                                                        }
                                                                                                        ?>
                                                                                                </select>
                                                                                                <span class="Error">*</span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%" valign="top">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Related Articles:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <select name="strArticleIds[]" multiple style="width:385">
                                                                                                        <?php

                                                                                                                /*
                                                                                                                        We will get a list of related articles for this
                                                                                                                        article into an array.
                                                                                                                */

                                                                                                                $arrRelArt = explode(",", $aRow["aArticleIds"]);

                                                                                                                /*
                                                                                                                        We will get a list of current article and display
                                                                                                                        them in the select box
                                                                                                                */

                                                                                                                $strQuery = "select pk_aId, aTitle from tbl_Articles order by aTitle asc";
                                                                                                                $results = mysql_query($strQuery);
                                                                                                                while($row = mysql_fetch_row($results))
                                                                                                                        { if($row[0] != $aId)
                                                                                                                                {
                                                                                                                                        echo "<option ";
                                                                                                                                         for($counter=0; $counter<sizeof($arrRelArt); $counter++)
                                                                                                                                                {
                                                                                                                                                        if($arrRelArt[$counter] == $row[0])
                                                                                                                                                                { echo " SELECTED "; }
                                                                                                                                                }
                                                                                                                                         echo "value='{$row[0]}'>{$row[1]}</option>";
                                                                                                                                }
                                                                                                                        }
                                                                                                        ?>
                                                                                                </select>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%" valign="top">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Related Books:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <select name="strBookIds[]" multiple style="width:385">
                                                                                                        <?php

                                                                                                                // We will get a list of books for the article
                                                                                                                $arrBooks = explode(",", $aRow["aBookIds"]);

                                                                                                                /*
                                                                                                                        We will get a list of current article and display
                                                                                                                        them in the select box
                                                                                                                */

                                                                                                                $strQuery = "select pk_bId, bTitle from tbl_Books order by bTitle asc";
                                                                                                                $results = mysql_query($strQuery);
                                                                                                                while($row = mysql_fetch_row($results))
                                                                                                                        { echo "<option ";
                                                                                                                          for($counter=0; $counter<sizeof($arrBooks); $counter++)
                                                                                                                                {
                                                                                                                                        if($arrBooks[$counter] == $row[0])
                                                                                                                                                { echo " SELECTED "; }
                                                                                                                                }
                                                                                                                          echo " value='{$row[0]}'>{$row[1]}</option>";
                                                                                                                        }
                                                                                                        ?>
                                                                                                </select>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Forum Link:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <input type="text" name="strForumLink" maxlength="255" size="60" value="<?php echo $aRow["aForumLink"]; ?>">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Related Link #1:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <input type="text" name="strRelLink1" maxlength="255" size="60" value="<?php echo $aRow["aLink1"]; ?>">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Related Link #2:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <input type="text" name="strRelLink2" maxlength="255" size="60" value="<?php echo $aRow["aLink2"]; ?>">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Related Link #3:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <input type="text" name="strRelLink3" maxlength="255" size="60" value="<?php echo $aRow["aLink3"]; ?>">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%" valign="top">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Zip Support File:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <span class="BodyText">
                                                                                                        <input onKeyDown="blnUseZip.checked = false" onClick="blnUseZip.checked = false" type="file" name="strZip" style="width:385"><br>
                                                                                                        <input type="checkbox" name="blnUseZip" value="ON" CHECKED> Use Current Support File?
                                                                                                        <?php

                                                                                                                if($aRow["aSupportFile"] == 0)
                                                                                                                        echo " [None Uploaded]";

                                                                                                        ?>
                                                                                                </span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Status:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <span class="BodyText">
                                                                                                  <select name="intStatus" style="width:385" size="2">
                                                                                                        <option <?php if($aRow["aStatus"] == 0) echo " SELECTED "; ?> value="0">Draft Version</option>
                                                                                                    <option <?php if($aRow["aStatus"] == 1) echo " SELECTED "; ?> value="1">Final Version</option>
                                                                                                  </select>
                                                                                                </span>
                                                                                                <span class="Error">*</span>
                                                                                                </span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Published:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <select name="intPubMonth">
                                                                                                <?php
                                                                                                        
																									for($i = 1; $i <= 12; $i++)
																									{
																										echo "<option value='$i' ";
																										if($i == (int)$todaysMonth)
																											echo " SELECTED ";
																										echo ">$i</option>";
																									}
                                                                                                ?>
                                                                                                </select>
                                                                                                /
                                                                                                <select name="intPubDay">
                                                                                                <?php
                                                                                                        
																									for($i = 1; $i <= 31; $i++)
																									{
																										echo "<option value='$i' ";
																										if($i == (int)$todaysDay)
																											echo " SELECTED ";
																										echo ">$i</option>";
																									}
                                                                                                ?>
                                                                                                </select>
                                                                                                /
                                                                                                <select name="intPubYear">
                                                                                                <?php
                                                                                                        
																									if($todaysYear <= 0)
																										$todaysYear = date("Y");
																									
																									for($i = $todaysYear-5; $i < $todaysYear+5; $i++)
																									{
																										echo "<option value='$i' ";
																										if($i == (int)$todaysYear)
																											echo " SELECTED ";
																										echo ">$i</option>";
																									}
                                                                                                ?>
                                                                                                </select>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Visible:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <span class="BodyText">
                                                                                                        <?php

                                                                                                                // If the user is a publisher, does he have access to
                                                                                                                // make new articles visible on the web site?

                                                                                                                if(IsLoggedIn() == 3 || in_array("activate_articles", $publisherAccess))
                                                                                                                {
                                                                                                                ?>
                                                                                                                        <input  <?php if($aRow["aActive"] == 1) echo " CHECKED "; ?> type="checkbox" name="blnVisible" value="1"> Yes, this article should be visible on my site
                                                                                                                <?php
                                                                                                                }
                                                                                                                else
                                                                                                                {
                                                                                                                ?>
                                                                                                                        <input type="hidden" name="blnVisible" value="">
                                                                                                                        No, this article should not be visible
                                                                                                                <?php
                                                                                                                }
                                                                                                                ?>
                                                                                                </span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="100%" colspan="2">
                                                                                                <br>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="20%" colspan="1">
                                                                                        </td>
                                                                                        <td width="80%" colspan="2">
                                                                                                <input type="button" value="Cancel" onClick="history.go(-1)" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                                                <input type="submit" value="Next »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                                                <br><br>
                                                                                        </td>
                                                                                </tr>

                                                                        </table>
                                                                        </div>
                                                                        </form>

                                                                        <?php

                                                                }
                                                        else
                                                                {
                                                                        // The record doesn't exist in the database anymore

                                                                }
                                                }
                                        else
                                                {
                                                        // Couldn't connect to the database

                                                }
                                }
                        else
                                {
                                        // Couldn't connect to the database server

                                }
                }

        function ProcessNew()
                {
                        /*
                                This function will display the form through which a new article
                                can be added. The form also allows the user to add page content
                                to the articlePages table.
                        */
                        
                                global $siteName;
                                global $publisherAccess;

                                $dbVars = new dbVars();
                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                if($svrConn)
                                        {
                                                // Connected to the database server OK
                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                if($dbConn)
                                                        {
                                                                // Connected to the database OK


                                                                ?>

                                                                        <?php include("includes/jscript/articles.js"); ?>

                                                                        <form onSubmit="return CheckAddArticle()" enctype="multipart/form-data" name="frmAddArticle" action="articles.php?strMethod=addnew1" method="post">
                                                                        <div align="center">
                                                                        <table border="0" width="95%" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                        <td colspan="2" width="100%">
                                                                                                <p class="BodyText">
                                                                                                        <span class="BodyHeading">Add New Article [Step 1/2]</span><br><br>
                                                                                                        Use the form below to add a new article to the <?php echo $siteName; ?> database.
                                                                                                        Be sure to select all related articles, books and URL links for this article.
                                                                                                        <br><br>
                                                                                                </p>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="2" width="100%" background="doth.gif">
                                                                                                &nbsp;
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Title:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <input type="text" name="strTitle" maxlength="100" size="60">
                                                                                                <span class="Error">*</span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Article Type:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <select name="intType" style="width:385">
                                                                                                        <option selected value="NULL">Select Article Type</option>
                                                                                                        <option value="1">Tutorial</option>
                                                                                                        <option value="2">Review</option>
                                                                                                        <option value="3">Summary</option>
                                                                                                        <option value="4">Tip</option>
                                                                                                        <option value="5">Interview</option>
                                                                                                </select>
                                                                                                <span class="Error">*</span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Author:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <select name="intAuthorId" style="width:385">
                                                                                                        <option selected value="NULL">Select Author</option>
                                                                                                        <?php

                                                                                                                // We will get a list of authors from the tbl_AdminLogins table
                                                                                                                $strQuery = "select pk_alId, alFName, alLName from tbl_AdminLogins order by alFName asc, alLName asc";
                                                                                                                $results = mysql_query($strQuery);

                                                                                                                while($row = mysql_fetch_array($results))
                                                                                                                        { echo "<option value='{$row["pk_alId"]}'>{$row["alFName"]} {$row["alLName"]}</option>"; }
                                                                                                        ?>
                                                                                                </select>
                                                                                                <span class="Error">*</span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%" valign="top">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Summary:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <textarea name="strSummary" style="width:385;height:110"></textarea>
                                                                                                <span class="Error">*</span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%" valign="top">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Topics:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <select name="strTopicIds[]" multiple style="width:385">
                                                                                                        <?php

                                                                                                                /*
                                                                                                                        We will get a list of topics from the tbl_Topics
                                                                                                                        table and display them in a select box
                                                                                                                */

                                                                                                                $strQuery = "select * from tbl_Topics order by tName asc";
                                                                                                                $results = mysql_query($strQuery);

                                                                                                                while($row = mysql_fetch_array($results))
                                                                                                                        { echo "<option value='{$row["pk_tId"]}'>{$row["tName"]}</option>"; }
                                                                                                        ?>
                                                                                                </select>
                                                                                                <span class="Error">*</span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%" valign="top">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Related Articles:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <select name="strArticleIds[]" multiple style="width:385">
                                                                                                        <?php

                                                                                                                /*
                                                                                                                        We will get a list of current article and display
                                                                                                                        them in the select box
                                                                                                                */

                                                                                                                $strQuery = "select pk_aId, aTitle from tbl_Articles order by aTitle asc";
                                                                                                                $results = mysql_query($strQuery);
                                                                                                                while($row = mysql_fetch_row($results))
                                                                                                                        { echo "<option value='{$row[0]}'>{$row[1]}</option>"; }
                                                                                                        ?>
                                                                                                </select>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%" valign="top">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Related Books:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <select name="strBookIds[]" multiple style="width:385">
                                                                                                        <?php

                                                                                                                /*
                                                                                                                        We will get a list of current article and display
                                                                                                                        them in the select box
                                                                                                                */

                                                                                                                $strQuery = "select pk_bId, bTitle from tbl_Books order by bTitle asc";
                                                                                                                $results = mysql_query($strQuery);
                                                                                                                while($row = mysql_fetch_row($results))
                                                                                                                        { echo "<option value='{$row[0]}'>{$row[1]}</option>"; }
                                                                                                        ?>
                                                                                                </select>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Forum Link:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <input type="text" name="strForumLink" maxlength="255" size="60">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Related Link #1:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <input type="text" name="strRelLink1" maxlength="255" size="60">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Related Link #2:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <input type="text" name="strRelLink2" maxlength="255" size="60">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Related Link #3:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <input type="text" name="strRelLink3" maxlength="255" size="60">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Zip Support File:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <input type="file" name="strZip" style="width:385">
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Status:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <span class="BodyText">
                                                                                                  <select name="intStatus" style="width:385" size="2">
                                                                                                        <option value="0">Draft Version</option>
                                                                                                    <option value="1" selected>Final Version</option>
                                                                                                  </select>
                                                                                                </span>
                                                                                                <span class="Error">*</span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Published:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <select name="intPubMonth">
                                                                                                <?php
                                                                                                        
																									$todaysMonth = date("m");
																											
																									for($i = 1; $i <= 12; $i++)
																									{
																										echo "<option value='$i' ";
																										if($i == (int)$todaysMonth)
																											echo " SELECTED ";
																										echo ">$i</option>";
																									}
                                                                                                ?>
                                                                                                </select>
                                                                                                /
                                                                                                <select name="intPubDay">
                                                                                                <?php
                                                                                                        
																									$todaysDay = date("d");
																											
																									for($i = 1; $i <= 31; $i++)
																									{
																										echo "<option value='$i' ";
																										if($i == (int)$todaysDay)
																											echo " SELECTED ";
																										echo ">$i</option>";
																									}
                                                                                                ?>
                                                                                                </select>
                                                                                                /
                                                                                                <select name="intPubYear">
                                                                                                <?php
                                                                                                        
																									$todaysYear = date("Y");
																											
																									for($i = $todaysYear-5; $i < $todaysYear+5; $i++)
																									{
																										echo "<option value='$i' ";
																										if($i == (int)$todaysYear)
																											echo " SELECTED ";
																										echo ">$i</option>";
																									}
                                                                                                ?>
                                                                                                </select>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td colspan="1" width="20%">
                                                                                                <div align="right">
                                                                                                        <span class="BodyText">
                                                                                                                Visible:&nbsp;&nbsp;
                                                                                                        </span>
                                                                                                </div>
                                                                                        </td>
                                                                                        <td colspan="1" width="80%">
                                                                                                <span class="BodyText">
                                                                                                        <?php

                                                                                                                // If the user is a publisher, does he have access to
                                                                                                                // make new articles visible on the web site?

                                                                                                                if(IsLoggedIn() == 3 || in_array("activate_articles", $publisherAccess))
                                                                                                                {
                                                                                                                ?>
                                                                                                                        <input type="checkbox" name="blnVisible" value="1"> Yes, this article should be visible on my site
                                                                                                                <?php
                                                                                                                }
                                                                                                                else
                                                                                                                {
                                                                                                                ?>
                                                                                                                        <input type="hidden" name="blnVisible" value="">
                                                                                                                        No, this article should not be visible
                                                                                                                <?php
                                                                                                                }
                                                                                                                ?>
                                                                                                </span>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="100%" colspan="2">
                                                                                                <br>
                                                                                        </td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <td width="20%" colspan="1">
                                                                                        </td>
                                                                                        <td width="80%" colspan="2">
                                                                                                <input type="button" value="Cancel" onClick="history.go(-1)" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                                                <input type="submit" value="Next »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                                                <br><br>
                                                                                        </td>
                                                                                </tr>

                                                                        </table>
                                                                        </div>
                                                                        </form>

                                                                <?php

                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database

                                                        }
                                        }
                                else
                                        {
                                                // Couldnt connect to the database server

                                        }
                        ?>

                        <?php
                }

        function ProcessNewContent()
                {
                        /*
                                This function will allow the user to add "pages" to the current article.
                                All values from the previous form will be added to this form.
                        */

                         $strTitle = @$_POST["strTitle"];
                         $intType = @$_POST["intType"];
                         $intAuthorId = @$_POST["intAuthorId"];
                         $strSummary = @$_POST["strSummary"];
                         $strTopicIds = @$_POST["strTopicIds"];
                         $strArticleIds = @$_POST["strArticleIds"];
                         $strBookIds = @$_POST["strBookIds"];
                         $strForumLink = @$_POST["strForumLink"];
                         $strRelLink1 = @$_POST["strRelLink1"];
                         $strRelLink2 = @$_POST["strRelLink2"];
                         $strRelLink3 = @$_POST["strRelLink3"];
                         $blnVisible = @$_POST["blnVisible"];
                         $intStatus = @$_POST["intStatus"];
                         $intPubMonth = @$_POST["intPubMonth"];
                         $intPubDay = @$_POST["intPubDay"];
                         $intPubYear = @$_POST["intPubYear"];
                         $intZipId = @$_POST["intZipId"];
                         $strZip = @$_FILES["strZip"]["tmp_name"];
                         $strZip_name = @$_FILES["strZip"]["name"];
                         $strZip_type = @$_FILES["strZip"]["type"];
                         $strZip_size = @$_FILES["strZip"]["size"];
                         $counter = 0;
                         
                        if($blnVisible == "")
                        {
							$_POST["blnVisible"] = 0;
							$blnVisible = 0;
                        }

                        $strErr = "<ul>";

                        if($strTitle == "")
                                { $strErr .= "<li>You must enter a title</li>"; }
                        if($intType == "")
                                { $strErr .= "<li>You must select a type</li>"; }
                        if($intAuthorId == "")
                                { $strErr .= "<li>You must select an author</li>"; }
                        if($strSummary == "")
                                { $strErr .= "<li>You must enter a summary</li>"; }
                        if(!is_array($strTopicIds))
                                { $strErr .= "<li>You must select at least one topic. To add a new topic, <a href='topics.php?strMethod=addnew'>click here</a>.</li>"; }
                        if( $strZip_size > 0 && ($strZip_type != "application/x-zip-compressed" || $strZip_size > 5000000))
                                { $strErr .= "<li>You must select a valid zip file</li>"; }
                        if($intStatus == "")
                                { $strErr .= "<li>You must select whether this article is a draft/full copy</li>"; }

                        $strErr .= "</ul>";

                        if($strErr != "<ul></ul>")
                                {
                                        ?>
                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                        One or more fields were not completed. Please review the list of
                                                        errors below and click on the link below to go back and correct them.<br><br>
                                                        <span class="Error">
                                                                <?php echo $strErr; ?>
                                                        </span>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                </p>

                                        <?php
                                                        include("templates/dev_bottom.php");
                                                        exit;
                                }

                        /*
                                If the user has gotten to this point then all of the values that they have
                                entered are OK...we can upload the binary file if it exists and add all of
                                the parsed variables to the form on this page
                        */

                        if($strZip_size > 0)
                                {
                                        // The user has uploaded a support file, we will temporarily store
                                        // it in the tbl_TempBlob table and then write it to our tbl_Articles table

                                        $dbVars = new dbVars();
                                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                        if($svrConn)
                                                {
                                                        $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                        if($dbConn)
                                                                {
                                                                        $strBinPic = addslashes(fread(fopen($strZip, "r"), filesize($strZip)));
                                                                        $strQuery = "insert into tbl_TempZips values(0, '$strBinPic')";
                                                                        $result = mysql_query($strQuery);

                                                                        if(!$result)
                                                                                {
                                                                                        ?>
                                                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                                        <span class="BodyHeading">Couldn't Save Zip File</span><br><br>
                                                                                                        The zip file that you have chosen couldn't be saved to the
                                                                                                        database. Please use the link below to go back and
                                                                                                        select another zip file.<br><br>
                                                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                                                </span>
                                                                                        <?php
                                                                                                        include("templates/dev_bottom.php");
                                                                                                        exit;
                                                                                }
                                                                        $intZipId = mysql_insert_id();
                                                                }
                                                        else
                                                                {
                                                                        // Couldnt connect to the database
                                                                        ?>
                                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                        The zip file that you have chosen couldn't be saved to the
                                                                                        database. Please use the link below to go back and
                                                                                        try again.<br><br>
                                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                                </span>
                                                                        <?php
                                                                                        include("templates/dev_bottom.php");
                                                                                        exit;
                                                                }
                                                }
                                        else
                                                {
                                                        // Couldnt connect to the database server
                                                                        ?>
                                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                        The zip file that you have chosen couldn't be saved to the
                                                                                        database. Please use the link below to go back and
                                                                                        try again.<br><br>
                                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                                </span>
                                                                        <?php
                                                                                        include("templates/dev_bottom.php");
                                                                                        exit;
                                                }
                                }
                        else
                                { $intZipId = 0; }

                        /*
                                We've now got the value of the zip file's id (if one was supplied). We will create
                                the new form and add all of the previous form values as hidden form values.
                        */

                        ?>

                                <? include("includes/jscript/articles.js"); ?>

                                <script language="JavaScript">

                                        // We will setup the arrays for the page details
                                        var arrTitles = new Array();
                                        var arrContents = new Array();

                                </script>

                                <form onSubmit="return CheckAddContent()" enctype="multipart/form-data" name="frmAddContent" action="articles.php?strMethod=addnew2" method="post">
                                        <input type="hidden" name="intZipId" value="<?php echo $intZipId; ?>">
                                        <input type="hidden" name="strTitle1" value="">
                                        <input type="hidden" name="strTitle2" value="">
                                        <input type="hidden" name="strTitle3" value="">
                                        <input type="hidden" name="strTitle4" value="">
                                        <input type="hidden" name="strTitle5" value="">
                                        <input type="hidden" name="strTitle6" value="">
                                        <input type="hidden" name="strTitle7" value="">
                                        <input type="hidden" name="strTitle8" value="">
                                        <input type="hidden" name="strTitle9" value="">
                                        <input type="hidden" name="strTitle10" value="">
                                        <input type="hidden" name="strTitle11" value="">
                                        <input type="hidden" name="strTitle12" value="">
                                        <input type="hidden" name="strTitle13" value="">
                                        <input type="hidden" name="strTitle14" value="">
                                        <input type="hidden" name="strTitle15" value="">
                                        <input type="hidden" name="strTitle16" value="">
                                        <input type="hidden" name="strTitle17" value="">
                                        <input type="hidden" name="strTitle18" value="">
                                        <input type="hidden" name="strTitle19" value="">
                                        <input type="hidden" name="strTitle20" value="">

                                        <input type="hidden" name="strContent1" value="">
                                        <input type="hidden" name="strContent2" value="">
                                        <input type="hidden" name="strContent3" value="">
                                        <input type="hidden" name="strContent4" value="">
                                        <input type="hidden" name="strContent5" value="">
                                        <input type="hidden" name="strContent6" value="">
                                        <input type="hidden" name="strContent7" value="">
                                        <input type="hidden" name="strContent8" value="">
                                        <input type="hidden" name="strContent9" value="">
                                        <input type="hidden" name="strContent10" value="">
                                        <input type="hidden" name="strContent11" value="">
                                        <input type="hidden" name="strContent12" value="">
                                        <input type="hidden" name="strContent13" value="">
                                        <input type="hidden" name="strContent14" value="">
                                        <input type="hidden" name="strContent15" value="">
                                        <input type="hidden" name="strContent16" value="">
                                        <input type="hidden" name="strContent17" value="">
                                        <input type="hidden" name="strContent18" value="">
                                        <input type="hidden" name="strContent19" value="">
                                        <input type="hidden" name="strContent20" value="">

                                        <?php

                                                $locKeys = array_keys($_POST);

                                                for($counter=0; $counter<sizeof($locKeys); $counter++)
                                                        {
                                                                $thisName = "";
                                                                $thisValue = "";
                                                                $thisName = $locKeys[$counter];

                                                                if(is_array($_POST[$locKeys[$counter]]))
                                                                        {
																			for($aCounter=0; $aCounter<count($_POST[$locKeys[$counter]]); $aCounter++)
																			        { $thisValue .= $_POST[$locKeys[$counter]][$aCounter] . ","; }

																			if(substr($thisValue, strlen($thisValue)-1, 1) == ",")
																			        { $thisValue = stripslashes(substr($thisValue, 0, strlen($thisValue)-1)); }
                                                                        }
                                                                else
                                                                        { $thisValue = stripslashes($_POST[$locKeys[$counter]]); }

                                                                echo "<input type='hidden' name='$thisName' value='$thisValue'>";
                                                        }
                                        ?>

                                        <div align="center">
                                        <table width="95%" cellspacing="0" cellpadding="0" border="0">
                                                <tr>
                                                        <td width="100%" colspan="2">
                                                                <p class="BodyText">
                                                                        <span class="BodyHeading">Add Article Content [Step 2/2]</span><br><br>
                                                                        You are half way through adding a new article to the <?php echo $siteName; ?> database. Use
                                                                        the form and buttons below to add content to this article. You can add a maximum
                                                                        of twenty pages.<br><br>
                                                                </p>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="100%" colspan="2" background="doth.gif">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td valign="top" width="100%" colspan="2">
                                                                <span class="ParaHeading">Article Pages:</span><br>
                                                                <select onChange="LoadPage()" name="txtPages" style="width:545; height:80" size="5">
                                                                </select><br>
                                                                <input onClick="ClearPages()" name="cmdClear" title="Click here to remove all pages from this article" type="button" value="Remove All Pages" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input onClick="MoveUp()" name="cmdUp" title="Click here to move the selected page up one spot" type="button" value=" « " style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input onClick="MoveDown()" name="cmdDown" title="Click here to move the selected page down one spot" type="button" value=" » " style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input onClick="DelPage()" name="cmdDel" title="Click here to delete the selected page" type="button" value=" x " style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="100%" colspan="2" background="doth.gif">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td valign="top" width="100%" colspan="2">
                                                                <span class="ParaHeading">Current Page Title:</span><br>
                                                                <input name="txtTitle" type="text" style="width:540" maxlength="100"><br>
                                                                <?php PrintWYSIWYGTable("", 540, 300); ?>
                                                                <input type="hidden" name="txtContent"><br>
                                                                <input onClick="AddPage()" name="cmdAdd" title="Click here to add the current content to a new page" type="button" value="Add Page" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input onClick="UpdatePage()" name="cmdUpdate" title="Click here to update the current page" type="button" value="Update Page" style="font-size: 8pt; font-family: Verdana; font-weight: normal" DISABLED>
                                                                <input onClick="ClearPage()" name="cmdClearPage" title="Click here to clear the details of the current page" type="button" value="Clear Content" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input onClick="Cancel()" name="cmdCancel" title="Click here to cancel the current operation" type="button" value=" New Page " style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="100%" colspan="2">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="100%" colspan="2" background="doth.gif">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="100%" colspan="2">
                                                                <div align="right">
                                                                        <input type="button" value="Cancel" onClick="history.go(-2)" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                        <input name="cmdSubmit" type="submit" value="Add Article »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                </div>
                                                        </td>
                                                </tr>
                                        </table>
                                        </div>
                                </form>

                        <?php
                }

        function AddNew()
                {
                        /*
                                This function will actually add our article to the tbl_Articles and tbl_ArticlePages
                                tables.
                        */
                        
                         $aId = @$_POST["aId"];
                         $strTitle1 = @$_POST["strTitle1"];
                         $strTitle2 = @$_POST["strTitle2"];
                         $strTitle3 = @$_POST["strTitle3"];
                         $strTitle4 = @$_POST["strTitle4"];
                         $strTitle5 = @$_POST["strTitle5"];
                         $strTitle6 = @$_POST["strTitle6"];
                         $strTitle7 = @$_POST["strTitle7"];
                         $strTitle8 = @$_POST["strTitle8"];
                         $strTitle9 = @$_POST["strTitle9"];
                         $strTitle10 = @$_POST["strTitle10"];
                         $strTitle11 = @$_POST["strTitle11"];
                         $strTitle12 = @$_POST["strTitle12"];
                         $strTitle13 = @$_POST["strTitle13"];
                         $strTitle14 = @$_POST["strTitle14"];
                         $strTitle15 = @$_POST["strTitle15"];
                         $strTitle16 = @$_POST["strTitle16"];
                         $strTitle17 = @$_POST["strTitle17"];
                         $strTitle18 = @$_POST["strTitle18"];
                         $strTitle19 = @$_POST["strTitle19"];
                         $strTitle20 = @$_POST["strTitle20"];
                         $strContent1 = @$_POST["strContent1"];
                         $strContent2 = @$_POST["strContent2"];
                         $strContent3 = @$_POST["strContent3"];
                         $strContent4 = @$_POST["strContent4"];
                         $strContent5 = @$_POST["strContent5"];
                         $strContent6 = @$_POST["strContent6"];
                         $strContent7 = @$_POST["strContent7"];
                         $strContent8 = @$_POST["strContent8"];
                         $strContent9 = @$_POST["strContent9"];
                         $strContent10 = @$_POST["strContent10"];
                         $strContent11 = @$_POST["strContent11"];
                         $strContent12 = @$_POST["strContent12"];
                         $strContent13 = @$_POST["strContent13"];
                         $strContent14 = @$_POST["strContent14"];
                         $strContent15 = @$_POST["strContent15"];
                         $strContent16 = @$_POST["strContent16"];
                         $strContent17 = @$_POST["strContent17"];
                         $strContent18 = @$_POST["strContent18"];
                         $strContent19 = @$_POST["strContent19"];
                         $strContent20 = @$_POST["strContent20"];

                         $strTitle = @$_POST["strTitle"];
                         $intType = @$_POST["intType"];
                         $intAuthorId = @$_POST["intAuthorId"];
                         $strSummary = @$_POST["strSummary"];
                         $strTopicIds = @$_POST["strTopicIds"];
                         $strArticleIds = @$_POST["strArticleIds"];
                         $strBookIds = @$_POST["strBookIds"];
                         $strForumLink = @$_POST["strForumLink"];
                         $strRelLink1 = @$_POST["strRelLink1"];
                         $strRelLink2 = @$_POST["strRelLink2"];
                         $strRelLink3 = @$_POST["strRelLink3"];
                         $blnVisible = @$_POST["blnVisible"];
                         $intStatus = @$_POST["intStatus"];
                         $intPubMonth = @$_POST["intPubMonth"];
                         $intPubDay = @$_POST["intPubDay"];
                         $intPubYear = @$_POST["intPubYear"];
                         $intZipId = @$_POST["intZipId"];
                         $strZip = @$_FILES["strZip"]["tmp_name"];
                         $strZip_name = @$_FILES["strZip"]["name"];
                         $strZip_type = @$_FILES["strZip"]["type"];
                         $strZip_size = @$_FILES["strZip"]["size"];
                         
                        // Make sure that the published date is valid
                        if(strlen($intPubMonth) == 1)
							$intPubMonth = "0" . $intPubMonth;

                        if(strlen($intPubDay) == 1)
							$intPubDay = "0" . $intPubDay;
							
						if($blnVisible == "")
							$blnVisible = 0;

                        $pubDate = $intPubYear . $intPubMonth . $intPubDay;

                        // We will do some basic error checking...

                        $err = "<ul>";

                        if(!isset($strTitle))
                                $err .= "<li>You must enter a title</li>";

                        if(!isset($intType))
                                $err .= "<li>You must select the type for this article</li>";

                        if(!isset($intAuthorId))
                                $err .= "<li>You must select an author for this article</li>";

                        if(!isset($strSummary))
                                $err .= "<li>You must enter a summary for this article</li>";

                        if(!isset($strTopicIds))
                                $err .= "<li>You must select at least one topic for this article</li>";

                        if(!isset($blnVisible))
                                $err .= "<li>You must select whether this article is visible</li>";

                        if(!isset($intStatus))
                                $err .= "<li>You must select whether this article is a draft/final copy</li>";

                        $err .= "</ul>";

                        if($err != "<ul></ul>")
                        {
                        ?>
                            <p style="margin-left:15; margin-right:20" class="BodyText">
                                    <span class="BodyHeading">An Error Occured</span><br><br>
                                    An error occured while trying to add this article to the
                                    database. Please review the errors below and then
                                    click on the Go Back link below to go back and correct them.
                                    <span class="Error">
                                            <?php echo $err; ?>
                                    </span>
                                    <p style="margin-left:15; margin-right:20" class="BodyText">
                                    <a href="javascript:history.go(-2)">Go Back</a>
                            </p>
                        <?php
                            include("templates/dev_bottom.php");
                            exit;
                        }

                        $strTitle = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strTitle));
                        $strSummary = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strSummary));
                        $strForumLink = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strForumLink));
                        $strRelLink1 = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strRelLink1));
                        $strRelLink2 = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strRelLink2));
                        $strRelLink3 = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strRelLink3));
                        
                        // We will now check to make sure that there is at least one page for the article
                        if(!isset($strTitle1))
                        {
                        ?>
							<p style="margin-left:15; margin-right:20" class="BodyText">
							    <span class="BodyHeading">An Error Occured</span><br><br>
							    An error occured while trying to add this article to the
							    database. Please review the errors below and then
							    click on the Go Back link below to go back and correct them.
							    <span class="Error">
							            <ul>
							                    <li>You must add at least one page to this article</li>
							            </ul>
							    </span>
							    <p style="margin-left:15; margin-right:20" class="BodyText">
							    <a href="javascript:history.go(-1)">Go Back</a>
							</p>
                        <?php
                            include("templates/dev_bottom.php");
                            exit;
                        }
                        
                        /*
                                If this script has gotten to this point then it means that all of the details
                                that the user has entered for this article are Ok. We will start by adding the
                                actual article to the tbl_Articles table and then we will add the pages.
                        */

                        $intArticleId = @$_POST["intArticleId"];

                        $dbVars = new dbVars();
                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                        if($svrConn)
                                {
                                        $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                        if($dbConn)
                                                {
                                                        $strQuery = "insert into tbl_Articles(aTitle, aDocType, aAuthorId, aSummary, aTopicIds, aArticleIds, aBookIds, aForumLink, aLink1, aLink2, aLink3, aActive, aStatus, aRatingTotal, aNumRatings, aNumVotes, aSupportFile, aNumViews, aDateCreated) values(
																									 '$strTitle',
																									 $intType,
																									 $intAuthorId,
																									 '$strSummary',
																									 '$strTopicIds',
																									 '$strArticleIds',
																									 '$strBookIds',
																									 '$strForumLink',
																									 '$strRelLink1',
																									 '$strRelLink2',
																									 '$strRelLink3',
																									 $blnVisible,
																									 $intStatus,
																									 0,
																									 0,
																									 0,
																									 $intZipId,
																									 0,
																									 $pubDate)";
                                                        
                                                        $aResult = mysql_query($strQuery);
                                                        $intArticleId = mysql_insert_id();
                                                        
                                                        /*
                                                                If the article id returned is zero then the insert failed. We will
                                                                inform the user that it failed and end
                                                        */
                                                        
                                                        if($intArticleId == 0)
                                                        {
                                                        ?>
                                                            <p style="margin-left:15; margin-right:10;" class="BodyText">
                                                                    <span class="BodyHeading">An Error Occured</span><br><br>
                                                                    An internal database error occured while trying to add this
                                                                    article to the database. Please use the link below
                                                                    to go back and try again.<br><br>
                                                                    <a href="javascript:history.go(-1)">Go Back</a>
                                                            </p>
                                                        <?php
                                                            include("templates/dev_bottom.php");
                                                            exit;
                                                        }

                                                        // We've now got an article id and we can add the pages for this article.
                                                        if($strTitle1 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle1', 1, '$strContent1')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle2 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle2', 2, '$strContent2')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle3 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle3', 3, '$strContent3')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle4 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle4', 4, '$strContent4')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle5 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle5', 5, '$strContent5')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle6 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle6', 6, '$strContent6')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle7 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle7', 7, '$strContent7')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle8 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle8', 8, '$strContent8')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle9 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle9', 9, '$strContent9')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle10 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle10', 10, '$strContent10')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle11 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle1', 11, '$strContent11')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle12 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle2', 12, '$strContent12')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle13 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle3', 13, '$strContent13')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle14 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle4', 14, '$strContent14')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle15 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle5', 15, '$strContent15')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle16 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle6', 16, '$strContent16')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle17 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle7', 17, '$strContent17')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle18 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle8', 18, '$strContent18')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle19 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle9', 19, '$strContent19')";
                                                                  mysql_query($strQuery);
                                                                }
                                                        if($strTitle20 != 'undefined')
                                                                { $strQuery = "insert into tbl_ArticlePages VALUES(0, $intArticleId, '$strTitle10', 20, '$strContent20')";
                                                                  mysql_query($strQuery);
                                                                }

                                                        /*
                                                                The article has been added successfully. We will inform the user that it has been added
                                                                and then return to the article list.
                                                        */
                                                        ?>
                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                        <span class="BodyHeading">Article Added Successfully!</span><br><br>
                                                                        You have successfully added an article entitled "<i><?php echo $strTitle; ?></i>".
                                                                        Please click on the continue link below to return to the list of articles.<br><br>
                                                                        <a href="articles.php">Continue</a>
                                                                </p>
                                                        <?php
                                                }
                                        else
                                                {
                                                        // Couldn't connect to the database
                                                        ?>
                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                                        An error occured while trying to connect to the
                                                                        database. Please click on the refresh button above to try
                                                                        and load this page again.
                                                                </p>
                                                        <?php
                                                }
                                }
                        else
                                {
                                        // Couldn't connect to the database server
                                        ?>
                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                        An error occured while trying to connect to the
                                                        database server. Please click on the refresh button above to try
                                                        and load this page again.
                                                </p>
                                        <?php
                                }

                }

        function ShowArticleList()
                {

                        /*
                                This function will display a list of the articles in the database and
                                provide a link for the current user to update the article if needed
                        */

                        global $recsPerPage;
                        global $siteName;
                        
                        $page = @$_GET["page"];
                        $start = @$_GET["start"];

                        $dbVars = new dbVars();
                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                        if($svrConn)
                                {
                                        $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                        if($dbConn)
                                                {
                                                        // Connected to the database, execute our query
                                                        if(!is_numeric($page) || $page < 1)
                                                                $page = 1;

                                                        if($page == 1)
                                                                $start = 0;
                                                        else
                                                                $start = ($page * $recsPerPage) - $recsPerPage;

                                                        // Get the number of records in the table
                                                        $numRows = mysql_num_rows(mysql_query("select pk_aId from tbl_Articles"));

                                                        $strQuery  = "select pk_aId, aTitle, aAuthorId, aStatus, aDateCreated ";
                                                        $strQuery .= "from tbl_Articles ";
                                                        $strQuery .= "order by pk_aId desc ";
                                                        $strQuery .= "limit $start, $recsPerPage";

                                                        $results = mysql_query($strQuery);
                                                        $bgColor = "#ECECEC";

                                                        ?>
                                                                <? include("includes/jscript/articles.js"); ?>
																
																<form onSubmit="return ConfirmDelArticle()" action="articles.php?strMethod=del" method="post">
                                                                <div align="center">
                                                                <table border="0" width="95%" cellspacing="0" cellpadding="0">
                                                                  <tr>
                                                                    <td width="100%" colspan="4" height="21">
                                                                                <span class="BodyHeading">
                                                                                        <?php echo $siteName; ?> Articles
                                                                                </span>
                                                                    </td>
                                                                  </tr>
                                                                <tr>
                                                                        <td width="100%" height="20" colspan="5" align="right" class="BodyText" valign="top">
                                                                        <?php

                                                                                if($page > 1)
                                                                                  $nav .= "<a href='articles.php?page=" . ($page-1) . "'><u>« Prev</u></a> | ";

                                                                                for($i = 1; $i <= ceil($numRows / $recsPerPage); $i++)
                                                                                  if($i == $page)
                                                                                    $nav .= "<a href='articles.php?page=$i'><b>$i</b></a> | ";
                                                                                  else
                                                                                    $nav .= "<a href='articles.php?page=$i'>$i</a> | ";

                                                                                if(($start+$recsPerPage) < $numRows && $numRows > 0)
                                                                                  $nav .= "<a href='articles.php?page=" . ($page+1) . "'><u>Next »</u></a>";

                                                                                if(substr(strrev($nav), 0, 2) == " |")
                                                                                  $nav = substr($nav, 0, strlen($nav)-2);

                                                                                echo $nav . "<br>&nbsp;";
                                                                        ?>
                                                                        </td>
                                                                </tr>
                                                                  <tr>
                                                                    <td width="6%" bgcolor="#333333" height="21">
                                                                    <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Delete?</span>
                                                                    </td>
                                                                    <td width="52%" bgcolor="#333333" height="21">
                                                                      <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Title</span></td>
                                                                    <td width="25%" bgcolor="#333333" height="21">
                                                                      <p style="margin-left: 10; margin-right: 10"><span class="TableHeading"><b>Author</span></td>
                                                                    <td width="20%" bgcolor="#333333" height="21">
                                                                      <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Created</span></td>
                                                                  </tr>

                                                        <?php

                                                                // If there's no records, show a "No users found" table row
                                                                if($numRows == 0)
                                                                {
                                                                ?>
                                                                        <tr>
                                                                          <td width="100%" colspan="5" bgcolor="#FFFFFF" height="25">
                                                                                <span class="BodyText">
                                                                                        No articles found in the database.
                                                                                </span>
                                                                          </td>
                                                                        </tr>
                                                                <?php
                                                                }


                                                                /*
                                                                        We will get a list of articles on the site and sort them by their id.
                                                                        We could sort them by their date but id is already indexed so its faster.
                                                                */

                                                                while($row = mysql_fetch_array($results))
                                                                        {
                                                                                $bgColor = ($bgColor == "#C6E7C6" ? "#ECECEC" : "#C6E7C6");
                                                                                ?>
                                                                                        <tr>
                                                                                          <td width="6%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                                <div align="center">
                                                                                                        <input type="checkbox" name="aId[]" value="<?php echo $row["pk_aId"]; ?>">
                                                                                                </div>
                                                                                          </td>
                                                                                          <td width="52%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                            <p style="margin-left: 10; margin-right: 10; margin-top:3; margin-bottom:3">
                                                                                                        <a href="articles.php?strMethod=update&aId=<?php echo $row["pk_aId"]; ?>">
                                                                                                                <span class="BodyText">
                                                                                                                <?php

                                                                                                                        if(strlen($row["aTitle"]) >= 45)
                                                                                                                                echo substr($row["aTitle"], 0, 45) . "...";
                                                                                                                        else
                                                                                                                                echo $row["aTitle"];

                                                                                                                ?></a>
                                                                                          </td>
                                                                                          <td width="25%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                            <p style="margin-left: 10; margin-right: 10">
                                                                                                        <span class="BodyText">
                                                                                                                <?php

                                                                                                                        // We will get the author details if the author exists
                                                                                                                        $strQuery = "select alUserName from tbl_AdminLogins where pk_alId = {$row["aAuthorId"]}";
                                                                                                                        $aResults = mysql_query($strQuery);

                                                                                                                        if($aRow = mysql_fetch_row($aResults))
                                                                                                                                {
                                                                                                                                        // This author exists, provide a link to his profile
                                                                                                                                        echo "<a href='users.php?strMethod=modify&auId={$row["aAuthorId"]}'>{$aRow[0]}</a>";
                                                                                                                                }
                                                                                                                        else
                                                                                                                                {
                                                                                                                                        // This author no longer exists
                                                                                                                                        echo "N/A";
                                                                                                                                }
                                                                                                                ?>
                                                                                                        </span>
                                                                                          </td>
                                                                                          <td width="20%" bgcolor="<?php echo $bgColor; ?>" height="25">
                                                                                            <p style="margin-left: 10; margin-right: 10">
                                                                                                        <span class="BodyText">
                                                                                                                <?php echo substr($row["aDateCreated"], 4, 2) . "/" . substr($row["aDateCreated"], 6, 2) . "/" . substr($row["aDateCreated"], 0, 4); ?>
                                                                                                        </span>
                                                                                          </td>
                                                                                        </tr>
                                                                                <?php
                                                                        }
                                                                ?>
                                                                <?php if($numRows > 0) { ?>
                                                                <tr>
																	<td width="58%" colspan="2">
																		<input type="submit" value="Delete Selected »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
																	</td>
																	<td width="100%" height="20" colspan="3" align="right" class="BodyText" valign="top">
																	<br>
																		<?php echo $nav . "<br>&nbsp;"; ?>
																	</td>
                                                                </tr>
                                                                <?php } ?>
                                                            <tr>
                                                                        <td colspan="4" width="100%">
                                                                                &nbsp;
                                                                        </td>
                                                                </tr>
                                                            <tr>
                                                              <td colspan="2" width="58%" height="21">
                                                                        <input onClick="JumpToAddArticle()" type="button" value="Add Article »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                              </td>
                                                              <td colspan="2" width="42%" height="21">
                                                                <div align="right">
                                                                                <a href="#top">^ Top</a>
                                                                </div>
                                                            </tr>
                                                        </table>
                                                        <br>&nbsp;
                                                        <?php
                                                }
                                        else
                                                {
                                                        // Couldn't connect to the database
                                                        ?>
                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                                        An error occured while trying to connect to the
                                                                        database. Please click on the refresh button above to try
                                                                        and load this page again.
                                                                </p>
                                                        <?php
                                                }
                                }
                        else
                                {
                                        // Couldn't connect to the database server
                                        ?>
                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                        An error occured while trying to connect to the
                                                        database server. Please click on the refresh button above to try
                                                        and load this page again.
                                                </p>
                                        <?php
                                }
                }

        function DelArticle()
                {
                        /*
                                This function will delete the article whose pk_aId = $aId. It will also delete all of
                                the pages for this article
                        */

                        $aId = $_POST["aId"];
                        
                        if($aId != "")
                                {
                                        // We will connect to the database and delete the article
                                        $dbVars = new dbVars();
                                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                        if($svrConn)
                                                {
                                                        $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                        if($dbConn)
                                                                {
                                                                        // Connected to the database OK
                                                                        foreach($aId as $v)
                                                                        {
																			$where1 .= " pk_aId = $v or";
																			$where2 .= " apArticleId = $v or";
																		}
                                                                        
                                                                        $where1 = ereg_replace(" or$", "", $where1);
                                                                        $where2 = ereg_replace(" or$", "", $where2);
                                                                        
                                                                        $strQuery = "delete from tbl_Articles where $where1";
                                                                        
                                                                        mysql_query($strQuery);

                                                                        $strQuery = "delete from tbl_ArticlePages where $where2";
                                                                        mysql_query($strQuery);

                                                                        ?>
                                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                        <span class="BodyHeading">Articles Deleted Successfully</span><br><br>
                                                                                        The selected articles have been successfully deleted from the
                                                                                        database. Click on the continue link below to return to the list of articles.<br><br>
                                                                                        <a href="articles.php">Continue</a>
                                                                                </p>
                                                                        <?php
                                                                }
                                                        else
                                                                {
                                                                        // Couldnt connect to the database
                                                                        ?>
                                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                        An error occured while trying to connect to the database. Click on
                                                                                        the refresh button of your browser to try and load this page again.
                                                                                </p>
                                                                        <?php
                                                                }
                                                }
                                        else
                                                {
                                                        // Couldnt connect to the database server
                                                        ?>
                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                                        An error occured while trying to connect to the database server. Click on
                                                                        the refresh button of your browser to try and load this page again.
                                                                </p>
                                                        <?php
                                                }
                                }
                        else
                                {
                                        // The user specified an invalid article identifier
                                        ?>
                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                        <span class="BodyHeading">Invalid Article Id</span><br><br>
                                                        The article id that you have selected is invalid. Please use the link below to
                                                        go back and select another article.<br><br>
                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                </p>
                                        <?php
                                }
                }
?>

<?php include("templates/dev_bottom.php"); ?>
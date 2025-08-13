<?php include("templates/dev_top.php"); ?>

        <?php

                /*
                        Firstly, we will make sure that the user is a super-administrator
                        (has an IsLoggedIn value of 3). If they arent, we will redirect them
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
                
                if($strMethod == "")
					$strMethod = @$_POST["strMethod"];

                switch($strMethod)
                        {
                                case "addnew":
                                {
                                         if($userStatus < 3)
                                                if(in_array("add_books", $publisherAccess))
                                                        ProcessNew();
                                                else
                                                        NoAccess();
                                         else
                                                ProcessNew();
                                        break;
                                }
                                case "newfinal":
                                        AddNew();
                                        break;
                                case "delete":
                                {
                                         if($userStatus < 3)
                                                if(in_array("delete_books", $publisherAccess))
                                                        DeleteBook();
                                                else
                                                        NoAccess();
                                         else
                                                DeleteBook();
                                        break;
                                }
                                case "update":
                                {
                                         if($userStatus < 3)
                                                if(in_array("edit_books", $publisherAccess))
                                                        ProcessUpdate();
                                                else
                                                        NoAccess();
                                         else
                                                ProcessUpdate();
                                        break;
                                }
                                case "updatefinal":
                                        UpdateBook();
                                        exit;
                                default:
                                {
                                         if($userStatus < 3)
                                                if(in_array("view_books", $publisherAccess))
                                                        ShowBookList();
                                                else
                                                        NoAccess();
                                         else
                                                ShowBookList();
                                        break;
                                }
                        }

                function ShowBookList()
                        {
                                /*
                                        This function will get a complete list of books
                                        from the tbl_Books table and display them for deleting
                                        or editing.
                                */

                                        global $recsPerPage;
                                        
                                        $page = @$_GET["page"];
                                        $start = @$_GET["start"];

                                        $dbVars = new dbVars();
                                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                        if($svrConn)
                                                {
                                                        // Connected to the database
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
                                                                        $numRows = mysql_num_rows(mysql_query("select pk_bId from tbl_Books"));

                                                                        $strQuery = "select * from tbl_Books order by bTitle asc limit $start, $recsPerPage";
                                                                        $results = mysql_query($strQuery);
                                                                }
                                                        else
                                                                {
                                                                        // Couldnt connect to database
                                                                        ?>
                                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                        A connection to the database couldnt be established. Please
                                                                                        use the link below to go back and try again.<br><br>
                                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                                </p>
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
                                                                        A connection to the database server couldnt be established. Please
                                                                        use the link below to go back and try again.<br><br>
                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                </p>
                                                        <?php
                                                                        include("templates/dev_bottom.php");
                                                                        exit;
                                                }

                                ?>

                                        <?php include("includes/jscript/books.js"); ?>

                                        <form onSubmit="return ConfirmDelBook()" action="books.php?strMethod=delete" method="post">
                                        <div align="center">
                                        <table width="95%" cellspacing="0" cellpadding="0" border="0" valign="middle">
                                                <tr>
                                                        <td width="100%" colspan="4">
                                                                <p class="BodyText">
                                                                        <span class="BodyHeading">Books</span>
                                                                </p>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="100%" height="20" colspan="4" align="right" class="BodyText" valign="top">
                                                        <?php

                                                                if($page > 1)
                                                                  $nav .= "<a href='books.php?page=" . ($page-1) . "'><u>« Prev</u></a> | ";

                                                                for($i = 1; $i <= ceil($numRows / $recsPerPage); $i++)
                                                                  if($i == $page)
                                                                    $nav .= "<a href='books.php?page=$i'><b>$i</b></a> | ";
                                                                  else
                                                                    $nav .= "<a href='books.php?page=$i'>$i</a> | ";

                                                                if(($start+$recsPerPage) < $numRows && $numRows > 0)
                                                                  $nav .= "<a href='books.php?page=" . ($page+1) . "'><u>Next »</u></a>";

                                                                if(substr(strrev($nav), 0, 2) == " |")
                                                                  $nav = substr($nav, 0, strlen($nav)-2);

                                                                echo $nav . "<br>&nbsp;";
                                                        ?>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="5%" bgcolor="#333333" height="21">
                                                                &nbsp;
                                                        </td>
                                                        <td width="50%" bgcolor="#333333" height="21" valign="middle">
                                                                <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Book Title</span>
                                                        </td>
                                                        <td width="20%" bgcolor="#333333" height="21">
                                                                <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Book Link</span>
                                                        </td>
                                                        <td width="25%" bgcolor="#333333" height="21">
                                                                <p style="margin-left: 10; margin-right: 10"><span class="TableHeading">Book Topic</span>
                                                        </td>
                                                </tr>

                                                <?php

                                                                if($numRows == 0)
                                                                {
                                                                ?>
                                                                        <tr>
                                                                          <td width="100%" bgcolor="#FFFFFF" height="21" colspan="4">
                                                                                <span class="BodyText">No books found in the database.</span>
                                                                          </td>
                                                                        </tr>
                                                                <?php
                                                                }

                                                                $bgColor = "#ECECEC";

                                                                while($row = mysql_fetch_array($results))
                                                                        {
                                                                                $bgColor = ($bgColor == "#C6E7C6" ? "#ECECEC" : "#C6E7C6");
                                                                                ?>
                                                                                        <tr>
                                                                                                <td width="5%" height="29" valign="middle" bgcolor="<?php echo $bgColor; ?>">
                                                                                                        &nbsp;<input type="checkbox" name="bId[]" value="<?php echo $row["pk_bId"]; ?>">
                                                                                                </td>
                                                                                                <td width="50%" height="29" valign="middle" bgcolor="<?php echo $bgColor; ?>">
                                                                                                        <p style="margin-top:5; margin-bottom:5; margin-left:10; margin-right:10">
                                                                                                                <span class="BodyText">
                                                                                                                        <?php

                                                                                                                                 if(strlen($row["bTitle"]) > 35)
                                                                                                                                        { echo "<a href=\"books.php?strMethod=update&bId={$row["pk_bId"]}\">" . substr($row["bTitle"], 0, 35) . "...</a>"; }
                                                                                                                                else
                                                                                                                                        { echo "<a href=\"books.php?strMethod=update&bId={$row["pk_bId"]}\">" . $row["bTitle"] . "</a>"; }
                                                                                                                        ?>
                                                                                                                </span>
                                                                                                        </p>
                                                                                                </td>
                                                                                                <td width="20%" height="29" valign="middle" bgcolor="<?php echo $bgColor; ?>">
                                                                                                        <p style="margin-top:5; margin-bottom:5; margin-left:10; margin-right:10">
                                                                                                                <span class="BodyText">
                                                                                                                        <?php echo "<a target=\"_blank\" href=\"{$row["bURL"]}\">click here</a>"; ?>
                                                                                                                </span>
                                                                                                        </p>
                                                                                                </td>
                                                                                                <td width="25%" height="29" valign="middle" bgcolor="<?php echo $bgColor; ?>">
                                                                                                        <p style="margin-top:5; margin-bottom:5; margin-left:10; margin-right:10">
                                                                                                                <span class="BodyText">
                                                                                                                        <?php

                                                                                                                                // We will get the name of the topic if it exists

                                                                                                                                if($arrTopics = explode(",", $row["bTopicIds"]) )
                                                                                                                                        {
                                                                                                                                                $strTopics = "";
                                                                                                                                                for($counter=0;$counter<count($arrTopics);$counter++)
                                                                                                                                                        {
                                                                                                                                                                $strQuery = "select * from tbl_Topics where pk_tId = {$arrTopics[$counter]}";
                                                                                                                                                                $tResults = mysql_query($strQuery);

                                                                                                                                                                if($tRow = mysql_fetch_array($tResults))
                                                                                                                                                                        { $strTopics .= $tRow["tName"] . ", "; }
                                                                                                                                                        }
                                                                                                                                                                if($strTopics == "")
                                                                                                                                                                        { $strTopics = "N/A"; }

                                                                                                                                                                if(substr($strTopics, strlen($strTopics)-2, 1) == ",")
                                                                                                                                                                        { $strTopics = substr($strTopics, 0, strlen($strTopics)-2);
                                                                                                                                                                          if(strlen($strTopics) > 17)
                                                                                                                                                                                { echo substr($strTopics, 0, 14) . "..."; }
                                                                                                                                                                          else
                                                                                                                                                                            { echo $strTopics; }
                                                                                                                                                                        }
                                                                                                                                                                else
                                                                                                                                                                        { echo $strTopics; }
                                                                                                                                        }
                                                                                                                        ?>
                                                                                                                </span>
                                                                                                        </p>
                                                                                                </td>
                                                                                        </tr>

                                                                                <?php
                                                                        }
                                                if($numRows > 0)
                                                {
                                                ?>

                                                <tr>
                                                        <td width="55%" height="20" colspan="2">
															<input type="submit" value="Delete Selected »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                        </td>
                                                        <td width="45%" height="20" colspan="2" align="right" class="BodyText" valign="top">
                                                        <br>
                                                        <?php echo $nav . "<br>&nbsp;"; ?>
                                                        </td>
                                                </tr>
                                                <?php
                                                }
                                                ?>
                                                <tr>
                                                        <td width="100%" colspan="4">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                            <tr>
                                              <td colspan="2" width="35%" height="21">
                                                        <input onClick="JumpToAddBook()" type="button" value="Add Book »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                              </td>
                                              <td width="65%" height="21" colspan="2">
                                                <div align="right">
                                                                <a href="#top">^ Top</a>
                                                </div>
                                            </tr>
                                                <tr>
                                                        <td width="100%" colspan="4">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                        </table>
                                        <br>&nbsp;
                                        </div>
                                    </form>
                                <?
                        }

                function ProcessNew()
                        {
                                /*
                                        This function will allow the user to add a new
                                        book which will be randomly shown on the right
                                        hand strip of the site
                                */

                                        $dbVars = new dbVars();
                                        @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                        if($svrConn)
                                                {
                                                        // Connected to the database server OK
                                                        $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                        if($dbConn)
                                                                {
                                                                        // Connected to the database OK
                                                                        $strQuery = "select * from tbl_Topics order by tName asc";
                                                                        $results = mysql_query($strQuery);
                                                                }
                                                        else
                                                                {
                                                                        // Couldnt connect to the database
                                                                        ?>
                                                                                <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                        <span class="BodyHeading">An Error Occured</span><br><br>
                                                                                        A connection to the database server couldnt be established. Please
                                                                                        use the link below to go back and try again.<br><br>
                                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                                </p>
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
                                                                        A connection to the database server couldnt be established. Please
                                                                        use the link below to go back and try again.<br><br>
                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                </p>
                                                        <?php
                                                                        include("templates/dev_bottom.php");
                                                                        exit;
                                                }
                                ?>

                                <?php include("includes/jscript/books.js"); ?>

                                <form enctype="multipart/form-data" onSubmit="return CheckAddBook()" enctype="multipart/form-data"  name="frmAddBook" action="books.php?strMethod=newfinal" method="post">
                                <div align="center">
                                        <table class="BodyText" width="95%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <p class="BodyText">
                                                                        <span class="BodyHeading">Add Book</span><br><br>
                                                                        Use the form below to add a new book to the list.
                                                                        Fields marked with a <span class="Error">*</span>  are required
                                                                        to be completed before the book can be added.<br><br>
                                                                </p>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td colspan="2" width="100%" background="doth.gif">
                                                                &nbsp;
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <br>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Book Title:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strTitle" type="text" size="60" maxlength="100">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Book URL:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strURL" type="text" size="60" maxlength="250">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Book Topic:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <select multiple name="strTopicIds[]" style="width:385">
                                                                        <?php

                                                                                while($row = mysql_fetch_array($results))
                                                                                        { echo "<option value=\"{$row["pk_tId"]}\">{$row["tName"]}</option>"; }
                                                                        ?>
                                                                </select>
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                                <div align="right">
                                                                        Book Picture:&nbsp;&nbsp;
                                                                </div>
                                                        </td>
                                                        <td width="80%">
                                                                <input name="strPic" type="file" size="30" style="width:385">
                                                                <span class="Error">*</span>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <br>
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td width="20%">
                                                        </td>
                                                        <td width="80%">
                                                                <input type="button" value="Cancel" onClick="history.go(-1)" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                <input type="submit" value="Add Book »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                        </td>
                                                </tr>
                                                <tr>
                                                        <td colspan="2" width="100%">
                                                                <br>
                                                        </td>
                                                </tr>
                                        </table>
                                        </form>

                                <?php
                        }

                function AddNew()
                        {
                                /*
                                        This function will take the form values parsed and
                                        add them to the tbl_Books table as a new book record
                                */

                                $strTitle = @$_POST["strTitle"];
                                $strURL = @$_POST["strURL"];
                                $strTopicIds = @$_POST["strTopicIds"];
                                $strPic = @$_FILES["strPic"]["tmp_name"];
                                $strPic_name = @$_FILES["strPic"]["name"];
                                $strPic_type = @$_FILES["strPic"]["type"];
                                $strPic_size = @$_FILES["strPic"]["size"];

                                $strTitle = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strTitle));
                                $strURL = str_replace("\\\"", "&quot;", str_replace("\'", "&#39;", $strURL));

                                $errDesc = "<ul>";

                                if($strTitle == "")
                                        { $errDesc .= "<li>You must enter a title</li>"; }
                                if($strURL == "")
                                        { $errDesc .= "<li>You must enter URL</li>"; }
                                if(count($strTopicIds) == 0)
                                        { $errDesc .= "<li>You must select at least one topic. To add a new topic, <a href='topics.php?strMethod=addnew'>click here</a>.</li>"; }
                                if($strPic == "" || $strPic_type != "image/gif" || $strPic_size > 50000)
                                        { $errDesc .= "<li>You must select a valid GIF image under 50k</li>"; }

                                $errDesc .= "</ul>";

                                if($errDesc != "<ul></ul>")
                                        {
                                                // Values are invalid
                                                ?>
                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                <span class="BodyHeading">Invalid Details forBook</span><br><br>
                                                                Some of the values that you have entered/selected for this book
                                                                are invalid. These are shown in the list below:<br>
                                                                <span class='Error'><?php echo $errDesc; ?></span>
                                                                <p style="margin-left:15">
                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                </p>
                                                        </p>
                                                <?
                                                        include("templates/dev_bottom.php");
                                                        exit;
                                        }
                                else
                                        {
                                                /* We will add the new account details to the database */
                                                $dbVars = new dbVars();
                                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                                if($svrConn)
                                                        {
                                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                                if($dbConn)
                                                                        {
                                                                                // We will build the INSERT INTO query and generate the topic string
                                                                                $strTops = "";
                                                                                for($counter=0;$counter<count($strTopicIds);$counter++)
                                                                                        { $strTops .= $strTopicIds[$counter] . ", "; }

                                                                                if(substr($strTops, strlen($strTops)-2, 1) == ",")
                                                                                        { $strTops = substr($strTops, 0, strlen($strTops)-2); }

                                                                                $strBinPic = addslashes(fread(fopen($strPic, "rb"), filesize($strPic)));
                                                                                $strQuery  = "INSERT INTO tbl_Books ";
                                                                                $strQuery .= "VALUES(0, '$strTitle', '$strURL', '0, $strTops', '$strBinPic')";

                                                                                $result = mysql_query($strQuery);
                                                                                if($result)
                                                                                        {
                                                                                                // Query was ok, send the credentials in an email to the user and upload pic
                                                                                                ?>
                                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                                <span class="BodyHeading">New Book Added!</span><br><br>
                                                                                                                You have successfully added a new book. Click on the
                                                                                                                link below to continue.<br><br>
                                                                                                                <a href="books.php">Continue</a>
                                                                                                        </p>
                                                                                                <?php
                                                                                        }
                                                                                else
                                                                                        {
                                                                                                // Query failed
                                                                                                ?>
                                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                                <span class="BodyHeading">Book Not Added</span><br><br>
                                                                                                                An error occured while trying to add this book to the database. You can click
                                                                                                                on the link below to go back and retry your submission.<br><br>
                                                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                                                        </p>
                                                                                                <?php
                                                                                        }
                                                                        }
                                                                else
                                                                        {
                                                                                // Couldnt connect to the database
                                                                                ?>
                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                <span class="BodyHeading">Couldnt Open Logins Table</span><br><br>
                                                                                                A connection to the database was made, however the logins table couldn't be opened.
                                                                                                Please click on the link below to go back and try again.<br><br>
                                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                                        </p>
                                                                                <?php
                                                                        }
                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database server
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                <span class="BodyHeading">Couldnt Open Database</span><br><br>
                                                                                A connection to the database couldn't be opened.
                                                                                Please click on the link below to go back and try again.<br><br>
                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                        </p>
                                                                <?php
                                                        }
                                        }
                        }

                function DeleteBook()
                        {
                                /*
                                        This function will take the Id of the book to delete and
                                        delete any records in the tbl_Books table whose pk_bId matches it.
                                */

                                $bId = @$_POST["bId"];

                                if(!is_array($bId))
                                        {
                                                // Invalid book ID
                                                ?>
                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                <span class="BodyHeading">Invalid Book Specified</span><br><br>
                                                                The book that you have chosen to delete is invalid. Please use the
                                                                link below to go back and try again.<br><br>
                                                                <a href="javascript:history.go(-1)">GO Back</a>
                                                        </p>
                                                <?
                                                        include("templates/dev_bottom.php");
                                                        exit;
                                        }
                                else
                                        {
                                                // Delete the records after connecting to the DB
                                                $dbVars = new dbVars();
                                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                                if($svrConn)
                                                        {
                                                                // Connected to the database server OK
                                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                                if($dbConn)
                                                                        {
                                                                                // Use a DELETE FROM query to remove the book
																				foreach($bId as $v)
																				{
																					$where .= " pk_bId = $v or";
																				}
																				                                            
																				$where = ereg_replace(" or$", "", $where);
                                                                                $strQuery = "delete from tbl_Books where $where";
                                                                                mysql_query($strQuery);
                                                                                ?>
                                                                                        <p style="margin-left:15; margin-right:20" class="BodyText">
                                                                                                <span class="BodyHeading">Book Deleted Successfully</span><br><br>
                                                                                                The selected book has been successfully deleted from the
                                                                                                database. Please use the link below to continue.<br><br>
                                                                                                <a href="books.php">Continue</a>
                                                                                        </p>
                                                                                <?php
                                                                        }
                                                                else
                                                                        {
                                                                                // Couldnt connect to the database
                                                                                ?>
                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                <span class="BodyHeading">Couldnt Open Database</span><br><br>
                                                                                                A connection to the database couldn't be opened.
                                                                                                Please click on the link below to go back and try again.<br><br>
                                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                                        </p>
                                                                                <?php
                                                                        }
                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database server
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                <span class="BodyHeading">Couldnt Open Database</span><br><br>
                                                                                A connection to the database couldn't be opened.
                                                                                Please click on the link below to go back and try again.<br><br>
                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                        </p>
                                                                <?php
                                                        }
                                        }
                        }

                function ProcessUpdate()
                        {
                                /*
                                        This function will display the form that will allow
                                        the user to modify a "top reading" book
                                */

                                $bId = @$_GET["bId"];

                                if(!is_numeric($bId))
                                        {
                                                ?>
                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                <span class="BodyHeading">Invalid Book Id</span><br><br>
                                                                The book that you have chosen to update is invalid or no longer
                                                                exists in the database. Please use the link below to go back and try again.<br><br>
                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                        </p>
                                                <?php
                                                                include("templates/dev_bottom.php");
                                                                exit;
                                        }

                                $dbVars = new dbVars();
                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                if($svrConn)
                                        {
                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                if($dbConn)
                                                        {
                                                                // Connected to the database server OK
                                                                $strQuery = "select * from tbl_Books where pk_bId = $bId";
                                                                $results = mysql_query($strQuery);

                                                                if(!$row = mysql_fetch_array($results))
                                                                        {
                                                                                // The book doesnt exist
                                                                                ?>
                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                <span class="BodyHeading">Book Doesn't Exist</span><br><br>
                                                                                                The book that you have chosen to update no longer
                                                                                                exists in the database. Please use the link below to go back and try again.<br><br>
                                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                                        </p>
                                                                                <?php
                                                                                                include("templates/dev_bottom.php");
                                                                                                exit;
                                                                        }

                                                                // The book exists, display the form to update the book
                                                                ?>

                                                                        <?php include("includes/jscript/books.js"); ?>

                                                                        <form enctype="multipart/form-data" onSubmit="return CheckUpdateBook()" name="frmUpdateBook" action="books.php?strMethod=updatefinal&bId=<?php echo $row["pk_bId"]; ?>" method="post">
                                                                        <div align="center">
                                                                                <table class="BodyText" width="95%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                                <td colspan="2" width="100%">
                                                                                                        <p class="BodyText">
                                                                                                                <span class="BodyHeading">Update Book</span><br><br>
                                                                                                                Use the form below to update the selected book.
                                                                                                                Fields marked with a <span class="Error">*</span>  are required
                                                                                                                to be completed before the book entry can be updated.<br><br>
                                                                                                        </p>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td colspan="2" width="100%" background="doth.gif">
                                                                                                        &nbsp;
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td colspan="2" width="100%">
                                                                                                        <br>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td width="20%">
                                                                                                        <div align="right">
                                                                                                                Book Title:&nbsp;&nbsp;
                                                                                                        </div>
                                                                                                </td>
                                                                                                <td width="80%">
                                                                                                        <input name="strTitle" type="text" size="60" maxlength="100" value="<?php echo $row["bTitle"]; ?>">
                                                                                                        <span class="Error">*</span>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td width="20%">
                                                                                                        <div align="right">
                                                                                                                Book URL:&nbsp;&nbsp;
                                                                                                        </div>
                                                                                                </td>
                                                                                                <td width="80%">
                                                                                                        <input name="strURL" type="text" size="60" maxlength="250" value="<?php echo $row["bURL"]; ?>">
                                                                                                        <span class="Error">*</span>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td width="20%">
                                                                                                        <div align="right">
                                                                                                                Book Topic:&nbsp;&nbsp;
                                                                                                        </div>
                                                                                                </td>
                                                                                                <td width="80%">
                                                                                                        <select multiple name="strTopicIds[]" style="width:385">
                                                                                                                <?php

                                                                                                                        $strQuery = "select * from tbl_Topics order by tName asc";
                                                                                                                        $tResults = mysql_query($strQuery);

                                                                                                                        while($tRow = mysql_fetch_array($tResults))
                                                                                                                                { echo "<option ";
                                                                                                                                  $arrTopics = explode(",", $row["bTopicIds"]);

                                                                                                                                  for($counter=0; $counter<count($arrTopics); $counter++)
                                                                                                                                        { if($arrTopics[$counter] == $tRow["pk_tId"])
                                                                                                                                            { echo " SELECTED "; }
                                                                                                                                        }

                                                                                                                                  echo " value=\"{$tRow["pk_tId"]}\">{$tRow["tName"]}</option>"; }
                                                                                                                ?>
                                                                                                        </select>
                                                                                                        <span class="Error">*</span>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td width="20%">
                                                                                                        <div align="right">
                                                                                                                Book Picture:&nbsp;&nbsp;
                                                                                                        </div>
                                                                                                </td>
                                                                                                <td width="80%">
                                                                                                        <input onKeyDown="chkUseCurrentPic.checked = false" onClick="chkUseCurrentPic.checked = false" name="strPic" type="file" size="30" style="width:385">
                                                                                                        <span class="Error">*</span>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td width="20%">
                                                                                                </td>
                                                                                                <td width="80%">
                                                                                                        <span class="BodyText">
                                                                                                                <input type="checkbox" name="chkUseCurrentPic" value="ON" checked>
                                                                                                                Use Current Picture?
                                                                                                        </span>
                                                                                                </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                                <td colspan="2" width="100%">
                                                                                                        <br>
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td width="20%">
                                                                                                </td>
                                                                                                <td width="80%">
                                                                                                        <input type="button" value="Cancel" onClick="history.go(-1)" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                                                        <input type="submit" value="Update Book »" style="font-size: 8pt; font-family: Verdana; font-weight: normal">
                                                                                                </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td colspan="2" width="100%">
                                                                                                        <br>
                                                                                                </td>
                                                                                        </tr>
                                                                                </table>
                                                                                </form>

                                                                <?php

                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                <span class="BodyHeading">Couldnt Open Books Table</span><br><br>
                                                                                A connection to the database was made, however the books table couldn't be opened.
                                                                                Please click on the link below to go back and try again.<br><br>
                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                        </p>
                                                                <?php
                                                                                include("templates/dev_bottom.php");
                                                                                exit;
                                                        }
                                        }
                                else
                                        {
                                                // Couldnt connect to the database server
                                                ?>
                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                <span class="BodyHeading">Couldnt Open Database</span><br><br>
                                                                A connection to the database couldn't be opened.
                                                                Please click on the link below to go back and try again.<br><br>
                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                        </p>
                                                <?php
                                        }
                        }

                function UpdateBook()
                        {
                                /*
                                        This function will update the current book and save it to the database
                                */

                                $strTitle = @$_POST["strTitle"];
                                $strURL = @$_POST["strURL"];
                                $strTopicIds = @$_POST["strTopicIds"];
                                $strPic = @$_FILES["strPic"]["tmp_name"];
                                $strPic_name = @$_FILES["strPic"]["name"];
                                $strPic_type = @$_FILES["strPic"]["type"];
                                $strPic_size = @$_FILES["strPic"]["size"];
                                $chkUseCurrentPic = @$_POST["chkUseCurrentPic"];
                                $bId = @$_GET["bId"];

                                $errDesc = "<ul>";

                                if($strTitle == "")
                                        { $errDesc .= "<li>You must enter a title</li>"; }
                                if($strURL == "")
                                        { $errDesc .= "<li>You must enter URL</li>"; }
                                if(count($strTopicIds) == 0)
                                        { $errDesc .= "<li>You select at least one topic</li>"; }
                                if(($chkUseCurrentPic == "") && (($strPic == "") || ($strPic_type != "image/gif") || ($strPic_size > 50000)))
                                        { $errDesc .= "<li>You must select a valid GIF image under 50k or choose to use the current image</li>"; }

                                $errDesc .= "</ul>";

                                if($errDesc != "<ul></ul>")
                                        {
                                                // Values are invalid
                                                ?>
                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                <span class="BodyHeading">Invalid Details forBook</span><br><br>
                                                                Some of the values that you have entered/selected for this book
                                                                are invalid. These are shown in the list below:<br>
                                                                <span class='Error'><?php echo $errDesc; ?></span>
                                                                <p style="margin-left:15">
                                                                        <a href="javascript:history.go(-1)">Go Back</a>
                                                                </p>
                                                        </p>
                                                <?
                                                        include("templates/dev_bottom.php");
                                                        exit;
                                        }
                                else
                                        {
                                                /* We will add the new account details to the database */
                                                $dbVars = new dbVars();
                                                @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

                                                if($svrConn)
                                                        {
                                                                $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

                                                                if($dbConn)
                                                                        {
                                                                                // We will build the UPDATE query and generate the topic string
                                                                                $strTops = "";
                                                                                for($counter=0;$counter<count($strTopicIds);$counter++)
                                                                                        { $strTops .= $strTopicIds[$counter] . ", "; }

                                                                                if(substr($strTops, strlen($strTops)-2, 1) == ",")
                                                                                        { $strTops = substr($strTops, 0, strlen($strTops)-2); }

                                                                                //if($chkUseCurrentPic
                                                                                if($chkUseCurrentPic == "")
                                                                                        { $strBinPic = addslashes(fread(fopen($strPic, "rb"), filesize($strPic)));
                                                                                          $strQuery  = "UPDATE tbl_Books ";
                                                                                          $strQuery .= "SET bTitle = '$strTitle', ";
                                                                                          $strQuery .= "bURL = '$strURL', ";
                                                                                          $strQuery .= "bTopicIds = '$strTops', ";
                                                                                          $strQuery .= "bPic = '$strBinPic' ";
                                                                                          $strQuery .= "WHERE pk_bId = $bId ";
                                                                                        }
                                                                                else
                                                                                        {
                                                                                          $strQuery  = "UPDATE tbl_Books ";
                                                                                          $strQuery .= "SET bTitle = '$strTitle', ";
                                                                                          $strQuery .= "bURL = '$strURL', ";
                                                                                          $strQuery .= "bTopicIds = '$strTops' ";
                                                                                          $strQuery .= "WHERE pk_bId = $bId ";
                                                                                        }

                                                                                $result = mysql_query($strQuery);
                                                                                if($result)
                                                                                        {
                                                                                                // Query was ok
                                                                                                ?>
                                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                                <span class="BodyHeading">Book Updated Successfully!</span><br><br>
                                                                                                                You have successfully updated the selected new book. Click on the
                                                                                                                link below to continue.<br><br>
                                                                                                                <a href="books.php">Continue</a>
                                                                                                        </p>
                                                                                                <?php
                                                                                        }
                                                                                else
                                                                                        {
                                                                                                // Query failed
                                                                                                ?>
                                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                                <span class="BodyHeading">Book Not Updated</span><br><br>
                                                                                                                An error occured while trying to update this book in the database. You can click
                                                                                                                on the link below to go back and retry your submission.<br><br>
                                                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                                                        </p>
                                                                                                <?php
                                                                                        }
                                                                        }
                                                                else
                                                                        {
                                                                                // Couldnt connect to the database
                                                                                ?>
                                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                                <span class="BodyHeading">Couldnt Open Logins Table</span><br><br>
                                                                                                A connection to the database was made, however the logins table couldn't be opened.
                                                                                                Please click on the link below to go back and try again.<br><br>
                                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                                        </p>
                                                                                <?php
                                                                        }
                                                        }
                                                else
                                                        {
                                                                // Couldnt connect to the database server
                                                                ?>
                                                                        <p style="margin-left:15; margin-right:10" class="BodyText">
                                                                                <span class="BodyHeading">Couldnt Open Database</span><br><br>
                                                                                A connection to the database couldn't be opened.
                                                                                Please click on the link below to go back and try again.<br><br>
                                                                                <a href="javascript:history.go(-1)">Go Back</a>
                                                                        </p>
                                                                <?php
                                                        }
                                        }
                        }

        ?>




















                <?php include("templates/dev_bottom.php"); ?>
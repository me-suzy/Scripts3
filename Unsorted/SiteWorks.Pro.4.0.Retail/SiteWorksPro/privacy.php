<?php include_once("templates/top.php"); ?>

        <!-- Start About -->
        <div align="center">
                <center>
                <table width="96%" border="0" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                                <td width="100%" colspan="2" class="BodyHeader2">
                                        <span class="BodyHeading">
                                                <br>Our Privacy Policy
                                        </span>
                                </td>
                        </tr>
                        <tr>
                                <td width="100%" height="20" colspan="2" class="BodyText" valign="top">
                                        <span class="Text1"><br>
                                        <?php

                                                $query = "select sdPrivacy from tbl_SiteDetails limit 1";
                                                $result = mysql_query($query);

                                                if($row = mysql_fetch_row($result))
                                                {
                                                   echo $row[0] . "<br><br>";;
                                                }
                                                else
                                                {
                                                   echo "Run admin and click on 'Privacy' under Site Details to add text here.";
                                                }
                                        ?>
                                        </span>
                                </td>
                        </tr>
                </table>
                </center>
        </div>
        <!-- End About -->

<?php include_once("templates/bottom.php"); ?>
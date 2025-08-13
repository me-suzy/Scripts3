<?php include_once("templates/top.php"); ?>

        <!-- Start Contact -->
        <div align="center">
                <center>
                <table width="96%" border="0" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                                <td width="100%" colspan="2" class="BodyHeader2">
                                        <span class="BodyHeading">
                                                <br><?php echo "Contact Us"; ?>
                                        </span>
                                </td>
                        </tr>
                        <tr>
                                <td width="100%" height="20" colspan="2" valign="top">
                                        <span class="Text1"><br>
                                        <?php

                                                $query = "select sdContact from tbl_SiteDetails limit 1";
                                                $result = mysql_query($query);

                                                if($row = mysql_fetch_row($result))
                                                {
                                                   echo $row[0] . "<br><br>";;
                                                }
                                                else
                                                {
                                                   echo "Run admin and click on 'Contact Us' under Site Details to add text here.";
                                                }
                                        ?>
                                        </span>
                                </td>
                        </tr>
                </table>
                </center>
        </div>
        <!-- End Contact -->

<?php include_once("templates/bottom.php"); ?>
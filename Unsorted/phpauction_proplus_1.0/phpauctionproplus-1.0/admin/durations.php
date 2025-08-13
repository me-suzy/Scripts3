<?#//v.1.0.0
        include "loggedin.inc.php";


#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

        Function ToBeDeleted($index){
                Global $delete;

                $i = 0;
                while($i < count($delete)){
                        if($delete[$i] == $index) return true;

                        $i++;
                }
                return false;
        }

   require('../includes/messages.inc.php');
   require('../includes/config.inc.php');


      


        if($act && !$$ERR){

                //-- Update DURATIONS table

                $rebuilt_durations = array();
                $rebuilt_days      = array();
                $i = 0;
                while($i < count($new_durations) && strlen($new_durations[$i]) != 0){

                        if(!ToBeDeleted($new_days[$i]) && strlen($new_durations) != 0){

                                $rebuilt_durations[]         = $new_durations[$i];
                                $rebuilt_days[]              = $new_days[$i];
                        }
                        $i++;
                }

                //--

                $query = "delete from PHPAUCTIONPROPLUS_durations";
                $result = mysql_query($query);
                if(!$result)
                {
                        print $ERR_001." - ".mysql_error();
                }

                $i = 0;
                while($i < count($rebuilt_durations)){

                        $query = "insert into 
                        		  PHPAUCTIONPROPLUS_durations
                        		  values($rebuilt_days[$i],
                        		  \"$rebuilt_durations[$i]\")";
                        $result = mysql_query($query);
                        print $query;
                        if(!$result)
                        {
                                print $ERR_001." - ".mysql_error();
                        }

                        $i++;
                }

                $MSG = "MSG_123";

        }




        require("./header.php");
        require('../includes/styles.inc.php');
?>
<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
                <TD COLSPAN=3>
                        <FORM NAME=conf ACTION=durations.php METHOD=POST>
                                <BR>
                                <TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB" ALIGN="CENTER">
                                        <TR>
                                                <TD ALIGN=CENTER><FONT COLOR=#FFFFFF  FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
                                                        <? print $MSG_069; ?>
                                                        </B></FONT></TD>
                                        </TR>
                                        <TR>
                                                <TD>
                                                        <TABLE WIDTH=100% CELLPADDING=2 BGCOLOR="#FFFFFF">
                                                                <TR>
                                                                        <TD WIDTH=50></TD>
                                                                        <TD COLSPAN=2> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
                                                                                <?
                        print $MSG_122;
                        if($$ERR){
                                print "<FONT COLOR=red><BR><BR>".$$ERR;
                        }else{
                                if($$MSG){
                                        print "<FONT COLOR=red><BR><BR>".$$MSG;
                                }else{
                                        print "<BR><BR>";
                                }
                        }
                ?>
                                                                                </FONT></TD>
                                                                </TR>
                                                                <TR>
                                                                        <TD WIDTH=50></TD>
                                                                        <TD BGCOLOR="#EEEEEE">
                                                                                <? print $std_font; ?>
                                                                                <B>
                                                                                <? print $MSG_097; ?>
                                                                                </B> </TD>
                                                                        <TD BGCOLOR="#EEEEEE">
                                                                                <? print $std_font; ?>
                                                                                <B>
                                                                                <? print $MSG_087; ?>
                                                                                </B> </TD>
                                                                        <TD BGCOLOR="#EEEEEE">
                                                                                <? print $std_font; ?>
                                                                                <B>
                                                                                <? print $MSG_088; ?>
                                                                                </B> </TD>
                                                                </TR>
                                                                <?
                //--
                $query = "select * from PHPAUCTIONPROPLUS_durations order by days";
                $result = mysql_query($query);
                if(!$result)
                {
                        print $ERR_001." - ".mysql_error();
                        exit;
                }
                $num = mysql_num_rows($result);
                $i = 0;

                while($i < $num){

                        $days                  = mysql_result($result,$i,"days");
                        $description = mysql_result($result,$i,"description");

                        print "<TR>
                                         <TD WIDTH=50></TD>
                                         <TD>
                                         <INPUT TYPE=text NAME=new_days[] VALUE=\"$days\" SIZE=5>
                                         </TD>
                                         <TD>
                                         <INPUT TYPE=text NAME=new_durations[] VALUE=\"$description\" SIZE=25>
                                         </TD>
                                         <TD>
                                         <INPUT TYPE=checkbox NAME=delete[] VALUE=\"$days\">
                                         </TD>
                                         </TR>";
                        $i++;
                }
                        print "<TR>
                                         <TD WIDTH=50>
                                         $std_font Add
                                         </TD>
                                         <TD>
                                         Days <INPUT TYPE=\"text\" NAME=\"new_days[]\" SIZE=\"5\" maxlength=\"5\" value=\"0\">
                                         
                                         <!-- Teporary disables
                                          Hours <select name=\"hour\">
                                         <option>0</option>
                                         <option>1</option>
                                         <option>2</option>
                                         <option>3</option>
                                         <option>4</option>
                                         <option>5</option>
                                         <option>6</option>
                                         <option>7</option>
                                         <option>8</option>
                                         <option>9</option>
                                         <option>10</option>
                                         <option>11</option>
                                         <option>12</option>
                                         <option>13</option>
                                         <option>14</option>
                                         <option>15</option>
                                         <option>16</option>
                                         <option>17</option>
                                         <option>18</option>
                                         <option>19</option>
                                         <option>20</option>
                                         <option>21</option>
                                         <option>22</option>
                                         <option>23</option>
                                         </select>
                                         -->
                                         </TD>
                                         <TD>
                                         <INPUT TYPE=text NAME=\"new_durations[]\" SIZE=25>
                                         </TD>
                                         <TD>
                                         </TD>
                                         </TR>";

        ?>
                                                                <TR>
                                                                        <TD WIDTH=50></TD>
                                                                        <TD>
                                                                                <INPUT TYPE=submit NAME=act VALUE="<? print $MSG_089; ?>">
                                                                        </TD>
                                                                </TR>
                                                                <TR>
                                                                        <TD WIDTH=50></TD>
                                                                        <TD> </TD>
                                                                </TR>
                                                        </TABLE>
                                                </TD>
                                        </TR>
                                </TABLE>
                                </FORM>
                        <CENTER>
                                <BR>
                                <BR>
                                <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
                                <A HREF="admin.php" CLASS="links">Admin Home</A> </FONT> <BR>
                                <BR>
                        </CENTER>
                </TD>
</TR>
</TABLE>

<!-- Closing external table (header.php) -->
<? require("./footer.php"); ?>
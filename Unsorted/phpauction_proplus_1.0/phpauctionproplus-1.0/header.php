<?#//v.1.0.0


#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

        //-- Function definition section

   include "./includes/dates.inc.php";

?>

<HTML>

<HEAD>
<TITLE><? print $SETTINGS[sitename]?></TITLE>



<SCRIPT Language=Javascript>

function MM_swapImgRestore() { //v2.0

  if (document.MM_swapImgData != null)

    for (var i=0; i<(document.MM_swapImgData.length-1); i+=2)

      document.MM_swapImgData[i].src = document.MM_swapImgData[i+1];

}

function MM_preloadImages() { //v2.0

  if (document.images) {

    var imgFiles = MM_preloadImages.arguments;

    if (document.preloadArray==null) document.preloadArray = new Array();

    var i = document.preloadArray.length;

    with (document) for (var j=0; j<imgFiles.length; j++) if (imgFiles[j].charAt(0)!="#"){

      preloadArray[i] = new Image;

      preloadArray[i++].src = imgFiles[j];

  } }

}



function MM_swapImage() { //v2.0

  var i,j=0,objStr,obj,swapArray=new Array,oldArray=document.MM_swapImgData;

  for (i=0; i < (MM_swapImage.arguments.length-2); i+=3) {

    objStr = MM_swapImage.arguments[(navigator.appName == 'Netscape')?i:i+1];

    if ((objStr.indexOf('document.layers[')==0 && document.layers==null) ||

        (objStr.indexOf('document.all[')   ==0 && document.all   ==null))

      objStr = 'document'+objStr.substring(objStr.lastIndexOf('.'),objStr.length);

    obj = eval(objStr);

    if (obj != null) {

      swapArray[j++] = obj;

      swapArray[j++] = (oldArray==null || oldArray[j-1]!=obj)?obj.src:oldArray[j];

      obj.src = MM_swapImage.arguments[i+2];

  } }

  document.MM_swapImgData = swapArray; //used for restore

}

//-->

</script>

</HEAD>



<BODY BGCOLOR="#FFFFFF"  LINK="<?=$FONTCOLOR[$SETTINGS[linkscolor]]?>" ALINK="<?=$FONTCOLOR[$SETTINGS[vlinkscolor]]?>" VLINK="<?=$FONTCOLOR[$SETTINGS[vlinkscolor]]?>">

<TABLE width="764" border="0" cellspacing="0" cellpadding="1" bgcolor="<?=$FONTCOLOR[$SETTINGS[bordercolor]]?>">

  <TR>

    <TD>

      <TABLE width="100%" border="0" cellspacing="0" cellpadding="8" bgcolor="<?=$FONTCOLOR[$SETTINGS[headercolor]]?>">

        <TR>

          <TD width="35%" VALIGN=MIDDLE>
          <?
                  if($SETTINGS[logo])
                  {
          ?>
                  <A href="index.php?"><IMG src="<?=$uploaded_path.$SETTINGS[logo];?>" border="0"></A>
          <?
                  }
                  else
                  {
                          print "&nbsp;";
                  }
          ?>
          </TD>

          <TD width="65%" valign="MIDDLE" align=right>

                        <?
                                if($SETTINGS[banners] == 1)
                                {
                                        view("468x60","_blank");
                                }
                                else
                                {
                                        print "&nbsp;";
                                }
                        ?>

        </TD>

        </TR>

      </TABLE>

      <TABLE width="100%" border="0" cellspacing="0" cellpadding="2">

        <TR>

          <TD ALIGN=CENTER>

    <? print $nav_font; ?>

        <A HREF="./index.php?">

        <? print $nav_font.$MSG_501; ?></FONT></A>

        

        | <A HREF="./sell.php?">

  		<? print $nav_font.$MSG_236; ?></FONT></A>

		<?
	   	if($HTTP_SESSION_VARS["PHPAUCTION_LOGGED_IN"])
	   	{
		/* user is logged in, give link to edit data or log out */
		?>

        | <A HREF="./user_menu.php?">

   		<? print $nav_font.$MSG_622; ?></FONT></A>

        | <A HREF="./logout.php?">

   		<? print $nav_font.$MSG_245; ?></FONT></A>

		<?
		} else {
		/* user not logged in, give link to register or login */
		?>

        | <A HREF="./register.php?">

   		<? print $nav_font.$MSG_235; ?></FONT></A>

        | <A HREF="user_login.php?">

   		<? print $nav_font.$MSG_259; ?></FONT></A>

		<?
		}
		?>

        | <A HREF="./help.php?">

   		<? print $nav_font.$MSG_164; ?></FONT></A>

         </TD>

        </TR>

      </TABLE>

      
    <TABLE bgcolor="<?=$FONTCOLOR[$SETTINGS[headercolor]]?>" border=0 width="100%" cellspacing="0" cellpadding="4">
      <TR width=100%> 
        <TD width="33%" valign=top  nowrap align="left"> 
          <FORM name="search" action="search.php" method="GET">
            <INPUT type=HIDDEN name="">
            <? print $sml_font.$MSG_103 ?></FONT>
            <INPUT type="text" name="q" size=15 value="<SCRIPT Language=PHP> print htmlspecialchars($q); </SCRIPT>">
            <FONT size="1"> 
            <INPUT TYPE=submit NAME="" VALUE="<? print $MSG_275;?>"><br>
            <A HREF="adsearch.php"> 
            <? print $sml_font.$MSG_464 ?>
            </A></FONT> 
          </FORM>
        </TD>
        <TD width="32%" valign=top  nowrap> 
          <FORM name=browse action="browse.php" method=GET>
            
<INPUT type=HIDDEN name="">
            <? print $sml_font.$MSG_104; ?></FONT>
            <? include "./includes/categories_select_box.inc.php"; ?>
            <INPUT TYPE=submit NAME="" VALUE="<? print $MSG_275;?>">
          </FORM>
        </TD>
        <TD width="35%" VALIGN=top align=RIGHT > 
          <?

                        //-- Display current Date/Time

                        print $sml_font.ActualDate();

                        print "<BR><BR>";

                    ?>
          </FONT> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right" height="8">
            <tr> 
              <td height="2" valign="bottom" align="right"> 
                <?


                        //-- Get users and auctions counters



                        $query = "select * from PHPAUCTIONPROPLUS_counters";

                        $result_counters = mysql_query($query);

                        if($result_counters){

                                $counters  = $sml_font."<B>".mysql_result($result_counters,0,"users")."</B>&nbsp;".strtoupper($MSG_231);

                                $counters .= "&nbsp;&nbsp;&nbsp;<B>".mysql_result($result_counters,0,"auctions")."</B>&nbsp;".strtoupper($MSG_232)."</FONT>";

                                print $counters;

                        }
             ?>
              </td>
            </tr>
          </table>
           
          </FONT> </TD>
      </TR>
    </TABLE>

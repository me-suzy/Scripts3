<?php
    include ("../includes/global_vars.php");    
    include ("../classes/database_class.php");
    include ("../classes/user_class.php");
    include ("../classes/contact_class.php");
    include ("../includes/fotools.php");
    
    session_start();

    
    // === AntiHack check ===
    if (!session_is_registered("s_previous_page") || !session_is_registered("s_user") || !session_is_registered("s_user_type")) {
    		msgBox ("1", "../", "Sécurité !  <br><br>Une erreur de session est survenue !",
            "Sécurité !", "error",
            "<< Retour", "../index.php");
            die;
    }   
    if ($s_user_type != "GODLIKE" && $s_user_type != "ADMIN") { // should never happened
    		msgBox ("1", "../", "Sécurité !  <br><br>Une erreur de session est survenue !",
            "Sécurité !", "error",
            "<< Retour", "../index.php");
            die;
    }
    
    // === END of AntiHack check ===
    
    if (!session_is_registered('s_admin_domain')) {
        $s_admin_domain = "initialize";
        session_register('s_admin_domain');
    } 
    
    $s_previous_page = "adm_index.php";
?><head>
  <title>wa-boo Administration</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <STYLE><? include ("../includes/css.php"); ?></style> 
</head>
	
<body topmargin="0">

<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="versionfont"><img src="../images/wa-boo_small.gif"> 
      <? echo $G_version; ?>
    </td>
  </tr>
  <tr>
    <td>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td background="../images/left_top_corner.gif" height="7"></td>
          <td background="../images/h_line_top.gif" height="7"></td>
          <td background="../images/right_top_corner.gif" height="7"></td>
        </tr>
        <tr> 
          <td background="../images/v_line_left.gif" width="7"></td>
          <td class="stdfont" bgcolor="#EEEEFF" >
            <table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" background="../images/bg2.gif">
              <tr align="center" bgcolor="<? echo $G_admin_menu_color ?>"> 
                <td colspan="4" class="stdtitle">&nbsp;
                  <? echo $s_user->getUserFirstname() . " " . $s_user->getUserName() . " - " . $s_user_type . " mode"; ?>
                  &nbsp;</td>
              </tr>
              <tr>
                <td background="../images/h_line.gif" align="center" colspan="8" height="7"></td>
              </tr>
              <tr align="center"> 
                <td colspan="4" class="stdfont" height="25"></td>
              </tr>
              
              <?   if ($s_user_type =="GODLIKE") {  ?>
                      
              <tr align="center">
                <td align="center" height="40" class="stdfont" width="50">&nbsp;</td>
                <td height="40" align="center" class="stdfont">&nbsp;</td>
                <td height="40" class="stdfont">
                  <form name="groupmngt" action="domains.php" method="post">
                    <input type="submit" name="sendpasswd" value="Groups management" class="adminbuttons">
                  </form>
                </td>
                <td width="50" align="center">&nbsp;</td>
              </tr>
              <tr>
                <td align="right" height="40" class="stdfont" width="50">&nbsp;</td>
                <td height="40" align="center" class="stdfont">&nbsp;</td>
                <td height="40" align="center" class="stdfont">
                  <form name="usermngt" action="users.php" method="post">
                    <input type="submit" name="users" value="Users management" class="adminbuttons">
                  </form>
                </td>
                <td width="50">&nbsp;</td>
              </tr>
              
              <?    } elseif ($s_user_type == "ADMIN") { 
                        if (count($s_user->getUserDomains())) { 
                            reset ($s_user->getUserDomains());
                    	    while (list ($key, $value) = each ($s_user->getUserDomains())) {
                    	        if ($value["right"] == "ADMIN") {
                    	        
                        	        $domain[$key] = $value["id"];
                        	        $domain_label[$key] = $value["name"]; ?>
                    
                                    
              <tr align="center">
                <td align="center" height="40" class="stdfont" width="50">&nbsp;</td>
                <td height="40" align="center" class="stdfont">&nbsp;</td>
                <td height="40" class="stdfont">
                  <form name="groupmngt" action="adm_users.php" method="post">
                    <input type="hidden" name="dID" value="<? echo $domain[$key] ?>">
                    <input type="submit" value="Group : <? echo $domain_label[$key]; ?>" class="adminbuttons" name="submit">
                  </form>
                </td>
                <td width="50" align="center">&nbsp;</td>
              </tr>
               
              <?   	            }
                     		}
                        }  
                    }  
              ?>
              
              <tr>
                <td align="right" height="40" class="stdfont" width="50">&nbsp;</td>
                <td height="40" align="center" class="stdfont">&nbsp;</td>
                <td height="40" align="center" class="stdfont"><br>
                  <a href="../index.php"><img src="../images/escape.gif" border="0"></a><br>
                  Logoff</td>
                <td width="50">&nbsp;</td>
              </tr>
              <tr> 
                <td width="50" >&nbsp;</td>
                <td height="40" class="stdfont" >&nbsp;</td>
                <td height="38" class="stdfont" align="left" >&nbsp;</td>
                <td height="38" class="stdfont" align="left" >&nbsp;</td>
              </tr>
            </table>
          </td>
          <td valign="top" background="../images/v_line_right.gif" width="7"></td>
        </tr>
        <tr>
          <td class="texteg" height="7" background="../images/left_bottom_corner.gif"></td>
          <td height="7" background="../images/h_line_bottom.gif"></td>
          <td height="7" background="../images/right_bottom_corner.gif"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>

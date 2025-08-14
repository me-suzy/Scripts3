<?php 
/*
Tiny Tool for Web Hosts, Copyright (C) 2004 John Sinclair and Dennis Turner.
WebHost Tiny Tool comes with ABSOLUTELY NO WARRANTY; this is free software, 
and you are welcome to redistribute it under certain conditions; for details 
read WWW.TEATOAST.COM/GNU_GPL_LICENSE.HTML
*/
include 'config.php';
if ( $_POST['Package'] )
    {
    ( $_POST['job'] == 'edit' ? $error_tally = 1 : $error_tally = 0 );
    $Package = $_POST['Package'];
    extract($hosting_options["$Package"]);
    $package_dropdown = "";
    reset($hosting_options);
    while (list($key, $value) = each ($hosting_options)) 
        {
        if ( $key == $Package )
            {
            $package_dropdown .= "<option value=\"$key\" selected>" . $value['item_name'] . "</option>\n";
            }
        else
            {
            $package_dropdown .= "<option value=\"$key\">" . $value['item_name'] . "</option>\n";
            }
        }
//
    if ( $_POST['DomainName'] )
        {
        $DomainName = strip_tags( $_POST['DomainName'] ); 
        $DomainName = clean_domain($DomainName); 
        if ( valid_domain($DomainName) )
            {
            $db=mysql_connect($db_host, $db_user, $db_pass) or die ('Could not CONNECT because: ' . mysql_error());
            mysql_select_db($db_name,$db) or die ('Could not SELECT database because: ' . mysql_error());
            $sql = "SELECT sequence, option_selection1, option_selection2, txn_type FROM ".$db_table." WHERE option_selection1 = '".$DomainName."'";
            $result = mysql_query($sql,$db) or exit("$query failed: ".mysql_error());
            if ( mysql_num_rows($result) == 0 )
                {
                $message['DomainName'] = "";
                }
            else
                {
                $subscriber = mysql_fetch_array($result, MYSQL_ASSOC) ;
                if ( $subscriber['txn_type'] == 'touched' )
                    {
                    $message['DomainName'] = "";
                    }
                else
                    {
                    $message['DomainName'] = '&#8226; That Domain Name already has an account ...<br>';
                    $error_tally++;
                    }
                }
            mysql_close($db);
            }
        else
            {
            $message['DomainName'] = '&#8226; The Domain Name appears to be incorrect ...<br>';
            $error_tally++;
            }
        }
    else
        {
        $message['DomainName'] = '&#8226; Please enter a Domain to be hosted ...<br>';
        $error_tally++;
        }
//
    if ( $_POST['UserName'] )
        {
        $UserName = strip_tags( $_POST['UserName'] );
        if ( valid_username($UserName) )
            {
            $db=mysql_connect($db_host, $db_user, $db_pass) or die ('Could not CONNECT because: ' . mysql_error());
            mysql_select_db($db_name,$db) or die ('Could not SELECT database because: ' . mysql_error());
            $sql = "SELECT sequence, option_selection1, option_selection2, txn_type FROM ".$db_table." WHERE option_selection2 = '".$UserName."'";
            $result = mysql_query($sql,$db) or exit("$query failed: ".mysql_error());
            if ( mysql_num_rows($result) == 0 )
                {
                $message['UserName'] = "";
                }
            else
                {
                $subscriber = mysql_fetch_array($result, MYSQL_ASSOC) ;
                if ( $subscriber['option_selection1'] == $DomainName )
                    {
                    $message['DomainName'] = "";
                    }
                else
                    {
                    $message['UserName'] = '&#8226; That User Login name is not unique ... <br>';
                    $error_tally++;
                    }
                }
            mysql_close($db);
            }
        else
            {
            $message['UserName'] = '&#8226; User Login should begin with a letter and be 1 to 8 alpha-numeric characters long (case-sensitive) ...<br>';
            $error_tally++;
            }
        }
    else
        {
        $message['UserName'] = '&#8226; Please create a unique User Login name ...<br>';
        $error_tally++;
        }
//
    if ( $_POST['terms'] == 'on' )
        {
        $terms = 'checked';
        $message['terms'] = "";
        }
    else
        {
        $terms = "";
        $message['terms'] = '&#8226; Please check the Terms of Service box ...<br>';
        $error_tally++;
        }
    }
//
if ($error_tally === 0)
    {
    /* 
    Look for discounts, update database, offer a recap, then post to PayPal 
    */
    if($_POST['Coupon'])
        {
        $Coupon = strtolower(strip_tags($_POST['Coupon']));
        extract($coupon_codes["$Coupon"]);
        }
    (empty($srt) ? $set_srt=null : $set_srt="<input type=\"hidden\" name=\"srt\" value=\"$srt\" />");
    (empty($a1) ? $set_a1=null : $set_a1="<input type=\"hidden\" name=\"a1\" value=\"$a1\" />");
    (empty($p1) ? $set_p1=null : $set_p1="<input type=\"hidden\" name=\"p1\" value=\"$p1\" />");
    (empty($t1) ? $set_t1=null : $set_t1="<input type=\"hidden\" name=\"t1\" value=\"$t1\" />");
    (empty($a2) ? $set_a2=null : $set_a2="<input type=\"hidden\" name=\"a2\" value=\"$a2\" />");
    (empty($p2) ? $set_p2=null : $set_p2="<input type=\"hidden\" name=\"p2\" value=\"$p2\" />");
    (empty($t2) ? $set_t2=null : $set_t2="<input type=\"hidden\" name=\"t2\" value=\"$t2\" />");
    $item_number = time();
    $db=mysql_connect($db_host, $db_user, $db_pass) or die ('Could not CONNECT because: ' . mysql_error());
    mysql_select_db($db_name,$db) or die ('Could not SELECT database because: ' . mysql_error());
    ($subscriber['txn_type'] == 'touched' ? $sql = "UPDATE $db_table SET " : $sql = "INSERT INTO $db_table SET option_selection1='$DomainName', txn_type='touched', ");
    $sql .= " 
            coupon_code = '$Coupon', 
            whm_name = '$whm_name', 
            IP = '$IP_none', 
            item_name = '$item_name', 
            item_number = '$item_number', 
            option_selection2 = '$UserName', 
            period1 = '$p1 ".strtolower($t1)."', 
            period2 = '$p2 ".strtolower($t2)."', 
            period3 = '$p3 ".strtolower($t3)."', 
            amount1 = '$a1', 
            amount2 = '$a2', 
            amount3 = '$a3',
            reattempt = '$sra', 
            recurring = '$src', 
            recur_times = '$srt'
            "; 
    ($subscriber['txn_type'] == 'touched' ? $sql .= " WHERE sequence='".$subscriber['sequence']."'" : $sql .= "");
    mysql_query($sql,$db) or die ('Could not INSERT or UPDATE table because: ' . mysql_error());
    mysql_close($db);
/*
$flop creates a 'post-to-PayPal' string 
*/
$flop = <<<content
<table summary="recap" border="0" cellspacing="0" cellpadding="2" align="center" width="480">
    <tr>
        <td width="460" colspan="2" bgcolor="#ffffff">
            <table border="0" cellspacing="0" cellpadding="2" align="center" width="480" frame="box" rules="none">
                <tr>
                    <td height="20" width="440" id="redletter">
                        &#8226; Is Everything Correct?
                    </td>
                    <td height="20" width="20" bgcolor=green>&nbsp;</td>
                    <td height="20" width="20" bgcolor=green>&nbsp;</td>
                </tr>
                <tr>
                    <td width="460" colspan="2" bgcolor=green>
                        <table bgcolor="#E5E5E5" width="100%" border="0" cellspacing="0" cellpadding="5" frame="box" rules="none" align="center">
                            <tr>
                                <td colspan="2" align="center" valign="middle">
                                    <h4>Your Domain Name:<br>{$DomainName}</h4>
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center" valign="middle">
                                    <h4>Your User Login Name:<br>{$UserName}</h4>
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center" valign="middle">
                                    <h4>Your Hosting Plan and Payment Cycle:<br>{$item_name}</h4>
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" valign="middle">
                                    <form method="post" action="{$_SERVER['PHP_SELF']}">
                                    <input type="hidden" name="job" value="edit" />
                                    <input type="hidden" name="terms" value="on" />
                                    <input type="hidden" name="Package" value="{$Package}" />
                                    <input type="hidden" name="Coupon" value="{$Coupon}" />
                                    <input type="hidden" name="DomainName" value="{$DomainName}" />
                                    <input type="hidden" name="UserName" value="{$UserName}" />
                                    <input type="submit" name="submit" value="&lt;&lt;&lt; Go Back">
                                    </form>
                                </td>
                                <td align="left" valign="middle">
                                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                    <input type="hidden" name="cmd" value="_xclick-subscriptions" />
                                    <input type="hidden" name="business" value="{$business}" />
                                    <input type="hidden" name="return" value="{$return}" />
                                    <input type="hidden" name="cancel_return" value="{$cancel_return}" />
                                    <input type="hidden" name="no_shipping" value="{$no_shipping}" />
                                    <input type="hidden" name="no_note" value="{$no_note}" />
                                    <input type="hidden" name="src" value="{$src}" />
                                    <input type="hidden" name="sra" value="{$sra}" />
                                    {$set_srt}
									{$set_a1}
									{$set_p1}
									{$set_t1}
									{$set_a2}
									{$set_p2}
									{$set_t2}
                                    <input type="hidden" name="rm" value="{$rm}" />
                                    <input type="hidden" name="modify" value="{$modify}" />
                                    <input type="hidden" name="currency_code" value="{$currency_code}" />
                                    <input type="hidden" name="a3" value="{$a3}" />
                                    <input type="hidden" name="p3" value="{$p3}" />
                                    <input type="hidden" name="t3" value="{$t3}" />
                                    <input type="hidden" name="item_name" value="{$item_name}" />
                                    <input type="hidden" name="on0" value="{$on0}" />
                                    <input type="hidden" name="os0" value="{$DomainName}" />
                                    <input type="hidden" name="on1" value="{$on1}" />
                                    <input type="hidden" name="os1" value="{$UserName}" />
                                    <input type="hidden" name="item_number" value="{$item_number}" />
                                    <input type="submit" name="submit" value="Continue &gt;&gt;&gt;">
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="20" height="20" bgcolor=green>&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" width="440"><span id="small"><a href="http://www.TeaToast.com">Design by TeaToast © 2004</a></span></td>
                    <td height="20" width="20" bgcolor=green>&nbsp;</td>
                    <td height="20" width="20" bgcolor=green>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
content;
    print $head.$flop.$foot;
    }
else
    {
/*
$flip gathers subscription information from user. 
It may be pre-populated with user's previous answers.
*/
$flip = <<<content
<table summary="input form" border="0" cellspacing="0" cellpadding="2" align="center" width="480">
    <tr>
        <td width="460" colspan="2" bgcolor="#FFFFFF">
            <table border="0" cellspacing="0" cellpadding="2" align="center" width="480" frame="box" rules="none">
                <tr>
                    <td height="20" width="440" id="redletter">
                        {$message['DomainName']}{$message['UserName']}{$message['terms']}
                    </td>
                    <td height="20" width="20" bgcolor=green>&nbsp;</td>
                    <td height="20" width="20" bgcolor=green>&nbsp;</td>
                </tr>
                <tr>
                    <td width="460" colspan="2" bgcolor=green>
<form name="inputs" method="post" action="{$_SERVER['PHP_SELF']}">
    <table bgcolor="#E5E5E5" width="100%" border="0" cellspacing="0" cellpadding="5" frame="box" rules="none" align="center">
        <tr>
            <td colspan="2" align="center" valign="middle">
                <h4>Enter Your Domain Name:</h4><input type="text" name="DomainName" maxlength="80" size="40" value="$DomainName" /><br>&nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center" valign="middle">
                <h4>Create Your Permanent User Login Name:</h4><input type="text" name="UserName" maxlength="8" size="14" value="$UserName" /><br>&nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center" valign="middle">
                <h4>Select a Hosting Plan and Payment Cycle:</h4>
                <select name="Package">
                    {$package_dropdown}
                </select>
                <br>&nbsp;
            </td>
        </tr>
        <tr>
            <td align="center" colspan="2" valign="middle">
                <h4>I have read and agree to the <a href="TOS.htm" target="_blank">Terms of Service</a>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="terms" $terms /></h4>
            </td>
        </tr>
        <tr>
            <td align="left" valign="baseline">
                <br>Coupon Code? <input type="text" name="Coupon" maxlength="28" size="6" value="$Coupon" /><br>&nbsp;
            </td>
            <td align="center" valign="middle">
                <input type="submit" name="submit" value="Continue >>>" />
            </td>
        </tr>
    </table>
</form>
                    </td>
                    <td width="20" height="20" bgcolor=green>&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" width="440"><span id="small"><a href="http://www.TeaToast.com">Design by TeaToast © 2004</a></span></td>
                    <td height="20" width="20" bgcolor=green>&nbsp;</td>
                    <td height="20" width="20" bgcolor=green>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
content;
    print $head.$flip.$foot;
    }
//
?>
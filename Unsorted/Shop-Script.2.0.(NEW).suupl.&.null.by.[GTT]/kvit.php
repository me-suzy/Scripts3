<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/

	session_start();

	include("cfg/connect.inc.php");
	include("cfg/settings.inc.php");
	include("includes/database/".DBMS.".php");
	include("functions.php");

	//connect to the database
	db_connect(DB_HOST,DB_USER,DB_PASS) or die (db_error());
	db_select_db(DB_NAME) or die (db_error());

	//authorized access check
	include("checklogin.php");

	if (!isset($client))
	{
		if (isset($log))
			$client = $log; //äëÿ ïîêàçà êëèåíòó
		else die ("Êëèåíò íå íàéäåí â áàçå äàííûõ");
	}
	
	$client = base64_decode($client);

	//currencies file
	include("cfg/currency.inc.php");

	$current_currency = $invoice_currency;

	//! for phys
	$q = db_query("select first_name, otch, last_name, City, Address from ".CUSTOMERS_TABLE." where Login='$client'") or die (db_error());
	$cl =  db_fetch_row($q);
	if (!$cl) die ("Êëèåíò íå íàéäåí â áàçå äàííûõ èëè íå ÿâëÿåòñÿ ôèçè÷åñêèì ëèöîì");


	if (!isset($orderID)) die("Íå óêàçàí íîìåð çàêàçà");
	$q = db_query("select shipping_cost, tax from ".ORDERS_TABLE." where orderID='$orderID' and customer_login='$client'") or die (db_error());
	$order =  db_fetch_row($q);
	if (!$order) die ("Çàêàç íå íàéäåí â áàçå äàííûõ");

	$timestamp = time();



	//order content
	$total = 0;
	$cnt = 1;
	$q = db_query("select name, Price, Quantity from ".ORDERED_CARTS_TABLE." where orderID='$orderID'") or die (db_error());
	while ($row = db_fetch_row($q))
	{
		$total += $row[2]*$row[1];
		$cnt++;

	}

	if ($order[1])
	{
		$total += $total*$order[1]/100; //+tax if required
	}
	$total += $order[0]; //+shipping cost



	$invoice = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<!-- saved from url=(0061)http://login.valuehost.ru/inc/sb.php?bid=97383&aff=2136071810 -->
<HTML><HEAD><TITLE>Îïëàòà ïî Êâèòàíöèè</TITLE>
<META http-equiv=Content-Type content=\"text/html; charset=windows-1251\">
<META content=\"MSHTML 6.00.2800.1264\" name=GENERATOR></HEAD>
<BODY bgColor=#ffffff>
<CENTER>
  <CENTER>
    <TABLE cellSpacing=0 cellPadding=3 width=570 border=1>
  <TBODY>
  <TR>
    <TD vAlign=top align=right width=200 height=255><BR><BR><FONT size=2>
      <P>Èçâåùåíèå</P><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><FONT size=1>
      <P>Êàññèð</P></FONT></FONT></TD>
    <TD vAlign=center align=right width=100 height=255>
      <TABLE cellSpacing=0 cellPadding=3 width=370 border=1><FONT size=-1>
        <P align=center>ÈÍÍ $inn ÊÏÏ $kpp<BR><U>$reciever</U><BR><FONT size=1>ïîëó÷àòåëü ïëàòåæà</FONT></FONT> 
        <TBODY>
        <TR>
          <TD vAlign=center align=middle colSpan=4 height=10><FONT size=2>
            <P>Ðàñ÷åòíûé ñ÷åò ¹: 
            $rs&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ÁÈÊ: 
            $bik</P></FONT></TD></TR>
        <TR>
          <TD vAlign=center align=middle colSpan=4 height=10><FONT size=2>
            <P>Êîð. ñ÷.: $ks</P></FONT></TD></TR>
        <TR>
          <TD colSpan=4><FONT size=2>
            <CENTER>$cl[2]&nbsp;$cl[0]&nbsp;$cl[1]<BR>$cl[3],&nbsp;$cl[4]
            <CENTER>
            <CENTER><FONT size=1>ïëàòåëüùèê (ÔÈÎ, 
            àäðåñ)</FONT></CENTER></FONT></CENTER></CENTER></TD></TR>
        <TR>
          <TD align=middle colSpan=2><FONT size=2>Âèä ïëàòåæà</FONT></TD>
          <TD align=middle><FONT size=2>Äàòà</FONT></TD>
          <TD align=middle><FONT size=2>Ñóììà</FONT></TD></TR>
        <TR>
          <TD align=middle colSpan=2><FONT size=1>çàêàç ¹$orderID</FONT></TD>
          <TD align=middle><BR></TD>
          <TD align=middle><FONT size=2>".show_price($total)."</FONT></TD></TR>
        <TR>
          <TD align=left colSpan=2 rowSpan=2><FONT 
            size=2>Ïëàòåëüùèê:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</FONT></TD>
          <TD align=middle><FONT size=1>Ïåíÿ:</FONT></TD>
          <TD align=middle><BR></TD></TR>
        <TR align=middle>
          <TD><FONT size=1>Âñåãî:</FONT></TD>
          <TD><BR></TD></TR></P></TABLE></TD></TR>
  <TR>
    <TD vAlign=top align=right width=200 height=255><FONT size=2><BR><BR>
      <P>Êâèòàíöèÿ</P><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><FONT size=1>
      <P>Êàññèð</P></FONT></FONT></TD>
    <TD vAlign=center align=right width=100 height=255>
      <TABLE cellSpacing=0 cellPadding=3 width=370 border=1><FONT size=-1>
        <P align=center>ÈÍÍ $inn ÊÏÏ $kpp<BR><U>$reciever</U><BR><FONT size=1>ïîëó÷àòåëü ïëàòåæà</FONT></FONT> 
        <TBODY>
        <TR>
          <TD vAlign=center align=middle colSpan=4 height=10><FONT size=2>
            <P>Ðàñ÷åòíûé ñ÷åò ¹: 
            $rs&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ÁÈÊ: 
            $bik</P></FONT></TD></TR>
        <TR>
          <TD vAlign=center align=middle colSpan=4 height=10><FONT size=2>
            <P>Êîð. ñ÷.: $ks</P></FONT></TD></TR>
        <TR>
          <TD colSpan=4><FONT size=2>
            <CENTER>$cl[2]&nbsp;$cl[0]&nbsp;$cl[1]<BR>$cl[3],&nbsp;$cl[4]</CENTER>
            <CENTER><FONT size=1>ïëàòåëüùèê (ÔÈÎ, 
          àäðåñ)</FONT></CENTER></FONT></TD></TR>
        <TR>
          <TD align=middle colSpan=2><FONT size=2>Âèä ïëàòåæà</FONT></TD>
          <TD align=middle><FONT size=2>Äàòà</FONT></TD>
          <TD align=middle><FONT size=2>Ñóììà</FONT></TD></TR>
        <TR>
          <TD align=middle colSpan=2><FONT size=1>çàêàç ¹$orderID</FONT></TD>
          <TD align=middle><BR></TD>
          <TD align=middle><FONT size=2>".show_price($total)."</FONT></TD></TR>
        <TR>
          <TD align=left colSpan=2 rowSpan=2><FONT 
            size=2>Ïëàòåëüùèê:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</FONT></TD>
          <TD align=middle><FONT size=1>Ïåíÿ:</FONT></TD>
          <TD align=middle><BR></TD></TR>
        <TR align=middle>
          <TD><FONT size=1>Âñåãî:</FONT></TD>
          <TD><BR></TD></TR></P></TABLE></TD></TR></TBODY></TABLE>
  </CENTER>
</CENTER></BODY></HTML>
	";

	echo $invoice;

?>
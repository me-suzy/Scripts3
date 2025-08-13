<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//  Program Name         : RedTempest Profiler Pro                           //
//  Release Version      : 1.0.0                                             //
//  Program Author       : Eric Fredin ( RedTempest )                        //
//  Supplied by          : Scoons [WTN]                                      //
//  Nullified by         : CyKuH [WTN]                                       //
//  Distribution         : via WebForum, ForumRU and associated file dumps   //
//                                                                           //
//            Copyright 2000 RedTempest. All Rights Reserved.                //
///////////////////////////////////////////////////////////////////////////////
?>
<html>
<head>
<title><?php echo $site[title]; ?></title>

<style>
<!--
A {text-decoration: underline; color: #000000;}
A:hover {text-decoration: none; color: #000000;}
.form {background-color: #ffffff; color: #000000; font-family: Tahoma; border-style: inset; border-color: #336699; border-width: 1pt;}
.submit {background-color: #336699; color: #ffffff; font-family: Tahoma; font-size: 8pt; border-style: inset; border-color: #000000; border-width: 1pt;}
-->
</style>
</head>
<body bgcolor=336699 text=black>

<center>
<table bgcolor=ffffff width=90% border=0 cellpadding=4 cellspacing=0>
  <tr>
    <td width=40%><!--CyKuH [WTN]--><img src='minilogo.gif' border=0></td>
    <td width=60% valign=bottom align=right><font face=tahoma size=2><?php echo $site[status]; ?></font></td>
  </tr>
  <tr>
    <td width=100% colspan=2><table border=0 cellpadding=2 cellspacing=0 width=100%>
      <tr>
        <td width=10% valign=top><font face=tahoma size=2>Navigation:<br>
        <a href='profiler.php'>Home</a><br>
        <a href='profiler.php?action=vmain'>Clan Ranks</a><br>
        <a href='profiler.php?action=apply'>Join</a><br>
        <a href='profiler.php?action=code'>Rules</a><br><br>
        <?php echo $site[side_menu]; ?></font></td>
        <td width=75% valign=top><font face=tahoma size=2><?php echo $site[text]; ?></font></td>
        <td width=15% valign=top><font face=tahoma size=2>Headlines:<br><?php echo $site[headlines]; ?><br>Events:<br><?php echo $site[events]; ?></font></td>
      </tr>
    </table></td>
  </tr>
</table>

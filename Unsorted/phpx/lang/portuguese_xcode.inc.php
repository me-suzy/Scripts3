<?php
#$Id: portuguese_xcode.inc.php,v 1.2 2003/10/20 14:59:48 ryan Exp $
$xTitle[] = "What is XCode?";
$bodyText[] = "Xcode is a variation on the HTML tags you may already be familiar with. Basically, it allows you to add functionality or style to your message that would normally require HTML. You can use Xcode even if HTML is not enabled in the forums. You may want to use Xcode as opposed to HTML, even if HTML is enabled, because there is less coding required, and will never break the layout of the pages you are viewing.";

$xTitle[] = "URL and Email Links";
$bodyText[] = "Xcode, unlike some other bulletin board codes, does not require you to specify links (URL) and email links.  However, you can specify URL's if you wish. <br><br>To create a link, just enter the URL, with either ftp://, telnet://, http:// or https:// before the link and PHPX will automatically turn it into a link.  Email does the same, just put in the email address and when you save your post, PHPX will create an email link.<br><br>If you wish to specify a URL link, or create a link on text or image you may use the <font color=red>[url=][/url]</font> tags.<br><br>Linking an Image:<br><font color=red>[url=http://www.phpx.org/][img=http://www.phpx.org/images/someimage.jpg][/url]</font> will create a link to www.phpx.org using the image specified.";

$xTitle[] = "Bold, Underline, and Italics Text";
$bodyText[] = "To make italicized text, bold text, or underlined text by encasing the applicable sections of your text with either the [b] [/b], [i] [/i], or [u] [/u] tags.<br><br>Bold Text: <br><font color=red>[b]</font>Bold Text<font color=red>[/b]</font> becomes <b>Bold Text</b><br><br>Italicized Text:<br><font color=red>[i]</font>Italicized Text<font color=red>[/i]</font> becomes <i>Italicized Text</i> <br><br>Underlined Text:<br> <font color=red>[u]</font>Underlined Text<font color=red>[/u]</font> becomes <u>Underlined Text</u><br><br>You can also combine the elements:<br><font color=red>[u][b]</font>Combo Text<font color=red>[/b][/u]</font> becomes <u><b>Combo Text</b></u>";

$xTitle[] = "Text Alignment";
$bodyText[] = "Text can aligned however you would like using several tags: [left], [right], [center], and [block].<br><br>Left Aligned Text:<br><font color=red>[left]</font>left aligned<font color=red>[/left]</font> becomes <div align=left>left aligned</div><br><br>Right Aligned Text:<br><font color=red>[right]</font>right aligned<font color=red>[/right]</font> becomes <div align=right>right aligned</div><br><br>Center Aligned Text:<br><font color=red>[center]</font>center aligned<font color=red>[/center]</font> becomes <div align=center>center aligned</div><br><br>Justified Text:<br><font color=red>[block]</font>justified text<font color=red>[/block]</font> becomes <blockquote>justified text</blockquote><br><br>";

$xTitle[] = "Other Text Formatting";
$bodyText[] = "Text has three other attributes that can be controlled by XCode: size, style, color.<br><br>Text Size:<br>Text can be set to sizes anywhere from 1 (unreadable) to 24 (huge).<br><font color=red>[size=18]</font>large text<font color=red>[/size]</font> becomes <span style=font-size:18px>large text</span><br><br>Text Style (font):<br>Text can be set to several different font faces.  These need to be standard fonts only.<br><font color=red>[font=times]</font>Times New Roman<font color=red>[/font]</font> becomes <span style=font-family:times>Times New Roman</span><br><br>Text Color:<br>Text can also be any color.  You can use a color name, or the hex code for the color you want.<br><font color=red>[color=blue]</font>blue text<font color=red>[/color]</font> becomes <span style=color:blue>blue text</span><br><font color=red>[color=#0000FF]</font>blue text<font color=red>[/color]</font> becomes <span style=color:#0000FF>blue text</span>";

$xTitle[] = "Line Insert";
$bodyText[] = "To insert a line, use the [line] tag.<br><br><font color=red>[line]</font> becomes <hr width=100% border=2>";

$xTitle[] = "Lists";
$bodyText[] = "You can make bulleted lists or ordered lists (by number or letter).<br><br>Unordered, bulleted list:<br><font color=red>[list]</font><br><font color=red>[*]</font> This is the first bulleted item.<br><font color=red>[*]</font> This is the second bulleted item.<br><font color=red>[/list]</font><br>This produces:<br><ul><li>This is the first bulleted item.<li>This is the second bulleted item.</ul><br><br>Note that you must include a closing [/list] when you end each list.<br><br>Making ordered lists is just as easy. Just add either [list A], [list a], [list 1], [list i], [list I].<br> [list A] will produce a list from A to Z, capital letters.<br> [list a] will produce a list from a to z, lowercase letters.<br> [list 1] will produce numbered lists.<br> [list I] will produce a list using captial roman numerals.<br> [list i] will produce a list of lowercase roman numerals.<br><br>Here's an example:<br><font color=red>[list A]</font><br><font color=red>[*]</font> This is the first bulleted item.<br><font color=red>[*]</font> This is the second bulleted item.<br><font color=red>[/list]</font><br><br>This produces:<ol type=A><li>This is the first bulleted item.<li>This is the second bulleted item.";

$xTitle[] = "Images";
$bodyText[] = "To add a graphic within your message, just encase the URL of the graphic image as shown in the following example.<br><br><font color=red>[img]</font>http://www.yoururl.com/images/hot.jpg<font color=red>[/img]</font><br><br>In the example above, the XCode automatically makes the graphic visible in your message. Note: the \"http://\" part of the URL is REQUIRED for the [img] code. Also note: some forums may disable the [img] tag support to prevent objectionable images from being viewed.";

$xTitle[] = "Quoting other Messages";
$bodyText[] = "To reference something specific that someone has posted, just cut and paste the applicable verbiage and enclose it as shown below.<br><br><font color=red>[quote]</font>Ask not what your country can do for you....ask what you can do for your country.<font color=red>[/quote]</font> becomes <blockquote><div class=small><i>quote:</i><hr width=100% align=center>Ask not what your country can do for you....ask what you can do for your country.<hr width=100% align=center></blockquote></div>";

$xTitle[] = "Posting Code";
$bodyText[] = "Similar to the quote tag, the code tag preserves formatting. This useful for displaying programming code.<br><br><font color=red>[code]</font>#!/usr/bin/perl<br><br>print \"Content-type: text/html\n\n\";<br>print \"Hello World!\"; <font color=red>[/code]</font> becomes <font color=green><blockquote>#!/usr/bin/perl<br><br>print \"Content-type: text/html\n\n\";<br>print \"Hello World!\"; </blockquote></font>";

$xTitle[] = "Other";
$bodyText[] = "Xcode can be customized.  Some of the tags mentioned here may not work because your forum admin may have changed their function.";






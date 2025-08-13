<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/



		// show detailed product information

		$q = db_query("SELECT categoryID, name FROM ".PRODUCTS_TABLE." WHERE productID=$productID") or die (db_error());
		if (!($a = db_fetch_row($q)))
		{
			die (ERROR_CANT_FIND_REQUIRED_PAGE); //no item found
		}

		$path = calculatePath(&$cats, $a[0]);

		//show category, where product is placed (with a path to it)
		$s="<a href=\"index.php\">".LINK_TO_HOMEPAGE."</a> : ";
		for ($i=1; $i<count($path); $i++)
			$s .= "<a href=\"index.php?categoryID=$path[$i]\">".$cats[categoryIndexInArray(&$cats, $path[$i])][1]."</a> : ";
		$out .= "<b>\n".$s."</b><br>";

		if (!isset($discuss)) //show product info
		{

			showGood($productID,false,&$out);

		}
		else // product discussion
		{

			$out .= "<p><font class=cat><b>$a[1]</b> :: <u>".DISCUSSION_TITLE."</u></font> [ <a href=\"index.php?productID=$productID\">".MORE_INFO_ON_PRODUCT."</a> ]";
			$out .= "<p><center>";
			$q = db_query("select count(*) from ".DISCUSSIONS_TABLE." WHERE productID=$productID") or die (db_error());
			$cnt = db_fetch_row($q);
			if ($cnt[0])
			{
			   $q = db_query("select Author, Body, add_time, DID, Topic FROM ".DISCUSSIONS_TABLE." WHERE productID=$productID ORDER BY add_time DESC") or die (db_error());

			   while ($row = db_fetch_row($q))
			   {
				$out .= "<table width=90% border=0>";
				$out .= "<tr>";
				$out .= "<td width=1%><font class=average><nobr>".DISCUSSION_NICKNAME.":</nobr></font></td><td width=99% align=left>".str_replace("<","&lt;",$row[0])." ($row[2])</td>";
				$out .= "</tr>";

				$out .= "<tr>";
				$out .= "<td width=1%><font class=average><nobr>".DISCUSSION_SUBJECT.":</nobr></font></td><td width=99% align=left><b>".str_replace("<","&lt;",$row[4])."</b></td>";
				$out .= "</tr>";

				if (trim($row[1]))
				{
					$out .= "<tr>";
					$out .= "<td width=1% valign=top><font class=average><nobr>".DISCUSSION_BODY.":</nobr></font></td><td width=99% align=left>".nl2br(str_replace("<","&lt;",$row[1]))."</td>";
					$out .= "</tr>";
				}
				$out .= "</table>";
				if (isset($log) && !strcmp($log,ADMIN_LOGIN)) $out .= "[ <a href=\"index.php?productID=$productID&discuss=true&remove_topic=$row[3]\">".DISCUSSION_DELETE_POST_LINK."</a> ]";
				$out .= "<hr size=1 width=90%>";
			   }
		} else $out .= DISCUSSION_NO_POSTS_ON_ITEM_STRING;

			$out .= "

</center>

<p>".DISCUSSION_ADD_MESSAGE.":
<form action=\"index.php?productID=$productID&discuss=true\" method=post name=\"formD\" onSubmit=\"return validate_disc(this);\">
<table>
<tr>
<td>".DISCUSSION_NICKNAME.":</td>
<td><input type=text name=nick></td>
</tr>
<tr>
<td align=right>".DISCUSSION_SUBJECT.":</td>
<td><input type=text name=topic></td>
</tr>
<tr>
<td align=right valign=top>".DISCUSSION_BODY.":</td>
<td>
<textarea name=body cols=50 rows=10>
</textarea>
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>
<input type=submit name=add_topic value=\"".POST_BUTTON."\">
<input type=reset value=\"".RESET_BUTTON."\">
</td>
</tr>
</table>
</form>

			";

			};

?>
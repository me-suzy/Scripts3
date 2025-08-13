<?php
// upgrade.php - part of ssLinks v1.22
// ==================================
/* This script will upgrade the ssLinks v1.1 database tables for
   compatibility with ssLinks v1.22.  You only need to run this
   script if you are updating from ssLinks v1.1.
*/

if (!$submit)
{
	// Form not submitted - display form.
?><html>
<head>
<title>ssLinks Upgrade Script</title>
<style type="text/css">
.standard {color: black; font-family: verdana, arial, helvetica, sans-serif; font-size: 9 pt}
.small {color: black; font-family: verdana, arial, helvetica, sans-serif; font-size: 7 pt}
</style></head>
<body><center>
<table width=80% border=1 cellpadding=3 cellspacing=1>
<td class="standard"><b>ssLinks Upgrade Script</b><p>

This script will upgrade the ssLinks v1.1 database tables for
   compatibility with ssLinks v1.22.  You only need to run this
   script if you are updating your links database from ssLinks v1.1.<p>
   
<b>mySQL Database Details:</b><p>

<form action=upgrade.php method=post>
Database Host: <input type=text name=db_host size=20 value="localhost"><p>

Database Username: <input type=text name=db_user size=20 value="Username"><p>

Database Password: <input type=text name=db_pass size=20 value="Password"><p>

Database Name: <input type=text name=db_name size=20><p>

<input type=submit name=submit value="Go!">
</td></table></center></body></html>
<?php
exit;
}

// If script gets this far they have submitted

if ((!$db_host) || (!$db_user) || (!$db_pass) || (!$db_name))
{
	header("Location: upgrade.php");
	exit;
}

$sql = "ALTER TABLE sslinkcats ADD lcat_numlinks INT (11) not null";

$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or die("Unable to connect to database server.");
mysql_select_db($db_name, $cnx)
		or die("Unable to select database.");
$result = mysql_query($sql);

if (!$result)
	die("The upgrade failed for some reason.  Are you sure the database tables sslinks and sslinkcats already exist in your database?");

// Now update lcat_numlinks in the database

$return = numlinks_array(); // Build array of number of links in each category
$numlinks = $return[0];
$numlinkstree = $return[1];

build_numlinks();

function numlinks_array()
{
	global $db_name, $db_host, $db_user, $db_pass;
	// returns an array of the number of links in each category
	$sql = "SELECT sslinkcats.lcat_id, COUNT(*) AS NumLinks, lcat_numlinks
		FROM sslinks, sslinkcats 
		WHERE link_cat = sslinkcats.lcat_id 
		AND link_validated = 'yes'
		GROUP BY link_cat";
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$result = mysql_query($sql);
	if (!$result)
		custom_die("Database error while getting array of number of links in categories.");
	$numlinks[] = "";
	$numlinkstree[] = "";
	while ($row = mysql_fetch_array($result))
	{
		$lcat_id = $row["lcat_id"];
		$numlinks[$lcat_id] = $row["NumLinks"];
		$numlinkstree[$lcat_id] = $row["lcat_numlinks"];
	}
	$return[0] = $numlinks;
	$return[1] = $numlinkstree;
	return $return;
}

function build_numlinks()
{
	// This function goes through every category in the database and sets the
	// lcat_numlinks database field for it - storing the number of links held
	// in that category AND all sub categories.  The function should be called
	// whenever an alteration is made to the number of links in the database.
	
	// The good news: $numlinks_array already has the number of links in each
	// category (without the recursion).
	
	global $numlinks, $numlinks_cache_tree, $db_host, $db_user, $db_pass, $db_name;
	
	// OK we need to reset the $numlinks and $numlinks_cache_tree variables
	$return = numlinks_array(); // Build array of number of links in each category
	$numlinks = $return[0];
	
	unset($numlinks_cache_tree);
	
	// First problem - need to loop through every category in the database.
	// For each category we are going to have to trace it's children.  This
	// will involve a VERY nasty recursive SQL query, which is the reason
	// this function should be run as in-frequently as possible.
	
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");

	$numlinks_cache_tree = category_numlinks_cache ('0');

	/* DEBUG reset ($numlinks_cache_tree);
	while (list ($cat, $numlinks) = each ($numlinks_cache_tree)) 
	{
		echo "Category $cat has $numlinks links.<br>\n";
	} */

	// Woohoo!! $numlinks_cache_tree is now an array with all the right values
	// All that remains is a big ugly SQL call to EVERY category record setting
	// the value as listed in that array.

	$sql = "SELECT lcat_id FROM sslinkcats";
	$result = mysql_query ($sql) or custom_die("SQL Error!");
	while ($row = mysql_fetch_array($result))
	{
		$currentid = $row["lcat_id"];
		// Update cached record for that link
		$sql2 = "UPDATE sslinkcats SET
		lcat_numlinks = '" . $numlinks_cache_tree[$currentid] . "'
		WHERE lcat_id = '$currentid'";
		$result2 = mysql_query($sql2) or custom_die("SQL Error");
	}
	
	/* DEBUG while (list ($cat, $numlinks) = each ($numlinks_cache_tree)) 
	{
		echo "Category $cat has $numlinks links.<br>\n";
	}
	echo "All done!"; */
}

function category_numlinks_cache ($cat)
{
	// Nasty recursive function.  Here are the notes I made when designing it:
	/*
		OK we have a category - here are the steps:
		1. Is the category in $numlinks_cache_tree[]?
			YES: Excellent, sorted, next please.
			NO:  OK time for some processing then
		2. Fetch a list of sub categories for that category.
		   Are there any sub categories?
		   	YES: Do this process for each of them
		   	NO:  Excellent - next step (step 3)
		3. Add up the numlinks from the sub categories.
		   Now add on the number of links in this category.
		   Now store the value in $numlinks_cache_tree[].
	*/
	global $numlinks_cache_tree, $numlinks;
	// echo "Processing category <b>$cat</b><br>"; // DEBUG
	$thiscat_numlinks = 0;	// Variable to store numlinks for this cat
	if (isset($numlinks_cache_tree[$cat]))
	{
		echo "... Category $cat cached, returning... <br>";
		return $numlinks_cache_tree;
	}
	// Get a list of sub categories
	$sql = "SELECT lcat_cat, lcat_id FROM sslinkcats WHERE lcat_cat = '$cat'";
	$result = mysql_query($sql);
	$numcats = mysql_num_rows($result);
	if ($numcats > 0)
	{
		// Do this function for each sub category
		// echo "... Category $cat has $numcats sub cats...<br>"; // DEBUG
		while ($row = mysql_fetch_array($result))
		{
			$newcat = $row["lcat_id"];
			$numlinks_cache_tree = category_numlinks_cache ($newcat);
			$thiscat_numlinks += $numlinks_cache_tree[$newcat];
		}
	}
	// Add up the links and store them in the cache variable
	// $thiscat_numlinks already contains the total links in all sub cats
	$thiscat_numlinks += $numlinks[$cat];
	$numlinks_cache_tree[$cat] = $thiscat_numlinks;
	return $numlinks_cache_tree;
}

?><html>
<head>
<title>ssLinks Upgrade Sucessful</title>
<style type="text/css">
.standard {color: black; font-family: verdana, arial, helvetica, sans-serif; font-size: 9 pt}
.small {color: black; font-family: verdana, arial, helvetica, sans-serif; font-size: 7 pt}
</style></head>
<body><center>
<table width=80% border=1 cellpadding=3 cellspacing=1>
<td class="standard"><b>Upgrade Done!</b><p>

The number of links in each category has also been cached in the database.<p>

Woohoo!  Now go <a href="links.php">Here</a>.
</td></table></center></body></html>
<?php
/***********************************************************
*
* ssLinks v1.22 - a PHP / mySQL links management system
* (c) Simon Willison 2001
* For more information, visit www.sslinks.co.uk
*
***********************************************************/

/***********************************************************
*
* Change log for v1.22 (improvements since 1.2)
*
* i.   Small template fix in confirm_catdel.tmpl
* ii.  The elusive top rated SQL error has finally been squashed :o)
*      (thanks freakysid on www.sitepointforums.com)
* iii. Login form now uses META tag redirect instead of standard
*      header method - this prevents conflicts with the cookie header
*      which may have prevented some browsers from logging in.
*
***********************************************************/

/***********************************************************
*
* Change log for v1.2 (improvements since 1.1)
*
* i.   Fixed the problem with submitted links that had not yet been validated
*      being included in the routine that counts the number of links in each
*      category.
* ii.  New template system has eliminated virtually all hard coded HTML from
*      the script.
* iii. ssLinks can now display the number of links in a category including
*      all links in sub categories of that category.
* iv.  Various minor bug fixes.
*
***********************************************************/

/***********************************************************
*2
* Change log for v1.1 (improvements since 1.0)
*
* i.   Fixed 'FLF Central' bug where some error messages and the login screen
*      contained a reference to 'FLF Central' - they now show the site name as
*      defined by the $sitename variable. :o)
* ii.  Added 'Top Rated Links', 'Most Popular Links' and 'New Links' pages.
* iii. Made it possible to edit categories (omission from ssLinks v1.0).
* iv.  Added number of links in this category - appears next to the category name.
* v.   Added alternative header / footer functions allowing proper PHP includes.
*
***********************************************************/

// mySQL database Host / Name / Username / Password

$db_host = "localhost"; // Your mySQL server host address
$db_name = "searchthis_net"; // The name of the database to use
$db_user = "onequality"; // Your mySQL username
$db_pass = "49xwy46"; // Your mySQL password

// Admin username / password for the script

$admin_user = "searadmin"; // This is the username used to log in as an admin
$admin_pass = "67xps49"; // This is the password used to log in as an admin

// Files containing header and footer HTML, also name of the site

$sitename = "SearchThis.Net Link Indexing"; 		// Set this to the name of your site
$sitemail = "1qualityhost@1qualityhost.com"; 	// Set to your e-mail address.
					// mails sent automatically by the links script
					// will be 'from' this address.

// Preferences:

$minvotes = "10"; // Minimum number of votes needed before link displayed on "top rated" page
$nol = "20"; // Number Of Links to show on what's new / top rated / most popular

$template_dir = "templates/"; // Set this to the directory that templates are stored in.
		              // Alternatively leave it blank if templates in same dir as ssLinks scripts
$header_file = "header.html"; // Set this to the path to your header template file
$footer_file = "footer.html"; // Set this to the path to your footer template file
$template_php = false; // Set this to true if you wish to use PHP code in your template files
		      // if set to true template support will be turned off - i.e %title%
		      // will not be replaced by the title of the page.  Instead you should
		      // insert the following code within your PHP where you want the title
		      // to be displayed:
		      // 		<?php echo $title; ? > (with no space between ? and >)

// NB - any occurences of %title% in the header file will be replaced by the page title

$template_comments = false;	// Set to true and HTML comments will be added to all
				// output HTML showing which template files are being
				// used at any particular point.  Useful for 
				// understanding how the template system works.

// You should not need to change anything beneath this line.

$template_cache[] = "";	// Initialise the template cache array
$debug = false;	// Set to true for debugging information

function html_header($title)
{ 
	// open header file - replace %title% in the file with whatever title is
	global $header_file, $template_php;
	if (!$template_php)
	{	$file = fopen($header_file, "r");
		$contents = fread($file, filesize($header_file));
		fclose($file);
		$header = eregi_replace('%title%', $title, $contents);
		return $header;
	}
	else
	{
		include($header_file);
		return "";
	}
}

function html_footer()
{
	global $admin, $footer_file, $template_php, $template_cache;
	// First build standard footer for all links pages - then load template
	$footer =  "";
	if (!$admin)
	{
		$login_validate = ss_template('admin_login_option.tmpl');
	}
	else
	{
		$login_validate = ss_template('validate_links_option.tmpl');
	}
	$footer = ss_template('footer_menu.tmpl', array('%login_or_validate%' => $login_validate));
	
	/* DEBUG lines 
	reset ($template_cache);
	$footer .= "<br>Templates Loaded: " . count($template_cache) . "<br>";
	while (list($name, $template) = each ($template_cache))
	{
		$footer .= "Template: $name<br>\n";
	} */
	
	if (!$template_php)
	{
		// Now load template and add it to the HTML already generated.
		$file = fopen($footer_file, "r");
		$contents = fread($file, filesize($footer_file));
		fclose($file);
		$footer .= $contents;
		return $footer;
	}
	else
	{
		echo $footer;
		include($footer_file);
		return "";
	}	
}

function ss_template($filename,$replacements='none')
{ 
	// Complicated function - bare with me on this one...
	// 	$filename = name of the template file to open
	//	$replacements = associative array of replacements to be made
	// The function will open the template file specified (within the directory
	// specified by $template_dir if necessary) and make all replacements listed
	// within the $replacements array - it will then return the generated HTML.
	// If no replacement array specified it just returns the contents of the file.
	// IN ADDITION - this function caches templates that it has opened in an
	// array called $template_cache - this is to save it from opening the same 
	// template more than once per script execution.
	global $template_dir, $template_cache, $debug, $template_comments;
	
	$template_fullpath = $template_dir . $filename;
	
	if ($template_cache[$template_fullpath])
	{
		// Template has been loaded already - use cached version
		$contents = $template_cache[$template_fullpath];
	}
	else
	{
		// Template has not been loaded yet - load it from the file
		if ($debug) echo "<small>Loading Template: $template_fullpath</small><br>";
		$file = @fopen($template_fullpath, "r");
		if (!$file) custom_die("Could not open template file $template_fullpath");
		$contents = fread($file, filesize($template_fullpath));
		fclose($file);
		// Save template in the cache
		if ($template_comments)
		{
			// Add comments to the template HTML
			$contents = "\n<!-- TEMPLATE: $filename STARTS -->\n" . $contents;
			$contents .= "\n<!-- TEMPLATE: $filename ENDS -->\n";
		}
		$template_cache[$template_fullpath] = $contents;
	}
	
	if (is_array($replacements))
	{
		// Replacements array is set - perform the replacements
		while (list($code, $replace) = each ($replacements))
		{
    			$contents = eregi_replace($code, $replace, $contents);
    		}
    	}
	return $contents;
}


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

function cats_table($id)
{
	global $db_host, $db_name, $db_user, $db_pass, $admin, $numlinks, $numlinkstree;
	
	// Returns HTML for a table showing all categories attached to $num
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database in cats_table().");
	$result = mysql_query("SELECT lcat_id, lcat_name, lcat_ranking FROM sslinkcats WHERE lcat_cat = '$id' ORDER BY lcat_ranking DESC, lcat_name");
	if (!$result)
		custom_die("SQL result to get cats failed");
	$num = mysql_num_rows($result);
	if ($num == 0)
		return "";
	$html = ss_template('categories_list_header.tmpl');
	$i = 1;
	while ($row = mysql_fetch_array($result))
	{
		$catid = $row["lcat_id"];
		$catname = $row["lcat_name"];
		$rank = $row["lcat_ranking"];
		$lcat_numlinks = $numlinks[$catid];
		$lcat_numlinkstree = $numlinkstree[$catid];
		if (!$lcat_numlinks)
			$lcat_numlinks = '0';
		if (!$lcat_numlinkstree)
			$lcat_numlinkstree = '0';
		if ($admin)
		{
			$admin_html = ss_template('categories_item_admin.tmpl',array('%catid%' => $catid, '%rank%' => $rank));
		}
		else
		{
			$admin_html = "";
		}
		
		$html .= ss_template('categories_item.tmpl',array('%catid%' => $catid, '%catname%' => $catname, '%cat_numlinks%' => $lcat_numlinks, '%cat_numlinkstree%' => $lcat_numlinkstree, '%admin_options%' => $admin_html));
		if (is_int($i / 2))
			$html .= ss_template('categories_list_nextrow.tmpl');
		$i++;
	}
	$html .= ss_template('categories_list_footer.tmpl');
	return $html;
}

function cats_sql($sql)
{
	global $db_host, $db_name, $db_user, $db_pass, $admin, $numlinks, $numlinkstree;
	
	// Returns HTML for a table showing all categories found using search term

	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$result = mysql_query($sql);
	if (!$result)
		custom_die("SQL result failed");
	$num = mysql_num_rows($result);
	if ($num == 0)
		return "";
	$html = ss_template('categories_list_header.tmpl');
	$i = 1;
	while ($row = mysql_fetch_array($result))
	{
		$catid = $row["lcat_id"];
		$catname = $row["lcat_name"];
		$rank = $row["lcat_ranking"];
		$lcat_numlinks = $numlinks[$catid];
		$lcat_numlinkstree = $numlinkstree[$catid];
		if (!$lcat_numlinks)
			$lcat_numlinks = '0';
		if (!$lcat_numlinkstree)
			$lcat_numlinkstree = '0';
		if ($admin)
		{
			$admin_html = ss_template('categories_item_admin.tmpl',array('%catid%' => $catid, '%rank%' => $rank));
		}
		else
		{
			$admin_html = "";
		}
		$html .= ss_template('categories_item.tmpl',array('%catid%' => $catid, '%catname%' => $catname, '%cat_numlinks%' => $lcat_numlinks, '%cat_numlinkstree%' => $lcat_numlinkstree, '%admin_options%' => $admin_html));
		if (is_int($i / 2))
			$html .= ss_template('categories_list_nextrow.tmpl');
		$i++;
	}
	$html .= ss_template('categories_list_footer.tmpl');
	return $html;
}

function get_catheader($id)
{
	global $db_host, $db_name, $db_user, $db_pass;
	// returns the description field lcat_header of the specified category
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$result = mysql_query("SELECT lcat_header FROM sslinkcats WHERE lcat_id = '$id'");
	if (!$result)
		custom_die("SQL result failed to get cat_header");
	while ($row = mysql_fetch_array($result))
	{
		$header = $row["lcat_header"];
	}
	$return = "";
	if ($header != "")
		$return = ss_template('category_headtext.tmpl',array('%cat_header%' => $header));
	return $return;
}

function get_catname($id)
{
	global $db_host, $db_name, $db_user, $db_pass;
	// returns the name of the specified category
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$result = mysql_query("SELECT lcat_name FROM sslinkcats WHERE lcat_id = '$id'");
	if (!$result)
		custom_die("SQL result failed to get cat name");
	while ($row = mysql_fetch_array($result))
	{
		$name = $row["lcat_name"];
	}
	if ($name == "")
		$name = "Links Home Page";
	return $name;		
}

function show_links($id)
{
	global $db_host, $db_name, $db_user, $db_pass, $admin;
	
	// Returns HTML for displaying list of links attached to category where id = $id
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database in show_links().");
	$result1 = mysql_query("SELECT * FROM sslinks WHERE link_cat = '$id' AND link_validated = 'yes' AND link_recommended = 'yes' ORDER BY link_name");
	if (!$result1)
		custom_die("SQL result failed to get links: $result1");
	$num1 = mysql_num_rows($result1);
	$result2 = mysql_query("SELECT * FROM sslinks WHERE link_cat = '$id' AND link_validated = 'yes' AND link_recommended = 'no' ORDER BY link_name");
	if (!$result2)
		custom_die("SQL result failed");
	$num2 = mysql_num_rows($result2);
	if (($num1 == 0) && ($num2 == 0))
		return false;
	$html = "";
	while ($row = mysql_fetch_array($result1))
	{
		$lid = $row["link_id"];
		$name = $row["link_name"];
		$hits = $row["link_hits"];
		$desc = $row["link_desc"];
		$url = $row["link_url"];
		$totalrate = $row["link_totalrate"];
		$numvotes = $row["link_numvotes"];
		$dateadd = $row["link_dateadd"];
		$dateadd = date ("jS M Y", $dateadd);
		if ($numvotes > 0)
		{
			$avgrate = sprintf ("%.2f", ($totalrate / $numvotes));
		}
		else
		{
			$avgrate = '0';
		}
		if ($admin)
		{
			$admin_html = ss_template('linklist_item_admin.tmpl', array('%linkid%' => $lid));
		}
		else
		{
			$admin_html = "";
		}
		$html .= ss_template('linklist_item.tmpl', array(
				'%linkid%' => $lid,
				'%rec%' => ss_template('linklist_rec.tmpl'),
				'%url%' => $url,
				'%name%' => $name,
				'%description%' => $desc,
				'%hits%' => $hits,
				'%votes%' => $numvotes,
				'%rating%' => $avgrate,
				'%date%' => $dateadd,
				'%admin%' => $admin_html,
				'%validate_list_html%' => ''
			));
	}
	if (strlen($html) > 1)
	{
		$html .= ss_template('linklist_seperator.tmpl');
	}
	$row[] = "";
	while ($row = mysql_fetch_array($result2))
	{
		$lid = $row["link_id"];
		$name = $row["link_name"];
		$hits = $row["link_hits"];
		$url = $row["link_url"];
		$desc = $row["link_desc"];
		$totalrate = $row["link_totalrate"];
		$numvotes = $row["link_numvotes"];
		$dateadd = $row["link_dateadd"];
		$dateadd = date ("jS M Y", $dateadd);
		
		if ($numvotes > 0)
		{
			$avgrate = sprintf ("%.2f", ($totalrate / $numvotes));
		}
		else
		{
			$avgrate = '0';
		}
		if ($admin)
		{
			$admin_html = ss_template('linklist_item_admin.tmpl', array('%linkid%' => $lid));
		}
		else
		{
			$admin_html = "";
		}
		$html .= ss_template('linklist_item.tmpl', array(
				'%linkid%' => $lid,
				'%rec%' => '',
				'%url%' => $url,
				'%name%' => $name,
				'%description%' => $desc,
				'%hits%' => $hits,
				'%votes%' => $numvotes,
				'%rating%' => $avgrate,
				'%date%' => $dateadd,
				'%admin%' => $admin_html,
				'%validate_list_html%' => ''
			));
	}
	$html = ss_template('linklist.tmpl', array('%links%' => $html));
	return $html;
}

function links_sql($sql)
{
	global $db_host, $db_name, $db_user, $db_pass, $admin;
	
	// Returns HTML for displaying list of links found using supplied SQL query
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$result = mysql_query($sql);
	if (!$result)
		custom_die("SQL result failed");
	$num = mysql_num_rows($result);
	if ($num == 0)
		return false;
	$html = "";
	while ($row = mysql_fetch_array($result))
	{
		$lid = $row["link_id"];
		$name = $row["link_name"];
		$hits = $row["link_hits"];
		$desc = $row["link_desc"];
		$url = $row["link_url"];
		$totalrate = $row["link_totalrate"];
		$numvotes = $row["link_numvotes"];
		$dateadd = $row["link_dateadd"];
		$dateadd = date ("jS M Y", $dateadd);
		if ($numvotes > 0)
		{
			$avgrate = sprintf ("%.2f", ($totalrate / $numvotes));
		}
		else
		{
			$avgrate = '0';
		}
		if ($admin)
		{
			$admin_html = ss_template('linklist_item_admin.tmpl', array('%linkid%' => $lid));
		}
		else
		{
			$admin_html = "";
		}
		$html .= ss_template('linklist_item.tmpl', array(
				'%linkid%' => $lid,
				'%rec%' => '',
				'%url%' => $url,
				'%name%' => $name,
				'%description%' => $desc,
				'%hits%' => $hits,
				'%votes%' => $numvotes,
				'%rating%' => $avgrate,
				'%date%' => $dateadd,
				'%admin%' => $admin_html,
				'%validate_list_html%' => ''
			));
	}
	$html = ss_template('linklist.tmpl', array('%links%' => $html));
	return $html;
}

function jump_to($id)
{
	// redirect user to URL of $id and increment the hit counter
	global $db_host, $db_name, $db_user, $db_pass;
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$result = mysql_query("SELECT link_url, link_hits FROM sslinks WHERE link_id = '$id'");
	if (!$result)
		custom_die("SQL result failed");
	$num = mysql_num_rows($result);
	if ($num == 0)
	{
		header("Location: links.php");
		exit;
	}
	while ($row = mysql_fetch_array($result))
	{
		$hits = $row["link_hits"];
		$url = $row["link_url"];
	}
	$hits++;
	$result2 = @mysql_query("UPDATE sslinks SET link_hits = '$hits' WHERE link_id = '$id'");
	header("Location: $url");
	exit;
}

function is_admin()
{
	global $Links_Cookie_User, $Links_Cookie_Pass, $admin_user, $admin_pass;
	if (($Links_Cookie_User == $admin_user) && ($Links_Cookie_Pass == $admin_pass))
		return true;
	return false;
}

function login($username, $password)
{
	setcookie("Links_Cookie_User", $username, time() + 3600);
	setcookie("Links_Cookie_Pass", $password, time() + 3600);
	header("Location: links.php");
	exit;
}

function logout()
{
	setcookie("Links_Cookie_User", "");
	setcookie("Links_Cookie_Pass", "");
	header("Location: links.php");
	exit;
}

function admin_options($cat)
{
	// display admin options for category
	global $linkshtml;
	if ((cats_table($cat) == "") && (!$linkshtml))
	{
		$delcat = " - <a href=\"links.php?action=delcat&id=$cat\">Delete This Category</a>";
	}
	else
	{
		$delcat = "";
	}
	$html = ss_template('admin_controls.tmpl', array(
		'%catid%' => $cat,
		'%delcat%' => $delcat
	));
	return $html;
}

function do_rating($id, $score)
{
	global $db_host, $db_name, $db_user, $db_pass;
	$cookie = "cookie_rate" . $id;
	global $$cookie;
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$result = mysql_query("SELECT link_totalrate, link_numvotes, link_cat FROM sslinks WHERE link_id = '$id'");
	if (!$result)
		custom_die("SQL result failed");
	$num = mysql_num_rows($result);
	if ($num == 0)
	{
		header("Location: links.php");
		exit;
	}
	while ($row = mysql_fetch_array($result))
	{
		$numvotes = $row["link_numvotes"];
		$totalrate = $row["link_totalrate"];
		$cat = $row["link_cat"];
	}
	if ($$cookie == "yes")
	{
		header("Location: links.php?cat=$cat");
		exit;
	}
	$numvotes++;
	$totalrate = $totalrate + $score;
	$result2 = @mysql_query("UPDATE sslinks SET link_numvotes = '$numvotes', link_totalrate = '$totalrate' WHERE link_id = '$id'");
	setcookie($cookie, "yes", time() + 360000);
	header("Location: links.php?cat=$cat");
	exit;	
}

function add_link($link_name, $link_url, $link_desc, $link_cat, $link_recommended)
{
	// adds a link to the database
	global $db_host, $db_name, $db_user, $db_pass, $admin, $sitename;
	if ((!$link_name) || (!$link_url) || (!$link_desc) || (!isset($link_cat)))
	{
		echo html_header("$sitename - Error");
		echo ss_template('error_addlink.tmpl');
		echo html_footer();
		exit;
	}
	if (!$admin)
	{
		header("Location: links.php?action=logout");
		exit;
	}
	if (!$link_recommended)
		$link_recommended = "no";
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$time = time();
	$sql = "INSERT INTO sslinks SET
		link_name = '$link_name',
		link_cat = '$link_cat',
		link_url = '$link_url',
		link_desc = '$link_desc',
		link_numvotes = '0',
		link_dateadd = '$time',
		link_totalrate = '0',
		link_validated = 'yes',
		link_recommended = '$link_recommended'";
	$result = mysql_query($sql);
	if (!$result)
	{
		custom_die("SQL result failed - link was not added");
	}
	else
	{
		// Rebuild number of links for categories
		build_numlinks();
		header("Location: links.php?cat=$link_cat");
		exit;
	}	
}


function add_cat($lcat_name, $lcat_header, $lcat_cat, $lcat_ranking)
{
	// adds a link to the database
	global $db_host, $db_name, $db_user, $db_pass, $admin, $sitename;
	if ((!$lcat_name) || (!isset($lcat_cat)))
	{
		echo html_header("$sitename - Error");
		echo ss_template('error_addcat.tmpl');
		echo html_footer();
		exit;
	}
	if (!$admin)
	{
		header("Location: links.php?action=logout");
		exit;
	}
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$sql = "INSERT INTO sslinkcats SET
		lcat_name = '$lcat_name',
		lcat_cat = '$lcat_cat',
		lcat_header = '$lcat_header',
		lcat_ranking = '$lcat_ranking'";
	$result = mysql_query($sql);
	if (!$result)
	{
		custom_die("SQL result failed - category was not added");
	}
	else
	{
		// Rebuild number of links for categories
		build_numlinks();
		header("Location: links.php?cat=$lcat_cat");
		exit;
	}	
}

function do_breadcrumbs($cat)
{
	/* returns HTML for the breadcrumbs for the specified category

	This is NOT template driven - if you want to change the look of the breadcrumbs
	you will need to alter this function manually.

	Logic:  First round ($lcat != 0), get the name and id of the current category 
	i.e the one being displayed.  The name will be displayed without a link at the 
	end of the breadcrumbs.  Stick the HTML for this in the temporary HTML building 
	variable.

	Second round, get the name and id of the next category up and add it (formatted) 
	to the front of the HTML buliding variable.

	Repeat this step until $lcat = 0.  At this point bung on the link to the Links 
	home page.

	I'm doing it a nasty way, with a loop that performs a database query each cycle 
	*/

	if ($cat == 0)
	{
		// This is the first page so no breadcrumbs needed
		return ss_template('breadcrumbs_indexpage.tmpl');
	}
	global $db_host, $db_name, $db_user, $db_pass;
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	// Now loop through building up array $breadcrumbs until root (ie cat 0) is reached
	$lcat = $cat;
	$breadcrumbs = "";
	while ($lcat != 0)
	{
		$sql = "SELECT lcat_cat, lcat_name, lcat_id FROM sslinkcats WHERE lcat_id = '$lcat'";
		$result = mysql_query($sql);
		if (!$result)
		{
			custom_die("SQL result failed - can't build breadcrumbs.");
		}
		while ($row = mysql_fetch_array($result))
		{
			$id = $row["lcat_id"];
			$lcat = $row["lcat_cat"];
			$name = $row["lcat_name"];
		}
		// defence against infinite loops
		if (!$id)
		{
			header("Location: links.php");
			exit;
		}
		if ($id == $cat)
		{
			// First round, so add just the name of the category and not the link
			$breadcrumbs = ss_template('breadcrumbs_endbit.tmpl', array('%name%' => $name));
			continue; // Start the next loop straight away
		}
		$breadcrumbs = ss_template('breadcrumbs_bit.tmpl', array('%catid%' => $id, '%name%' => $name)) . $breadcrumbs;
	} // end while
	$breadcrumbs = ss_template('breadcrumbs_startbit.tmpl') . $breadcrumbs;
	return $breadcrumbs;
}

function del_cat($id)
{
	// Requests confirmation then deletes the current category
	
	global $admin, $submit, $db_host, $db_name, $db_user, $db_pass;
	if (!$admin)
	{
		header("Location: links.php?action=logout");
		exit;
	}
	if ((cats_table($cat) == "") && (show_links($cat)))
	{
		// This category still has links / categories below it.  Do not allow it to be deleted.
		echo html_header("Error");
		echo ss_template('error_delcat.tmpl');
		echo html_footer();
		exit;
	}
	if (!$submit)
	{
		// display page requesting confirmation
		echo html_header("Confirm Deletion");
		echo ss_template('confirm_catdel.tmpl', array('%catid%' => $id));
		echo html_footer();
		exit;
	}
	// submit has been clicked - delete the category
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$sql = "DELETE FROM sslinkcats WHERE lcat_id = '$id'";
	$result = mysql_query($sql);
	if (!$result)
	{
		custom_die("Unable to delete category from database");
	}
	else
	{
		// display page requesting confirmation
		echo html_header("Category Deleted");
		echo ss_template('category_deleted.tmpl', array('%catid%' => $id));
		echo html_footer();
		exit;
	}
}

function rec_link($cat)
{
	// Allows any visitor to recommend a link for addition to the database - the link will be added
	// but the field link_validated will be set to 'no'
	
	global $db_host, $db_name, $db_user, $db_pass, $link_name, $link_desc, $link_url, $link_addname, $link_addemail, $submit, $sitename;
	
	if (!ereg("^[0-9]+$", $cat))
	{
		// function has been fed an invalid category id
		header("Location: links.php");
		exit;
	}
	if (!$submit)
	{
		// display form for adding new link
		$linkcat = get_catname($cat);
		echo html_header("Recommend a Link - $linkcat");
		echo ss_template('recommend_link.tmpl', array('%catid%' => $cat, '%catname%' => $linkcat));
		echo html_footer();
		exit;
	}
	// data has been submitted - check it and add to the database
	
	if ((!$link_name) || ($link_url == "http://") || (!$link_desc))
	{
		echo html_header("$sitename - Error");
		echo ss_template('recommend_link_error.tmpl');
		echo html_footer();
		exit;
	}
	if (!ereg("^(.+)@(.+)\\.(.+)$", $link_addemail))
		$link_addemail = "none";
	ereg_replace("[^0-9 A-Za-z]", "", $link_addname);
	if ($link_addname == "")
		$link_addname = "none";
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$time = time();
	$sql = "INSERT INTO sslinks SET
		link_name = '$link_name',
		link_cat = '$cat',
		link_url = '$link_url',
		link_desc = '$link_desc',
		link_numvotes = '0',
		link_totalrate = '0',
		link_dateadd = '$time',
		link_validated = 'no',
		link_addname = '$link_addname',
		link_addemail = '$link_addemail',
		link_recommended = 'no'";
	$result = mysql_query($sql);
	if (!$result)
	{
		custom_die("SQL result failed - link was not added");
	}
	else
	{
		echo html_header("Thank You");
		echo ss_template('recommend_link_thanks.tmpl');
		echo html_footer();
		exit;
	}
}

function delete_link($id)
{
	// after confirmation, deletes specified link
	
	global $db_host, $db_name, $db_user, $db_pass, $admin, $submit;
	
	if (!$admin)
	{
		header("Location: admin.php?action=logout");
		exit;
	}
	
	if (!ereg("^[0-9]+$", $id))
	{
		// function has been fed an invalid link id
		header("Location: links.php");
		exit;
	}
	if (!$submit)
	{
		// display form requesting confirmation
		// get link details
		
		$cnx = mysql_connect($db_host, $db_user, $db_pass)
			or custom_die("Unable to connect to database server.");
		mysql_select_db($db_name, $cnx)
			or custom_die("Unable to select database.");
		$result = mysql_query("SELECT * FROM sslinks WHERE link_id = '$id'");
		if (!$result)
			custom_die("SQL result failed");
		while ($row = mysql_fetch_array($result))
		{
			$lid = $row["link_id"];
			$name = $row["link_name"];
			$hits = $row["link_hits"];
			$desc = $row["link_desc"];
			$url = $row["link_url"];
			$totalrate = $row["link_totalrate"];
			$numvotes = $row["link_numvotes"];
			$dateadd = $row["link_dateadd"];
			if ($numvotes > 0)
			{
				$avgrate = sprintf ("%.2f", ($totalrate / $numvotes));
			}
			else
			{
				$avgrate = '0';
			}
		}
		echo html_header("Confirm Deletion");
		echo ss_template('confirm_linkdel.tmpl',array('%linkid%' => $lid, 
			'%name%' => $name,
			'%hits%' => $hits,
			'%votes%' => $numvotes,
			'%rating%' => $avgrate,
			'%description%' => $desc,
			'%url%' => $url
		));
		echo html_footer();
		exit;
	}
	
	// delete the link
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$result = mysql_query("DELETE FROM sslinks WHERE link_id = '$id'");
	if (!$result)
		custom_die("SQL result failed");
	// Rebuild number of links for categories
	build_numlinks();
	echo html_header("Link Deleted");
	echo ss_template('link_deleted.tmpl');
	echo html_footer();
	exit;
}

function reject_link($id)
{
	// This is an exact copy of the code for deletion (bad to use copy and paste I know)
	// only this code also e-mails the owner of the link to say it has been deleted.
	// The sole purpose of this function is for deleting links that are under validation.
	
	global $db_host, $db_name, $db_user, $db_pass, $admin, $submit, $sitename, $sitemail;
	
	if (!$admin)
	{
		header("Location: admin.php?action=logout");
		exit;
	}
	
	if (!ereg("^[0-9]+$", $id))
	{
		// function has been fed an invalid link id
		header("Location: links.php");
		exit;
	}
	if (!$submit)
	{
		// display form requesting confirmation
		// get link details
		
		$cnx = mysql_connect($db_host, $db_user, $db_pass)
			or custom_die("Unable to connect to database server.");
		mysql_select_db($db_name, $cnx)
			or custom_die("Unable to select database.");
		$result = mysql_query("SELECT * FROM sslinks WHERE link_id = '$id'");
		if (!$result)
			custom_die("SQL result failed");
		while ($row = mysql_fetch_array($result))
		{
			$lid = $row["link_id"];
			$name = $row["link_name"];
			$hits = $row["link_hits"];
			$desc = $row["link_desc"];
			$url = $row["link_url"];
			$totalrate = $row["link_totalrate"];
			$numvotes = $row["link_numvotes"];
			$dateadd = $row["link_dateadd"];
			$link_addemail = $row["link_addemail"];
			$link_addname = $row["link_addname"];
			if ($numvotes > 0)
			{
				$avgrate = sprintf ("%.2f", ($totalrate / $numvotes));
			}
			else
			{
				$avgrate = '0';
			}
		}
		echo html_header("Confirm Deletion");
		if ($link_addemail != "")
		{
			if ($link_addname == "")
			{
				$link_addname = "Anonymous";
			}
			$sub = "Submitted by <a href=\"mailto:$link_addemail\">$link_addname</a><p>\n";
		}
		else 
		{
			$sub = "";
		}
		echo ss_template('confirm_linkreject.tmpl', array('%linkid%' => $lid, 
			'%name%' => $name,
			'%hits%' => $hits,
			'%votes%' => $numvotes,
			'%rating%' => $avgrate,
			'%description%' => $desc,
			'%url%' => $url,
			'%sub%' => $sub
		));
		echo html_footer();
		exit;
	}
	
	// delete the link
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");

	$result = mysql_query("SELECT * FROM sslinks WHERE link_id = '$id'");
	if (!$result)
		custom_die("SQL result failed");
	while ($row = mysql_fetch_array($result))
	{
		$name = $row["link_name"];
		$url = $row["link_url"];
		$link_addemail = $row["link_addemail"];
		$link_addname = $row["link_addname"];
	}
	if ($link_addemail != "")
	{
		// send mail to punter telling them link has been rejected
		if (($link_addname == "") || ($link_addname == "none"))
			$link_addname = "Visitor";
		$mail = ss_template('email_rejection.tmpl', array('%link_addname%' => $link_addname, '%sitename%' => $sitename, '%linkname%' => $name, '%url%' => $url));
		$yay = @mail($link_addemail, "$sitename - Your link has been rejected", $mail, "From: $sitemail\n");
	}
	$result2 = mysql_query("DELETE FROM sslinks WHERE link_id = '$id'");
	if (!$result2)
		custom_die("SQL result failed");
	// Rebuild number of links for categories
	build_numlinks();
	echo html_header("Link Deleted");
	echo ss_template('link_rejected.tmpl');
	if ($yay)
		echo ss_template('email_rejection_sent.tmpl', array('%email%' => $link_addemail)); 
	echo html_footer();
	exit;
}


function edit_link($id)
{
	// displays form for editing link, or applys changes if form is submitted
	
	global $db_host, $db_name, $db_user, $db_pass, $link_name, $link_cat, $link_desc, $link_url, $link_recommended, $link_validated, $submit, $admin;

	if (!$admin)	
	{
		header("Location: links.php?action=logout");
		exit;
	}
	if (ereg("[^0-9]", $id))
	{
		// function has been fed an invalid link id
		header("Location: links.php");
		exit;
	}
	if (!$submit)
	{
		// show form for editing link
		$cnx = mysql_connect($db_host, $db_user, $db_pass)
			or custom_die("Unable to connect to database server.");
		mysql_select_db($db_name, $cnx)
			or custom_die("Unable to select database.");
		$result = mysql_query("SELECT * FROM sslinks WHERE link_id = '$id'");
		if (!$result)
			custom_die("SQL result failed");
		while ($row = mysql_fetch_array($result))
		{
			$lid = $row["link_id"];
			$name = $row["link_name"];
			$desc = $row["link_desc"];
			$url = $row["link_url"];
			$cat = $row["link_cat"];
			$rec = $row["link_recommended"];
			$val = $row["link_validated"];
		}
		echo html_header("Edit Link: $name");
		echo ss_template('edit_form_top.tmpl', array('%name%' => $name,
			'%linkid%' => $lid,
			'%url%' => $url,
			'%description%' => $desc,
			'%catid%' => $cat));
		// build select boxes for validated and recommended
		
		if ($rec == "yes")
		{
			$rec1 = " SELECTED";
			$rec2 = "";
		}
		else
		{
			$rec1 = "";
			$rec2 = " SELECTED";
		}
		echo ss_template('edit_form_recommended.tmpl', array('%rec1%' => $rec1, '%rec2%' => $rec2));
		if ($val == "yes")
		{
			$val1 = " SELECTED";
			$val2 = "";
		}
		else
		{
			$val1 = "";
			$val2 = " SELECTED";
		}
		echo ss_template('edit_form_validated.tmpl', array('%val1%' => $val1, '%val2%' => $val2));
		echo ss_template('edit_form_bottom.tmpl');
		echo html_footer();
		exit;
	}
	
	// Form has been submitted - update the database entry
	
	$sql = "UPDATE sslinks SET
		link_name = '$link_name',
		link_url = '$link_url',
		link_desc = '$link_desc',
		link_cat = '$link_cat',
		link_validated = '$link_validated',
		link_recommended = '$link_recommended'
		WHERE link_id = '$id'";
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$result = mysql_query($sql);
	if (!$result)
		custom_die("SQL result failed");
	build_numlinks();
	header("Location: links.php?cat=$link_cat");
	exit;
}

function validate_list()
{
	// lists all non-validated links
	global $db_host, $db_name, $db_user, $db_pass, $admin;
	
	if (!$admin)	
	{
		header("Location: links.php?action=logout");
		exit;
	}
	
	// Returns HTML for displaying list of links attached to category where id = $id
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$result = mysql_query("SELECT * FROM sslinks WHERE link_validated = 'no' ORDER BY link_name");
	if (!$result)
		custom_die("SQL result failed");
	$html = "";
	$num = mysql_num_rows($result);
	while ($row = mysql_fetch_array($result))
	{
		$lid = $row["link_id"];
		$name = $row["link_name"];
		$hits = $row["link_hits"];
		$desc = $row["link_desc"];
		$cat = $row["link_cat"];
		$totalrate = $row["link_totalrate"];
		$numvotes = $row["link_numvotes"];
		$dateadd = $row["link_dateadd"];
		$dateadd = date ("jS M Y", $dateadd);
		if ($numvotes > 0)
		{
			$avgrate = sprintf ("%.2f", ($totalrate / $numvotes));
		}
		else
		{
			$avgrate = '0';
		}
		
		$catname = get_catname($cat);

		if ($admin)
		{
			$admin_html = ss_template('linklist_item_admin.tmpl', array('%linkid%' => $lid));
		}
		else
		{
			$admin_html = "";
		}
		$links = ss_template('linklist_item.tmpl', array(
				'%linkid%' => $lid,
				'%rec%' => '',
				'%url%' => $url,
				'%name%' => $name,
				'%description%' => $desc,
				'%hits%' => $hits,
				'%votes%' => $numvotes,
				'%rating%' => $avgrate,
				'%date%' => $dateadd,
				'%admin%' => $admin_html,
				'%validate_list_html%' => ss_template('validate_list_html.tmpl', array('%catid%' => $cat, '%catname%' => $catname, '%dateadd%' => $dateadd, '%linkid%' => $lid))
			));
	}
	
	$html = ss_template('linklist.tmpl', array('%links%' => $links));

	echo html_header("Validate Links");
	if ($num == 0)
	{
		$html = ss_template('validate_no_links.tmpl');
	}
	echo ss_template('validate_links_page.tmpl', array('%html%' => $html));
	echo html_footer();
	exit;
}

function val_link($id)
{
	// validates (sets link_validated to 'yes') the specified link
	global $admin, $db_name, $db_host, $db_pass, $db_user;
	
	if (ereg("[^0-9]", $id))
	{
		// function has been fed an invalid link id
		header("Location: links.php");
		exit;
	}
	if (!$admin)
	{
		header("Location: links.php?action=logout");
		exit;
	}
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");

	$result = mysql_query("SELECT * FROM sslinks WHERE link_id = '$id'");
	if (!$result)
		custom_die("SQL result failed");
	while ($row = mysql_fetch_array($result))
	{
		$name = $row["link_name"];
		$url = $row["link_url"];
		$link_addemail = $row["link_addemail"];
		$link_addname = $row["link_addname"];
	}
	$time = time();
	$result2 = mysql_query("UPDATE sslinks SET link_validated = 'yes', link_dateadd = '$time' WHERE link_id = '$id'");
	if (!$result2)
		custom_die("SQL result failed");
	if ($link_addemail != "")
	{
		// send mail to punter telling them link has been rejected
		if (($link_addname == "") || ($link_addname == "none"))
			$link_addname = "Visitor";
		$mail = ss_template('email_link_accepted.tmpl', array('%emailname%' => $link_addname,
			'%sitename%' => $sitename,
			'%linkname%' => $name,
			'%linkurl%' => $url,
			'%email%' => $link_addemail));
		$yay = @mail($link_addemail, "$sitename - Your link has been validated", $mail, "From: $sitemail\n");
	}
	if ($yay)
	{
		echo html_header("E-Mail Sent");
		echo ss_template('email_link_accepted_sent.tmpl', array('%email%' => $link_addemail));
		echo html_footer();
		exit;
	}
	// Rebuild number of links for categories
	build_numlinks();
	
	header("Location: links.php?action=validated");
	exit;
}

function search_results($search)
{
	$searchtext = htmlentities($search);
	$search = stripslashes($search);
	$links_sql = "SELECT * FROM sslinks WHERE link_validated = 'yes' AND
		(link_name LIKE '%$search%' OR link_desc LIKE '%$search%')
		ORDER BY link_name";
	$cats_sql = "SELECT lcat_id, lcat_name, lcat_ranking FROM sslinkcats WHERE
		(lcat_name LIKE '%$search%' OR lcat_header LIKE '%$search%')
		ORDER BY lcat_ranking DESC, lcat_name";
	$links = links_sql($links_sql);
	
	echo html_header("Search Results");
	
	$cats = cats_sql($cats_sql);
	
	if (!$links)
	{
		$links = ss_template('search_no_links_found.tmpl');
	}
	echo ss_template('search_results.tmpl', array('%cats%' => $cats, '%links%' => $links, '%searchtext%' => $searchtext));

	echo html_footer();
	exit;
}

function show_searchpage()
{
	// displays search form
	echo html_header("Search");
	echo ss_template('search_form.tmpl');
	echo html_footer();
	exit;
}

function new_links($numdays)
{
	global $nol;
	// displays a list of links added in the last x days - limit is $nol links
	if (!$numdays)
	{
		$numdays = '5';
	}
	else
	{
		$numdays = "$numdays";
	}
	$time = time();
	$mintime = $time - ($numdays * 24 * 60 * 60);
	$links_sql = "SELECT * FROM sslinks WHERE link_dateadd > $mintime AND link_validated = 'yes' ORDER BY link_dateadd DESC LIMIT 0,$nol";
	$links = links_sql($links_sql);
	
	echo html_header("New Links");
	if (!$links)
	{
		$links = ss_template('no_new_links.tmpl');
	}
	echo ss_template('new_links_page.tmpl', array('%maxnumlinks%' => $nol, '%numdays%' => $numdays, '%links%' => $links));
	echo html_footer();
	exit;
}

function popular_links()
{
	global $nol;
	// displays 20 links with the most hits
	$links_sql = "SELECT * FROM sslinks WHERE link_validated = 'yes' AND link_hits > 0 ORDER BY link_hits DESC LIMIT 0,$nol";
	$links = links_sql($links_sql);
	
	echo html_header("Popular Links");
	
	if (!$links)
	{
		$links = ss_template('no_popular_links.tmpl');
	}
	echo ss_template('popular_links_page.tmpl', array('%links%' => $links, '%maxnumlinks%' => $nol));
	
	echo html_footer();
	exit;
}

function top_rated()
{
	// displays 20 links with the highest ratings (must have min. $minvotes votes)
	global $minvotes, $nol;
	$links_sql = "SELECT *, (link_totalrate/link_numvotes) AS link_rating FROM sslinks WHERE link_validated = 'yes' AND link_numvotes > $minvotes ORDER BY link_rating DESC LIMIT 0,$nol";
	$links = links_sql($links_sql);
	
	echo html_header("Top Rated Links");
	if (!$links)
	{
		$links = ss_template('no_rated_links.tmpl', array('%minvotes%' => $minvotes));
	}
	echo ss_template('rated_links_page.tmpl', array('%links%' => $links, '%maxnumvotes%' => $nol, '%minvotes%' => $minvotes));
	echo html_footer();
	exit;
}

function edit_cat($id)
{
	// edit specified category
	global $db_host, $db_name, $db_user, $db_pass, $lcat_name, $lcat_cat, $lcat_header, $lcat_rank, $submit, $admin;
	if (!$admin)	
	{
		header("Location: links.php?action=logout");
		exit;
	}
	if (ereg("[^0-9]", $id))
	{
		// function has been fed an invalid link id
		header("Location: links.php");
		exit;
	}
	if (!$submit)
	{
		// show form for editing link
		$cnx = mysql_connect($db_host, $db_user, $db_pass)
			or custom_die("Unable to connect to database server.");
		mysql_select_db($db_name, $cnx)
			or custom_die("Unable to select database.");
		$result = mysql_query("SELECT * FROM sslinkcats WHERE lcat_id = '$id'");
		if (!$result)
			custom_die("SQL result failed");
		while ($row = mysql_fetch_array($result))
		{
			$cid = $row["lcat_id"];
			$name = $row["lcat_name"];
			$cat = $row["lcat_cat"];
			$header = $row["lcat_header"];
			$ranking = $row["lcat_ranking"];
		}
		echo html_header("Edit Category: $name");
		echo ss_template('edit_category_form.tmpl', array ('%catname%' => $name,
			'%catid%' => $cid,
			'%ranking%' => $ranking,
			'%header%' => $header,
			'%nestid%' => $cat));
		echo html_footer();
		exit;
	}
	
	// Form has been submitted - update the database entry
	
	// First check that nested ID isn't the same as category ID
	// this avoids horrible recursive loops.
	if ($lcat_cat == $id)
		custom_die("Category ID the same as Parent ID - don't do that!");
	$sql = "UPDATE sslinkcats SET
		lcat_name = '$lcat_name',
		lcat_ranking = '$lcat_rank',
		lcat_header = '$lcat_header',
		lcat_cat = '$lcat_cat'
		WHERE lcat_id = '$id'";
	$cnx = mysql_connect($db_host, $db_user, $db_pass)
		or custom_die("Unable to connect to database server.");
	mysql_select_db($db_name, $cnx)
		or custom_die("Unable to select database.");
	$result = mysql_query($sql);
	if (!$result)
		custom_die("SQL result failed. \$sql was $sql");
	// Rebuild number of links for categories
	build_numlinks();
	header("Location: links.php?cat=$link_cat");
	exit;
}

function custom_die($error_msg)
{
	// Nice tidy way of dying
	echo html_header("Unexpected Error");
	echo ss_template('error_die.tmpl', array('%error%' => $error_msg));
	echo html_footer();
	exit;
}

function build_numlinks()
{
	// This function goes through every category in the database and sets the
	// lcat_numlinks database field for it - storing the number of links held
	// in that category AND all sub categories.  The function should be called
	// whenever an alteration is made to the number of links in the database.
	
	// The good news: $numlinks_array already has the number of links in each
	// category (without the recursion).
	
	global $numlinks, $numlinks_cache_tree, $db_host, $db_user, $db_pass, $db_name, $admin;
	
	// Check if user is an admin - if not send them packing
	
	if (!$admin)	
	{
		header("Location: links.php?action=logout");
		exit;
	}
	
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
?>
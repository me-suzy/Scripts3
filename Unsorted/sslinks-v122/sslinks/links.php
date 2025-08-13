<?php
/***********************************************************
*
* ssLinks v1.1 - a PHP / mySQL links management system
* (c) Simon Willison 2001
* For more information, visit www.tfc-central.co.uk/sslinks/
*
***********************************************************/

// See global.inc.php for changes since version 1.0.

include("global.inc.php"); //Change this if global.inc.php is in a different directory

// You should not need to change anything below this line.

$admin = is_admin();
$return = numlinks_array(); // Build array of number of links in each category
$numlinks = $return[0];
$numlinkstree = $return[1];

if ((!$cat) && (!$go) && (!$action))
	$cat = 0;

if (isset($go))
{
	jump_to($go);
}

if ($action == "login")
{
	if ($username)
		login($username, $password);
}

if ($action == "loginform")
{
	echo html_header("$sitename - Links Login");
	echo ss_template('login_form.tmpl');
	echo html_footer();
	exit;
}

if ($action == "logout")
{
	logout();
}

if ($action == "rate")
{
	do_rating($id, $score);
}

if ($action == "build")
{
	build_numlinks();
	echo html_header('Build Complete');
	echo ss_template('links_built.tmpl');
	echo html_footer();
	exit;
}

if ($action == "addlink")
{
	add_link($link_name, $link_url, $link_desc, $link_cat, $link_recommended);
}

if ($action == "addcat")
{
	add_cat($lcat_name, $lcat_header, $lcat_cat, $lcat_ranking);
}

if ($action == "delcat")
{
	del_cat($id);
}

if ($action == "reclink")
{
	rec_link($cat);
}

if ($action == "dellink")
{
	delete_link($id);
}

if ($action == "editlink")
{
	edit_link($id);
}
if ($action == "validate")
{
	validate_list();
}

if ($action == "vallink")
{
	val_link($id);
}

if ($action == "search")
{
	show_searchpage();
}
if ($action == "reject_link")
{
	reject_link($id);
}
if ($action == "newlinks")
{
	new_links($days);
}
if ($action == "popular")
{
	popular_links();
}
if ($action == "rated")
{
	top_rated();
}
if ($action == "editcat")
{
	edit_cat($id);
	exit;
}

if ($search)
{
	search_results($search);
}

// if all else fails then show normal page

if (!ereg("^[0-9]+$", $cat))
	$cat = 0;
$breadcrumbshtml = do_breadcrumbs($cat);
$html = cats_table($cat);
echo html_header("$sitename - Links");
if ($cat == 0)
	$cat = '0';
echo ss_template('category_header.tmpl', array('%breadcrumbs%' => $breadcrumbshtml, '%catid%' => $cat));
echo get_catheader($cat);
echo cats_table($cat);
$linkshtml = show_links($cat);
if ($linkshtml)
{
	echo $linkshtml;
} 
else 
{
	echo ss_template('category_nolinks.tmpl');
}
if ($admin)
{
	echo admin_options($cat);
}
echo html_footer();
exit;

?>
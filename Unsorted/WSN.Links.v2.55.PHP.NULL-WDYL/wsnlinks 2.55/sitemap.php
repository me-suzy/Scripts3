<?php
require 'start.php';

$template = new template("sitemap.tpl");
$mapline = templateextract($template->text, '<!-- BEGIN MAP LINE -->', '<!-- END MAP LINE -->');
$orig = $mapline;

$cattypelist = explode(',', $settings->cattypes);
$num = sizeof($cattypelist);
for ($c=0; $c<$num; $c++)
{
 $cattype = $cattypelist[$c];
 $var = $cattype .'line';
 $$var = templateextract($mapline, '<!-- BEGIN '. strtoupper($cattype) .' -->', '<!-- END '. strtoupper($cattype) .' -->');
}

$cats = $db->select($settings->categoryfields, 'categoriestable', 'hide=0 AND validated=1', '', '');
$n = $db->numrows($cats);
for ($y=0; $y<$n; $y++)
{
 $row = $db->row($cats);
 $id = $row[0];
 $cat[$id] = new category('row', $row);
}

// somehow make children be parent's category type so organization will be correct
$n = sizeof($cat);
for ($y=0; $y<$n; $y++)
{
 $parents = $cat[$y]->parentids;
 if ($parents == '' && $cat[$y]->parent > 0) $parents = $cat[$y]->parent;
 if ($parents != '')
 {  
  $pars = explode('|||', $parents);
  if (is_array($pars)) $top = $pars[0]; else $top = $parents;
  $cat[$y]->type = $cat[$top]->type;
 }
}



$mapstuff = explode('{ENDLINE}', decodeit($settings->sitemap));
$num = sizeof($mapstuff);
for ($x=0; $x<$num; $x++)
{
 $maprow = explode('[,]', $mapstuff[$x]);
 $indent = $maprow[0];
 $id = $maprow[1];
 $cvar = $cat[$id]->type . 'line';
 $tmp = $cat[$id]->type . 'temp';
 $$tmp .= str_replace('{INDENT}', $indent, $$cvar);
 $$tmp = categoryreplacements($$tmp, $cat[$id]);
}


$numtypes = sizeof($cattypelist);
for ($c=0; $c<$numtypes; $c++)
{
 $cattype = $cattypelist[$c];
 $bvar = $cattype .'temp';
 $linet = $cattype .'line';
 $mapline = str_replace($$linet, $$bvar, $mapline);
}

$template->replace($orig, $mapline);
$area = $language->title_divider . $language->title_sitemap;
require 'end.php';
?>
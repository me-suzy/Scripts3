<?php
$blocks_modules[category] = array(
	'title' => 'Languages',
	'func_display' => 'blocks_category_block',
	'text_type' => 'Category',
	'text_type_long' => 'Categories Menu',
	'text_content' => 'Categories Menu',
	'support_nukecode' => false,
	'allow_create' => false,
	'allow_delete' => false,
	'form_url' => false,
	'form_content' => false,
	'form_refresh' => false,
	'show_preview' => true
);
function blocks_category_block($row) {
global $DB_site,$bburl,$prefix,$userid,$block_sidetemplate,$hastopics;
if ($hastopics){
echo "<FORM name=\"guideform\">";
$block_content = "<SELECT name=\"guidelinks\""; 
$block_content .= "onChange=\"window.location=document.guideform.guidelinks.options[document.guideform.guidelinks.selectedIndex].value\">";
$block_content .= "<OPTION SELECTED value=\"javascript:void(0)\">--Choose--";
 $topiclist = $DB_site->query("select topicid, topictext from $prefix"._topics." order by topictext");
     while(list($topicid, $topics) = mysql_fetch_row($topiclist)) {
     if ($topicid==$topic) { $sel = "selected=\"selected\" "; }
           $topicid=$topicid;
$block_content .= "<OPTION value=\"$bburl/search.php?s=$session[sessionhash]&action=findtopic&topic=$topicid&userid=$userid\">$topics";
   $sel = "";
}
$block_content .= "</SELECT>";
}else{
  $block_content ="Topics Not Installed";
}
$block_title = $row[title];
eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");
echo "</FORM>";
}
?>
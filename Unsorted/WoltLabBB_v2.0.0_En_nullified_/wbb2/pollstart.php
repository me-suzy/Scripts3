<?php
$filename="pollstart.php";
require ("./global.php");

if($wbbuserdata['canpostpoll']==0 || $wbbuserdata['canstarttopic']==0) {
 eval("\$tpl->output(\"".$tpl->get("window_close")."\");");
 exit();
}

if(isset($_POST['send'])) {
 $question=htmlspecialchars(trim($_POST['question']));
 $db->query("INSERT INTO bb".$n."_polls (pollid,question,starttime,choicecount,timeout) VALUES (NULL,'".addslashes($question)."','".time()."','".intval($_POST['choicecount'])."','".intval($_POST['timeout'])."')");
 $pollid=$db->insert_id();
 
 $options=explode("\n",$_POST['polloptions']);
 $count=1;
 for($i=0;$i<count($options);$i++) {
  $options[$i]=trim($options[$i]);
  if(!$options[$i]) continue;
  $db->query("INSERT INTO bb".$n."_polloptions (polloptionid,pollid,polloption,showorder) VALUES (NULL,'$pollid','".addslashes($options[$i])."','$count')");
  $count++;
 }
 
 eval("\$tpl->output(\"".$tpl->get("pollstart_give_parent")."\");");
 exit();
}
else {
 $choicecount=1;
 $timeout=0;
}

eval("\$tpl->output(\"".$tpl->get("pollstart")."\");");
?>
<?php


require("global.php");

settype($modid,"integer");

if ($modid) {
  $sqlwhere = "id = $modid";
} else {
  $sqlwhere = "name = '$modname'";
}

if ($data_arr = query_first("SELECT id,name,enable,description,options FROM news_module WHERE $sqlwhere")) {
  if ($data_arr[enable]) {
    $modid = $data_arr[id];
    $modname = $data_arr[name];
    $moddescription = $data_arr[description];
    $modoptions = getmodoptions($modid,"",$data_arr[options]);
    include("modules/$data_arr[name]/index.php");
  } else {
    standarderror("module_disabled");
  }
} else {
  standarderror("invalid_id");
}

/*======================================================================*\
|| ####################################################################
|| # File: modules.php
|| ####################################################################
\*======================================================================*/

?>
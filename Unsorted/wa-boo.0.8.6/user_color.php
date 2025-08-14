<?php

    include ("includes/global_vars.php");
    include ("includes/fotools.php");
    
    session_start();
    
    // === AntiHack check ===
    if (!session_is_registered("s_user")) {
        msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
    }
    
    $s_previous_page = "user_color.php";
    
    /*if ($menubgcolor == "" && $buttonbgcolor == "" && $stdfontcolor == "") {
        echo "rien";
    } else*/
    
    if ($menubgcolor != "") {
        $itemcolor = "menubgcolor";
        $selection = $menubgcolor;
    }

    if ($buttonbgcolor != "") {
        $itemcolor = "buttonbgcolor";
        $selection = $buttonbgcolor;
        $s_temp_color["button"] = "#" . $buttonbgcolor;
    }
    
    if ($stdfontcolor != "") {
        $itemcolor = "stdfontcolor";
        $selection = $stdfontcolor;
        $s_temp_color["stdfontcolor"] = "#" . $stdfontcolor;
    }
    
    if ($listfontcolor != "") {
        $itemcolor = "listfontcolor";
        $selection = $listfontcolor;
        $s_temp_color["listfontcolor"] = "#" . $listfontcolor;
    }     
    $color_array = buildColorTable();
    include ("templates/user_color.tmpl");
    
    
    
    
    
?>



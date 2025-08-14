<?php

	/*
        import3.php - contacts importing
        by Roulaizze <aaaxl@nocive.com> 
  */

    include ("includes/global_vars.php");
	  include ("classes/database_class.php");
    include ("classes/user_class.php");
    include ("classes/domain_class.php");
    include ("classes/contact_class.php");
    include ("includes/fotools.php");
    
    global $s_combo_fields;
    global $G_default_nb_import_display;
    
	  session_start();
	  
	
  	if (!session_is_registered("s_user") || !session_is_registered("s_array_import")) {
  		msgBox ("1", "", $s_boxLab[7] /* Security !  <br><br>Session error ! */,
            $s_boxLab[4] /* Security ! */, "error",
            $s_boxLab[6] /* OK */, "index.php");
            die;
      }	
	
	function displayFieldsCombo($columnPosition, $value, $noImportLabel) {
	    global $s_combo_fields;
	    global $lab;
	    
		$combobox = "";
		$combobox = "<select name=\"column[" . $columnPosition . "]\">";
		$is_selected = ($value == -1 || !isset($value) ? " selected" : "" ) ;
		$combobox .= "<option value=\"-1\"" . $is_selected . ">". $noImportLabel . "</option>";
		
		for ($i = 0 ; $i < count($s_combo_fields) ; $i++) {
			$ID = getFieldNumber($i);
			$label = getFieldLabel($i);
    		$is_selected = ($ID == $value && $value != "" ? " selected" : "" ); 
    		// strange bug : '$ID == $value' condition (that should be OK to do correct test) is not working alone (with $ID == 0 and $value == '', 
    		// it was returning selected) -> that's why I add the '$value != ""' contition. 
    		// Now it's workin but it's quite dirty :(
    		
    		// echo $is_selected . "<<<br>";
			$combobox .= "<option value=\"" . $ID . "\"" . $is_selected . ">" . $label . "</option>";
		}
		$combobox .= "</select>";
		return $combobox;
	}
	
    function jivaro($truncateStatus, $inputstr, $size) {
        //echo "params de fct jivaro : " . $truncateStatus . ", " . $inputstr . ", size : " . $size . "<br>";
        if ($truncateStatus == 1) { // if truncattion is required
            if (is_numeric($size) && $size >= 0) {
                return substr($inputstr, 0 , $size); // return truncated string for display
            }
        }
        //echo "RIEN A JIVARER";
        return $inputstr;
    }
    
    function jivaroFieldColor($truncateStatus, $row, $inputstr, $size) {
        global $i_firstline;
        global $G_import_truncated_cell_color;
        //echo "FIRSTLINE ? " . $i_firstline; 
        //"params de fct jivaro : " . $truncateStatus . ", " . $inputstr . ", size : " . $size . "<br>";
        if ($truncateStatus == 1 && $row != 0) { // if truncattion is required and not on firstline
            if (is_numeric($size) && $size >= 0) {
                if (strlen(substr($inputstr, 0 , $size)) <  strlen($inputstr)) {
                    return "$G_import_truncated_cell_color"; // return other color for display
                }
            }
        }
        return "";
    }    	
	
	//display the right number of row, according to ask number, default value and imported array size
	if ($nb_import_displ == "") {
	    if ($s_import_params["nbrows"] > $G_default_nb_import_display) { // there is more rows than default
	        $nb_import_displ = $G_default_nb_import_display;
	    } else {  
	        $nb_import_displ = $s_import_params["nbrows"];
	    }
	} else { // user has typed value in input
	    if ($s_import_params["nbrows"] < $nb_import_displ) { // there is more row than default
	        $nb_import_displ = $s_import_params["nbrows"];
	    }
	} 
	
	$user_cat = $s_user->getUsercategories();
  $lab = loadTemplateLabels($s_lang, "import3");
  include ("templates/import3.tmpl");
?>


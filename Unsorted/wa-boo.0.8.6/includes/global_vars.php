<?php
    
    $G_version                         = "v 0.8.5";
    $G_host_URL                        = "http://your_url";                      // url of service
    $G_email_function                  = "mail";                                 // value : "email" or "mail"
    $G_server_OS                       = "win";                                  // value : "win" for windows, "nix" for unixes
    $G_time_to_sleep                   = 0.001;                                  // in seconds, time to make sleep php engine for large item list display. Only valid for $G_server_OS = "win". Bad answer to the proble, but it works with 0.001 value ;)
    $G_email_header                    = "== wa-boo service | " . $G_host_URL . " ==\n\n";
    $G_email_footer                    = "\n\n----\n  Get a powerfull Web based wa-boo on " .$G_host_URL;
    $G_robot                           = "wa-boo_robot@your_domain.com";
    $G_nb_of_DB_fields                 = 17;                                     // NOT TO BE MODIFIED
    $s_combo_fields = array();                                                   // array, will be loaded in fotool.php in function loadLangParameters() from langage file
    $G_import_truncated_cell_color     = "#FFCC00";                              // color of cells which are truncated in import array
    $G_import_disabled_firstline_color = "#333399";                              // color of firstline in import array, if disabled
    $G_control_duplicates              = "YES";                                  // if set to "NO", don't bother users with duplicates. If set to "YES", will try to catch duplicates if context allows
    $G_list_bg_color                   = "#EEEEEE";                              // bg color for some lists (i.e. : export)
    $G_user_menu_color                 = "#7B8590";                              // menubar color for user pages. default is "#7B8590"
    $G_user_button_color               = "#BAC2C7";
    $G_user_stdfont_color              = "#3E4860";
    $G_user_stdfont_size               = "11";
    $G_user_listfont_color             = "#883322";
    $G_user_listfont_size              = "11";    
    $G_admin_menu_color                = "#CC2233";                              // menubar color for admin pages. default is "#C14F02"
    $G_admin_button_color              = "#FFCC99";
    $G_default_nb_import_display       = 60;
    $G_default_max_contact             = 200;                                    // For Godlike user management form.
    // default maximum number of contacts for users created by Admin in to be set in "classes/user_class.php" actually, value is 150 
    $G_languages                       = array (0 => array ("short" => "en", "full" => "English"), 
                                                1 => array ("short" => "fr", "full" => "Français")
                                                );
    $G_default_langage                 = "en"; // can be change to "en". In this case, can be changed in "classes/user_class.php" to be perfect (but works if not:-).
    // parameters for database connection must be changed in database_class
    

?>
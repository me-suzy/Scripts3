a:hover {
    color: <? echo $s_user_context["color"]["menu"]; ?>;
    text-decoration: none
}

a {
    color: <? echo $s_user_context["color"]["listfontcolor"]; ?>;
    text-decoration: underline
}

INPUT {
    FONT-FAMILY: Verdana,Helvetica;
    FONT-SIZE: <? echo $s_user_context["color"]["stdfontsize"]; ?>px
}

TEXTAREA {
    FONT-FAMILY: Verdana,Helvetica;
    FONT-SIZE: <? echo $s_user_context["color"]["stdfontsize"]; ?>px
}

SELECT {
    FONT-FAMILY: Arial,Helvetica;
    FONT-SIZE: <? echo $s_user_context["color"]["stdfontsize"]; ?>px 
}

.userbutton {
    background-color: <? echo $s_user_context["color"]["button"]; ?><? //echo $G_user_button_color; ?>;
    Verdana, Arial, Helvetica, sans-serif;  
    font-size: <? echo $s_user_context["color"]["stdfontsize"]; ?>px; 
    color: <? echo buttonFontColor($s_user_context["color"]["button"]); ?>
}

.tempuserbutton {
    background-color: <? echo $s_temp_color["button"]; ?><? //echo $G_user_button_color; ?>;
    Verdana, Arial, Helvetica, sans-serif;  
    font-size: <? echo $s_user_context["color"]["stdfontsize"]; ?>px; 
    color: <? echo buttonFontColor($s_temp_color["button"]); ?>
}

.stdfont {  
    font-family: Arial, Helvetica, sans-serif; 
    font-size: <? echo $s_user_context["color"]["stdfontsize"]; ?>px; 
    color: <? echo $s_user_context["color"]["stdfontcolor"]; ?>
}

.tempstdfont {  
    font-family: Arial, Helvetica, sans-serif; 
    font-size: <? echo $s_user_context["color"]["stdfontsize"]; ?>px; 
    color: <? echo $s_temp_color["stdfontcolor"]; ?>
}

.templistfont {  
    font-family: Arial, Helvetica, sans-serif; 
    font-size: <? echo $s_user_context["color"]["listfontsize"]; ?>px; 
    color: <? echo $s_temp_color["listfontcolor"]; ?>
}

.boldstdfont {  
    font-family: Arial,Helvetica, sans-serif; 
    font-size: <? echo $s_user_context["color"]["stdfontsize"]; ?>px;
    font-weight: bold; 
    color: <? echo $s_user_context["color"]["stdfontcolor"]; ?>
}

.contactlistfont {  
    font-family: Arial, Helvetica, sans-serif;
    font-size: <? echo $s_user_context["color"]["listfontsize"]; ?>px;     
    color: <? echo $s_user_context["color"]["listfontcolor"]; ?>
}

.boldcontactlistfont {  
    font-family: Arial, Helvetica, sans-serif;
    font-size: <? echo $s_user_context["color"]["listfontsize"]; ?>px;
    font-weight: bold; 
    color: <? echo $s_user_context["color"]["listfontcolor"]; ?>
}

.altcontactlistfont {  
    font-family: Arial, Helvetica, sans-serif;
    font-size: <? echo $s_user_context["color"]["listfontsize"]; ?>px;     
    color: #222222
}

.versionfont {  
    font-family: Verdana, Arial, Helvetica, sans-serif; 
    font-size: 9px; 
    color: #404280
}

.stdtitle {  
    font-family: Verdana, Arial, Helvetica, sans-serif; 
    font-size: <? echo round($s_user_context["color"]["stdfontsize"] * 1.2, 0); ?>px; 
    font-weight: bold; 
    color: <? echo buttonFontColor($s_user_context["color"]["menu"]); ?>
}

.stdsubtitle {  
    font-family: Arial, Helvetica, sans-serif; 
    font-size: <? echo round($s_user_context["color"]["stdfontsize"] * 1.1, 0); ?>px;
    font-style: italic;
    color: #333333
}

.boldstdsubtitle {  
    font-family: Arial, Helvetica, sans-serif; 
    font-size: <? echo round($s_user_context["color"]["stdfontsize"] * 1.1, 0); ?>px;
    font-weight: bold; 
    color: #333333
}

.bolddarkredfont {  
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: <? echo $s_user_context["color"]["stdfontsize"]; ?>px;
    font-weight: bold;
    color: #660000
}

.exportdisplay {  
    font-family: Courier New, Courier, mono; 
    font-size: <? echo $s_user_context["color"]["stdfontsize"]; ?>px;
    font-weight: bold; 
    color: #660000
}

.hlptitle {  
    font-family: Georgia,Garamond,Times,serif; 
    font-size: <? echo round($s_user_context["color"]["stdfontsize"] * 1.3, 0); ?>px; 
    font-weight: bold; 
    color: <? echo $s_user_context["color"]["stdfontcolor"]; ?>
}

.hlpsubtitle {  
    font-family: Georgia,Garamond,Times,serif; 
    font-size: <? echo round($s_user_context["color"]["stdfontsize"] * 1.1, 0); ?>px;
    font-weight: bold; 
    color: <? echo $s_user_context["color"]["stdfontcolor"]; ?>
}

.hlpfont {  
    font-family: Georgia,Garamond,Times,serif; 
    font-size: <? echo $s_user_context["color"]["stdfontsize"]; ?>px; 
    color: <? echo $s_user_context["color"]["stdfontcolor"]; ?>
}

.hlpmenu {  
    font-family: Georgia,Garamond,Times,serif; 
    font-size: <? echo round($s_user_context["color"]["stdfontsize"] * 1.2, 0); ?>px; 
    font-weight: bold; 
    color: <? echo buttonFontColor($s_user_context["color"]["menu"]); ?>
}
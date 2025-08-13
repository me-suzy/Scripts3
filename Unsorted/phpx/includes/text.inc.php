<?php
#$Id: text.inc.php,v 1.10 2003/10/20 14:59:48 ryan Exp $
class textFunctions {

    function convertText($text, $flag='', $eval=''){
        //! Converts text to text or to HTML
        //! flag is set to 1 for incoming
        if ($flag == 2){
            $text = stripslashes($text);
        }

        else if ($flag == 1){
            if ($eval == 1){
                if ($this->html != 1){ $text = htmlspecialchars($text); }
                if ($this->images != 1){
                    $text = str_replace("[img]", '', $text);
                    $text = str_replace("[/img]", '', $text);
                }

                if ($this->xcode == 1){
                    $text = str_replace("[/quote]\r\n", "[/quote]", $text);
                    if (substr_count($text, "[") > 0 && substr_count($text, "]") > 0){

                       $trans = $this->getTranslation("xcode", $flag);
                       $text = strtr($text, $trans);

                       while(substr_count($text, "[size=") > 0){
                            $pos = strpos($text,"[size=") + 6;
                            $size = substr($text, $pos, strpos(substr($text, $pos), "]"));
                            $replace = "[size=$size]";
                            $text = str_replace($replace, "[size=%:%]", $text);
                            $text = strtr($text, $trans);
                            $text = str_replace("%:%", $size, $text);
                        }
                        while(substr_count($text, "[color=") > 0){
                            $pos = strpos($text,"[color=") + 7;
                            $size = substr($text, $pos, strpos(substr($text, $pos), "]"));
                            $replace = "[color=$size]";
                            $text = str_replace($replace, "[color=%:%]", $text);
                            $text = strtr($text, $trans);
                            $text = str_replace("%:%", $size, $text);
                        }
                        while(substr_count($text, "[url=") > 0){
                            $pos = strpos($text,"[url=") + 5;
                            $size = substr($text, $pos, strpos(substr($text, $pos), "]"));
                            $replace = "[url=$size]";
                            $text = str_replace($replace, "[url=%:%]", $text);
                            $text = strtr($text, $trans);
                            $text = str_replace("%:%", $size, $text);
                        }
                        while(substr_count($text, "[font=") > 0){
                            $pos = strpos($text,"[font=") + 6;
                            $size = substr($text, $pos, strpos(substr($text, $pos), "]"));
                            $replace = "[font=$size]";
                            $text = str_replace($replace, "[font=%:%]", $text);
                            $text = strtr($text, $trans);
                            $text = str_replace("%:%", $size, $text);
                        }
                    }
                }
                if ($this->smiles == 1){
                    $trans = $this->getTranslation("smiles", $flag);
                    $text = strtr($text, $trans);
                }
                $ret = " " . $text;
                $ret = preg_replace("#([\n ])([a-z]+?)://([^, \n\r]+)#i", "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", $ret);
                $ret = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^, \n\r]*)?)#i", "\\1<a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $ret);
                $ret = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([^, \n\r]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
                $text = substr($ret, 1);

            }
            if ($this->words == 1){
                $trans = $this->getTranslation("words", $flag);
                $text = " " . $text . " ";
                $text = strtr($text, $trans);
                $text = trim(rtrim($text));

            }
            $text = str_replace("(", "&#40;", $text);
            $text = str_replace("'", "&#39;", $text);
            $text = str_replace(")", "&#41;", $text);
            $text = str_replace("\r\n", "<br>", $text);
            $text = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $text);
            $text = addslashes($text);

        }
        else if ($flag == 0 || $flag == ''){
            if ($eval == 1){
                if (substr_count($text, "<span class=small><i><br><br>[") > 0){
                    $pos = strpos($text, "<span class=small><i><br><br>[");
                    $text = substr($text, 0, $pos);
                }

                if ($this->html == 1){
                    $trans = get_html_translation_table(HTML_ENTITIES);
                    $trans = array_flip($trans);
                    $text = strtr($text, $trans);
                }

                if ($this->xcode == 1){
                    $trans = $this->getTranslation("xcode", $flag);
                    $text = " " . $text;
                    $text = strtr($text, $trans);
                    if (strpos($text, "<") && strpos($text, ">")){
                         $check = "<span style=\"font-size:";
                         while(substr_count($text, $check) > 0){
                            $len = strlen($check);
                            $pos = strpos($text,$check) + $len;
                            $size = substr($text, $pos, strpos(substr($text, $pos), ">"));
                            $size = str_replace("px\"", '', $size);
                            $replace = $check . $size . "px\">";
                            $text = str_replace($replace, "<span style=\"font-size:%:%px\">", $text);
                            $text = strtr($text, $trans);
                            $text = str_replace("%:%", $size, $text);
                         }
                         $check = "<span style=\"color:";
                         while(substr_count($text, $check) > 0){
                            $len = strlen($check);
                            $pos = strpos($text,$check) + $len;
                            $size = substr($text, $pos, strpos(substr($text, $pos), ">"));
                            $size = str_replace("\"", '', $size);
                            $replace = $check . $size . "\">";
                            $text = str_replace($replace, "<span style=\"color:%:%\">", $text);
                            $text = strtr($text, $trans);
                            $text = str_replace("%:%", $size, $text);
                         }
                         $check = "<a href=\"";
                         while(substr_count($text, $check) > 0){
                            $len = strlen($check);
                            $pos = strpos($text,$check) + $len;
                            $size = substr($text, $pos, strpos(substr($text, $pos), ">"));
                            $size = str_replace("\"", '', $size);
                            $replace = $check . $size . "\">";
                            $text = str_replace($replace, "<a href=\"%:%\">", $text);
                            $text = strtr($text, $trans);
                            $text = str_replace("%:%", $size, $text);
                         }
                         $check = "<span style=\"font-family:";
                         while(substr_count($text, $check) > 0){
                            $len = strlen($check);
                            $pos = strpos($text,$check) + $len;
                            $size = substr($text, $pos, strpos(substr($text, $pos), ">"));
                            $size = str_replace("\"", '', $size);
                            $replace = $check . $size . "\">";
                            $text = str_replace($replace, "<span style=\"font-family:%:%\">", $text);
                            $text = strtr($text, $trans);
                            $text = str_replace("%:%", $size, $text);
                        }
                    }
                }
                if ($this->smiles == 1){
                    $trans = $this->getTranslation("smiles", $flag);
                    $text = strtr($text, $trans);
                }
            }

            $text = str_replace("<br>", "\r\n", $text);
            $text = str_replace("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", "\t", $text);
            $text = stripslashes($text);
        }

        return $text;
    }

    function getTranslation($type, $flag){

        switch($type){
            case xcode:
                $result = mysql_query("select xcode_find, xcode_replace from forums_xcode");
                break;
            case words:
                $result = mysql_query("select word, replacement from forums_words");
                break;
            case smiles:
                $result = mysql_query("select code, image from forums_smiles");
                break;
        }
        $trans = array();
        while(list($find, $replace) = mysql_fetch_row($result)){
            if ($type == "xcode"){
                if ($flag == 1){ $trans[$find] = $replace; }
                else { $trans[$replace] = $find; }
            }
            else if ($type == "smiles"){
                if ($flag == 1){ $trans[$find] = "<img border=0 alt=Smilie src=images/smiles/" . $replace . ">"; }
                else {

                    $replace = "<img border=0 alt=Smilie src=images/smiles/" . $replace . ">";
                    $trans[$replace] = $find;
                }
            }
            else {
                if ($flag == 1){
                    $find = " " . $find . " ";
                    $trans[$find] = " " . $replace . " "; }
                else {
                    $replace = " " . $replace . " ";
                    $trans[$replace] = " " . $find . " ";
                }
            }
        }
        return $trans;
    }

    function createFontForm(){

        $size = array(small,large,huge);
        $color = array(red,black,white,blue,yellow,green,orange,gray);
        $face = array(arial,times,verdana,wingdings,courier);

        sort($face);
        sort($color);

        $form = "<b>Font </b><select name=fontSize><option value='' selected>Size</option>";
        foreach($size as $s){
            $form .= "<option value='$s'>$s</option>";
        }
        $form .= "</select> <select name=fontColor><option value='' selected>Color</option>";
        foreach($color as $s){
            $form .= "<option value=$s>$s</option>";
        }
        $form .= "</select> <select name=fontFace><option value='' selected>Font</option>";
        foreach($face as $s){
            $form .= "<option value=$s>$s</option>";
        }
        $form .= "</select>";
        $form .= " <input type=button value='Change Font' onClick='changeFont()'>";
        return $form;
    }
}

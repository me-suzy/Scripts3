<?php
#$Id: images.inc.php,v 1.9 2003/09/09 15:02:14 ryan Exp $

class imageModule{

    function imageModule($userinfo){
        $this->action = $_GET['action'];
        $this->image_id = $_GET['image_id'];
        $this->userinfo = $userinfo;
        $this->insert = $_GET['insert'];
        $this->core = new coreFunctions();
        if ($this->image_id == ''){ $this->image_id = $_POST['image_id']; }

        switch($this->action){
            case create:
                $this->createImage();
                break;
            case delete:
                $this->deleteImage();
                break;
            case view:
                $this->viewImage();
                break;
            default:
                $this->listImages();
        }
        $this->stroke();


    }

    function stroke(){
        new adminPage($this->body, $this->insert);
    }


    function createImage(){
        if ($_POST['confirm'] == 1){
            global $image_types;
            $ext = array_flip($image_types);

            for($i=1; $i<6; $i++){
                $fileInput = "file" . $i;
                $check = $_FILES[$fileInput][tmp_name];
                if ($check != ''){
                    $file_type = $_FILES[$fileInput][type];
                    if (!in_array($file_type, $image_types)){ die("Invalid File Type"); }
                    $title = $_FILES[$fileInput][name];
                    $title = str_replace("(", "", $title);
                    $title = str_replace(")", "", $title);
                    $title = str_replace(" ", "_", $title);
                    $file_size = $_FILES[$fileInput][size];
                    $file = $_FILES[$fileInput][tmp_name];

                    $file_name = time() . $i . $ext[$file_type];
                    $filesend =  "../images/" . $file_name;
                    copy($file, $filesend);
                    mysql_query("insert into images (title, file, size) values ('$title', '$file_name', '$file_size')");
                    $image_id = mysql_insert_id();
                    $this->core->addLog("Image Created", $this->userinfo[0], $image_id);
                }
                else {
                    break;
                }
            }
            if ($_POST['more'] == 1){ header("Location: images.php?action=create&more=1"); }
            else { header("Location: images.php"); }
        }
        else {

            $text = "<table class=outline border=0 cellpadding=0 cellspacing=1 width=500 align=center>";
            $text .= "<tr>";
            $text .= "<td class=title colspan=2>&nbsp; Add Image</td>";
            $text .= "</tr>";
            $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
            $text .= "<form method=post action=images.php?action=create enctype='multipart/form-data' onsubmit=\"return validateForm(this)\">";
            $text .= "<input type=hidden name=confirm value=1>";
            $text .= "<tr><td class=mainbold>File 1: </td><td class=main><input type=file name=file1 size=40></td></tr>";
            $text .= "<tr><td class=mainbold>File 2: </td><td class=main><input type=file name=file2 size=40></td></tr>";
            $text .= "<tr><td class=mainbold>File 3: </td><td class=main><input type=file name=file3 size=40></td></tr>";
            $text .= "<tr><td class=mainbold>File 4: </td><td class=main><input type=file name=file4 size=40></td></tr>";
            $text .= "<tr><td class=mainbold>File 5: </td><td class=main><input type=file name=file5 size=40></td></tr>";
            if ($_GET['more'] != 1){ $text .= "<tr><td class=mainbold>And: </td><td class=main><input type=radio name=more value=0 checked>List Files <input type=radio name=more value=1>Add More Images</td></tr>"; }
            else { $text .= "<tr><td class=mainbold>And: </td><td class=main><input type=radio name=more value=0>List Files <input type=radio name=more value=1 checked>Add More Images</td></tr>"; }
            $text .= "<tr><td class=main align=center colspan=2><input type=submit value='Add File(s)'></td></form></tr></table>";
            $text .= "</td></tr></table>";
            $text .= "<script Language=JavaScript>";
            $text .= "function validateForm(theForm)";
            $text .= "{";
            $text .= "    if (!validRequired(theForm.file1,\"File 1\"))";
            $text .= "        return false;";
            $text .= "    return true;";
            $text .= "}";
            $text .= "</script>";
            $this->body = $text;
        }
    }

    function deleteImage(){
        list($file, $title) = mysql_fetch_row(mysql_query("select file, title from images where image_id = '$this->image_id'"));
        $size = getImageSize("../images/$file");
        unlink("../images/$file");
        mysql_query("delete from images where image_id = '$this->image_id'");
        $check = "<img src=images/$file $size[3] alt=\'$title\'>";
        $result = mysql_query("select page_id, body from pages where body like '$check'") or die(mysql_error());
        $count = mysql_num_rows($result);
        if ($count != 0){
            while(list($page_id, $body) = mysql_fetch_row($result)){
                $body = str_replace($check, '', $body);
                mysql_query("update pages set body = '$body' where page_id = '$page_id'");
            }
        }
        $this->core->addLog("Image Deleted", $this->userinfo[0], $this->image_id);
        header("Location: images.php");
    }

    function listImages(){
        $text = "<table class=outline border=0 cellpadding=0 cellspacing=1 width=100%>";
        $text .= "<tr>";
        $text .= "<td class=title colspan=2>&nbsp; List Images</td>";
        $text .= "</tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=2 cellspacing=0 width=100%>";
        $text .= "<tr><td class=mainbold>Title</td><td class=mainbold>Size</td><td class=mainbold>Thumb</td><td class=mainbold>Actions</tr>";
        $result = mysql_query("select image_id, title, file, size from images order by title asc") or die(mysql_error());
        while(list($image_id, $title, $file, $size) = mysql_fetch_row($result)){

            $text .=  "<tr <tr onMouseOver=\"style.backgroundColor='#B5C1DC';\" onmouseout=\"style.backgroundColor='#FFFFFF';\" bgcolor=#ffffff>";
            $text .= "<td class=hover valign=top>$title</td>";
            $text .= "<td class=hover valign=top>$size</td>";
            $text .= "<td class=hover><img src=../images/$file width=45 alt='' border=0></td>";
            $text .= "<td class=hover><a href=images.php?action=view&image_id=$image_id><img src=images/_page.gif alt=View border=0></a>";
            if ($this->insert == 1){ $text .= " <a href=javascript:insertImage('images/$file','$title')><img src=images/_block.gif alt=Insert border=0></a>"; }
            else { $text .= " <a href=javascript:confirmDelete('images.php?action=delete&image_id=$image_id')><img src=images/bdelete.gif alt='Delete' border=0></a>"; }
            $text .= "</td></tr>";
        }
        $text .= "</table></td></tr></table>";
        $this->core->addLog("List Images", $userinfo[0], '');
        $this->body = $text;
    }

    function viewImage(){
        list($title, $file) = mysql_fetch_row(mysql_query("select title, file from images where image_id = '$this->image_id'"));
        $size = getImageSize("../images/$file");
        $text = "<table border=0 cellpadding=0 cellspacing=1 width=100%>";
        $text .= "<tr>";
        $text .= "<td class=title>$title</td>";
        $text .= "</tr>";
        $text .= "<tr><td class=main><table border=0 cellpadding=0 cellspacing=0>";
        $text .= "<tr><td class=main align=center>";
        $text .= "<img src=../images/$file alt='$title' $size[3] border=0>";
        $text .= "</td></tr></table></td></tr></table>";
        $this->body = $text;
    }
}



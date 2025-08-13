<?
include "config.php";
include "db/db.php";
db_options();
include "languages/$language.php";
include "style/" . $option['style'] . ".php";
if($action == ""){
echo $lang['comments'] . ":<br><br>";

                $wyn = "SELECT * FROM toplista_comments WHERE siteid='$siteid'";
                $wykonaj = mysql_query($wyn);
                $znaleziono = mysql_num_rows($wykonaj);
                if($znaleziono == "0"){
                echo $lang['nocomments'] . "<br><br>";
                } else {
                while($tab = mysql_fetch_array($wykonaj)) for($i=0;$i<count($znaleziono);$i++){
                echo $tab['comment'] . "<br><br><p align=right><a href=\"mailto:". $tab['email'] . "\">" . $tab['nick'] . "</a> - " . $lang['posted'] . ": ". $tab['data'] . "</p><br><br>";
                }
        }
                echo "<br>" . $lang['postacomment'] . ":<br><br><form method=post action=comments.php?action=post&siteid=$siteid>" . $lang['nick'] . ":<br><input type=text name=nick><br>E-mail:<br><input type=text name=email><br>" . $lang['comment'] . ":<br><textarea name=comment rows=10 cols=20></textarea><br><br><input type=submit value=" . $lang['submit'] . "></form>";
}
                if($action == "post"){
                        if($nick == ""){
                                die($lang['error_forgot']);
                        }
                        if($email == ""){
                                $email = "noone@nowhere.com ;-)";
                        }
                        if($comment == ""){
                                die($lang['error_forgot']);
                        }

                $date = date("Y-m-d H:i:s");
                $sql = mysql_query("INSERT INTO toplista_comments VALUES('$nick', '$email','$date','$comment','','$siteid')") or die('Database error');
                echo "<br><br>Comment added";
                echo "<meta http-equiv=\"refresh\" content=\"1; url=comments.php?siteid=" . $siteid . "\">";
                }
?>

<?
$db_handler->sql_query("UPDATE $sql_table[screens] SET views=views+1 WHERE screen_id='$screen_id'");
$screen = $db_handler->sql_fetch_array($db_handler->sql_query("SELECT * FROM $sql_table[screens] WHERE screen_id='$screen_id'"));

echo "
<center>
<a href=\"$settings[script_file]release_id=$screen[release_id]\"><img src=\"pdl-gfx/screens/release".$screen[release_id]."screen".$screen_id."g.jpg\" border=\"0\"></a><br>
".stripslashes($screen[text])."<br>
Screen wurde $screen[views] mal Angeschaut
</center>";
?>

<table width="100%" border="0">
  <tr>
    <td width="50%" valign="top">
<?
$tables = 0;
$tables_res = $db_handler->sql_query("SHOW TABLE STATUS");
while($tables_row = $db_handler->sql_fetch_array($tables_res))
 {
  $tables++;
  $size+= $tables_row[Data_length]+$tables_row[Index_length];
  $rows+= $tables_row[Rows];
 }

$mysqlversion = $db_handler->sql_fetch_array($db_handler->sql_query("SHOW VARIABLES LIKE 'version'"));
$mysqlversion = $mysqlversion[1];
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td bgcolor="<? echo $template[table_border]; ?>">
      <table border="0" cellpadding="3" cellspacing="1" width="100%">
        <tr>
          <td bgcolor="<? echo $template[header_bg]; ?>" colspan="2" align="center">
            <b>Server & DB Stats</b>
          </td>
        </tr>
        <? $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <b>DB Version</b>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $mysqlversion; ?>
          </td>
        </tr>
        <? $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <b>DB Größe</b>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo round($size/1024/1024,2); ?> MB
          </td>
        </tr>
        <? $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <b>Tabellen in der DB</b>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $tables; ?>
          </td>
        </tr>
        <? $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <b>DB Einträge</b>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $rows; ?>
          </td>
        </tr>
        <? $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <b>Server Software</b>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $_SERVER['SERVER_SOFTWARE']; ?>
          </td>
        </tr>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>" colspan="2">
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br><br>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td bgcolor="<? echo $template[table_border]; ?>">
      <table border="0" cellpadding="3" cellspacing="1" width="100%">
        <tr>
          <td bgcolor="<? echo $template[header_bg]; ?>" colspan="2" align="center">
            <b>User & Gruppen</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Usergruppe</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>User</b>
          </td>
        </tr>
<?
$ugroup_res = $db_handler->sql_query("SELECT $sql_table[usergroup].name AS ugroup_name, COUNT($sql_table[user].user_id) AS ugroup_user FROM $sql_table[usergroup], $sql_table[user] WHERE $sql_table[user].ugroup_id = $sql_table[usergroup].ugroup_id AND $sql_table[usergroup].ugroup_id != '3' GROUP BY $sql_table[user].ugroup_id");
while($ugroup_row = $db_handler->sql_fetch_array($ugroup_res))
 {
  $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $ugroup_row[ugroup_name]; ?>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $ugroup_row[ugroup_user]; ?>
          </td>
        </tr>
<? } ?>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>" colspan="2">
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br><br>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td bgcolor="<? echo $template[table_border]; ?>">
      <table border="0" cellpadding="3" cellspacing="1" width="100%">
        <tr>
          <td bgcolor="<? echo $template[header_bg]; ?>" colspan="3" align="center">
            <b>Top 10 Kommentare Poster</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>#</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Nick</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Kommentare</b>
          </td>
        </tr>
<?
$user_res = $db_handler->sql_query("SELECT $sql_table[user].user_id, COUNT($sql_table[comments].comment_id) AS kommentare FROM $sql_table[user], $sql_table[comments] WHERE $sql_table[user].user_id = $sql_table[comments].user_id GROUP BY $sql_table[user].user_id ORDER BY kommentare DESC LIMIT 0,10");
$count = 0;
while($user_row = $db_handler->sql_fetch_array($user_res))
 {
  $count++;
  $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $count; ?>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo user($user_row[user_id]); ?>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $user_row[kommentare]; ?>
          </td>
        </tr>
<? } ?>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>" colspan="3">
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br><br>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td bgcolor="<? echo $template[table_border]; ?>">
      <table border="0" cellpadding="3" cellspacing="1" width="100%">
        <tr>
          <td bgcolor="<? echo $template[header_bg]; ?>" colspan="3" align="center">
            <b>Top 10 Uploader</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>#</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Nick</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Uploads</b>
          </td>
        </tr>
<?
$user_res = $db_handler->sql_query("SELECT $sql_table[user].user_id, COUNT($sql_table[release].release_id) AS release FROM $sql_table[user], $sql_table[release] WHERE $sql_table[user].user_id = $sql_table[release].uploader GROUP BY $sql_table[user].user_id ORDER BY release DESC LIMIT 0,10");
$count = 0;
while($user_row = $db_handler->sql_fetch_array($user_res))
 {
  $count++;
  $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $count; ?>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo user($user_row[user_id]); ?>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $user_row[release]; ?>
          </td>
        </tr>
<? } ?>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>" colspan="3">
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br><br>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td bgcolor="<? echo $template[table_border]; ?>">
      <table border="0" cellpadding="3" cellspacing="1" width="100%">
        <tr>
          <td bgcolor="<? echo $template[header_bg]; ?>" colspan="3" align="center">
            <b>Top 10 Ordner</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>#</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Name</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Release</b>
          </td>
        </tr>
<?
$ordner_res = $db_handler->sql_query("SELECT $sql_table[ordner].ordner_id, $sql_table[ordner].name, COUNT($sql_table[release].release_id) AS release FROM $sql_table[ordner], $sql_table[release] WHERE $sql_table[ordner].ordner_id = $sql_table[release].ordner_id GROUP BY $sql_table[release].ordner_id ORDER BY release DESC LIMIT 0,10");
$count = 0;
while($ordner_row = $db_handler->sql_fetch_array($ordner_res))
 {
  $count++;
  $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $count; ?>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <a href="<? echo $settings[script_file]; ?>ordner_id=<? echo $ordner_row[ordner_id]; ?>"><? echo stripslashes($ordner_row[name]); ?></a>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $ordner_row[release]; ?>
          </td>
        </tr>
<? } ?>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>" colspan="3">
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
    </td>
    <td width="50%" valign="top">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td bgcolor="<? echo $template[table_border]; ?>">
      <table border="0" cellpadding="3" cellspacing="1" width="100%">
        <tr>
          <td bgcolor="<? echo $template[header_bg]; ?>" colspan="3" align="center">
            <b>Top 10 Release nach Größe</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>#</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Release</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Größe</b>
          </td>
        </tr>
<?
$release_res = $db_handler->sql_query("SELECT $sql_table[release].release_id, $sql_table[release].name, SUM($sql_table[files].size) AS size FROM $sql_table[release], $sql_table[files] WHERE $sql_table[release].release_id = $sql_table[files].release_id GROUP BY $sql_table[release].release_id ORDER BY size DESC LIMIT 0,10");
$count = 0;
while($release_row = $db_handler->sql_fetch_array($release_res))
 {
  $count++;
  $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $count; ?>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <a href="<? echo $settings[script_file]; ?>release_id=<? echo $release_row[release_id]; ?>"><? echo stripslashes($release_row[name]); ?></a>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo size($release_row[size]); ?>
          </td>
        </tr>
<? } ?>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>" colspan="3">
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br><br>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td bgcolor="<? echo $template[table_border]; ?>">
      <table border="0" cellpadding="3" cellspacing="1" width="100%">
        <tr>
          <td bgcolor="<? echo $template[header_bg]; ?>" colspan="3" align="center">
            <b>Top 10 Release nach Files</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>#</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Release</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Files</b>
          </td>
        </tr>
<?
$release_res = $db_handler->sql_query("SELECT $sql_table[release].release_id, $sql_table[release].name, COUNT($sql_table[files].file_id) AS files FROM $sql_table[release], $sql_table[files] WHERE $sql_table[release].release_id = $sql_table[files].release_id GROUP BY $sql_table[release].release_id ORDER BY files DESC LIMIT 0,10");
$count = 0;
while($release_row = $db_handler->sql_fetch_array($release_res))
 {
  $count++;
  $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $count; ?>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <a href="<? echo $settings[script_file]; ?>release_id=<? echo $release_row[release_id]; ?>"><? echo stripslashes($release_row[name]); ?></a>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $release_row[files]; ?>
          </td>
        </tr>
<? } ?>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>" colspan="3">
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br><br>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td bgcolor="<? echo $template[table_border]; ?>">
      <table border="0" cellpadding="3" cellspacing="1" width="100%">
        <tr>
          <td bgcolor="<? echo $template[header_bg]; ?>" colspan="3" align="center">
            <b>Top 10 Release nach Kommentaren</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>#</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Release</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Kommentare</b>
          </td>
        </tr>
<?
$release_res = $db_handler->sql_query("SELECT $sql_table[release].release_id, $sql_table[release].name, COUNT($sql_table[comments].comment_id) AS comments FROM $sql_table[release], $sql_table[comments] WHERE $sql_table[release].release_id = $sql_table[comments].release_id GROUP BY $sql_table[release].release_id ORDER BY comments DESC LIMIT 0,10");
$count = 0;
while($release_row = $db_handler->sql_fetch_array($release_res))
 {
  $count++;
  $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $count; ?>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <a href="<? echo $settings[script_file]; ?>release_id=<? echo $release_row[release_id]; ?>"><? echo stripslashes($release_row[name]); ?></a>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $release_row[comments]; ?>
          </td>
        </tr>
<? } ?>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>" colspan="3">
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br><br>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td bgcolor="<? echo $template[table_border]; ?>">
      <table border="0" cellpadding="3" cellspacing="1" width="100%">
        <tr>
          <td bgcolor="<? echo $template[header_bg]; ?>" colspan="3" align="center">
            <b>Top 10 Release nach Bewertungen</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>#</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Release</b>
          </td>
          <td bgcolor="<? echo $template[footer_bg]; ?>">
            <b>Bewertungen</b>
          </td>
        </tr>
<?
$release_res = $db_handler->sql_query("SELECT * FROM $sql_table[release] ORDER BY votes DESC LIMIT 0,10");
$count = 0;
while($release_row = $db_handler->sql_fetch_array($release_res))
 {
  $count++;
  $alt = alt_switch(); ?>
        <tr>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $count; ?>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <a href="<? echo $settings[script_file]; ?>release_id=<? echo $release_row[release_id]; ?>"><? echo stripslashes($release_row[name]); ?></a>
          </td>
          <td bgcolor="<? echo $alt; ?>">
            <? echo $release_row[votes]; ?>
          </td>
        </tr>
<? } ?>
        <tr>
          <td bgcolor="<? echo $template[footer_bg]; ?>" colspan="3">
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
    </td>
  </tr>
</table>

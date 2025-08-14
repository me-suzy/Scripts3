<?
/* mod.guests.php          version 19/jan/2002 */
/* copyright © 2001 Y0Gi <webmaster@nwsnet.de> */

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330,
Boston, MA 02111-1307 USA
*/

class guests
{
  /* mysql data */
  var $db_host  = "localhost";
  var $db_user  = "username";
  var $db_pass  = "password";
  var $db_name  = "database";
  var $db_tbl   = "guests";


  /* will be filled in constructor */
  var $fast_mode;
  var $color_line;
  var $status;
  var $url;


  /* constructor - executed on object creation */
  function guests()
  {
    // set to 1 to skip confirmation on status change
    $this->fast_mode = 0;

    // every second row will be filled with this color
    $this->color_line = "#e4c494";

    // status labels and colors
    $this->status[0]["label"] = "angemeldet";
    $this->status[0]["color"] = "#aa000";
    $this->status[1]["label"] = "bezahlt";
    $this->status[1]["color"] = "#006600";
    $this->status[2]["label"] = "platz reserviert";
    $this->status[2]["color"] = "#000088";
    $this->status[3]["label"] = "eingecheckt";
    $this->status[3]["color"] = "#444444";

    // links    
    $this->url["add"]     = "mod.guests-example-add.php";
    $this->url["list"]    = "mod.guests-example-list.php";
    $this->url["checkin"] = "mod.guests-example-checkin.php";
    $this->url["admin"]   = "mod.guests-example-admin.php";

    // connect to mysql and select database
    $link = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
    mysql_select_db($this->db_name, $link);
  }


  /* general list */
  function show_list($type="")
  {
    $result = mysql_query("SELECT * FROM " . $this->db_tbl . " ORDER BY id");

    if ($type == "admin")
      echo "<form action=\"" . $SCRIPT_NAME . "\" method=\"post\">\n";
    echo "<table cellspacing=0 cellpadding=2 border=0>\n";
    echo "  <tr>\n";
    echo "    <td><b>ID</b></td>\n";
    echo "    <td width=140><b>Nick</b></td>\n";
    echo "    <td width=180><b>Clan</b></td>\n";
    echo "    <td width=100><b>Status</b></td>\n";
    if ($type == "checkin")
      echo "    <td width=80>&nbsp;</td>\n";
    if ($type == "admin")
      echo "    <td colspan=4><b>&auml;ndern in</b></td>\n";
    echo "  </tr>\n";

    $line = 1;
    while ($row = mysql_fetch_assoc($result)) {
      if ($line % 2 == 1)
        echo "  <tr bgcolor=" . $this->color_line . ">\n";
      else
        echo "  <tr>\n";

      echo "    <td>" . $row["id"] . "</td>\n";
      echo "    <td>" . $row["nick"] . "</td>\n";
      echo "    <td>" . $row["clan"] . "</td>\n";

      for ($i = 0; $i < 4; $i++)
        if ($row["status"] == $i)
          echo "    <td><font color=" . $this->status[$i]["color"] . ">" . $this->status[$i]["label"] . "</font></td>\n";

      // additional field for check-in list
      if ($type == "checkin") {
        echo "    <td><b>";
        if ($row["status"] < 3)
          // needs two &'s to show one... mail me if you can tell me why... change if necessary
          echo "<a href=\"" . $this->url["checkin"] . "?id=" . $row["id"] . "&&status=3" . "\">einchecken</a>";
        echo "&nbsp;</b></td>\n";
      }

      // additional field for admin list
      if ($type == "admin") {
        for ($i = 0; $i < 4; $i++) {
          echo "    <td><b>\n";
          if ($row["status"] != $i) {
            // needs two &'s to show one... mail me if you can tell me why... change if necessary
            $link = $this->url["admin"] . "?id=" . $row["id"] . "&&status=" . $i;
            echo "<a href=\"" . $link . "\"><font color=" . $this->status[$i]["color"] . ">" . substr($this->status[$i]["label"], 0, 1) . "</font></a>\n";
          }
          else
            echo "&middot;";
          echo "&nbsp;</b></td>\n";
        }
      }

      echo "  </tr>\n";
      $line++;
    }
    echo "</table>\n";
    echo "<br><br>" . $this->copyright . "<br>\n";
    if ($type == "admin")
      echo "</form>\n";
  }


  /* set status */
  function set_status($id, $status, $type)
  {
    $result = mysql_query("UPDATE " . $this->db_tbl . " SET status=" . $status . " WHERE id=" . $id);
/*
    $result = mysql_query("SELECT * FROM " . $this->db_tbl . " WHERE id=" . $id);
    while ($row = mysql_fetch_assoc($result))
      echo "<b>" . $row["nick"] . "</b> hat nun den Status <b><font color=" . $this->status[$row["status"]]["color"] . ">" . $this->status[$row["status"]]["label"] . "</font></b>.<br>\n";
    echo "<br><a href=\"" . $this->url[$type] . "\">Liste anzeigen</a><br>\n";
    if ($this->fast_mode == 1)
      echo "<script language=\"javascript\"><!-- document.location.href='" . $this->url[$type] . "'; --></script>\n";
*/

    if ($this->fast_mode == 0) {
      $result = mysql_query("SELECT * FROM " . $this->db_tbl . " WHERE id=" . $id);
      while ($row = mysql_fetch_assoc($result))
        echo "<b>" . $row["nick"] . "</b> hat nun den Status <b><font color=" . $this->status[$row["status"]]["color"] . ">" . $this->status[$row["status"]]["label"] . "</font></b>.<br>\n";
      echo "<br><a href=\"" . $this->url[$type] . "\">Liste anzeigen</a><br>\n";
    }
    else
      $this->show_list($type);

/*
    if ($this->fast_mode == 0) {
      $result = mysql_query("SELECT * FROM " . $this->db_tbl . " WHERE id=" . $id);
      while ($row = mysql_fetch_assoc($result))
        echo "<b>" . $row["nick"] . "</b> hat nun den Status <b><font color=" . $this->status[$row["status"]]["color"] . ">" . $this->status[$row["status"]]["label"] . "</font></b>.<br>\n";
      echo "<br><a href=\"" . $this->url[$type] . "\">Liste anzeigen</a><br>\n";
    }
    else {
      header("Location: " . $this->url[$type]);
      exit;
    }
*/
  }


  /* number of guests */
  function num($status=9)
  {
    $query = "SELECT * FROM " . $this->db_tbl;
    if ($status >= 0 && $status <= 3)
      $query .= " WHERE status=" . $status;
    $result = mysql_query($query);
    return mysql_num_rows($result);
  }


  /* add form */
  function add_form() {
?>

<form action="<?= $this->url["add"]; ?>" method="post">
<br><table cellspacing=0 cellpadding=2 border=0>
  <tr>
    <td><u>Name</u>:</td>
    <td><input type=text name="name" size=16 maxlength=20></td>
  </tr>
  <tr>
    <td><u>EMail</u>:</td>
    <td><input type=text name="email" size=16 maxlength=40></td>
  </tr>
  <tr>
    <td><u>Nick</u>:</td>
    <td><input type=text name="nick" size=16 maxlength=20></td>
  </tr>
  <tr>
    <td><u>Clan</u>:</td>
    <td><input type=text name="clan" size=16 maxlength=40></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align=center><br><input type=submit value="Anmelden"></td>
  </tr>
</table>
<br><?= $this->copyright; ?>
</form>

<?
  }


  /* add process */
  function add_res($name, $email, $nick, $clan) {
    $result = mysql_query("INSERT INTO " . $this->db_tbl . " (name, email, nick, clan, time, ip) VALUES ('$name', '$email', '$nick', '$clan', '" . date("d_m_Y H:i") . "', '" . getenv("REMOTE_ADDR") . "')");
    echo "Sch&ouml;n, dass du dich angemeldet hast, <b>" . $nick . "</b>!<br>\n";
    echo "Du wurdest in die G&auml;steliste aufgenommen.<br>\n";
    echo "<br><a href=\"" . $this->url["list"] . "\">G&auml;steliste anzeigen</a><br>\n";
  }


  /* please do not remove this copyright - this is gpl software (and it was hard work, too...) */
  var $copyright = '<span style="font-size: 8pt">copyright &copy; 2001 jochen kupperschmidt aka Y0Gi</span>';
}
?>
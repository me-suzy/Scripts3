<?
  require('lib/config.inc');
  require('lib/auth.inc');
  require('lib/classes.inc');
  require('lib/functions.inc');

  $user = new user($login);

  function list_messages() {
    $result = @mysql_query("SELECT c.id AS id,u.name AS user,u.email AS email,c.subject AS subject,c.ref_id AS ref,DATE_FORMAT(c.date, \"%W, %e %M %Y\") AS date FROM chat AS c LEFT JOIN users AS u ON c.user=u.id ORDER BY id DESC LIMIT 50" );
    if( ($num = @mysql_num_rows( $result )) == 1) {
        echo "<h2>Message Board: $num message</h2>\n";
    } else {
        echo "<h2>Message Board: $num messages</h2>\n";
    }

    echo "<p><a href=\"message.php?action=post\">Post</a> a new message";
    if($num)
      echo " or click on any of the subject lines below to read.\n<br><br>\n";
    else
      echo ".<br><br>\n";

    while( $row = @mysql_fetch_array($result) ) {
        if($row[subject])
            if($row[ref])
                echo "$row[id] <a href=\"message.php?action=read&mid=$row[id]\">Re: ". stripslashes($row[subject]) ."</a> by $row[user] &lt;<a href=\"mailto:$row[email]\">$row[email]</a>&gt; on $row[date]<br>\n";
            else
                echo "$row[id] <a href=\"message.php?action=read&mid=$row[id]\">". stripslashes($row[subject]) ."</a> by $row[user] &lt;<a href=\"mailto:$row[email]\">$row[email]</a>&gt; on $row[date]<br>\n";
        else
            if($row[ref])
                echo "$row[id] <a href=\"message.php?action=read&mid=$row[id]\">Re: No subject</a> by $row[user] &lt;<a href=\"mailto:$row[email]\">$row[email]</a>&gt; on $row[date]<br>\n";
            else
                echo "$row[id] <a href=\"message.php?action=read&mid=$row[id]\">No subject</a> by $row[user] &lt;<a href=\"mailto:$row[email]\">$row[email]</a>&gt; on $row[date]<br>\n";
    }
    return;
  }

  function show_message( $mid ) {

    $result = @mysql_query("SELECT c.id AS id,c.ref_id AS ref,u.name AS user,u.email AS email,c.subject AS subject,c.content AS content,DATE_FORMAT(c.date, \"%W, %e %M %Y\") AS date FROM chat AS c LEFT JOIN users AS u ON c.user=u.id WHERE c.id=$mid");
    $row = @mysql_fetch_array($result);

    echo "<h2>Message $row[id] by $row[user]</h2>\n";

    echo "<table border=\"0\" width=\"500\">\n";
    echo "<tr>\n<td>\n";

    echo "<p>Date: <b>$row[date]</b>\n";
    echo "<br>From: <b>$row[user]</b> &lt;<a href=\"mailto:$row[email]\">$row[email]</a>&gt;\n";
    echo "<br>Subject: <b>". stripslashes( $row[subject] ) ."</b>\n";

    if($row[ref]) {
        $result = @mysql_query("SELECT c.id AS id,u.name AS user,c.subject AS subject,DATE_FORMAT(c.date, \"%W, %e %M %Y\") AS date FROM chat AS c LEFT JOIN users AS u ON c.user=u.id WHERE c.id=$row[ref]");
        $ref = @mysql_fetch_array( $result );
        echo "<br>In reply to: <a href=\"message.php?action=read&mid=$ref[id]\">". stripslashes($ref[subject]) ."</a> by $ref[user] on $ref[date]\n";
    }

    echo "<p>". nl2br( htmlentities( stripslashes($row[content]) )) ."\n";
    echo "<p><a href=\"message.php?action=reply&reply=$row[id]\">Reply</a> <b>|</b> <a href=\"message.php?action=post\">Post New</a>\n";

    $res = @mysql_query("SELECT id FROM chat WHERE id<$row[id]");
    if( mysql_num_rows($res) )
        echo " <b>|</b> <a href=\"message.php?action=read&mid=". ($row[id]-1) ."\">Previous</a>\n";

    $res = @mysql_query("SELECT id FROM chat WHERE id>$row[id]");
    if( mysql_num_rows($res) )
        echo " <b>|</b> <a href=\"message.php?action=read&mid=". ($row[id]+1) ."\">Next</a>\n";

    echo " <b>|</b> <a href=\"message.php\">Index</a>\n";

    echo "</td>\n<td class=\"topright\" width=\"150\">\n";
    echo "</td>\n</tr>\n</table>\n";

    return;
  }

  function new_message( $mid ) {

    if( $id > 0)
        echo "<h2>Message Board - Reply to message #$mid</h2>\n";
    else
        echo "<h2>Message Board - Enter a message</h2>\n";

    echo "<form action=\"message.php?action=save\" method=\"post\">\n";

    echo "<input type=\"hidden\" value=\"$mid\" name=\"ref\">\n";
    if($id) {
       $res = @mysql_query("SELECT subject FROM chat WHERE id=$mid");
       $row = @mysql_fetch_array($res);
       echo "<p>Subject: <b>Re: ". stripslashes($row[subject]) ."</b>\n";
       echo "<input type=\"hidden\" name=\"subject\" value=\"". stripslashes($row[subject]) ."\">\n";
    } else {
        echo "<p>Subject:<br><input type=\"text\" size=\"32\" maxsize=\"128\" name=\"subject\">\n";
    }
    echo "<p>Message:<br><textarea cols=\"40\" rows=\"12\" name=\"content\"></textarea>\n";
    echo "<p><input type=\"submit\" value=\"Post message\"> <input type=\"reset\" value=\"Clear message\">\n";

    echo "</form>\n";

    return;
  }

  function save_message( $ref, $subject, $content ) {
    global $user;

    if($ref)
        $query = "INSERT INTO chat(ref_id,user,subject,content,date) values($ref,$user->id,'". addslashes($subject) ."','". addslashes($content) ."',NOW())";
    else
        $query = "INSERT INTO chat(user,subject,content,date) values($user->id,'". addslashes($subject) ."','". addslashes($content) ."',NOW())";
    $result = @mysql_query( $query );
    if($result != -1) {
        echo "<h2>Error ". mysql_errno() .": ". mysql_error() ."</h2>\n";
        echo "<p>$query\n";
    } else {
        $result = @mysql_query("SELECT LAST_INSERT_ID() FROM chat LIMIT 1");
        $row = @mysql_fetch_array($result);
        echo "<h2>Your message was posted successfully</h2>\n";
        echo "<p>You can <a href=\"message.php?action=read&mid=$row[0]\">read it</a> or go to the <a href=\"message.php\">message index.\n";
    }
    return;
  }

  global $action, $mid, $reply, $ref, $subject, $content;

  print_header("Message Board");

  neutral_table_start("center", 0, 0);
  echo "<tr>\n";
  echo "<td>\n";

  switch($action) {

    case "read":
      show_message( $mid );
      break;

    case "post":
      new_message( 0 );
      break;

    case "reply":
      new_message( $reply );
      break;

    case"save":
      save_message( $ref, $subject, $content );
      break;

    default:
      list_messages();
      break;
  }

  echo "</td>\n";
  echo "</tr>\n";
  table_end();

  print_footer();

?>

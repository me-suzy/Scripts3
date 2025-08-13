<?
  require('lib/config.inc');
  require('lib/auth.inc');
  require('lib/classes.inc');
  require('lib/functions.inc');

  $user = new user($login);
  $contact = new user($contact);
  $contact->load_address();

  print_header("Contact Information for $contact->name");

  neutral_table_start("center", 1, 0);

  echo "<tr>\n";
    echo "<th colspan=\"2\">Contact Information for $contact->name</th>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>Name:</td>\n";
    echo "<td>$contact->name</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>Email:</td>\n";
    echo "<td>$contact->email</td>\n";
  echo "</tr>\n";
  if($contact->phone) {
    echo "<tr>\n";
      echo "<td>Phone:</td>\n";
      echo "<td>$contact->phone</td>\n";
    echo "</tr>\n";
  }
  if($contact->fax) {
    echo "<tr>\n";
      echo "<td>Fax:</td>\n";
      echo "<td>$contact->fax</td>\n";
    echo "</tr>\n";
  }
  if($contact->mobile) {
    echo "<tr>\n";
      echo "<td>Mobile:</td>\n";
      echo "<td>$contact->mobile</td>\n";
    echo "</tr>\n";
  }

  if($contact->addr_1) {
    echo "<tr>\n";
      echo "<td colspan=\"2\"> </td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
      echo "<td valign=\"top\">Address:</td>\n";
      echo "<td>\n";
        echo "$contact->addr_1<br>\n";
        if($contact->addr_2)
          echo "$contact->addr_2<br>\n";
        echo "$contact->city, $contact->state $contact->postcode<br>\n";
      echo "</td>\n";
    echo "</tr>\n";
  }

  table_end();

  print_footer()

?>

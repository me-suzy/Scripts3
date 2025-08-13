<?
    require('lib/config.inc');
    // require('lib/auth.inc');
    require('lib/classes.inc');
    require('lib/functions.inc');

    // $user = new user($login);

    print_login_header("Login");

    echo "<div align=\"center\">\n";
    echo "<img src=\"pix/home.gif\" height=\"250\" width=\"500\" alt=\"[ $cfg[site_name] Document Management ]\">\n";
    echo "<form action=\"login.php\" method=\"post\">\n";
    echo "<table border=\"0\">\n";
    echo "<tr>\n";
    echo "<td align=\"right\"><b>Username:</b></td>\n";
    echo "<td align=\"right\"><input type=\"text\" name=\"login\" size=\"12\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td align=\"right\"><b>Password:</b></td>\n";
    echo "<td align=\"right\"><input type=\"password\" name=\"pass\" size=\"12\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td align=\"center\" colspan=\"2\">\n";
    echo "<input type=\"Submit\" value=\"Login\">\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>\n";
    echo "</div>\n";

    print_login_footer();
?>

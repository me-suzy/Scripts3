<?

    require('lib/config.inc');
    require('lib/auth.inc');
    require('lib/classes.inc');
    require('lib/functions.inc');

    $user = new user($login);

    print_header("Main");

    echo "<div align=\"center\">\n";
    echo "<img src=\"pix/home.gif\" height=\"250\" width=\"500\" alt=\"[ $cfg[site_name] Document Management ]\">\n";
    echo "</div>\n";

    print_footer();
?>

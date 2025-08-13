<?
require("admin/config/general.inc.php");
require("admin/db.php");

?>
         <p><b>PASSWORD PROTECTION</b>
         <br /><small>You can now password protect your admin directory. By pushing the submit button,
         .htaccess and .htpasswd file in the admin dir will be deleted, and recreated with
         information given below. .htaccess is a file that will protect the dir with relatively high protection, and will give you a password promt when accessing the admin dir.</p>
        <p>
        <i>IMPORTANT: On Unix/Linux systems this type of protection will often be availble, but on Windows
        system, other methods of protection of the admin dir may be needed instead. Talk with your
        webhost if you are unsure about this, but always test if you have to supply correct
        username and password.</i>
        </p>
        <p>
        Your .htaccess and .htpasswd files will be created in <b><? print("$admindir"); ?></b>.</p>
         </small>


         <?
                         if (file_exists("$admindir/.htaccess"))
                        {
                                           $filemodtime = filemtime ("$admindir/.htaccess");
                                        $last_mod_hta = date("d.m.Y - H:i:s", $filemodtime);
                                        print("Your existing htaccess file was last modified $last_mod_hta<br />");

                        }
                        else
                        {
                                         print("Good, No .htaccess file exists. <br />");
                        }
                        if (file_exists("$admindir/.htpasswd"))
                        {
                                            $filemodtime = filemtime ("$admindir/.htpasswd");
                                        $last_mod_htp = date("d.m.Y - H:i:s", $filemodtime);
                                        print("Your existing htpasswd file was last modified $last_mod_htp<br />");
                        }

                        else
                        {
                                         print("Good, No .htpasswd file exists.");
                        }


         ?>
</p><p></p>

                <form method="POST" action="install.php">
                <input type="hidden" name="level" value="4">
                <p align="center">Username : <input type="text" name="username" size="20"></p>
                <p align="center">Password : <input type="text" name="password" size="20"></p>
                <p align="center"><input type="submit" value="Create passwordprotection" name="submit"></p>
                  </form>
 <?




         if ($submit)
         {





                        if (file_exists("$admindir/.htaccess"))
                        {
                                           unlink ("$admindir/.htaccess");
                        }

                        if (file_exists("$admindir/.htpasswd"))
                        {
                                    unlink ("$admindir/.htpasswd");
                        }




                        if ($username AND $password)
                         {
                                  $usernamenew = $username;
                                  $passwordnew = crypt ($password);

                                  $fd = fopen( "$admindir/.htaccess", "w+" );
                                  $fd2 = fopen( "$admindir/.htpasswd", "w+" );
                                  $str = "$usernamenew:$passwordnew";
                                  $len = strlen( $str );

                                 $str_gen = "AuthUserFile $admindir/.htpasswd\nAuthGroupFile /dev/null\nAuthName \"PHP CLASSIFIEDS\"\nAuthType Basic\n<Limit GET>\nrequire user $usernamenew\n</Limit>";
                                 $len_gen = strlen( $str_gen );
                                 fwrite( $fd, $str_gen, $len_gen );
                                 fwrite( $fd2, $str, $len );
                                  fclose( $fd );
                                 fclose( $fd2 );

                                 print("If you did not recieve any errors, NICE");
                                 print("<br /><a href='install.php?level=5'>Next step</a>.");


                        }

                        else
                        {
                                         print("No username and/or password given....");
                        }
}
?>

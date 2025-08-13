<?

include "config.php";
include "db/db.php";
db_options();
include "style/" . $option['style'] . ".php";
include "header.php";
include "languages/$language.php";


mkdir("banners/$sitename",0777);

$upload_path = "banners/$sitename/";

$extval_use = 0;

// forbidden extensions

$extval = array();

$filesize_limit_use = 1; // turns on/off size check

$filesize_limit = 40; // file size limit (in kB)








$rc = 0;



if ( isset($HTTP_POST_VARS["upload"]) ) {



   $orig_name = $HTTP_POST_FILES['userfile']['name'];



   $filename = ereg_replace("[^a-z0-9._]", "",

                ereg_replace (" ", "_",

                 ereg_replace("%20", "_",

                  strtolower($orig_name))));



   // <filesize>

   if($filesize_limit_use=1) {



      $filesize = $HTTP_POST_FILES['userfile']['name'] / 1024; //filesize in kB



      if($filesize_limit < $filesize) {

         echo "<p><font color='red'><center>"

              . $message["fileisbig"]."</font></center></p>";

         $rc = 1;

      }

   }

   // </filesize>



   if ( $rc == 0 ) {

      // <extension_validate>

      if($extval_use=1) {



         $extget = substr( strrchr($filename, "."), 1);



         $found = in_array($extget, $extval);



         if ( $found ) {

            echo "<p><font color='red'><center>"

               . $message["invext"]."</font></center></p>";



            $rc = 2;

         }

      }

      // </extension_validate>

   }



   if ( $rc == 0 ) {

      // <file exists verification>

      echo "<p><center>Uploading...</center></p>\n";



      if ( file_exists($upload_path.$filename) ) {

         echo "<p><font color='red'><center>"

            . $message["fileexists"]."</font></center></p>";



      } else {

         if( move_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'],

                   $upload_path.$filename) ) {

            echo "<p><center>" . $message["complete"]." URL to your banner: http://" . $option['siteurl'] . "/banners/$sitename/$filename :)</center></p>";

         } else {

            echo "<p><font color='red'><center>"

               . $message["incomplete"]."</font></center></p>";

         }



      }

      // </file exists verification>

   }

}

include "footer.php";
?>

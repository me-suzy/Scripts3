<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.6
// Supplied by          : Stive [WTN], CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
require("../config.php");

// ###################### Start Database #######################
$dbclassname="../db_mysql.php";
require($dbclassname);

$DB_site=new DB_Sql_vb;

$DB_site->appname="SunShop";
$DB_site->appshortname="SunShop";
$DB_site->database=$dbname;
$DB_site->server=$servername;
$DB_site->user=$dbusername;
$DB_site->password=$dbpassword;

$DB_site->connect();

// ######################## sqldumptable ####################
function sqldumptable($table) {
  global $DB_site, $dbprefix;
  $rows = $DB_site->query("SELECT * FROM ".$dbprefix."items");
  $numfields=$DB_site->num_fields($rows);
  while ($row = $DB_site->fetch_array($rows)) {
    $tabledump .= "";
    $fieldcounter=-1;
    $firstfield=1;
    while (++$fieldcounter<$numfields) {
      if (!$firstfield) {
        $tabledump.=",";
      } else {
        $firstfield=0;
      }

      if (!isset($row[$fieldcounter])) {
        $tabledump .= " ";
      } else {
        $tabledump .= $row[$fieldcounter];
      }
    }

    $tabledump .= "\n";
  }
  $DB_site->free_result($rows);
  
  return $tabledump;
}

if ($action == "export") {

   $PATH_TO = str_replace("export.php", "", $PATH_TRANSLATED);
   $filehandle=fopen($PATH_TO."products.csv","w");
   fwrite($filehandle,sqldumptable($currow[0]));
   fclose($filehandle);
   
   header("Content-disposition: filename=products.csv");
   header("Content-type: application/force-download");
   readfile("products.csv");
   unlink("products.csv");
}

?>
<?
  require('lib/config.inc');
  require('lib/auth.inc');
  require('lib/classes.inc');
  require('lib/functions.inc');

  function upload_failed($message) {
    global $userfile;

    // Trash it.
    @unlink($userfile);

    echo "<h2 align=\"center\">Error: $message</h2>\n";
    print_footer();
    exit;
  }

  $user = new user($login);

  print_header("Uploading Document");

  if(!isset($userfile))
    upload_failed("Document was not found");

  if(!file_exists($userfile))
    upload_failed("Document was not uploaded");

  $fp = fopen($userfile, "r");
  if(!$fp)
    upload_failed("Cannot open uploaded documentile");
  $content = fread($fp, $userfile_size);
  fclose($fp);
  unlink($userfile);

//  $fp = @fopen("data\\$userfile_name", "w+");
//  if(!$fp)
//    upload_failed("Cannot save uploaded document");
//  fwrite($fp, $content, $userfile_size);
//  fclose($fp);
  
  $res = @mysql_query("INSERT INTO documents(name,type,size,author,revision,created) VALUES('$userfile_name','$userfile_type',$userfile_size,$user->id,1,NOW())");

  switch( mysql_errno() ) {

    case 0:
        $doc_id = mysql_insert_id();
        @mysql_query("INSERT INTO documents_content(id,content) VALUES($doc_id,'". base64_encode($content) ."')");
        if(mysql_errno() ) {
            $error = mysql_error();
            @mysql_query("DELETE FROM documents WHERE id=$doc_id");
            upload_failed( "Index ($doc_id) succeeded, but content failed<br>Error: $error" );
        } else {
            @mysql_query("INSERT INTO documents_info(id,info) VALUES($doc_id,'". addslashes($info) ."')");
            if(mysql_errno() ) {
                $error = mysql_error();
                @mysql_query("DELETE FROM documents WHERE id=$doc_id");
                @mysql_query("DELETE FROM documents_content WHERE id=$doc_id");
                upload_failed( "Index ($doc_id) and content succeeded, but info failed<br>Error: $error" );
            }
        }
        $keywords = ereg_replace(",", " ", $keywords);
        $keywords = ereg_replace("  ", " ", $keywords);
        $keywords = explode(" ", $keywords);
        $keyword = current($keywords);
        echo "<h2 align=\"center\">Uploaded ". htmlspecialchars(stripslashes($userfile_name)) ." ($userfile_size bytes) as Document ID $doc_id</h2>\n";
        echo "<h3 align=\"center\">Using keywords: \n";
        do {
            @mysql_query("INSERT INTO documents_keywords(id,keyword) VALUES($doc_id,'". addslashes($keyword) ."')");
            if(mysql_errno())
                echo "<br>Error, $keyword not saved\n";
            else
                echo "<br>$keyword\n";
        } while ($keyword = next($keywords));
        echo "</h3>\n";
        break;

    default:
        upload_failed( mysql_error() );
        break;
  }

  print_footer()

?>

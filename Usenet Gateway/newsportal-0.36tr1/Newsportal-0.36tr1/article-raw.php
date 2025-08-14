<?
@header("Expires: ".gmdate("D, d M Y H:i:s",time()+(3600*24))." GMT");
include "config.inc.php";
  $attachment_delete_alternative=false;
  $attachment_uudecode=false;

// register parameters
$id=$_REQUEST["id"];
$group=$_REQUEST["group"];

  include "auth.inc";
  include "$file_newsportal";

  $message=message_read($id,0,$group);
  if (!$message) {
    @header ("HTTP/1.0 404 Not Found");
    $subject=$title;
    $title.=' - Article not found';
    if($ns!=false)
    nntp_close($ns);
  } else {
    $subject=htmlspecialchars($message->header->subject);
    @header("Last-Modified: ".date("r", $message->header->date));
    $title.= ' - '.$subject;
  }
  include "head.inc";
  
echo'<pre class="np_article_raw">';

  $message=message_read($id,0,$group);
  if (!$message) {
    echo $text_error["article_not_found"];
  }
  else {
    flush();
    $ns=nntp_open($server,$port);

    if ($ns) {

     $head=readPlainHeader($ns,$group,$id);
     for ($i=0; $i<count($head); $i++)
      $output.=$head[$i]."\n";
     $body=message_read($id,0,$group);
     $output.=$body->body[0];
    echo(text2html($output));
    }
    nntp_close($ns);
  }
echo"</pre>\n";
include "tail.inc";
?>
